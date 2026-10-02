<x-core::form.on-off.checkbox
    name="optimize_page_speed_enable"
    :label="trans('packages/optimize::optimize.settings.enable')"
    :checked="setting('optimize_page_speed_enable', false)"
    data-bb-toggle="collapse"
    data-bb-target=".optimize-settings"
    :wrapper="false"
/>

<x-core::form.fieldset
    data-bb-value="1"
    class="optimize-settings mt-3"
    @style(['display: none;' => !setting('optimize_page_speed_enable', false)])
>
    <div class="mb-4 p-3 bg-light border rounded">
        <h5 class="font-weight-bold mb-2">⚡ LiteSpeed Server RAM Cache</h5>
        <x-core::form.on-off.checkbox
            name="optimize_litespeed_cache_enable"
            label="Enable LiteSpeed Cache & Enterprise RAM Caching"
            :checked="setting('optimize_litespeed_cache_enable', true)"
            helper-text="Outputs X-LiteSpeed-CacheControl response headers to allow LiteSpeed Web Server to cache HTML directly in server RAM for sub-15ms response times."
        />
    </div>

    <div class="mb-4 p-3 bg-light border rounded">
        <h5 class="font-weight-bold mb-2">🖼️ Image Optimization & Next-Gen WebP</h5>
        <x-core::form.on-off.checkbox
            name="media_convert_image_to_webp"
            label="Convert Uploaded Images to WebP Automatically"
            :checked="setting('media_convert_image_to_webp', true)"
            helper-text="Automatically converts uploaded PNG, JPG, and JPEG images to high-compression .webp format to boost Google PageSpeed performance."
        />
        <div class="mt-3 pt-2 border-top">
            <button type="button" onclick="if(confirm('Convert all existing JPG/PNG images in Media Library to WebP format now?')) { document.getElementById('form-convert-webp-action').submit(); }" class="btn btn-warning btn-sm font-weight-bold">
                🔄 Convert All Existing Media Images to WebP Now
            </button>
        </div>
    </div>

<form id="form-convert-webp-action" action="{{ route('optimize.settings.convert-webp') }}" method="POST" class="d-none">
    @csrf
</form>

    <h5 class="font-weight-bold mb-2">🧹 PageSpeed & HTML Minification Controls</h5>

    <x-core::form.on-off.checkbox
        name="optimize_collapse_white_space"
        :label="trans('packages/optimize::optimize.collapse_white_space')"
        :checked="setting('optimize_collapse_white_space', false)"
        :helper-text="trans('packages/optimize::optimize.collapse_white_space_description')"
    />

    <x-core::form.on-off.checkbox
        name="optimize_elide_attributes"
        :label="trans('packages/optimize::optimize.elide_attributes')"
        :checked="setting('optimize_elide_attributes', false)"
        :helper-text="trans('packages/optimize::optimize.elide_attributes_description')"
    />

    <x-core::form.on-off.checkbox
        name="optimize_inline_css"
        :label="trans('packages/optimize::optimize.inline_css')"
        :checked="setting('optimize_inline_css', false)"
        :helper-text="trans('packages/optimize::optimize.inline_css_description')"
    />

    <x-core::form.on-off.checkbox
        name="optimize_insert_dns_prefetch"
        :label="trans('packages/optimize::optimize.insert_dns_prefetch')"
        :checked="setting('optimize_insert_dns_prefetch', false)"
        :helper-text="trans('packages/optimize::optimize.insert_dns_prefetch_description')"
    />

    <x-core::form.on-off.checkbox
        name="optimize_remove_comments"
        :label="trans('packages/optimize::optimize.remove_comments')"
        :checked="setting('optimize_remove_comments', false)"
        :helper-text="trans('packages/optimize::optimize.remove_comments_description')"
    />

    <x-core::form.on-off.checkbox
        name="optimize_remove_quotes"
        :label="trans('packages/optimize::optimize.remove_quotes')"
        :checked="setting('optimize_remove_quotes', false)"
        :helper-text="trans('packages/optimize::optimize.remove_quotes_description')"
    />

    <x-core::form.on-off.checkbox
        name="optimize_defer_javascript"
        :label="trans('packages/optimize::optimize.defer_javascript')"
        :checked="setting('optimize_defer_javascript', false)"
        :helper-text="trans('packages/optimize::optimize.defer_javascript_description')"
    />
</x-core::form.fieldset>
