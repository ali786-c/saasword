<?php

namespace Botble\SeoBoost\Services;

use Botble\SeoBoost\Models\IndexNowLog;
use Botble\SeoBoost\Repositories\Interfaces\IndexNowLogInterface;
use Botble\Setting\Facades\Setting;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Str;

class IndexNowService
{
    public const API_ENDPOINT = 'https://api.indexnow.org/indexnow/';

    /**
     * Status codes that mean the submission was accepted.
     */
    protected const SUCCESS_CODES = [200, 202, 204];

    /**
     * Don't submit the same URL automatically more than once every 5 seconds
     * (mirrors the rapid-save throttle used by WordPress SEO plugins).
     */
    protected const THROTTLE_SECONDS = 5;

    /**
     * Keep the submission history at this many rows.
     */
    public const LOG_LIMIT = 100;

    public function __construct(protected IndexNowLogInterface $logRepository, protected ?Client $client = null)
    {
    }

    protected function httpClient(): Client
    {
        if (! $this->client) {
            $this->client = new Client([
                'timeout' => 10,
                'connect_timeout' => 5,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);
        }

        return $this->client;
    }

    public function isEnabled(): bool
    {
        $value = setting('seo_boost_enabled', true);

        return in_array($value, [true, 1, '1'], true);
    }

    public function getKey(): string
    {
        return (string) setting('seo_boost_api_key');
    }

    /**
     * Generate a fresh 32-char hex key, persist it, and return it.
     */
    public function resetKey(): string
    {
        $key = str_replace('-', '', (string) Str::uuid());

        Setting::set('seo_boost_api_key', $key)->save();

        return $key;
    }

    public function keyFileUrl(): string
    {
        return rtrim(url('/'), '/') . '/' . $this->getKey() . '.txt';
    }

    /**
     * Submit one or more URLs to the IndexNow API.
     *
     * @param array|string $urls
     */
    public function submit(array|string $urls, bool $isManual = false): bool
    {
        $urls = array_values(array_filter(array_map('trim', (array) $urls)));

        if (! $urls || ! $this->getKey()) {
            return false;
        }

        $status = 0;
        $message = '';

        try {
            $response = $this->httpClient()->post(self::API_ENDPOINT, [
                'json' => $this->buildPayload($urls),
            ]);

            $status = $response->getStatusCode();
            $message = $response->getBody()->getContents();

            if (in_array($status, self::SUCCESS_CODES, true)) {
                $message = 'OK';

                $this->log($urls, $status, $message, $isManual);

                return true;
            }
        } catch (ConnectException $exception) {
            $message = 'Connection failed: ' . $exception->getMessage();
        } catch (Exception $exception) {
            $message = $exception->getMessage();
        }

        $this->log($urls, $status, $this->mapErrorMessage($status, $message), $isManual);

        return false;
    }

    /**
     * Auto-submit entry point used by the content event listeners.
     * Enforces the per-URL throttle and only submits when enabled.
     */
    public function submitAuto(string $url): bool
    {
        if (! $this->isEnabled()) {
            return false;
        }

        if ($this->wasRecentlySubmitted($url)) {
            return false;
        }

        return $this->submit($url, false);
    }

    /**
     * Payload documented by indexnow.org:
     * {host, key, keyLocation, urlList}
     */
    public function buildPayload(array $urls): array
    {
        return [
            'host' => parse_url(url('/'), PHP_URL_HOST) ?: 'localhost',
            'key' => $this->getKey(),
            'keyLocation' => $this->keyFileUrl(),
            'urlList' => array_values($urls),
        ];
    }

    protected function wasRecentlySubmitted(string $url): bool
    {
        $latest = $this->logRepository->latestLog();

        return $latest
            && $latest->url === $url
            && $latest->created_at
            && $latest->created_at->gt(now()->subSeconds(self::THROTTLE_SECONDS));
    }

    protected function log(array $urls, int $status, string $message, bool $isManual): void
    {
        $this->logRepository->create([
            'url' => count($urls) > 1 ? $urls[0] . ' [+' . (count($urls) - 1) . ']' : $urls[0],
            'status_code' => $status,
            'message' => Str::limit($message, 480),
            'is_manual' => $isManual,
        ]);

        $this->trimLog();
    }

    /**
     * Keep only the most recent LOG_LIMIT submission records.
     */
    public function trimLog(): void
    {
        $cutoffId = IndexNowLog::query()
            ->orderByDesc('id')
            ->skip(self::LOG_LIMIT - 1)
            ->limit(1)
            ->value('id');

        if ($cutoffId) {
            IndexNowLog::query()->where('id', '<', $cutoffId)->delete();
        }
    }

    protected function mapErrorMessage(int $status, string $fallback): string
    {
        return match ($status) {
            400 => 'Invalid request.',
            403 => 'Invalid API key.',
            422 => 'Invalid URL.',
            429 => 'Too many requests.',
            500 => 'Internal server error.',
            default => $fallback ?: 'Unknown error.',
        };
    }
}
