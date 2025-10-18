@extends('frontend.layouts.frontend')

@section('content')
    <!-- breadcrumb area start -->
    <section class="breadcrumb__area breadcrumb__style-6 p-relative include-bg pt-200 pb-120">
        <div class="breadcrumb__bg-2 breadcrumb__overlay include-bg"
            data-background="{{ asset('site/assets/img/breadcrumb/breadcrumb-bg-6.jpg') }}"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-8 col-xl-8 col-lg-10">
                    <div class="breadcrumb__content text-center p-relative z-index-1">
                        <h3 class="breadcrumb__title">{{ $category->name }}</h3>
                        <div class="breadcrumb__list">
                            @foreach ($breadcrumbs as $i => $bc)
                                <span>
                                    @if (!empty($bc['url']))
                                        <a href="{{ $bc['url'] }}">{{ $bc['label'] }}</a>
                                    @else
                                        {{ $bc['label'] }}
                                    @endif
                                </span>
                                @if ($i < count($breadcrumbs) - 1)
                                    <span class="dvdr"><i class="fa-solid fa-circle-small"></i></span>
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
    <section class="services__area pt-100 pb-90">
        <div class="container">

            @php
                /** @var \Illuminate\Support\Collection|\Illuminate\Contracts\Pagination\Paginator $services */
                $hasAny = isset($services) && count($services) > 0;
            @endphp

            @if (!$hasAny)
                <div class="row">
                    <div class="col-12">
                        <p class="text-center">Henüz hizmet tanımlanmamış.</p>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-12">
                        <h2 class="mb-30">{{ $category->name }}</h2>
                    </div>
                </div>

                <div class="row">
                    @foreach ($services as $service)
                        @php
                            $label = $service->name ?? 'Hizmet';
                            $img = $service->icon_image_path ?? null;
                            $cls = $service->icon_class ?? null;
                            $imgUrl = null;

                            if (method_exists($service, 'getFirstMediaUrl')) {
                                $imgUrl = $service->getFirstMediaUrl('images') ?: null;
                            }

                            if (!$imgUrl && $img) {
                                $imgUrl = \Illuminate\Support\Str::startsWith($img, ['http://', 'https://'])
                                    ? $img
                                    : \Illuminate\Support\Facades\Storage::url($img);
                            }

                            $desc =
                                $service->summary ??
                                ($service->short_description ?? ($service->excerpt ?? ($service->description ?? '')));
                        @endphp

                        <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-6">
                            <div class="services__item transition-3 mb-30 fix wow fadeInUp" data-wow-delay=".3s"
                                data-wow-duration="1s">
                                <div class="services__shape">
                                    <img class="services__shape-1"
                                        src="{{ asset('site/assets/img/services/shape/services-shape-1.png') }}"
                                        alt="">
                                    <img class="services__shape-2"
                                        src="{{ asset('site/assets/img/services/shape/services-shape-2.png') }}"
                                        alt="">
                                </div>

                                <div class="services__item-inner">
                                    <div class="services__icon">
                                        <span>
                                            @if ($imgUrl)
                                                <img src="{{ $imgUrl }}" alt="{{ $label }}"
                                                    style="width:40px;height:40px;object-fit:contain;">
                                            @elseif($cls)
                                                <i class="{{ $cls }}"
                                                    style="font-size:40px;line-height:40px;"></i>
                                            @else
                                                <i class="fa-regular fa-star" style="font-size:40px;line-height:40px;"></i>
                                            @endif
                                        </span>
                                    </div>

                                    <div class="services__content">
                                        <h3 class="services__title">
                                            <a
                                                href="{{ route('frontend.services.show', $service->slug) }}">{{ $label }}</a>
                                        </h3>

                                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($desc), 120) }}</p>

                                        <div class="services__btn">
                                            <a href="{{ route('frontend.services.show', $service->slug) }}"
                                                class="tp-btn-border">
                                                Detaylı İncele <i class="fa-regular fa-angle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($services instanceof \Illuminate\Contracts\Pagination\Paginator)
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center mt-20">
                            {{ $services->withQueryString()->links() }}
                        </div>
                    </div>
                @endif
            @endif

        </div>
    </section>
    <!-- services area end -->




    <!-- subscribe area start -->
    <section class="subscribe__area p-relative z-index-1">
        <div class="subscribe__bg"></div>
        <div class="container">
            <div class="subscribe__inner-14">
                <div class="row gx-0 align-items-center">
                    <div class="col-xl-5 col-lg-5">
                        <h3 class="subscribe__title-14">Join our Newsletter</h3>
                    </div>
                    <div class="col-xl-7 col-lg-7">
                        <div class="subscribe__form-14">
                            <form action="#">
                                <input type="email" placeholder="Your Email (required)">
                                <button type="submit">Subscribe Me</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- subscribe area end -->
@endsection
