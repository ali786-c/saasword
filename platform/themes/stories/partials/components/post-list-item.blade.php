<div class="row mb-40 list-style-2">
    <div class="col-md-4">
        <div class="post-thumb position-relative border-radius-5">
            <div class="img-hover-slide border-radius-5 position-relative" style="background-image: url({{ RvMedia::getImageUrl($post->image, null, false, RvMedia::getDefaultImage())}})">
                <a class="img-link" href="{{ $post->url }}" title="{{ $post->name }}"></a>
            </div>
            {!! Theme::partial('social-share-buttons', compact('post')) !!}
        </div>
    </div>
    <div class="col-md-8 align-self-center">
        <div class="post-content">
            @if ($post->firstCategory)
                <div class="entry-meta meta-0 font-small mb-10">
                    <a href="{{ $post->firstCategory->url }}"><span class="post-cat {{ random_color() }}">{{ $post->firstCategory->name }}</span></a>
                </div>
            @endif
            <h4 class="h5 post-title font-weight-900 mb-20">
                <a href="{{ $post->url }}">{{ $post->name }}</a>
                <span class="post-format-icon ml-1"><i class="elegant-icon icon_star_alt"></i></span>
            </h4>
            <div class="entry-meta meta-1 float-left font-x-small text-uppercase">
                <span class="post-on">{{ Theme::formatDate($post->created_at) }}</span>
                @if ($post->time_reading)
                    <span class="time-reading has-dot">{{ __(':number mins read', ['number' => $post->time_reading]) }}</span>
                @endif
                <span class="post-by has-dot">{{ number_format($post->views) }} {{ __('views') }}</span>
            </div>
        </div>
    </div>
</div>
