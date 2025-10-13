@extends('frontend.layouts.frontend')
@section('content')
    <!-- breadcrumb area start -->
    <section class="breadcrumb__area breadcrumb__style-6 p-relative include-bg pt-200 pb-120">
        <div class="breadcrumb__bg-2 breadcrumb__overlay include-bg"
            data-background="{{ asset('site/assets/img/breadcrumb/breadcrumb-bg-5.jpg') }}"></div>

        @php
            // Başlık: kategori adı > controller'dan gelen metaTitle > fallback
$pageTitle = $category->name ?? ($metaTitle ?? 'Paket Kategorisi');

// Breadcrumb: controller sağladıysa onu kullan; yoksa güvenli varsayılan
$crumbs = $breadcrumbs ?? [
    ['label' => 'Ana Sayfa', 'url' => route('site.home')],
    ['label' => 'Hizmetler', 'url' => route('frontend.services.index')],
    ['label' => $pageTitle, 'url' => null],
            ];
        @endphp

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-8 col-xl-8 col-lg-10">
                    <div class="breadcrumb__content text-center p-relative z-index-1">

                        {{-- Başlık (kategori adı) --}}
                        <h3 class="breadcrumb__title">
                            {{ $pageTitle }}
                        </h3>

                        {{-- Breadcrumb listesi --}}
                        <div class="breadcrumb__list">
                            @foreach ($crumbs as $i => $bc)
                                @if ($i > 0)
                                    <span class="dvdr"><i class="fa-solid fa-circle-small"></i></span>
                                @endif

                                @if (!empty($bc['url']))
                                    <span><a href="{{ $bc['url'] }}">{{ $bc['label'] }}</a></span>
                                @else
                                    <span>{{ $bc['label'] }}</span>
                                @endif
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb area end -->


    <!-- pricing area start -->
    <section class="pricing__area pt-110 pb-120 p-relative z-index-1">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8 col-md-10">
                    <div class="tp-section-wrapper-2 mb-65 text-center">
                        <span class="tp-section-subtitle-2">FİYATLANDIRMA</span>
                        <h3 class="tp-section-title-2 font-70">{{ $category->name }} paket karşılaştırması</h3>
                    </div>
                </div>
            </div>

            <div class="pricing__table pricing__style-2 white-bg">
                <div class="pricing__table-wrapper">

                    {{-- pricing header --}}
                    <div class="pricing__header grey-bg-13">
                        <div class="row gx-0">
                            <div class="col-xl-4 col-4">
                                <div class="pricing__header-content">
                                    <h3 class="pricing__header-title">Bize Ulaşın</h3>
                                    <a href="{{ route('contact.index') }}" class="tp-btn-11">Hemen Başlayın</a>
                                    <img class="pricing-header-shape pricing-header-shape-2"
                                        src="{{ asset('site/assets/img/price/7/price-icon-2.png') }}" alt="">
                                </div>
                            </div>

                            <div class="col-xl-8 col-8">
                                <div class="pricing__header-top-wrapper d-flex align-items-center">

                                    @foreach ($packages as $idx => $pkg)
                                        <div class="pricing__top-7 p-relative text-center">
                                            @if ($idx === 1)
                                                <div class="pricing__popular-2"><span>En Uygun</span></div>
                                            @endif
                                            <div class="pricing__tag-7">
                                                <span>{{ $pkg->name }}</span>
                                            </div>
                                            <div class="pricing__title-wrapper-7">
                                                @php
                                                    $gosterFiyat = $pkg->show_price && filled($pkg->override_price);
                                                @endphp
                                                <h3 class="pricing__title-4">
                                                    @if ($gosterFiyat)
                                                        {{ number_format($pkg->override_price, 2, ',', '.') }} ₺
                                                    @else
                                                        Teklif Alın
                                                    @endif
                                                </h3>
                                                <p>{{ $pkg->short_description ?: 'Pakete özel detaylar için iletişime geçin.' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- pricing features (rows) --}}
                    <div class="pricing__feature-item-wrapper">

                        @forelse($featureServices as $srv)
                            <div class="pricing__feature-info-item">
                                <div class="row gx-0 align-items-center">

                                    <div class="col-xl-4 col-4">
                                        <div class="pricing__feature-info-content d-flex align-items-center">
                                            <div class="pricing__feature-info-details">
                                                <span>
                                                    {{-- info icon --}}
                                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M9 1.5C4.99594 1.5 1.75 4.74594 1.75 8.75C1.75 12.7541 4.99594 16 9 16C13.0041 16 16.25 12.7541 16.25 8.75C16.25 4.74594 13.0041 1.5 9 1.5ZM0.25 8.75C0.25 3.91751 4.16751 0 9 0C13.8325 0 17.75 3.91751 17.75 8.75C17.75 13.5825 13.8325 17.5 9 17.5C4.16751 17.5 0.25 13.5825 0.25 8.75ZM9 7.75C9.55229 7.75 10 8.19771 10 8.75V11.95C10 12.5023 9.55229 12.95 9 12.95C8.44771 12.95 8 12.5023 8 11.95V8.75C8 8.19771 8.44771 7.75 9 7.75ZM9 4.5498C8.44771 4.5498 8 4.99752 8 5.5498C8 6.10209 8.44771 6.5498 9 6.5498H9.008C9.56028 6.5498 10.008 6.10209 10.008 5.5498C10.008 4.99752 9.56028 4.5498 9.008 4.5498H9Z"
                                                            fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <div class="pricing__feature-info-tooltip transition-3">
                                                    <p>{{ $srv->description ?? 'Bu hizmet hakkında detay için iletişime geçin.' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="pricing__feature-info-text">
                                                <p>{{ $srv->name }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-8 col-8">
                                        <div class="pricing__feature-info-wrapper d-flex align-items-center">
                                            @foreach ($packages as $pkg)
                                                @php $has = isset($packageServiceIds[$pkg->id][$srv->id]); @endphp
                                                <div class="pricing__feature-info-available text-center">
                                                    <p>
                                                        <span
                                                            @if (!$has) style="background:#ef4444!important;border-color:#ef4444!important;color:#fff!important" @endif>
                                                            @if ($has)
                                                                <svg width="11" height="9" viewBox="0 0 11 9"
                                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M9.5451 1.27344L3.9201 7.04884L1.36328 4.42366"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                                </svg>
                                                            @else
                                                                <svg width="10" height="10" viewBox="0 0 10 10"
                                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M1 1L9 9" stroke="currentColor"
                                                                        stroke-width="2" stroke-linecap="round" />
                                                                    <path d="M9 1L1 9" stroke="currentColor"
                                                                        stroke-width="2" stroke-linecap="round" />
                                                                </svg>
                                                            @endif
                                                        </span>
                                                    </p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @empty
                            <div class="pricing__feature-info-item">
                                <div class="row gx-0">
                                    <div class="col-12 text-center py-4">
                                        Bu kategoride listelenecek hizmet bulunamadı.
                                    </div>
                                </div>
                            </div>
                        @endforelse

                        {{-- pricing buttons --}}
                        <div class="pricing__footer">
                            <div class="row gx-0">
                                <div class="col-xl-4 col-4">
                                    <div class="pricing__footer-content"></div>
                                </div>
                                <div class="col-xl-8 col-8">
                                    <div class="pricing__btn-wrapper-7 d-flex align-items-center">
                                        @foreach ($packages as $pkg)
                                            <div
                                                class="pricing__btn-7 {{ $loop->index === 1 ? 'price-active' : '' }} text-center">
                                                <a href="{{ route('frontend.services.show', $pkg->slug) }}"
                                                    class="tp-btnr-border-2">Paketi Git</a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- pricing area end -->


    <!-- services area start -->
    <section class="services__area pt-120 pb-125">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    @include('frontend.pages.services.partials.service-detail-side-bar', [
                        // Sidebar’ı hangi moda alacağımızı belirt
                        // 'package-category' | 'package' | 'service'
                        'type' => $type ?? 'package',
                    
                        // Paket detay için (sekme yapısı)
                        'tabs' => $tabs ?? [],
                    
                        // Paket kategori sayfası için (aynı kategorideki paketler)
                        'packages' => $packages ?? [],
                    
                        // Tekil hizmet sayfası için (kategoriler listesi)
                        'serviceCategories' => $serviceCategories ?? collect(),
                    
                        // Seçime göre aktif kayıtlar
                        'service' => $service ?? null,
                        'package' => $package ?? null,
                    
                        // (ops) paket detayında aynı kategorideki akran paketler
                        'pricingPackages' => $pricingPackages ?? [],
                    ])
                </div>

                <div class="col-lg-8 order-first order-lg-last">
                    <div class="services__details-wrapper">

                        @switch($type ?? 'package')
                            {{-- ============ 1) PAKET KATEGORİ SAYFASI ============ --}}
                            @case('package-category')
                                <h3 class="services__details-title">{{ $category->name ?? 'Paket Kategorisi' }}</h3>
                                @if (!empty($category?->description))
                                    <p>{{ $category->description }}</p>
                                @endif>

                                <div class="row">
                                    @forelse(($packages ?? []) as $p)
                                        <div class="col-md-6">
                                            <div class="services__item transition-3 mb-30 fix">
                                                <div class="services__item-inner">
                                                    <div class="services__content">
                                                        <h3 class="services__title">
                                                            <a href="{{ route('frontend.services.show', $p->slug) }}">
                                                                {{ $p->name }}
                                                            </a>
                                                        </h3>
                                                        @if (!empty($p->short_description))
                                                            <p>{{ \Illuminate\Support\Str::limit($p->short_description, 120) }}</p>
                                                        @endif
                                                        <div class="services__btn">
                                                            <a href="{{ route('frontend.services.show', $p->slug) }}"
                                                                class="tp-btn-border">
                                                                Detaylı İncele <i class="fa-regular fa-angle-right"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <p class="text-center">Bu kategoride yayınlanmış paket bulunamadı.</p>
                                        </div>
                                    @endforelse
                                </div>
                            @break

                            {{-- ============ 2) TEKİL HİZMET SAYFASI ============ --}}
                            @case('service')
                                <h3 class="services__details-title">{{ $service->name ?? 'Hizmet' }}</h3>

                                @if (!empty($service?->short_description))
                                    <p>{{ $service->short_description }}</p>
                                @endif

                                @if (!empty($service?->description))
                                    <div class="services__details-text mb-45">
                                        {!! nl2br(e($service->description)) !!}
                                    </div>
                                @endif

                                {{-- İsterseniz hizmete bağlı paket/özellik vb. burada listelenebilir --}}
                            @break

                            {{-- ============ 3) PAKET DETAY SAYFASI (varsayılan) ============ --}}
                            @case('package')

                                @default
                                    {{-- Paket başlığı (dinamik) --}}
                                    <h3 class="services__details-title">{{ $package->name ?? 'Paket' }}</h3>

                                    {{-- Kısa açıklama --}}
                                    @if (!empty($package?->short_description))
                                        <p>{{ $package->short_description }}</p>
                                    @endif

                                    {{-- TAB PANELLERİ: her kök item için bir panel --}}
                                    @foreach ($tabs ?? [] as $tab)
                                        <div id="tab-{{ $tab->id }}"
                                            class="services__tab-panel {{ $loop->first ? 'is-active' : '' }}">
                                            @if (!empty($tab->image_path))
                                                <div class="services__details-thumb m-img">
                                                    <img src="{{ asset('storage/' . $tab->image_path) }}"
                                                        alt="{{ $tab->image_alt ?? $tab->name }}">
                                                </div>
                                            @endif

                                            <div class="services__details-text mb-45">
                                                {{-- Sekme başlığı (dinamik) --}}
                                                <h3 class="services__details-text-title">{{ $tab->name }}</h3>

                                                @if (!empty($tab->description))
                                                    <p>{{ $tab->description }}</p>
                                                @endif
                                            </div>

                                            @php
                                                // Bu tab’a özel “Sunduğumuz Çözüm / Neden Gerekli?” eşlemesi
                                                $solutions = $solutionsById[$tab->id] ?? [];
                                                $necessaryRaw = (string) ($necessaryById[$tab->id] ?? '');
                                                $necessaryLines = array_values(
                                                    array_filter(
                                                        array_map('trim', preg_split("/\r\n|\r|\n/", $necessaryRaw)),
                                                    ),
                                                );
                                                $maxRows = max(count($solutions), count($necessaryLines));
                                            @endphp

                                            <div class="solutions-paired mb-45">
                                                <div class="row align-items-start">
                                                    <div class="col-md-6">
                                                        <h3 class="services__details-list-title">Sunduğumuz Çözüm</h3>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h3 class="services__details-text-title services__details-text-title-2">
                                                            Neden Gerekli?
                                                        </h3>
                                                    </div>
                                                </div>

                                                @if ($maxRows > 0)
                                                    @for ($i = 0; $i < $maxRows; $i++)
                                                        <div class="row g-3 solution-row">
                                                            <div class="col-md-6">
                                                                @if (isset($solutions[$i]) && $solutions[$i] !== '')
                                                                    <div class="sp-item">
                                                                        <span class="sp-dot" aria-hidden="true"></span>
                                                                        <div class="sp-text">{{ $solutions[$i] }}</div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6">
                                                                @if (isset($necessaryLines[$i]) && $necessaryLines[$i] !== '')
                                                                    <p class="mb-0">{{ $necessaryLines[$i] }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endfor
                                                @else
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="text-muted mb-0">Bu sekme için çözüm maddeleri yakında eklenecek.
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="text-muted mb-0">Bu sekme için açıklama yakında eklenecek.</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- SSS --}}
                                    @if (!empty($package) && $package->faqs?->isNotEmpty())
                                        <div class="services__details-faq faq__style-3 mt-60">
                                            <h3 class="services__details-faq-title">Sık Sorulan Sorular</h3>
                                            <div class="faq__tab-content tp-accordion">
                                                <div class="accordion" id="general_accordion">
                                                    @foreach ($package->faqs as $k => $faq)
                                                        @php
                                                            $headingId = 'faqHeading' . $k;
                                                            $collapseId = 'faqCollapse' . $k;
                                                            $isFirst = $k === 0;
                                                        @endphp

                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="{{ $headingId }}">
                                                                <button class="accordion-button {{ $isFirst ? '' : 'collapsed' }}"
                                                                    type="button" data-bs-toggle="collapse"
                                                                    data-bs-target="#{{ $collapseId }}"
                                                                    aria-expanded="{{ $isFirst ? 'true' : 'false' }}"
                                                                    aria-controls="{{ $collapseId }}">
                                                                    {{ $faq->question }}
                                                                </button>
                                                            </h2>
                                                            <div id="{{ $collapseId }}"
                                                                class="accordion-collapse collapse {{ $isFirst ? 'show' : '' }}"
                                                                aria-labelledby="{{ $headingId }}"
                                                                data-bs-parent="#general_accordion">
                                                                <div class="accordion-body">
                                                                    {!! $faq->answer !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @break
                            @endswitch

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- services area end -->

    @endsection
