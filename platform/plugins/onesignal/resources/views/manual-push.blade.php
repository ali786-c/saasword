@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0 text-white"><i class="ti ti-send me-1"></i> Send Manual Push Notification</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('onesignal.send-manual') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label font-weight-bold">Notification Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" required placeholder="e.g. New Job Alert: FPSC 350+ Vacancies Announced!">
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label font-weight-bold">Notification Message <span class="text-danger">*</span></label>
                            <textarea name="message" id="message" rows="3" class="form-control" required placeholder="e.g. Federal Board of Revenue announces 350+ Inspector Inland Revenue jobs. Tap to check eligibility and apply online."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="segment" class="form-label font-weight-bold">Target Segment</label>
                            <select name="segment" id="segment" class="form-select">
                                @foreach($segments as $seg)
                                    <option value="{{ $seg }}">{{ $seg }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="url" class="form-label font-weight-bold">Launch URL (Optional)</label>
                            <input type="url" name="url" id="url" class="form-control" placeholder="https://careerinpak.com/fbr-jobs-2026">
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label font-weight-bold">Featured Image URL (Optional)</label>
                            <input type="url" name="image" id="image" class="form-control" placeholder="https://careerinpak.com/storage/job-ad.jpg">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-send me-1"></i> Send Push Blast to Subscribers
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-white"><i class="ti ti-history me-1"></i> Sent Push Notifications History</h5>
                    <a href="{{ route('onesignal.manual-push') }}" class="btn btn-sm btn-outline-light"><i class="ti ti-refresh"></i> Refresh</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Title & Message</th>
                                    <th>Delivered</th>
                                    <th>Failed</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notifications as $notif)
                                    <tr>
                                        <td>
                                            <div class="font-weight-bold text-dark">
                                                {{ $notif['headings']['en'] ?? 'No Title' }}
                                            </div>
                                            <div class="small text-muted">
                                                {{ Str::limit($notif['contents']['en'] ?? '', 80) }}
                                            </div>
                                            @if(!empty($notif['url']))
                                                <a href="{{ $notif['url'] }}" target="_blank" class="small text-primary d-block mt-1">
                                                    <i class="ti ti-link"></i> {{ Str::limit($notif['url'], 40) }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-success">
                                                {{ number_format($notif['successful'] ?? 0) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger">
                                                {{ number_format($notif['failed'] ?? 0) }}
                                            </span>
                                        </td>
                                        <td class="small text-muted">
                                            @if(!empty($notif['completed_at']))
                                                {{ \Carbon\Carbon::createFromTimestamp($notif['completed_at'])->diffForHumans() }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            No sent notifications history found or API credentials not set.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
