<?php

use Botble\Base\Facades\AdminHelper;
use Botble\SeoBoost\Http\Controllers\IndexNowController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\SeoBoost\Http\Controllers'], function (): void {
    /**
     * IndexNow API key verification file.
     * Search engines fetch /{key}.txt before honoring submissions.
     */
    Route::get('/{keyFile}', [IndexNowController::class, 'serveKeyFile'])
        ->where('keyFile', '[a-f0-9]{32}\.txt')
        ->name('seo-boost.key-file');

    AdminHelper::registerRoutes(function (): void {
        Route::prefix('seo-boost')->name('seo-boost.')->group(function (): void {
            Route::group(['permission' => 'seo-boost.index'], function (): void {
                Route::get('/', [IndexNowController::class, 'index'])->name('index');
                Route::post('submit', [IndexNowController::class, 'submit'])->name('submit');
            });

            Route::group(['permission' => 'seo-boost.logs'], function (): void {
                // Serves the history page (GET) and the DataTable AJAX data (POST),
                // mirroring how core table pages work.
                Route::match(['get', 'post'], 'logs', [IndexNowController::class, 'getLogs'])->name('logs');
            });

            Route::group(['permission' => 'seo-boost.settings'], function (): void {
                Route::get('settings', [IndexNowController::class, 'getSettings'])->name('settings');
                Route::post('settings', [IndexNowController::class, 'postSettings'])->name('settings.post');
                Route::post('reset-key', [IndexNowController::class, 'resetKey'])->name('reset-key');
                Route::post('google/test', [IndexNowController::class, 'testGoogleConnection'])->name('google.test');
            });
        });
    });
});
