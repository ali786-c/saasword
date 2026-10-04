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
            'onesignal_default_title_prefix' => ['nullable', 'string', 'max:255'],
        ];
    }
}
