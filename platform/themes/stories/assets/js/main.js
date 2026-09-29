(function($) {
    'use strict';

    $(window).on('load', function() {
        $('.preloader').delay(450).fadeOut('slow');
    });

    var scrollProgress = function() {
        var docHeight = $(document).height(),
            windowHeight = $(window).height(),
            scrollPercent;
        $(window).on('scroll', function() {
            scrollPercent = $(window).scrollTop() / (docHeight - windowHeight) * 100;
            $('.scroll-progress').width(scrollPercent + '%');
        });
    };

    var OffCanvas = function() {
        $('#off-canvas-toggle').on('click', function() {
            $('body').toggleClass("canvas-opened");
        });

        $('.dark-mark').on('click', function() {
            $('body').removeClass("canvas-opened");
        });
        $('.off-canvas-close').on('click', function() {
            $('body').removeClass("canvas-opened");
        });
    };

    var openSearchForm = function() {
        $('button.search-icon').on('click', function() {
            $('body').toggleClass("open-search-form");
            $('.mega-menu-item').removeClass("open");
            $("html, body").animate({ scrollTop: 0 }, "slow");
        });
        $('.search-close').on('click', function() {
            $('body').removeClass("open-search-form");
        });
    };

    var mobileMenu = function() {
        var menu = $('ul#mobile-menu');
        if (menu.length) {
            menu.slicknav({
                prependTo: '.mobile_menu',
                closedSymbol: '+',
                openedSymbol: '-',
                label: menu.data('label'),
                allowParentLinks: true,
            });
        };
    };

    var SubMenu = function() {
        $(".menu li.menu-item-has-children").on({
            mouseenter: function() {
                $('.sub-menu:first, .children:first', this).stop(true, true).slideDown('fast');
            },
            mouseleave: function() {
                $('.sub-menu:first, .children:first', this).stop(true, true).slideUp('fast');
            }
        });
    };

    var WidgetSubMenu = function() {
        $('.menu li.menu-item-has-children').on('click', function() {
            var element = $(this);
            if (element.hasClass('open')) {
                element.removeClass('open');
                element.find('li').removeClass('open');
                element.find('ul').slideUp(200);
            } else {
                element.addClass('open');
                element.children('ul').slideDown(200);
                element.siblings('li').children('ul').slideUp(200);
                element.siblings('li').removeClass('open');
                element.siblings('li').find('li').removeClass('open');
                element.siblings('li').find('ul').slideUp(200);
            }
        });
    };

    var customSlickSlider = function() {
        if ($('.slide-fade').length && !$('.slide-fade').hasClass('slick-initialized')) {
            $('.slide-fade').slick({
                infinite: true,
                dots: false,
                arrows: true,
                autoplay: false,
                autoplaySpeed: 3000,
                fade: true,
                fadeSpeed: 1500,
                prevArrow: '<button type="button" class="slick-prev" title="Previous"><i class="elegant-icon arrow_left"></i></button>',
                nextArrow: '<button type="button" class="slick-next" title="Next"><i class="elegant-icon arrow_right"></i></button>',
                appendArrows: '.arrow-cover',
            });
        }

        if ($('.carausel-3-columns').length && !$('.carausel-3-columns').hasClass('slick-initialized')) {
            $('.carausel-3-columns').slick({
                dots: false,
                infinite: true,
                speed: 1000,
                arrows: false,
                autoplay: true,
                slidesToShow: 3,
                slidesToScroll: 1,
                loop: true,
                adaptiveHeight: true,
                responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                    }
                },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
        }

        $('.carousel-slider').each(function (index, el) {
            if (!$(el).hasClass('slick-initialized')) {
                $(el).slick({
                dots: false,
                infinite: true,
                speed: 1000,
                arrows: false,
                autoplay: true,
                slidesToShow: $(el).data('items') ? $(el).data('items') : 3,
                slidesToScroll: 1,
                loop: true,
                adaptiveHeight: true,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: $(el).data('items') ? $(el).data('items') : 3,
                            slidesToScroll: $(el).data('items') ? $(el).data('items') : 3,
                        }
                    },
                    {
                        breakpoint: 1199,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    },
                ]
                });
            }
        });

        if ($('.featured-slider-2-items').length && $('.featured-slider-2-nav').length) {
            if (!$('.featured-slider-2-items').hasClass('slick-initialized')) {
                $('.featured-slider-2-items').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    dots: false,
                    fade: true,
                    asNavFor: '.featured-slider-2-nav',
                });
            }
            if (!$('.featured-slider-2-nav').hasClass('slick-initialized')) {
                $('.featured-slider-2-nav').slick({
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    vertical: true,
                    asNavFor: '.featured-slider-2-items',
                    dots: false,
                    arrows: false,
                    focusOnSelect: true,
                    verticalSwiping: true
                });
            }
        }
        if ($('.featured-slider-3-items').length && !$('.featured-slider-3-items').hasClass('slick-initialized')) {
            $('.featured-slider-3-items').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: true,
                dots: false,
                fade: true,
                prevArrow: '<button type="button" class="slick-prev"><i class="elegant-icon arrow_left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="elegant-icon arrow_right"></i></button>',
                appendArrows: '.slider-3-arrow-cover',
            });
        }
    };

    var typeWriter = function() {
        var TxtType = function(el, toRotate, period) {
            this.toRotate = toRotate;
            this.el = el;
            this.loopNum = 0;
            this.period = parseInt(period, 10) || 2000;
            this.txt = '';
            this.tick();
            this.isDeleting = !1
        };
        TxtType.prototype.tick = function() {
            var i = this.loopNum % this.toRotate.length;
            var fullTxt = this.toRotate[i];
            if (this.isDeleting) {
                this.txt = fullTxt.substring(0, this.txt.length - 1)
            } else {
                this.txt = fullTxt.substring(0, this.txt.length + 1)
            }
            this.el.innerHTML = '<span class="wrap">' + this.txt + '</span>';
            var that = this;
            var delta = 200 - Math.random() * 100;
            if (this.isDeleting) {
                delta /= 2
            }
            if (!this.isDeleting && this.txt === fullTxt) {
                delta = this.period;
                this.isDeleting = !0
            } else if (this.isDeleting && this.txt === '') {
                this.isDeleting = !1;
                this.loopNum++;
                delta = 500
            }
            setTimeout(function() {
                that.tick()
            }, delta)
        };
        window.onload = function() {
            var elements = document.getElementsByClassName('typewrite');
            for (var i = 0; i < elements.length; i++) {
                var toRotate = elements[i].getAttribute('data-type');
                var period = elements[i].getAttribute('data-period');
                if (toRotate) {
                    new TxtType(elements[i], JSON.parse(toRotate), period)
                }
            }
            var css = document.createElement("style");
            css.type = "text/css";
            css.innerHTML = ".typewrite > .wrap { border-right: 0.05em solid #5869DA}";
            document.body.appendChild(css)
        }
    }

    var niceSelectBox = function() {
        var nice_Select = $('select');
        if (nice_Select.length) {
            nice_Select.niceSelect();
        }
    };

    var headerSticky = function() {
        $(window).on('scroll', function() {
            var scroll = $(window).scrollTop();
            if (scroll < 245) {
                $(".header-sticky").removeClass("sticky-bar");
            } else {
                $(".header-sticky").addClass("sticky-bar");
            }
        });
    };

    var scrollToTop = function() {
        $.scrollUp({
            scrollName: 'scrollUp',
            topDistance: '300',
            topSpeed: 300,
            animation: 'fade',
            animationInSpeed: 200,
            animationOutSpeed: 200,
            scrollText: '<i class="elegant-icon arrow_up"></i>',
            activeOverlay: false
        });
    };

    var VSticker = function() {
        $('#news-flash').vTicker({
            speed: 800,
            pause: 3000,
            animation: 'fade',
            mousePause: false,
            showItems: 1
        });
        $('#date-time').vTicker({
            speed: 800,
            pause: 3000,
            animation: 'fade',
            mousePause: false,
            showItems: 1
        });
    };

    var stickySidebar = function() {
        $('.sticky-sidebar').theiaStickySidebar();
    };

    var customScrollbar = function() {
        if (! $('.custom-scrollbar').length) {
            return
        }

        new PerfectScrollbar('.custom-scrollbar', {
            isRtl: $('body').prop('dir') === 'rtl',
        });
    };

    var megaMenu = function() {
        $('.sub-mega-menu .nav-pills > a').on('mouseover', function() {
            $(this).tab('show');
        });
    };

    var magPopup = function() {
        if ($('.play-video').length) {
            $('.play-video').magnificPopup({
                disableOn: 700,
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                fixedContentPos: false
            });
        }
    };

    var masonryGrid = function() {
        if ($(".grid").length) {
            var $grid = $('.grid').masonry({
                itemSelector: '.grid-item',
                percentPosition: true,
                columnWidth: '.grid-sizer',
                gutter: 0
            });

            $grid.imagesLoaded().progress(function() {
                $grid.masonry();
            });
        }
    };

    var moreArticles = function() {
        $.fn.vwScroller = function(options) {
            var default_options = {
                delay: 500,
                position: 0.7,
                visibleClass: '',
                invisibleClass: '',
            }

            var isVisible = false;
            var $document = $(document);
            var $window = $(window);

            options = $.extend(default_options, options);

            var observer = $.proxy(function() {
                var isInViewPort = $document.scrollTop() > (($document.height() - $window.height()) * options.position);

                if (!isVisible && isInViewPort) {
                    onVisible();
                } else if (isVisible && !isInViewPort) {
                    onInvisible();
                }
            }, this);

            var onVisible = $.proxy(function() {
                isVisible = true;

                if (options.visibleClass) {
                    this.addClass(options.visibleClass);
                }

                if (options.invisibleClass) {
                    this.removeClass(options.invisibleClass);
                }

            }, this);

            var onInvisible = $.proxy(function() {
                isVisible = false;

                if (options.visibleClass) {
                    this.removeClass(options.visibleClass);
                }

                if (options.invisibleClass) {
                    this.addClass(options.invisibleClass);
                }
            }, this);

            setInterval(observer, options.delay);

            return this;
        }

        if ($.fn.vwScroller) {
            var $more_articles = $('.single-more-articles');
            $more_articles.vwScroller({ visibleClass: 'single-more-articles--visible', position: 0.55 })
            $more_articles.find('.single-more-articles-close-button').on('click', function() {
                $more_articles.hide();
            });
        }

        $('button.single-more-articles-close').on('click', function() {
            $('.single-more-articles').removeClass('single-more-articles--visible');
        });
    }

    if (typeof WOW != 'undefined') {
        new WOW().init();
    }

    var blogPostsSearchForm = function() {
        var $blogSearchForm = $('[data-bb-toggle="blog-posts-search-form"]');
        var $blogSearchResult = $('.blog-posts-search-results');

        if (!$blogSearchForm.length) {
            return;
        }

        function debounce(func, timeout) {
            timeout = timeout || 300;
            var timer;

            return function() {
                var args = arguments;
                var context = this;
                clearTimeout(timer);
                timer = setTimeout(function() {
                    func.apply(context, args);
                }, timeout);
            };
        }

        $blogSearchForm.on('keyup', 'input[name="q"]', debounce(function(event) {
            loadAjaxBlogSearch($(event.target).val() || null);
        }, 300));

        function loadAjaxBlogSearch(keyword) {
            var url = $blogSearchForm.data('url');

            if (!url) {
                return;
            }

            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                data: {
                    q: keyword
                },
                beforeSend: function() {
                    $blogSearchResult.find('.lds-dual-ring').addClass('loading');
                    $blogSearchResult.find('.content').addClass('loading');
                },
                success: function(response) {
                    if (response.error) {
                        return;
                    }

                    $blogSearchResult.find('.content').html(response.data);
                },
                error: function(error) {
                    console.error(error);
                },
                complete: function() {
                    $blogSearchResult.find('.lds-dual-ring').removeClass('loading');
                    $blogSearchResult.find('.content').removeClass('loading');
                }
            });
        }
    };

    $(document).ready(function() {
        openSearchForm();
        OffCanvas();
        customScrollbar();
        magPopup();
        scrollToTop();
        headerSticky();
        stickySidebar();
        customSlickSlider();
        megaMenu();
        mobileMenu();
        typeWriter();
        WidgetSubMenu();
        scrollProgress();
        masonryGrid();
        niceSelectBox();
        moreArticles();
        VSticker();
        blogPostsSearchForm();
    });

    document.addEventListener('shortcode.loaded', function () {
        masonryGrid();
        customSlickSlider()
    });

})(jQuery);
