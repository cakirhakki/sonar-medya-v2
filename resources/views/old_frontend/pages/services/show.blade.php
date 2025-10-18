@extends('frontend.layouts.frontend')
@section('content')


<!-- breadcrumb area start -->
<section class="breadcrumb__area breadcrumb__style-6 p-relative include-bg pt-200 pb-120">
  <div class="breadcrumb__bg-2 breadcrumb__overlay include-bg"
       data-background="{{ asset('site/assets/img/breadcrumb/breadcrumb-bg-5.jpg') }}"></div>

  @php
    $pageTitle = $service->name ?? 'Hizmet Detayı';

    // Controller'dan geldiyse onu kullan, gelmediyse güvenli fallback oluştur
    $crumbs = $breadcrumbs ?? [
        ['label' => 'Ana Sayfa', 'url' => route('site.home')],
        ['label' => 'Hizmetler', 'url' => route('frontend.services.index')],
        ['label' => $category?->name, 'url' => $category ? route('frontend.services.category', $category->slug) : null],
        ['label' => $pageTitle, 'url' => null],
    ];
  @endphp

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xxl-8 col-xl-8 col-lg-10">
        <div class="breadcrumb__content text-center p-relative z-index-1">
          <h3 class="breadcrumb__title">{{ $pageTitle }}</h3>

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

<!-- services area start -->
<section class="services__area pt-120 pb-125">
  <div class="container">
    <div class="row">
      <div class="col-lg-4">
        {{-- Paket mantığı yok; include bozulmasın diye type=service ve boş tabs veriyoruz --}}
        @include('frontend.pages.services.partials.service-detail-side-bar', [
            'type' => 'service',
            'tabs' => [],
        ])
      </div>

      <div class="col-lg-8 order-first order-lg-last">
        <div class="services__details-wrapper">

          {{-- Başlık --}}
          <h3 class="services__details-title">{{ $service->name }}</h3>

          @php
            // Görsel önceliği: Spatie Media -> icon_image_path -> yoksa null
            $imgUrl = null;
            if (method_exists($service, 'getFirstMediaUrl')) {
                $imgUrl = $service->getFirstMediaUrl('images') ?: null;
            }
            if (!$imgUrl && !empty($service->icon_image_path)) {
                $img = $service->icon_image_path;
                $imgUrl = \Illuminate\Support\Str::startsWith($img, ['http://','https://'])
                    ? $img
                    : \Illuminate\Support\Facades\Storage::url($img);
            }

            $summary = $service->summary ?? ($service->short_description ?? null);
            $content = $service->description ?? ($service->content ?? null);
          @endphp

          {{-- Özet --}}
          @if($summary)
            <p>{{ $summary }}</p>
          @endif

          {{-- Kapak görseli --}}
          @if($imgUrl)
            <div class="services__details-thumb m-img mb-30">
              <img src="{{ $imgUrl }}" alt="{{ $service->name }}" loading="lazy">
            </div>
          @endif

          {{-- Detay içerik --}}
          @if($content)
            <div class="services__details-text mb-45">
              {!! $content !!}
            </div>
          @endif

          {{-- SSS: Service -> faqs ilişkisi varsayılır --}}
          @if(method_exists($service, 'faqs') && $service->faqs->isNotEmpty())
            <div class="services__details-faq faq__style-3 mt-60">
              <h3 class="services__details-faq-title">Sık Sorulan Sorular</h3>

              <div class="faq__tab-content tp-accordion">
                <div class="accordion" id="service_faq_accordion">
                  @foreach ($service->faqs as $k => $faq)
                    @php
                      $headingId = 'faqHeading_'.$k;
                      $collapseId = 'faqCollapse_'.$k;
                      $isFirst = $k === 0;
                    @endphp

                    <div class="accordion-item">
                      <h2 class="accordion-header" id="{{ $headingId }}">
                        <button class="accordion-button {{ $isFirst ? '' : 'collapsed' }}"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#{{ $collapseId }}"
                                aria-expanded="{{ $isFirst ? 'true' : 'false' }}"
                                aria-controls="{{ $collapseId }}">
                          {{ $faq->question }}
                        </button>
                      </h2>
                      <div id="{{ $collapseId }}"
                           class="accordion-collapse collapse {{ $isFirst ? 'show' : '' }}"
                           aria-labelledby="{{ $headingId }}"
                           data-bs-parent="#service_faq_accordion">
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

        </div>
      </div>
    </div>
  </div>
</section>
<!-- services area end -->

@endsection
