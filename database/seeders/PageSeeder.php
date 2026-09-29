<?php

namespace Database\Seeders;

use Botble\Base\Facades\Html;
use Botble\Base\Supports\BaseSeeder;
use Botble\Page\Models\Page;
use Botble\Slug\Facades\SlugHelper;
use Botble\Slug\Models\Slug;
use Illuminate\Support\Str;

class PageSeeder extends BaseSeeder
{
    public function run(): void
    {
        $pages = [
            [
                'name' => 'Home',
                'content' =>
                    Html::tag(
                        'div',
                        '[about-banner title="Hello, I’m &lt;span&gt;Steven&lt;/span&gt;" subtitle="Welcome to my blog" text_muted="Travel Blogger., Content Writer., Food Guides" image="general/featured.png" newsletter_title="Don\'t miss out on the latest news about Travel tips, Hotels review, Food guide..." image="general/featured.png" show_newsletter_form="yes"][/about-banner]'
                    ) .
                    Html::tag('div', '[featured-posts title="Featured posts" enable_lazy_loading="yes"][/featured-posts]') .
                    Html::tag('div', '[blog-categories-posts category_id="2" enable_lazy_loading="yes"][/blog-categories-posts]') .
                    Html::tag(
                        'div',
                        '[categories-with-posts category_id_1="3" category_id_2="4" category_id_3="5" enable_lazy_loading="yes"][/categories-with-posts]'
                    ) .
                    Html::tag('div', '[featured-categories title="Categories" enable_lazy_loading="yes"][/featured-categories]')
                ,
                'template' => 'homepage',
            ],
            [
                'name' => 'Home 2',
                'content' =>
                    Html::tag('div', '[featured-posts-slider-full][/featured-posts-slider-full]') .
                    Html::tag('div', '[blog-categories-posts category_id="2" enable_lazy_loading="yes"][/blog-categories-posts]') .
                    Html::tag(
                        'div',
                        '[categories-with-posts category_id_1="3" category_id_2="4" category_id_3="5" enable_lazy_loading="yes"][/categories-with-posts]'
                    ) .
                    Html::tag('div', '[featured-categories title="Categories" enable_lazy_loading="yes"][/featured-categories]')
                ,
                'template' => 'homepage',
            ],
            [
                'name' => 'Home 3',
                'content' =>
                    Html::tag('div', '[featured-posts-slider][/featured-posts-slider]') .
                    Html::tag('div', '[blog-categories-posts category_id="2" enable_lazy_loading="yes"][/blog-categories-posts]') .
                    Html::tag(
                        'div',
                        '[categories-with-posts category_id_1="3" category_id_2="4" category_id_3="5" enable_lazy_loading="yes"][/categories-with-posts]'
                    ) .
                    Html::tag('div', '[featured-categories title="Categories" enable_lazy_loading="yes"][/featured-categories]')
                ,
                'template' => 'homepage',
            ],
            [
                'name' => 'Blog',
                'content' => '---',
                'template' => 'default',
            ],
            [
                'name' => 'Contact',
                'content' => Html::tag(
                    'p',
                    'Address: North Link Building, 10 Admiralty Street, 757695 Singapore'
                ) .
                    Html::tag('p', 'Hotline: 18006268') .
                    Html::tag('p', 'Email: contact@botble.com') .
                    Html::tag(
                        'p',
                        '[google-map]North Link Building, 10 Admiralty Street, 757695 Singapore[/google-map]'
                    ) .
                    Html::tag('p', 'For the fastest reply, please use the contact form below.') .
                    Html::tag('p', '[contact-form][/contact-form]'),
                'template' => 'default',
            ],
            [
                'name' => 'Cookie Policy',
                'content' => Html::tag('h3', 'EU Cookie Consent') .
                    Html::tag(
                        'p',
                        'To use this website we are using Cookies and collecting some Data. To be compliant with the EU GDPR we give you to choose if you allow us to use certain Cookies and to collect some Data.'
                    ) .
                    Html::tag('h4', 'Essential Data') .
                    Html::tag(
                        'p',
                        'The Essential Data is needed to run the Site you are visiting technically. You can not deactivate them.'
                    ) .
                    Html::tag(
                        'p',
                        '- Session Cookie: PHP uses a Cookie to identify user sessions. Without this Cookie the Website is not working.'
                    ) .
                    Html::tag(
                        'p',
                        '- XSRF-Token Cookie: Laravel automatically generates a CSRF "token" for each active user session managed by the application. This token is used to verify that the authenticated user is the one actually making the requests to the application.'
                    ),
                'template' => 'default',
            ],
            [
                'name' => 'Blog List layout',
                'content' => Html::tag('div', '[blog-list limit="12"][/blog-list]'),
                'template' => 'right-sidebar',
            ],
            [
                'name' => 'Blog Big layout',
                'content' => Html::tag('div', '[blog-big limit="12"][/blog-big]'),
                'template' => 'default',
            ],
            [
                'name' => 'Blog Grid layout',
                'content' => Html::tag('div', '[blog-big limit="12"][/blog-big]'),
                'template' => 'right-sidebar',
            ],
        ];

        Page::query()->truncate();

        foreach ($pages as $item) {
            $item['user_id'] = 1;
            $page = Page::query()->create($item);

            Slug::query()->create([
                'reference_type' => Page::class,
                'reference_id' => $page->id,
                'key' => Str::slug($page->name),
                'prefix' => SlugHelper::getPrefix(Page::class),
            ]);
        }
    }
}
