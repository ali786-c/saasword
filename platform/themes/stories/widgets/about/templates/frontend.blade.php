<div class="sidebar-widget widget-about mb-50 pt-30 pr-30 pb-30 pl-30 bg-white border-radius-5 has-border  wow fadeInUp animated">
    @if ($config['image'])
        <img class="about-author-img mb-25" src="{{ RvMedia::getImageUrl($config['image']) }}" alt="{{ $config['name'] }}">
    @endif
    <h5 class="mb-20">{{ $config['name'] }}</h5>
    <p class="font-medium text-muted">{!! BaseHelper::clean(shortcode()->compile($config['description'], true)) !!}</p>
    <strong>{{ __('Follow me') }}: </strong>

    {!! Theme::partial('social-links', ['cssClass' => 'header-social-network d-inline-block list-inline color-white mb-20']) !!}
</div>
