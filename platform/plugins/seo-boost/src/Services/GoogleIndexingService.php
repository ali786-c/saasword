<?php

namespace Botble\SeoBoost\Services;

use Botble\SeoBoost\Models\IndexNowLog;
use Botble\SeoBoost\Repositories\Interfaces\IndexNowLogInterface;
use Botble\Setting\Facades\Setting;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Google Indexing API client.
 *
 * Implements the same wire protocol as the official google/apiclient SDK
 * flow used by Rank Math's Instant Indexing plugin, without the SDK:
 * service-account JSON key -> RS256 JWT (firebase/php-jwt) -> OAuth2
 * access token -> REST calls against indexing.googleapis.com.
 */
class GoogleIndexingService
{
    public const OAUTH_TOKEN_URL = 'https://oauth2.googleapis.com/token';

    public const API_BASE_URL = 'https://indexing.googleapis.com';

    public const INDEXING_SCOPE = 'https://www.googleapis.com/auth/indexing';

    /**
     * Google's documented daily publish quota.
     */
    public const DAILY_QUOTA = 200;

    /**
     * Cache duration for the OAuth access token (Google tokens live 1h;
     * refresh 5 minutes early to avoid clock-skew rejections).
     */
    protected const TOKEN_CACHE_SECONDS = 3300;

    public function __construct(
        protected IndexNowLogInterface $logRepository,
        protected ?Client $client = null
    ) {
    }

    public function isConfigured(): bool
    {
        return (bool) $this->getJsonKey();
    }

    public function isEnabled(): bool
    {
        if (! $this->isConfigured()) {
            return false;
        }

        $value = setting('seo_boost_google_enabled', true);

        return in_array($value, [true, 1, '1'], true);
    }

    /**
     * Raw service-account JSON, as pasted into the settings page.
     */
    public function getJsonKey(): string
    {
        return trim((string) setting('seo_boost_google_json_key'));
    }

    public function saveJsonKey(string $json): void
    {
        Setting::set('seo_boost_google_json_key', trim($json))->save();

        // A new key invalidates any cached token.
        Cache::forget($this->tokenCacheKey());
    }

    /**
     * Validate a service-account JSON payload and return its fields.
     *
     * @return array{client_email: string, project_id: string}|null null when invalid
     */
    public function parseJsonKey(?string $json = null): ?array
    {
        $json ??= $this->getJsonKey();

        if (! $json) {
            return null;
        }

        try {
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception) {
            return null;
        }

        if (
            ! is_array($data)
            || ($data['type'] ?? '') !== 'service_account'
            || empty($data['client_email'])
            || empty($data['private_key'])
        ) {
            return null;
        }

        return [
            'client_email' => (string) $data['client_email'],
            'project_id' => (string) ($data['project_id'] ?? ''),
        ];
    }

    protected function tokenCacheKey(): string
    {
        return 'seo_boost_google_token_' . md5($this->getJsonKey());
    }

    /**
     * Exchange a signed JWT for an OAuth2 access token (cached ~55 min).
     */
    public function getAccessToken(): string
    {
        return Cache::remember(
            $this->tokenCacheKey(),
            self::TOKEN_CACHE_SECONDS,
            function (): string {
                $key = $this->parseJsonKey();

                if (! $key) {
                    throw new RuntimeException('Invalid Google service account JSON key.');
                }

                $issuedAt = Carbon::now()->getTimestamp();

                $jwt = \Firebase\JWT\JWT::encode(
                    [
                        'iss' => $key['client_email'],
                        'scope' => self::INDEXING_SCOPE,
                        'aud' => self::OAUTH_TOKEN_URL,
                        'iat' => $issuedAt,
                        'exp' => $issuedAt + 3600,
                    ],
                    $this->getPrivateKey(),
                    'RS256'
                );

                $response = $this->httpClient()->post(self::OAUTH_TOKEN_URL, [
                    'form_params' => [
                        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                        'assertion' => $jwt,
                    ],
                ]);

                $body = json_decode($response->getBody()->getContents(), true);

                if (empty($body['access_token'])) {
                    throw new RuntimeException('Google OAuth token response missing access_token.');
                }

                return (string) $body['access_token'];
            }
        );
    }

    protected function getPrivateKey(): string
    {
        $data = json_decode($this->getJsonKey(), true);

        return (string) ($data['private_key'] ?? '');
    }

    protected function httpClient(): Client
    {
        if (! $this->client) {
            $this->client = new Client([
                'timeout' => 15,
                'connect_timeout' => 5,
            ]);
        }

        return $this->client;
    }

    /**
     * Submit URL update/delete notifications to the Google Indexing API.
     *
     * @param array|string $urls
     * @param string $action 'update' or 'delete'
     */
    public function submit(array|string $urls, string $action = 'update', bool $isManual = false): bool
    {
        $urls = array_values(array_unique(array_filter(array_map('trim', (array) $urls))));

        if (! $urls || ! in_array($action, ['update', 'delete'], true)) {
            return false;
        }

        if (! $this->isConfigured()) {
            return false;
        }

        // Google's documented daily quota; stay under it.
        $urls = array_slice($urls, 0, self::DAILY_QUOTA);

        try {
            $token = $this->getAccessToken();
        } catch (Exception $exception) {
            $this->log($urls, 0, 'Auth failed: ' . $exception->getMessage(), $isManual, $action);

            return false;
        }

        $allSucceeded = true;

        foreach ($urls as $url) {
            $succeeded = $this->publishOne($url, $action, $token, $isManual);

            $allSucceeded = $allSucceeded && $succeeded;
        }

        return $allSucceeded;
    }

    protected function publishOne(string $url, string $action, string $token, bool $isManual): bool
    {
        $status = 0;
        $message = '';

        try {
            $response = $this->httpClient()->post(self::API_BASE_URL . '/v3/urlNotifications:publish', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'url' => $url,
                    'type' => $action === 'delete' ? 'URL_DELETED' : 'URL_UPDATED',
                ],
            ]);

            $status = $response->getStatusCode();
            $message = $response->getBody()->getContents();

            if ($status === 200) {
                $this->log([$url], $status, 'OK', $isManual, $action);

                return true;
            }
        } catch (ConnectException $exception) {
            $message = 'Connection failed: ' . $exception->getMessage();
        } catch (Exception $exception) {
            $message = $exception->getMessage();
        }

        $this->log([$url], $status, $this->mapErrorMessage($status, $message), $isManual, $action);

        return false;
    }

    /**
     * Auto-submit entry point used by the content event listeners.
     * Enforces the per-URL 5-second throttle (mirrors IndexNow behavior).
     */
    public function submitAuto(string $url, string $action = 'update'): bool
    {
        if (! $this->isEnabled()) {
            return false;
        }

        if ($this->wasRecentlySubmitted($url, $action)) {
            return false;
        }

        return $this->submit($url, $action, false);
    }

    protected function wasRecentlySubmitted(string $url, string $action): bool
    {
        $latest = IndexNowLog::query()
            ->where('engine', 'google')
            ->where('action', $action)
            ->orderByDesc('created_at')
            ->first();

        return $latest
            && $latest->url === $url
            && $latest->created_at
            && $latest->created_at->gt(now()->subSeconds(IndexNowService::THROTTLE_SECONDS));
    }

    protected function log(array $urls, int $status, string $message, bool $isManual, string $action): void
    {
        $this->logRepository->create([
            'url' => count($urls) > 1 ? $urls[0] . ' [+' . (count($urls) - 1) . ']' : $urls[0],
            'status_code' => $status,
            'message' => Str::limit($message, 480),
            'is_manual' => $isManual,
            'engine' => 'google',
            'action' => $action,
        ]);

        app(IndexNowService::class)->trimLog();
    }

    protected function mapErrorMessage(int $status, string $fallback): string
    {
        return match ($status) {
            400 => 'Invalid URL or request body.',
            403 => 'Access denied: verify the site in Search Console with this service account (owner permission required).',
            422 => 'The URL cannot be indexed (unknown URL or quota exhausted).',
            429 => 'Quota exhausted: Google allows ~200 URLs/day for this API.',
            500 => 'Google server error, retry later.',
            default => $fallback ?: 'Unknown error.',
        };
    }
}
