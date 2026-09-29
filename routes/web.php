<?php

use App\Models\WpImportMapping;
use Botble\Blog\Models\Post;
use Botble\Slug\Models\Slug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| WordPress legacy URL redirects (Phase 2 — SEO preservation)
|--------------------------------------------------------------------------
|
| The theme package already owns the public single route ({prefix}/{slug}),
| so a catch-all "fallback" is the only safe interception point: it fires
| only when no other route matched — i.e. exactly the legacy 404 case.
|
| Handles old WordPress permalink patterns:
|   /2024/05/post-slug        (/%year%/%monthnum%/%postname%/)
|   /2024/05/12/post-slug     (/%year%/%monthnum%/%day%/%postname%/)
|   /category/travel          (WP term archives)
|   /any-plain-post-slug      (?p= style / plain permalinks)
*/

Route::fallback(function (Request $request) {
    $path = trim(rawurldecode($request->path()), '/');

    if ($path === '' || str_starts_with($path, 'admin') || str_starts_with($path, 'api')) {
        abort(404);
    }

    // 1. Dated permalinks: /YYYY/MM/slug, /YYYY/MM/DD/slug
    if (preg_match('#^\d{4}/(?:\d{1,2}/)?(?:\d{1,2}/)?([a-z0-9\-_]+)/?$#i', $path, $matches)) {
        if ($target = legacyRedirectForSlug($matches[1])) {
            return redirect()->to($target, 301);
        }
    }

    // 2. WP term archives: /category/x, /tag/x (also /category/x/page/2)
    if (preg_match('#^(category|tag)/([a-z0-9\-_]+)/?(?:page/\d+/?$)?#i', $path, $matches)) {
        $type = strtolower($matches[1]) === 'category' ? 'category' : 'tag';

        $localId = WpImportMapping::query()
            ->where('wp_type', $type)
            ->where('wp_slug', $matches[2])
            ->value('local_id');

        if ($localId && ($target = localUrlFor($type, (int) $localId))) {
            return redirect()->to($target, 301);
        }
    }

    // 3. Any other legacy path: direct slug match against imported content.
    if ($target = legacyRedirectForSlug($path)) {
        return redirect()->to($target, 301);
    }

    abort(404);
})->name('wp.legacy.redirect');

if (! function_exists('legacyRedirectForSlug')) {
    function legacyRedirectForSlug(string $slug): ?string
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
}

if (! function_exists('localUrlFor')) {
    function localUrlFor(string $type, int $id): ?string
    {
        $modelClass = $type === 'category'
            ? \Botble\Blog\Models\Category::class
            : \Botble\Blog\Models\Tag::class;

        $model = $modelClass::query()->find($id);

        return $model?->url;
    }
}
