<?php

namespace App\Services\Wp;

/**
 * Contract for resolving a WordPress media URL into the local media
 * library. Implementations: MediaDownloader (HTTP, live site) and
 * LocalMediaCopier (filesystem copy of wp-content/uploads, offline DB
 * imports).
 */
interface MediaSource
{
    /**
     * Resolve a WP media URL and store the file locally.
     *
     * @return string|null local URL, or null when resolution fails
     *                     (caller keeps the original URL).
     */
    public function download(string $url): ?string;

    /**
     * Map of every successfully stored URL in this run — consumed by
     * ContentSanitizer::rewriteImages().
     */
    public function map(): array;
}
