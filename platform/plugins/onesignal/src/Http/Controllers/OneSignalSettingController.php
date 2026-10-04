<?php

namespace Botble\OneSignal\Http\Controllers;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Breadcrumb;
use Botble\OneSignal\Forms\OneSignalSettingForm;
use Botble\OneSignal\Http\Requests\OneSignalSettingRequest;
use Botble\OneSignal\Services\OneSignalService;
use Botble\Setting\Http\Controllers\SettingController;

class OneSignalSettingController extends SettingController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/onesignal::onesignal.name'));
    }

    public function edit()
    {
        $this->pageTitle(trans('plugins/onesignal::onesignal.settings_title'));

        return OneSignalSettingForm::create()->renderForm();
    }

    public function update(OneSignalSettingRequest $request): BaseHttpResponse
    {
        return $this->performUpdate($request->validated());
    }

    public function sendTest(OneSignalService $service, BaseHttpResponse $response): BaseHttpResponse
    {
        $result = $service->sendNotification(
            title: 'Test Notification',
            message: 'This is a test web push notification from CareerInPak / OneSignal plugin!',
            url: url('/')
        );

        if (! $result['success']) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/onesignal::onesignal.test_push_error') . $result['message']);
        }

        return $response
            ->setMessage(trans('plugins/onesignal::onesignal.test_push_success'));
    }
}
