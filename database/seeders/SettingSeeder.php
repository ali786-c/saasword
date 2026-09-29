<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;

class SettingSeeder extends BaseSeeder
{
    public function run(): void
    {
        $settings = [
            'admin_favicon' => 'general/favicon.png',
            'admin_logo' => 'general/logo-white.png',
        ];

        $this->saveSettings($settings);
    }
}
