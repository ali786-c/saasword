<?php

namespace App\Console\Commands;

use App\Services\Wp\ContentSanitizer;
use App\Services\Wp\MediaDownloader;
use App\Services\Wp\WpClient;
use App\Services\Wp\WpImporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class WpMigrateCommand extends Command
{
    protected $signature = 'wp:migrate
                            {--url= : Base URL of the source WordPress site (e.g. https://example.com)}
                            {--publish : Publish imported content immediately (default: import as drafts)}
                            {--without-media : Skip downloading images (default: images are downloaded)}
                            {--posts-only : Skip pages, import posts only}
                            {--pages-only : Skip posts, import pages only}
                            {--no-terms : Skip importing categories and tags}';

    protected $description = 'Migrate posts/pages/categories/tags (with images + SEO meta) from a WordPress site via its REST API';

    public function handle(): int
    {
        $url = (string) $this->option('url');

        if (! $url || ! preg_match('#^https?://#i', $url)) {
            $this->components->error('Please provide a valid --url, e.g. --url=https://your-old-site.com');

            return self::FAILURE;
        }

        $withMedia = ! $this->option('without-media');
        $publish = (bool) $this->option('publish');

        $this->components->info(sprintf(
            'Migrating from %s (media: %s, status: %s)',
            $url,
            $withMedia ? 'yes' : 'no',
            $publish ? 'PUBLISHED' : 'drafts'
        ));

        $client = new WpClient($url);
        $importer = new WpImporter(
            $client,
            new ContentSanitizer(),
            new MediaDownloader('wp-import')
        );

        // ---------- 1. Terms (posts attach to these) ----------
        if (! $this->option('no-terms')) {
            $counts = $importer->importTerms();

            $this->components->twoColumnDetail('Categories imported', (string) $counts['categories']);
            $this->components->twoColumnDetail('Tags imported', (string) $counts['tags']);
        }

        // ---------- 2. Posts ----------
        if (! $this->option('pages-only')) {
            $this->importCollection($importer, 'posts', fn () => $client->posts(), [
                'publish' => $publish,
                'with_media' => $withMedia,
                'type' => 'post',
            ]);
        }

        // ---------- 3. Pages ----------
        if (! $this->option('posts-only')) {
            $this->importCollection($importer, 'pages', fn () => $client->pages(), [
                'publish' => $publish,
                'with_media' => $withMedia,
                'type' => 'page',
            ]);
        }

        $this->components->info('Migration finished. Review drafts in the admin panel before publishing.');

        return self::SUCCESS;
    }

    protected function importCollection(WpImporter $importer, string $label, callable $fetch, array $options): void
    {
        $this->components->info("Fetching {$label}...");

        $items = $fetch();

        if ($items->isEmpty()) {
            $this->components->twoColumnDetail(ucfirst($label), 'none found');

            return;
        }

        $this->components->twoColumnDetail(ucfirst($label).' found', (string) $items->count());

        $progress = $this->output->createProgressBar($items->count());
        $progress->start();

        $totals = ['imported' => 0, 'skipped' => 0, 'failed' => 0];
        $errors = [];

        foreach ($items->chunk(25) as $chunk) {
            $result = $importer->importPosts($chunk, $options);

            foreach ($totals as $key => $value) {
                $totals[$key] = $value + ($result[$key] ?? 0);
            }

            $errors = array_merge($errors, $result['errors'] ?? []);

            $progress->advance($chunk->count());
        }

        $progress->finish();
        $this->newLine();

        $this->components->twoColumnDetail(ucfirst($label).' imported', (string) $totals['imported']);
        $this->components->twoColumnDetail(ucfirst($label).' skipped (already imported)', (string) $totals['skipped']);
        $this->components->twoColumnDetail(ucfirst($label).' failed', (string) $totals['failed']);

        foreach (array_slice($errors, 0, 5) as $error) {
            $this->components->warn(' - '.$error);
        }

        if ($totals['failed']) {
            Log::error('[wp:migrate] failures', ['errors' => $errors]);
            $this->components->warn('Re-run the same command to retry only the failures (mapping table makes it resume-safe).');
        }
    }
}
