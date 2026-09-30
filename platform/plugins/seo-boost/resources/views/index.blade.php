@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="container-xl">
        <div class="card border border-secondary bg-transparent">
            <div class="card-header">
                <h4 class="card-title">{{ trans('plugins/seo-boost::seo-boost.name') }}</h4>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ trans('plugins/seo-boost::seo-boost.description') }}</p>

                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th class="w-25">{{ trans('plugins/seo-boost::seo-boost.status') }}</th>
                            <td>
                                <span class="badge bg-{{ $isEnabled ? 'success' : 'secondary' }}">
                                    {{ trans('plugins/seo-boost::seo-boost.' . ($isEnabled ? 'enabled' : 'disabled')) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ trans('plugins/seo-boost::seo-boost.api_key') }}</th>
                            <td><code>{{ $key }}</code></td>
                        </tr>
                        <tr>
                            <th>{{ trans('plugins/seo-boost::seo-boost.key_file_url') }}</th>
                            <td>
                                <a href="{{ $keyFileUrl }}" target="_blank" rel="noopener">{{ $keyFileUrl }}</a>
                            </td>
                        </tr>
                        @if ($latestLog)
                            <tr>
                                <th>{{ trans('plugins/seo-boost::seo-boost.last_submission') }}</th>
                                <td>
                                    <span class="badge bg-{{ $latestLog->is_success ? 'success' : 'danger' }}">
                                        {{ $latestLog->status_code }}
                                    </span>
                                    <code class="ms-2">{{ $latestLog->url }}</code>
                                    <span class="text-muted ms-2">{{ $latestLog->created_at->diffForHumans() }}</span>
                                    @if ($latestLog->message && ! $latestLog->is_success)
                                        <div class="text-muted small">{{ $latestLog->message }}</div>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
