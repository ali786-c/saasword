@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="container-xl">
        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card border border-secondary bg-transparent">
                    <div class="card-header">
                        <h4 class="card-title">{{ trans('plugins/seo-boost::seo-boost.key_card_title') }}</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">{{ trans('plugins/seo-boost::seo-boost.description') }}</p>

                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th class="w-40">{{ trans('plugins/seo-boost::seo-boost.status') }}</th>
                                    <td>
                                        <span class="badge bg-{{ $isEnabled ? 'success' : 'secondary' }}">
                                            {{ trans('plugins/seo-boost::seo-boost.' . ($isEnabled ? 'enabled' : 'disabled')) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('plugins/seo-boost::seo-boost.api_key') }}</th>
                                    <td><code class="seo-boost-key">{{ $key }}</code></td>
                                </tr>
                                <tr>
                                    <th>{{ trans('plugins/seo-boost::seo-boost.key_file_url') }}</th>
                                    <td class="text-break">
                                        <a href="{{ $keyFileUrl }}" target="_blank" rel="noopener">{{ $keyFileUrl }}</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <button type="button" class="btn btn-outline-secondary btn-sm seo-boost-copy-key" data-key="{{ $key }}">
                            <i class="ti ti-copy"></i> {{ trans('plugins/seo-boost::seo-boost.copy_key') }}
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm seo-boost-reset-key" data-url="{{ route('seo-boost.reset-key') }}">
                            <i class="ti ti-refresh"></i> {{ trans('plugins/seo-boost::seo-boost.reset_key') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border border-secondary bg-transparent">
                    <div class="card-header">
                        <h4 class="card-title">{{ trans('plugins/seo-boost::seo-boost.submit_urls') }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('seo-boost.submit') }}" method="post" class="seo-boost-submit-form" data-url="{{ route('seo-boost.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="seo-boost-urls">{{ trans('plugins/seo-boost::seo-boost.urls_label') }}</label>
                                <textarea
                                    class="form-control"
                                    name="urls"
                                    id="seo-boost-urls"
                                    rows="8"
                                    placeholder="{{ trans('plugins/seo-boost::seo-boost.urls_placeholder') }}"
                                ></textarea>
                                <div class="form-text">{{ trans('plugins/seo-boost::seo-boost.urls_help') }}</div>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ trans('plugins/seo-boost::seo-boost.submit_now') }}</button>
                            <a href="{{ route('seo-boost.logs') }}" class="btn btn-outline-secondary">
                                {{ trans('plugins/seo-boost::seo-boost.history_title') }}
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer')
    <script src="{{ asset('vendor/core/plugins/seo-boost/js/seo-boost.js') }}?v={{ get_cms_version() }}"></script>
@endpush
