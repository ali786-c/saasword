<?php

namespace Botble\SeoBoost;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\SeoBoost\Services\IndexNowService;
use Botble\Setting\Models\Setting;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function activate(): void
    {
        // Ensure an API key exists so the verification file route works right away.
        if (! setting('seo_boost_api_key')) {
            app(IndexNowService::class)->resetKey();
        }
    }

    public static function remove(): void
    {
        Schema::dropIfExists('index_now_logs');

        Setting::query()
            ->where('key', 'like', 'seo_boost_%')
            ->delete();
    }
}
