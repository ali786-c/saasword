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
            });
        });
    });
});
