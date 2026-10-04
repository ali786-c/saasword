<?php

namespace Botble\OneSignal\Forms;

use Botble\Base\Forms\FieldOptions\AlertFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\AlertField;
use Botble\Base\Forms\Fields\OnOffField;
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
            ->add(
                'onesignal_auto_send_on_post_publish',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/onesignal::onesignal.auto_send'))
                    ->helperText(trans('plugins/onesignal::onesignal.auto_send_helper'))
                    ->value(setting('onesignal_auto_send_on_post_publish', 1))
            )
            ->add(
                'onesignal_default_title_prefix',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/onesignal::onesignal.default_title_prefix'))
                    ->placeholder(trans('plugins/onesignal::onesignal.default_title_prefix_placeholder'))
                    ->value(setting('onesignal_default_title_prefix'))
            )
            ->addCloseCollapsible('onesignal_enabled', '1')
            ->add(
                'onesignal_info',
                AlertField::class,
                AlertFieldOption::make()
                    ->type('info')
                    ->content('Get your App ID and REST API Key from your <a href="https://dashboard.onesignal.com" target="_blank" rel="noopener noreferrer">OneSignal Dashboard</a> under Settings -> Keys & IDs.')
            );
    }
}
