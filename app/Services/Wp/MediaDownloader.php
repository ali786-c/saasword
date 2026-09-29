<?php

namespace App\Services\Wp;

use Botble\Media\Facades\RvMedia;
use Illuminate\Support\Facades\Log;

/**
 * Downloads WordPress media (featured images, in-content images) into the
 * Botble media library and returns local URLs.
 */
class MediaDownloader
{
    /**
     * Per-run cache so the same image referenced by many posts is downloaded once.
     *
     * @var array<string, string|null> old URL => local URL (null = failed)
     */
    protected array $downloaded = [];

    public function __construct(protected string $directory = 'posts')
    {
    }

    /**
     * Download a WP media URL into the local media library.
     *
     * @return string|null local URL, or null when the download fails
     *                     (caller should keep the original URL).
     */
    public function download(string $url): ?string
    {
        if (! $url || ! preg_match('#^https?://#i', $url)) {
            return null;
        }

        if (array_key_exists($url, $this->downloaded)) {
            return $this->downloaded[$url];
        }

        $result = RvMedia::uploadFromUrl($url, 0, $this->directory);

        if (! is_array($result) || ($result['error'] ?? true) || empty($result['data']->url)) {
            Log::warning('[wp:migrate] media download failed', [
                'url' => $url,
                'message' => $result['message'] ?? 'unknown error',
            ]);
            $this->downloaded[$url] = null;

            return null;
        }

        return $this->downloaded[$url] = $result['data']->url;
    }

    /**
     * Map of every successfully downloaded URL in this run — used by
     * ContentSanitizer::rewriteImages() to rewrite in-content images.
     */
    public function map(): array
    {
        return array_filter($this->downloaded);
    }
}
