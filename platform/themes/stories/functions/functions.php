<?php

use Botble\ACL\Forms\ProfileForm;
use Botble\Ads\Facades\AdsManager;
use Botble\Base\Forms\FieldOptions\EditorFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Models\MetaBox as MetaBoxModel;
use Botble\Blog\Forms\CategoryForm;
use Botble\Blog\Forms\PostForm;
use Botble\Blog\Models\Category;
use Botble\Menu\Facades\Menu;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Supports\ThemeSupport;
use Botble\Theme\Typography\TypographyItem;

app()->booted(function (): void {
    ThemeSupport::registerSocialLinks();
    ThemeSupport::registerSocialSharing();
    ThemeSupport::registerSiteLogoHeight();
    ThemeSupport::registerSiteCopyright();
    ThemeSupport::registerDateFormatOption();

    Theme::typography()
        ->registerFontFamilies([
            new TypographyItem('primary', __('Primary'), theme_option('primary_font', 'Noto Sans JP'), [400, 500, 700, 900]),
        ]);

    register_page_template([
        'full-width' => __('Full width'),
        'homepage' => __('Homepage'),
        'right-sidebar' => __('Right sidebar'),
    ]);

    register_sidebar([
        'id' => 'footer_sidebar',
        'name' => __('Footer sidebar'),
        'description' => __('Sidebar in the footer of page'),
    ]);

    Menu::addMenuLocation('header-menu', __('Header Navigation'));

    if (is_plugin_active('blog')) {
        CategoryForm::extend(function (CategoryForm $form): void {
            $form->add('image', MediaImageField::class, MediaImageFieldOption::make()->metadata()->toArray());
        });

        PostForm::extend(function (PostForm $form): void {
            $form
                ->add(
                    'time_to_read',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->label(__('Time to read (minute)'))
                        ->metadata()
                        ->toArray()
                )
                ->add(
                    'layout',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Layout'))
                        ->choices(get_blog_single_layouts())
                        ->when(! $form->getModel()->id, function (SelectFieldOption $option): void {
                            $option->selected(theme_option('blog_single_layout'));
                        })
                        ->metadata()
                        ->toArray()
                );
        });

        Category::resolveRelationUsing('image', function ($model) {
            return $model->morphOne(MetaBoxModel::class, 'reference')->where('meta_key', 'image');
        });
    }

    if (is_plugin_active('ads')) {
        AdsManager::registerLocation('panel-ads', __('Panel Ads'))
            ->registerLocation('top-sidebar-ads', __('Top Sidebar Ads'))
            ->registerLocation('bottom-sidebar-ads', __('Bottom Sidebar Ads'));
    }

    ProfileForm::extend(function (ProfileForm $form): void {
        $form
            ->add(
                'bio',
                EditorField::class,
                EditorFieldOption::make()
                    ->label(__('Bio (Write something about yourself...)'))
                    ->placeholder(__('Write something about yourself...'))
                    ->metadata()
                    ->colspan(2)
                    ->toArray()
            );
    });
});

if (! function_exists('random_color')) {
    function random_color(): string
    {
        $colors = ['warning', 'primary', 'info', 'success'];

        return 'text-' . $colors[array_rand($colors)];
    }
}

if (! function_exists('get_blog_single_layouts')) {
    function get_blog_single_layouts(): array
    {
        return [
            '' => __('Inherit'),
            'blog-right-sidebar' => __('Blog Right Sidebar'),
            'blog-left-sidebar' => __('Blog Left Sidebar'),
            'blog-full-width' => __('Full width'),
        ];
    }
}

if (! function_exists('get_blog_layouts')) {
    function get_blog_layouts(): array
    {
        return [
            'grid' => __('Grid layout'),
            'list' => __('List layout'),
            'big' => __('Big layout'),
        ];
    }
}

if (! function_exists('display_ad')) {
    function display_ad(string $location, array $attributes = []): string
    {
        if (! is_plugin_active('ads')) {
            return '';
        }

        return AdsManager::display($location, $attributes);
    }
}
