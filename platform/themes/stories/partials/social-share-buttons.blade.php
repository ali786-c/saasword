<ul class="social-share">
    <li><a href="#" title="Share buttons"><i class="elegant-icon social_share"></i></a></li>
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
