<?php

namespace App\Providers;

use App\Http\Controllers\PostSyncController;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Local -> live post sync (see App\Services\PostSyncService).
 *
 * This provider is listed in bootstrap/providers.php. It is deliberately NOT
 * part of AppServiceProvider: that provider was never registered by this
 * application (no bootstrap/providers.php existed), so hooking the sync route
 * up there would have silently registered nothing. Keeping the feature in its
 * own provider also means activating it does not switch on any dormant
 * behaviour that was never actually running.
 *
 * The route is registered here rather than in routes/api.php on purpose: adding
 * that file to bootstrap/app.php would put it in the `api` middleware group,
 * where Botble's ApiEnabledMiddleware returns 503 while the public "API enabled"
 * admin setting is off (its default). This machine-to-machine channel
 * authenticates with its own Sanctum token, so it only needs `auth:sanctum`
 * (Bearer) plus a throttle — no session, no CSRF, no dependency on that toggle.
 */
class PostSyncServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // HTTP client used by `post:push`, bound so tests can swap in a Guzzle
        // MockHandler instead of ever reaching the live site.
        $this->app->bind(Client::class, function () {
            return new Client([
                'timeout' => (int) config('sync.timeout', 30),
                'connect_timeout' => 15,
            ]);
        });
    }

    public function boot(): void
    {
        Route::post('/api/v1/sync/posts', [PostSyncController::class, 'store'])
            ->middleware(['auth:sanctum', 'throttle:30'])
            ->name('api.sync.posts');
    }
}
