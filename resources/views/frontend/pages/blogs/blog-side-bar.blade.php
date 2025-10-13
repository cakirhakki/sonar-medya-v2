{{-- SEARCH --}}
<div class="sidebar__widget mb-20">
    <div class="sidebar__widget-content">
        <div class="sidebar__search">
            <form action="{{ route('posts.index') }}" method="get">
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                <input type="hidden" name="etiket" value="{{ request('etiket') }}">
                <div class="sidebar__search-input">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Anahtar kelime yazın...">
                    <button type="submit" aria-label="Ara">…</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- AUTHOR / ABOUT --}}
@php
    // Güvenli yardımcılar
    $site           = $site ?? null;
    $siteFooter     = is_array($siteFooter ?? null) ? $siteFooter : [];
    $logoPath       = data_get($site, 'logo_path');
    $fallbackLogo   = asset('site/assets/img/blog/sidebar/sidebar-author.jpg');
    $logoUrl        = $logoPath ? route('thumb', ['src' => $logoPath, 'w' => 120, 'h' => 120, 'fit' => 'contain']) : $fallbackLogo;
    $siteTitle      = data_get($site, 'meta_title') ?: (data_get($site, 'site_title') ?: 'Logo');
    $desc           = $siteFooter['description'] ?? (data_get($site, 'footer_description') ?: null);
    $s              = $siteFooter['social'] ?? [];
@endphp

<div class="sidebar__widget mb-45">
    <div class="sidebar__widget-content">
        <div class="sidebar__author">
            <div class="sidebar__author-thumb">
                <img src="{{ $logoUrl }}"
                     alt="{{ $siteTitle }}" width="120" height="120"
                     style="width:120px;height:120px;object-fit:contain;border-radius:50%;background:transparent">
            </div>
            <div class="sidebar__author-content">
                <h3 class="sidebar__author-title">{{ data_get($site, 'brand_name', 'Sonar Medya') }}</h3>

                @if ($desc)
                    <p>{!! nl2br(e($desc)) !!}</p>
                @endif

                <div class="sidebar__author-social d-flex align-items-center justify-content-center">
                    @if (!empty($s['facebook']))
                        <a href="{{ $s['facebook'] }}" target="_blank" rel="noopener nofollow" aria-label="Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                    @endif
                    @if (!empty($s['twitter']))
                        <a href="{{ $s['twitter'] }}" target="_blank" rel="noopener nofollow" aria-label="X">
                            <i class="fa-brands fa-twitter"></i>
                        </a>
                    @endif
                    @if (!empty($s['instagram']))
                        <a href="{{ $s['instagram'] }}" target="_blank" rel="noopener nofollow" aria-label="Instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    @endif
                    @if (!empty($s['linkedin']))
                        <a href="{{ $s['linkedin'] }}" target="_blank" rel="noopener nofollow" aria-label="LinkedIn">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                    @endif
                    @if (!empty($s['youtube']))
                        <a href="{{ $s['youtube'] }}" target="_blank" rel="noopener nofollow" aria-label="YouTube">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CATEGORIES (AppServiceProvider: $sidebarCats) --}}
<div class="sidebar__widget mb-45">
    <h3 class="sidebar__widget-title">Kategoriler</h3>
    <div class="sidebar__widget-content">
        <ul>
            @forelse(($sidebarCats ?? []) as $cat)
                @php $active = request('kategori') === ($cat->slug ?? null); @endphp
                <li class="{{ $active ? 'is-active' : '' }}">
                    <a href="{{ route('posts.index', ['kategori' => $cat->slug] + request()->except('page')) }}">
                        {{ $cat->name ?? '-' }}
                        <span>{{ $cat->posts_count ?? 0 }}</span>
                    </a>
                </li>
            @empty
                <li><em>Henüz kategori yok.</em></li>
            @endforelse
        </ul>
    </div>
</div>

{{-- RECENT POSTS (AppServiceProvider: $sidebarRecent) --}}
<div class="sidebar__widget mb-45">
    <h3 class="sidebar__widget-title">Son Yazılar</h3>
    <div class="sidebar__widget-content">
        <div class="sidebar__post">
            @forelse(($sidebarRecent ?? []) as $p)
                <div class="rc__post d-flex align-items-center">
                    <div class="rc__post-thumb">
                        <a href="{{ route('posts.show', $p) }}">
                            @php
                                $thumb = method_exists($p, 'thumbUrl')
                                    ? $p->thumbUrl(80, 80)
                                    : ($p->featured_image_url ?? asset('site/assets/img/blog/sidebar/blog-sm-1.jpg'));
                            @endphp
                            <img src="{{ $thumb }}"
                                 alt="{{ $p->featured_image_alt ?? $p->title ?? 'Görsel' }}"
                                 width="80" height="80" style="object-fit:cover;display:block">
                        </a>
                    </div>
                    <div class="rc__post-content">
                        <h3 class="rc__post-title">
                            <a href="{{ route('posts.show', $p) }}">
                                {{ \Illuminate\Support\Str::limit($p->title ?? '-', 60) }}
                            </a>
                        </h3>
                        <div class="rc__meta">
                            <span>
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                                    <path d="M7.5 14C11.0899 14 14 11.0899 14 7.5C14 3.91015 11.0899 1 7.5 1C3.91015 1 1 3.91015 1 7.5C1 11.0899 3.91015 14 7.5 14Z"
                                          stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.5 3.59961V7.49961L10.1 8.79961" stroke="currentColor" stroke-width="1.5"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                {{ optional($p->published_at)->translatedFormat('d F Y') ?? '' }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <p><em>Henüz içerik yok.</em></p>
            @endforelse
        </div>
    </div>
</div>

{{-- TAGS --}}
@if (!empty($sidebarTags ?? null))
    <div class="sidebar__widget mb-40">
        <h3 class="sidebar__widget-title">Etiketler</h3>
        <div class="sidebar__widget-content">
            <div class="tagcloud">
                @foreach ($sidebarTags as $t)
                    <a href="{{ route('posts.index', ['etiket' => $t->name]) }}">{{ $t->name }}</a>
                @endforeach
            </div>
        </div>
    </div>
@endif
