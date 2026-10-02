<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LiteSpeedCacheMiddleware
{
    /**
     * Handle an incoming request and apply LiteSpeed / Enterprise Cache Headers
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only cache GET requests for non-authenticated guests and non-admin routes
        $adminDir = config('core.base.general.admin_dir', 'admin');
        if (
            ! $request->isMethod('GET') ||
            $request->is($adminDir . '*') ||
            $request->is('api/*') ||
            auth()->check()
        ) {
            return $response->header('X-LiteSpeed-CacheControl', 'no-cache');
        }

        // Apply LiteSpeed LSCache & Enterprise Cache Headers (1 Week cache)
        $ttl = 604800; // 7 days in seconds

        $response->headers->set('X-LiteSpeed-CacheControl', "public, max-age={$ttl}");
        $response->headers->set('X-LiteSpeed-Tag', 'careerinpak_page');
        $response->headers->set('Cache-Control', "public, max-age=86400, s-maxage={$ttl}, stale-while-revalidate=3600");

        return $response;
    }
}
