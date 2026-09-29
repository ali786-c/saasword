@if ($posts->isNotEmpty())
    <ul class="list-post search-results-list">
        @foreach($posts as $post)
            <li class="mb-10">
                <div class="search-result-item d-flex align-items-center">
                    <div class="post-thumb post-thumb-64 mr-10">
                        <a href="{{ $post->url }}">
                            <img src="{{ RvMedia::getImageUrl($post->image, 'thumb', false, RvMedia::getDefaultImage()) }}" alt="{{ $post->name }}">
                        </a>
                    </div>
                    <div class="post-content flex-grow-1">
                        <h6 class="post-title text-limit-2-row"><a href="{{ $post->url }}">{{ $post->name }}</a></h6>
                        <div class="entry-meta">
                            <span class="post-on">{{ Theme::formatDate($post->created_at) }}</span>
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
@else
    <div class="text-center py-4">
        <i class="elegant-icon icon_search text-muted" style="font-size: 32px; opacity: 0.5;"></i>
        <p class="text-muted mt-15 mb-0">{{ __('No results found, please try with different keywords.') }}</p>
    </div>
@endif
