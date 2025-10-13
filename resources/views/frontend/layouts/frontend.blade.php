<!doctype html>
<html class="no-js" lang="tr">

<head>
    {{-- Temel meta --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Başlık & Açıklama --}}
    <title>{{ $seo['title'] ?? ($site->site_title ?? config('app.name')) }}</title>
    @if (!empty($seo['meta_description']))
        <meta name="description" content="{{ $seo['meta_description'] }}">
    @endif

    {{-- Canonical (partial içinde de üretiliyor; burada kalması sorun değil) --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Robots --}}
    @if (app()->environment('local', 'staging'))
        <meta name="robots" content="noindex,nofollow">
    @else
        <meta name="robots" content="index,follow">
    @endif

    {{-- Tema / UI --}}
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" content="#0f172a">

    {{-- Favicon & Apple Touch Icon (dinamik) --}}
    @php
        $favicon = $seo['favicon'] ?? ($site->favicon_url ?? asset('favicon.ico'));
    @endphp
    <link rel="icon" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ $favicon }}">

    {{-- ✅ SEO / OG / Twitter --}}
    @include('frontend.layouts.partials.seo')

    {{-- === Analytics / Head Scripts (PRODUCTION) === --}}
    @if (app()->environment('production'))
        {{-- GTM tercih; yoksa GA4 --}}
        @if (!empty($site?->gtm_id))
            <!-- Google Tag Manager -->
            <script>
                (function(w, d, s, l, i) {
                    w[l] = w[l] || [];
                    w[l].push({
                        'gtm.start': new Date().getTime(),
                        event: 'gtm.js'
                    });
                    var f = d.getElementsByTagName(s)[0],
                        j = d.createElement(s),
                        dl = l != 'dataLayer' ? '&l=' + l : '';
                    j.async = true;
                    j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                    f.parentNode.insertBefore(j, f);
                })(window, document, 'script', 'dataLayer', '{{ $site->gtm_id }}');
            </script>
            <!-- End Google Tag Manager -->
        @elseif (!empty($site?->ga_measurement_id))
            <!-- Google Analytics (GA4) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $site->ga_measurement_id }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];

                function gtag() {
                    dataLayer.push(arguments);
                }
                gtag('js', new Date());
                gtag('config', '{{ $site->ga_measurement_id }}');
            </script>
            <!-- End GA4 -->
        @endif

        {{-- Meta Pixel --}}
        @if (!empty($site?->meta_pixel_id))
            <!-- Meta Pixel Code -->
            <script>
                ! function(f, b, e, v, n, t, s) {
                    if (f.fbq) return;
                    n = f.fbq = function() {
                        n.callMethod ?
                            n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                    };
                    if (!f._fbq) f._fbq = n;
                    n.push = n;
                    n.loaded = !0;
                    n.version = '2.0';
                    n.queue = [];
                    t = b.createElement(e);
                    t.async = !0;
                    t.src = v;
                    s = b.getElementsByTagName(e)[0];
                    s.parentNode.insertBefore(t, s)
                }(window, document, 'script',
                    'https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', '{{ $site->meta_pixel_id }}');
                fbq('track', 'PageView');
            </script>
            <!-- End Meta Pixel Code -->
        @endif

        {{-- Head Scripts (panel) --}}
        @if (!empty($site?->head_scripts))
            {!! $site->head_scripts !!}
        @endif
    @endif
    {{-- === /Analytics / Head Scripts === --}}

    {{-- CSS --}}
    @include('frontend.includes.css')

    {{-- Sayfa bazlı ekstra head içerikleri --}}
    @stack('head')
    
</head>

<body>
    {{-- === GTM / Meta Pixel noscript (PRODUCTION) === --}}
    @if (app()->environment('production'))
        @if (!empty($site?->gtm_id))
            <!-- Google Tag Manager (noscript) -->
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $site->gtm_id }}" height="0"
                    width="0" style="display:none;visibility:hidden"></iframe></noscript>
            <!-- End -->
        @endif

        @if (!empty($site?->meta_pixel_id))
            <!-- Meta Pixel (noscript) -->
            <noscript>
                <img height="1" width="1" style="display:none"
                    src="https://www.facebook.com/tr?id={{ $site->meta_pixel_id }}&ev=PageView&noscript=1" />
            </noscript>
            <!-- End -->
        @endif
    @endif

    <!--[if lte IE 9]>
      <p class="browserupgrade">Eski bir tarayıcı kullanıyorsunuz. Daha iyi deneyim için güncelleyin.</p>
    <![endif]-->

    <!-- pre loader -->
    <div id="loading">
        <div id="loading-center">
            <div id="loading-center-absolute">
                <div class="preloader__content text-center">
                    
                    <h3 class="preloader__title">sonar</h3>
                    <div class="preloader__with-text ">
                        <div class="preloader__with-text-wrapper">
                            <span data-text-preloader="s" class="preloader__title-2">s</span>
                            <span data-text-preloader="o" class="preloader__title-2">o</span>
                            <span data-text-preloader="n" class="preloader__title-2">n</span>
                            <span data-text-preloader="a" class="preloader__title-2">a</span>
                            <span data-text-preloader="r" class="preloader__title-2">r</span>
                        </div>
                    </div>
                    <p class="preloader__loading">Medya</p>
                    <div id="tp-loading-bar" class="preloader__bar">
                        <div id="tp-loading-line" class="preloader__bar-inner"></div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- /pre loader -->

    <!-- back to top -->
    <div class="back-to-top-wrapper">
        <button id="back_to_top" type="button" class="back-to-top-btn">
            <svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 6L6 1L1 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>
    </div>

    {{-- header --}}
    @include('frontend.includes.header')

    <!-- offcanvas area start -->
    <div class="offcanvas__area offcanvas__area-1">
        <div class="offcanvas__shape">
            <img class="offcanvas__shape-1" src="{{ asset('site/assets/img/shape/offcanvas-shape-1.png') }}"
                alt="">
        </div>

        <div class="offcanvas__wrapper">
            <div class="offcanvas__close">
                <button class="offcanvas__close-btn offcanvas-close-btn">
                    <i class="fa-regular fa-xmark"></i>
                </button>
            </div>

            <div class="offcanvas__content">
                <div class="offcanvas__top mb-40 d-flex justify-content-between align-items-center">
                    <div class="offcanvas__logo logo">
                        <a href="{{ route('site.home') }}">
                            <img src="{{ asset('site/images/sonar-logo-renkli.png') }}" alt="Sonar Medya"
                                style="max-height:64px;height:auto;width:auto">
                        </a>
                    </div>
                </div>

                {{-- Tema JS'nin kopyalayacağı hedef --}}
                <div class="mobile-menu fix mb-40"></div>

                {{-- İstersen büyük ekranda sabit menü --}}
                <div class="offcanvas__menu offcanvas__menu-ff-space d-none d-lg-block">
                    <nav>
                        <ul>
                            <li><a href="{{ route('site.home') }}">Anasayfa</a></li>
                            <li><a href="{{ route('site.about') }}">Hakkımızda</a></li>
                            <li><a href="{{ route('frontend.services.index') }}">Hizmetlerimiz</a></li>
                            <li><a href="{{ route('posts.index') }}">Makaleler</a></li>
                            <li><a href="{{ route('contact.index') }}">İletişim</a></li>
                        </ul>
                    </nav>
                </div>

                <div class="offcanvas__btn">
                    <a href="{{ route('contact.index') }}" class="tp-btn-offcanvas">
                        İletişim <i class="fa-regular fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="body-overlay"></div>
    <!-- offcanvas area end -->

    <div class="body-overlay"></div>

    <main>
        @yield('content')
        @includeWhen(!request()->cookies->has('cookie_consent'), 'frontend.includes.cookie-banner')
    </main>

    {{-- footer --}}
    @include('frontend.includes.footer')

    @include('frontend.includes.js')

    {{-- WhatsApp FAB --}}
    @include('frontend.includes.whatsapp-fab')

    {{-- Body Scripts (panel) --}}
    @if (!empty($site?->body_scripts))
        {!! $site->body_scripts !!}
    @endif
    
</body>

</html>
