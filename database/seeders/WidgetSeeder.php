<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Theme\Facades\Theme;
use Botble\Widget\Models\Widget;

class WidgetSeeder extends BaseSeeder
{
    public function run(): void
    {
        Widget::query()->truncate();

        $data = [
            [
                'widget_id' => 'CustomMenuWidget',
                'sidebar_id' => 'footer_sidebar',
                'position' => 0,
                'data' => [
                    'id' => 'CustomMenuWidget',
                    'name' => 'Quick links',
                    'menu_id' => 'quick-links',
                ],
            ],
            [
                'widget_id' => 'TagsWidget',
                'sidebar_id' => 'footer_sidebar',
                'position' => 1,
                'data' => [
                    'id' => 'TagsWidget',
                    'name' => 'Tags',
                    'number_display' => 5,
                ],
            ],
            [
                'widget_id' => 'NewsletterWidget',
                'sidebar_id' => 'footer_sidebar',
                'position' => 2,
                'data' => [
                    'id' => 'NewsletterWidget',
                    'name' => 'Newsletter',
                ],
            ],
            [
                'widget_id' => 'AboutWidget',
                'sidebar_id' => 'primary_sidebar',
                'position' => 0,
                'data' => [
                    'id' => 'AboutWidget',
                    'name' => "Hello, I'm Steven",
                    'description' => 'Hi, I’m Steven, a Florida native, who left my career in corporate wealth management six years ago to embark on a summer of soul searching that would change the course of my life forever.',
                    'image' => 'general/author.jpg',
                ],
            ],
            [
                'widget_id' => 'PopularPostsWidget',
                'sidebar_id' => 'primary_sidebar',
                'position' => 1,
                'data' => [
                    'id' => 'PopularPostsWidget',
                    'name' => 'Most popular',
                    'number_display' => 5,
                ],
            ],
            [
                'widget_id' => 'GalleriesWidget',
                'sidebar_id' => 'primary_sidebar',
                'position' => 2,
                'data' => [
                    'id' => 'GalleriesWidget',
                    'name' => 'Galleries',
                    'number_display' => 6,
                ],
            ],
        ];

        $theme = Theme::getThemeName();

        foreach ($data as $item) {
            $item['theme'] = $theme;
            Widget::query()->create($item);
        }
    }
}
