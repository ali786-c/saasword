<?php

namespace Botble\OneSignal\Forms;

use Botble\Base\Forms\FieldOptions\AlertFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\AlertField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\OneSignal\Http\Requests\OneSignalSettingRequest;
use Botble\Setting\Forms\SettingForm;

class OneSignalSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/onesignal::onesignal.settings_title'))
            ->setSectionDescription(trans('plugins/onesignal::onesignal.settings_description'))
            ->setFormOption('id', 'onesignal-settings-form')
            ->setValidatorClass(OneSignalSettingRequest::class)
            ->setActionButtons(view('core/setting::forms.partials.action', ['form' => $this->getFormOption('id')])->render())
            ->add(
                'onesignal_enabled',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/onesignal::onesignal.enable'))
                    ->helperText(trans('plugins/onesignal::onesignal.enable_helper'))
                    ->value($targetValue = old('onesignal_enabled', setting('onesignal_enabled', 0)))
            )
            ->addOpenCollapsible('onesignal_enabled', '1', $targetValue)
            
            // Section 1: API Keys & Credentials
            ->add(
                'onesignal_credentials_info',
                AlertField::class,
                AlertFieldOption::make()
                    ->type('info')
                    ->content('<strong>1. API Credentials & Authentication</strong>')
            )
            ->add(
                'onesignal_app_id',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/onesignal::onesignal.app_id'))
                    ->placeholder(trans('plugins/onesignal::onesignal.app_id_placeholder'))
                    ->value(setting('onesignal_app_id'))
            )
            ->add(
                'onesignal_rest_api_key',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/onesignal::onesignal.rest_api_key'))
                    ->placeholder(trans('plugins/onesignal::onesignal.rest_api_key_placeholder'))
                    ->value(setting('onesignal_rest_api_key'))
            )
            ->add(
                'onesignal_safari_web_id',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/onesignal::onesignal.safari_web_id'))
                    ->placeholder(trans('plugins/onesignal::onesignal.safari_web_id_placeholder'))
                    ->value(setting('onesignal_safari_web_id'))
            )

            // Section 2: Post Delivery Rules & UTM Parameters
            ->add(
                'onesignal_rules_info',
                AlertField::class,
                AlertFieldOption::make()
                    ->type('info')
                    ->content('<strong>2. Automatic Delivery Rules & UTM Tracking</strong>')
            )
            ->add(
                'onesignal_auto_send_on_post_publish',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label('Automatically Send Push Notification when Post is Published')
                    ->helperText('When a new post is published, automatically blast a push notification to subscribers.')
                    ->value(setting('onesignal_auto_send_on_post_publish', 1))
            )
            ->add(
                'onesignal_auto_send_on_post_update',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label('Automatically Send Push Notification when Post is Updated')
                    ->helperText('When an existing post is updated, automatically resend a push notification.')
                    ->value(setting('onesignal_auto_send_on_post_update', 0))
            )
            ->add(
                'onesignal_send_to_mobile_platforms',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label('Include Mobile App Subscribers (iOS / Android)')
                    ->helperText('If enabled, mobile app subscribers in OneSignal will also receive notifications.')
                    ->value(setting('onesignal_send_to_mobile_platforms', 0))
            )
            ->add(
                'onesignal_default_title_prefix',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/onesignal::onesignal.default_title_prefix'))
                    ->placeholder(trans('plugins/onesignal::onesignal.default_title_prefix_placeholder'))
                    ->value(setting('onesignal_default_title_prefix'))
            )
            ->add(
                'onesignal_utm_additional_url_params',
                TextField::class,
                TextFieldOption::make()
                    ->label('Additional UTM URL Tracking Parameters')
                    ->placeholder('e.g. utm_source=onesignal&utm_medium=push&utm_campaign=jobs')
                    ->value(setting('onesignal_utm_additional_url_params'))
                    ->helperText('Appends tracking parameters to every notification URL for Google Analytics tracking.')
            )

            // Section 3: Subscription Prompt Customization
            ->add(
                'onesignal_prompt_info',
                AlertField::class,
                AlertFieldOption::make()
                    ->type('info')
                    ->content('<strong>3. Visitor Subscription Prompt Customization</strong>')
            )
            ->add(
                'onesignal_prompt_action_message',
                TextField::class,
                TextFieldOption::make()
                    ->label('Subscription Action Message')
                    ->placeholder('e.g. We\'d like to show you notifications for the latest jobs and updates.')
                    ->value(setting('onesignal_prompt_action_message', "We'd like to send you notifications for the latest updates."))
            )
            ->add(
                'onesignal_prompt_accept_button_text',
                TextField::class,
                TextFieldOption::make()
                    ->label('Accept Button Text')
                    ->placeholder('e.g. Allow')
                    ->value(setting('onesignal_prompt_accept_button_text', 'Allow'))
            )
            ->add(
                'onesignal_prompt_cancel_button_text',
                TextField::class,
                TextFieldOption::make()
                    ->label('Cancel Button Text')
                    ->placeholder('e.g. No Thanks')
                    ->value(setting('onesignal_prompt_cancel_button_text', 'No Thanks'))
            )

            // Section 4: Bell Notify Widget Customization
            ->add(
                'onesignal_bell_info',
                AlertField::class,
                AlertFieldOption::make()
                    ->type('info')
                    ->content('<strong>4. Subscription Bell Icon (Floating Widget)</strong>')
            )
            ->add(
                'onesignal_notify_button_enable',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label('Enable Floating Subscription Bell Widget')
                    ->helperText('Shows a floating bell widget on the corner of your website for visitors to manage push subscriptions.')
                    ->value(setting('onesignal_notify_button_enable', 1))
            )
            ->add(
                'onesignal_notify_button_position',
                SelectField::class,
                SelectFieldOption::make()
                    ->label('Bell Widget Position')
                    ->choices([
                        'bottom-right' => 'Bottom Right',
                        'bottom-left' => 'Bottom Left',
                    ])
                    ->selected(setting('onesignal_notify_button_position', 'bottom-right'))
            )
            ->add(
                'onesignal_notify_button_size',
                SelectField::class,
                SelectFieldOption::make()
                    ->label('Bell Widget Size')
                    ->choices([
                        'small' => 'Small',
                        'medium' => 'Medium',
                        'large' => 'Large',
                    ])
                    ->selected(setting('onesignal_notify_button_size', 'medium'))
            )
            ->add(
                'onesignal_notify_button_theme',
                SelectField::class,
                SelectFieldOption::make()
                    ->label('Bell Widget Theme')
                    ->choices([
                        'default' => 'Red / Default Theme',
                        'inverse' => 'Dark / Inverse Theme',
                    ])
                    ->selected(setting('onesignal_notify_button_theme', 'default'))
            )

            // Section 5: Welcome Notification Settings
            ->add(
                'onesignal_welcome_info',
                AlertField::class,
                AlertFieldOption::make()
                    ->type('info')
                    ->content('<strong>5. First-Time Subscriber Welcome Notification</strong>')
            )
            ->add(
                'onesignal_welcome_notification_enable',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label('Send Welcome Notification on New Subscription')
                    ->helperText('Automatically send a instant push notification right after a user subscribes.')
                    ->value(setting('onesignal_welcome_notification_enable', 1))
            )
            ->add(
                'onesignal_welcome_notification_title',
                TextField::class,
                TextFieldOption::make()
                    ->label('Welcome Notification Title')
                    ->placeholder('e.g. Thanks for subscribing!')
                    ->value(setting('onesignal_welcome_notification_title', 'Thanks for subscribing!'))
            )
            ->add(
                'onesignal_welcome_notification_message',
                TextField::class,
                TextFieldOption::make()
                    ->label('Welcome Notification Message')
                    ->placeholder('e.g. You will now receive latest job updates instantly.')
                    ->value(setting('onesignal_welcome_notification_message', 'You will now receive latest job alerts & updates.'))
            )
            ->add(
                'onesignal_welcome_notification_url',
                TextField::class,
                TextFieldOption::make()
                    ->label('Welcome Launch URL (Optional)')
                    ->placeholder('e.g. https://careerinpak.com')
                    ->value(setting('onesignal_welcome_notification_url'))
            )

            ->addCloseCollapsible('onesignal_enabled', '1');
    }
}
