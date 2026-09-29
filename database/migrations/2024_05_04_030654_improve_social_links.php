<?php

use Botble\Setting\Facades\Setting;
use Botble\Theme\Facades\ThemeOption;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration
{
    public function up(): void
    {
        $socialLinks = [];
        for ($i = 1; $i <= 10; $i++) {
            if (
                theme_option('social_' . $i . '_name')
                && theme_option('social_' . $i . '_url')
                && theme_option('social_' . $i . '_icon')
            ) {
                $socialLinks[] = [
                    ['key' => 'name', 'value' => theme_option('social_' . $i . '_name')],
                    ['key' => 'icon', 'value' => 'elegant-icon ' . theme_option('social_' . $i . '_icon')],
                    ['key' => 'url', 'value' => theme_option('social_' . $i . '_url')],
                    ['key' => 'icon_image', 'value' => null],
                    ['key' => 'color', 'value' => '#fff'],
                    ['key' => 'background-color', 'value' => theme_option('social_' . $i . '_color')],
                ];
            }
        }

        Setting::delete('social_links');

        $socialSharingButtons = [
            [
                ['key' => 'social', 'value' => 'facebook'],
                ['key' => 'icon', 'value' => 'ti ti-brand-facebook'],
                ['key' => 'icon_image', 'value' => null],
                ['key' => 'color', 'value' => '#fff'],
                ['key' => 'background_color', 'value' => '#3b5999'],
            ],
            [
                ['key' => 'social', 'value' => 'x'],
                ['key' => 'icon', 'value' => 'ti ti-brand-twitter'],
                ['key' => 'icon_image', 'value' => null],
                ['key' => 'color', 'value' => '#fff'],
                ['key' => 'background_color', 'value' => '#55acee'],
            ],
            [
                ['key' => 'social', 'value' => 'linkedin'],
                ['key' => 'icon', 'value' => 'ti ti-brand-linkedin'],
                ['key' => 'icon_image', 'value' => null],
                ['key' => 'color', 'value' => '#fff'],
                ['key' => 'background_color', 'value' => '#0271ae'],
            ],
        ];

        Setting::set(ThemeOption::prepareFromArray([
            'social_links' => $socialLinks,
            'social_sharing' => $socialSharingButtons,
        ]));

        Setting::save();
    }
};
