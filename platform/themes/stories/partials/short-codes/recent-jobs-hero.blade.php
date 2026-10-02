<style>
.hero-side-card {
    height: 185px;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hero-side-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
}
.hero-side-card .img-link {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 2;
}
.hero-side-card__categories {
    position: absolute;
    top: 12px;
    left: 15px;
    z-index: 3;
}
.hero-side-card__content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 3;
    padding: 15px;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.95) 0%, rgba(0, 0, 0, 0.65) 60%, rgba(0, 0, 0, 0) 100%);
}
.hero-side-card__content .post-title {
    margin: 0 0 4px 0;
    font-size: 14px;
    font-weight: 700;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.hero-side-card__content .post-title a {
    color: #ffffff !important;
    text-shadow: 0 1px 3px rgba(0,0,0,0.8);
}
.hero-side-card__content .post-title a:hover {
    color: #ff4a5a !important;
}
.hero-side-card__content .entry-meta {
    font-size: 11px;
    color: rgba(255, 255, 255, 0.85);
}
</style>

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
                    <div class="hero-side-card mb-20" style="background-image: url({{ RvMedia::getImageUrl($post->image, null, false, RvMedia::getDefaultImage()) }});">
                        <a class="img-link" href="{{ $post->url }}" title="{{ $post->name }}"></a>
                        
                        <div class="hero-side-card__categories">
                            @foreach($post->categories->take(2) as $category)
                                <a href="{{ $category->url }}"><span class="post-cat {{ random_color() }} text-uppercase">{{ $category->name }}</span></a>
                            @endforeach
                        </div>

                        <div class="hero-side-card__content">
                            <h6 class="post-title">
                                <a href="{{ $post->url }}">{{ $post->name }}</a>
                            </h6>
                            <div class="entry-meta">
                                <span class="post-on">{{ Theme::formatDate($post->created_at) }}</span>
                                @if ($post->author && $post->author->id)
                                    <span class="post-by has-dot">{{ $post->author->name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
