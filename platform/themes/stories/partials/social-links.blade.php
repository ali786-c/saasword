@if ($socialLinks = Theme::getSocialLinks())
    <ul class="{{ $cssClass }}">
        @foreach($socialLinks as $socialLink)
            @continue(! $socialLink->getUrl() || ! $socialLink->getIconHtml())

            <li class="list-inline-item">
                <a {!! $socialLink->getAttributes() !!}>{{ $socialLink->getIconHtml() }}</a>
            </li>
        @endforeach
    </ul>
@endif
