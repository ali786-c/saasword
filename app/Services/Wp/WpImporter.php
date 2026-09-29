<?php

namespace App\Services\Wp;

use App\Models\WpImportMapping;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\MetaBox as MetaBoxSupport;
use Botble\ACL\Models\User;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Blog\Models\Tag;
use Botble\Page\Models\Page;
use Botble\Slug\Facades\SlugHelper;
use Botble\Slug\Models\Slug;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

/**
 * Imports WordPress posts/pages/categories/tags into Botble via the REST API,
 * preserving slugs, dates and SEO metadata. Re-runnable: progress is tracked
 * in the wp_import_mapping table so an interrupted run resumes safely.
 */
class WpImporter
{
    public function __construct(
        protected WpClient $client,
        protected ContentSanitizer $sanitizer,
        protected MediaDownloader $media
    ) {
    }

    /**
     * Import all categories and tags first so posts can attach to them.
     *
     * @return array{categories: int, tags: int}
     */
    public function importTerms(): array
    {
        $counts = ['categories' => 0, 'tags' => 0];

        foreach ($this->client->categories() as $term) {
            if ($this->importCategory($term)) {
                $counts['categories']++;
            }
        }

        foreach ($this->client->tags() as $term) {
            if ($this->importTag($term)) {
                $counts['tags']++;
            }
        }

        return $counts;
    }

    /**
     * @return bool true when newly imported
     */
    public function importCategory(array $term): bool
    {
        $mapping = WpImportMapping::firstOrCreate(
            ['wp_type' => 'category', 'wp_id' => $term['id']],
            ['wp_slug' => $term['slug'] ?? null, 'status' => 'imported']
        );

        if ($mapping->local_id) {
            return false; // already imported
        }

        $category = Category::query()->create([
            'name' => $term['name'] ?? ('Category #'.$term['id']),
            'description' => $this->sanitizer->toPlainText($term['description'] ?? ''),
            'status' => BaseStatusEnum::PUBLISHED,
        ]);

        $this->createSlug($category, $term['slug'] ?? null);

        $mapping->update(['local_id' => $category->getKey(), 'local_type' => Category::class]);

        return true;
    }

    /**
     * @return bool true when newly imported
     */
    public function importTag(array $term): bool
    {
        $mapping = WpImportMapping::firstOrCreate(
            ['wp_type' => 'tag', 'wp_id' => $term['id']],
            ['wp_slug' => $term['slug'] ?? null, 'status' => 'imported']
        );

        if ($mapping->local_id) {
            return false;
        }

        $tag = Tag::query()->create([
            'name' => $term['name'] ?? ('Tag #'.$term['id']),
            'description' => $this->sanitizer->toPlainText($term['description'] ?? ''),
            'status' => BaseStatusEnum::PUBLISHED,
        ]);

        $this->createSlug($tag, $term['slug'] ?? null);

        $mapping->update(['local_id' => $tag->getKey(), 'local_type' => Tag::class]);

        return true;
    }

    /**
     * Import posts (or pages) from a REST payload collection.
     *
     * @param iterable $posts raw WP REST post objects
     * @param array $options {publish: bool, with_media: bool, type: string}
     * @return array{imported: int, skipped: int, failed: int, errors: string[]}
     */
    public function importPosts(iterable $posts, array $options = []): array
    {
        $options = [
            'publish' => $options['publish'] ?? false,
            'with_media' => $options['with_media'] ?? true,
            'type' => $options['type'] ?? 'post',
        ];

        $counts = ['imported' => 0, 'skipped' => 0, 'failed' => 0, 'errors' => []];

        foreach ($posts as $wpPost) {
            try {
                $result = $this->importPost($wpPost, $options);

                if ($result === 'imported') {
                    $counts['imported']++;
                } elseif ($result === 'skipped') {
                    $counts['skipped']++;
                } else {
                    $counts['failed']++;
                }
            } catch (Throwable $e) {
                $counts['failed']++;
                $counts['errors'][] = sprintf('#%s %s: %s', $wpPost['id'] ?? '?', $wpPost['slug'] ?? '?', $e->getMessage());
                Log::error('[wp:migrate] post import failed', ['id' => $wpPost['id'] ?? null, 'error' => $e->getMessage()]);
            }
        }

        return $counts;
    }

    /**
     * @return string imported|skipped|failed
     */
    public function importPost(array $wpPost, array $options = []): string
    {
        $wpType = $options['type'] ?? 'post';
        $mappingType = $wpType === 'page' ? 'page' : 'post';

        $mapping = WpImportMapping::firstOrCreate(
            ['wp_type' => $mappingType, 'wp_id' => $wpPost['id']],
            ['wp_slug' => $wpPost['slug'] ?? null, 'status' => 'imported']
        );

        if ($mapping->local_id) {
            return 'skipped'; // already imported on a previous run
        }

        // ---------- content ----------
        $html = $wpPost['content']['rendered'] ?? '';

        $imageMap = [];
        if ($options['with_media']) {
            $imageMap = $this->downloadContentImages($html);
        }

        $html = $this->sanitizer->rewriteImages($html, $imageMap);
        $content = $this->sanitizer->clean($html);

        // ---------- model ----------
        $isPage = $mappingType === 'page';

        $attributes = [
            'name' => $wpPost['title']['rendered'] ?? ('Untitled #'.$wpPost['id']),
            'description' => $this->sanitizer->toPlainText($wpPost['excerpt']['rendered'] ?? ''),
            'content' => $content,
            'status' => ($options['publish'] ?? false) ? BaseStatusEnum::PUBLISHED : BaseStatusEnum::DRAFT,
        ];

        if ($isPage) {
            $local = Page::query()->create($attributes + ['user_id' => $this->authorId()]);
        } else {
            $local = Post::query()->create($attributes + [
                'author_id' => $this->authorId(),
                'author_type' => User::class,
                'image' => $this->featuredImage($wpPost, $options['with_media'] ?? true),
                'is_featured' => (int) (($wpPost['sticky'] ?? false) === true),
            ]);

            $this->attachTerms($local, $wpPost);
        }

        // Preserve original publish date (matters for SEO and archives).
        $date = Arr::get($wpPost, 'date_gmt') ?: Arr::get($wpPost, 'date');
        if ($date && ($timestamp = Carbon::parse($date))) {
            $local->forceFill(['created_at' => $timestamp, 'updated_at' => $timestamp])->save();
        }

        // ---------- slug + SEO meta ----------
        $this->createSlug($local, $wpPost['slug'] ?? null);

        $this->saveSeoMeta($local, $wpPost, $imageMap);

        $mapping->update(['local_id' => $local->getKey(), 'local_type' => $local::class]);

        return 'imported';
    }

    /**
     * Download featured image (_embedded['wp:featuredmedia']) and return local path.
     */
    protected function featuredImage(array $wpPost, bool $withMedia): ?string
    {
        if (! $withMedia) {
            return null;
        }

        $media = $this->embedded($wpPost, 'wp:featuredmedia', 0);

        if (! $media) {
            return null;
        }

        $url = $this->pickMediaUrl($media);

        if (! $url) {
            return null;
        }

        return $this->media->download($url) ?? $url;
    }

    /**
     * Download every image referenced in post content, returning old => new map.
     */
    protected function downloadContentImages(string $html): array
    {
        $map = [];

        preg_match_all('/<img[^>]+src\s*=\s*("|\')([^"\']+)\1/i', $html, $matches);

        foreach ($matches[2] ?? [] as $url) {
            if (! preg_match('#^https?://#i', $url)) {
                continue;
            }

            // Skip URLs that are clearly not images to avoid wasted downloads.
            if (preg_match('/\.(svg|js|css|php)(\?|$)/i', $url)) {
                continue;
            }

            $local = $this->media->download($url);

            if ($local) {
                $map[$url] = $local;
            }
        }

        return $map;
    }

    /**
     * Attach categories and tags resolved from the WP REST _embedded payload.
     */
    protected function attachTerms(Post $post, array $wpPost): void
    {
        $categoryIds = [];
        $tagIds = [];

        foreach ($this->embedded($wpPost, 'wp:term') ?: [] as $group) {
            foreach ($group ?: [] as $term) {
                $taxonomy = $term['taxonomy'] ?? null;
                $wpId = $term['id'] ?? null;

                if (! $wpId || ! in_array($taxonomy, ['category', 'post_tag'])) {
                    continue;
                }

                $localId = WpImportMapping::query()
                    ->where('wp_type', $taxonomy === 'category' ? 'category' : 'tag')
                    ->where('wp_id', $wpId)
                    ->value('local_id');

                if (! $localId) {
                    continue;
                }

                if ($taxonomy === 'category') {
                    $categoryIds[] = (int) $localId;
                } else {
                    $tagIds[] = (int) $localId;
                }
            }
        }

        if ($categoryIds) {
            $post->categories()->syncWithoutDetaching(array_unique($categoryIds));
        }

        if ($tagIds) {
            $post->tags()->syncWithoutDetaching(array_unique($tagIds));
        }
    }

    /**
     * Store Yoast/RankMath SEO data (available as yoast_head_json) into the
     * Botble SeoHelper meta box so titles/descriptions survive migration.
     */
    protected function saveSeoMeta(BaseModel $local, array $wpPost, array $imageMap): void
    {
        $seo = Arr::get($wpPost, 'yoast_head_json');

        if (! $seo) {
            return;
        }

        $meta = [
            'seo_title' => Str::limit($seo['title'] ?? '', 250, ''),
            'seo_description' => Str::limit($seo['meta_description'] ?? '', 400, ''),
            'seo_image' => null,
            'index' => ($seo['robots']['index'] ?? 'index') === 'noindex' ? 'noindex' : 'index',
        ];

        $ogImage = $seo['og_image'][0]['url'] ?? null;

        if ($ogImage) {
            $meta['seo_image'] = $imageMap[$ogImage] ?? $this->media->download($ogImage) ?? $ogImage;
        }

        app(MetaBoxSupport::class)->saveMetaBoxData($local, 'seo_meta', $meta);
    }

    /**
     * Create the URL slug preserving the original WP slug for SEO. Ensures
     * uniqueness explicitly (SlugHelper's createSlug would silently overwrite
     * an existing key for the same reference, and does not suffix duplicates).
     */
    protected function createSlug(BaseModel $model, ?string $wpSlug): void
    {
        $base = Str::slug($wpSlug ?: (string) $model->name);

        if ($base === '') {
            $base = 'import-'.Str::random(6);
        }

        $key = $base;
        $i = 1;

        while (
            Slug::query()->where('key', $key)
                ->where(function ($q) use ($model) {
                    $q->where('reference_type', '!=', $model::class)
                        ->orWhere('reference_id', '!=', $model->getKey());
                })
                ->exists()
        ) {
            $key = $base.'-'.$i++;
        }

        SlugHelper::createSlug($model, $key);
    }

    /**
     * Map WP author to the first admin user (single-author blog migration).
     */
    protected function authorId(): int
    {
        return (int) (User::query()->orderBy('id')->value('id') ?? 0);
    }

    /**
     * Resolve the best downloadable URL from a WP media object
     * (prefer reasonably-sized renditions over the full-resolution original).
     */
    protected function pickMediaUrl(array $media): ?string
    {
        $sizes = $media['media_details']['sizes'] ?? [];

        return $sizes['large']['source_url']
            ?? $sizes['medium_large']['source_url']
            ?? $sizes['medium']['source_url']
            ?? $media['source_url']
            ?? null;
    }

    /**
 * Safe accessor for REST _embed payloads whose keys contain colons
     * (Arr::get dot-notation cannot address keys like "wp:featuredmedia").
     */
    protected function embedded(array $wpPost, string $key, ?int $index = null): mixed
    {
        $value = $wpPost['_embedded'][$key] ?? null;

        if ($index === null) {
            return $value;
        }

        return $value[$index] ?? null;
    }
}
