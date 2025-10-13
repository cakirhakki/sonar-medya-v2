<footer>
    <div class="footer__area p-relative z-index-1" data-bg-color="footer-bg-black-2">
        <div class="footer__bg" data-background="{{ asset('site/assets/img/footer/footer-shape-2.jpg') }}"></div>
        <div class="footer__top-6">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="footer__widget-6 mb-50 footer-col-6-1">
                            <div class="footer__logo">
                                <a href="{{ route('site.home') }}" class="d-inline-block">
                                    <img src="{{ $site->logo_url ?? asset('site/assets/img/logo/logo.svg') }}"
                                        alt="{{ $site->site_title ?? 'Logo' }}" class="img-fluid sm-footer-logo">
                                </a>
                            </div>

                            <div class="footer__widget-content">
                                <div class="footer__info">
                                    <p>{{ $siteFooter['description'] ?? '' }}</p>

                                    {{-- <a href="job.html" class="tp-link-btn-2 tp-link-btn-white">
                                        We are Hiring
                                        <span>
                                            <i class="fa-regular fa-arrow-right"></i>
                                        </span>
                                    </a> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="footer__widget-6 mb-50 footer-col-6-2">
                            <h3 class="footer__widget-title-6">Quick Link</h3>

                            <div class="footer__widget-content">
                                <ul>
                                    <li><a href="{{ route('site.home') }}">Anasayfa</a></li>
                                    <li><a href="{{ route('frontend.services.index') }}">Hizmetlerimiz</a></li>
                                    <li><a href="{{ route('posts.index') }}">Makaleler</a></li>
                                    <li><a href="{{ route('contact.index') }}">İletişim</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-2 col-xl-2 col-lg-3 col-md-6 col-sm-6">
                        <div class="footer__widget-6 mb-50 footer-col-6-3">
                            <h3 class="footer__widget-title-6">Hizmetlerimiz</h3>

                            <div class="footer__widget-content">
                                @php
                                    $cats = $menuServiceCategories ?? collect();
                                    $max = 5; // header örneğine paralel, ilk 5 kayıt
                                    $list = $cats->take($max);
                                @endphp
                                <ul>
                                    @forelse ($list as $cat)
                                        <li>
                                             <a href="{{ route('frontend.services.category', ['category' => $cat->slug]) }}">
                            {{ $cat->name }}
                        </a>
                                        </li>
                                    @empty
                                        <li><a href="javascript:void(0)">Kategori bulunamadı</a></li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-4 col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="footer__widget-6 mb-50 footer-col-6-4">
                            <h3 class="footer__widget-title-6">Contact Us</h3>

                            <div class="footer__widget-content">
                                <div class="footer__info">
                                    @php
                                        $addr = $siteFooter['contact']['address'] ?? null;
                                        $mapUrl = $siteFooter['contact']['map_url'] ?? null;

                                        // E-posta ve telefonlar AppServiceProvider'da hazır [{display, href}] olarak geliyor
$email = $siteFooter['contact']['email'] ?? null; // ['display' => 'info@...', 'href' => 'mailto:...']
$phone = $siteFooter['contact']['phone'] ?? null; // ['display' => '+90 ...',  'href' => 'tel:+90...']
$mobile = $siteFooter['contact']['mobile'] ?? null; // opsiyonel, varsa göstereceğiz
                                    @endphp

                                    {{-- Adres --}}
                                    @if ($addr)
                                        <div class="footer__info-item-6 d-flex align-items-start">
                                            <div class="footer__info-icon-6 mr-20">
                                                <span>
                                                    <svg width="16" height="18" viewBox="0 0 16 18"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M8.1121 10.1466C9.53315 10.1466 10.6851 9.02879 10.6851 7.64994C10.6851 6.2711 9.53315 5.15332 8.1121 5.15332C6.69105 5.15332 5.53906 6.2711 5.53906 7.64994C5.53906 9.02879 6.69105 10.1466 8.1121 10.1466Z"
                                                            stroke="white" stroke-width="1.5" />
                                                        <path
                                                            d="M1.2002 6.1933C2.82484 -0.736432 13.4057 -0.72843 15.022 6.2013C15.9704 10.2663 13.3644 13.7072 11.08 15.8357C9.42239 17.3881 6.79986 17.3881 5.13398 15.8357C2.85783 13.7072 0.251802 10.2583 1.2002 6.1933Z"
                                                            stroke="white" stroke-width="1.5" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="footer__info-text-6">
                                                @if ($mapUrl)
                                                    <a class="text-white" target="_blank"
                                                        href="{{ $mapUrl }}">{{ $addr }}</a>
                                                @else
                                                    <span class="text-white">{{ $addr }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    {{-- E-posta --}}
                                    @if ($email)
                                        <div class="footer__info-item-6 d-flex align-items-start">
                                            <div class="footer__info-icon-6 mr-20">
                                                <span>
                                                    <svg width="16" height="14" viewBox="0 0 16 14"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M11.5 12.9H4.5C2.4 12.9 1 11.85 1 9.4V4.5C1 2.05 2.4 1 4.5 1H11.5C13.6 1 15 2.05 15 4.5V9.4C15 11.85 13.6 12.9 11.5 12.9Z"
                                                            stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path
                                                            d="M11.5 4.85059L9.309 6.60059C8.588 7.17459 7.405 7.17459 6.684 6.60059L4.5 4.85059"
                                                            stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="footer__info-text-6">
                                                <a href="{{ $email['href'] }}">{{ $email['display'] }}</a>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Telefon (varsa mobil ile birlikte) --}}
                                    @if ($phone || $mobile)
                                        <div class="footer__info-item-6 d-flex align-items-start">
                                            <div class="footer__info-icon-6 mr-20">
                                                <span>
                                                    <svg width="16" height="16" viewBox="0 0 16 16"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M14.979 12.431C14.979 12.683 14.923 12.942 14.804 13.194C14.685 13.446 14.531 13.684 14.328 13.908C13.985 14.286 13.607 14.559 13.18 14.734C12.76 14.909 12.305 15 11.815 15C11.101 15 10.338 14.832 9.533 14.489C8.728 14.146 7.923 13.684 7.125 13.103C6.32 12.515 5.557 11.864 4.829 11.143C4.108 10.415 3.457 9.652 2.876 8.854C2.302 8.056 1.84 7.258 1.504 6.467C1.168 5.669 1 4.906 1 4.178C1 3.702 1.084 3.247 1.252 2.827C1.42 2.4 1.686 2.008 2.057 1.658C2.505 1.217 2.995 1 3.513 1C3.709 1 3.905 1.042 4.08 1.126C4.262 1.21 4.423 1.336 4.549 1.518L6.173 3.807C6.299 3.982 6.39 4.143 6.453 4.297C6.516 4.444 6.551 4.591 6.551 4.724C6.551 4.892 6.502 5.06 6.404 5.221C6.313 5.382 6.18 5.55 6.012 5.718L5.48 6.271C5.403 6.348 5.368 6.439 5.368 6.551C5.368 6.607 5.375 6.656 5.389 6.712C5.41 6.768 5.431 6.81 5.445 6.852C5.571 7.083 5.788 7.384 6.096 7.748C6.411 8.112 6.747 8.483 7.111 8.854C7.489 9.225 7.853 9.568 8.224 9.883C8.588 10.191 8.889 10.401 9.127 10.527C9.162 10.541 9.204 10.562 9.253 10.583C9.309 10.604 9.365 10.611 9.428 10.611C9.547 10.611 9.638 10.569 9.715 10.492L10.247 9.967C10.422 9.792 10.59 9.659 10.751 9.575C10.912 9.477 11.073 9.428 11.248 9.428C11.381 9.428 11.521 9.456 11.675 9.519C11.829 9.582 11.99 9.673 12.165 9.792L14.482 11.437C14.664 11.563 14.79 11.71 14.867 11.885C14.937 12.06 14.979 12.235 14.979 12.431Z"
                                                            stroke="white" stroke-width="1.5" />
                                                        <path
                                                            d="M12.5497 5.89986C12.5497 5.47986 12.2207 4.83586 11.7307 4.31086C11.2827 3.82786 10.6877 3.44986 10.0997 3.44986"
                                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <path d="M14.9997 5.9C14.9997 3.191 12.8087 1 10.0997 1"
                                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="footer__info-text-6">
                                                @if ($phone)
                                                    <a href="{{ $phone['href'] }}">{{ $phone['display'] }}</a>
                                                @endif
                                                @if ($phone && $mobile)
                                                    <span class="mx-2">/</span>
                                                @endif
                                                @if ($mobile)
                                                    <a href="{{ $mobile['href'] }}">{{ $mobile['display'] }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="footer__social-6">
                                    @php
                                        // ikon sınıfı eşlemeleri (Font Awesome Pro)
                                        $icons = [
                                            'facebook' => 'facebook-f',
                                            'twitter' => 'twitter',
                                            'instagram' => 'instagram',
                                            'linkedin' => 'linkedin-in',
                                            'youtube' => 'youtube',
                                        ];
                                    @endphp

                                    @foreach ($siteFooter['social'] ?? [] as $network => $url)
                                        @php $icon = $icons[$network] ?? $network; @endphp
                                        <a href="{{ $url }}" target="_blank" rel="noopener"
                                            aria-label="{{ ucfirst($network) }}">
                                            <i class="fa-brands fa-{{ $icon }}"></i>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer__bottom">
            <div class="container">
                <div class="footer__bottom-inner-6">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="footer__copyright-6">
                                <p>{!! $siteFooter['copyright'] ??
                                    '<a href="' . e(route('site.home')) . '">© ' . date('Y') . ' Sonar Medya</a> All Rights Reserved.' !!}</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
  <div class="footer__link-6 text-sm-end">
    <a href="{{ route('legal.privacy') }}">Gizlilik Politikası</a>
    <a href="{{ route('legal.terms') }}">Kullanım Şartları</a>
    <a href="{{ route('legal.cookies') }}">Çerez Politikası</a>
  </div>
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
