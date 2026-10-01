<?php

namespace App\Services\Wp;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Throwable;

/**
 * Converts Elementor's `_elementor_data` postmeta (a JSON tree of
 * sections/columns/widgets) into plain, clean HTML that the Botble editor
 * can render. Layout grids are flattened in document order; unknown
 * widgets are skipped.
 *
 * Why: Elementor does NOT store the real content in wp_posts.post_content —
 * only a static snapshot lives there. The DB import mode reads postmeta
 * directly, so we can rebuild proper HTML from the widget tree (the REST
 * API never exposes this meta, which is why DB mode is REQUIRED for
 * Elementor-built sites).
 */
class ElementorConverter
{
    /**
     * Does this post have usable Elementor data in its meta?
     */
    public function isElementorPost(array $meta): bool
    {
        $data = $meta['_elementor_data'] ?? null;

        if (! is_string($data) || trim($data) === '' || trim($data) === '[]') {
            return false;
        }

        return str_starts_with(ltrim($data), '[');
    }

    /**
     * Build HTML from Elementor meta. Returns null when the meta is absent
     * or unparsable (caller should fall back to post_content).
     */
    public function convert(array $meta): ?string
    {
        if (! $this->isElementorPost($meta)) {
            return null;
        }

        try {
            $elements = json_decode((string) $meta['_elementor_data'], true, 512, JSON_INVALID_UTF8_SUBSTITUTE);
        } catch (Throwable) {
            return null;
        }

        if (! is_array($elements)) {
            return null;
        }

        $html = $this->renderElements($elements);
        $html = trim($html);

        return $html !== '' ? $html : null;
    }

    /**
     * Render a list of Elementor elements in order.
     */
    protected function renderElements(array $elements): string
    {
        $html = '';

        foreach ($elements as $element) {
            if (is_array($element)) {
                $html .= $this->renderElement($element)."\n";
            }
        }

        return $html;
    }

    /**
     * Sections/containers/columns carry children — flatten them in order.
     */
    protected function renderElement(array $element): string
    {
        $type = (string) ($element['elType'] ?? '');
        $children = is_array($element['elements'] ?? null) ? $element['elements'] : [];

        if ($type === 'widget') {
            return $this->renderWidget($element, $children);
        }

        // section, container, column, inner-section, and anything unknown:
        // keep the children, drop the layout chrome.
        return $this->renderElements($children);
    }

    protected function renderWidget(array $element, array $children = []): string
    {
        // Note: widgetType lives on the ELEMENT, not inside settings.
        $settings = (array) ($element['settings'] ?? []);

        return match ((string) ($element['widgetType'] ?? '')) {
            'heading' => $this->renderHeading($settings),
            'text-editor' => $this->renderTextEditor($settings),
            'image' => $this->renderImage($settings),
            'button' => $this->renderButton($settings),
            'icon-list' => $this->renderIconList($settings),
            'image-gallery' => $this->renderImageGallery($settings),
            'video' => $this->renderVideo($settings),
            'html' => (string) ($settings['html'] ?? ''),
            'divider' => "<hr>\n",
            'spacer' => '',
            default => $children !== [] ? $this->renderElements($children) : '',
        };
    }

    protected function renderHeading(array $settings): string
    {
        $title = trim((string) ($settings['title'] ?? ''));

        if ($title === '') {
            return '';
        }

        $size = (string) ($settings['header_size'] ?? 'h2');

        if (! preg_match('/^h[1-6]$/i', $size)) {
            $size = 'h2';
        }

        $link = Arr::get($settings, 'link.url');

        if ($link) {
            $title = sprintf('<a href="%s">%s</a>', e($link), $title);
        }

        return sprintf("<%s>%s</%s>\n", $size, $title, $size);
    }

    protected function renderTextEditor(array $settings): string
    {
        $editor = trim((string) ($settings['editor'] ?? ''));

        // Some Elementor builds (and pasted content) store the editor HTML
        // entity-escaped — decode it once so <strong> etc. render as tags.
        $editor = $this->decodeEscapedHtml($editor);

        return $editor !== '' ? $editor."\n" : '';
    }

    /**
     * Decode ONE layer of entity-escaped markup (e.g. "&lt;strong&gt;Posts
     * ...") so it becomes real HTML again. Only fires when escaped TAGS are
     * present — literal text about HTML ("use &lt;br&gt; here") without a
     * recognized tag stays untouched.
     */
    protected function decodeEscapedHtml(string $value): string
    {
        if (preg_match('/&lt;\/?(strong|b|em|i|p|br|ul|ol|li|a|h[1-6]|span|div|table)\b/i', $value)) {
            return html_entity_decode($value, ENT_QUOTES, 'UTF-8');
        }

        return $value;
    }

    protected function renderImage(array $settings): string
    {
        $url = Arr::get($settings, 'image.url');

        if (! $url) {
            return '';
        }

        $alt = e((string) ($settings['caption'] ?? ''));

        return sprintf("<img src=\"%s\" alt=\"%s\">\n", e($url), $alt);
    }

    protected function renderButton(array $settings): string
    {
        $text = trim((string) ($settings['text'] ?? ''));
        $link = Arr::get($settings, 'link.url');

        if ($text === '' || ! $link) {
            return '';
        }

        $attrs = '';

        if (! empty(Arr::get($settings, 'link.is_external'))) {
            $attrs .= ' target="_blank" rel="noopener"';
        }

        if (! empty(Arr::get($settings, 'link.nofollow'))) {
            $attrs .= ' rel="nofollow"';
        }

        return sprintf("<p><a href=\"%s\"%s>%s</a></p>\n", e($link), $attrs, e($text));
    }

    protected function renderIconList(array $settings): string
    {
        $items = is_array($settings['icon_list'] ?? null) ? $settings['icon_list'] : [];

        if ($items === []) {
            return '';
        }

        $lines = "<ul>\n";

        foreach ($items as $item) {
            $text = trim((string) ($item['text'] ?? ''));

            if ($text !== '') {
                // List items are plain text: if escaped markup is present,
                // decode it once and strip the tags instead of double-escaping.
                $text = strip_tags($this->decodeEscapedHtml($text));

                if ($text !== '') {
                    $lines .= sprintf("<li>%s</li>\n", e($text));
                }
            }
        }

        return $lines."</ul>\n";
    }

    protected function renderImageGallery(array $settings): string
    {
        $images = is_array($settings['gallery'] ?? null) ? $settings['gallery'] : [];

        $html = '';

        foreach ($images as $image) {
            if (! empty($image['url'])) {
                $html .= sprintf("<img src=\"%s\" alt=\"\">\n", e($image['url']));
            }
        }

        return $html;
    }

    protected function renderVideo(array $settings): string
    {
        // Iframes are stripped by the sanitizer, so embed videos as plain links.
        $type = (string) ($settings['video_type'] ?? 'youtube');

        $url = match ($type) {
            'youtube' => Arr::get($settings, 'youtube'),
            'vimeo' => Arr::get($settings, 'vimeo'),
            'hosted' => Arr::get($settings, 'hosted_url.url'),
            'dailymotion' => Arr::get($settings, 'dailymotion'),
            default => null,
        };

        if (! $url) {
            return '';
        }

        return sprintf("<p><a href=\"%s\" target=\"_blank\" rel=\"noopener\">%s</a></p>\n", e($url), e('Watch video'));
    }

    /**
     * Convenience: convert or fall back to the raw post_content snapshot.
     */
    public function convertOrFallback(array $meta, string $postContent): string
    {
        return $this->convert($meta)
            ?? (Str::of($postContent)->trim()->isNotEmpty() ? $postContent : '');
    }
}
