@extends('frontend.layouts.frontend')
@section('content')
    <!-- about top area start -->
    <section class="about__heading about__heading-overlay about__spacing include-bg jarallax"
        data-background="{{ asset('site/assets/img/about/about-breadcrumb.jpg') }}">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">
                    <div class="about__heading-content text-center p-relative z-index-1">
                        <span class="about__heading-subtitle">Hakkımızda</span>
                        <h3 class="about__heading-title">Bir dijital büyüme ortağı mı arıyorsunuz? Doğru yerdesiniz.</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- about top area end -->

    <!-- about text area start -->
    <section class="about__text pt-115 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-4">
                    <div class="about__text-wrapper wow fadeInUp" data-wow-delay=".3s" data-wow-duration="1s">
                        <h3 class="about__text-title">Bir fikirle başladık,<br>bugün markaları büyütüyoruz.</h3>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8">
                    <div class="about__text wow fadeInUp" data-wow-delay=".6s" data-wow-duration="1s">
                        <p>Sonar Medya, dijital dünyada işletmelerin daha görünür, ölçülebilir ve sürdürülebilir olmasını
                            sağlamak için kurulmuştur.
                            Veri odaklı yaklaşımlar, güçlü tasarım ve performans pazarlamasıyla markalara büyüme
                            stratejileri geliştiririz.</p>

                        <p>Her projeye özgü çözümler üretir, dönüşüm odaklı stratejilerle markaların hedeflerine ulaşmasına
                            yardımcı oluruz.
                            Teknoloji, yaratıcılık ve analitik bakış açısını birleştiririz.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- about text area end -->


    <!-- services kategori area start -->
    <section class="services__area pb-110">
        <div class="container">
            <div class="row">
                @forelse ($categories as $cat)
                    <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6">
                        <div class="services__item-9 services__item-style-2 mb-30 transition-3">
                            <div class="services__item-9-top d-flex align-items-start justify-content-between">
                                <div class="services__icon-9">
                                    <span>
                                        <svg width="47" height="42" viewBox="0 0 47 42" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M5.06035 29.4262L12.5801 37.0285C17.8613 42.3677 19.6652 42.2798 24.8812 37.0285L36.9867 24.79C41.203 20.5275 42.2679 17.6931 36.9867 12.3539L29.467 4.75152C23.838 -0.939241 21.3821 0.488942 17.1659 4.75152L5.06035 16.99C-0.133935 22.2633 -0.568603 23.7354 5.06035 29.4262Z"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path opacity="0.4"
                                                d="M38.5975 32.1286L37.1631 34.5236C35.1419 37.9292 36.7067 40.7197 40.6405 40.7197C44.5742 40.7197 46.139 37.9292 44.1178 34.5236L42.6834 32.1286C41.5533 30.239 39.7059 30.239 38.5975 32.1286Z"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path opacity="0.4"
                                                d="M1.21484 22.1312C13.2986 18.8134 26.0344 18.7036 38.1616 21.8456L39.2483 22.1312"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <img src="assets/img/services/9/services-icon-shape.png" alt="">
                                    </span>
                                </div>
                                <div class="services__btn-9">
                                    <a href="{{ route('frontend.services.category', ['category' => $cat->slug]) }}"><i
                                            class="fa-light fa-arrow-up-right"></i></a>
                                </div>
                            </div>
                            <div class="services__content-9">
                                <span class="services-project">{{ $cat->services_count ?? 0 }} Hizmet</span>
                                <h3 class="services__title-9">
                                    <a
                                        href="{{ route('frontend.services.category', ['category' => $cat->slug]) }}">{{ $cat->name }}</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="mb-0">Gösterilecek kategori bulunamadı.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- services area end -->


    <!-- about gallery slider area start -->
    <x-about-gallery /> {{-- ya da: <x-about-gallery limit="8" /> --}}

    <!-- about gallery slider area end -->

    <!-- vision-mission area (award block reused) start -->
    <section class="award__area pt-120 pb-120 white-bg">
        <div class="container">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="section__title-wrapper mb-55 text-center">
                        <h3 class="section__title">Vizyon, Misyon ve Yaklaşımımız</h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-12">
                    <div class="award__item-wrapper-9 award__style-2">

                        <!-- Vizyon -->
                        <div class="award__item-9 p-relative wow fadeInUp" data-wow-delay=".2s" data-wow-duration="1s">
                            <div class="row align-items-center">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <div class="award__topic">
                                        <p>Vizyon</p>
                                    </div>
                                </div>
                                <div class="col-xl-7 col-lg-7 col-md-7">
                                    <div class="award__content-9">
                                        <h3 class="award__title-9">
                                            <span class="tp-img-reveal tp-img-reveal-item"
                                                data-img="{{ asset('site/assets/img/award/9/award-1.jpg') }}"
                                                data-fx="1">Markaların sürdürülebilir büyümesini veriyle
                                                hızlandırmak</span>
                                        </h3>
                                        <p>Performans pazarlama, e-ticaret altyapısı ve otomasyonla ölçeklenebilir sonuç
                                            üretmek.</p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Misyon -->
                        <div class="award__item-9 p-relative wow fadeInUp" data-wow-delay=".35s" data-wow-duration="1s">
                            <div class="row align-items-center">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <div class="award__topic">
                                        <p>Misyon</p>
                                    </div>
                                </div>
                                <div class="col-xl-7 col-lg-7 col-md-7">
                                    <div class="award__content-9">
                                        <h3 class="award__title-9">
                                            <span class="tp-img-reveal tp-img-reveal-item"
                                                data-img="{{ asset('site/assets/img/award/9/award-2.jpg') }}"
                                                data-fx="1">Uçtan uca e-ticaret ve performans çözümleri sunmak</span>
                                        </h3>
                                        <p>Kurulumdan kreatife, medya satın almadan entegrasyonlara kadar tek noktadan
                                            hizmet.</p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Yaklaşım -->
                        <div class="award__item-9 p-relative wow fadeInUp" data-wow-delay=".5s" data-wow-duration="1s">
                            <div class="row align-items-center">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <div class="award__topic">
                                        <p>Yaklaşım</p>
                                    </div>
                                </div>
                                <div class="col-xl-7 col-lg-7 col-md-7">
                                    <div class="award__content-9">
                                        <h3 class="award__title-9">
                                            <span class="tp-img-reveal tp-img-reveal-item"
                                                data-img="{{ asset('site/assets/img/award/9/award-3.jpg') }}"
                                                data-fx="1">Veri odaklı, hızlı deney-test döngüsü</span>
                                        </h3>
                                        <p>Net KPI’lar, haftalık sprintler, şeffaf raporlama ve sürekli optimizasyon.</p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Değerler -->
                        <div class="award__item-9 p-relative wow fadeInUp" data-wow-delay=".65s" data-wow-duration="1s">
                            <div class="row align-items-center">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <div class="award__topic">
                                        <p>Değerler</p>
                                    </div>
                                </div>
                                <div class="col-xl-7 col-lg-7 col-md-7">
                                    <div class="award__content-9">
                                        <h3 class="award__title-9">
                                            <span class="tp-img-reveal tp-img-reveal-item"
                                                data-img="{{ asset('site/assets/img/award/9/award-4.jpg') }}"
                                                data-fx="1">Şeffaflık, sahiplenme, sonuç</span>
                                        </h3>
                                        <p>Raporlar açık, kararlar veriye dayanır, vaat değil çıktı teslim ederiz.</p>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div><!-- /.award__item-wrapper-9 -->
                </div>
            </div>
        </div>
    </section>
    <!-- vision-mission area end -->


    <!-- faq area start -->
    <section class="faq__area p-relative">
        <!-- Statik görsel (tam kapla) -->
        <div class="faq__video" data-background="{{ asset('site/assets/img/faq/faq-img.jpg') }}">
            <!-- İstersen CTA bırak: -->
            <div class="faq__video-btn">
                <a href="{{ route('contact.index') }}" class="tp-pulse-border" aria-label="İletişime geçin">
                    <svg width="22" height="18" viewBox="0 0 22 18" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.7334 1L21 9.00007L12.7334 17" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M1 8.99756H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row justify-content-end">
                <div class="col-xxl-7 col-xl-7 col-lg-7">
                    <div class="faq__wrapper-2 faq__gradient-border faq__style-2 tp-accordion pl-160">
                        <div class="faq__title-wrapper">
                            <span class="faq__title-pre">Sorularınızı hızlıca yanıtlayalım</span>
                            <h3 class="faq__title">Akıllı ve esnek dijital hizmetler</h3>
                        </div>

                        <div class="accordion" id="faqaccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Hizmeti ne kadar sürede aktif ediyorsunuz?
                                        <span class="accordion-btn"></span>
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show"
                                    aria-labelledby="headingOne" data-bs-parent="#faqaccordion">
                                    <div class="accordion-body">
                                        <p>Paket ve entegrasyon kapsamına göre değişir. Standart kurulum 3–10 iş günü içinde
                                            tamamlanır.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Raporlama ve iletişim süreci nasıl işliyor?
                                        <span class="accordion-btn"></span>
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                    data-bs-parent="#faqaccordion">
                                    <div class="accordion-body">
                                        <p>Haftalık sprint, aylık özet ve panel erişimi sağlarız. KPI’lar belirlenir ve
                                            şeffaf raporlanır.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">
                                        Minimum sözleşme süresi var mı?
                                        <span class="accordion-btn"></span>
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse"
                                    aria-labelledby="headingThree" data-bs-parent="#faqaccordion">
                                    <div class="accordion-body">
                                        <p>Genelde 3 ay öneririz. Kısa pilot çalışmalar da yapılabilir; ihtiyaçlarınıza göre
                                            şekillendiririz.</p>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /accordion -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- faq area end -->


    <!-- team area start -->
    <section class="team__area pt-110 pb-110">
        <div class="container">
            <div class="row align-items-end">
                <div class="col-xxl-5 col-xl-5 col-lg-6 col-md-6 col-sm-7">
                    <div class="section__title-wrapper mb-60">
                        <h3 class="section__title">
                            Tek <span class="section__title-highlight">Ekip
                                <svg width="220" height="27" viewBox="0 0 220 27" fill="none">
                                    <path class="wow"
                                        d="M19.64 15.29C33.45 12.46 47.28 10.30 61.24 8.63C48.61 9.07 35.99 9.33 23.37 9.29c-.9 0-1.64-.74-1.64-1.64s.74-1.64 1.64-1.64c37.45.12 115.07-2.43 152.45-4.31 9.97-.51 19.72-.95 29.69-1.21 2.55-.07 6.98-.29 7.47-.26 1.36.09 1.56 1.27 1.58 1.46.05.43.01 1.08-.76 1.57-.06.04-.35.23-1.01.31-37.04 4.41-114.47 3.85-151.55 7.96-15.6 1.74-31.01 4.06-46.39 7.21-5.37 1.1-8.43 1.79-12.71 2.95 7.07 1.86 14.32 2.9 21.63 3.46 0 0-9.58 2.58-16.84 1-7.25-1.55-7.42-1.72-11.05-2.91-.84-.27-1.1-.79-1.21-1.1-.23-.59-.2-1.23.41-1.82.17-.16.49-.39.98-.55.64-.2 1.97-.41 2.56-.57 5.9-1.63 9.07-2.33 15.55-3.65Z"
                                        fill="currentColor" />
                                </svg>
                            </span>, Çok Yetkinlik
                        </h3>
                    </div>
                </div>
                <div class="col-xxl-7 col-xl-7 col-lg-6 col-md-6 col-sm-5">
                    <div class="team__join mb-70 text-sm-end">
                        <a href="{{ route('contact.index') }}" class="tp-link-btn-2">
                            İletişime Geçin
                            <span>
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 7H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M7 1L13 7L7 13" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Kart 1 -->
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                    <div class="team__item">
                        <div class="team__thumb w-img fix transition-3">
                            <div class="tp-thumb-overlay wow"></div>
                            <a href="{{ route('frontend.services.category', 'dijital-pazarlama') }}">
                                <img src="{{ asset('site/images/reklam-pazarlama.jpg') }}" alt="Reklam ve Performans">
                            </a>
                        </div>
                        <div class="team__content">
                            <h3 class="team__title">
                                <a href="{{ route('frontend.services.category', 'dijital-pazarlama') }}">Reklam &
                                    Performans</a>
                            </h3>
                            <span class="team__designation">Google / Meta / TikTok / YouTube</span>
                        </div>
                    </div>
                </div>

                <!-- Kart 2 -->
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                    <div class="team__item">
                        <div class="team__thumb w-img fix transition-3">
                            <div class="tp-thumb-overlay wow"></div>
                            <a href="{{ route('frontend.services.category', 'e-ticaret-yonetimi') }}">
                                <img src="{{ asset('site/images/e-ticaret-yonetimi.jpg') }}" alt="E-ticaret Yönetimi">
                            </a>
                        </div>
                        <div class="team__content">
                            <h3 class="team__title">
                                <a href="{{ route('frontend.services.category', 'e-ticaret-yonetimi') }}">E-Ticaret
                                    Yönetimi</a>
                            </h3>
                            <span class="team__designation">Altyapı, entegrasyon, otomasyon</span>
                        </div>
                    </div>
                </div>

                <!-- Kart 3 -->
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                    <div class="team__item">
                        <div class="team__thumb w-img fix transition-3">
                            <div class="tp-thumb-overlay wow"></div>
                            <a href="{{ route('frontend.services.category', 'teknik-seo') }}">
                                <img src="{{ asset('site/images/teknik-seo.jpg') }}" alt="Teknik SEO">
                            </a>
                        </div>
                        <div class="team__content">
                            <h3 class="team__title">
                                <a href="{{ route('frontend.services.category', 'teknik-seo') }}">Teknik SEO</a>
                            </h3>
                            <span class="team__designation">CWV, indexleme, şema</span>
                        </div>
                    </div>
                </div>

                <!-- Kart 4 -->
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                    <div class="team__item">
                        <div class="team__thumb w-img fix transition-3">
                            <div class="tp-thumb-overlay wow"></div>
                            <a href="{{ route('frontend.services.category', 'yazilim-ve-mobil-uygulama') }}">
                                <img src="{{ asset('site/images/yazilim-mobil.jpg') }}" alt="Yazılım & Mobil">
                            </a>
                        </div>
                        <div class="team__content">
                            <h3 class="team__title">
                                <a href="{{ route('frontend.services.category', 'yazilim-ve-mobil-uygulama') }}">Yazılım &
                                    Mobil</a>
                            </h3>
                            <span class="team__designation">Özel yazılım, API, mobil</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- team area end -->


    <!-- brand area start -->
    <x-brand-sliders split="alternate" />
    <!-- brand area end -->


    <!-- cta area start -->
<section class="cta__area cta__style-2 p-relative z-index-1">
    <div class="cta__half-bg"></div>
    <div class="container">
        <div class="cta__inner-5" data-bg-color="blue-dark">
            <div class="cta__shape-bg include-bg" data-background="{{ asset('site/assets/img/cta/5/cta-bg.png') }}">
            </div>
            <div class="row align-items-center">
                <div class="col-xxl-8 col-xl-8 col-lg-8">
                    <div class="cta__content-5">
                        <span>Sonar Medya ile Tanışın</span>
                        <h3 class="cta__title-5">Bir sonraki iş hedefinizi konuşalım</h3>
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-4 col-lg-4">
                    <div class="cta__btn-5 text-lg-end">
                        <a href="{{ route('contact.index') }}" class="tp-btn-orange-2">İletişime Geçin</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- cta area end -->
@endsection
