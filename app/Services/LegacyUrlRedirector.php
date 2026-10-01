<?php

namespace App\Services;

use App\Models\WpImportMapping;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Blog\Models\Tag;
use Botble\Slug\Models\Slug;

/**
 * Resolves WordPress legacy URLs to their imported Botble equivalents.
 *
 * Lives in a real class (not routes/web.php closures/helpers) because
 * production route caching (`php artisan optimize`) serializes the fallback
 * route's action and drops non-loaded helper functions entirely — a cached
 * route calling legacyRedirectForSlug() fatals with
 * "Call to undefined function". Composer's PSR-4 autoloader guarantees the
 * class exists on every request, cached or not.
 */
class LegacyUrlRedirector
{
    public function redirectForSlug(string $slug): ?string
    {
        // Prefer the migration mapping (exact WP slug we imported).
        $localId = WpImportMapping::query()
            ->where('wp_type', 'post')
            ->where('wp_slug', $slug)
            ->value('local_id');

        if ($localId && ($post = Post::query()->find($localId))) {
            return $post->url;
        }

        // Fall back to any locally-registered slug with this key.
        $slugModel = Slug::query()->where('key', $slug)->first();

        if ($slugModel && $reference = $slugModel->reference) {
            return $reference->url ?? null;
        }

        return null;
    }

    public function localUrlFor(string $type, int $id): ?string
    {
        $modelClass = $type === 'category'
            ? Category::class
            : Tag::class;

        return $modelClass::query()->find($id)?->url;
    }
}
