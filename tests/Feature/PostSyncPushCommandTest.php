<?php

namespace Tests\Feature;

use App\Console\Commands\PushPostToLiveCommand;
use Botble\ACL\Models\User;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Blog\Models\Post;
use Botble\Slug\Models\Slug;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Sender side of the local -> live post sync (`php artisan post:push`).
 *
 * Outbound HTTP goes through a Guzzle MockHandler (same pattern as
 * SeoBoostAdminTest) so nothing ever leaves this machine.
 */
class PostSyncPushCommandTest extends TestCase
{
    /** @var array<int, array<string, mixed>> captured requests */
    protected array $requests = [];

    protected MockHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->requests = [];
        $this->handler = new MockHandler();

        $stack = HandlerStack::create($this->handler);
        $stack->push(Middleware::history($this->requests));

        // Replace the bound client so the command never opens a real socket.
        app()->instance(Client::class, new Client(['handler' => $stack]));
    }

    protected function tearDown(): void
    {
        Post::query()->where('name', 'like', 'Push Command Test%')->delete();
        Slug::query()->where('key', 'like', 'push-command-test-%')->delete();

        parent::tearDown();
    }

    public function test_push_sends_bearer_token_and_payload_then_reports_draft(): void
    {
        $post = $this->createLocalPost();

        $this->handler->append(new Response(201, [], json_encode([
            'ok' => true,
            'created' => true,
            'post_id' => 900,
            'url' => 'https://live.example/' . self::SLUG,
            'status' => 'draft',
        ])));

        $this->artisan('post:push', [
            'post' => (string) $post->getKey(),
            '--url' => 'https://live.example',
            '--token' => '1|secret-token',
        ])
            ->expectsOutputToContain('Created on live as a DRAFT.')
            ->assertExitCode(0);

        $this->assertCount(1, $this->requests);

        $request = $this->requests[0]['request'];

        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('https://live.example/api/v1/sync/posts', (string) $request->getUri());
        $this->assertSame('Bearer 1|secret-token', $request->getHeaderLine('Authorization'));
        $this->assertSame('application/json', $request->getHeaderLine('Content-Type'));

        $sent = json_decode((string) $request->getBody(), true);

        $this->assertSame(self::TITLE, $sent['name']);
        $this->assertSame(self::SLUG, $sent['slug']);
        $this->assertArrayNotHasKey('status', $sent, 'The sender must never send a status.');
        $this->assertArrayNotHasKey('force_draft', $sent, 'Without --draft the live status must be preserved.');
    }

    public function test_draft_flag_requests_a_review_hold(): void
    {
        $post = $this->createLocalPost();

        $this->handler->append(new Response(200, [], json_encode([
            'ok' => true,
            'created' => false,
            'post_id' => 900,
            'url' => 'https://live.example/' . self::SLUG,
            'status' => 'draft',
        ])));

        $this->artisan('post:push', [
            'post' => (string) $post->getKey(),
            '--draft' => true,
            '--url' => 'https://live.example',
            '--token' => '1|secret-token',
        ])->assertExitCode(0);

        $sent = json_decode((string) $this->requests[0]['request']->getBody(), true);

        $this->assertTrue((bool) ($sent['force_draft'] ?? false));
        $this->assertArrayNotHasKey('status', $sent);
    }

    public function test_dry_run_sends_nothing(): void
    {
        $post = $this->createLocalPost();

        $this->artisan('post:push', [
            'post' => self::SLUG,
            '--dry-run' => true,
        ])
            ->expectsOutputToContain('Dry run')
            ->assertExitCode(0);

        $this->assertCount(0, $this->requests);
    }

    public function test_fails_clearly_when_sync_is_not_configured(): void
    {
        $post = $this->createLocalPost();

        config(['sync.url' => null, 'sync.token' => null]);

        $this->artisan('post:push', ['post' => (string) $post->getKey()])
            ->expectsOutputToContain('Sync is not configured')
            ->assertExitCode(1);

        $this->assertCount(0, $this->requests);
    }

    public function test_fails_when_live_rejects_the_payload(): void
    {
        $post = $this->createLocalPost();

        $this->handler->append(new Response(422, [], json_encode([
            'ok' => false,
            'message' => 'Invalid sync payload.',
        ])));

        $this->artisan('post:push', [
            'post' => (string) $post->getKey(),
            '--url' => 'https://live.example',
            '--token' => '1|secret-token',
        ])
            ->expectsOutputToContain('Live site rejected the sync (HTTP 422)')
            ->assertExitCode(1);
    }

    public function test_unknown_post_fails_without_any_request(): void
    {
        $this->artisan('post:push', ['post' => 'does-not-exist-slug'])
            ->assertExitCode(1);

        $this->assertCount(0, $this->requests);
    }

    protected function createLocalPost(): Post
    {
        $name = 'Push Command Test Post';

        $post = Post::query()->create([
            'name' => $name,
            'description' => 'Push command test description.',
            'content' => '<p>Push command test content.</p>',
            'status' => BaseStatusEnum::DRAFT,
            'is_featured' => 0,
        ]);

        Slug::query()->create([
            'key' => self::SLUG,
            'reference_id' => $post->getKey(),
            'reference_type' => get_class($post),
            'prefix' => null,
        ]);

        return $post;
    }

    protected const TITLE = 'Push Command Test Post';

    protected const SLUG = 'push-command-test-post';
}
