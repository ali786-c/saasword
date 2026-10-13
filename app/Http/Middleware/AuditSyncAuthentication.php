<?php

namespace App\Http\Middleware;

use App\Models\PostSyncLog;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

/**
 * Same behaviour as the stock `auth` middleware, plus an audit row when the
 * sync endpoint rejects a request for missing/invalid credentials.
 *
 * Without this, a 401 would happen before PostSyncController ran and the
 * dashboard would never show the most important failure of all: a wrong or
 * expired SYNC_LIVE_TOKEN.
 *
 * Only the numeric post id and the (already strict) slug are read from an
 * unauthenticated body — free text from an unknown caller is never stored.
 */
class AuditSyncAuthentication extends Authenticate
{
    protected function unauthenticated($request, array $guards)
    {
        if ($request instanceof Request
            && $request->isMethod('POST')
            && $request->is('api/v1/sync/posts')) {
            $slug = (string) $request->input('slug', '');
            $slug = preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug) ? $slug : null;

            PostSyncLog::record([
                'local_post_id' => is_numeric($request->input('local_post_id'))
                    ? (int) $request->input('local_post_id')
                    : null,
                'slug' => $slug ? Str::limit($slug, 250) : null,
                'action' => PostSyncLog::ACTION_REJECTED,
                'http_status' => 401,
                'success' => false,
                'message' => 'Missing or invalid bearer token.',
            ]);
        }

        parent::unauthenticated($request, $guards);
    }
}
