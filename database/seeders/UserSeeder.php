<?php

namespace Database\Seeders;

use Botble\ACL\Database\Seeders\UserSeeder as BaseUserSeeder;
use Botble\ACL\Models\User;
use Botble\Base\Facades\MetaBox;

class UserSeeder extends BaseUserSeeder
{
    public function run(): void
    {
        parent::run();

        $files = $this->uploadFiles('users');

        foreach (User::query()->get() as $user) {
            MetaBox::saveMetaBoxData(
                $user,
                'bio',
                'Hi, I’m ' . $user->name . ', Your Blogging Journey Guide 🖋️. Writing, one blog post at a time, to inspire, inform, and ignite your curiosity. Join me as we explore the world through words and embark on a limitless adventure of knowledge and creativity. Let’s bring your thoughts to life on these digital pages. 🌟 #BloggingAdventures'
            );

            $user->avatar_id = $files[0]['error'] ? 0 : $files[0]['data']->id;
            $user->save();
        }
    }
}
