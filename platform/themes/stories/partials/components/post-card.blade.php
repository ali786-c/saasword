<div class="post-card-1 border-radius-10 hover-up">
    <div class="post-thumb thumb-overlay img-hover-slide position-relative" style="background-image: url({{ RvMedia::getImageUrl($post->image, null, false, RvMedia::getDefaultImage())}})">
        <a class="img-link" href="{{ $post->url }}" title="{{ $post->name }}"></a>
        {!! Theme::partial('social-share-buttons', compact('post')) !!}
    </div>
    <div class="post-content p-30">
        @if ($post->firstCategory)
        <div class="entry-meta meta-0 font-small mb-10">
            <a href="{{ $post->firstCategory->url }}"><span class="post-cat {{ random_color() }}">{{ $post->firstCategory->name }}</span></a>
        </div>
        @endif
        <div class="d-flex post-card-content mt-sm-3">
            <h4 class="h5 post-title mb-20 font-weight-900">
                <a href="{{ $post->url }}">{{ $post->name }}</a>
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
