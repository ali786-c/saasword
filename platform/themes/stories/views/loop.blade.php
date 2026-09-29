@php
    $posts->loadMissing('metadata')
@endphp

@include(Theme::getThemeNamespace() . '::views.templates.posts', compact('posts'))
