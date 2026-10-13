<?php

namespace App\Http\Controllers;

use App\Console\Commands\GenerateSyncTokenCommand;
use App\Models\PostSyncLog;
use App\Services\PostSyncService;
use Botble\Blog\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

/**
 * Receiver side of the local -> live post sync.
 *
 * Route is registered in PostSyncServiceProvider with `auth:sanctum` + `throttle`
 * only — deliberately NOT inside the `api` middleware group, because that group
 * carries Botble's ApiEnabledMiddleware, which 503s while the public "API
 * enabled" admin setting is off (its default value).
 *
 * Everything about this endpoint is fail-closed: unauthenticated is 401,
 * invalid payload is 422, and PostSyncService only ever writes draft for a new
 * post — so a bad sender can never publish straight to the live site.
 *
 * Every outcome is also written to post_sync_logs, which powers the admin
 * dashboard at {admin}/post-sync.
 */
class PostSyncController extends Controller
{
    public function store(Request $request, PostSyncService $sync): JsonResponse
    {
        $startedAt = microtime(true);
        $user = $request->user();

        // Scope check: the token issued by `php artisan post:sync-token` carries
        // the `post-sync` ability. Any other token — including a session user —
        // is refused, so a token minted for another purpose cannot write posts.
        if (! $user || ! $user->tokenCan(GenerateSyncTokenCommand::ABILITY)) {
            return $this->respond(
                $request,
                $startedAt,
                403,
                PostSyncLog::ACTION_REJECTED,
                'Token is not allowed to sync posts (missing post-sync ability).'
            );
        }

        $validator = Validator::make($request->all(), [
            'local_post_id' => ['required', 'integer', 'min:1'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'description' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string', 'max:500000'],
            'category' => ['nullable', 'string', 'max:191'],
            'is_featured' => ['nullable', 'boolean'],
            'views' => ['nullable', 'integer', 'min:0', 'max:100000000'],
            // Can only force a post TO draft for review, never to published.
            'force_draft' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $this->respond(
                $request,
                $startedAt,
                422,
                PostSyncLog::ACTION_REJECTED,
                Str::limit(implode(' ', $validator->errors()->all()), 480),
                null,
                ['errors' => $validator->errors()]
            );
        }

        try {
            $result = $sync->apply($validator->validated(), (int) $user->getKey());
        } catch (Throwable $exception) {
            Log::error('Post sync failed: ' . $exception->getMessage(), [
                'exception' => $exception,
                'local_post_id' => $request->input('local_post_id'),
            ]);

            return $this->respond(
                $request,
                $startedAt,
                500,
                PostSyncLog::ACTION_FAILED,
                Str::limit($exception->getMessage(), 480)
            );
        }

        $post = $result['post'];

        return $this->respond(
            $request,
            $startedAt,
            $result['created'] ? 201 : 200,
            $result['created'] ? PostSyncLog::ACTION_CREATED : PostSyncLog::ACTION_UPDATED,
            $result['created'] ? 'Stored as a draft for admin review.' : 'Existing post updated.',
            $post
        );
    }

    /**
     * Render the JSON reply AND write the matching audit row.
     *
     * @param  array<string, mixed>  $extra  extra JSON keys for the response body
     */
    protected function respond(
        Request $request,
        float $startedAt,
        int $status,
        string $action,
        string $message,
        ?Post $post = null,
        array $extra = []
    ): JsonResponse {
        $slug = (string) $request->input('slug', '');
        $title = $post ? (string) $post->name : (string) $request->input('name', '');

        PostSyncLog::record([
            'local_post_id' => is_numeric($request->input('local_post_id'))
                ? (int) $request->input('local_post_id')
                : null,
            'post_id' => $post?->getKey(),
            'title' => $title !== '' ? Str::limit($title, 250) : null,
            'slug' => $slug !== '' ? Str::limit($slug, 250) : null,
            'action' => $action,
            'http_status' => $status,
            'success' => $status >= 200 && $status < 300,
            'message' => Str::limit($message, 480),
            'token_name' => $this->tokenName($request),
            'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
        ]);

        return response()->json(array_merge([
            'ok' => $status >= 200 && $status < 300,
            'message' => $message,
        ], $extra, $post ? [
            'created' => $action === PostSyncLog::ACTION_CREATED,
            'post_id' => (int) $post->getKey(),
            'slug' => $slug,
            'status' => $post->getRawOriginal('status'),
            'url' => $post->url,
        ] : []), $status);
    }

    /**
     * Which key performed the push. Never allowed to break the response —
     * test doubles for the token have no `name` property.
     */
    protected function tokenName(Request $request): ?string
    {
        try {
            $token = $request->user()?->currentAccessToken();

            if ($token && isset($token->name)) {
                return Str::limit((string) $token->name, 100, '');
            }
        } catch (Throwable) {
            // Intentionally ignored: audit context is nice-to-have only.
        }

        return null;
    }
}
