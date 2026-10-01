@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="max-width-1200">
        <form action="{{ route('seo-boost.settings.post') }}" method="post">
            @csrf

            <x-core-setting::section
                :title="trans('plugins/seo-boost::seo-boost.settings_name')"
                :description="trans('plugins/seo-boost::seo-boost.settings_description')"
            >
                <x-core-setting::checkbox
                    name="seo_boost_enabled"
                    :value="'1'"
                    :label="trans('plugins/seo-boost::seo-boost.auto_submit_enabled')"
                    :checked="setting('seo_boost_enabled', '1') == '1'"
                />

                <x-core-setting::form-group>
                    <label class="form-label">{{ trans('plugins/seo-boost::seo-boost.auto_submit_post_types') }} — IndexNow</label>
                    @foreach (['post' => trans('plugins/seo-boost::seo-boost.post_type_posts'), 'page' => trans('plugins/seo-boost::seo-boost.post_type_pages')] as $type => $label)
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="seo_boost_post_types[{{ $type }}]"
                                value="1"
                                id="seo-boost-type-{{ $type }}"
                                @checked(setting('seo_boost_post_types.' . $type, '1') == '1')
                            >
                            <label class="form-check-label" for="seo-boost-type-{{ $type }}">{{ $label }}</label>
                        </div>
                    @endforeach
                </x-core-setting::form-group>
            </x-core-setting::section>

            <x-core-setting::section
                :title="trans('plugins/seo-boost::seo-boost.google_section_title')"
                :description="trans('plugins/seo-boost::seo-boost.google_section_description')"
            >
                <x-core-setting::checkbox
                    name="seo_boost_google_enabled"
                    :value="'1'"
                    :label="trans('plugins/seo-boost::seo-boost.google_enabled_label')"
                    :checked="setting('seo_boost_google_enabled', '1') == '1' && $googleConfigured"
                />

                @if ($googleConfigured)
                    <div class="alert alert-success">
                        {{ trans('plugins/seo-boost::seo-boost.google_key_saved', ['email' => $googleAccount]) }}
                    </div>
                @endif

                <x-core-setting::form-group>
                    <label class="form-label" for="seo_boost_google_json_key">{{ trans('plugins/seo-boost::seo-boost.google_json_key_label') }}</label>
                    <textarea
                        class="form-control font-monospace"
                        name="seo_boost_google_json_key"
                        id="seo_boost_google_json_key"
                        rows="8"
                        placeholder='{"type": "service_account", "project_id": "...", "private_key": "...", "client_email": "..."}'
                    >{{ old('seo_boost_google_json_key') }}</textarea>
                    <div class="form-text">
                        @if ($googleConfigured)
                            {{ trans('plugins/seo-boost::seo-boost.google_json_key_replace_help') }}
                        @else
                            {{ trans('plugins/seo-boost::seo-boost.google_json_key_help') }}
                        @endif
                    </div>
                </x-core-setting::form-group>

                <x-core-setting::form-group>
                    <label class="form-label">{{ trans('plugins/seo-boost::seo-boost.auto_submit_post_types') }} — Google</label>
                    @foreach (['post' => trans('plugins/seo-boost::seo-boost.post_type_posts'), 'page' => trans('plugins/seo-boost::seo-boost.post_type_pages')] as $type => $label)
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="seo_boost_google_post_types[{{ $type }}]"
                                value="1"
                                id="seo-boost-google-type-{{ $type }}"
                                @checked(setting('seo_boost_google_post_types.' . $type, '1') == '1')
                            >
                            <label class="form-check-label" for="seo-boost-google-type-{{ $type }}">{{ $label }}</label>
                        </div>
                    @endforeach
                    {{ Form::helper(trans('plugins/seo-boost::seo-boost.google_quota_help', ['quota' => \Botble\SeoBoost\Services\GoogleIndexingService::DAILY_QUOTA])) }}
                </x-core-setting::form-group>
            </x-core-setting::section>

            <div class="flexbox-annotated-section" style="border: none">
                <div class="flexbox-annotated-section-annotation">&nbsp;</div>
                <div class="flexbox-annotated-section-content">
                    <button class="btn btn-info" type="submit">{{ trans('core/setting::setting.save_settings') }}</button>
                    <button type="button" class="btn btn-outline-primary seo-boost-google-test" data-url="{{ route('seo-boost.google.test') }}">
                        <i class="ti ti-plug"></i> {{ trans('plugins/seo-boost::seo-boost.google_test_connection') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('footer')
    <script>
        $(function () {
            'use strict';
            $(document).on('click', '.seo-boost-google-test', function (event) {
                event.preventDefault();
                const button = $(this);
                $httpClient.make().withButtonLoading(button).post(button.data('url')).then(function (res) {
                    const data = res.data;
                    if (data.error) { Botble.showError(data.message); } else { Botble.showSuccess(data.message); }
                });
            });
        });
    </script>
@endpush
