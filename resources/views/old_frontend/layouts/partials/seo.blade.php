@php
    use Illuminate\Support\Str;

    // İçerik tipi tespiti
    $isArticle = isset($metaType)
        ? ($metaType === 'article')
        : (isset($post) && $post instanceof \App\Models\Post);

    // Canonical
    $canonical = $seo['canonical'] ?? url()->current();

    // Başlık / Açıklama / Görsel – $seo[] varsa öncelikli
    $titleRaw = $seo['title']
        ?? ($isArticle
            ? ($post->meta_title ?: $post->title)
            : ($site->meta_title ?? ($site->site_title ?? config('app.name'))));

    $descRaw = $seo['meta_description']
        ?? ($isArticle
            ? ($post->meta_description ?: ($post->excerpt ?: Str::limit(strip_tags($post->content ?? ''), 160, '')))
            : ($site->meta_description ?? null));

    $image = $seo['meta_image']
        ?? ($isArticle
            ? ($post->featured_image_url ?: $post->meta_image)
            : ($site->meta_image_url ?? null));

    // Uzunluk sınırları
    $title = $titleRaw ? Str::limit(trim($titleRaw), 60, '') : null;
    $desc  = $descRaw ? Str::limit(trim($descRaw), 160, '') : null;

    // Opsiyonel robots (noindex vb.) desteği
    $robots = $seo['robots'] ?? null;
@endphp

<link rel="canonical" href="{{ $canonical }}"/>

@if($robots)
<meta name="robots" content="{{ $robots }}">
@endif

<meta property="og:site_name" content="{{ $site->site_title ?? config('app.name') }}">
<meta property="og:type" content="{{ $isArticle ? 'article' : 'website' }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:title" content="{{ e($title) }}">
@if($desc)<meta property="og:description" content="{{ e($desc) }}">@endif
@if($image)<meta property="og:image" content="{{ $image }}">@endif

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ e($title) }}">
@if($desc)<meta name="twitter:description" content="{{ e($desc) }}">@endif
@if($image)<meta name="twitter:image" content="{{ $image }}">@endif

@if($isArticle)
    @if(!empty($post->published_at))
        <meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}">
    @endif
    @if(!empty($post->author?->name))
        <meta name="author" content="{{ e($post->author->name) }}">
    @endif
@endif
