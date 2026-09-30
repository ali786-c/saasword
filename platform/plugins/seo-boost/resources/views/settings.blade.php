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
                    :label="trans('plugins/seo-boost::seo-boost.auto_submit_enabled')"
                    :checked="setting('seo_boost_enabled', '1') == '1'"
                />

                <x-core-setting::form-group>
                    <label class="form-label">{{ trans('plugins/seo-boost::seo-boost.auto_submit_post_types') }}</label>
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
                    {{ Form::helper(trans('plugins/seo-boost::seo-boost.auto_submit_post_types_help')) }}
                </x-core-setting::form-group>
            </x-core-setting::section>

            <div class="flexbox-annotated-section" style="border: none">
                <div class="flexbox-annotated-section-annotation">&nbsp;</div>
                <div class="flexbox-annotated-section-content">
                    <button class="btn btn-info" type="submit">{{ trans('core/setting::setting.save_settings') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection
