<?php

namespace Tests\Unit;

use Botble\SeoBoost\Models\IndexNowLog;
use Botble\SeoBoost\Repositories\Interfaces\IndexNowLogInterface;
use Botble\SeoBoost\Services\GoogleIndexingService;
use Botble\Setting\Facades\Setting;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class GoogleIndexingServiceTest extends TestCase
{
    protected GoogleIndexingService $service;

    protected MockHandler $handler;

    protected string $jsonKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jsonKey = json_encode([
            'type' => 'service_account',
            'project_id' => 'test-project',
            'private_key' => $this->generateTestPrivateKey(),
            'client_email' => 'bot@test-project.iam.gserviceaccount.com',
        ]);

        Setting::set('seo_boost_google_json_key', $this->jsonKey)->save();
        Setting::set('seo_boost_enabled', '1')->save();
        Setting::set('seo_boost_google_enabled', '1')->save();

        $this->handler = new MockHandler();
        $this->service = new GoogleIndexingService(
            app(IndexNowLogInterface::class),
            new Client(['handler' => $this->handler])
        );

        Cache::flush();
    }

    protected function tearDown(): void
    {
        IndexNowLog::query()->where('url', 'like', 'https://tests.example/%')->delete();
        Setting::set('seo_boost_google_json_key', '')->save();
        Cache::flush();

        parent::tearDown();
    }

    /**
     * Tests never hit Google: the token exchange and publish calls are both
     * served by the MockHandler, so the private key below only needs to be a
     * structurally valid PEM (the JWT is signed but never verified remotely).
     */
    protected function generateTestPrivateKey(): string
    {
        $options = [];

        // Windows PHP builds need an explicit openssl.cnf for key generation.
        if (PHP_OS_FAMILY === 'Windows') {
            foreach ([
                'C:/Users/Muhammad Aliyan/Downloads/Compressed/php-8.3.33-nts-Win32-vs16-x64/extras/ssl/openssl.cnf',
                'C:/xampp/php/extras/ssl/openssl.cnf',
            ] as $candidate) {
                if (is_file($candidate)) {
                    $options['config'] = $candidate;
                    break;
                }
            }
        }

        $res = openssl_pkey_new(array_merge([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ], $options));

        openssl_pkey_export($res, $privateKey, null, $options);

        return $privateKey;
    }

    public function test_parse_json_key_validates_service_account(): void
    {
        $parsed = $this->service->parseJsonKey();

        $this->assertSame('bot@test-project.iam.gserviceaccount.com', $parsed['client_email']);
        $this->assertSame('test-project', $parsed['project_id']);

        Setting::set('seo_boost_google_json_key', '{"broken"')->save();

        $this->assertNull($this->service->parseJsonKey());
    }

    public function test_submit_exchanges_jwt_for_token_and_publishes(): void
    {
        // 1st request: token exchange. 2nd: publish.
        $this->handler->append(new Response(200, [], json_encode(['access_token' => 'ya29.test-token'])));
        $this->handler->append(new Response(200, [], json_encode([
            'urlNotificationMetadata' => ['url' => 'https://tests.example/g1', 'latestUpdate' => []],
        ])));

        $this->assertTrue($this->service->submit('https://tests.example/g1', 'update', true));

        // The publish request must carry the OAuth bearer token.
        $publishRequest = $this->handler->getLastRequest();

        $this->assertSame('POST', $publishRequest->getMethod());
        $this->assertSame('Bearer ya29.test-token', $publishRequest->getHeaderLine('Authorization'));
        $this->assertStringContainsString('urlNotifications:publish', $publishRequest->getUri()->getPath());
    }

    public function test_error_status_is_mapped(): void
    {
        $this->handler->append(new Response(200, [], json_encode(['access_token' => 'ya29.t'])));
        $this->handler->append(new Response(403, [], json_encode(['error' => ['message' => 'Permission denied']])));

        $this->assertFalse($this->service->submit('https://tests.example/g2', 'update', true));

        $log = IndexNowLog::query()->where('engine', 'google')->latest('id')->first();

        $this->assertSame(403, $log->status_code);
        $this->assertStringContainsString('Search Console', $log->message);
        $this->assertSame('google', $log->engine);
        $this->assertSame('update', $log->action);
    }

    public function test_delete_action_sends_url_deleted(): void
    {
        $this->handler->append(new Response(200, [], json_encode(['access_token' => 'ya29.t'])));
        $this->handler->append(new Response(200, [], '{}'));

        $this->assertTrue($this->service->submit('https://tests.example/g3', 'delete', true));

        $log = IndexNowLog::query()->where('engine', 'google')->latest('id')->first();

        $this->assertSame('delete', $log->action);
    }

    public function test_auth_failure_is_logged_without_throwing(): void
    {
        $this->handler->append(new ConnectException('cURL error 7', new Request('POST', 'test')));

        $this->assertFalse($this->service->submit('https://tests.example/g4', 'update', true));

        $log = IndexNowLog::query()->where('engine', 'google')->latest('id')->first();

        $this->assertSame(0, $log->status_code);
        $this->assertStringContainsString('Auth failed', $log->message);
    }

    public function test_submit_without_key_short_circuits(): void
    {
        Setting::set('seo_boost_google_json_key', '')->save();

        $this->handler->append(new Response(200, [], json_encode(['access_token' => 'ya29.t'])));

        $this->assertFalse($this->service->submit('https://tests.example/g5', 'update', true));
        $this->assertSame(0, IndexNowLog::query()->where('url', 'like', 'https://tests.example/g%')->count());
    }

    public function test_auto_submit_throttles_per_engine_and_action(): void
    {
        $this->handler->append(new Response(200, [], json_encode(['access_token' => 'ya29.t'])));
        $this->handler->append(new Response(200, [], '{}'));

        $url = 'https://tests.example/gthrottle';

        $this->assertTrue($this->service->submitAuto($url));
        $this->assertFalse($this->service->submitAuto($url));

        $this->assertSame(
            1,
            IndexNowLog::query()->where('engine', 'google')->where('url', $url)->count()
        );
    }
}
