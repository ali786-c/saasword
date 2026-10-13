<?php

namespace App\Services;

use Botble\ACL\Models\User;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Slug\Models\Slug;
use RuntimeException;

/**
 * Moves a post from the LOCAL install to the LIVE install over the
 * token-protected POST /api/v1/sync/posts endpoint.
 *
 * Design decisions:
 *
 * - One-way only (local -> live). The live site is authoritative for nothing
 *   but its own publish decision, which stays with the admin.
 * - A NEW post always lands as status=draft, so the sender can never publish
 *   anything straight to production.
 * - An EXISTING post keeps whatever status the live site already gave it.
 *   Forcing a draft here would silently unpublish an indexed live URL (404).
 *   The sender can still ask for a review hold with `force_draft`, which can
 *   only ever move a post TO draft — never to published.
 * - Identity is the slug, not the local post ID: both installs then serve the
 *   identical URL (SEO-safe, matches the migration guide's slug preservation)
 *   and re-pushing the same post updates it instead of duplicating it.
 * - `content` is stored verbatim. It is NOT passed through HTMLPurifier,
 *   because the 11-section template relies on inline `!important` styles that
 *   the purifier strips. The endpoint is authenticated, and the payload only
 *   ever originates from our own admin/CLI — unlike the WordPress migration,
 *   where content arrived from an infected third-party host.
 */
class PostSyncService
{
    /**
     * The only payload keys the receiver will read. Everything else sent by a
     * client is ignored, so the endpoint cannot be used to write arbitrary
     * columns (author_id, status, image, ...).
     */
    public const ALLOWED_KEYS = [
        'local_post_id',
        'name',
        'slug',
        'description',
        'content',
        'category',
        'is_featured',
        'views',
        'force_draft',
    ];

    /**
     * @return array<string, mixed> payload ready to be JSON-encoded and POSTed
     */
    public function payloadFor(Post $post): array
    {
        return [
            'local_post_id' => (int) $post->getKey(),
            'name' => (string) $post->name,
            'slug' => $this->slugKeyFor($post),
            'description' => (string) ($post->description ?? ''),
            'content' => (string) ($post->content ?? ''),
            'category' => $this->categoryNameFor($post),
            'is_featured' => (bool) $post->is_featured,
            'views' => (int) ($post->views ?? 0),
        ];
    }

    /**
     * Create or update the post on the receiving (live) side.
     *
     * @param  array<string, mixed>  $payload  already validated by the controller
     * @param  int|null  $authorId  authenticated user id from the Sanctum token
     * @return array{post: Post, created: bool}
     */
    public function apply(array $payload, ?int $authorId = null): array
    {
        $payload = array_intersect_key($payload, array_flip(self::ALLOWED_KEYS));

        foreach (['name', 'slug', 'content'] as $required) {
            if (! isset($payload[$required]) || $payload[$required] === '') {
                throw new RuntimeException("Sync payload is missing required field [{$required}].");
            }
        }

        $slugKey = $payload['slug'];
        $authorId = $authorId ?: 1;

        $attributes = [
            'name' => $payload['name'],
            'description' => (string) ($payload['description'] ?? ''),
            'content' => $payload['content'],
            'is_featured' => ! empty($payload['is_featured']) ? 1 : 0,
            'author_id' => $authorId,
            'author_type' => User::class,
        ];

        $post = $this->postForSlug($slugKey);

        if ($post) {
            $created = false;

            // Preserve the live status by default: re-syncing must never take a
            // published page offline. `force_draft` is the only status the
            // sender may request, and it can only move a post TO draft.
            if (! empty($payload['force_draft'])) {
                $attributes['status'] = BaseStatusEnum::DRAFT;
            }

            // `views` is intentionally NOT updated on an existing post: the live
            // site owns its own counter, and re-syncing must not reset it.
            $post->fill($attributes);
            $post->save();
        } else {
            $created = true;
            // New posts always await admin review — the sender never chooses.
            $attributes['status'] = BaseStatusEnum::DRAFT;
            $attributes['views'] = max(0, (int) ($payload['views'] ?? 0));

            $post = Post::query()->create($attributes);
        }

        Slug::query()->firstOrCreate(
            [
                'key' => $slugKey,
                'reference_type' => Post::class,
            ],
            [
                'reference_id' => $post->getKey(),
                'prefix' => '',
            ]
        );

        $this->attachCategory($post, (string) ($payload['category'] ?? ''));
        $this->ensureLanguageMeta($post);
        $this->flushPostCache();

        return ['post' => $post->fresh(), 'created' => $created];
    }

    /**
     * Resolve the local post that owns this slug, so a re-push updates it.
     */
    public function postForSlug(string $slugKey): ?Post
    {
        $slug = Slug::query()
            ->where('reference_type', Post::class)
            ->where('key', $slugKey)
            ->first();

        if (! $slug || ! $slug->reference_id) {
            return null;
        }

        return Post::query()->find($slug->reference_id);
    }

    public function slugKeyFor(Post $post): string
    {
        $key = Slug::query()
            ->where('reference_type', Post::class)
            ->where('reference_id', $post->getKey())
            ->value('key');

        if ($key) {
            return (string) $key;
        }

        // No slug row yet (possible for imported/hand-made posts) — derive one
        // the same way the migration and the create command do.
        return \Illuminate\Support\Str::slug((string) $post->name);
    }

    public function categoryNameFor(Post $post): string
    {
        $this->loadCategories($post);

        return (string) ($post->categories->first()->name ?? '');
    }

    /**
     * Attach the named category, falling back to the same priority chain the
     * `cms:create-fresh-job` command uses so both installs classify posts alike.
     */
    protected function attachCategory(Post $post, string $categoryName): void
    {
        if (! class_exists(Category::class)) {
            return;
        }

        $category = null;

        if ($categoryName !== '') {
            $category = Category::query()->where('name', $categoryName)->first();
        }

        $category = $category
            ?: Category::query()->where('name', 'Punjab Jobs')->first()
            ?: Category::query()->where('name', 'Federal Jobs')->first()
            ?: Category::query()->where('name', 'Jobs')->first()
            ?: Category::query()->first();

        if ($category) {
            $post->categories()->sync([$category->getKey()]);
        }
    }

    /**
     * Botble's language plugin needs a meta row or the post can render oddly
     * in the admin list. Mirrors `cms:create-fresh-job`.
     */
    protected function ensureLanguageMeta(Post $post): void
    {
        if (! class_exists(\Botble\Language\Models\LanguageMeta::class)) {
            return;
        }

        \Botble\Language\Models\LanguageMeta::query()->firstOrCreate(
            [
                'reference_id' => $post->getKey(),
                'reference_type' => Post::class,
            ],
            [
                'lang_meta_code' => setting('language_default_code', 'en_US'),
                'lang_meta_origin' => md5(microtime() . $post->getKey()),
            ]
        );
    }

    protected function flushPostCache(): void
    {
        if (class_exists(\Botble\Support\Services\Cache\Cache::class)) {
            \Botble\Support\Services\Cache\Cache::make(Post::class)->flush();
        }
    }

    protected function loadCategories(Post $post): void
    {
        if (! $post->relationLoaded('categories')) {
            $post->load('categories');
        }
    }
}
