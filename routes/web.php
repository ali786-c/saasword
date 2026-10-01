<?php

use App\Models\WpImportMapping;
use App\Services\LegacyUrlRedirector;
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
|
| Helpers live in App\Services\LegacyUrlRedirector — NOT as functions in
| this file. `php artisan optimize` (route cache) serializes this closure
| and skips helper function definitions, which fatalled on production
| with "Call to undefined function legacyRedirectForSlug()".
*/

Route::fallback(function (Request $request, LegacyUrlRedirector $redirector) {
    $path = trim(rawurldecode($request->path()), '/');

    // Never intercept the admin panel, API or installer — they are real routes,
    // but if something under them 404s we must not touch it here.
    $adminDir = config('core.base.general.admin_dir', 'admin');

    if ($path === '' || str_starts_with($path, $adminDir) || str_starts_with($path, 'api') || str_starts_with($path, 'install')) {
        abort(404);
    }

    // 1. Dated permalinks: /YYYY/MM/slug, /YYYY/MM/DD/slug
    if (preg_match('#^\d{4}/(?:\d{1,2}/)?(?:\d{1,2}/)?([a-z0-9\-_]+)/?$#i', $path, $matches)) {
        if ($target = $redirector->redirectForSlug($matches[1])) {
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

        if ($localId && ($target = $redirector->localUrlFor($type, (int) $localId))) {
            return redirect()->to($target, 301);
        }
    }

    // 3. Any other legacy path: direct slug match against imported content.
    if ($target = $redirector->redirectForSlug($path)) {
        return redirect()->to($target, 301);
    }

    abort(404);
})->name('wp.legacy.redirect');
