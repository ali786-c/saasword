</main>
    <footer class="pt-50 pb-20 bg-grey">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="sidebar-widget wow fadeInUp animated mb-30">
                        <div class="widget-header-2 position-relative mb-30">
                            <h5 class="mt-5 mb-30">{{ __('About') }} {{ theme_option('site_title', 'CareerInPak') }}</h5>
                        </div>
                        <div class="textwidget">
                            <p>
                                {{ theme_option('site_description') }}
                            </p>
                            @if (theme_option('address'))
                                <p><strong class="color-black">{{ __('Address') }}</strong><br>
                                    {{ theme_option('address') }}
                                </p>
                            @endif
                            @if (theme_option('social_1_url'))
                                <p><strong class="color-black">{{ __('Follow us') }}</strong><br>
                            @endif
                            {!! Theme::partial('social-links', ['cssClass' => 'header-social-network d-inline-block list-inline color-white mb-20']) !!}
                        </div>
                    </div>
                </div>
                {!! dynamic_sidebar('footer_sidebar') !!}
            </div>
            <div class="footer-copy-right pt-30 mt-20 wow fadeInUp animated">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        @if ($copyright = Theme::getSiteCopyright())
                            <p class="font-small text-muted mb-0">{!! $copyright !!}</p>
                        @endif
                    </div>
                    <div class="col-md-7 text-md-right">
                        {!! Menu::renderMenuLocation('footer-legal', [
                            'view' => 'menu',
                            'options' => ['class' => 'list-inline footer-legal-menu mb-0 font-small text-muted'],
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer -->
    <div class="dark-mark"></div>

    {!! Theme::footer() !!}

    @include('packages/theme::toast-notification')
</body>
</html>
