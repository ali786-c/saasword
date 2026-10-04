<?php

namespace Botble\OneSignal\Http\Requests;

use Botble\Support\Http\Requests\Request;

class OneSignalSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'onesignal_enabled' => ['nullable', 'in:0,1'],
            'onesignal_app_id' => ['nullable', 'string', 'max:255'],
            'onesignal_rest_api_key' => ['nullable', 'string', 'max:255'],
            'onesignal_safari_web_id' => ['nullable', 'string', 'max:255'],
            'onesignal_auto_send_on_post_publish' => ['nullable', 'in:0,1'],
            'onesignal_auto_send_on_post_update' => ['nullable', 'in:0,1'],
            'onesignal_send_to_mobile_platforms' => ['nullable', 'in:0,1'],
            'onesignal_default_title_prefix' => ['nullable', 'string', 'max:255'],
            'onesignal_utm_additional_url_params' => ['nullable', 'string', 'max:500'],
            'onesignal_prompt_action_message' => ['nullable', 'string', 'max:500'],
            'onesignal_prompt_accept_button_text' => ['nullable', 'string', 'max:100'],
            'onesignal_prompt_cancel_button_text' => ['nullable', 'string', 'max:100'],
            'onesignal_notify_button_enable' => ['nullable', 'in:0,1'],
            'onesignal_notify_button_position' => ['nullable', 'string', 'in:bottom-right,bottom-left'],
            'onesignal_notify_button_size' => ['nullable', 'string', 'in:small,medium,large'],
            'onesignal_notify_button_theme' => ['nullable', 'string', 'in:default,inverse'],
            'onesignal_welcome_notification_enable' => ['nullable', 'in:0,1'],
            'onesignal_welcome_notification_title' => ['nullable', 'string', 'max:255'],
            'onesignal_welcome_notification_message' => ['nullable', 'string', 'max:500'],
            'onesignal_welcome_notification_url' => ['nullable', 'string', 'max:500'],
        ];
    }
}
