<?php

namespace Tests\Unit;

use Botble\SeoBoost\Models\IndexNowLog;
use Botble\SeoBoost\Repositories\Interfaces\IndexNowLogInterface;
use Botble\SeoBoost\Services\IndexNowService;
use Botble\Setting\Facades\Setting;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class IndexNowServiceTest extends TestCase
{
    protected IndexNowService $service;

    protected MockHandler $handler;

    protected string $originalKey = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalKey = setting('seo_boost_api_key');

        if (! $this->originalKey) {
            Setting::set('seo_boost_api_key', '1234567890abcdef1234567890abcdef')->save();
            $this->originalKey = '1234567890abcdef1234567890abcdef';
        }

        $this->handler = new MockHandler();
        $this->service = new IndexNowService(
            app(IndexNowLogInterface::class),
            new Client(['handler' => $this->handler])
        );
    }

    protected function tearDown(): void
    {
        IndexNowLog::query()->where('url', 'like', 'https://tests.example/%')->delete();

        // Never leak the test key into the site settings.
        Setting::set('seo_boost_api_key', $this->originalKey)->save();

        parent::tearDown();
    }

    public function test_build_payload_has_indexnow_structure(): void
    {
        $payload = $this->service->buildPayload([
            'https://tests.example/one',
            'https://tests.example/two',
        ]);

        $this->assertSame($this->service->getKey(), $payload['key']);
        $this->assertSame($this->service->keyFileUrl(), $payload['keyLocation']);
        $this->assertSame(['https://tests.example/one', 'https://tests.example/two'], $payload['urlList']);
        $this->assertNotEmpty($payload['host']);
        $this->assertStringEndsWith('/' . $this->service->getKey() . '.txt', $payload['keyLocation']);
    }

    public function test_successful_submission_returns_true_and_logs(): void
    {
        $this->handler->append(new Response(202));

        $result = $this->service->submit('https://tests.example/post-1', true);

        $this->assertTrue($result);

        $log = IndexNowLog::query()->latest('id')->first();

        $this->assertSame('https://tests.example/post-1', $log->url);
        $this->assertSame(202, $log->status_code);
        $this->assertSame('OK', $log->message);
        $this->assertTrue($log->is_manual);
        $this->assertTrue($log->is_success);
    }

    public function test_error_status_is_mapped_to_friendly_message(): void
    {
        $this->handler->append(new Response(403, [], 'Forbidden'));

        $result = $this->service->submit('https://tests.example/post-2', false);

        $this->assertFalse($result);

        $log = IndexNowLog::query()->latest('id')->first();

        $this->assertSame(403, $log->status_code);
        $this->assertSame('Invalid API key.', $log->message);
        $this->assertFalse($log->is_manual);
        $this->assertFalse($log->is_success);
    }

    public function test_connection_failure_is_logged_without_throwing(): void
    {
        $this->handler->append(
            new ConnectException('cURL error 7', new Request('POST', 'test'))
        );

        $result = $this->service->submit('https://tests.example/post-3', false);

        $this->assertFalse($result);

        $log = IndexNowLog::query()->latest('id')->first();

        $this->assertSame(0, $log->status_code);
        $this->assertStringContainsString('Connection failed', $log->message);
    }

    public function test_submit_without_api_key_short_circuits(): void
    {
        Setting::set('seo_boost_api_key', '')->save();

        $this->handler->append(new Response(200));

        $result = $this->service->submit('https://tests.example/post-4', true);

        $this->assertFalse($result);
        $this->assertSame(0, IndexNowLog::query()->where('url', 'like', 'https://tests.example/%')->count());
    }

    public function test_auto_submit_is_throttled_per_url(): void
    {
        $this->handler->append(new Response(200));

        $url = 'https://tests.example/throttled';

        $this->assertTrue($this->service->submitAuto($url));
        $this->assertFalse($this->service->submitAuto($url));

        $this->assertSame(
            1,
            IndexNowLog::query()->where('url', $url)->count()
        );
    }
}
