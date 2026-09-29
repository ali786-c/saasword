<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Language\Database\Seeders\LanguageSeeder;
use FriendsOfBotble\Comment\Database\Seeders\CommentSeeder;

class DatabaseSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->prepareRun();

        $this->call([
            UserSeeder::class,
            LanguageSeeder::class,
            PageSeeder::class,
            MenuSeeder::class,
            WidgetSeeder::class,
            ContactSeeder::class,
            ThemeOptionSeeder::class,
            BlogSeeder::class,
            GallerySeeder::class,
            AdsSeeder::class,
            SettingSeeder::class,
            AnnouncementSeeder::class,
            CommentSeeder::class,
        ]);

        $this->finished();
    }
}
