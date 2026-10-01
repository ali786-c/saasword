<?php

namespace App\Services\Wp;

use Botble\Media\Facades\RvMedia;
use Illuminate\Support\Facades\Log;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Throwable;

/**
 * Drop-in replacement for MediaDownloader for database-based imports:
 * instead of downloading images over HTTP (impossible once the old
 * WordPress site is offline), it copies image files from a local copy of
 * the WP uploads directory straight into the Botble media library.
 *
 * Everything the importer resolves by URL (featured images, in-content
 * images, og:image) goes through here — the URL is translated to a path
 * inside the uploads folder and copied from disk.
 */
class LocalMediaCopier
{
    /** @var array<string, string|null> old URL => local URL (null = failed) */
    protected array $downloaded = [];

    protected string $error = '';

    public function __construct(
        protected string $uploadsPath,
        protected string $wpBaseUrl,
        protected string $directory = 'wp-import'
    ) {
        $this->uploadsPath = rtrim($this->uploadsPath, '/\\');
        $this->wpBaseUrl = rtrim($this->wpBaseUrl, '/');
    }

    public function available(): bool
    {
        return is_dir($this->uploadsPath);
    }

    public function uploadsPath(): string
    {
        return $this->uploadsPath;
    }

    public function error(): string
    {
        return $this->error;
    }

    /**
     * Total image files found under the uploads directory (informational).
     */
    public function countFiles(): int
    {
        if (! $this->available()) {
            return 0;
        }

        $count = 0;

        try {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($this->uploadsPath, RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                /** @var SplFileInfo $file */
                if ($file->isFile() && preg_match('/\.(jpe?g|png|gif|webp|avif|bmp|ico)$/i', $file->getFilename())) {
                    $count++;
                }
            }
        } catch (Throwable $e) {
            $this->error = $e->getMessage();

            return 0;
        }

        return $count;
    }

    /**
     * Same contract as MediaDownloader::download(): resolve a WP image URL
     * to a local media-library URL, copying the file from disk.
     */
    public function download(string $url): ?string
    {
        if (! $url || ! preg_match('#^https?://#i', $url)) {
            return null;
        }

        if (array_key_exists($url, $this->downloaded)) {
            return $this->downloaded[$url];
        }

        $path = $this->urlToPath($url);

        if (! $path || ! is_file($path)) {
            Log::warning('[wp:migrate:db] uploads file not found on disk', ['url' => $url]);
            $this->downloaded[$url] = null;

            return null;
        }

        $result = RvMedia::uploadFromPath($path, 0, $this->directory);

        if (! is_array($result) || ($result['error'] ?? true) || empty($result['data']->url)) {
            Log::warning('[wp:migrate:db] media copy failed', [
                'url' => $url,
                'message' => $result['message'] ?? 'unknown error',
            ]);
            $this->downloaded[$url] = null;

            return null;
        }

        return $this->downloaded[$url] = $result['data']->url;
    }

    /**
     * Map of every successfully copied URL in this run — consumed by
     * ContentSanitizer::rewriteImages().
     */
    public function map(): array
    {
        return array_filter($this->downloaded);
    }

    /**
     * Translate a WP image URL (any host variant, query strings, -scaled
     * and size renditions) to a file path inside the uploads copy.
     */
    public function urlToPath(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH);

        if (! $path) {
            return null;
        }

        $path = rawurldecode($path);

        $marker = '/uploads/';

        $pos = strrpos($path, $marker);

        if ($pos === false) {
            return null;
        }

        $relative = ltrim(substr($path, $pos + strlen($marker)), '/');

        $candidate = $this->uploadsPath.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relative);

        if (is_file($candidate)) {
            return $candidate;
        }

        // Size renditions (image-300x200.jpg) may only exist in full size;
        // try the name without the -WxH suffix before giving up.
        if (preg_match('/^(.+)-(\d+)x(\d+)(\.[a-z0-9]+)$/i', basename($candidate), $m)) {
            $fallback = dirname($candidate).DIRECTORY_SEPARATOR.$m[1].$m[4];

            if (is_file($fallback)) {
                return $fallback;
            }
        }

        return null;
    }
}
