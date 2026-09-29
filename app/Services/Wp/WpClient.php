<?php

namespace App\Services\Wp;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Minimal read-only WordPress REST API v2 client used by the WP migration tool.
 *
 * Docs: https://developer.wordpress.org/rest-api/reference/
 */
class WpClient
{
    protected int $perPage = 50;

    public function __construct(protected string $baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * Fetch every page of a REST collection endpoint, transparently paginating
     * via the X-WP-TotalPages header until the API returns an empty page.
     */
    public function fetchAll(string $endpoint, array $query = [], ?callable $onPage = null): Collection
    {
        $items = collect();
        $page = 1;

        while (true) {
            $response = $this->request($endpoint, [...$query, 'per_page' => $this->perPage, 'page' => $page]);

            if ($response === null) {
                break;
            }

            try {
                $batch = collect($response->json() ?: []);
            } catch (Throwable $e) {
                // Body streaming can fail lazily (timeouts, truncated responses).
                Log::error('[wp:migrate] failed to decode API response', ['endpoint' => $endpoint, 'error' => $e->getMessage()]);
                break;
            }

            if ($batch->isEmpty()) {
                break;
            }

            $items = $items->merge($batch);

            if ($onPage) {
                $onPage($batch, $page);
            }

            $totalPages = (int) $response->header('X-WP-TotalPages', '0');

            if ($totalPages && $page >= $totalPages) {
                break;
            }

            $page++;
        }

        return $items;
    }

    public function posts(array $query = []): Collection
    {
        // _embed inlines author, featured media (image), categories and terms
        // so we can import without extra round-trips.
        return $this->fetchAll('wp/v2/posts', [...$query, '_embed' => 1]);
    }

    public function pages(array $query = []): Collection
    {
        return $this->fetchAll('wp/v2/pages', [...$query, '_embed' => 1]);
    }

    public function categories(): Collection
    {
        return $this->fetchAll('wp/v2/categories', ['per_page' => 100]);
    }

    public function tags(): Collection
    {
        return $this->fetchAll('wp/v2/tags', ['per_page' => 100]);
    }

    /**
     * WordPress redirects legacy attachment URLs to /wp-json/wp/v2/media/<id>.
     */
    public function media(int $id): ?array
    {
        $response = $this->request("wp/v2/media/{$id}");

        try {
            return $response?->json();
        } catch (Throwable $e) {
            Log::error('[wp:migrate] failed to decode media response', ['id' => $id, 'error' => $e->getMessage()]);

            return null;
        }
    }

    protected function request(string $endpoint, array $query = [])
    {
        $url = $this->baseUrl . '/wp-json/' . ltrim($endpoint, '/');

        try {
            $response = Http::withoutVerifying()
                ->timeout(60)
                ->connectTimeout(15)
                ->get($url, $query);
        } catch (ConnectionException $e) {
            Log::error('[wp:migrate] connection failed', ['url' => $url, 'error' => $e->getMessage()]);

            return null;
        } catch (Throwable $e) {
            Log::error('[wp:migrate] request failed', ['url' => $url, 'error' => $e->getMessage()]);

            return null;
        }

        if ($response->status() === 400 || $response->status() === 404) {
            // REST API returns 400 with rest_post_invalid_page_number when we
            // paginated past the end; treat both as "no more data".
            return null;
        }

        if ($response->failed()) {
            Log::warning('[wp:migrate] unexpected API response', [
                'url' => $url,
                'status' => $response->status(),
            ]);

            return null;
        }

        return $response;
    }
}
