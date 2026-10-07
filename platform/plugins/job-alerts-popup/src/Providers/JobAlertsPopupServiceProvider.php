<?php

namespace Botble\JobAlertsPopup\Providers;

use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Theme\Events\RenderingThemeOptionSettings;
use Illuminate\Routing\Events\RouteMatched;

class JobAlertsPopupServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/job-alerts-popup')
            ->loadAndPublishConfigurations(['general'])
            ->loadAndPublishViews();

        $hook = defined('THEME_FRONT_FOOTER') ? THEME_FRONT_FOOTER : 'theme-front-footer';
        add_filter($hook, [$this, 'renderJobAlertsPopup'], 1350);
        add_filter('theme-front-footer', [$this, 'renderJobAlertsPopup'], 1350);

        $this->app['events']->listen(RenderingThemeOptionSettings::class, function (): void {
            theme_option()
                ->setSection([
                    'title' => __('Job Alerts Popup'),
                    'id' => 'opt-text-subsection-job-alerts-popup',
                    'subsection' => true,
                    'icon' => 'ti ti-bell-ringing',
                    'priority' => 9998,
                    'fields' => [
                        [
                            'id' => 'job_alerts_popup_enable',
                            'type' => 'customSelect',
                            'label' => __('Enable Job Alerts Popup'),
                            'attributes' => [
                                'name' => 'job_alerts_popup_enable',
                                'list' => [
                                    'yes' => __('Yes'),
                                    'no' => __('No'),
                                ],
                                'value' => 'yes',
                                'options' => [
                                    'class' => 'form-control',
                                ],
                            ],
                        ],
                        [
                            'id' => 'job_alerts_popup_title',
                            'type' => 'text',
                            'label' => __('Popup Title'),
                            'attributes' => [
                                'name' => 'job_alerts_popup_title',
                                'value' => theme_option('job_alerts_popup_title', 'Get Job Alerts First!'),
                                'options' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'Get Job Alerts First!',
                                ],
                            ],
                        ],
                        [
                            'id' => 'job_alerts_popup_subtitle',
                            'type' => 'text',
                            'label' => __('Popup Subtitle'),
                            'attributes' => [
                                'name' => 'job_alerts_popup_subtitle',
                                'value' => theme_option('job_alerts_popup_subtitle', 'Join our channels and never miss a new job posting.'),
                                'options' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'Join our channels and never miss a new job posting.',
                                ],
                            ],
                        ],
                        [
                            'id' => 'job_alerts_popup_whatsapp_url',
                            'type' => 'text',
                            'label' => __('WhatsApp Channel URL'),
                            'attributes' => [
                                'name' => 'job_alerts_popup_whatsapp_url',
                                'value' => theme_option('job_alerts_popup_whatsapp_url', 'https://whatsapp.com/channel/0029Va...'),
                                'options' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'https://whatsapp.com/channel/...',
                                ],
                            ],
                        ],
                        [
                            'id' => 'job_alerts_popup_whatsapp_text',
                            'type' => 'text',
                            'label' => __('WhatsApp Button Text'),
                            'attributes' => [
                                'name' => 'job_alerts_popup_whatsapp_text',
                                'value' => theme_option('job_alerts_popup_whatsapp_text', 'Join WhatsApp'),
                                'options' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'Join WhatsApp',
                                ],
                            ],
                        ],
                        [
                            'id' => 'job_alerts_popup_frequency',
                            'type' => 'customSelect',
                            'label' => __('Popup Frequency'),
                            'attributes' => [
                                'name' => 'job_alerts_popup_frequency',
                                'list' => [
                                    'once_per_session' => __('Once per session (Recommended)'),
                                    'always' => __('Always show on every page visit'),
                                    'once_per_day' => __('Once per 24 hours'),
                                ],
                                'value' => 'once_per_session',
                                'options' => [
                                    'class' => 'form-control',
                                ],
                            ],
                        ],
                        [
                            'id' => 'job_alerts_popup_delay',
                            'type' => 'number',
                            'label' => __('Display Delay (Milliseconds)'),
                            'attributes' => [
                                'name' => 'job_alerts_popup_delay',
                                'value' => theme_option('job_alerts_popup_delay', 500),
                                'options' => [
                                    'class' => 'form-control',
                                    'placeholder' => '500',
                                ],
                            ],
                        ],
                    ],
                ]);
        });
    }

    public function renderJobAlertsPopup(?string $html): string
    {
        $enable = theme_option('job_alerts_popup_enable', 'yes');
        if (in_array($enable, ['no', '0', 'false', false], true)) {
            return $html ?? '';
        }

        $title = theme_option('job_alerts_popup_title') ?: 'Get Job Alerts First!';
        $subtitle = theme_option('job_alerts_popup_subtitle') ?: 'Join our WhatsApp channel and never miss a new job posting.';
        $whatsappUrl = theme_option('job_alerts_popup_whatsapp_url') ?: 'https://whatsapp.com/channel/0029Vb1vBU95a249w33Gha16';
        $whatsappText = theme_option('job_alerts_popup_whatsapp_text') ?: 'Join WhatsApp Channel';
        $frequency = theme_option('job_alerts_popup_frequency') ?: 'once_per_session';
        $delay = (int) (theme_option('job_alerts_popup_delay') ?: 500);

        $popupHtml = view('plugins/job-alerts-popup::popup', compact(
            'title',
            'subtitle',
            'whatsappUrl',
            'whatsappText',
            'frequency',
            'delay'
        ))->render();

        return ($html ?? '') . $popupHtml;
    }
}
