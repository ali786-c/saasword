<?php

namespace App\Console\Commands;

use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Page\Models\Page;
use Botble\Slug\Models\Slug;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupDemoContentCommand extends Command
{
    protected $signature = 'cms:cleanup-demo-content {--dry-run : Only list what would be deleted without actually deleting}';

    protected $description = 'Safely purge theme demo posts, demo layout pages, demo categories, and duplicate posts for AdSense compliance';

    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');

        $this->info("==================================================");
        $this->info("🚀 CareerInPak — AdSense Demo Content Cleanup");
        $this->info("==================================================");
        if ($isDryRun) {
            $this->warn("MODE: DRY RUN (No changes will be saved)");
        }

        $this->cleanupDemoPosts($isDryRun);
        $this->cleanupDemoPages($isDryRun);
        $this->cleanupDemoCategories($isDryRun);
        $this->deduplicateJobPosts($isDryRun);

        if (! $isDryRun) {
            $this->info("\n🧹 Clearing application and view caches...");
            $this->callSilent('optimize:clear');
            $this->info("✔ Cache cleared successfully!");
        }

        $this->info("\n🎉 Cleanup completed successfully! Your site is now sanitized for Google AdSense.");
        return self::SUCCESS;
    }

    /**
     * Purge all theme demo posts (Handbags, Wrinkles, Weight loss, Hello world, etc.)
     */
    protected function cleanupDemoPosts(bool $dryRun): void
    {
        $this->line("\n--- Step 1: Cleaning Up Demo Posts ---");

        $demoTitles = [
            'The Top 2020 Handbag Trends to Know',
            'Top Search Engine Optimization Strategies!',
            'Which Company Would You Choose?',
            'Used Car Dealer Sales Tricks Exposed',
            '20 Ways To Sell Your Product Faster',
            'The Secrets Of Rich And Famous Writers',
            'Imagine Losing 20 Pounds In 14 Days!',
            'Are You Still Using That Slow, Old Typewriter?',
            'A Skin Cream That?s Proven To Work',
            'A Skin Cream That\'s Proven To Work',
            '10 Reasons To Start Your Own, Profitable Website!',
            'Simple Ways To Reduce Your Unwanted Wrinkles!',
            'Apple iMac with Retina 5K display review',
            '10,000 Web Site Visitors In One Month:Guaranteed',
            'Unlock The Secrets Of Selling High Ticket Items',
            '4 Expert Tips On How To Choose The Right Men?s Wallet',
            '4 Expert Tips On How To Choose The Right Men\'s Wallet',
            'Sexy Clutches: How to Buy & Wear a Designer Clutch Bag',
            'Sexy Clutches: How to Buy &amp; Wear a Designer Clutch Bag',
            'Hello world!',
        ];

        $demoPostIds = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 179];

        $posts = Post::query()
            ->whereIn('id', $demoPostIds)
            ->orWhereIn('name', $demoTitles)
            ->orWhere('created_at', '<', '2026-01-01')
            ->get();

        $count = $posts->count();
        $this->line("Found <comment>{$count}</comment> demo post(s) to remove.");

        foreach ($posts as $post) {
            $this->line("  - Removing Post #{$post->id}: <comment>{$post->name}</comment>");
            if (! $dryRun) {
                // Remove slugs
                Slug::query()
                    ->where('reference_type', Post::class)
                    ->where('reference_id', $post->id)
                    ->delete();

                // Detach categories & tags if relationships exist
                if (method_exists($post, 'categories')) {
                    $post->categories()->detach();
                }
                if (method_exists($post, 'tags')) {
                    $post->tags()->detach();
                }

                $post->delete();
            }
        }
    }

    /**
     * Purge demo layout pages (blog-grid-layout, home-2, home-3, duplicate contact)
     */
    protected function cleanupDemoPages(bool $dryRun): void
    {
        $this->line("\n--- Step 2: Cleaning Up Demo Layout Pages ---");

        $demoPageNames = [
            'Blog Grid layout',
            'Blog Big layout',
            'Blog List layout',
            'Home 2',
            'Home 3',
            'Sample Page',
            'Elementor #1046',
            'Contact', // Keep "Contact Us" (id 70), delete older duplicate "Contact" (id 5)
        ];

        // Specific IDs for safety:
        // ID 2 = Home 2, ID 3 = Home 3, ID 5 = Contact (duplicate), ID 7, 8, 9 = blog layouts, ID 41 = Sample Page, ID 68 = Elementor #1046
        $demoPageIds = [2, 3, 5, 7, 8, 9, 41, 68];

        $pages = Page::query()
            ->whereIn('id', $demoPageIds)
            ->orWhereIn('name', $demoPageNames)
            ->get();

        $count = $pages->count();
        $this->line("Found <comment>{$count}</comment> demo page(s) to remove.");

        foreach ($pages as $page) {
            // Safety guard: NEVER delete primary Home, About Us, Privacy Policy, Terms, Disclaimer, or Contact Us (id 70)
            if (in_array(strtolower($page->name), ['home', 'about us', 'privacy policy', 'terms & conditions', 'terms &amp; conditions', 'disclaimer', 'contact us']) && ! in_array($page->id, $demoPageIds)) {
                continue;
            }

            $this->line("  - Removing Page #{$page->id}: <comment>{$page->name}</comment>");
            if (! $dryRun) {
                Slug::query()
                    ->where('reference_type', Page::class)
                    ->where('reference_id', $page->id)
                    ->delete();

                $page->delete();
            }
        }
    }

    /**
     * Purge unrelated demo categories (Travel, Food, Hotels, Healthy, Lifestyle, Games)
     */
    protected function cleanupDemoCategories(bool $dryRun): void
    {
        $this->line("\n--- Step 3: Cleaning Up Unrelated Demo Categories ---");

        $demoCategoryNames = [
            'Travel',
            'Guides',
            'Destination',
            'Food',
            'Foods',
            'Hotels',
            'Review',
            'Healthy',
            'Lifestyle',
            'Life Style',
            'Games',
            'World',
        ];

        $categories = Category::query()
            ->whereIn('name', $demoCategoryNames)
            ->get();

        $count = $categories->count();
        $this->line("Found <comment>{$count}</comment> demo category/categories to remove.");

        foreach ($categories as $cat) {
            $this->line("  - Removing Category #{$cat->id}: <comment>{$cat->name}</comment>");
            if (! $dryRun) {
                Slug::query()
                    ->where('reference_type', Category::class)
                    ->where('reference_id', $cat->id)
                    ->delete();

                // Detach from posts
                if (method_exists($cat, 'posts')) {
                    $cat->posts()->detach();
                }

                $cat->delete();
            }
        }
    }

    /**
     * Deduplicate cloned job posts (keeping the latest one, deleting earlier copies)
     */
    protected function deduplicateJobPosts(bool $dryRun): void
    {
        $this->line("\n--- Step 4: Deduplicating Cloned Job Posts ---");

        // Find post names that exist more than once
        $duplicates = Post::query()
            ->select('name', DB::raw('COUNT(*) as total'))
            ->groupBy('name')
            ->having('total', '>', 1)
            ->get();

        $totalDuplicateGroups = $duplicates->count();
        $this->line("Found <comment>{$totalDuplicateGroups}</comment> set(s) of duplicate job posts.");

        foreach ($duplicates as $dup) {
            $allCopies = Post::query()
                ->where('name', $dup->name)
                ->orderBy('id', 'desc')
                ->get();

            // Keep the first (newest ID), delete the rest
            $keep = $allCopies->first();
            $toDelete = $allCopies->slice(1);

            $this->line("  - Post: <comment>{$dup->name}</comment> (Keeping ID #{$keep->id}, removing " . $toDelete->count() . " duplicate clone(s))");

            if (! $dryRun) {
                foreach ($toDelete as $clone) {
                    Slug::query()
                        ->where('reference_type', Post::class)
                        ->where('reference_id', $clone->id)
                        ->delete();

                    if (method_exists($clone, 'categories')) {
                        $clone->categories()->detach();
                    }
                    if (method_exists($clone, 'tags')) {
                        $clone->tags()->detach();
                    }

                    $clone->delete();
                }
            }
        }
    }
}
