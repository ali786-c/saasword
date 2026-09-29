@php
    Gallery::registerAssets();
    Theme::set('title', SeoHelper::getTitle());
@endphp

<div class="archive-header">
    <h2 class="font-weight-900">{{ Theme::get('title') ?: SeoHelper::getTitle() }}</h2>
    @if (Theme::get('subtitle'))
        <p>{!! BaseHelper::clean(Theme::get('subtitle')) !!}</p>
    @endif
    {!! Theme::partial('breadcrumbs') !!}
    <div class="bt-1 border-color-1 mt-30 mb-50"></div>
</div>

<div class="single-content">
    <div class="pb-50">
        @if (isset($galleries) && !$galleries->isEmpty())
            <div class="gallery-wrap">
                @foreach ($galleries as $gallery)
                    <div class="gallery-item">
                        <div class="img-wrap">
                            <a href="{{ $gallery->url }}"><img src="{{ RvMedia::getImageUrl($gallery->image, 'medium', false, RvMedia::getDefaultImage()) }}" alt="{{ $gallery->name }}" loading="lazy"></a>
                        </div>
                        <div class="gallery-detail">
                            <div class="gallery-title"><a href="{{ $gallery->url }}">{{ $gallery->name }}</a></div>
                            @if (trim($gallery->user->name))
                                <div class="gallery-author">{{ __('By') }} {{ $gallery->user->name }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="clearfix"></div>
        @endif
    </div>
</div>
