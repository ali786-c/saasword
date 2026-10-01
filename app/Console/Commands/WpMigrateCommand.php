<?php

namespace App\Console\Commands;

use App\Services\Wp\ContentSanitizer;
use App\Services\Wp\LocalMediaCopier;
use App\Services\Wp\MediaDownloader;
use App\Services\Wp\WpClient;
use App\Services\Wp\WpDbClient;
use App\Services\Wp\WpImporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class WpMigrateCommand extends Command
{
    protected $signature = 'wp:migrate
                            {--url= : Base URL of a live WordPress site (REST API source)}
                            {--from-db : Import from a WordPress DATABASE instead of the REST API (works after the old site is offline)}
                            {--db-host=127.0.0.1 : WP database host}
                            {--db-port=3306 : WP database port}
                            {--db-name= : WP database name}
                            {--db-user=root : WP database user}
                            {--db-pass= : WP database password}
                            {--db-prefix=wp_ : WP table prefix}
                            {--uploads-path= : Path to a copy of the WP wp-content/uploads directory (images are copied from disk)}
                            {--wp-base-url= : Old site base URL (defaults to --url, used to match image URLs)}
                            {--statuses=publish : Comma-separated WP post statuses to import (e.g. publish,draft)}
                            {--limit= : Import at most N posts (from the NEWEST, in descending date order)}
                            {--publish : Publish imported content immediately (default: import as drafts)}
                            {--without-media : Skip importing images}
                            {--posts-only : Skip pages, import posts only}
                            {--pages-only : Skip posts, import pages only}
                            {--no-terms : Skip importing categories and tags}';

    protected $description = 'Migrate posts/pages/categories/tags (with images + SEO meta) from a WordPress site via its REST API or directly from its database';

    public function handle(): int
    {
        $fromDb = (bool) $this->option('from-db');
        $url = (string) $this->option('url');

        if ($fromDb) {
            return $this->handleFromDatabase();
        }

        if (! $url || ! preg_match('#^https?://#i', $url)) {
            $this->components->error('Please provide a valid --url, e.g. --url=https://your-old-site.com (or use --from-db).');

            return self::FAILURE;
        }

        $withMedia = ! $this->option('without-media');
        $publish = (bool) $this->option('publish');

        $this->components->info(sprintf(
            'Migrating from %s via REST API (media: %s, status: %s)',
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

    /**
     * Database source: reads straight from a WordPress MySQL database
     * (local dump or cPanel DB) — keeps working after the old site is down.
     */
    protected function handleFromDatabase(): int
    {
        $dbName = (string) $this->option('db-name');

        if (! $dbName) {
            $this->components->error('--db-name is required with --from-db, e.g. --from-db --db-name=old_wp_db');

            return self::FAILURE;
        }

        $wpBaseUrl = (string) ($this->option('wp-base-url') ?: $this->option('url') ?: '');

        $client = new WpDbClient(
            (string) $this->option('db-host'),
            (string) $this->option('db-port'),
            $dbName,
            (string) $this->option('db-user'),
            (string) $this->option('db-pass'),
            (string) $this->option('db-prefix'),
        );

        if (! $client->connected()) {
            $this->components->error('Could not connect to the WordPress database: '.$client->error());

            return self::FAILURE;
        }

        $withMedia = ! $this->option('without-media');
        $publish = (bool) $this->option('publish');
        $limit = (int) $this->option('limit') ?: null;

        if ($withMedia) {
            $uploadsPath = (string) $this->option('uploads-path');

            if (! $uploadsPath) {
                $this->components->warn('No --uploads-path given: images will be skipped (content URLs stay unchanged).');
                $withMedia = false;
            } elseif (! is_dir($uploadsPath)) {
                $this->components->error(sprintf('Uploads path not found: %s', $uploadsPath));

                return self::FAILURE;
            }
        }

        $media = $withMedia
            ? new LocalMediaCopier($uploadsPath, $wpBaseUrl, 'wp-import')
            : null;

        $statuses = collect(explode(',', (string) $this->option('statuses')))
            ->map(fn (string $s) => trim($s))
            ->filter()
            ->all();

        $this->components->info(sprintf(
            'Migrating from DATABASE %s@%s:%s (prefix: %s, media: %s, statuses: %s, limit: %s, status: %s)',
            $dbName,
            $this->option('db-host'),
            $this->option('db-port'),
            $this->option('db-prefix'),
            $media ? $media->uploadsPath() : 'no',
            implode(',', $statuses) ?: '-',
            $limit ?? 'all',
            $publish ? 'PUBLISHED' : 'drafts'
        ));

        if ($media) {
            $this->components->twoColumnDetail('Image files in uploads copy', (string) $media->countFiles());
        }

        $importer = new WpImporter(
            $client,
            new ContentSanitizer(),
            // MediaDownloader contract without a network: URL -> local copy.
            $media ?? new class implements \App\Services\Wp\MediaSource
            {
                public function download(string $url): ?string
                {
                    return null;
                }

                public function map(): array
                {
                    return [];
                }
            }
        );

        // ---------- 1. Terms (posts attach to these) ----------
        if (! $this->option('no-terms')) {
            $categories = $client->categories();
            $tags = $client->tags();

            $this->components->twoColumnDetail('Categories found', (string) $categories->count());
            $this->components->twoColumnDetail('Tags found', (string) $tags->count());

            $importedCategories = 0;
            $importedTags = 0;

            foreach ($categories as $term) {
                $importedCategories += (int) $importer->importCategory(is_array($term) ? $term : $term->toArray());
            }

            foreach ($tags as $term) {
                $importedTags += (int) $importer->importTag(is_array($term) ? $term : $term->toArray());
            }

            $this->components->twoColumnDetail('Categories imported', (string) $importedCategories);
            $this->components->twoColumnDetail('Tags imported', (string) $importedTags);
        }

        // ---------- 2. Posts ----------
        if (! $this->option('pages-only')) {
            $this->importDbCollection($importer, 'posts', fn () => $client->posts('post', $statuses, $limit), $publish, $withMedia);
        }

        // ---------- 3. Pages ----------
        if (! $this->option('posts-only')) {
            $this->importDbCollection($importer, 'pages', fn () => $client->posts('page', $statuses, $limit), $publish, $withMedia);
        }

        $this->components->info('Database migration finished. Review drafts in the admin panel before publishing.');

        return self::SUCCESS;
    }

    protected function importDbCollection(WpImporter $importer, string $label, callable $fetch, bool $publish, bool $withMedia): void
    {
        $this->components->info("Fetching {$label} from database...");

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
            $result = $importer->importPosts($chunk->all(), [
                'publish' => $publish,
                'with_media' => $withMedia,
                'type' => rtrim($label, 's'),
            ]);

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
