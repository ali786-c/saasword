@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="container-xl">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1">Post Sync Dashboard</h4>
                <p class="text-muted mb-0 small">
                    Local → live pushes. Posts always arrive as drafts; publish them from Blog → Posts.
                </p>
            </div>
            <div class="btn-group">
                <a href="{{ route('post-sync.index') }}"
                   class="btn btn-sm {{ $filter === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">All</a>
                <a href="{{ route('post-sync.index', ['status' => 'success']) }}"
                   class="btn btn-sm {{ $filter === 'success' ? 'btn-success' : 'btn-outline-secondary' }}">Success</a>
                <a href="{{ route('post-sync.index', ['status' => 'failed']) }}"
                   class="btn btn-sm {{ $filter === 'failed' ? 'btn-danger' : 'btn-outline-secondary' }}">Failed</a>
            </div>
        </div>

        {{-- Stat cards --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-2">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">Total pushes</div>
                        <div class="fs-3 fw-bold">{{ number_format($stats['total']) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">Today</div>
                        <div class="fs-3 fw-bold">{{ number_format($stats['today']) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="card h-100 shadow-sm border-success">
                    <div class="card-body">
                        <div class="text-muted small">Created (all time)</div>
                        <div class="fs-3 fw-bold text-success">{{ number_format($stats['created']) }}</div>
                        <div class="small text-muted">{{ number_format($stats['today_created']) }} today</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">Updated (all time)</div>
                        <div class="fs-3 fw-bold text-primary">{{ number_format($stats['updated']) }}</div>
                        <div class="small text-muted">{{ number_format($stats['today_updated']) }} today</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="card h-100 shadow-sm {{ $stats['failed'] > 0 ? 'border-danger' : '' }}">
                    <div class="card-body">
                        <div class="text-muted small">Failed</div>
                        <div class="fs-3 fw-bold {{ $stats['failed'] > 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($stats['failed']) }}
                        </div>
                        <div class="small text-muted">{{ number_format($stats['today_failed']) }} today</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">Avg. duration</div>
                        <div class="fs-3 fw-bold">{{ number_format($stats['avg_duration_ms']) }}<span class="fs-6">ms</span></div>
                        <div class="small text-muted">
                            {{ $stats['last_sync_at'] ? \Illuminate\Support\Carbon::parse($stats['last_sync_at'])->diffForHumans() : 'never' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($failureReasons->count())
            <div class="alert alert-danger">
                <strong>Why pushes are failing</strong>
                <ul class="mb-0 mt-2">
                    @foreach($failureReasons as $reason)
                        <li>
                            <span class="badge bg-danger">{{ $reason->occurrences }}</span>
                            {{ $reason->message ?: 'No message recorded' }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Recent sync attempts</span>
                <span class="text-muted small">{{ $logs->total() }} matching</span>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>When</th>
                            <th>Post</th>
                            <th>Slug</th>
                            <th>Action</th>
                            <th>HTTP</th>
                            <th>Duration</th>
                            <th>Key</th>
                            <th>Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="text-nowrap small">
                                    {{ $log->created_at?->format('Y-m-d H:i:s') }}
                                </td>
                                <td>
                                    {{ $log->title ?: '—' }}
                                    @if($log->post_id)
                                        <div class="small text-muted">live #{{ $log->post_id }}</div>
                                    @endif
                                </td>
                                <td class="small text-muted" style="max-width: 220px; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $log->slug ?: '—' }}
                                </td>
                                <td>
                                    @if($log->action === \App\Models\PostSyncLog::ACTION_CREATED)
                                        <span class="badge bg-success">created</span>
                                    @elseif($log->action === \App\Models\PostSyncLog::ACTION_UPDATED)
                                        <span class="badge bg-primary">updated</span>
                                    @elseif($log->action === \App\Models\PostSyncLog::ACTION_REJECTED)
                                        <span class="badge bg-warning text-dark">rejected</span>
                                    @else
                                        <span class="badge bg-danger">failed</span>
                                    @endif
                                </td>
                                <td>{{ $log->http_status }}</td>
                                <td class="text-nowrap">{{ $log->duration_ms }} ms</td>
                                <td class="small text-muted">{{ $log->token_name ?: '—' }}</td>
                                <td class="small" style="max-width: 320px;">
                                    {{ $log->message ?: '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No sync attempts recorded yet. Push one from your local machine with
                                    <code>php artisan post:push &lt;slug&gt;</code>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection
