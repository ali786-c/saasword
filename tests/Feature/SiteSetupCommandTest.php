<?php

namespace Tests\Feature;

use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Menu\Models\Menu;
use Botble\Menu\Models\MenuNode;
use Botble\Page\Models\Page;
use Botble\Slug\Models\Slug;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

/**
 * The cms:site-setup command is the single repeatable entry point for the
 * site's front-end scaffolding (menu, Pak Army category, homepage layout,
 * featured flags) — locally and on production. These tests pin its
 * contract: the exact menu shape, relative menu URLs (domain-agnostic),
 * featured-posts-as-hero homepage content, and idempotency.
 */
class SiteSetupCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        // Leave the command's end state in place (it IS the desired local
        // state); only remove content created purely by these tests.
        parent::tearDown();
    }

    public function test_site_setup_builds_menu_category_and_homepage(): void
    {
        $this->assertSame(0, $this->artisanExitCode());

        // 1. Pak Army category exists, published, slug attached.
        $pakArmy = Category::query()->where('name', 'Pak Army')->first();

        $this->assertNotNull($pakArmy, 'Pak Army category must exist');
        $this->assertSame('published', $pakArmy->status->getValue());
        $this->assertSame(
            'pak-army',
            Slug::query()
                ->where('reference_type', Category::class)
                ->where('reference_id', $pakArmy->id)
                ->value('key')
        );

        // 2. Main menu: exactly Home, Jobs, PPSC, FPSC, Pak Army — in order.
        $menu = Menu::query()->where('name', 'Main menu')->firstOrFail();

        $nodes = MenuNode::query()
            ->where('menu_id', $menu->id)
            ->where('parent_id', 0)
            ->orderBy('position')
            ->get();

        $this->assertSame(['Home', 'Jobs', 'PPSC', 'FPSC', 'Pak Army'], $nodes->pluck('title')->all());

        // Every category-backed node must reference the real category and
        // carry a RELATIVE url (works on localhost AND production domain).
        foreach ($nodes->skip(1) as $node) {
            $this->assertSame(Category::class, $node->reference_type);

            $category = Category::query()->find($node->reference_id);

            $this->assertNotNull($category, "Menu node {$node->title} must reference a real category");
            $this->assertSame(
                strtolower($node->title) === 'pak army' ? 'pak-army' : str_replace(' ', '-', strtolower($node->title)),
                $category->slugable->key ?? '',
                "Menu node {$node->title} must point at its own category slug"
            );

            $this->assertStringStartsWith('/', (string) $node->url);
            $this->assertStringNotContainsString('http', (string) $node->url, 'Menu URLs must be relative, not absolute');
        }

        // 3. Homepage: featured-posts hero present, about-banner gone.
        $homepageId = (int) theme_option('homepage_id');

        $this->assertGreaterThan(0, $homepageId);

        $page = Page::query()->find($homepageId);

        $this->assertNotNull($page);
        $this->assertStringContainsString('[featured-posts', (string) $page->content);
        $this->assertStringNotContainsString('about-banner', (string) $page->content);
        $this->assertStringContainsString('[blog-categories-posts', (string) $page->content);
        $this->assertStringContainsString('[categories-with-posts', (string) $page->content);
        $this->assertStringContainsString('[featured-categories', (string) $page->content);
    }

    public function test_site_setup_is_idempotent(): void
    {
        $this->assertSame(0, $this->artisanExitCode());

        $menu = Menu::query()->where('name', 'Main menu')->firstOrFail();
        $nodeCount = MenuNode::query()->where('menu_id', $menu->id)->count();
        $pakArmyCount = Category::query()->where('name', 'Pak Army')->count();
        $homepageId = (int) theme_option('homepage_id');

        // Re-run: nothing duplicated, nothing lost.
        $this->assertSame(0, $this->artisanExitCode());

        $this->assertSame($nodeCount, MenuNode::query()->where('menu_id', $menu->id)->count());
        $this->assertSame(1, $pakArmyCount, 'Pak Army category must not be duplicated');
        $this->assertSame(1, Category::query()->where('name', 'Pak Army')->count());
        $this->assertSame($homepageId, (int) theme_option('homepage_id'));
    }

    public function test_site_setup_flags_newest_published_posts_as_featured(): void
    {
        $this->assertSame(0, $this->artisanExitCode());

        $featured = Post::query()->where('is_featured', 1)->count();

        $this->assertGreaterThanOrEqual(5, $featured, 'At least 5 posts must be flagged featured for the hero carousel');
    }

    protected function artisanExitCode(): int
    {
        return Artisan::call('cms:site-setup');
    }
}
