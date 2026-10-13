<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * One row per attempt to push a post onto this (live) site.
 *
 * This is the data behind the admin dashboard at {admin}/post-sync: it answers
 * "how many syncs happened, which succeeded, which failed and why".
 *
 * Writes go through record(), which swallows its own failures — an audit row
 * must never be able to break the sync it is describing.
 */
class PostSyncLog extends Model
{
    public const ACTION_CREATED = 'created';

    public const ACTION_UPDATED = 'updated';

    public const ACTION_REJECTED = 'rejected';

    public const ACTION_FAILED = 'failed';

    protected $table = 'post_sync_logs';

    protected $fillable = [
        'local_post_id',
        'post_id',
        'title',
        'slug',
        'action',
        'http_status',
        'success',
        'message',
        'token_name',
        'duration_ms',
    ];

    protected $casts = [
        'local_post_id' => 'integer',
        'post_id' => 'integer',
        'http_status' => 'integer',
        'success' => 'boolean',
        'duration_ms' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Write an audit row, never throwing.
     *
     * @param  array<string, mixed>  $attributes
     */
    public static function record(array $attributes): ?self
    {
        try {
            return static::query()->create($attributes);
        } catch (Throwable $exception) {
            Log::error('Could not write post sync log: ' . $exception->getMessage(), [
                'exception' => $exception,
                'attributes' => $attributes,
            ]);

            return null;
        }
    }

    public function scopeRecent($query, int $limit = 200)
    {
        return $query->orderByDesc('created_at')->orderByDesc('id')->limit($limit);
    }
}
