<?php

namespace Botble\OneSignal\Http\Controllers;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Breadcrumb;
use Botble\OneSignal\Forms\OneSignalSettingForm;
use Botble\OneSignal\Http\Requests\OneSignalSettingRequest;
use Botble\OneSignal\Services\OneSignalService;
use Botble\Setting\Http\Controllers\SettingController;
use Illuminate\Http\Request;

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

    public function manualPush(OneSignalService $service)
    {
        $this->pageTitle('Send Push Blast & History');

        $segments = $service->getSegments();
        $notifications = $service->getNotificationsHistory(20);

        return view('plugins/onesignal::manual-push', compact('segments', 'notifications'));
    }

    public function sendManualPush(Request $request, OneSignalService $service, BaseHttpResponse $response): BaseHttpResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'segment' => 'required|string',
            'url' => 'nullable|url',
            'image' => 'nullable|url',
        ]);

        $result = $service->sendNotification(
            title: $request->input('title'),
            message: $request->input('message'),
            url: $request->input('url'),
            image: $request->input('image'),
            includedSegments: [$request->input('segment')]
        );

        if (! $result['success']) {
            return $response
                ->setError()
                ->setMessage('Push Blast Failed: ' . $result['message']);
        }

        return $response
            ->setMessage('Push notification blast sent successfully to subscribers!');
    }
}
