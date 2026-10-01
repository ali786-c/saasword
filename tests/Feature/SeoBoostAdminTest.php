<?php

namespace Tests\Feature;

use Botble\ACL\Models\User;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Blog\Models\Post;
use Botble\SeoBoost\Models\IndexNowLog;
use Botble\SeoBoost\Services\GoogleIndexingService;
use Botble\SeoBoost\Services\IndexNowService;
use Botble\Setting\Facades\Setting;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class SeoBoostAdminTest extends TestCase
{
    protected MockHandler $handler;

    protected string $originalKey = '';

    protected string $adminDir = 'admin';

    protected string $originalGoogleKey = '';

    protected string $originalEnabled = '1';

    protected string $originalGoogleEnabled = '1';

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminDir = config('core.base.general.admin_dir', 'admin');

        // The listener refuses loopback/private URLs (by design); present
        // the app as a public host so generated permalinks are submittable.
        // config('app.url') alone does not affect the already-built url().
        config(['app.url' => 'https://tests.example']);
        \Illuminate\Support\Facades\URL::forceRootUrl('https://tests.example');

        $this->originalKey = setting('seo_boost_api_key');
        $this->originalGoogleKey = (string) setting('seo_boost_google_json_key');
        $this->originalEnabled = (string) setting('seo_boost_enabled', '1');
        $this->originalGoogleEnabled = (string) setting('seo_boost_google_enabled', '1');

        if (! $this->originalKey) {
            $this->originalKey = '1234567890abcdef1234567890abcdef';
            Setting::set('seo_boost_api_key', $this->originalKey)->save();
        }

        // Route IndexNow HTTP calls through a Guzzle MockHandler so nothing
        // leaves the machine during tests.
        $this->handler = new MockHandler();

        app()->singleton(IndexNowService::class, function () {
            return new IndexNowService(
                app(\Botble\SeoBoost\Repositories\Interfaces\IndexNowLogInterface::class),
                new Client(['handler' => $this->handler])
            );
        });

        app()->singleton(GoogleIndexingService::class, function () {
            return new GoogleIndexingService(
                app(\Botble\SeoBoost\Repositories\Interfaces\IndexNowLogInterface::class),
                new Client(['handler' => $this->handler])
            );
        });
    }

    protected function tearDown(): void
    {
        IndexNowLog::query()
            ->where(fn ($q) => $q
                ->where('url', 'like', '%tests.example%')
                ->orWhere('url', 'like', '%seoboost-test-%'))
            ->delete();
        Post::query()->where('name', 'like', 'SeoBoost test %')->delete();
        \Botble\Slug\Models\Slug::query()->where('key', 'like', 'seoboost-test-%')->delete();
        Setting::set('seo_boost_api_key', $this->originalKey)->save();

        // Never leak the test service-account key or toggle state.
        Setting::set('seo_boost_google_json_key', $this->originalGoogleKey)->save();
        Setting::set('seo_boost_enabled', $this->originalEnabled)->save();
        Setting::set('seo_boost_google_enabled', $this->originalGoogleEnabled)->save();

        parent::tearDown();
    }

    protected function admin(): User
    {
        return User::query()->firstOrFail();
    }

    protected function createPublishedPostWithSlug(string $suffix): Post
    {
        $name = 'SeoBoost test ' . $suffix;
        $author = $this->admin();

        $post = Post::create([
            'name' => $name,
            'content' => '<p>x</p>',
            'status' => BaseStatusEnum::PUBLISHED,
            'author_type' => get_class($author),
            'author_id' => $author->id,
        ]);

        \Botble\Slug\Models\Slug::create([
            'key' => Str::slug($name),
            'reference_id' => $post->id,
            'reference_type' => get_class($post),
            'prefix' => null,
        ]);

        return $post;
    }

    public function test_manual_submit_endpoint_creates_manual_log(): void
    {
        $this->handler->append(new Response(200));

        $this->actingAs($this->admin())
            ->postJson("/{$this->adminDir}/seo-boost/submit", [
                'urls' => "https://tests.example/manual-1\nhttps://tests.example/manual-2",
                'engine_indexnow' => '1',
            ])
            ->assertStatus(200)
            ->assertJson(['error' => false]);

        $log = IndexNowLog::query()->latest('id')->first();

        $this->assertTrue((bool) $log->is_manual);
        $this->assertStringContainsString('[+1]', $log->url);
        $this->assertSame('indexnow', $log->engine);
        $this->assertSame('update', $log->action);
    }

    public function test_manual_submit_google_engine_only(): void
    {
        // Google engine: token exchange + one publish per URL (2 URLs).
        $this->handler->append(new Response(200, [], json_encode(['access_token' => 'ya29.t'])));
        $this->handler->append(new Response(200, [], '{}'));
        $this->handler->append(new Response(200, [], '{}'));

        // The private key is only used for local JWT signing; MockHandler
        // serves the token response, so the key never reaches Google.
        $res = openssl_pkey_new(array_merge(
            ['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA],
            $this->opensslOptions()
        ));
        openssl_pkey_export($res, $pem, null, $this->opensslOptions());

        $jsonKey = json_encode([
            'type' => 'service_account',
            'project_id' => 't',
            'private_key' => $pem,
            'client_email' => 'bot@test.iam.gserviceaccount.com',
        ]);

        Setting::set('seo_boost_google_json_key', $jsonKey)->save();

        $this->actingAs($this->admin())
            ->postJson("/{$this->adminDir}/seo-boost/submit", [
                'urls' => "https://tests.example/gm-1\nhttps://tests.example/gm-2",
                'engine_google' => '1',
                'action' => 'update',
            ])
            ->assertStatus(200)
            ->assertJson(['error' => false]);

        $googleLogs = IndexNowLog::query()->where('engine', 'google')->get();

        $this->assertCount(2, $googleLogs);
        $this->assertTrue((bool) $googleLogs->first()->is_manual);
    }

    protected function opensslOptions(): array
    {
        if (PHP_OS_FAMILY !== 'Windows') {
            return [];
        }

        foreach ([
            'C:/Users/Muhammad Aliyan/Downloads/Compressed/php-8.3.33-nts-Win32-vs16-x64/extras/ssl/openssl.cnf',
            'C:/xampp/php/extras/ssl/openssl.cnf',
        ] as $candidate) {
            if (is_file($candidate)) {
                return ['config' => $candidate];
            }
        }

        return [];
    }

    public function test_reset_key_endpoint_generates_new_32_hex_key(): void
    {
        $response = $this->actingAs($this->admin())
            ->postJson("/{$this->adminDir}/seo-boost/reset-key");

        $response->assertStatus(200)
            ->assertJson(['error' => false]);

        $newKey = $response->json('data.key');

        $this->assertMatchesRegularExpression('/^[a-f0-9]{32}$/', $newKey);
        $this->assertNotSame($this->originalKey, $newKey);
        $this->assertSame($newKey, setting('seo_boost_api_key'));
    }

    public function test_bulk_action_dispatches_selected_posts_to_both_engines(): void
    {
        // IndexNow: one batch call. Google: token exchange + one publish per URL (2 URLs).
        $this->handler->append(new Response(200));
        $this->handler->append(new Response(200, [], json_encode(['access_token' => 'ya29.t'])));
        $this->handler->append(new Response(200, [], '{}'));
        $this->handler->append(new Response(200, [], '{}'));

        Setting::set('seo_boost_google_json_key', $this->googleJsonKey())->save();
        Setting::set('seo_boost_google_enabled', '1')->save();

        $p1 = $this->createPublishedPostWithSlug('bulk-1');
        $p2 = $this->createPublishedPostWithSlug('bulk-2');

        $this->actingAs($this->admin())
            ->postJson("/{$this->adminDir}/tables/bulk-actions", [
                'bulk_action' => '1',
                'bulk_action_table' => \Botble\Blog\Tables\PostTable::class,
                'bulk_action_target' => \Botble\SeoBoost\BulkActions\SubmitToIndexingBulkAction::class,
                'ids' => [(string) $p1->id, (string) $p2->id],
            ])
            ->assertStatus(200)
            ->assertJson(['error' => false]);

        $indexNowLog = IndexNowLog::query()->where('engine', 'indexnow')->latest('id')->first();

        $this->assertNotNull($indexNowLog);
        $this->assertTrue((bool) $indexNowLog->is_manual);
        // Batch submissions log the first URL plus a [+N] extra marker.
        $this->assertStringContainsString('seoboost-test-bulk-1', $indexNowLog->url);
        $this->assertStringContainsString('[+1]', $indexNowLog->url);

        $googleLogs = IndexNowLog::query()->where('engine', 'google')->latest('id')->take(2)->get();

        $this->assertCount(2, $googleLogs);
        $this->assertSame('update', $googleLogs->first()->action);
    }

    public function test_bulk_action_skips_drafts_and_submits_published_only(): void
    {
        // IndexNow batch + Google token + one publish (only the published post).
        $this->handler->append(new Response(200));
        $this->handler->append(new Response(200, [], json_encode(['access_token' => 'ya29.t'])));
        $this->handler->append(new Response(200, [], '{}'));

        Setting::set('seo_boost_google_json_key', $this->googleJsonKey())->save();

        $published = $this->createPublishedPostWithSlug('bulk-pub');

        $author = $this->admin();

        $draft = Post::create([
            'name' => 'SeoBoost test bulk-draft',
            'content' => '<p>x</p>',
            'status' => BaseStatusEnum::DRAFT,
            'author_type' => get_class($author),
            'author_id' => $author->id,
        ]);

        $this->actingAs($author)
            ->postJson("/{$this->adminDir}/tables/bulk-actions", [
                'bulk_action' => '1',
                'bulk_action_table' => \Botble\Blog\Tables\PostTable::class,
                'bulk_action_target' => \Botble\SeoBoost\BulkActions\SubmitToIndexingBulkAction::class,
                'ids' => [(string) $published->id, (string) $draft->id],
            ])
            ->assertStatus(200)
            ->assertJson(['error' => false]);

        $this->assertSame(
            1,
            IndexNowLog::query()->where('engine', 'google')->where('url', 'like', '%bulk-pub%')->count()
        );
        $this->assertSame(
            0,
            IndexNowLog::query()->where('url', 'like', '%bulk-draft%')->count()
        );
    }

    public function test_bulk_action_filter_allows_only_post_and_page_tables(): void
    {
        $actions = apply_filters('base_filter_table_bulk_actions', [], app(\Botble\Blog\Tables\PostTable::class));

        $this->assertContains(\Botble\SeoBoost\BulkActions\SubmitToIndexingBulkAction::class, $actions);

        $actions = apply_filters('base_filter_table_bulk_actions', [], app(\Botble\Page\Tables\PageTable::class));

        $this->assertContains(\Botble\SeoBoost\BulkActions\SubmitToIndexingBulkAction::class, $actions);

        $actions = apply_filters('base_filter_table_bulk_actions', [], app(\Botble\Blog\Tables\CategoryTable::class));

        $this->assertNotContains(\Botble\SeoBoost\BulkActions\SubmitToIndexingBulkAction::class, $actions);
    }

    public function test_settings_save_persists_toggles(): void
    {
        $this->actingAs($this->admin())
            ->postJson("/{$this->adminDir}/seo-boost/settings", [
                'seo_boost_enabled' => '1',
                'seo_boost_post_types' => [
                    'post' => '1',
                    'page' => '1',
                ],
            ])
            ->assertStatus(200);

        $this->assertSame('1', setting('seo_boost_post_types.page'));

        $this->actingAs($this->admin())
            ->postJson("/{$this->adminDir}/seo-boost/settings", [
                'seo_boost_enabled' => '1',
                'seo_boost_post_types' => [
                    'post' => '1',
                ],
            ])
            ->assertStatus(200);

        $this->assertNotSame('1', setting('seo_boost_post_types.page'));
    }

    public function test_listener_respects_per_type_toggle(): void
    {
        // First save: posts enabled -> submission happens.
        $this->handler->append(new Response(200));
        $post = $this->createPublishedPostWithSlug('listener-1');

        event(new UpdatedContentEvent('posts', request(), $post));

        $this->assertSame(
            1,
            IndexNowLog::query()->where('url', 'like', '%seoboost-test-listener-1%')->count()
        );

        // Turn posts off, save another post -> no new submission.
        Setting::set('seo_boost_post_types.post', '0')->save();

        $post2 = $this->createPublishedPostWithSlug('listener-2');

        event(new UpdatedContentEvent('posts', request(), $post2));

        $this->assertSame(
            0,
            IndexNowLog::query()->where('url', 'like', '%seoboost-test-listener-2%')->count()
        );
    }

    protected function permalinkKey(Post $post): string
    {
        return 'seo_boost_permalink_' . md5($post::class . '|' . $post->getKey());
    }

    protected function seedPublishedPermalink(Post $post): void
    {
        // Simulate the snapshot the update listener stores on publish.
        \Illuminate\Support\Facades\Cache::put(
            $this->permalinkKey($post),
            url('seoboost-test-' . $post->id),
            600
        );
    }

    public function test_published_delete_sends_google_url_deleted(): void
    {
        Setting::set('seo_boost_google_post_types.post', '1')->save();
        Setting::set('seo_boost_google_enabled', '1')->save();

        $post = $this->createPublishedPostWithSlug('deleted-1');
        $this->seedPublishedPermalink($post);

        // Token exchange + publish.
        $this->handler->append(new Response(200, [], json_encode(['access_token' => 'ya29.t'])));
        $this->handler->append(new Response(200, [], '{}'));

        Setting::set('seo_boost_google_json_key', $this->googleJsonKey())->save();

        event(new DeletedContentEvent(Post::class, request(), $post));

        $log = IndexNowLog::query()->where('engine', 'google')->latest('id')->first();

        $this->assertNotNull($log);
        $this->assertSame('delete', $log->action);
        $this->assertSame(url('seoboost-test-' . $post->id), $log->url);

        // Permalink snapshot is consumed.
        $this->assertFalse(\Illuminate\Support\Facades\Cache::has($this->permalinkKey($post)));
    }

    public function test_draft_delete_sends_nothing(): void
    {
        Setting::set('seo_boost_google_post_types.post', '1')->save();
        Setting::set('seo_boost_google_enabled', '1')->save();

        $author = $this->admin();

        $draft = Post::create([
            'name' => 'SeoBoost test draft-del',
            'content' => '<p>x</p>',
            'status' => BaseStatusEnum::DRAFT,
            'author_type' => get_class($author),
            'author_id' => $author->id,
        ]);

        // A draft that was never published has no permalink snapshot, so
        // the delete path has nothing to notify Google about.
        $this->handler->append(new Response(200, [], json_encode(['access_token' => 'ya29.t'])));

        Setting::set('seo_boost_google_json_key', $this->googleJsonKey())->save();

        event(new DeletedContentEvent(Post::class, request(), $draft));

        $this->assertSame(
            0,
            IndexNowLog::query()->where('engine', 'google')->count()
        );
    }

    public function test_delete_is_skipped_when_google_engine_disabled(): void
    {
        Setting::set('seo_boost_google_post_types.post', '1')->save();

        $post = $this->createPublishedPostWithSlug('deleted-2');
        $this->seedPublishedPermalink($post);

        Setting::set('seo_boost_google_enabled', '0')->save();
        Setting::set('seo_boost_google_json_key', $this->googleJsonKey())->save();

        $this->handler->append(new Response(200, [], json_encode(['access_token' => 'ya29.t'])));

        event(new DeletedContentEvent(Post::class, request(), $post));

        $this->assertSame(
            0,
            IndexNowLog::query()->where('engine', 'google')->count()
        );
    }

    protected function googleJsonKey(): string
    {
        $res = openssl_pkey_new(array_merge(
            ['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA],
            $this->opensslOptions()
        ));
        openssl_pkey_export($res, $pem, null, $this->opensslOptions());

        return json_encode([
            'type' => 'service_account',
            'project_id' => 't',
            'private_key' => $pem,
            'client_email' => 'bot@test.iam.gserviceaccount.com',
        ]);
    }
}
