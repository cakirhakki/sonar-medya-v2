@extends('frontend.layouts.frontend')
@section('content')
    <!-- breadcrumb area start -->
    <section class="breadcrumb__area pt-130 pb-115 breadcrumb__style-10 black-bg p-relative z-index-1">
        <div class="breadcrumb__bg-4 breadcrumb__bg-overlay m-img include-bg"
            data-background="{{ asset('site/images/blog-breadcrumb-slider-3.jpg') }}"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="breadcrumb__content text-center">
                        <h3 class="breadcrumb__title">Makaleler</h3>
                        <div class="breadcrumb__list">
                            <span><a href="{{ route('site.home') }}">Anasayfa</a></span>
                            <span class="dvdr"><i class="fa-solid fa-circle-small"></i></span>
                            <span>Makaleler</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb area end -->

    <!-- postbox area start -->
    <section class="postbox__area grey-bg-4 pt-120 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-xxl-8 col-lg-8">
                    <div class="postbox__wrapper">

                        @forelse($posts as $post)
                            <article class="postbox__item format-image mb-50 transition-3">
                                <div class="postbox__thumb w-img">
                                    <a href="{{ route('posts.show', $post) }}">
                                        @php
                                            // Tercih: önce storage içi için ThumbController croplu görsel,
                                            // değilse harici URL; en sonda bir placeholder.
                                            $img = method_exists($post, 'thumbUrl')
                                                ? $post->thumbUrl(370, 260) ?? $post->featured_image_url
                                                : $post->featured_image_url ??
                                                    asset('site/assets/img/blog/6/blog-1.jpg');
                                        @endphp
                                        <img src="{{ $img }}" alt="{{ $post->featured_image_alt ?? $post->title }}"
                                            width="370" height="260" loading="lazy"
                                            style="object-fit:cover;aspect-ratio:370/260">
                                    </a>
                                </div>

                                <div class="postbox__content">
                                    <div class="postbox__meta">
                                        <span>
                                            <a href="javascript:void(0)">
                                                <svg width="13" height="14" viewBox="0 0 13 14" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M11.6667 13V11.6667C11.6667 10.9594 11.3857 10.2811 10.8856 9.78105C10.3855 9.28095 9.70724 9 9 9H3.66667C2.95942 9 2.28115 9.28095 1.78105 9.78105C1.28095 10.2811 1 10.9594 1 11.6667V13"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M6.33317 6.33333C7.80593 6.33333 8.99984 5.13943 8.99984 3.66667C8.99984 2.19391 7.80593 1 6.33317 1C4.86041 1 3.6665 2.19391 3.6665 3.66667C3.6665 5.13943 4.86041 6.33333 6.33317 6.33333Z"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                {{ optional($post->author)->name ?? 'Yazar' }}
                                            </a>
                                        </span>

                                        <span>
                                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M7.5 14C11.0899 14 14 11.0899 14 7.5C14 3.91015 11.0899 1 7.5 1C3.91015 1 1 3.91015 1 7.5C1 11.0899 3.91015 14 7.5 14Z"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M7.5 3.59961V7.49961L10.1 8.79961" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            {{ optional($post->published_at)->translatedFormat('d F Y') }}
                                        </span>

                                        <span title="Görüntülenme">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"
       xmlns="http://www.w3.org/2000/svg" style="vertical-align:-2px">
    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z" stroke="currentColor" stroke-width="1.5" fill="none"/>
    <circle cx="12" cy="12" r="3.5" stroke="currentColor" stroke-width="1.5" fill="none"/>
  </svg>
  {{ number_format($post->views) }}
</span>

                                    </div>

                                    <h3 class="postbox__title">
                                        <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                                    </h3>

                                    <div class="postbox__text">
                                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?: $post->content), 220) }}
                                        </p>
                                    </div>

                                    <div class="postbox__read-more">
                                        <a href="{{ route('posts.show', $post) }}" class="tp-btn">Devamını Oku</a>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <p class="mb-50">Henüz içerik bulunamadı.</p>
                        @endforelse

                        {{-- Pagination --}}
                        @if ($posts->hasPages())
                            <div class="tp-pagination tp-pagination-style-2 mt-20">
                                <nav>
                                    <ul>
                                        <li>
                                            @if ($posts->onFirstPage())
                                                <span class="tp-pagination-prev prev page-numbers disabled">
                                                    <svg width="16" height="11" viewBox="0 0 16 11" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M6.17749 10.105L1.62499 5.55248L6.17749 0.999981"
                                                            stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M14.3767 5.55249L1.75421 5.55249" stroke="currentColor"
                                                            stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                    Önceki
                                                </span>
                                            @else
                                                <a href="{{ $posts->previousPageUrl() }}"
                                                    class="tp-pagination-prev prev page-numbers">
                                                    <svg width="16" height="11" viewBox="0 0 16 11" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M6.17749 10.105L1.62499 5.55248L6.17749 0.999981"
                                                            stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M14.3767 5.55249L1.75421 5.55249" stroke="currentColor"
                                                            stroke-width="1.5" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    Önceki
                                                </a>
                                            @endif
                                        </li>

                                        @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                                            <li>
                                                @if ($page == $posts->currentPage())
                                                    <span class="current">{{ $page }}</span>
                                                @else
                                                    <a href="{{ $url }}">{{ $page }}</a>
                                                @endif
                                            </li>
                                        @endforeach

                                        <li>
                                            @if ($posts->hasMorePages())
                                                <a href="{{ $posts->nextPageUrl() }}" class="next page-numbers">
                                                    Sonraki
                                                    <svg width="16" height="11" viewBox="0 0 16 11"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9.82422 1L14.3767 5.5525L9.82422 10.105"
                                                            stroke="currentColor" stroke-width="1.5"
                                                            stroke-miterlimit="10" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <path d="M1.625 5.55249H14.2475" stroke="currentColor"
                                                            stroke-width="1.5" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </a>
                                            @else
                                                <span class="next page-numbers disabled">
                                                    Sonraki
                                                    <svg width="16" height="11" viewBox="0 0 16 11"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9.82422 1L14.3767 5.5525L9.82422 10.105"
                                                            stroke="currentColor" stroke-width="1.5"
                                                            stroke-miterlimit="10" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <path d="M1.625 5.55249H14.2475" stroke="currentColor"
                                                            stroke-width="1.5" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        @endif

                    </div>
                </div>

                <div class="col-xxl-4 col-lg-4">
                    <div class="sidebar__wrapper pl-40">
                        @include('frontend.pages.blogs.blog-side-bar')
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- postbox area end -->


@endsection
