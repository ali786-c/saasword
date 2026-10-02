<div class="container">
    <div class="pt-30 pb-10">
        <div class="row">
            <div class="col-lg-8 mb-30">
                <div class="widget-header-1 position-relative mb-30">
                    <h5 class="mt-5 mb-30">{{ $title }}</h5>
                </div>

                @php($mainPosts = $posts->take(3))
                @if ($mainPosts->isNotEmpty())
                    <div class="carausel-post-1 hover-up border-radius-10 overflow-hidden transition-normal position-relative">
                        <div class="arrow-cover"></div>
                        <div class="slide-fade">
                            @foreach($mainPosts as $post)
                                <div class="position-relative post-thumb">
                                    <div class="thumb-overlay img-hover-slide position-relative" style="background-image: url({{ RvMedia::getImageUrl($post->image, null, false, RvMedia::getDefaultImage()) }})">
                                        <a class="img-link" href="{{ $post->url }}" title="{{ $post->name }}"></a>
                                        <div class="post-content-overlay text-white ml-30 mr-30 pb-30">
                                            <div class="entry-meta meta-0 font-small mb-20">
                                                @foreach($post->categories->take(3) as $category)
                                                    <a href="{{ $category->url }}"><span class="post-cat {{ random_color() }} text-uppercase">{{ $category->name }}</span></a>
                                                @endforeach
                                            </div>
                                            <h3 class="post-title font-weight-900 mb-20">
                                                <a class="text-white" href="{{ $post->url }}">{{ $post->name }}</a>
                                            </h3>
                                            <div class="entry-meta meta-1 font-small text-white mt-10 pr-5 pl-5">
                                                <span class="post-on">{{ Theme::formatDate($post->created_at) }}</span>
                                                @if ($post->author && $post->author->id)
                                                    <span class="post-by has-dot">{{ $post->author->name }}</span>
                                                @endif
                                                <span class="hit-count has-dot">{{ number_format($post->views) }} {{ __('views') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4 mb-30">
                <div class="widget-header-1 position-relative mb-30">
                    <h5 class="mt-5 mb-30">{{ $latestTitle }}</h5>
                </div>

                @foreach($posts->skip(3)->take(2) as $post)
                    <article class="position-relative post-thumb mb-30">
                        <div class="thumb-overlay img-hover-slide border-radius-10" style="background-image: url({{ RvMedia::getImageUrl($post->image, null, false, RvMedia::getDefaultImage()) }})">
                            <a class="img-link" href="{{ $post->url }}" title="{{ $post->name }}"></a>
                            <div class="post-content-overlay text-white ml-30 mr-30 pb-30">
                                <div class="entry-meta meta-0 font-small mb-10">
                                    @foreach($post->categories->take(2) as $category)
                                        <a href="{{ $category->url }}"><span class="post-cat {{ random_color() }} text-uppercase">{{ $category->name }}</span></a>
                                    @endforeach
                                </div>
                                <h4 class="h5 post-title font-weight-900 mb-10">
                                    <a class="text-white" href="{{ $post->url }}">{{ $post->name }}</a>
                                </h4>
                                <div class="entry-meta meta-1 font-small text-white">
                                    <span class="post-on">{{ Theme::formatDate($post->created_at) }}</span>
                                    @if ($post->author && $post->author->id)
                                        <span class="post-by has-dot">{{ $post->author->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</div>
