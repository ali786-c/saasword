<?php

namespace App\Console\Commands;

use App\Services\PostSyncService;
use Botble\Blog\Models\Post;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\TransferException;
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
                            {--draft : Also pull an ALREADY-PUBLISHED live post back to draft for review (new posts are always drafts)}
                            {--url= : Override SYNC_LIVE_URL}
                            {--token= : Override SYNC_LIVE_TOKEN}';

    protected $description = 'Push a local post to the live site; new posts always arrive there as drafts for admin review';

    public function handle(PostSyncService $sync, Client $client): int
    {
        $post = $this->resolvePost($sync, (string) $this->argument('post'));

        if (! $post) {
            return self::FAILURE;
        }

        $payload = $sync->payloadFor($post);

        if ($this->option('draft')) {
            // Request a review hold on an existing live post. The receiver
            // only ever honours this in the draft direction — it can never
            // publish anything, so it is safe to let the sender ask for it.
            $payload['force_draft'] = true;
        }

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
        } catch (TransferException $exception) {
            // Base class for ConnectException, RequestException (SSL/network)
            // and everything else Guzzle throws. Catching only ConnectException
            // let SSL failures escape as a raw stack trace.
            $this->error('Could not reach the live site: ' . $exception->getMessage());

            if (str_contains($exception->getMessage(), 'cURL error 60')) {
                $this->newLine();
                $this->line('  PHP could not verify the SSL certificate because it has no CA bundle.');
                $this->line('  Fix (pick one):');
                $this->line('    1. Set SYNC_CA_BUNDLE in .env to a ca-bundle.crt / cacert.pem path, or');
                $this->line('    2. Set curl.cainfo in php.ini to that same file.');
                $this->line('  (Git for Windows ships one at C:\Program Files\Git\usr\ssl\certs\ca-bundle.crt)');
            }

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
