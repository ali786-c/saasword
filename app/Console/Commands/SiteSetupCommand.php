<?php

namespace App\Console\Commands;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Menu\Models\Menu;
use Botble\Menu\Models\MenuLocation;
use Botble\Menu\Models\MenuNode;
use Botble\Page\Models\Page;
use Botble\Slug\Models\Slug;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use Botble\ACL\Models\User;

/**
 * Idempotent site scaffolding for CareerInPak:
 *
 *  1. Category "Pak Army" (created when missing)
 *  2. Main menu rebuilt to: Home, Jobs, PPSC, FPSC, Pak Army
 *     (menu nodes reference real categories with RELATIVE urls so the same
 *     data works on localhost and production)
 *  3. Homepage: Featured posts section as the hero (about-banner removed),
 *     then per-category post sections and featured categories
 *  4. 5 newest published posts flagged as featured (hero carousel needs them)
 *
 * Safe to re-run: uses firstOrCreate everywhere and wipes only the menu
 * nodes it owns before recreating them.
 */
class SiteSetupCommand extends Command
{
    protected $signature = 'cms:site-setup
        {--menu-only : Only rebuild the menu, skip homepage/featured setup}
        {--home-only : Only set up the homepage, skip menu/category setup}';

    protected $description = 'Set up main menu (Home, Jobs, PPSC, FPSC, Pak Army), Pak Army category, homepage with featured-posts hero, and featured flags';

    /**
     * Menu structure: title => category name to link (null = custom link).
     */
    protected array $menuItems = [
        'Home' => null,
        'Jobs' => 'Jobs',
        'PPSC' => 'PPSC',
        'FPSC' => 'FPSC',
        'Pak Army' => 'Pak Army',
    ];

    /**
     * Homepage sections below the hero: [section => category name(s)].
     */
    protected array $homepageSections = [
        'single' => 'Jobs',
        'triple' => ['Scholarships', 'Govt Jobs', 'Blog'],
    ];

    public function handle(): int
    {
        $menuOnly = (bool) $this->option('menu-only');
        $homeOnly = (bool) $this->option('home-only');

        if (! $homeOnly) {
            $this->ensurePakArmyCategory();
            $this->rebuildMainMenu();
        }

        if (! $menuOnly) {
            $this->setupHomepage();
            $this->flagFeaturedPosts();
        }

        $this->info('Clearing caches...');
        $this->callSilent('optimize:clear');

        $this->newLine();
        $this->info('✔ Site setup complete. Re-run any time — it is idempotent.');

        return self::SUCCESS;
    }

    protected function ensurePakArmyCategory(): void
    {
        $author = User::query()->first();

        $category = Category::query()->firstOrCreate(
            ['name' => 'Pak Army'],
            [
                'description' => 'Pakistan Army jobs, recruitment and results',
                'parent_id' => 0,
                'status' => BaseStatusEnum::PUBLISHED,
                'author_id' => $author?->id ?: 1,
                'author_type' => $author ? $author::class : User::class,
            ]
        );

        Slug::query()->firstOrCreate(
            [
                'key' => Str::slug('Pak Army'),
                'reference_type' => Category::class,
            ],
            [
                'reference_id' => $category->id,
                'prefix' => '',
            ]
        );

        $this->info("Category 'Pak Army': ID {$category->id}");
    }

    protected function rebuildMainMenu(): void
    {
        $menu = Menu::query()->firstOrCreate(['name' => 'Main menu']);

        // Keep the location binding pointing at this menu (header uses main-menu).
        MenuLocation::query()->updateOrCreate(
            ['location' => 'main-menu'],
            ['menu_id' => $menu->id]
        );

        // Rebuild: drop this menu's nodes and recreate the 5 items fresh.
        // (Other menus, e.g. "Quick links", are left untouched.)
        MenuNode::query()->where('menu_id', $menu->id)->delete();

        $position = 0;

        foreach ($this->menuItems as $title => $categoryName) {
            $attributes = [
                'menu_id' => $menu->id,
                'parent_id' => 0,
                'title' => $title,
                'target' => '_self',
                'has_child' => 0,
                'position' => $position++,
            ];

            if ($categoryName === null) {
                // Custom link: Home.
                MenuNode::query()->create($attributes + ['reference_id' => 0, 'reference_type' => null, 'url' => '/']);

                continue;
            }

            $category = Category::query()->where('name', $categoryName)->first();

            if (! $category) {
                $this->warn("Category '{$categoryName}' not found — menu item '{$title}' skipped.");

                continue;
            }

            // Store the RELATIVE url (like the admin UI's slug updater does)
            // so the same node works on any domain.
            $relativeUrl = str_replace(url(''), '', $category->url) ?: '/';

            MenuNode::query()->create(
                $attributes + [
                    'reference_id' => $category->id,
                    'reference_type' => $category::class,
                    'url' => $relativeUrl,
                ]
            );

            $this->line("  Menu: {$title} → {$relativeUrl}");
        }

        $this->info('Main menu rebuilt: ' . implode(', ', array_keys($this->menuItems)));
    }

    protected function setupHomepage(): void
    {
        $page = Page::query()->firstOrCreate(
            ['name' => 'Home'],
            ['status' => BaseStatusEnum::PUBLISHED, 'user_id' => 1]
        );

        $page->content = $this->buildHomepageContent();
        $page->save();

        theme_option()->setOption('homepage_id', $page->id)->saveOptions();

        $this->info("Homepage set: Page '{$page->name}' (ID {$page->id})");
    }

    protected function buildHomepageContent(): string
    {
        // Featured posts section acts as the hero (replaces about-banner).
        $content = '[featured-posts title="Featured posts"][/featured-posts]';

        if ($single = $this->categoryId($this->homepageSections['single'])) {
            $content .= "[blog-categories-posts category_id=\"{$single}\"][/blog-categories-posts]";
        }

        $triple = collect($this->homepageSections['triple'])
            ->map(fn (string $name) => $this->categoryId($name))
            ->filter()
            ->all();

        if ($triple) {
            $content .= '[categories-with-posts'
                . ' category_id_1="' . Arr::get($triple, 0, 0) . '"'
                . ' category_id_2="' . Arr::get($triple, 1, 0) . '"'
                . ' category_id_3="' . Arr::get($triple, 2, 0) . '"'
                . '][/categories-with-posts]';
        }

        $content .= '[featured-categories title="Categories"][/featured-categories]';

        return $content;
    }

    protected function categoryId(string $name): ?int
    {
        return Category::query()->where('name', $name)->value('id');
    }

    protected function flagFeaturedPosts(): void
    {
        $newest = Post::query()
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->latest('created_at')
            ->limit(5)
            ->pluck('id');

        if ($newest->isEmpty()) {
            $this->warn('No published posts found — featured flags skipped.');

            return;
        }

        Post::query()->whereIn('id', $newest)->update(['is_featured' => 1]);

        $this->info("Featured flags set on {$newest->count()} newest published posts.");
    }
}
