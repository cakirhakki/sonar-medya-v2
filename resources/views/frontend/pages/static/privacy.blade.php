@extends('frontend.layouts.frontend')
@section('content')

    <!-- contact area start -->
    <section class="tp-section-area p-relative z-index-1 tp-section-spacing">
        <div class="tp-section-bg include-bg" data-background="{{ asset('site/assets/img/contact/contact-bg.png') }}"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8">
                    <div class="tp-section-wrapper-2 text-center">
                        <h3 class="tp-section-title-2 font-70">Gizlilik Politikası</h3>
                        <p>
                            Gizliliğiniz önemlidir. Bu metin, Sonar Medya’nın
                            web sitesi ve işlettiği diğer alanlarda sizden toplanabilecek
                            kişisel verilerin hangi amaçlarla, hangi hukuki sebeplerle
                            işlendiğini ve haklarınızı açıklar.
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
                                Sonar Medya olarak kişisel verilerinizin güvenliğine önem veriyoruz.
                                Bu Gizlilik Politikası; 6698 sayılı KVKK ve ilgili mevzuat kapsamında,
                                hizmetlerimizden yararlanırken bize sağladığınız bilgilerin nasıl toplandığını,
                                kullanıldığını, saklandığını ve korunduğunu açıklar.
                            </p>
                            <p>
                                Sitemizi kullanmakla, burada belirtilen uygulamaları kabul etmiş olursunuz.
                                Koşullarımız hakkında daha fazla bilgi için Kullanım Şartları sayfamıza bakabilirsiniz.
                            </p>
                        </div>

                        <div class="policy__item policy__item-2 mb-35">
                            <h3 class="policy__title">Toplanan Bilgiler</h3>
                            <p>
                                Sitemizi yalnızca ziyaret ederek kimliğinizi açık eden bilgi paylaşmadan da
                                gezinebilirsiniz. Ancak bazı hizmetlerin çalışabilmesi için aşağıdaki türde
                                veriler toplanabilir:
                            </p>
                            <ul>
                                <li>İletişim bilgileri (ad, e-posta, telefon vb.)</li>
                                <li>Kullanım verileri ve tanımlama bilgileri (IP, cihaz, tarayıcı bilgisi, çerezler)</li>
                                <li>Talep ve destek kayıtları (iletişim formları, teklif talepleri)</li>
                            </ul>
                            <p>
                                Kamuya açık kaynaklardan elde edilen veya anonimleştirilmiş veriler
                                kişisel veri sayılmaz.
                            </p>
                        </div>

                        <div class="policy__list mb-35">
                            <h3 class="policy__title">Kişisel Verilerin Kullanım Amaçları</h3>
                            <ul>
                                <li>Hizmetlerin sunulması, geliştirilmesi ve bakımının yapılması</li>
                                <li>Destek taleplerinin yanıtlanması ve müşteri ilişkileri yönetimi</li>
                                <li>Güvenlik, hata tespiti ve suiistimalin önlenmesi</li>
                                <li>Yasal yükümlülüklerin yerine getirilmesi</li>
                                <li>Tercihlerinize uygun içerik ve bildirimlerin iletilmesi (onay vermeniz halinde)</li>
                            </ul>
                        </div>

                        <div class="policy__item mb-35">
                            <h3 class="policy__title">Saklama Süreleri ve Aktarımlar</h3>
                            <p>
                                Veriler, amaç için gerekli süre boyunca ve yasal zorunluluklar ölçüsünde saklanır.
                                Hizmet sağlayıcılarımıza ve hukuken yetkili kurumlara, gerektiğinde ve ölçülülük
                                ilkesine uygun olarak aktarım yapılabilir.
                            </p>
                        </div>

                        <div class="policy__item mb-35">
                            <h3 class="policy__title">Haklarınız</h3>
                            <p>
                                KVKK uyarınca; verilerinize erişme, düzeltme, silme, işlemeyi kısıtlama,
                                itiraz etme ve açık rızayı geri çekme haklarına sahipsiniz.
                                Başvurularınızı aşağıdaki kanallardan iletebilirsiniz.
                            </p>
                        </div>

                        <div class="policy__contact">
                            <h3 class="policy__title policy__title-2">İletişim</h3>
                            <p>Her zaman bizimle iletişime geçebilirsiniz:</p>

                            <ul>
                                <li>
                                    E-posta:
                                    <span>
                                        <a href="mailto:{{ $site->email ?? 'info@sonarmedya.com' }}">
                                            {{ $site->email ?? 'info@sonarmedya.com' }}
                                        </a>
                                    </span>
                                </li>
                                @if (!empty($site?->phone))
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
