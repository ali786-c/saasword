<?php

use Botble\Base\Facades\AdminHelper;
use Botble\OneSignal\Http\Controllers\OneSignalSettingController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\OneSignal\Http\Controllers'], function (): void {
    // Serve OneSignal Service Worker file dynamically at root
    Route::get('/OneSignalSDKWorker.js', function () {
        $content = "importScripts('https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.sw.js');";

        return response($content, 200, [
            'Content-Type' => 'application/javascript',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    })->name('onesignal.worker');

    AdminHelper::registerRoutes(function (): void {
        Route::prefix('settings/onesignal')->name('onesignal.')->group(function (): void {
            Route::group(['permission' => 'onesignal.settings'], function (): void {
                Route::get('/', [OneSignalSettingController::class, 'edit'])->name('settings');
                Route::put('/', [OneSignalSettingController::class, 'update'])->name('settings.update');
                Route::post('/send-test', [OneSignalSettingController::class, 'sendTest'])->name('settings.send-test');
            });
        });
    });
});
