@php
    $layout = $post->getMetaData('layout', true);
    $layout = ($layout && in_array($layout, array_keys(get_blog_single_layouts()))) ? $layout : 'blog-full-width';
    Theme::layout($layout);
    Theme::set('title', $post->name);
@endphp

<div class="container single-content">
    <div class="entry-header entry-header-style-1 mb-50 pt-50">
        <h1 class="entry-title mb-50 font-weight-900">
            {{ $post->name }}
        </h1>
        <div class="row">
            <div class="col-md-6">
                <div class="entry-meta align-items-center meta-2 font-small color-muted">
                    @if ($post->author && $post->author->id)
                        <p class="mb-5">
                            <span class="author-avatar"><img class="img-circle" src="{{ $post->author->avatar_url }}" alt="{{ $post->author->name }}" loading="lazy"></span>
                                {{ __('By') }} <span class="author-name font-weight-bold">{{ $post->author->name }}</span>
                        </p>
                    @endif
                    <span class="mr-10"> {{ Theme::formatDate($post->created_at) }}</span>
                    @if ($post->time_reading)
                        <span class="has-dot mr-2"> {{ __(':number mins read', ['number' => $post->time_reading]) }}</span>
                    @endif
                    <span class="hit-count has-dot">{{ number_format($post->views) }} {{ __('views') }}</span>
                </div>
            </div>
            @if ($sharingButtons = \Botble\Theme\Supports\ThemeSupport::getSocialSharingButtons($post->url, $post->description, $post->image))
                <div class="col-md-6 text-right d-none d-md-inline">
                    <ul class="header-social-network d-inline-block list-inline mr-15">
                        <li class="list-inline-item text-muted"><span>{{ __('Share this') }}: </span></li>
                        @foreach($sharingButtons as $button)
                            <li class="list-inline-item">
                                <a
                                    href="{{ $button['url'] }}"
                                    target="_blank"
                                    title="{{ __('Share on :social', ['social' => $button['name']]) }}"
                                    @style(["background-color: {$button['background_color']}" => $button['background_color'], "color: {$button['color']}" => $button['color']])
                                >
                                    {!! $button['icon'] !!}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    @if ($post->image)
        <figure class="image mb-30 m-auto text-center border-radius-10">
            <img class="border-radius-10" src="{{ RvMedia::getImageUrl($post->image) }}" alt="{{ $post->name }}" loading="lazy">
        </figure>
    @endif
    <article class="entry-wraper mb-50">
        <div class="entry-main-content">
            @if (defined('GALLERY_MODULE_SCREEN_NAME') && !empty($galleries = gallery_meta_data($post)))
                {!! render_object_gallery($galleries, ($post->first_category ? $post->first_category->name : __('Uncategorized'))) !!}
            @endif
            <div class="ck-content">{!! BaseHelper::clean($post->content) !!}</div>
        </div>
        @if ($post->tags->isNotEmpty())
            <div class="entry-bottom mt-50 mb-30 wow fadeIn  animated" style="visibility: visible; animation-name: fadeIn;">
                <div class="tags">
                    <span>{{ __('Tags') }}: </span>
                    @foreach ($post->tags as $tag)
                        <a href="{{ $tag->url }}" rel="tag">{{ $tag->name }}</a>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="single-social-share clearfix wow fadeIn  animated" style="visibility: visible; animation-name: fadeIn;">
            <ul class="header-social-network d-inline-block list-inline float-md-right mt-md-0 mt-4">
                <li class="list-inline-item text-muted"><span>{{ __('Share this') }}: </span></li>
                @foreach(\Botble\Theme\Supports\ThemeSupport::getSocialSharingButtons($post->url, $post->description, $post->image) as $button)
                    <li class="list-inline-item">
                        <a
                            href="{{ $button['url'] }}"
                            target="_blank"
                            title="{{ __('Share on :social', ['social' => $button['name']]) }}"
                            @style(["background-color: {$button['background_color']}" => $button['background_color'], "color: {$button['color']}" => $button['color']])
                        >
                            {!! $button['icon'] !!}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        @if ($post->author && $post->author->id)
            <div class="author-bio p-30 mt-50 border-radius-10 bg-white wow fadeIn  animated" style="visibility: visible; animation-name: fadeIn;">
                @php
                    $bio = $post->author->getMetaData('bio', true);
                @endphp
                <div class="author-image @if($bio) mb-30 @endif">
                    <img class="avatar" src="{{ $post->author->avatar_url }}" alt="{{ $post->author->name }}" loading="lazy">
                </div>
                <div class="author-info @if(! $bio) d-flex align-items-center @endif">
                    <h4 class="font-weight-bold @if($bio) mb-20 @else mb-0 @endif">
                        <span class="vcard author"><span class="fn">{{ $post->author->name }}</span></span>
                    </h4>
                    @if($bio)
                        <p>{!! BaseHelper::clean($bio) !!}</p>
                    @endif
                </div>
            </div>
        @endif

        {!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, null, $post) !!}

        @php $relatedPosts = get_related_posts($post->id, 4); @endphp
        @if ($relatedPosts->isNotEmpty())
            <div class="related-posts">
                <div class="post-module-3">
                    <div class="widget-header-2 position-relative mb-30">
                        <h5 class="mt-5 mb-30">{{ __('Related posts') }}</h5>
                    </div>
                    <div class="loop-list loop-list-style-1">
                        @foreach ($relatedPosts->take(2) as $relatedItem)
                            <article class="hover-up-2 transition-normal wow fadeInUp   animated" style="visibility: visible; animation-name: fadeInUp;">
                                <div class="row mb-40 list-style-2">
                                    <div class="col-md-4">
                                        <div class="post-thumb position-relative border-radius-5">
                                            <div class="img-hover-slide border-radius-5 position-relative" style="background-image: url({{ RvMedia::getImageUrl($relatedItem->image) }})">
                                                <a class="img-link" href="{{ $relatedItem->url }}" title="{{ $relatedItem->name }}"></a>
                                            </div>
                                            {!! Theme::partial('social-share-buttons', compact('post')) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-8 align-self-center">
                                        <div class="post-content">
                                            <div class="entry-meta meta-0 font-small mb-10">
                                                @foreach($relatedItem->categories as $category)
                                                    <a href="{{ $category->url }}"><span class="post-cat {{ random_color() }}">{{ $category->name }}</span></a>
                                                @endforeach
                                            </div>
                                            <h4 class="h5 post-title font-weight-900 mb-20">
                                                <a href="{{ $relatedItem->url }}">{{ $relatedItem->name }}</a>
                                                <span class="post-format-icon ml-1"><i class="elegant-icon icon_star_alt"></i></span>
                                            </h4>
                                            <div class="entry-meta meta-1 float-left font-x-small text-uppercase">
                                                <span class="post-on">{{ Theme::formatDate($relatedItem->created_at) }}</span>
                                                <span class="time-reading has-dot">{{ __(':number mins read', ['number' => $relatedItem->time_reading]) }}</span>
                                                <span class="post-by has-dot">{{ number_format($relatedItem->views) }} {{ __('views') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        @if ($relatedPosts->count() > 2)
            <div class="single-more-articles border-radius-5">
                <div class="widget-header-2 position-relative mb-30">
                    <h5 class="mt-5 mb-30">{{ __('You might be interested in') }}</h5>
                    <button class="single-more-articles-close"><i class="elegant-icon icon_close"></i></button>
                </div>
                <div class="post-block-list post-module-1 post-module-5">
                    <ul class="list-post">
                        @foreach ($relatedPosts->skip(2) as $relatedItem)
                            <li class="mb-30">
                                <div class="d-flex hover-up-2 transition-normal">
                                    <div class="post-thumb post-thumb-80 d-flex mr-15 border-radius-5 img-hover-scale overflow-hidden">
                                        <a class="color-white" href="{{ $relatedItem->url }}">
                                            <img src="{{ RvMedia::getImageUrl($relatedItem->image) }}" alt="{{ $relatedItem->name }}" loading="lazy">
                                        </a>
                                    </div>
                                    <div class="post-content media-body">
                                        <h6 class="post-title mb-15 text-limit-2-row font-medium"><a href="{{ $relatedItem->url }}">{{ $relatedItem->name }}</a></h6>
                                        <div class="entry-meta meta-1 float-left font-x-small text-uppercase">
                                            <span class="post-on">{{ Theme::formatDate($relatedItem->created_at) }}</span>
                                            <span class="post-by has-dot">{{ number_format($relatedItem->views) }} {{ __('views') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </article>
</div>
