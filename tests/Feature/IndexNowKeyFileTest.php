<?php

namespace Tests\Feature;

use Botble\ACL\Models\User;
use Botble\SeoBoost\Services\IndexNowService;
use Tests\TestCase;

class IndexNowKeyFileTest extends TestCase
{
    public function test_key_file_is_served_as_plain_text_with_noindex_header(): void
    {
        $key = app(IndexNowService::class)->getKey();

        $response = $this->get("/{$key}.txt");

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=utf-8');
        $response->assertHeader('X-Robots-Tag', 'noindex');
        $this->assertSame($key, $response->getContent());
    }

    public function test_wrong_key_file_returns_404(): void
    {
        // Must be a valid 32-hex key that differs from the configured one.
        $this->get('/1234567890abcdef1234567890abcde1.txt')->assertStatus(404);
    }

    public function test_non_matching_paths_are_not_intercepted(): void
    {
        // Not a 32-hex .txt path: must not be handled by the plugin route.
        $this->get('/some-regular-page')->assertStatus(404);
    }

    public function test_admin_status_page_redirects_guests(): void
    {
        $adminDir = config('core.base.general.admin_dir', 'admin');

        $this->get("/{$adminDir}/seo-boost")->assertStatus(302);
    }

    public function test_admin_status_page_renders_for_admin_user(): void
    {
        $adminDir = config('core.base.general.admin_dir', 'admin');
        $user = User::query()->first();

        $response = $this->actingAs($user)->get("/{$adminDir}/seo-boost");

        $response->assertStatus(200);
        $response->assertSee(app(IndexNowService::class)->getKey());
    }
}
