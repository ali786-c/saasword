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

    public function test_site_setup_applies_pakistan_seo_defaults(): void
    {
        // Seed demo widgets like the theme installer does, so the sidebar
        // assertions below are proven, not vacuously true. (Unique index on
        // widgets: sidebar_id+widget_id+theme+position — firstOrCreate.)
        \Botble\Widget\Models\Widget::query()->firstOrCreate([
            'sidebar_id' => 'primary_sidebar',
            'widget_id' => 'AboutWidget',
            'theme' => 'stories',
            'position' => 0,
        ], ['data' => []]);

        \Botble\Widget\Models\Widget::query()->firstOrCreate([
            'sidebar_id' => 'footer_sidebar',
            'widget_id' => 'CustomMenuWidget',
            'theme' => 'stories',
            'position' => 0,
        ], ['data' => []]);

        $this->assertSame(0, $this->artisanExitCode());

        // Site identity + Pakistan-targeted homepage meta.
        $this->assertSame('CareerInPak', theme_option('site_title'));
        $this->assertStringContainsString('Pakistan', (string) theme_option('seo_title'));
        $this->assertStringContainsString('Pakistan', (string) theme_option('seo_description'));
        $this->assertStringContainsString('ppsc jobs', (string) theme_option('seo_keywords'));

        // Job announcements use Article schema, not NewsArticle.
        $this->assertSame('Article', setting('blog_post_schema_type'));

        // Right rail: demo widgets (About me "Hello, I'm Steven", Popular
        // Posts, Galleries) must be wiped so the homepage collapses the
        // empty rail to full width.
        $this->assertSame(
            0,
            \Botble\Widget\Models\Widget::query()
                ->where('sidebar_id', 'primary_sidebar')
                ->where('theme', 'stories')
                ->count()
        );

        // ...but the footer sidebar (Quick links menu, Tags, Newsletter)
        // must be left intact.
        $this->assertGreaterThan(
            0,
            \Botble\Widget\Models\Widget::query()
                ->where('sidebar_id', 'footer_sidebar')
                ->where('theme', 'stories')
                ->count()
        );

        // Footer Quick links menu rebuilt with real sections, no demo noise.
        $quickLinks = Menu::query()->where('name', 'Quick links')->first();

        $this->assertNotNull($quickLinks);
        $this->assertSame(
            ['Home', 'Jobs', 'PPSC', 'FPSC', 'Pak Army'],
            MenuNode::query()
                ->where('menu_id', $quickLinks->id)
                ->where('parent_id', 0)
                ->orderBy('position')
                ->pluck('title')
                ->all()
        );
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

    public function test_site_setup_publishes_policy_pages_and_footer_legal_menu(): void
    {
        $this->assertSame(0, $this->artisanExitCode());

        // Every standard policy page published and slug-resolvable.
        foreach ([
            'about-us',
            'contact-us',
            'privacy-policy',
            'terms-conditions',
            'disclaimer',
            'editorial-policy',
        ] as $slug) {
            $slugRow = Slug::query()
                ->where('key', $slug)
                ->where('reference_type', \Botble\Page\Models\Page::class)
                ->first();

            $this->assertNotNull($slugRow, "Page slug '{$slug}' must exist");

            $page = Page::query()->find($slugRow->reference_id);

            $this->assertNotNull($page, "Page for slug '{$slug}' must exist");
            $this->assertSame(
                'published',
                $page->status->getValue(),
                "Policy page '{$page->name}' must be published"
            );
            $this->assertGreaterThan(0, mb_strlen(trim(strip_tags((string) $page->content))));
        }

        // Privacy Policy was missing after the WP import — command creates it.
        $privacy = Slug::query()->where('key', 'privacy-policy')->first();

        $this->assertNotNull($privacy);

        // Footer legal menu: one node per policy page.
        $menu = Menu::query()->where('name', 'Footer legal')->firstOrFail();

        $this->assertSame(6, MenuNode::query()->where('menu_id', $menu->id)->count());

        // Menus carry language meta — the language plugin otherwise hides
        // language-less menus on frontend requests.
        $this->assertSame(
            1,
            \Botble\Language\Models\LanguageMeta::query()
                ->where('reference_type', Menu::class)
                ->where('reference_id', $menu->id)
                ->count()
        );
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
