<?php

namespace App\Providers;

use App\Http\Controllers\PostSyncController;
use App\Http\Controllers\PostSyncDashboardController;
use App\Http\Middleware\AuditSyncAuthentication;
use Botble\Base\Facades\AdminHelper;
use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Supports\DashboardMenuItem;
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
            $config = [
                'timeout' => (int) config('sync.timeout', 30),
                'connect_timeout' => 15,
            ];

            // Some PHP for Windows builds ship with curl.cainfo and
            // openssl.cafile both empty, so every push dies with
            // "cURL error 60: unable to get local issuer certificate".
            // Point Guzzle at a real bundle when we can find one.
            if ($caBundle = $this->caBundlePath()) {
                $config['verify'] = $caBundle;
            }

            return new Client($config);
        });
    }

    public function boot(): void
    {
        Route::post('/api/v1/sync/posts', [PostSyncController::class, 'store'])
            ->middleware([AuditSyncAuthentication::class . ':sanctum', 'throttle:30'])
            ->name('api.sync.posts');

        // Admin dashboard: how many pushes landed, which failed and why.
        AdminHelper::registerRoutes(function (): void {
            Route::get('/post-sync', [PostSyncDashboardController::class, 'index'])
                ->name('post-sync.index');
        });

        DashboardMenu::beforeRetrieving(function (): void {
            DashboardMenu::make()->registerItem(
                DashboardMenuItem::make()
                    ->id('cms-post-sync')
                    ->priority(15)
                    ->name('Post Sync')
                    ->icon('ti ti-refresh')
                    ->route('post-sync.index')
            );
        });
    }

    /**
     * Absolute path to a PEM CA bundle Guzzle can verify against, or null to
     * fall back to Guzzle's own default discovery.
     */
    protected function caBundlePath(): ?string
    {
        $configured = (string) config('sync.ca_bundle', '');

        if ($configured !== '' && is_file($configured)) {
            return $configured;
        }

        foreach (['curl.cainfo', 'openssl.cafile'] as $ini) {
            $value = (string) ini_get($ini);

            if ($value !== '' && is_file($value)) {
                return $value;
            }
        }

        foreach ($this->candidateCaBundles() as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Well-known locations that commonly hold a CA bundle. Git for Windows
     * ships the certificate set cURL expects, so it is the usual fallback.
     *
     * @return array<int, string>
     */
    protected function candidateCaBundles(): array
    {
        $candidates = [];

        foreach (['ProgramFiles', 'ProgramFiles(x86)', 'ProgramW6432'] as $variable) {
            $root = getenv($variable);

            if ($root) {
                $candidates[] = $root . '\\Git\\usr\\ssl\\certs\\ca-bundle.crt';
                $candidates[] = $root . '\\Git\\mingw64\\ssl\\certs\\ca-bundle.crt';
            }
        }

        $phpDir = dirname((string) PHP_BINARY);
        $candidates[] = $phpDir . DIRECTORY_SEPARATOR . 'extras' . DIRECTORY_SEPARATOR . 'ssl' . DIRECTORY_SEPARATOR . 'cacert.pem';
        $candidates[] = $phpDir . DIRECTORY_SEPARATOR . 'cacert.pem';

        return $candidates;
    }
}
