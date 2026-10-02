<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (class_exists(\Botble\Blog\Models\Post::class)) {
            \Botble\Blog\Models\Post::saved(function () {
                \App\Services\LlmsTxtService::generate();
            });

            \Botble\Blog\Models\Post::deleted(function () {
                \App\Services\LlmsTxtService::generate();
            });
        }
    }
}
