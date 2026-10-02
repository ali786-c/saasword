<div class="container">
    <div class="seo-text-section pt-50 pb-30">
        @foreach($blocks as $block)
            <div class="seo-block mb-30">
                <h2 class="font-weight-900 mb-20">{{ Arr::get($block, 'heading') }}</h2>
                <p class="text-muted">{!! BaseHelper::clean(Arr::get($block, 'text')) !!}</p>
            </div>
        @endforeach

        <div class="seo-internal-links border-radius-10 bg-white p-30 mt-20">
            <h3 class="font-weight-900 mb-20">{{ __('Explore jobs by category') }}</h3>
            <ul class="list-inline mb-30">
                @foreach($categories as $category)
                    <li class="list-inline-item mr-20 mb-10">
                        <a class="text-muted" href="{{ $category->url }}">
                            <i class="elegant-icon arrow_right-alt mr-5"></i>{{ str_ends_with($category->name, 'Jobs') ? $category->name : $category->name . ' Jobs' }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <h3 class="font-weight-900 mb-20">{{ __('Important pages') }}</h3>
            <ul class="list-inline mb-0">
                @foreach($pages as $page)
                    <li class="list-inline-item mr-20 mb-10">
                        <a class="text-muted" href="{{ url('/' . ($page->slugable->key ?? '')) }}">{{ $page->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
