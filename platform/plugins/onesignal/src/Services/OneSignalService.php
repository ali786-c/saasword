<?php

namespace Botble\OneSignal\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class OneSignalService
{
    /**
     * Send Web Push Notification via OneSignal REST API.
     */
    public function sendNotification(
        string $title,
        string $message,
        ?string $url = null,
        ?string $image = null,
        array $includedSegments = ['All']
    ): array {
        $appId = setting('onesignal_app_id');
        $apiKey = setting('onesignal_rest_api_key');

        if (! $appId || ! $apiKey) {
            return [
                'success' => false,
                'message' => 'OneSignal App ID or REST API Key is missing in Settings.',
            ];
        }

        $payload = [
            'app_id' => $appId,
            'included_segments' => $includedSegments,
            'headings' => [
                'en' => $title,
            ],
            'contents' => [
                'en' => $message,
            ],
        ];

        if ($url) {
            $payload['url'] = $url;
        }

        if ($image) {
            $payload['chrome_web_image'] = $image;
            $payload['big_picture'] = $image;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Key ' . trim($apiKey),
                'Content-Type' => 'application/json',
            ])->post('https://onesignal.com/api/v1/notifications', $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            Log::error('OneSignal Push API Error: ' . $response->body());

            return [
                'success' => false,
                'message' => 'OneSignal API Error (' . $response->status() . '): ' . $response->body(),
            ];
        } catch (Throwable $e) {
            Log::error('OneSignal Exception: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
