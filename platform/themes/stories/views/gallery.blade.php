@php Theme::set('title', $gallery->name); @endphp

<div class="archive-header">
    <h2 class="font-weight-900">{{ Theme::get('title') ?: SeoHelper::getTitle() }}</h2>
    @if (Theme::get('subtitle'))
        <p>{!! BaseHelper::clean(Theme::get('subtitle')) !!}</p>
    @endif
    {!! Theme::partial('breadcrumbs') !!}
    <div class="bt-1 border-color-1 mt-30 mb-50"></div>
</div>
<div class="pb-50">
    <div class="ck-content">
            {!! BaseHelper::clean($gallery->description) !!}
        </div>
    <div id="list-photo">
        @foreach (gallery_meta_data($gallery) as $image)
            @if ($image)
                <div class="item" data-src="{{ RvMedia::getImageUrl(Arr::get($image, 'img')) }}" data-sub-html="{{ BaseHelper::clean(Arr::get($image, 'description')) }}">
                    <div class="photo-item">
                        <div class="thumb">
                            <a href="{{ RvMedia::getImageUrl(Arr::get($image, 'img')) }}">
                                <img src="{{ RvMedia::getImageUrl(Arr::get($image, 'img')) }}" alt="{{ BaseHelper::clean(Arr::get($image, 'description')) }}" loading="lazy">
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    <br>
    {!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, null, $gallery) !!}
</div>
