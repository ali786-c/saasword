<?php

namespace App\Console\Commands;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Menu\Models\Menu;
use Botble\Menu\Models\MenuLocation;
use Botble\Menu\Models\MenuNode;
use Botble\Page\Models\Page;
use Botble\Setting\Facades\Setting;
use Botble\Slug\Models\Slug;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Botble\Base\Models\MetaBox as MetaBoxModel;

use Botble\ACL\Models\User;

/**
 * Idempotent site scaffolding for CareerInPak:
 *
 *  1. Category "Pak Army" (created when missing)
 *  2. Main menu rebuilt to: Home, Jobs, PPSC, FPSC, Pak Army
 *     (menu nodes reference real categories with RELATIVE urls so the same
 *     data works on localhost and production)
 *  3. Homepage: Featured posts section as the hero (about-banner removed),
 *     followed by the Jobs category post section (end-of-page category
 *     blocks removed — they pushed real job posts below the fold)
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
     * Homepage sections below the hero: [section => category name].
     */
    protected array $homepageSections = [
        'single' => 'Jobs',
    ];

    /**
     * Keyword-rich SEO copy blocks under the homepage grid. Each block gets
     * its own h2 + paragraph targeting a distinct keyword cluster, then an
     * internal-link list over every published category (slug-derived
     * anchors: "PPSC Jobs 2026", "Pak Army Jobs"...) and the policy pages.
     */
    protected array $homepageSeoBlocks = [
        [
            'heading' => 'Latest Jobs in Pakistan 2026',
            'text' => 'CareerInPak brings you the latest jobs in Pakistan 2026 from every corner of the country. Our team tracks announcements from federal and provincial departments, banks, universities and private companies, then publishes each listing with the official advertisement, eligibility criteria and a direct online apply link. Whether you are searching for today jobs in Pakistan or planning your next career move, this page is updated daily so you never miss a deadline.',
        ],
        [
            'heading' => 'Government Jobs 2026 — PPSC, FPSC, NADRA & More',
            'text' => 'Government jobs in Pakistan remain the most demanded career path, offering job security, allowances and a clear promotion track. We list every major announcement — PPSC jobs, FPSC jobs, NADRA jobs, police jobs, railway jobs and WAPDA jobs — with age limits, education requirements, domicile rules and test preparation guidance so you can apply with confidence.',
        ],
        [
            'heading' => 'Pak Army Jobs & Forces Recruitment',
            'text' => 'Joining the Pakistan Armed Forces is a matter of pride. From Pak Army jobs for soldiers and officers to Pakistan Navy and PAF recruitment, we publish registration dates, physical test standards, merit lists and final selection updates for every batch across all recruitment centres in Pakistan.',
        ],
        [
            'heading' => 'Scholarships for Pakistani Students 2026',
            'text' => 'Education should never stop because of money. We collect national and international scholarships for Pakistani students 2026 — HEC scholarships, fully funded masters and PhD programmes, and undergraduate admissions with fee waivers — including deadlines, required documents and step-by-step application instructions.',
        ],
    ];

    public function handle(): int
    {
        $menuOnly = (bool) $this->option('menu-only');
        $homeOnly = (bool) $this->option('home-only');

        if (! $homeOnly) {
            $this->ensurePakArmyCategory();
            $this->rebuildMainMenu();
            $this->rebuildQuickLinksMenu();
        }

        if (! $menuOnly) {
            $this->setupHomepage();
            $this->flagFeaturedPosts();
        }

        $this->setupFooterBranding();
        $this->setupLogosAndFavicon();
        $this->publishPolicyPages();
        $this->rebuildFooterLegalMenu();

        $this->removeSidebarWidgets();
        $this->setupSeo();

        $this->info('Clearing caches...');

        // The front menu renderer persists its own cache group; flush it or
        // freshly rebuilt menus keep rendering the old nodes.
        \Botble\Support\Services\Cache\Cache::make(\Botble\Menu\Models\Menu::class)->flush();

        $this->callSilent('optimize:clear');

        $this->newLine();
        $this->info('✔ Site setup complete. Re-run any time — it is idempotent.');

        return self::SUCCESS;
    }

    /**
     * The theme ships a demo right rail (About me "Hello, I'm Steven",
     * Popular Posts, Galleries). A jobs portal needs no blog sidebar, and
     * the demo author copy must never show on the live site — so empty the
     * whole primary_sidebar. The layouts collapse to full width while the
     * rail is empty, and it comes back automatically if real widgets are
     * added later in Admin -> Appearance -> Widgets.
     */
    protected function removeSidebarWidgets(): void
    {
        $deleted = \Botble\Widget\Models\Widget::query()
            ->where('sidebar_id', 'primary_sidebar')
            ->where('theme', 'like', 'stories%')
            ->delete();

        $this->info($deleted ? "Right-sidebar widgets removed ({$deleted} rows)." : 'Right-sidebar widgets already removed.');
    }

    /**
     * Pakistan-targeted SEO defaults: site identity, homepage title/meta,
     * keyword set and the Article schema type for job posts. Idempotent —
     * only writes values, never clears user edits made afterwards in the
     * admin UI (they will simply be overwritten if this command is re-run).
     */
    protected function setupSeo(): void
    {
        $meta = [
            'site_title' => 'CareerInPak',
            'seo_title' => 'CareerInPak – Latest Jobs, PPSC, FPSC, Pak Army & Scholarships in Pakistan',
            'seo_description' => 'Daily updates for government and private jobs in Pakistan: PPSC, FPSC, Pak Army, NADRA, police and scholarship alerts with official online apply links.',
            'seo_keywords' => 'jobs in pakistan, government jobs 2026, ppsc jobs, fpsc jobs, pak army jobs, nadra jobs, police jobs, scholarships in pakistan, online apply, career in pak',
        ];

        theme_option()->setOptions($meta)->saveOptions();

        // Blog plugin renders NewsArticle by default; job announcements
        // are better marked as Article (NewsArticle is for news outlets).
        Setting::set('blog_post_schema_type', 'Article')->save();

        $this->info('SEO defaults applied (site title, homepage meta, keywords, Article schema).');
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

    /**
     * The language plugin filters menus by a language_meta row on frontend
     * requests; freshly created menus without one silently vanish from the
     * site. Mirror the installer's locale code ('en_US').
     */
    protected function ensureLanguageMeta(Model $model): void
    {
        if (! class_exists(\Botble\Language\Models\LanguageMeta::class)) {
            return;
        }

        if (! in_array($model::class, \Botble\Language\Facades\Language::supportedModels())) {
            return;
        }

        $code = \Botble\Language\Models\LanguageMeta::query()
            ->where('reference_type', $model::class)
            ->value('lang_meta_code') ?: 'en_US';

        \Botble\Language\Models\LanguageMeta::query()->firstOrCreate(
            [
                'reference_id' => $model->getKey(),
                'reference_type' => $model::class,
                'lang_meta_code' => $code,
            ],
            ['lang_meta_origin' => $code]
        );
    }

    protected function rebuildMainMenu(): void
    {
        $menu = Menu::query()->firstOrCreate(
            ['name' => 'Main menu'],
            ['status' => BaseStatusEnum::PUBLISHED]
        );

        $this->ensureLanguageMeta($menu);

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

    /**
     * Footer "Quick links" menu (rendered by CustomMenuWidget): replace the
     * demo items (Travel, Galleries, ...) with the site's real sections so
     * no demo page is reachable from the footer navigation.
     */
    protected function rebuildQuickLinksMenu(): void
    {
        $menu = Menu::query()->firstOrCreate(
            ['name' => 'Quick links'],
            ['status' => BaseStatusEnum::PUBLISHED]
        );

        $this->ensureLanguageMeta($menu);

        MenuNode::query()->where('menu_id', $menu->id)->delete();

        $items = ['Home' => null, 'Jobs' => 'Jobs', 'PPSC' => 'PPSC', 'FPSC' => 'FPSC', 'Pak Army' => 'Pak Army'];

        $position = 0;

        foreach ($items as $title => $categoryName) {
            $attributes = [
                'menu_id' => $menu->id,
                'parent_id' => 0,
                'title' => $title,
                'target' => '_self',
                'has_child' => 0,
                'position' => $position++,
            ];

            if ($categoryName === null) {
                MenuNode::query()->create($attributes + ['reference_id' => 0, 'reference_type' => null, 'url' => '/']);

                continue;
            }

            $category = Category::query()->where('name', $categoryName)->first();

            if (! $category) {
                continue;
            }

            MenuNode::query()->create(
                $attributes + [
                    'reference_id' => $category->id,
                    'reference_type' => $category::class,
                    'url' => str_replace(url(''), '', $category->url) ?: '/',
                ]
            );
        }

        $this->info('Quick links menu rebuilt.');
    }

    protected function setupHomepage(): void
    {
        $page = Page::query()->firstOrCreate(
            ['name' => 'Home'],
            ['status' => BaseStatusEnum::PUBLISHED, 'user_id' => 1]
        );

        $page->content = $this->buildHomepageContent();
        $page->save();

        $this->saveSeoTextMeta($page);

        theme_option()->setOption('homepage_id', $page->id)->saveOptions();

        $this->info("Homepage set: Page '{$page->name}' (ID {$page->id})");
    }

    protected function buildHomepageContent(): string
    {
        // Hero: Most Recent Jobs (big card with manual arrows) + Latest
        // Jobs column — replaces the old featured-posts carousel.
        $content = '[recent-jobs-hero title="Most Recent Jobs" latest_title="Latest Jobs"][/recent-jobs-hero]';

        // All recent jobs in a plain grid (no auto-scroll).
        $content .= '[recent-jobs-grid title="Recent Jobs" limit="8"][/recent-jobs-grid]';

        // SEO text block: keyword-rich intro + internal links to every
        // category and policy page (crawlable, edits survive re-runs).
        $content .= '[homepage-seo-text][/homepage-seo-text]';

        return $content;
    }

    protected function categoryId(string $name): ?int
    {
        return Category::query()->where('name', $name)->value('id');
    }

    /**
     * Persist the SEO text blocks (headings + copy) as homepage page meta.
     * The [homepage-seo-text] shortcode renders them; storing the copy here
     * (not hardcoded in the theme) keeps it editable and re-runnable.
     */
    protected function saveSeoTextMeta(Page $page): void
    {
        MetaBoxModel::query()->updateOrCreate(
            [
                'reference_id' => $page->id,
                'reference_type' => $page::class,
                'meta_key' => 'homepage_seo_blocks',
            ],
            ['meta_value' => $this->homepageSeoBlocks]
        );

        $this->info('Homepage SEO text section saved (' . count($this->homepageSeoBlocks) . ' blocks).');
    }

    /**
     * Replace the theme's demo footer branding (AliThemes credit, New York
     * address, placeholder description) with CareerInPak identity.
     */
    protected function setupFooterBranding(): void
    {
        theme_option()->setOptions([
            'copyright' => '© :year CareerInPak. All rights reserved.',
            'designed_by' => '',
            'site_description' => "CareerInPak is Pakistan's trusted jobs and scholarships portal — daily updates on government jobs, PPSC, FPSC, Pak Army, NADRA, police and scholarships across Pakistan.",
            'address' => 'Pakistan',
        ])->saveOptions();

        $this->info('Footer branding updated (copyright, description, address).');
    }

    /**
     * Copy & configure CareerInPak SVG logo and favicon assets.
     */
    protected function setupLogosAndFavicon(): void
    {
        $themeImgDir = platform_path('themes/stories/public/images');
        $targetDir = public_path('storage/logos');

        if (! file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $filesMap = [
            'logo.svg' => 'careerinpak-logo.svg',
            'logo-footer.svg' => 'careerinpak-logo-footer.svg',
            'favicon.svg' => 'favicon.svg',
        ];

        foreach ($filesMap as $themeFile => $storageFile) {
            $src = $themeImgDir . '/' . $themeFile;
            $dst = $targetDir . '/' . $storageFile;
            if (file_exists($src)) {
                copy($src, $dst);
            }
        }

        $logoPath = 'logos/careerinpak-logo.svg';
        $logoFooterPath = 'logos/careerinpak-logo-footer.svg';
        $faviconPath = 'logos/favicon.svg';

        theme_option()->setOptions([
            'logo' => $logoPath,
            'logo_footer' => $logoFooterPath,
            'favicon' => $faviconPath,
        ])->saveOptions();

        \Botble\Setting\Facades\Setting::set('favicon', $faviconPath)
            ->set('admin_favicon', $faviconPath)
            ->save();

        $this->info('SVG Logo & Favicon configured (careerinpak-logo.svg, favicon.svg).');
    }

    /**
     * Policy/landing pages every Pakistani site needs (AdSense + Ad Network
     * approval). Imported WP pages keep their real content and are only
     * published; missing ones are created with CareerInPak templates.
     * Idempotent via firstOrCreate.
     */
    protected function publishPolicyPages(): void
    {
        $author = User::query()->first();

        foreach ($this->policyPages() as $name => $template) {
            // Match by slug, not name: SafeContent stores names HTML-encoded
            // ("Terms &amp; Conditions"), so name lookups silently miss.
            $page = Page::query()
                ->whereHas('slugable', fn ($query) => $query->where('key', Str::slug($name)))
                ->first();

            if ($page) {
                $changed = false;

                if ($page->status->getValue() !== BaseStatusEnum::PUBLISHED) {
                    $page->status = BaseStatusEnum::PUBLISHED;
                    $changed = true;
                }

                if (trim(strip_tags((string) $page->content)) === '') {
                    $page->content = $template;
                    $changed = true;
                }

                if ($changed) {
                    $page->save();
                    $this->line("  Page '{$name}' published (ID {$page->id}).");
                }
            } else {
                $page = Page::query()->create([
                    'name' => $name,
                    'content' => $template,
                    'status' => BaseStatusEnum::PUBLISHED,
                    'user_id' => $author?->id ?: 1,
                ]);

                $this->line("  Page '{$name}' created (ID {$page->id}).");
            }

            Slug::query()->firstOrCreate(
                [
                    'key' => Str::slug($name),
                    'reference_type' => $page::class,
                ],
                [
                    'reference_id' => $page->id,
                    'prefix' => '',
                ]
            );
        }
    }

    protected function policyPages(): array
    {
        return [
            'About Us' => '<h2>About CareerInPak</h2><p>CareerInPak is an independent career information portal for Pakistan. We publish daily updates on government and private jobs, PPSC and FPSC announcements, Pak Army recruitment, scholarships and results.</p>',
            'Contact Us' => '<h2>Contact CareerInPak</h2><p>Have a question, correction or job advertisement enquiry? Reach us at <strong>info@careerinpak.com</strong> and we will get back to you.</p>',
            'Privacy Policy' => $this->privacyPolicyContent(),
            'Terms & Conditions' => '<h2>Terms &amp; Conditions</h2><p>Welcome to CareerInPak. By accessing this website you agree to these terms. Content is provided for general information only; verify all job details from official sources before applying.</p>',
            'Disclaimer' => '<h2>Disclaimer</h2><p>CareerInPak is not a recruiter and is not affiliated with any government department. Job advertisements are reproduced for information only — always verify from the official advertisement and apply through official channels.</p>',
            'Editorial and Verification Policy' => '<h2>Editorial and Verification Policy</h2><p>Every job and scholarship post on CareerInPak is checked against its official advertisement before publishing. Sources include departmental websites and mainstream Pakistani newspapers.</p>',
        ];
    }

    protected function privacyPolicyContent(): string
    {
        return '<h2>Privacy Policy for CareerInPak</h2>'
            . '<p>At CareerInPak, accessible from https://careerinpak.com, the privacy of our visitors in Pakistan and worldwide is one of our main priorities. This Privacy Policy document describes the types of information that are collected and how we use them.</p>'
            . '<h3>Information we collect</h3>'
            . '<p>If you contact us or leave a comment, we may collect your name and email address. Like most websites, our servers also record standard log data such as browser type, pages visited, time of visit and referring page.</p>'
            . '<h3>Cookies and web beacons</h3>'
            . '<p>CareerInPak uses cookies to store visitor preferences and optimize the user experience. You can disable cookies through your individual browser options.</p>'
            . '<h3>Google AdSense and advertising partners</h3>'
            . '<p>We may use Google AdSense and other advertising partners. Third-party vendors, including Google, use cookies (such as the DoubleClick DART cookie) to serve ads based on your visits to this and other websites. You may opt out of personalized advertising by visiting <a href="https://www.google.com/settings/ads" rel="nofollow noopener" target="_blank">Google Ads Settings</a>.</p>'
            . '<h3>Analytics</h3>'
            . '<p>We use analytics tools to understand traffic patterns and improve content. These tools collect aggregated, non-personally-identifying data.</p>'
            . '<h3>How we use your information</h3>'
            . '<ul><li>To operate and maintain this website</li><li>To reply to your emails and queries</li><li>To detect and prevent spam or abuse</li><li>To display relevant advertisements</li></ul>'
            . '<h3>Third-party links</h3>'
            . '<p>Job posts link to official departmental websites and newspapers. We are not responsible for the privacy practices of those external sites.</p>'
            . '<h3>Children\'s information</h3>'
            . '<p>CareerInPak does not knowingly collect any personally identifiable information from children under the age of 13.</p>'
            . '<h3>Consent</h3>'
            . '<p>By using our website, you hereby consent to this Privacy Policy and agree to its terms. For questions, contact us at <strong>info@careerinpak.com</strong>.</p>';
    }

    /**
     * Footer legal bar: Privacy/Terms/Disclaimer/etc. as a dedicated menu
     * location so the links stay admin-editable after seeding.
     */
    protected function rebuildFooterLegalMenu(): void
    {
        $menu = Menu::query()->firstOrCreate(
            ['name' => 'Footer legal'],
            ['status' => BaseStatusEnum::PUBLISHED]
        );

        $this->ensureLanguageMeta($menu);

        \Botble\Menu\Models\MenuLocation::query()->updateOrCreate(
            ['location' => 'footer-legal'],
            ['menu_id' => $menu->id]
        );

        MenuNode::query()->where('menu_id', $menu->id)->delete();

        $position = 0;

        foreach (array_keys($this->policyPages()) as $name) {
            $page = Page::query()
                ->whereHas('slugable', fn ($query) => $query->where('key', Str::slug($name)))
                ->first();

            if (! $page) {
                continue;
            }

            $slugKey = $page->slugable->key ?? Str::slug($name);

            MenuNode::query()->create([
                'menu_id' => $menu->id,
                'parent_id' => 0,
                'title' => $name,
                'target' => '_self',
                'has_child' => 0,
                'position' => $position++,
                'reference_id' => $page->id,
                'reference_type' => $page::class,
                'url' => '/' . $slugKey,
            ]);
        }

        $this->info('Footer legal menu rebuilt (' . $position . ' links).');
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
