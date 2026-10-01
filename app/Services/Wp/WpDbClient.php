<?php

namespace App\Services\Wp;

use Illuminate\Support\Collection;
use PDO;
use Throwable;

/**
 * Read-only client for a WordPress MySQL database (wp_posts, wp_postmeta,
 * wp_terms, ...). Assembles payloads in the SAME shape as the REST API
 * responses consumed by WpImporter, so the importer pipeline (sanitizing,
 * media, slugs, SEO meta, mapping) works identically for both sources.
 *
 * Why: the REST API dies when the old WordPress site is taken offline after
 * the cutover. A database copy (XAMPP dump or cPanel DB) keeps working —
 * all data then comes straight from the DB.
 */
class WpDbClient
{
    protected ?PDO $pdo = null;

    protected string $error = '';

    public function __construct(
        protected string $host,
        protected string $port,
        protected string $database,
        protected string $username,
        protected string $password,
        protected string $prefix = 'wp_',
        protected ElementorConverter $elementor = new ElementorConverter()
    ) {
        $this->prefix = rtrim($this->prefix, '_').'_';
    }

    public function connected(): bool
    {
        return $this->pdo() !== null;
    }

    public function error(): string
    {
        return $this->error;
    }

    protected function pdo(): ?PDO
    {
        if ($this->pdo instanceof PDO) {
            return $this->pdo;
        }

        $this->error = '';

        try {
            $this->pdo = new PDO(
                sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $this->host, $this->port, $this->database),
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (Throwable $e) {
            $this->error = $e->getMessage();
            $this->pdo = null;
        }

        return $this->pdo;
    }

    /**
     * All posts (or pages) as REST-shaped payloads.
     *
     * @param array $statuses WP post_status values to include
     */
    public function posts(string $type = 'post', array $statuses = ['publish'], ?int $max = null): Collection
    {
        return collect($this->rows($type, $statuses, $max))
            ->map(function (array $row) {
                $postId = (int) $row['ID'];
                $meta = $this->postMeta($postId);

                $this->resolveOgImageMeta($meta);

                $payload = $this->buildPostPayload(
                    $row,
                    $meta,
                    $this->postTerms($postId),
                    $this->thumbnail($postId),
                    in_array($postId, $this->stickyIds(), false),
                );

                // Elementor-built posts: rebuild real HTML from the widget
                // tree in postmeta (post_content only holds a degraded static
                // snapshot).
                $elementorHtml = $this->elementor->convert($meta);

                if ($elementorHtml !== null) {
                    $payload['content']['rendered'] = $elementorHtml;
                }

                return $payload;
            });
    }

    public function categories(): Collection
    {
        return $this->terms('category');
    }

    public function tags(): Collection
    {
        return $this->terms('post_tag');
    }

    protected function terms(string $taxonomy): Collection
    {
        $pdo = $this->pdo();

        if (! $pdo) {
            return collect();
        }

        $stmt = $pdo->prepare(
            'SELECT t.term_id, t.name, t.slug, tt.description'
            .' FROM '.$this->prefix.'terms t'
            .' JOIN '.$this->prefix.'term_taxonomy tt ON tt.term_id = t.term_id'
            .' WHERE tt.taxonomy = ?'
            .' ORDER BY t.term_id'
        );
        $stmt->execute([$taxonomy]);

        return collect($stmt->fetchAll())
            ->map(fn (array $row) => static::buildTermPayload($row));
    }

    /**
     * @return array<int, array<string, mixed>> raw wp_posts rows
     */
    protected function rows(string $type, array $statuses, ?int $max): array
    {
        $pdo = $this->pdo();

        if (! $pdo) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($statuses), '?'));

        $sql = 'SELECT ID, post_author, post_date, post_date_gmt, post_content, post_title,'
            .' post_excerpt, post_status, post_name, post_type'
            .' FROM '.$this->prefix.'posts'
            ." WHERE post_type = ? AND post_status IN ($placeholders)"
            .' ORDER BY post_date DESC, ID DESC';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$type, ...$statuses]);

        $rows = $stmt->fetchAll();

        if ($max !== null && $max > 0) {
            $rows = array_slice($rows, 0, $max);
        }

        return $rows;
    }

    /**
     * All post meta for one post, keyed by meta_key (last value wins —
     * matches WordPress get_post_meta behavior).
     */
    protected function postMeta(int $postId): array
    {
        $pdo = $this->pdo();

        if (! $pdo) {
            return [];
        }

        $stmt = $pdo->prepare('SELECT meta_key, meta_value FROM '.$this->prefix.'postmeta WHERE post_id = ?');
        $stmt->execute([$postId]);

        $meta = [];

        foreach ($stmt->fetchAll() as $row) {
            $meta[(string) $row['meta_key']] = static::maybeUnserialize((string) $row['meta_value']);
        }

        return $meta;
    }

    /**
     * Terms attached to a post, wrapped in the _embedded['wp:term'] shape
     * (an array of groups; WpImporter flattens the groups).
     */
    protected function postTerms(int $postId): array
    {
        $pdo = $this->pdo();

        if (! $pdo) {
            return [];
        }

        $stmt = $pdo->prepare(
            'SELECT t.term_id, t.name, t.slug, tt.taxonomy, tt.description'
            .' FROM '.$this->prefix.'term_relationships tr'
            .' JOIN '.$this->prefix.'term_taxonomy tt ON tt.term_taxonomy_id = tr.term_taxonomy_id'
            .' JOIN '.$this->prefix.'terms t ON t.term_id = tt.term_id'
            .' WHERE tr.object_id = ?'
        );
        $stmt->execute([$postId]);

        $terms = [];

        foreach ($stmt->fetchAll() as $row) {
            if (in_array($row['taxonomy'], ['category', 'post_tag'], true)) {
                $terms[] = static::buildTermPayload($row);
            }
        }

        return $terms ? [$terms] : [];
    }

    /**
     * Featured image: the _thumbnail_id meta -> attachment row, shaped like
     * a REST _embedded['wp:featuredmedia'][0] object.
     */
    protected function thumbnail(int $postId): ?array
    {
        $pdo = $this->pdo();

        if (! $pdo) {
            return null;
        }

        $stmt = $pdo->prepare('SELECT meta_value FROM '.$this->prefix.'postmeta WHERE post_id = ? AND meta_key = \'_thumbnail_id\'');
        $stmt->execute([$postId]);

        $thumbId = (int) ($stmt->fetchColumn() ?: 0);

        if (! $thumbId) {
            return null;
        }

        $stmt = $pdo->prepare("SELECT ID, guid FROM {$this->prefix}posts WHERE ID = ? AND post_type = 'attachment'");
        $stmt->execute([$thumbId]);

        return static::buildMediaPayload($stmt->fetch() ?: null);
    }

    /**
     * Yoast/RankMath store the og:image either as a URL or as an attachment
     * ID in a sibling meta key. Resolve IDs to attachment URLs here so the
     * payload always carries a downloadable/copyable URL.
     */
    protected function resolveOgImageMeta(array &$meta): void
    {
        foreach ([
            'rank_math_facebook_image' => 'rank_math_facebook_image_id',
            '_yoast_wpseo_opengraph-image' => '_yoast_wpseo_opengraph-image-id',
        ] as $urlKey => $idKey) {
            $id = (int) ($meta[$idKey] ?? 0);

            if ($id && empty($meta[$urlKey]) && ($guid = $this->attachmentGuid($id))) {
                $meta[$urlKey] = $guid;
            }
        }
    }

    public function attachmentGuid(int $attachmentId): ?string
    {
        $pdo = $this->pdo();

        if (! $pdo) {
            return null;
        }

        $stmt = $pdo->prepare("SELECT guid FROM {$this->prefix}posts WHERE ID = ? AND post_type = 'attachment'");
        $stmt->execute([$attachmentId]);

        return $stmt->fetchColumn() ?: null;
    }

    /**
     * IDs of sticky posts, read from the sticky_posts option.
     *
     * @return array<int, int>
     */
    protected function stickyIds(): array
    {
        $pdo = $this->pdo();

        if (! $pdo) {
            return [];
        }

        try {
            $stmt = $pdo->prepare("SELECT option_value FROM {$this->prefix}options WHERE option_name = 'sticky_posts'");
            $stmt->execute();

            $value = static::maybeUnserialize((string) ($stmt->fetchColumn() ?: ''));

            return is_array($value) ? array_map('intval', $value) : [];
        } catch (Throwable) {
            return []; // options table optional for import correctness
        }
    }

    // ---------- payload builders (pure, unit-testable) ----------

    /**
     * Assemble a REST-shaped post/page payload from raw DB pieces.
     *
     * @param array $post raw wp_posts row
     * @param array $meta postmeta key => value (unserialized)
     * @param array $termGroups _embedded['wp:term'] shape
     * @param array|null $thumbnail _embedded['wp:featuredmedia'][0] shape
     */
    public static function buildPostPayload(array $post, array $meta = [], array $termGroups = [], ?array $thumbnail = null, bool $isSticky = false): array
    {
        // WP stores '0000-00-00 00:00:00' for zero dates — fall back to local date.
        $dateGmt = (string) ($post['post_date_gmt'] ?? '');

        if ($dateGmt === '' || str_starts_with($dateGmt, '0000-00-00')) {
            $dateGmt = (string) ($post['post_date'] ?? '');
        }

        $payload = [
            'id' => (int) $post['ID'],
            'slug' => (string) ($post['post_name'] ?? ''),
            'status' => (string) ($post['post_status'] ?? 'publish'),
            'date_gmt' => $dateGmt,
            'date' => (string) ($post['post_date'] ?? ''),
            'title' => ['rendered' => (string) ($post['post_title'] ?? '')],
            'content' => ['rendered' => (string) ($post['post_content'] ?? '')],
            'excerpt' => ['rendered' => (string) ($post['post_excerpt'] ?? '')],
            'sticky' => $isSticky,
        ];

        if ($termGroups) {
            $payload['_embedded']['wp:term'] = $termGroups;
        }

        if ($thumbnail) {
            $payload['_embedded']['wp:featuredmedia'] = [$thumbnail];
        }

        $seo = static::assembleSeo($meta, (string) ($post['post_title'] ?? ''), $meta['_thumbnail_id'] ?? null);

        if ($seo) {
            $payload['yoast_head_json'] = $seo;
        }

        return $payload;
    }

    /**
     * REST-shaped term payload (works for categories and tags).
     */
    public static function buildTermPayload(array $row): array
    {
        return [
            'id' => (int) $row['term_id'],
            'name' => (string) ($row['name'] ?? ''),
            'slug' => (string) ($row['slug'] ?? ''),
            'description' => (string) ($row['description'] ?? ''),
            'taxonomy' => (string) ($row['taxonomy'] ?? ''),
        ];
    }

    /**
     * REST-shaped media object from an attachment row. Full size only —
     * WP does not store rendition URLs in the DB, the local media copier
     * resolves whatever file exists on disk.
     */
    public static function buildMediaPayload(?array $attachment): ?array
    {
        if (! $attachment || empty($attachment['ID'])) {
            return null;
        }

        return [
            'id' => (int) $attachment['ID'],
            'source_url' => (string) ($attachment['guid'] ?? ''),
            'media_details' => ['sizes' => []],
        ];
    }

    /**
     * Build a yoast_head_json-shaped SEO payload from Yoast OR RankMath
     * post meta (Yoast wins when both exist). Returns null when neither
     * plugin left meta behind.
     *
     * @param mixed $thumbnailId raw _thumbnail_id meta value, used by RankMath image-by-id
     */
    public static function assembleSeo(array $meta, string $fallbackTitle, mixed $thumbnailId = null): ?array
    {
        $isYoast = isset($meta['_yoast_wpseo_title']) || isset($meta['_yoast_wpseo_metadesc']);
        $isRankMath = isset($meta['rank_math_title']) || isset($meta['rank_math_description']);

        if (! $isYoast && ! $isRankMath) {
            return null;
        }

        if ($isYoast) {
            $title = static::expandVars((string) ($meta['_yoast_wpseo_title'] ?? ''), $fallbackTitle);
            $description = (string) ($meta['_yoast_wpseo_metadesc'] ?? '');
            $noindex = (string) ($meta['_yoast_wpseo_meta-robots-noindex'] ?? '') === '1';
            $ogImage = (string) ($meta['_yoast_wpseo_opengraph-image'] ?? '');
        } else {
            $title = static::expandVars((string) ($meta['rank_math_title'] ?? ''), $fallbackTitle);
            $description = (string) ($meta['rank_math_description'] ?? '');
            $robots = is_array($meta['rank_math_robots'] ?? null) ? $meta['rank_math_robots'] : [];
            $noindex = in_array('noindex', $robots, true);

            $ogImage = (string) ($meta['rank_math_facebook_image'] ?? '');

            if (! $ogImage && ! empty($meta['rank_math_facebook_image_id'])) {
                $ogImage = 'attachment://'.(int) $meta['rank_math_facebook_image_id'];
            }
        }

        if (str_starts_with($ogImage, 'attachment://')) {
            $ogImage = ''; // resolved by the caller via the attachments query when available
        }

        return [
            'title' => $title !== '' ? $title : $fallbackTitle,
            'meta_description' => $description,
            'robots' => ['index' => $noindex ? 'noindex' : 'index'],
            'og_image' => $ogImage ? [['url' => $ogImage]] : [],
        ];
    }

    /**
     * Replace the few Yoast/RankMath template variables we can resolve
     * locally; unknown %%vars%% are stripped so they never render raw.
     */
    public static function expandVars(string $template, string $title): string
    {
        $expanded = str_replace(['%%title%%', '%title%'], $title, $template);
        $expanded = str_replace(['%%sep%%', '%sep%'], '-', $expanded);
        $expanded = str_replace(['%%sitename%%', '%sitename%'], '', $expanded);

        return trim(preg_replace('/\s+/u', ' ', preg_replace('/%%[a-z_]+%%/i', '', $expanded) ?? '') ?? '');
    }

    /**
     * WordPress maybe_unserialize equivalent (safe — never instantiates
     * objects, arrays/scalars only).
     */
    public static function maybeUnserialize(string $value): mixed
    {
        $trimmed = trim($value);

        if ($trimmed === '' || $trimmed === 'b:0;' || $trimmed === 'b:1;') {
            return match ($trimmed) {
                'b:0;' => false,
                'b:1;' => true,
                default => $value,
            };
        }

        if (! preg_match('/^[adis]:/', $trimmed)) {
            return $value; // arrays/strings/ints/floats only — never objects
        }

        $unserialized = @unserialize($trimmed, ['allowed_classes' => false]);

        return $unserialized === false ? $value : $unserialized;
    }
}
