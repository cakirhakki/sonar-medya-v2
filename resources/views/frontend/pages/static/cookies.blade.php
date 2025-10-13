@extends('frontend.layouts.frontend')

@section('content')
    <!-- contact area start -->
    <section class="tp-section-area p-relative z-index-1 tp-section-spacing">
        <div class="tp-section-bg include-bg" data-background="{{ asset('site/assets/img/contact/contact-bg.png') }}"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8">
                    <div class="tp-section-wrapper-2 text-center">
                        <h3 class="tp-section-title-2 font-70">Çerez Politikası</h3>
                        <p>
                            Bu sayfa, Sonar Medya web sitesinde kullanılan çerezler hakkında bilgi verir.
                            Siteyi kullanarak bu politikayı kabul etmiş olursunuz.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- contact area end -->

    <!-- policy area start -->
    <section class="policy__area pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="policy__wrapper policy__translate p-relative z-index-1">

                        <div class="policy__item mb-35">
                            <h4 class="policy__meta">Yürürlük tarihi: {{ now()->format('d.m.Y') }}</h4>
                            <p>
                                Bu Çerez Politikası, <strong>Sonar Medya</strong>’nın ({{ $site->company_name ?? 'Sonar Medya Yazılım ve Danışmanlık A.Ş.' }})
                                çerezleri nasıl kullandığını açıklar. Çerezler, cihazınıza yerleştirilen küçük metin dosyalarıdır.
                            </p>
                        </div>

                        <div class="policy__item mb-35">
                            <h3 class="policy__title">Çerez Nedir?</h3>
                            <p>
                                Çerezler; siteyi çalıştırmak, tercihleri hatırlamak, istatistik tutmak ve
                                deneyimi iyileştirmek için kullanılır. Oturum çerezleri tarayıcı kapanınca silinir,
                                kalıcı çerezler belirli bir süre cihazda kalır.
                            </p>
                        </div>

                        <div class="policy__list mb-35">
                            <h3 class="policy__title">Kullandığımız Çerez Türleri</h3>
                            <ul>
                                <li><strong>Zorunlu çerezler:</strong> Güvenlik ve temel işlevler için gereklidir.</li>
                                <li><strong>Tercih çerezleri:</strong> Dil ve bölge gibi ayarları hatırlar.</li>
                                <li><strong>Analitik çerezler:</strong> Trafik ve kullanım istatistiklerini ölçer.</li>
                                <li><strong>Pazarlama çerezleri:</strong> İlgi alanına göre içerik ve reklam sunar.</li>
                            </ul>
                        </div>

                        <div class="policy__item mb-35">
                            <h3 class="policy__title">Üçüncü Taraf Çerezleri</h3>
                            <p>
                                Google Analytics, Meta Pixel vb. hizmetler çerez yerleştirebilir. Bu sağlayıcıların
                                politikaları kendi sitelerinde yayınlanır ve değişebilir.
                            </p>
                        </div>

                        <div class="policy__item mb-35">
                            <h3 class="policy__title">Çerez Yönetimi</h3>
                            <p>
                                Tarayıcı ayarlarından çerezleri kabul edebilir, reddedebilir veya silebilirsiniz.
                                Reddetmeniz hâlinde sitenin bazı bölümleri düzgün çalışmayabilir.
                            </p>
                        </div>

                        <div class="policy__item mb-35">
                            <h3 class="policy__title">Açık Rıza</h3>
                            <p>
                                Zorunlu olmayan çerezler için rızanız talep edilir. Rızanızı dilediğiniz an
                                tarayıcı ayarları veya çerez tercihleriniz üzerinden geri çekebilirsiniz.
                            </p>
                        </div>

                        <div class="policy__item mb-35">
                            <h3 class="policy__title">Değişiklikler</h3>
                            <p>
                                Bu politika zaman zaman güncellenebilir. Değişiklikler yayımlandığı anda yürürlüğe girer.
                            </p>
                        </div>

                        <div class="policy__contact">
                            <h3 class="policy__title policy__title-2">İletişim</h3>
                            <p>Sorular için bize ulaşın:</p>
                            <ul>
                                <li>
                                    E-posta:
                                    <span>
                                        <a href="mailto:{{ $site->email ?? 'info@sonarmedya.com' }}">
                                            {{ $site->email ?? 'info@sonarmedya.com' }}
                                        </a>
                                    </span>
                                </li>
                                @if(!empty($site?->phone))
                                    <li>Telefon: <span>{{ $site->phone }}</span></li>
                                @endif
                            </ul>
                            <div class="policy__address">
                                @php
                                    $map = $site->google_map_url ?? '#';
                                    $addr = $site->address ?? 'İstanbul, Türkiye';
                                    $company = $site->company_name ?? 'Sonar Medya Yazılım ve Danışmanlık A.Ş.';
                                @endphp
                                <p>
                                    <a href="{{ $map }}" target="_blank" rel="noopener">
                                        {{ $company }}<br>{{ $addr }}
                                    </a>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- policy area end -->
@endsection
