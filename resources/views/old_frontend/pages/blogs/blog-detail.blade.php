@extends('frontend.layouts.frontend')
@section('content')
    <!-- breadcrumb area start -->
    <section class="breadcrumb__area include-bg pb-70 pt-120 grey-bg-4">
        <div class="container">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="breadcrumb__content p-relative z-index-1">

                        <div class="postbox__category">
                            @php
                                $cat = optional($post->primaryCategory);
                            @endphp
                            @if ($cat && $cat->slug)
                                <a href="{{ route('posts.index', ['kategori' => $cat->slug]) }}">{{ $cat->name }}</a>
                            @else
                                <a href="{{ route('posts.index') }}">Blog</a>
                            @endif
                        </div>

                        <h3 class="breadcrumb__title">
                            {!! e($post->title) !!}
                        </h3>

                        <div class="breadcrumb__list">
                            <span><a href="{{ url('/') }}">Anasayfa</a></span>
                            <span class="dvdr"><i class="fa-solid fa-circle-small"></i></span>

                            @if ($cat && $cat->slug)
                                <span>
                                    <a href="{{ route('posts.index', ['kategori' => $cat->slug]) }}">{{ $cat->name }}</a>
                                </span>
                                <span class="dvdr"><i class="fa-solid fa-circle-small"></i></span>
                            @else
                                <span><a href="{{ route('posts.index') }}">Blog</a></span>
                                <span class="dvdr"><i class="fa-solid fa-circle-small"></i></span>
                            @endif

                            <span>{{ $post->title }}</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb area end -->


    <!-- postbox details area start -->
    <section class="postbox__area grey-bg-4 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="postbox__wrapper">
                        <div class="postbox__top">
                            <div class="postbox__thumb m-img mb-55">
                                <img src="{{ $post->thumbUrl(1200, 630) ?? ($post->featured_image_url ?? asset('assets/img/blog/blog-big-1.jpg')) }}"
                                    alt="{{ $post->featured_image_alt ?: $post->title }}" width="1200" height="630">
                                @if ($post->featured_image_caption || $post->featured_image_credit_text)
                                    <h5 class="postbox__img-caption">
                                        {{ $post->featured_image_caption }}
                                        @if ($post->featured_image_credit_text)
                                            — <a href="{{ $post->featured_image_credit_url ?: '#' }}" target="_blank"
                                                rel="noopener">
                                                {{ $post->featured_image_credit_text }}
                                            </a>
                                        @endif
                                    </h5>
                                @endif
                            </div>
                        </div>
                        @php
                            $yorumSayisi = $post->allComments()->approved()->count();
                            $yayimTarihi = optional($post->published_at)
                                ?->locale(app()->getLocale())
                                ->translatedFormat('j F Y');
                        @endphp
                        <div class="postbox__main">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="postbox__main-wrapper">

                                        <div class="postbox__meta-wrapper d-flex align-items-center flex-wrap">
                                            <div class="postbox__meta-item mb-30">
                                                <div class="postbox__meta-author d-flex align-items-center">
                                                    <div class="postbox__meta-author-thumb">
                                                        <a href="javascript:void(0)">
                                                            <img src="{{ optional($post->author)?->thumbAvatar(48, 48) ?? (optional($post->author)?->avatar_url ?? asset('assets/img/users/user-12.jpg')) }}"
                                                                alt="{{ optional($post->author)?->name ?? 'Yazar' }}">
                                                        </a>
                                                    </div>
                                                    <div class="postbox__meta-content">
                                                        <span class="postbox__meta-type">Yazar</span>
                                                        <p class="postbox__meta-name">
                                                            {{ optional($post->author)->name ?? 'Yazar' }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="postbox__meta-item mb-30">
                                                <div class="postbox__meta-content">
                                                    <span class="postbox__meta-type">Yayınlanma</span>
                                                    <p class="postbox__meta-name">{{ $yayimTarihi }}</p>
                                                </div>
                                            </div>

                                            {{-- Yorum sayacın yoksa bu bloğu gösteriş amaçlı bırakıyoruz --}}
                                            <div class="postbox__meta-item mb-30">
                                                <div class="postbox__meta-content">
                                                    <span class="postbox__meta-type">Yorumlar</span>
                                                    <p class="postbox__meta-name">
                                                        <a href="#tp-blog-details-comment">
                                                            {{ $yorumSayisi }} yorum — Sohbete Katılın
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="postbox__meta-item mb-30">
                                                <div class="postbox__meta-content">
                                                    <span class="postbox__meta-type">Görüntülenme</span>
                                                    <p class="postbox__meta-name">{{ number_format($post->views) }}
                                                        görüntülenme</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="postbox__details-content-wrapper">
                                            <h3 class="postbox__details-title">
                                                {!! e($post->title) !!}
                                            </h3>

                                            {{-- Zengin içerik (RichText) --}}
                                            {!! $post->content !!}

                                            {{-- Etiket listesi (tema yapısına uygun ayrı bölüm aşağıda zaten var) --}}
                                        </div>

                                        <div
                                            class="postbox__more-navigation white-bg d-none d-md-flex justify-content-between flex-wrap mb-40">
                                            @php
                                                // Controller'dan $prev / $next geliyor
$prevUrl = $prev ? route('posts.show', $prev) : 'javascript:void(0)';
$nextUrl = $next ? route('posts.show', $next) : 'javascript:void(0)';
                                            @endphp

                                            {{-- Önceki --}}
                                            <div class="postbox__more-left d-flex align-items-center">
                                                <div class="postbox__more-icon">
                                                    <a href="{{ $prevUrl }}" aria-label="Önceki yazı"
                                                        @class(['opacity-50 pointer-events-none' => !$prev])>
                                                        <svg width="8" height="14" viewBox="0 0 8 14" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg" role="img"
                                                            aria-hidden="true">
                                                            <path
                                                                d="M7 12.9718L2.06061 8.04401C1.47727 7.46205 1.47727 6.50975 2.06061 5.92778L7 1"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-miterlimit="10" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </a>
                                                </div>
                                                <div class="postbox__more-content">
                                                    <p>Önceki Yazı</p>
                                                    <h4>
                                                        @if ($prev)
                                                            <a href="{{ $prevUrl }}">{{ $prev->title }}</a>
                                                        @else
                                                            <span>—</span>
                                                        @endif
                                                    </h4>
                                                </div>
                                            </div>

                                            {{-- Sonraki --}}
                                            <div class="postbox__more-right d-flex align-items-center">
                                                <div class="postbox__more-content">
                                                    <p>Sonraki Yazı</p>
                                                    <h4>
                                                        @if ($next)
                                                            <a href="{{ $nextUrl }}">{{ $next->title }}</a>
                                                        @else
                                                            <span>—</span>
                                                        @endif
                                                    </h4>
                                                </div>
                                                <div class="postbox__more-icon">
                                                    <a href="{{ $nextUrl }}" aria-label="Sonraki yazı"
                                                        @class(['opacity-50 pointer-events-none' => !$next])>
                                                        <svg width="8" height="14" viewBox="0 0 8 14" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg" role="img"
                                                            aria-hidden="true">
                                                            <path
                                                                d="M1 12.9718L5.93939 8.04401C6.52273 7.46205 6.52273 6.50975 5.93939 5.92778L1 1"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-miterlimit="10" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="postbox__share-wrapper mb-60">
                                            <div class="row align-items-center">
                                                <div class="col-xl-7">
                                                    <div class="tagcloud tagcloud-sm">
                                                        <span>Tags:</span>

                                                        @forelse($post->tags as $t)
                                                            <a href="{{ route('posts.index', ['etiket' => $t->slug] + request()->except('page', 'q')) }}"
                                                                title="{{ $t->name }}">{{ $t->name }}</a>
                                                        @empty
                                                            <a href="javascript:void(0)">—</a>
                                                        @endforelse
                                                    </div>
                                                </div>
                                                <div class="col-xl-5">
                                                    <div class="postbox__share text-xl-end">
                                                        <span>Share On:</span>
                                                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->fullUrl()) }}"
                                                            target="_blank" rel="noopener">
                                                            <i class="fa-brands fa-linkedin-in"></i>
                                                        </a>
                                                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($post->title) }}"
                                                            target="_blank" rel="noopener">
                                                            <i class="fab fa-twitter"></i>
                                                        </a>
                                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                                                            target="_blank" rel="noopener">
                                                            <i class="fab fa-facebook-f"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="postbox__author d-sm-flex align-items-start white-bg mb-95">
                                            <div class="postbox__author-thumb">
                                                <a href="javascript:void(0)">
                                                    <img src="{{ optional($post->author)->avatar_url ?? asset('assets/img/users/user-14.jpg') }}"
                                                        alt="{{ optional($post->author)->name ?? 'Author' }}">
                                                </a>
                                            </div>
                                            <div class="postbox__author-content">
                                                <h3 class="postbox__author-title">
                                                    <a
                                                        href="javascript:void(0)">{{ optional($post->author)->name ?? 'Author' }}</a>
                                                </h3>
                                                @if (optional($post->author)->bio)
                                                    <p>{{ optional($post->author)->bio }}</p>
                                                @else
                                                    <p></p>
                                                @endif

                                                <div class="postbox__author-social d-flex align-items-center">
                                                    @if (optional($post->author)->twitter)
                                                        <a href="{{ $post->author->twitter }}" target="_blank"
                                                            rel="noopener"><i class="fa-brands fa-twitter"></i></a>
                                                    @endif
                                                    @if (optional($post->author)->facebook)
                                                        <a href="{{ $post->author->facebook }}" target="_blank"
                                                            rel="noopener"><i class="fa-brands fa-facebook-f"></i></a>
                                                    @endif
                                                    @if (optional($post->author)->linkedin)
                                                        <a href="{{ $post->author->linkedin }}" target="_blank"
                                                            rel="noopener"><i class="fa-brands fa-linkedin-in"></i></a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="postbox__related mb-65">
                                            <h3 class="postbox__related-title">İlginizi çekebilir</h3>

                                            <div class="row">
                                                @foreach ($related as $rel)
                                                    <div class="col-xl-6 col-lg-12 col-md-6">
                                                        <div class="blog__grid-item">
                                                            <div class="blog__item-10 white-bg transition-3 mb-30 fix">
                                                                <div class="blog__thumb-10 w-img fix">
                                                                    @php
                                                                        // Görsel: yerelse thumb üret, değilse tam URL; yoksa fallback
                                                                        $img =
                                                                            $rel->thumbUrl(540, 340) ?? // local ise kırpılmış thumb
                                                                            ($rel->featured_image_url ?? // harici URL (Picsum vs.)
                                                                                asset(
                                                                                    'assets/img/blog/grid/blog-grid-1.jpg',
                                                                                ));

                                                                        // Kategori (eager loaded ise doğrudan gelir)
                                                                        $cat = $rel->primaryCategory;
                                                                    @endphp

                                                                    <a href="{{ route('posts.show', $rel) }}">
                                                                        <img src="{{ $img }}"
                                                                            alt="{{ $rel->featured_image_alt ?: $rel->title }}"
                                                                            width="540" height="340"
                                                                            style="object-fit:cover;display:block">
                                                                    </a>

                                                                    @if ($cat)
                                                                        <div class="blog__tag-10">
                                                                            <a
                                                                                href="{{ route('posts.index', ['kategori' => $cat->slug]) }}">
                                                                                {{ $cat->name }}
                                                                            </a>
                                                                        </div>
                                                                    @endif

                                                                </div>
                                                                <div class="blog__content-10">
                                                                    <div class="blog__content-10-top">
                                                                        <div
                                                                            class="blog__meta-10-wrapper d-flex align-items-center">
                                                                            <div class="blog__meta-10 has-date">
                                                                                <span>
                                                                                    <svg width="15" height="15"
                                                                                        viewBox="0 0 15 15" fill="none"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                            d="M7.5 14C11.0899 14 14 11.0899 14 7.5C14 3.91015 11.0899 1 7.5 1C3.91015 1 1 3.91015 1 7.5C1 11.0899 3.91015 14 7.5 14Z"
                                                                                            stroke="currentColor"
                                                                                            stroke-width="1.5"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round" />
                                                                                        <path
                                                                                            d="M7.5 3.59961V7.49961L10.1 8.79961"
                                                                                            stroke="currentColor"
                                                                                            stroke-width="1.5"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round" />
                                                                                    </svg>
                                                                                    {{ optional($rel->published_at)->format('F d, Y') }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        <h3 class="blog__title-10">
                                                                            <a
                                                                                href="{{ route('posts.show', $rel) }}">{{ $rel->title }}</a>
                                                                        </h3>

                                                                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($rel->excerpt ?: ''), 110) }}
                                                                        </p>
                                                                    </div>
                                                                    <div
                                                                        class="blog__content-10-bottom d-flex align-items-center justify-content-between">
                                                                        <div
                                                                            class="blog__meta-author-10 d-flex align-items-center">
                                                                            <div class="blog__meta-author-thumb-10">
                                                                                <a href="javascript:void(0)">
                                                                                    <img src="{{ $rel->author?->avatar_url ?? asset('assets/img/users/user-2.jpg') }}"
                                                                                        alt="{{ $rel->author?->name ?? 'Yazar' }}">
                                                                                </a>
                                                                            </div>

                                                                            <div class="blog__meta-author-content-10">
                                                                                <span>Yazar:
                                                                                    <a
                                                                                        href="javascript:void(0)">{{ $rel->author?->name ?? 'Bilinmiyor' }}</a>
                                                                                </span>
                                                                            </div>
                                                                        </div>

                                                                        <div class="blog__meta-10 blog-meta-10-2">
                                                                            <span class="blog__meta-views">
  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"
       xmlns="http://www.w3.org/2000/svg">
    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"
          stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/>
  </svg>
  {{ number_format($rel->views) }}
</span>
                                                                            {{-- İkinci ikon temada izleyici sayısı; elde yoksa kaldırmayalım sadece örnek değer basmayalım --}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div id="tp-blog-details-comment" class="postbox__comment-wrapper">
                                            @include('frontend.pages.blogs.blog-comment')
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="sidebar__wrapper pl-40">
                                        @include('frontend.pages.blogs.blog-side-bar')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /.postbox__wrapper -->
                </div>
            </div>
        </div>
    </section>
    <!-- postbox details area end -->

@endsection
