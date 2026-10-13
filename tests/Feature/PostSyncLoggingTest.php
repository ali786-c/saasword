<?php

namespace Tests\Feature;

use App\Console\Commands\GenerateSyncTokenCommand;
use App\Models\PostSyncLog;
use Botble\ACL\Models\User;
use Botble\Blog\Models\Post;
use Botble\Slug\Models\Slug;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Audit trail behind the post-sync dashboard: every attempt to push a post
 * must leave a row, whether it succeeded or why it was refused.
 */
class PostSyncLoggingTest extends TestCase
{
    protected const ENDPOINT = '/api/v1/sync/posts';

    protected const TITLE = 'Sync Log Test Post';

    protected const SLUG = 'sync-log-test-post';

    protected string $adminDir = 'admin';

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminDir = config('core.base.general.admin_dir', 'admin');
    }

    protected function tearDown(): void
    {
        PostSyncLog::query()->where('slug', 'like', 'sync-log-test-%')->delete();
        Post::query()->where('name', 'like', 'Sync Log Test%')->delete();
        Slug::query()->where('key', 'like', 'sync-log-test-%')->delete();
        $this->admin()->tokens()->where('name', 'sync-log-token')->delete();

        parent::tearDown();
    }

    public function test_successful_create_writes_a_success_row(): void
    {
        Sanctum::actingAs($this->admin(), [GenerateSyncTokenCommand::ABILITY]);

        $this->postJson(self::ENDPOINT, $this->payload())->assertStatus(201);

        $log = PostSyncLog::query()->where('slug', self::SLUG)->firstOrFail();

        $this->assertTrue($log->success);
        $this->assertSame(PostSyncLog::ACTION_CREATED, $log->action);
        $this->assertSame(201, $log->http_status);
        $this->assertSame(self::TITLE, $log->title);
        $this->assertNotNull($log->post_id);
        $this->assertNotNull($log->duration_ms);
    }

    public function test_successful_update_writes_an_updated_row(): void
    {
        Sanctum::actingAs($this->admin(), [GenerateSyncTokenCommand::ABILITY]);

        $this->postJson(self::ENDPOINT, $this->payload())->assertStatus(201);
        $this->postJson(self::ENDPOINT, $this->payload())->assertStatus(200);

        $log = PostSyncLog::query()
            ->where('slug', self::SLUG)
            ->orderByDesc('id')
            ->firstOrFail();

        $this->assertSame(PostSyncLog::ACTION_UPDATED, $log->action);
        $this->assertSame(200, $log->http_status);
        $this->assertTrue($log->success);
    }

    public function test_validation_failure_is_recorded(): void
    {
        Sanctum::actingAs($this->admin(), [GenerateSyncTokenCommand::ABILITY]);

        $payload = $this->payload();
        unset($payload['content']);

        $this->postJson(self::ENDPOINT, $payload)->assertStatus(422);

        $log = PostSyncLog::query()->where('slug', self::SLUG)->firstOrFail();

        $this->assertFalse($log->success);
        $this->assertSame(PostSyncLog::ACTION_REJECTED, $log->action);
        $this->assertSame(422, $log->http_status);
        $this->assertStringContainsString('content', (string) $log->message);
        $this->assertNull($log->post_id, 'Nothing should have been written.');
    }

    public function test_missing_ability_is_recorded(): void
    {
        $token = $this->admin()->createToken('sync-log-token', ['read'])->plainTextToken;

        $this->postJson(
            self::ENDPOINT,
            $this->payload(),
            ['Authorization' => 'Bearer ' . $token]
        )->assertStatus(403);

        $log = PostSyncLog::query()->where('slug', self::SLUG)->firstOrFail();

        $this->assertFalse($log->success);
        $this->assertSame(403, $log->http_status);
        $this->assertSame(PostSyncLog::ACTION_REJECTED, $log->action);
    }

    public function test_unauthenticated_attempt_is_recorded(): void
    {
        $this->postJson(self::ENDPOINT, $this->payload())->assertStatus(401);

        $log = PostSyncLog::query()->where('slug', self::SLUG)->firstOrFail();

        $this->assertFalse($log->success);
        $this->assertSame(401, $log->http_status);
        $this->assertSame(PostSyncLog::ACTION_REJECTED, $log->action);
        $this->assertStringContainsString('token', strtolower((string) $log->message));
    }

    public function test_dashboard_lists_the_attempts(): void
    {
        Sanctum::actingAs($this->admin(), [GenerateSyncTokenCommand::ABILITY]);

        $this->postJson(self::ENDPOINT, $this->payload())->assertStatus(201);

        // `content` required hai — khali kar ke 422 laate hain.
        $invalid = $this->payload();
        $invalid['content'] = '';

        $this->postJson(self::ENDPOINT, $invalid)->assertStatus(422);

        $response = $this->actingAs($this->admin())
            ->get("/{$this->adminDir}/post-sync");

        $response->assertStatus(200);
        $response->assertSee('Post Sync Dashboard');
        $response->assertSee(self::TITLE);
        $response->assertSee('created');
        $response->assertSee('rejected');
    }

    public function test_dashboard_failure_filter_shows_only_failures(): void
    {
        Sanctum::actingAs($this->admin(), [GenerateSyncTokenCommand::ABILITY]);

        $this->postJson(self::ENDPOINT, $this->payload())->assertStatus(201);

        $response = $this->actingAs($this->admin())
            ->get("/{$this->adminDir}/post-sync?status=failed");

        $response->assertStatus(200);
        $response->assertDontSee(self::TITLE);
    }

    protected function payload(): array
    {
        return [
            'local_post_id' => 142,
            'name' => self::TITLE,
            'slug' => self::SLUG,
            'description' => 'Sync log test description.',
            'content' => '<p>Sync log test body.</p>',
        ];
    }

    protected function admin(): User
    {
        return User::query()->firstOrFail();
    }
}
