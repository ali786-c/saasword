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
        array $includedSegments = ['All'],
        ?string $mobileUrl = null
    ): array {
        $appId = setting('onesignal_app_id');
        $apiKey = setting('onesignal_rest_api_key');

        if (! $appId || ! $apiKey) {
            return [
                'success' => false,
                'message' => 'OneSignal App ID or REST API Key is missing in Settings.',
            ];
        }

        // Apply UTM parameters if configured
        $finalUrl = $url;
        $utmParams = setting('onesignal_utm_additional_url_params');
        if ($finalUrl && $utmParams) {
            $separator = (str_contains($finalUrl, '?')) ? '&' : '?';
            $finalUrl = $finalUrl . $separator . trim($utmParams, '?&');
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
            'isAnyWeb' => true,
        ];

        if ($finalUrl) {
            $payload['url'] = $finalUrl;
        }

        if ($image) {
            $payload['chrome_web_image'] = $image;
            $payload['big_picture'] = $image;
            $payload['chrome_web_icon'] = $image;
            $payload['firefox_icon'] = $image;
        }

        if (setting('onesignal_send_to_mobile_platforms')) {
            $payload['isIos'] = true;
            $payload['isAndroid'] = true;
            if ($mobileUrl) {
                $payload['app_url'] = $mobileUrl;
                $payload['web_url'] = $finalUrl;
            }
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Key ' . trim($apiKey),
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
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

    /**
     * Fetch segments list from OneSignal API.
     */
    public function getSegments(): array
    {
        $appId = setting('onesignal_app_id');
        $apiKey = setting('onesignal_rest_api_key');

        if (! $appId || ! $apiKey) {
            return ['All'];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Key ' . trim($apiKey),
                'accept' => 'application/json',
            ])->get("https://onesignal.com/api/v1/apps/{$appId}/segments");

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['segments']) && is_array($data['segments'])) {
                    $segments = ['All'];
                    foreach ($data['segments'] as $seg) {
                        if (isset($seg['name']) && $seg['name'] !== 'All') {
                            $segments[] = $seg['name'];
                        }
                    }
                    return $segments;
                }
            }
        } catch (Throwable $e) {
            Log::error('OneSignal getSegments Error: ' . $e->getMessage());
        }

        return ['All', 'Active Users', 'Inactive Users', 'Subscribed Users'];
    }

    /**
     * Fetch recent sent notifications history from OneSignal API.
     */
    public function getNotificationsHistory(int $limit = 15): array
    {
        $appId = setting('onesignal_app_id');
        $apiKey = setting('onesignal_rest_api_key');

        if (! $appId || ! $apiKey) {
            return [];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Key ' . trim($apiKey),
                'accept' => 'application/json',
            ])->get("https://onesignal.com/api/v1/notifications?app_id={$appId}&limit={$limit}");

            if ($response->successful()) {
                $data = $response->json();
                return $data['notifications'] ?? [];
            }
        } catch (Throwable $e) {
            Log::error('OneSignal getNotificationsHistory Error: ' . $e->getMessage());
        }

        return [];
    }
}
