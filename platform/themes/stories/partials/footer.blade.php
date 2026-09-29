</main>
    <footer class="pt-50 pb-20 bg-grey">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="sidebar-widget wow fadeInUp animated mb-30">
                        <div class="widget-header-2 position-relative mb-30">
                            <h5 class="mt-5 mb-30">{{ __('About me') }}</h5>
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
                                <p><strong class="color-black">{{ __('Follow me') }}</strong><br>
                            @endif
                            {!! Theme::partial('social-links', ['cssClass' => 'header-social-network d-inline-block list-inline color-white mb-20']) !!}
                        </div>
                    </div>
                </div>
                {!! dynamic_sidebar('footer_sidebar') !!}
            </div>
            <div class="footer-copy-right pt-30 mt-20 wow fadeInUp animated">
                @if ($copyright = Theme::getSiteCopyright())
                    <p class="float-md-left font-small text-muted">{!! $copyright !!}</p>
                @endif

                <p class="float-md-right font-small text-muted">
                    {{ theme_option('designed_by') }}
                </p>
            </div>
        </div>
    </footer>
    <!-- End Footer -->
    <div class="dark-mark"></div>

    {!! Theme::footer() !!}

    @include('packages/theme::toast-notification')
</body>
</html>
