<?php

namespace Tests\Feature;

use App\Models\WpImportMapping;
use App\Services\LegacyUrlRedirector;
use Botble\ACL\Models\User;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Setting\Facades\Setting;
use Tests\TestCase;

/**
 * Regression coverage for two production incidents:
 *
 *  1. "Call to undefined function legacyRedirectForSlug()" — helper
 *     functions defined in routes/web.php vanish when the route cache
 *     (`php artisan optimize`) serializes the fallback closure. The fix
 *     moved them into App\Services\LegacyUrlRedirector (autoloaded class).
 *
 *  2. SEO Boost master checkboxes silently unchecking themselves —
 *     the core checkbox component rendered value="" when no value prop
 *     was passed, so the browser submitted "on" and the controller's
 *     boolean() check read it as false. The fix passes :value="'1'".
 */
class RegressionFixesTest extends TestCase
{
    protected string $originalEnabled = '1';

    protected string $originalGoogleEnabled = '1';

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalEnabled = (string) setting('seo_boost_enabled', '1');
        $this->originalGoogleEnabled = (string) setting('seo_boost_google_enabled', '1');

        config(['app.url' => 'https://tests.example']);
        \Illuminate\Support\Facades\URL::forceRootUrl('https://tests.example');
    }

    protected function tearDown(): void
    {
        Post::query()->where('name', 'like', 'Regression test %')->delete();
        \Botble\Slug\Models\Slug::query()->where('key', 'like', 'regression-%')->delete();
        Category::query()->where('name', 'like', 'Regression test %')->delete();
        WpImportMapping::query()->where('wp_slug', 'like', 'regression-%')->delete();
        Setting::set('seo_boost_enabled', $this->originalEnabled)->save();
        Setting::set('seo_boost_google_enabled', $this->originalGoogleEnabled)->save();

        parent::tearDown();
    }

    protected function admin(): User
    {
        return User::query()->firstOrFail();
    }

    protected function makePost(string $suffix): Post
    {
        $name = 'Regression test ' . $suffix;
        $author = $this->admin();

        $post = Post::create([
            'name' => $name,
            'content' => '<p>x</p>',
            'status' => BaseStatusEnum::PUBLISHED,
            'author_type' => get_class($author),
            'author_id' => $author->id,
        ]);

        \Botble\Slug\Models\Slug::create([
            'key' => 'regression-test-' . $suffix,
            'reference_id' => $post->id,
            'reference_type' => get_class($post),
            'prefix' => null,
        ]);

        return $post;
    }

    protected function makeMapping(string $wpType, string $wpSlug, int $localId, string $localType): void
    {
        WpImportMapping::query()->create([
            'wp_type' => $wpType,
            'wp_id' => 999999,
            'wp_slug' => $wpSlug,
            'local_id' => $localId,
            'local_type' => $localType,
            'status' => 'imported',
        ]);
    }

    public function test_redirector_service_resolves_mapping_slug(): void
    {
        // Imported posts keep their WP slug, so single-segment legacy URLs
        // resolve directly through the theme's public.single route — no
        // 301 needed (the URL never changed). The fallback only owns
        // multi-segment patterns (dated permalinks, category/tag archives).
        // The service itself must still resolve slugs for those patterns.
        $post = $this->makePost('svc-1');

        $this->makeMapping('post', 'regression-test-svc-1', (int) $post->id, get_class($post));

        $redirector = app(LegacyUrlRedirector::class);

        $this->assertSame($post->url, $redirector->redirectForSlug('regression-test-svc-1'));
        $this->assertNull($redirector->redirectForSlug('definitely-not-imported'));
    }

    public function test_legacy_dated_permalink_redirects_to_post(): void
    {
        $post = $this->makePost('redirect-2');

        $this->makeMapping('post', 'regression-test-redirect-2', (int) $post->id, get_class($post));

        $this->get('/2024/05/regression-test-redirect-2')
            ->assertStatus(301)
            ->assertRedirect($post->url);

        $this->get('/2024/05/12/regression-test-redirect-2')
            ->assertStatus(301)
            ->assertRedirect($post->url);
    }

    public function test_legacy_category_archive_redirects(): void
    {
        $category = Category::query()->create([
            'name' => 'Regression test cat',
            'status' => BaseStatusEnum::PUBLISHED,
        ]);

        \Botble\Slug\Models\Slug::create([
            'key' => 'regression-cat',
            'reference_id' => $category->id,
            'reference_type' => get_class($category),
            'prefix' => 'categories',
        ]);

        $this->makeMapping('category', 'regression-cat', (int) $category->id, get_class($category));

        $this->get('/category/regression-cat')
            ->assertStatus(301)
            ->assertRedirect($category->url);
    }

    public function test_unknown_legacy_path_returns_404(): void
    {
        $this->get('/definitely-not-a-real-path-xyz')
            ->assertStatus(404);
    }

    public function test_seo_boost_master_checkbox_submitted_as_on_persists(): void
    {
        // Browsers submit "on" when a checkbox has no value attribute —
        // exactly what the broken settings page sent.
        Setting::set('seo_boost_enabled', '0')->save();

        $this->actingAs($this->admin())
            ->postJson($this->adminUrl('/seo-boost/settings'), [
                'seo_boost_enabled' => 'on',
                'seo_boost_post_types' => ['post' => '1'],
            ])
            ->assertStatus(200);

        $this->assertSame('1', setting('seo_boost_enabled'));
    }

    public function test_seo_boost_google_master_checkbox_submitted_as_on_persists(): void
    {
        Setting::set('seo_boost_enabled', '0')->save();
        Setting::set('seo_boost_google_enabled', '0')->save();

        $this->actingAs($this->admin())
            ->postJson($this->adminUrl('/seo-boost/settings'), [
                'seo_boost_enabled' => 'on',
                'seo_boost_google_enabled' => 'on',
            ])
            ->assertStatus(200);

        $this->assertSame('1', setting('seo_boost_google_enabled'));
    }

    public function test_seo_boost_settings_page_renders_checkboxes_with_value_attr(): void
    {
        Setting::set('seo_boost_enabled', '1')->save();
        Setting::set('seo_boost_google_enabled', '1')->save();

        $html = $this->actingAs($this->admin())
            ->get($this->adminUrl('/seo-boost/settings'))
            ->assertStatus(200)
            ->getContent();

        $this->assertMatchesRegularExpression(
            '/name="seo_boost_enabled"[^>]*value="1"/s',
            (string) $html
        );
        $this->assertMatchesRegularExpression(
            '/name="seo_boost_google_enabled"[^>]*value="1"/s',
            (string) $html
        );
    }

    protected function adminUrl(string $path): string
    {
        return '/' . config('core.base.general.admin_dir', 'admin') . $path;
    }
}
