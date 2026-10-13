<?php

namespace App\Console\Commands;

use App\Services\PostSyncService;
use Botble\Blog\Models\Post;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Console\Command;

/**
 * Sender side of the local -> live post sync.
 *
 *   php artisan post:push 142          # by local post ID
 *   php artisan post:push my-post-slug # by slug
 *   php artisan post:push 142 --dry-run
 *
 * The live site always stores the result as a DRAFT — publishing stays an
 * admin action (guide.md, "Strict Live Draft Rule").
 */
class PushPostToLiveCommand extends Command
{
    protected $signature = 'post:push
                            {post : Local post ID or slug to push}
                            {--dry-run : Print the JSON payload without sending it}
                            {--url= : Override SYNC_LIVE_URL}
                            {--token= : Override SYNC_LIVE_TOKEN}';

    protected $description = 'Push a local post to the live site, where it is stored as a draft for admin review';

    public function handle(PostSyncService $sync, Client $client): int
    {
        $post = $this->resolvePost($sync, (string) $this->argument('post'));

        if (! $post) {
            return self::FAILURE;
        }

        $payload = $sync->payloadFor($post);

        if ($this->option('dry-run')) {
            $this->line(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            $this->newLine();
            $this->info('Dry run — nothing was sent.');

            return self::SUCCESS;
        }

        $base = rtrim((string) ($this->option('url') ?: config('sync.url')), '/');
        $token = (string) ($this->option('token') ?: config('sync.token'));

        if ($base === '' || $token === '') {
            $this->error('Sync is not configured. Set SYNC_LIVE_URL and SYNC_LIVE_TOKEN in .env');
            $this->line('  (issue the token on the LIVE site with: php artisan post:sync-token)');

            return self::FAILURE;
        }

        $endpoint = $base . '/api/v1/sync/posts';
        $this->line(sprintf('Pushing <info>%s</info> (local #%d) to %s', $post->name, $post->getKey(), $endpoint));

        try {
            $response = $client->post($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
                'json' => $payload,
            ]);
        } catch (BadResponseException $exception) {
            $status = $exception->getResponse() ? $exception->getResponse()->getStatusCode() : 'unknown';
            $body = $exception->getResponse() ? (string) $exception->getResponse()->getBody() : '';

            $this->error("Live site rejected the sync (HTTP {$status}).");
            $this->line($body !== '' ? $body : $exception->getMessage());

            if ((string) $status === '401') {
                $this->line('  -> Re-issue the token on live: <info>php artisan post:sync-token</info>');
            }

            return self::FAILURE;
        } catch (ConnectException $exception) {
            $this->error('Could not reach the live site: ' . $exception->getMessage());

            return self::FAILURE;
        }

        $data = json_decode((string) $response->getBody(), true);

        if (! is_array($data) || empty($data['ok'])) {
            $this->error('Unexpected response from the live site:');
            $this->line((string) $response->getBody());

            return self::FAILURE;
        }

        $this->newLine();
        $this->info($data['created'] ? 'Created on live as a DRAFT.' : 'Updated existing draft on live.');
        $this->line('  Live ID : ' . ($data['post_id'] ?? '?'));
        $this->line('  URL     : ' . ($data['url'] ?? '?'));
        $this->line('  Status  : ' . ($data['status'] ?? 'draft') . ' (publish it from the live admin panel)');

        return self::SUCCESS;
    }

    protected function resolvePost(PostSyncService $sync, string $reference): ?Post
    {
        if ($reference === '') {
            $this->error('Please provide a post ID or slug.');

            return null;
        }

        $post = ctype_digit($reference)
            ? Post::query()->find((int) $reference)
            : $sync->postForSlug($reference);

        if (! $post) {
            $this->error("No local post found for [{$reference}]. Pass a post ID or its slug.");

            return null;
        }

        return $post;
    }
}
