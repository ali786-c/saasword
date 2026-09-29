<?php

use Botble\Theme\Events\RenderingThemeOptionSettings;

app('events')->listen(RenderingThemeOptionSettings::class, function (): void {
    theme_option()
        ->setField([
            'id' => 'designed_by',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'text',
            'label' => __('Designed by'),
            'attributes' => [
                'name' => 'designed_by',
                'value' => 'Designed by Your Company | All rights reserved.',
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 250,
                ],
            ],
        ])
        ->setField([
            'id' => 'panel_sidebar_enabled',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'select',
            'label' => __('Enable Panel Sidebar?'),
            'attributes' => [
                'name' => 'panel_sidebar_enabled',
                'list' => [
                    'no' => trans('core/base::base.no'),
                    'yes' => trans('core/base::base.yes'),
                ],
                'value' => 'yes',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id' => 'panel_sidebar_categories_widget_enabled',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'select',
            'label' => __('Enable Panel Sidebar Categories Widget (if panel sidebar enabled)?'),
            'attributes' => [
                'name' => 'panel_sidebar_categories_widget_enabled',
                'list' => [
                    'no' => trans('core/base::base.no'),
                    'yes' => trans('core/base::base.yes'),
                ],
                'value' => 'yes',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id' => 'panel_sidebar_latest_posts_widget_enabled',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'select',
            'label' => __('Enable Panel Sidebar Latest Posts Widget (if panel sidebar enabled)?'),
            'attributes' => [
                'name' => 'panel_sidebar_latest_posts_widget_enabled',
                'list' => [
                    'no' => trans('core/base::base.no'),
                    'yes' => trans('core/base::base.yes'),
                ],
                'value' => 'yes',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id' => 'preloader_enabled',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'select',
            'label' => __('Enable Preloader?'),
            'attributes' => [
                'name' => 'preloader_enabled',
                'list' => [
                    'no' => trans('core/base::base.no'),
                    'yes' => trans('core/base::base.yes'),
                ],
                'value' => 'no',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id' => 'animation_enabled',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'customSelect',
            'label' => __('Enable animation?'),
            'attributes' => [
                'name' => 'animation_enabled',
                'list' => [
                    'yes' => trans('core/base::base.yes'),
                    'no' => trans('core/base::base.no'),
                ],
                'value' => 'yes',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id' => 'primary_font',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'googleFonts',
            'label' => __('Primary font'),
            'attributes' => [
                'name' => 'primary_font',
                'value' => 'Roboto',
            ],
        ])
        ->setField([
            'id' => 'primary_color',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'customColor',
            'label' => __('Primary color'),
            'attributes' => [
                'name' => 'primary_color',
                'value' => '#5869DA',
            ],
        ])
        ->setField([
            'id' => 'secondary_color',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'customColor',
            'label' => __('Secondary color'),
            'attributes' => [
                'name' => 'secondary_color',
                'value' => '#2d3d8b',
            ],
        ])
        ->setField([
            'id' => 'danger_color',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'customColor',
            'label' => __('Danger color'),
            'attributes' => [
                'name' => 'danger_color',
                'value' => '#e3363e',
            ],
        ])
        ->setField([
            'id' => 'site_description',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'textarea',
            'label' => __('Site description'),
            'attributes' => [
                'name' => 'site_description',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 255,
                ],
            ],
        ])
        ->setField([
            'id' => 'address',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'text',
            'label' => __('Address'),
            'attributes' => [
                'name' => 'address',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 255,
                ],
            ],
        ])
        ->setSection([
            'title' => __('Header'),
            'desc' => __('Header config'),
            'id' => 'opt-text-subsection-header',
            'subsection' => true,
            'icon' => 'ti ti-brush',
        ])
        ->setField([
            'id' => 'action_button_text',
            'section_id' => 'opt-text-subsection-header',
            'type' => 'text',
            'label' => __('Action button text'),
            'attributes' => [
                'name' => 'action_button_text',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id' => 'action_button_url',
            'section_id' => 'opt-text-subsection-header',
            'type' => 'text',
            'label' => __('Action button URL'),
            'attributes' => [
                'name' => 'action_button_url',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id' => 'blog_single_layout',
            'section_id' => 'opt-text-subsection-blog',
            'type' => 'select',
            'label' => __('Default Blog Single Layout'),
            'attributes' => [
                'name' => 'blog_single_layout',
                'list' => get_blog_single_layouts(),
                'value' => 'blog-full-width',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id' => 'blog_layout',
            'section_id' => 'opt-text-subsection-blog',
            'type' => 'select',
            'label' => __('Blog Layout'),
            'attributes' => [
                'name' => 'blog_layout',
                'list' => get_blog_layouts(),
                'value' => 'grid',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id' => 'facebook_comment_enabled_in_gallery',
            'section_id' => 'opt-text-subsection-facebook-integration',
            'type' => 'customSelect',
            'label' => __('Enable Facebook comment in the gallery detail?'),
            'attributes' => [
                'name' => 'facebook_comment_enabled_in_gallery',
                'list' => [
                    'no' => trans('core/base::base.no'),
                    'yes' => trans('core/base::base.yes'),
                ],
                'value' => 'no',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ]);
});
