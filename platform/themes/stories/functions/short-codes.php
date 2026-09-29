<?php

use Botble\Ads\Facades\AdsManager;
use Botble\Ads\Models\Ads;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Blog\Models\Category;
use Botble\Blog\Repositories\Interfaces\CategoryInterface;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Supports\ThemeSupport;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Arr;

app('events')->listen(RouteMatched::class, function (): void {
    ThemeSupport::registerGoogleMapsShortcode();
    ThemeSupport::registerYoutubeShortcode();

    if (is_plugin_active('blog')) {
        add_shortcode(
            'featured-categories',
            __('Featured categories'),
            __('Add featured categories'),
            function ($shortcode) {
                return Theme::partial('short-codes.featured-categories', ['title' => $shortcode->title, 'shortcode' => $shortcode]);
            }
        );

        shortcode()->setAdminConfig('featured-categories', function ($attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add('title', TextField::class, [
                    'label' => __('Title'),
                    'attr' => [
                        'placeholder' => __('Title'),
                    ],
                ])
                ->add(
                    'number_items_to_show',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Number items to show'))
                        ->choices([
                            2 => '2',
                            3 => '3',
                            4 => '4',
                            5 => '5',
                            6 => '6',
                        ])
                        ->defaultValue(3)
                )
                ->add('limit', NumberField::class, [
                    'label' => __('Limit'),
                    'attr' => [
                        'placeholder' => __('Limit'),
                    ],
                    'default_value' => 10,
                ]);
        });

        add_shortcode('featured-posts', __('Featured posts'), __('Add featured posts'), function ($shortcode) {
            $limit = (int) $shortcode->limit ?: 6;

            $featuredPosts = get_featured_posts($limit, ['slugable', 'categories', 'categories.slugable', 'metadata']);

            $popularTags = get_popular_tags(4);

            return Theme::partial('short-codes.featured-posts', [
                'title' => $shortcode->title,
                'featuredPosts' => $featuredPosts,
                'popularTags' => $popularTags,
            ]);
        });

        shortcode()->setAdminConfig('featured-posts', function ($attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add('title', TextField::class, [
                    'label' => __('Title'),
                    'attr' => [
                        'placeholder' => __('Title'),
                    ],
                ])
                ->add('limit', NumberField::class, [
                    'label' => __('Limit'),
                    'attr' => [
                        'placeholder' => __('Limit'),
                    ],
                    'default_value' => 8,
                ]);
        });

        add_shortcode(
            'blog-categories-posts',
            __('Blog categories posts'),
            __('Blog categories posts'),
            function ($shortcode) {
                $category = app(CategoryInterface::class)
                    ->findById($shortcode->category_id, [
                        'slugable',
                        'posts' => function ($query): void {
                            $query
                                ->latest()
                                ->with(['slugable', 'categories', 'categories.slugable', 'metadata'])
                                ->where('status', BaseStatusEnum::PUBLISHED)
                                ->limit(4);
                        },
                    ]);

                if (! $category) {
                    return null;
                }

                return Theme::partial('short-codes.blog-categories-posts', compact('category'));
            }
        );

        shortcode()->setAdminConfig('blog-categories-posts', function ($attributes) {
            $categories = app(CategoryInterface::class)->allBy(['status' => BaseStatusEnum::PUBLISHED]);

            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add(
                    'category_id',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Category'))
                        ->choices($categories->pluck('name', 'id')->all())
                        ->searchable()
                );
        });

        add_shortcode(
            'categories-with-posts',
            __('Categories with Posts'),
            __('Categories with Posts'),
            function ($shortcode) {
                $attributes = $shortcode->toArray();

                $categoryIds = [];

                for ($i = 1; $i <= 3; $i++) {
                    if (! Arr::has($attributes, 'category_id_' . $i)) {
                        continue;
                    }

                    $categoryIds[] = Arr::get($attributes, 'category_id_' . $i);
                }

                $categories = app(CategoryInterface::class)->advancedGet([
                    'condition' => [['id', 'IN', $categoryIds]],
                    'with' => ['slugable'],
                ]);

                $categories->each(function (Category $category): void {
                    $category->load(['posts' => function (Builder $query) {
                        return $query
                            ->latest()
                            ->with(['slugable'])
                            ->where('status', BaseStatusEnum::PUBLISHED)
                            ->limit(3);
                    }]);
                });

                return Theme::partial('short-codes.categories-with-posts', compact('categories'));
            }
        );

        shortcode()->setAdminConfig('categories-with-posts', function ($attributes) {
            $categories = app(CategoryInterface::class)->allBy(['status' => BaseStatusEnum::PUBLISHED]);
            $categoryChoices = ['' => __('-- Select --')] + $categories->pluck('name', 'id')->all();

            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add(
                    'category_id_1',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Category 1'))
                        ->choices($categoryChoices)
                        ->searchable()
                )
                ->add(
                    'category_id_2',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Category 2'))
                        ->choices($categoryChoices)
                        ->searchable()
                )
                ->add(
                    'category_id_3',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Category 3'))
                        ->choices($categoryChoices)
                        ->searchable()
                );
        });

        add_shortcode('featured-posts-slider', __('Featured posts slider'), __('Featured posts slider'), function ($shortcode) {
            $limit = (int) $shortcode->limit ?: 3;

            $featuredPosts = get_featured_posts($limit, ['slugable', 'categories', 'categories.slugable', 'metadata']);

            return Theme::partial('short-codes.featured-posts-slider', compact('featuredPosts'));
        });

        shortcode()->setAdminConfig('featured-posts-slider', function ($attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add('limit', NumberField::class, [
                    'label' => __('Limit'),
                    'attr' => [
                        'placeholder' => __('Limit'),
                    ],
                    'default_value' => 8,
                ]);
        });

        add_shortcode(
            'featured-posts-slider-full',
            __('Featured posts slider full'),
            __('Featured posts slider full'),
            function ($shortcode) {
                $limit = (int) $shortcode->limit ?: 5;

                $featuredPosts = get_featured_posts($limit, ['slugable', 'categories', 'categories.slugable', 'metadata']);

                return Theme::partial('short-codes.featured-posts-slider-full', compact('featuredPosts'));
            }
        );

        shortcode()->setAdminConfig('featured-posts-slider-full', function ($attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add('limit', NumberField::class, [
                    'label' => __('Limit'),
                    'attr' => [
                        'placeholder' => __('Limit'),
                    ],
                    'default_value' => 8,
                ]);
        });

        add_shortcode('blog-list', __('Blog list'), __('Add blog posts list'), function ($shortcode) {
            $limit = $shortcode->limit ?: 12;

            $posts = get_all_posts(true, $limit);

            return Theme::partial('short-codes.blog-list', compact('posts'));
        });

        shortcode()->setAdminConfig('blog-list', function ($attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add('limit', NumberField::class, [
                    'label' => __('Limit'),
                    'attr' => [
                        'placeholder' => __('Limit'),
                    ],
                ]);
        });

        add_shortcode('blog-big', __('Blog big'), __('Add blog posts big'), function ($shortcode) {
            $limit = $shortcode->limit ?: 12;

            $posts = get_all_posts(true, $limit);

            return Theme::partial('short-codes.blog-big', compact('posts'));
        });

        shortcode()->setAdminConfig('blog-big', function ($attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add('limit', NumberField::class, [
                    'label' => __('Limit'),
                    'attr' => [
                        'placeholder' => __('Limit'),
                    ],
                ]);
        });
    }

    add_shortcode('about-banner', __('About banner'), __('About banner'), function ($shortcode) {
        return Theme::partial('short-codes.about-banner', [
            'title' => $shortcode->title,
            'subtitle' => $shortcode->subtitle,
            'textMuted' => $shortcode->text_muted,
            'newsletterTitle' => $shortcode->newsletter_title,
            'image' => $shortcode->image,
            'showNewsletterForm' => $shortcode->show_newsletter_form,
        ]);
    });

    shortcode()->setAdminConfig('about-banner', function ($attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add('title', TextField::class, [
                'label' => __('Title'),
                'attr' => [
                    'placeholder' => __('Title'),
                ],
            ])
            ->add('subtitle', TextField::class, [
                'label' => __('Subtitle'),
                'attr' => [
                    'placeholder' => __('Subtitle'),
                ],
            ])
            ->add('text_muted', TextField::class, [
                'label' => __('Text muted'),
                'attr' => [
                    'placeholder' => __('Text muted'),
                ],
            ])
            ->add('newsletter_title', TextField::class, [
                'label' => __('Newsletter title'),
                'attr' => [
                    'placeholder' => __('Newsletter title'),
                ],
            ])
            ->add('image', MediaImageField::class, [
                'label' => __('Image'),
            ])
            ->add(
                'show_newsletter_form',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Show newsletter form?'))
                    ->choices([
                        'yes' => __('Yes'),
                        'no' => __('No'),
                    ])
                    ->defaultValue('yes')
            );
    });

    if (is_plugin_active('ads')) {
        add_shortcode('theme-ads', __('Theme ads'), __('Theme ads'), function ($shortcode) {
            $ads = [];
            $attributes = $shortcode->toArray();

            for ($i = 1; $i < 5; $i++) {
                if (isset($attributes['key_' . $i]) && ! empty($attributes['key_' . $i])) {
                    $ad = AdsManager::displayAds((string) $attributes['key_' . $i]);
                    if ($ad) {
                        $ads[] = $ad;
                    }
                }
            }

            $ads = array_filter($ads);

            return Theme::partial('short-codes.theme-ads', compact('ads'));
        });

        shortcode()->setAdminConfig('theme-ads', function ($attributes) {
            $ads = Ads::query()
                ->wherePublished()
                ->notExpired()
                ->get();

            $adsChoices = ['' => __('-- Select --')] + $ads->pluck('name', 'key')->all();

            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add(
                    'key_1',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Ad #1'))
                        ->choices($adsChoices)
                        ->searchable()
                )
                ->add(
                    'key_2',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Ad #2'))
                        ->choices($adsChoices)
                        ->searchable()
                )
                ->add(
                    'key_3',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Ad #3'))
                        ->choices($adsChoices)
                        ->searchable()
                )
                ->add(
                    'key_4',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Ad #4'))
                        ->choices($adsChoices)
                        ->searchable()
                );
        });
    }

    if (is_plugin_active('gallery')) {
        add_filter('galleries_box_template_view', function () {
            return Theme::getThemeNamespace() . '::partials.short-codes.galleries';
        }, 120);

        shortcode()->setAdminConfig('gallery', function ($attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add('title', TextField::class, [
                    'label' => __('Title'),
                    'attr' => [
                        'placeholder' => __('Title'),
                    ],
                ])
                ->add('limit', NumberField::class, [
                    'label' => __('Limit'),
                    'attr' => [
                        'placeholder' => __('Limit'),
                    ],
                    'default_value' => 8,
                ]);
        });
    }
});
