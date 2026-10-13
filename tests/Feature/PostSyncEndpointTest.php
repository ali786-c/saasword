<?php

namespace Tests\Feature;

use App\Console\Commands\GenerateSyncTokenCommand;
use Botble\ACL\Models\User;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Slug\Models\Slug;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Receiver side of the local -> live post sync (POST /api/v1/sync/posts).
 *
 * These run against the real database, like the rest of the Feature suite, so
 * every test cleans up its own rows by name/slug pattern in tearDown().
 */
class PostSyncEndpointTest extends TestCase
{
    protected const ENDPOINT = '/api/v1/sync/posts';

    protected const TITLE = 'Sync Endpoint Test Post';

    protected const SLUG = 'sync-endpoint-test-post';

    protected const CATEGORY = 'Sync Endpoint Test Category';

    protected function tearDown(): void
    {
        Post::query()->where('name', 'like', 'Sync Endpoint Test%')->delete();
        Slug::query()->where('key', 'like', 'sync-endpoint-test-%')->delete();
        Category::query()->where('name', self::CATEGORY)->delete();
        $this->admin()->tokens()->where('name', 'sync-test-token')->delete();

        parent::tearDown();
    }

    public function test_guest_cannot_sync(): void
    {
        $response = $this->postJson(self::ENDPOINT, $this->payload());

        $response->assertStatus(401);

        $this->assertSame(0, Post::query()->where('name', self::TITLE)->count());
    }

    public function test_token_without_sync_ability_is_rejected(): void
    {
        $token = $this->admin()
            ->createToken('sync-test-token', ['read'])
            ->plainTextToken;

        $response = $this->postJson(
            self::ENDPOINT,
            $this->payload(),
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(403);
        $response->assertJson(['ok' => false]);

        $this->assertSame(0, Post::query()->where('name', self::TITLE)->count());
    }

    public function test_valid_payload_creates_post_as_draft(): void
    {
        Sanctum::actingAs($this->admin(), [GenerateSyncTokenCommand::ABILITY]);

        $payload = $this->payload();
        // The sender does NOT get to choose the live status, even if it tries.
        $payload['status'] = 'published';
        $payload['author_id'] = 9999;
        $payload['image'] = '/storage/evil.png';

        $response = $this->postJson(self::ENDPOINT, $payload);

        $response->assertStatus(201);
        $response->assertJson(['ok' => true, 'created' => true, 'status' => 'draft']);

        $post = Post::query()->where('name', self::TITLE)->firstOrFail();

        $this->assertSame('draft', $post->getRawOriginal('status'));
        $this->assertSame($this->admin()->getKey(), (int) $post->getRawOriginal('author_id'));
        $this->assertNull($post->getRawOriginal('image'));
    }

    public function test_repeat_push_updates_instead_of_duplicating(): void
    {
        Sanctum::actingAs($this->admin(), [GenerateSyncTokenCommand::ABILITY]);

        $first = $this->postJson(self::ENDPOINT, $this->payload());
        $first->assertStatus(201);

        $payload = $this->payload();
        $payload['content'] = '<p>Updated content</p>';

        $second = $this->postJson(self::ENDPOINT, $payload);
        $second->assertStatus(200);
        $second->assertJson(['ok' => true, 'created' => false]);

        $this->assertSame(1, Post::query()->where('name', self::TITLE)->count());

        $post = Post::query()->where('name', self::TITLE)->firstOrFail();
        $this->assertSame('<p>Updated content</p>', $post->getRawOriginal('content'));
        $this->assertSame(1, Slug::query()->where('key', self::SLUG)->count());
    }

    public function test_live_view_counter_is_not_reset_by_a_resync(): void
    {
        Sanctum::actingAs($this->admin(), [GenerateSyncTokenCommand::ABILITY]);

        $this->postJson(self::ENDPOINT, $this->payload())->assertStatus(201);

        $post = Post::query()->where('name', self::TITLE)->firstOrFail();
        $post->forceFill(['views' => 4242])->save();

        $payload = $this->payload();
        $payload['views'] = 7;

        $this->postJson(self::ENDPOINT, $payload)->assertStatus(200);

        $post = Post::query()->where('name', self::TITLE)->firstOrFail();
        $this->assertSame(4242, (int) $post->getRawOriginal('views'));
    }

    public function test_invalid_payloads_are_rejected(): void
    {
        Sanctum::actingAs($this->admin(), [GenerateSyncTokenCommand::ABILITY]);

        $missingName = $this->payload();
        unset($missingName['name']);
        $this->postJson(self::ENDPOINT, $missingName)->assertStatus(422);

        $missingContent = $this->payload();
        unset($missingContent['content']);
        $this->postJson(self::ENDPOINT, $missingContent)->assertStatus(422);

        $badSlug = $this->payload();
        $badSlug['slug'] = 'Not A Valid Slug!';
        $this->postJson(self::ENDPOINT, $badSlug)->assertStatus(422);

        $this->assertSame(0, Post::query()->where('name', self::TITLE)->count());
    }

    public function test_category_is_attached_by_name(): void
    {
        Sanctum::actingAs($this->admin(), [GenerateSyncTokenCommand::ABILITY]);

        Category::query()->firstOrCreate(
            ['name' => self::CATEGORY],
            ['status' => BaseStatusEnum::PUBLISHED]
        );

        $payload = $this->payload();
        $payload['category'] = self::CATEGORY;

        $this->postJson(self::ENDPOINT, $payload)->assertStatus(201);

        $post = Post::query()->where('name', self::TITLE)->firstOrFail();

        $this->assertSame(
            self::CATEGORY,
            (string) $post->categories()->first()->name
        );
    }

    public function test_template_inline_styles_are_stored_verbatim(): void
    {
        Sanctum::actingAs($this->admin(), [GenerateSyncTokenCommand::ABILITY]);

        $style = 'background-color: #e53935 !important; border-left: 5px solid #007bff !important;';
        $payload = $this->payload();
        $payload['content'] = '<div style="' . $style . '">1. Job Description</div>';

        $this->postJson(self::ENDPOINT, $payload)->assertStatus(201);

        $post = Post::query()->where('name', self::TITLE)->firstOrFail();

        // The 11-section template depends on !important inline styles; the
        // receiver must not run the content through HTMLPurifier.
        $this->assertStringContainsString('!important', $post->getRawOriginal('content'));
        $this->assertStringContainsString('#e53935', $post->getRawOriginal('content'));
    }

    protected function payload(): array
    {
        return [
            'local_post_id' => 142,
            'name' => self::TITLE,
            'slug' => self::SLUG,
            'description' => 'Sync endpoint test meta description.',
            'content' => '<p>Sync endpoint test body.</p>',
            'category' => self::CATEGORY,
            'is_featured' => true,
            'views' => 12,
        ];
    }

    protected function admin(): User
    {
        return User::query()->firstOrFail();
    }
}
