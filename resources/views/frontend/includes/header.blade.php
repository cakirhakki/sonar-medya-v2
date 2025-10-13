<header>
    <div class="header__area">
        <div class="header__bottom-6 header__padding-6 header-box-plr-245 header__sticky header__sticky-white"
            id="header-sticky">
            <div class="container-fluid">
                <div class="mega-menu-wrapper p-relative">
                    <div class="row align-items-center">
                        <div class="col-xxl-3 col-xl-2 col-lg-2 col-md-4 col-sm-5 col-8">
                            <div class="logo">
                                <a href="{{ route('site.home') }}">
                                    <img class="site-logo" src="{{ asset('site/images/sonar-logo-renkli.png') }}"
                                        alt="Sonar Medya">
                                </a>
                            </div>
                        </div>
                        <div class="col-xxl-7 col-xl-8 col-lg-7 d-none d-lg-block">
                            <div class="main-menu main-menu-6">
                                <nav id="mobile-menu">
                                    <ul>
                                        {{-- <li class="has-dropdown">
                                            <a href="home-main.html">Demos</a>
                                            <ul class="submenu">
                                                <li><a href="home-main.html">Main Home</a></li>
                                                <li><a href="home-lawyer.html">Lawyer</a></li>
                                                <li><a href="home-freelancer.html">Freelancer</a></li>
                                                <li><a href="home-agency.html">Digital Agency</a></li>
                                                <li><a href="home-photographer.html">Photographer</a></li>
                                                <li><a href="home-startup.html">Startup</a></li>
                                                <li><a href="home-creative.html">Creative Agency</a></li>
                                                <li><a href="home-portfolio.html">Personal Portfolio</a></li>
                                                <li><a href="home-architecture.html">Architechture</a></li>
                                                <li><a href="home-vertical.html">Vertical Slider</a></li>
                                                <li><a href="home-politician.html">Politician</a></li>
                                                <li><a href="home-shop.html">Minimal Shop</a></li>
                                                <li><a href="home-swipper.html">Swipper Slider</a></li>
                                            </ul>
                                        </li>
                                        <li class="has-dropdown has-mega-menu">
                                            <a href="about.html">Elements</a>
                                            <ul class="mega-menu">
                                                <li>
                                                    <a href="javasript:void(0);" class="mega-menu-title">Widget</a>
                                                    <ul>
                                                        <li>
                                                            <a href="elements-accordion.html">Accordion</a>
                                                        </li>
                                                        <li>
                                                            <a href="elements-blog-post.html">Blog Posts</a>
                                                        </li>
                                                        <li>
                                                            <a href="elements-button.html">Button</a>
                                                        </li>
                                                        <li>
                                                            <a href="elements-cta.html">Call to Action</a>
                                                        </li>
                                                        <li>
                                                            <a href="elements-carousel.html">Carousel</a>
                                                        </li>
                                                        <li>
                                                            <a href="elements-brand.html">Clients Logo</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <a href="javasript:void(0);" class="mega-menu-title">Widget</a>
                                                    <ul>
                                                        <li>
                                                            <a href="elements-form.html">Contact Form</a>
                                                        </li>
                                                        <li>
                                                            <a href="elements-gallery.html">Gallery</a>
                                                        </li>
                                                        <li>
                                                            <a href="elements-map.html">Google Map</a>
                                                        </li>
                                                        <li>
                                                            <a href="elements-heading.html">Heading</a>
                                                        </li>
                                                        <li>
                                                            <a href="elements-iconbox.html">Icon Box</a>
                                                        </li>
                                                        <li>
                                                            <a href="elements-instagram.html">Instagram Feed</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <a href="javasript:void(0);" class="mega-menu-title">Widget</a>
                                                    <ul>

                                                        <li>
                                                            <a href="elements-parallax.html">Parallax</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">Portfolio Video</a>
                                                        </li>
                                                        <li>
                                                            <a href="elements-pricing.html">Pricing Table</a>
                                                        </li>
                                                        <li>
                                                            <a href="elements-skill.html">Progress Bar</a>
                                                        </li>
                                                        <li>
                                                            <a href="elements-process.html">Process</a>
                                                        </li>
                                                        <li>
                                                            <a href="elements-team.html">Team Member</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <a href="javasript:void(0);" class="mega-menu-title">Widget</a>
                                                    <ul>

                                                        <li>
                                                            <a href="elements-tab.html">Tabs</a>
                                                        </li>
                                                        <li>
                                                            <a href="elements-shop.html">Shop Category</a>
                                                        </li>
                                                        <li>
                                                            <a href="elements-testimonial.html">Testimonial</a>
                                                        </li>
                                                        <li>
                                                            <a href="elements-social.html">Social Icons</a>
                                                        </li>
                                                        <li>
                                                            <a href="elements-subscribe.html">Subscribe Form</a>
                                                        </li>
                                                        <li>
                                                            <a href="elements-video.html">Video</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="has-dropdown">
                                            <a href="portfolio.html">Protfolio</a>

                                            <ul class="submenu">
                                                <li><a href="portfolio.html">Classic Style</a></li>
                                                <li><a href="portfolio-2.html">Grid 3 Columns</a></li>
                                                <li><a href="portfolio-gallery.html">Gallery Style</a></li>
                                                <li><a href="portfolio-masonary.html">Masonary Full</a></li>
                                                <li><a href="portfolio-metro.html">Metro Style</a></li>
                                                <li><a href="portfolio-slider.html">Slider Style</a></li>
                                                <li><a href="portfolio-details.html">Classic Details</a></li>
                                                <li><a href="portfolio-details-list.html">List With Details</a></li>
                                                <li><a href="portfolio-details-slider.html">Slider with Details</a></li>
                                                <li><a href="portfolio-details-video.html">Video With Details</a></li>
                                            </ul>
                                        </li> --}}
                                        <li>
                                            <a href="{{ route('site.home') }}">Anasayfa</a>

                                        </li>
                                        <li>
                                            <a href="{{ route('site.about') }}">Hakkımızda</a>

                                        </li>

                                        {{-- <li class="has-dropdown">
                                            <a href="{{ route('frontend.service-packages.index') }}">Paketler</a>
                                            @php $pkgs = $menuServicePackages ?? collect(); @endphp

                                            @if ($pkgs->isNotEmpty())
                                                <ul class="submenu">
                                                    @foreach ($pkgs as $pkg)
                                                        @php
                                                            $label =
                                                                $pkg->menu_label ??
                                                                ($pkg->category->name ?? ($pkg->name ?? 'Paket'));

                                                            $url =
                                                                $pkg->menu_url ??
                                                                (isset($pkg->category->slug)
                                                                    ? route(
                                                                        'frontend.package-categories.show',
                                                                        $pkg->category->slug,
                                                                    )
                                                                    : (isset($pkg->slug)
                                                                        ? route(
                                                                            'frontend.service-packages.show',
                                                                            $pkg->slug,
                                                                        )
                                                                        : route('frontend.service-packages.index')));
                                                        @endphp
                                                        <li><a href="{{ $url }}">{{ $label }}</a></li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li> --}}
                                        <li class="has-dropdown has-mega-menu">
    <a href="{{ route('frontend.services.index') }}">Hizmetlerimiz</a>

    @php
        $cats = ($menuServiceCategories ?? collect());
        $limit = 3;
        $cols = $cats->take($limit);
        $hasMore = $cats->count() > $limit;
    @endphp

    @if ($cats->isNotEmpty())
        <ul class="mega-menu">
            @foreach ($cols as $cat)
                <li>
                    <a href="{{ route('frontend.services.category', ['category' => $cat->slug]) }}" class="mega-menu-title">
                        {{ $cat->name }}
                    </a>
                    @php $services = collect($cat->services); @endphp
                    <ul>
                        @forelse ($services as $svc)
                            <li><a href="{{ route('frontend.services.show', $svc->slug) }}">{{ $svc->name }}</a></li>
                        @empty
                            <li><span class="opacity-60">Şu an listelenecek hizmet yok</span></li>
                        @endforelse
                    </ul>
                </li>
            @endforeach

            @if ($hasMore)
                <li>
                    <span class="mega-menu-title">Diğer</span>
                    <ul>
                        <li>
                            <a href="{{ route('frontend.services.index') }}">
                                Tüm kategoriler ve hizmetler →
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    @endif
</li>



                                        <li class="has-dropdown">
                                            <a href="{{ route('posts.index') }}">Makaleler</a>

                                            <ul class="submenu">
                                                @forelse($navBlogCategories ?? [] as $cat)
                                                    {{-- Her kategori bir alt-dropdown olsun --}}
                                                    <li class="has-dropdown">
                                                        <a
                                                            href="{{ route('posts.index', ['kategori' => $cat->slug]) }}">
                                                            {{ $cat->name }}
                                                        </a>

                                                        <ul class="submenu">
                                                            @forelse($cat->posts as $p)
                                                                <li>
                                                                    <a href="{{ route('posts.show', $p) }}">
                                                                        {{ \Illuminate\Support\Str::limit($p->title, 60) }}
                                                                    </a>
                                                                </li>
                                                            @empty
                                                                <li><a href="javascript:void(0)">Henüz içerik yok</a>
                                                                </li>
                                                            @endforelse
                                                        </ul>
                                                    </li>
                                                @empty
                                                    <li><a href="javascript:void(0)">Kategori bulunamadı</a></li>
                                                @endforelse
                                            </ul>
                                        </li>


                                        <li>
                                            <a href="{{ route('contact.index') }}">İletişim</a>

                                        </li>
                                    </ul>
                                </nav>
                                <!-- for wp -->
                                <div class="header__hamburger ml-50 d-none">
                                    <button type="button" class="hamburger-btn offcanvas-open-btn">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-2 col-xl-2 col-lg-3 col-md-8 col-sm-7 col-4">
                            <div class="header__bottom-right-6 d-flex justify-content-end align-items-center pl-30">
                                <div class="header__btn-6 d-none d-sm-block">
                                    <a href="{{ route('contact.index') }}" class="tp-btn-blue-2 tp-link-btn-3">
                                        İletişim
                                        <span>
                                            <i class="fa-regular fa-arrow-right"></i>
                                        </span>
                                    </a>
                                </div>
                                <div class="header__hamburger ml-50 d-lg-none">
                                    <button type="button" class="hamburger-btn hamburger-btn-black offcanvas-open-btn">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
