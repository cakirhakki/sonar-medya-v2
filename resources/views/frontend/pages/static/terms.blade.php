@extends('frontend.layouts.frontend')

@section('content')
    <!-- contact area start -->
    <section class="tp-section-area p-relative z-index-1 tp-section-spacing">
        <div class="tp-section-bg include-bg" data-background="{{ asset('site/assets/img/contact/contact-bg.png') }}"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8">
                    <div class="tp-section-wrapper-2 text-center">
                        <h3 class="tp-section-title-2 font-70">Kullanım Şartları</h3>
                        <p>
                            Bu sayfa, Sonar Medya web sitesini ve sunduğumuz hizmetleri kullanırken
                            geçerli olan temel koşulları açıklar. Siteyi kullanarak bu şartları kabul etmiş sayılırsınız.
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
                                Bu Kullanım Şartları (“Şartlar”), <strong>Sonar Medya</strong>’nın web sitesine ve
                                dijital varlıklarına erişiminizi ve kullanımınızı düzenler. Şartlar, yürürlükteki
                                mevzuat ile birlikte değerlendirilir.
                            </p>
                        </div>

                        <div class="policy__item mb-35">
                            <h3 class="policy__title">Hizmetlerin Kullanımı</h3>
                            <p>
                                Siteyi hukuka uygun, başkalarının haklarına saygılı biçimde kullanmayı kabul edersiniz.
                                Tersine mühendislik, sistemlere izinsiz erişim girişimleri, zararlı yazılım yayma ve
                                spam gibi eylemler yasaktır.
                            </p>
                        </div>

                        <div class="policy__item mb-35">
                            <h3 class="policy__title">İçerik ve Fikri Mülkiyet</h3>
                            <p>
                                Sitedeki tüm metin, görsel, logo ve yazılımlar ilgili hak sahiplerine aittir.
                                Yazılı izin olmaksızın çoğaltılamaz, dağıtılamaz veya türev eser yapılamaz.
                            </p>
                        </div>

                        <div class="policy__list mb-35">
                            <h3 class="policy__title">Sorumluluk Reddi</h3>
                            <ul>
                                <li>Site “olduğu gibi” ve “mevcut hâliyle” sunulur.</li>
                                <li>Kesinti, hata veya veri kaybından doğan dolaylı zararlardan sorumlu olmayız.</li>
                                <li>Harici bağlantıların içeriğinden ilgili üçüncü taraf sorumludur.</li>
                            </ul>
                        </div>

                        <div class="policy__item mb-35">
                            <h3 class="policy__title">Değişiklikler</h3>
                            <p>
                                Şartları zaman zaman güncelleyebiliriz. Değişiklikler yayımlandığı anda yürürlüğe girer.
                                Güncel sürümü düzenli olarak kontrol etmeniz gerekir.
                            </p>
                        </div>

                        <div class="policy__item mb-35">
                            <h3 class="policy__title">Uygulanacak Hukuk ve Yetki</h3>
                            <p>
                                İşbu Şartlar, Türkiye Cumhuriyeti kanunlarına tabidir. Uyuşmazlıklarda İstanbul mahkemeleri ve icra daireleri yetkilidir.
                            </p>
                        </div>

                        <div class="policy__contact">
                            <h3 class="policy__title policy__title-2">İletişim</h3>
                            <p>Sorularınız için bize ulaşın:</p>
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
