<?php

namespace App\Console\Commands;

use Botble\ACL\Models\User;
use Illuminate\Console\Command;

/**
 * Issues the Personal Access Token that `post:push` sends as a Bearer header.
 *
 * Run this on the LIVE site, copy the printed token into the LOCAL .env:
 *
 *   SYNC_LIVE_URL=https://careerinpak.com
 *   SYNC_LIVE_TOKEN=1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
 *
 * The token is scoped to the `post-sync` ability, so it can do nothing except
 * call the sync endpoint.
 */
class GenerateSyncTokenCommand extends Command
{
    protected $signature = 'post:sync-token
                            {--user= : ID or email of the user that owns the token (defaults to the first user)}
                            {--revoke : Revoke previously issued live-sync tokens first}';

    protected $description = 'Issue (or rotate) the API token used by post:push to sync posts to the live site';

    public const ABILITY = 'post-sync';

    public const TOKEN_NAME = 'live-sync';

    public function handle(): int
    {
        $user = $this->resolveUser();

        if (! $user) {
            return self::FAILURE;
        }

        if ($this->option('revoke')) {
            $revoked = $user->tokens()->where('name', self::TOKEN_NAME)->delete();
            $this->info("Revoked {$revoked} existing live-sync token(s).");
        }

        $token = $user->createToken(self::TOKEN_NAME, [self::ABILITY])->plainTextToken;

        $this->newLine();
        $this->info('Token issued for user: ' . $user->email);
        $this->newLine();
        $this->line('Add these two lines to the LOCAL machine\'s .env:');
        $this->newLine();
        $this->line('  SYNC_LIVE_URL=https://your-live-domain.com');
        $this->line('  SYNC_LIVE_TOKEN=' . $token);
        $this->newLine();
        $this->comment('Keep this token secret. Rotate it with: php artisan post:sync-token --revoke');

        return self::SUCCESS;
    }

    protected function resolveUser(): ?User
    {
        if (! class_exists(User::class)) {
            $this->error('Botble ACL User model not found.');

            return null;
        }

        $reference = (string) $this->option('user');

        $user = $reference === ''
            ? User::query()->orderBy('id')->first()
            : (ctype_digit($reference)
                ? User::query()->find((int) $reference)
                : User::query()->where('email', $reference)->first());

        if (! $user) {
            $this->error($reference === ''
                ? 'No users found in the database.'
                : "No user found for [{$reference}].");

            return null;
        }

        return $user;
    }
}
