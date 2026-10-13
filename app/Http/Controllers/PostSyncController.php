<?php

namespace App\Http\Controllers;

use App\Console\Commands\GenerateSyncTokenCommand;
use App\Services\PostSyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

/**
 * Receiver side of the local -> live post sync.
 *
 * Route is registered in AppServiceProvider with `auth:sanctum` + `throttle`
 * only — deliberately NOT inside the `api` middleware group, because that group
 * carries Botble's ApiEnabledMiddleware, which 503s while the public "API
 * enabled" admin setting is off (its default value).
 *
 * Everything about this endpoint is fail-closed: unauthenticated is 401,
 * invalid payload is 422, and PostSyncService forces status=draft so a bad
 * sender can never publish straight to the live site.
 */
class PostSyncController extends Controller
{
    public function store(Request $request, PostSyncService $sync): JsonResponse
    {
        $user = $request->user();

        // Scope check: the token issued by `php artisan post:sync-token` carries
        // the `post-sync` ability. Any other token — including a session user —
        // is refused, so a token minted for another purpose cannot write posts.
        if (! $user || ! $user->tokenCan(GenerateSyncTokenCommand::ABILITY)) {
            return response()->json([
                'ok' => false,
                'message' => 'This token is not allowed to sync posts. Issue one with: php artisan post:sync-token',
            ], 403);
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
            return response()->json([
                'ok' => false,
                'message' => 'Invalid sync payload.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $result = $sync->apply($validator->validated(), (int) $user->getKey());
        } catch (Throwable $exception) {
            Log::error('Post sync failed: ' . $exception->getMessage(), [
                'exception' => $exception,
                'local_post_id' => $request->input('local_post_id'),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Post sync failed on the receiving site.',
            ], 500);
        }

        $post = $result['post'];

        return response()->json([
            'ok' => true,
            'created' => $result['created'],
            'post_id' => (int) $post->getKey(),
            'slug' => (string) ($post->slug ?? $request->input('slug')),
            'status' => 'draft',
            'url' => $post->url,
        ], $result['created'] ? 201 : 200);
    }
}
