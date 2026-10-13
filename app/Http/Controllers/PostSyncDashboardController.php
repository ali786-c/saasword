<?php

namespace App\Http\Controllers;

use App\Models\PostSyncLog;
use Illuminate\Http\Request;

/**
 * Admin dashboard for the local -> live post sync: {admin}/post-sync
 *
 * Shows the numbers an operator actually asks for — how many pushes landed,
 * how many were created vs updated, how many failed and why, plus the recent
 * history with the reason behind each failure.
 */
class PostSyncDashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = (string) $request->query('status', 'all');

        $base = PostSyncLog::query();

        if ($filter === 'success') {
            $base->where('success', true);
        } elseif ($filter === 'failed') {
            $base->where('success', false);
        }

        $today = PostSyncLog::query()
            ->whereDate('created_at', today())
            ->get([
                'action',
                'success',
            ]);

        $stats = [
            'total' => (int) PostSyncLog::query()->count(),
            'today' => $today->count(),
            'today_created' => $today->filter(fn ($log) => $log->action === PostSyncLog::ACTION_CREATED)->count(),
            'today_updated' => $today->filter(fn ($log) => $log->action === PostSyncLog::ACTION_UPDATED)->count(),
            'today_failed' => $today->filter(fn ($log) => ! $log->success)->count(),
            'failed' => (int) PostSyncLog::query()->where('success', false)->count(),
            'created' => (int) PostSyncLog::query()->where('action', PostSyncLog::ACTION_CREATED)->count(),
            'updated' => (int) PostSyncLog::query()->where('action', PostSyncLog::ACTION_UPDATED)->count(),
            'last_sync_at' => PostSyncLog::query()->max('created_at'),
            'avg_duration_ms' => (int) round(
                (float) PostSyncLog::query()->where('success', true)->avg('duration_ms')
            ),
        ];

        // Recent failures grouped by message, so a systemic problem
        // (expired token, bad payload) is visible at a glance.
        $failureReasons = PostSyncLog::query()
            ->where('success', false)
            ->selectRaw('message, COUNT(*) as occurrences')
            ->groupBy('message')
            ->orderByDesc('occurrences')
            ->limit(5)
            ->get();

        $logs = $base
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        return view('admin.post-sync.index', [
            'stats' => $stats,
            'logs' => $logs,
            'failureReasons' => $failureReasons,
            'filter' => $filter,
        ]);
    }
}
