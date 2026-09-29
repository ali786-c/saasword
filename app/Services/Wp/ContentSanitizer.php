<?php

namespace App\Services\Wp;

use Botble\Base\Facades\BaseHelper;
use Illuminate\Support\Str;

/**
 * Strips malware/injected markup from WordPress HTML content before import.
 *
 * The old site was infected, so we treat ALL imported content as untrusted:
 * - removes <script>, inline event handlers, <iframe>/<object>/<embed>
 * - drops PHP snippets and shortcodes we cannot resolve locally
 * - sanitizes the remaining HTML through HTMLPurifier (BaseHelper::clean)
 */
class ContentSanitizer
{
    public function clean(string $html): string
    {
        // 1. Drop raw PHP blocks and malicious-looking inline scripts early.
        $html = preg_replace('/<\?(php|=).+?\?>/is', '', $html) ?? '';

        // 2. Remove whole dangerous elements including their content.
        foreach (['script', 'iframe', 'object', 'embed', 'form', 'noscript', 'template'] as $tag) {
            $html = preg_replace('/<'.$tag.'\b[^>]*>.*?<\/'.$tag.'>/is', '', $html) ?? '';
            $html = preg_replace('/<'.$tag.'\b[^>]*\/?>/is', '', $html) ?? '';
        }

        // 3. Strip inline event handlers (onclick, onload, onerror, ...).
        $html = preg_replace('/\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/is', '', $html) ?? '';

        // 4. Strip javascript: / data: URLs in href/src attributes.
        $html = preg_replace('/(href|src)\s*=\s*(["\']?)\s*(javascript|data)\s*:[^"\'>\s]*(["\']?)/is', '$1="#"', $html) ?? '';

        // 5. WordPress shortcodes we cannot resolve locally (e.g. leftover
        //    plugin shortcodes) — remove them so they never render raw.
        $html = preg_replace('/\[\/[a-z0-9_\-]+\]/i', '', $html) ?? '';
        $html = preg_replace('/\[[a-z0-9_\-]+[^\]]*\]/i', '', $html) ?? '';

        // 6. HTMLPurifier as the final authority (Botble's configured policy).
        $clean = BaseHelper::clean($html);

        return is_string($clean) ? trim($clean) : '';
    }

    /**
     * Rewrite images pointing at the old WordPress host to their new local
     * media-library paths (see MediaDownloader), so content renders offline
     * from this server — and can never re-load payloads from the old host.
     */
    public function rewriteImages(string $html, array $map): string
    {
        if (! $map) {
            return $html;
        }

        // Exact URL replacements first (uploaded media paths).
        $html = strtr($html, $map);

        // Then any remaining /wp-content/uploads/ URLs by basename (query strings allowed).
        return preg_replace_callback(
            '/(src|href)\s*=\s*("|\')([^"\']*\/wp-content\/uploads\/([^"\']+))\2/i',
            function (array $m) use (&$map) {
                // Strip ?resize=... style query strings and URL-encoding first.
                $basename = explode('?', rawurldecode($m[4]))[0];

                foreach ($map as $old => $new) {
                    if (str_ends_with($old, $basename)) {
                        return $m[1].'='.$m[2].$new.$m[2];
                    }
                }

                return $m[0];
            },
            $html
        ) ?? $html;
    }

    /**
     * Extract plain text (used for description fallback and time-to-read).
     */
    public function toPlainText(string $html): string
    {
        $text = preg_replace('/<[^>]+>/', ' ', $html) ?: '';

        return trim(Str::limit(preg_replace('/\s+/u', ' ', html_entity_decode($text, ENT_QUOTES, 'UTF-8')) ?: '', 400, ''));
    }
}
