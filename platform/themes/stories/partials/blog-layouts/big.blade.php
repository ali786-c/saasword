<div class="loop-list loop-list-style-1">
    <div class="row">
        @foreach($posts->loadMissing('metadata') as $post)
            <article class="col-md-{{ Theme::getLayoutName() == 'right-sidebar' ? 6 : 4 }} mb-40 wow fadeInUp animated">
                <div class="post-card-1 border-radius-10 hover-up">
                    <div class="post-thumb thumb-overlay img-hover-slide position-relative" style="background-image: url({{ RvMedia::getImageUrl($post->image, null, false, RvMedia::getDefaultImage()) }})">
                        <a class="img-link" href="{{ $post->url }}" title="{{ $post->name }}"></a>
                        {!! Theme::partial('social-share-buttons', compact('post')) !!}
                    </div>
                    <div class="post-content p-30">
                        <div class="entry-meta meta-0 font-small mb-10">
                            @foreach($post->categories as $category)
                                <a href="{{ $category->url }}"><span class="post-cat {{ random_color() }}">{{ $category->name }}</span></a>
                            @endforeach
                        </div>
                        <div class="d-flex post-card-content">
                            <h4 class="h5 post-title mb-20 font-weight-900">
                                <a href="{{ $post->url }}">{{ $post->name }}</a>
                            </h4>
                            <div class="post-excerpt mb-25 font-small text-muted">
                                <p>{{ $post->description }}</p>
                            </div>
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
            </article>
        @endforeach
    </div>
</div>

<div class="pagination-area mb-30 wow fadeInUp animated justify-content-start">
    {!! $posts->withQueryString()->onEachSide(1)->links() !!}
</div>
