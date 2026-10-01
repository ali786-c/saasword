<?php

namespace Tests\Feature;

use App\Models\WpImportMapping;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Page\Models\Page;
use Botble\Slug\Models\Slug;
use Illuminate\Support\Facades\DB;
use PDO;
use Tests\TestCase;

class WpMigrateFromDbCommandTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    protected const TEST_DB = 'wp_import_test_tmp';

    protected PDO $server;

    protected array $credentials;

    protected function setUp(): void
    {
        parent::setUp();

        $connection = config('database.connections.mysql');

        $this->credentials = [
            'host' => $connection['host'] ?? '127.0.0.1',
            'port' => (string) ($connection['port'] ?? '3306'),
            'user' => $connection['username'] ?? 'root',
            'pass' => (string) ($connection['password'] ?? ''),
        ];

        $this->server = new PDO(
            sprintf('mysql:host=%s;port=%s;charset=utf8mb4', $this->credentials['host'], $this->credentials['port']),
            $this->credentials['user'],
            $this->credentials['pass'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        $this->createWpDatabase();
    }

    protected function tearDown(): void
    {
        $this->server->exec('DROP DATABASE IF EXISTS '.self::TEST_DB);

        parent::tearDown();
    }

    protected function createWpDatabase(): void
    {
        $this->server->exec('DROP DATABASE IF EXISTS '.self::TEST_DB);
        $this->server->exec('CREATE DATABASE '.self::TEST_DB.' CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        $this->server->exec('USE '.self::TEST_DB);

        $this->server->exec(<<<'SQL'
CREATE TABLE wp_posts (
    ID BIGINT UNSIGNED PRIMARY KEY,
    post_author BIGINT UNSIGNED DEFAULT 0,
    post_date DATETIME NULL,
    post_date_gmt DATETIME NULL,
    post_content LONGTEXT NULL,
    post_title TEXT NULL,
    post_excerpt TEXT NULL,
    post_status VARCHAR(20) DEFAULT 'publish',
    post_name VARCHAR(200) DEFAULT '',
    post_type VARCHAR(20) DEFAULT 'post',
    post_parent BIGINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB;
CREATE TABLE wp_postmeta (
    meta_id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    post_id BIGINT UNSIGNED NOT NULL,
    meta_key VARCHAR(255) NULL,
    meta_value LONGTEXT NULL
) ENGINE=InnoDB;
CREATE TABLE wp_terms (
    term_id BIGINT UNSIGNED PRIMARY KEY,
    name VARCHAR(200) NULL,
    slug VARCHAR(200) NULL
) ENGINE=InnoDB;
CREATE TABLE wp_term_taxonomy (
    term_taxonomy_id BIGINT UNSIGNED PRIMARY KEY,
    term_id BIGINT UNSIGNED NOT NULL,
    taxonomy VARCHAR(32) NOT NULL,
    description LONGTEXT NULL
) ENGINE=InnoDB;
CREATE TABLE wp_term_relationships (
    object_id BIGINT UNSIGNED NOT NULL,
    term_taxonomy_id BIGINT UNSIGNED NOT NULL
) ENGINE=InnoDB;
CREATE TABLE wp_options (
    option_id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    option_name VARCHAR(191) NOT NULL,
    option_value LONGTEXT NULL
) ENGINE=InnoDB;
SQL);

        // Category "Jobs" (term 3 / term_taxonomy 3), tag "Govt" (term 4 / tt 4).
        $this->server->exec("INSERT INTO wp_terms VALUES (3, 'Jobs', 'jobs'), (4, 'Govt', 'govt')");
        $this->server->exec("INSERT INTO wp_term_taxonomy VALUES (3, 3, 'category', 'Job listings'), (4, 4, 'post_tag', '')");

        // Post 42: published, sticky, Yoast meta, infected script in content.
        $this->server->exec("INSERT INTO wp_posts VALUES
            (42, 1, '2024-05-01 10:00:00', '2024-05-01 05:00:00',
             '<p>Hello <script>alert(1)</script>clean world</p>', 'Best Jobs 2026', 'Jobs excerpt',
             'publish', 'best-jobs-2026', 'post', 0),
            (77, 1, '2024-06-01 09:00:00', '2024-06-01 04:00:00',
             '<p>About page <iframe src=\"http://evil.example\"></iframe>text</p>', 'About Us', '',
             'publish', 'about-us', 'page', 0),
            (55, 1, '2024-07-01 09:00:00', '2024-07-01 04:00:00',
             '<p>Draft post body</p>', 'Draft Post', '',
             'draft', 'draft-post', 'post', 0)");

        $this->server->exec("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES
            (42, '_yoast_wpseo_title', '%%title%% %%sep%% CareerInPak'),
            (42, '_yoast_wpseo_metadesc', 'Best jobs roundup 2026'),
            (77, 'rank_math_title', '%title% | CareerInPak'),
            (77, 'rank_math_description', 'About us page')");

        $this->server->exec("INSERT INTO wp_term_relationships VALUES (42, 3), (42, 4)");

        $this->server->exec("INSERT INTO wp_options (option_name, option_value) VALUES ('sticky_posts', '".serialize([42])."')");
    }

    protected function runMigration(array $extra = []): void
    {
        $this->artisan('wp:migrate', array_merge([
            '--from-db' => true,
            '--db-name' => self::TEST_DB,
            '--db-user' => $this->credentials['user'],
            '--db-pass' => $this->credentials['pass'],
            '--without-media' => true,
        ], $extra))->assertExitCode(0);
    }

    public function test_rejects_from_db_without_db_name(): void
    {
        $this->artisan('wp:migrate', ['--from-db' => true])->assertExitCode(1);
    }

    public function test_rejects_unreachable_database_without_crashing(): void
    {
        $this->artisan('wp:migrate', [
            '--from-db' => true,
            '--db-name' => 'no_such_db_xyz',
            '--db-user' => 'definitely-wrong-user',
            '--db-pass' => 'definitely-wrong-pass',
        ])->assertExitCode(1);
    }

    public function test_imports_posts_pages_terms_with_seo_slugs_and_dates(): void
    {
        $this->runMigration();

        // ---- post ----
        $post = Post::query()->where('name', 'Best Jobs 2026')->first();

        $this->assertNotNull($post, 'post imported');
        $this->assertEquals('draft', $post->status->getValue());
        $this->assertSame(1, $post->is_featured); // sticky preserved
        $this->assertSame('2024-05-01 05:00:00', $post->created_at->toDateTimeString()); // WP date preserved

        // malware stripped
        $this->assertStringNotContainsString('<script>', $post->content);
        $this->assertStringContainsString('clean world', $post->content);

        // slug preserved
        $slug = Slug::query()->where('key', 'best-jobs-2026')->first();
        $this->assertNotNull($slug);
        $this->assertSame(Post::class, $slug->reference_type);
        $this->assertEquals($post->getKey(), $slug->reference_id);

        // terms attached
        $this->assertTrue($post->categories->contains('name', 'Jobs'));
        $this->assertTrue($post->tags->contains('name', 'Govt'));

        // Yoast SEO meta → seo_meta metabox
        $postSeo = $post->getMetaData('seo_meta', true);
        $this->assertSame('Best Jobs 2026 - CareerInPak', $postSeo['seo_title']);
        $this->assertSame('Best jobs roundup 2026', $postSeo['seo_description']);

        // mapping row
        $this->assertNotNull(WpImportMapping::query()->where('wp_type', 'post')->where('wp_id', 42)->where('local_id', $post->getKey())->first());

        // ---- page ----
        $page = Page::query()->where('name', 'About Us')->first();

        $this->assertNotNull($page, 'page imported');
        $this->assertStringNotContainsString('<iframe', $page->content);
        $this->assertSame('About Us | CareerInPak', $page->getMetaData('seo_meta', true)['seo_title']);

        // ---- category imported from WP ----
        $category = Category::query()->where('name', 'Jobs')->first();
        $this->assertNotNull($category);
    }

    public function test_respects_statuses_option(): void
    {
        // default: publish only — the draft WP post must NOT be imported
        $this->runMigration();

        $this->assertNull(Post::query()->where('name', 'Draft Post')->first());

        // now include drafts explicitly
        $this->runMigration(['--statuses' => 'publish,draft']);

        $this->assertNotNull(Post::query()->where('name', 'Draft Post')->first());
    }

    public function test_respects_limit_option(): void
    {
        $this->runMigration(['--limit' => '1']);

        $this->assertSame(1, Post::query()->whereIn('name', ['Best Jobs 2026', 'Draft Post'])->count());
    }

    public function test_rerun_is_idempotent(): void
    {
        $this->runMigration();

        $postsAfterFirstRun = Post::count();
        $pagesAfterFirstRun = Page::count();

        $this->runMigration(); // same command again

        $this->assertSame($postsAfterFirstRun, Post::count(), 'no duplicate posts on re-run');
        $this->assertSame($pagesAfterFirstRun, Page::count(), 'no duplicate pages on re-run');
        $this->assertSame(1, WpImportMapping::query()->where('wp_type', 'post')->where('wp_id', 42)->count());
    }

    public function test_db_client_reads_seeded_fixtures(): void
    {
        $client = new \App\Services\Wp\WpDbClient(
            $this->credentials['host'],
            $this->credentials['port'],
            self::TEST_DB,
            $this->credentials['user'],
            $this->credentials['pass'],
        );

        $this->assertTrue($client->connected());

        $posts = $client->posts('post', ['publish']);

        $this->assertCount(1, $posts); // draft excluded by default

        $payload = $posts->first();

        $this->assertSame(42, $payload['id']);
        $this->assertSame('best-jobs-2026', $payload['slug']);
        $this->assertTrue($payload['sticky']);
        $this->assertSame('Best Jobs 2026 - CareerInPak', $payload['yoast_head_json']['title']);
        $this->assertSame('category', $payload['_embedded']['wp:term'][0][0]['taxonomy']);

        $categories = $client->categories();
        $this->assertSame('Jobs', $categories->first()['name']);
    }
}
