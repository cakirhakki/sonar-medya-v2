@extends('frontend.layouts.frontend')
@section('content')
    <!-- contact area start -->
    <section class="tp-section-area p-relative z-index-1 tp-section-spacing">
        <div class="tp-section-bg include-bg" data-background="assets/img/contact/contact-bg.png"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="tp-section-wrapper-2 text-center">
                        <span class="tp-section-subtitle-2 subtitle-mb-9">BİZİ TANIYIN</span>
                        <h3 class="tp-section-title-2 font-70">Bir projeniz mi var? Hadi konuşalım.</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- contact area end -->

    @php
        /** @var \App\Models\SiteSetting|null $site */
        $email = $site?->email;
        $telHref = $site?->primary_tel_href;
        $telDisplay = $site?->primary_tel_display;

        $address = $site?->address;
        $mapUrl = $site?->google_map_url;

        // Sosyal: footer’daki ikon eşlemesini aynen kullanalım
        $icons = [
            'facebook' => 'facebook-f',
            'twitter' => 'twitter',
            'instagram' => 'instagram',
            'linkedin' => 'linkedin-in',
            'youtube' => 'youtube',
        ];
        $social = array_filter([
            'facebook' => $site?->facebook,
            'twitter' => $site?->twitter,
            'instagram' => $site?->instagram,
            'linkedin' => $site?->linkedin,
            'youtube' => $site?->youtube,
        ]);
    @endphp

    <div class="contact__item-area contact__translate-2">
        <div class="container">
            <div class="row">

                <!-- Contact -->
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="contact__item text-center mb-30 transition-3 white-bg">
                        <div class="contact__icon">
                            <img src="{{ asset('site/assets/img/contact/icon/contact-icon-1.png') }}" alt="">
                        </div>
                        <div class="contact__content">
                            <span class="contact-item-subtitle">İletişim</span>
                            @if ($email)
                                <p><a href="mailto:{{ strtolower($email) }}">{{ $email }}</a></p>
                            @endif
                            @if ($telHref && $telDisplay)
                                <p><a href="{{ $telHref }}">{{ $telDisplay }}</a></p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="contact__item text-center mb-30 transition-3 white-bg">
                        <div class="contact__icon">
                            <img src="{{ asset('site/assets/img/contact/icon/contact-icon-3.png') }}" alt="">
                        </div>
                        <div class="contact__content">
                            <span class="contact-item-subtitle">Konum</span>
                            @if ($address)
                                <p>
                                    @if ($mapUrl)
                                        <a href="{{ $mapUrl }}" target="_blank" rel="noopener">
                                            {{ $address }}
                                        </a>
                                    @else
                                        {{ $address }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="contact__item text-center mb-30 transition-3 white-bg">
                        <div class="contact__icon">
                            <img src="{{ asset('site/assets/img/contact/icon/contact-icon-2.png') }}" alt="">
                        </div>
                        <div class="contact__content">
                            <span class="contact-item-subtitle">Sosyal Medya</span>
                            {{-- <p>Follow on social media</p> --}}
                            <div class="contact__social">
                                @foreach ($social as $network => $url)
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


    <!-- contact form area start -->
    <section class="contact__form-area pt-90">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    @include('frontend.pages.contacts.form-page', [
                        'title' => 'Bize mesaj gönderin',
                        'button' => 'Mesajı Gönder',
                    ])

                </div>
            </div>
        </div>
    </section>
    <!-- contact form area end -->

    <!-- contact location area start -->
    <section class="contact__location-area pb-130 pt-110">
        @php
            /** @var \App\Models\SiteSetting|null $site */
            // Çoklu ofis varsa kullan, yoksa tek adres fallback
            $locations = [];
            if (is_array($site?->offices ?? null) && count($site->offices)) {
                $locations = $site->offices; // her eleman: ['name','email','phone','address','map']
            } elseif ($site?->address) {
                $locations = [
                    [
                        'name' => $site?->company_name ?: 'Office',
                        'email' => $site?->email,
                        'phone' => $site?->primary_tel_display,
                        'address' => $site?->address,
                        'map' => $site?->google_map_url,
                    ],
                ];
            }
        @endphp

        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-lg-7 offset-xl-1 col-md-8">
                    <div class="tp-section-wrapper-2 mb-35">
                        <span class="tp-section-subtitle-2 subtitle-mb-9">OFİSİMİZ</span>
                        <h3 class="tp-section-title-2 font-40">Merkez ofisimizi ziyaret edin</h3>
                    </div>
                </div>
            </div>

            @if (!empty($locations))
                <div class="row justify-content-center">
                    <div class="col-xl-10 ">
                        <div class="contact__location-wrapper">
                            @foreach ($locations as $i => $office)
                                @php
                                    $name = $office['name'] ?? 'Office';
                                    $email = $office['email'] ?? null;
                                    $phone = $office['phone'] ?? null;
                                    $address = $office['address'] ?? null;
                                    $map = $office['map'] ?? null;

                                    // tel: href normalize
                                    $telHref = null;
                                    if ($phone) {
                                        $digits = preg_replace('/\D+/', '', $phone);
                                        if (strlen($digits) === 10) {
                                            $digits = '90' . $digits;
                                        } elseif (strlen($digits) === 11 && str_starts_with($digits, '0')) {
                                            $digits = '9' . substr($digits, 1);
                                        } elseif (str_starts_with($digits, '00')) {
                                            $digits = substr($digits, 2);
                                        }
                                        $telHref = $digits ? 'tel:+' . $digits : null;
                                    }

                                    // ikon görselini 1..3 döndür
                                    $imgIndex = ($i % 3) + 1;
                                @endphp

                                <div class="contact__location-item">
                                    <div class="row align-items-center">
                                        <div class="col-lg-9 col-md-8 col-sm-7">
                                            <div class="contact__location-content d-lg-flex align-items-center">

                                                <h3 class="contact__location-title">{{ $name }}</h3>

                                                <div class="contact__location-info d-sm-flex flex-wrap align-items-center">
                                                    <div class="contact__location-icon mr-45">
                                                        <img src="{{ asset('site/assets/img/contact/contact-location-' . $imgIndex . '.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="contact__location-content">
                                                        @if ($email)
                                                            <p><a
                                                                    href="mailto:{{ strtolower($email) }}">{{ $email }}</a>
                                                            </p>
                                                        @endif
                                                        @if ($telHref && $phone)
                                                            <p><a href="{{ $telHref }}">{{ $phone }}</a></p>
                                                        @elseif($phone)
                                                            <p>{{ $phone }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-5">
                                            <div class="contact__location-btn text-sm-end">
                                                @if ($map ?? false)
                                                    <a href="{{ $map }}" target="_blank" rel="noopener"
                                                        class="tp-btn-border">Haritada Göster</a>
                                                @elseif($address)
                                                    <a href="https://www.google.com/maps/search/{{ urlencode($address) }}"
                                                        target="_blank" rel="noopener" class="tp-btn-border">Haritada
                                                        Göster</a>
                                                @else
                                                    <a href="#" class="tp-btn-border disabled"
                                                        aria-disabled="true">Haritada Göster</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- contact location area end -->

@endsection
