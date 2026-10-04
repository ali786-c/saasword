<?php

namespace Botble\OneSignal;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Models\Setting;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Setting::query()
            ->where('key', 'like', 'onesignal_%')
            ->delete();
    }
}
