<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Blog\Models\Category;
use Botble\Menu\Database\Traits\HasMenuSeeder;
use Botble\Page\Models\Page;

class MenuSeeder extends BaseSeeder
{
    use HasMenuSeeder;

    public function run(): void
    {
        $data = [
            [
                'name' => 'Main menu',
                'slug' => 'main-menu',
                'location' => 'main-menu',
                'items' => [
                    [
                        'title' => 'Home',
                        'url' => '/',
                        'icon_font' => 'elegant-icon icon_house_alt mr-5',
                        'children' => [
                            [
                                'title' => 'Home default',
                                'url' => '/',
                            ],
                            [
                                'title' => 'Home 2',
                                'reference_id' => 2,
                                'reference_type' => Page::class,
                            ],
                            [
                                'title' => 'Home 3',
                                'reference_id' => 3,
                                'reference_type' => Page::class,
                            ],
                        ],
                    ],
                    [
                        'title' => 'Travel',
                        'reference_id' => 2,
                        'reference_type' => Category::class,
                    ],
                    [
                        'title' => 'Destination',
                        'reference_id' => 4,
                        'reference_type' => Category::class,
                    ],
                    [
                        'title' => 'Hotels',
                        'reference_id' => 6,
                        'reference_type' => Category::class,
                    ],
                    [
                        'title' => 'Lifestyle',
                        'reference_id' => 9,
                        'reference_type' => Category::class,
                    ],
                    [
                        'title' => 'Blog',
                        'reference_id' => 4,
                        'reference_type' => Page::class,
                        'children' => [
                            [
                                'title' => 'Grid layout',
                                'reference_id' => 9,
                                'reference_type' => Page::class,
                            ],
                            [
                                'title' => 'List layout',
                                'reference_id' => 7,
                                'reference_type' => Page::class,
                            ],
                            [
                                'title' => 'Big layout',
                                'reference_id' => 8,
                                'reference_type' => Page::class,
                            ],
                        ],
                    ],
                    [
                        'title' => 'Galleries',
                        'url' => '/galleries',
                    ],
                    [
                        'title' => 'Contact',
                        'reference_id' => 5,
                        'reference_type' => Page::class,
                    ],
                ],
            ],
            [
                'name' => 'Quick links',
                'slug' => 'quick-links',
                'items' => [
                    [
                        'title' => 'Homepage',
                        'url' => '/',
                    ],
                    [
                        'title' => 'Contact',
                        'reference_id' => 5,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Blog',
                        'reference_id' => 4,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Travel',
                        'reference_id' => 2,
                        'reference_type' => Category::class,
                    ],
                    [
                        'title' => 'Galleries',
                        'url' => '/galleries',
                    ],
                ],
            ],
        ];

        $this->createMenus($data);
    }
}
