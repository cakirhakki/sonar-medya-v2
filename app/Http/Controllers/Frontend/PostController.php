<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Spatie\Tags\Tag;

class PostController extends Controller
{
    /**
     * Liste sayfası
     * /blog
     * /blog?kategori=slug
     * /blog?etiket=seo            (tek etiket)
     * /blog?etiket=seo,crm        (çoklu etiket, OR mantığı)
     * /blog?q=aranan
     */
    public function index(Request $request)
    {
        $query = Post::query()
            ->with([
                'author:id,name',
                'primaryCategory:id,slug,name',
                // İlişkide gereksiz kolonları taşımamak için:
                'tags' => fn($q) => $q->select('id', 'name', 'slug'),
            ])
            ->published()
            ->latest('published_at');

        // Kategori filtresi (slug ile)
        if ($slug = $request->query('kategori')) {
            $query->whereHas('primaryCategory', fn($q) => $q->where('slug', $slug));
        }

        // Etiket filtresi (Spatie v4 - slug JSON; locale üzerinden eşleştir)
        if ($tagParam = $request->query('etiket')) {
            $slugs = collect(explode(',', $tagParam))->map(fn($s) => trim($s))->filter()->values()->all();

            if (!empty($slugs)) {
                $locale = app()->getLocale(); // örn: 'tr'

                $tagModels = Tag::query()
                    ->where(function ($q) use ($slugs, $locale) {
                        foreach ($slugs as $s) {
                            // slug->tr = 'seo' gibi
                            $q->orWhere("slug->{$locale}", $s);
                        }
                    })
                    ->get();

                if ($tagModels->isNotEmpty()) {
                    // OR mantığı: herhangi bir etikete sahip olan postlar
                    $query->withAnyTags($tagModels);
                    // AND istiyorsan: $query->withAllTags($tagModels);
                } else {
                    // Hiç eşleşme yoksa boş sonuç ver
                    $query->whereRaw('1=0');
                }
            }
        }

        // Basit arama
        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(10)->withQueryString();

        return view('frontend.pages.blogs.blog', [
            'posts' => $posts,
            'activeCat' => $request->query('kategori'),
            'activeTag' => $request->query('etiket'),
            'q' => $request->query('q'),
        ]);
    }

    /**
     * Detay sayfası (slug üzerinden route-model-binding)
     */
    public function show(\App\Models\Post $post)
    {
        // Yayın kontrolü
        if ($post->status !== 'published') {
            abort(404);
        }

        // Sayaç
        $post->incrementQuietly('views');

        $catId = $post->primary_post_category_id;

        /** ---------- Prev / Next (önce kategori, yoksa global) ---------- */
        $base = \App\Models\Post::query()->where('status', 'published');

        $prev = (clone $base)
            ->when($catId, fn($q) => $q->where('primary_post_category_id', $catId))
            ->where('id', '<', $post->id)
            ->orderByDesc('id')
            ->first(['id', 'title', 'slug']);

        if (!$prev) {
            $prev = (clone $base)
                ->where('id', '<', $post->id)
                ->orderByDesc('id')
                ->first(['id', 'title', 'slug']);
        }

        $next = (clone $base)
            ->when($catId, fn($q) => $q->where('primary_post_category_id', $catId))
            ->where('id', '>', $post->id)
            ->orderBy('id')
            ->first(['id', 'title', 'slug']);

        if (!$next) {
            $next = (clone $base)
                ->where('id', '>', $post->id)
                ->orderBy('id')
                ->first(['id', 'title', 'slug']);
        }

        /** ---------- Related (önce aynı kategori, yetmezse tümü) ---------- */
        $related = \App\Models\Post::query()
            ->where('status', 'published')
            ->where('id', '!=', $post->id)
            ->when($catId, fn($q) => $q->where('primary_post_category_id', $catId))
            ->with(['author:id,name,email,avatar_path', 'primaryCategory:id,name,slug'])
            ->latest('id')
            ->limit(4)
            ->get([
                'id',
                'title',
                'slug',
                'excerpt',
                'featured_image_path',
                'featured_image_alt',
                'primary_post_category_id',
                'published_at',
                'views',
                'author_id', // ⬅️ gerekli
            ]);

        if ($related->count() < 4) {
            $need = 4 - $related->count();
            $excludeIds = $related->pluck('id')->push($post->id);

            $fallback = \App\Models\Post::query()
                ->where('status', 'published')
                ->whereNotIn('id', $excludeIds)
                ->with(['author:id,name,email,avatar_path', 'primaryCategory:id,name,slug'])
                ->latest('id')
                ->limit($need)
                ->get([
                    'id',
                    'title',
                    'slug',
                    'excerpt',
                    'featured_image_path',
                    'featured_image_alt',
                    'primary_post_category_id',
                    'published_at',
                    'views',
                    'author_id', // ⬅️ gerekli
                ]);

            $related = $related->concat($fallback);
        }

        /** ---------- Eager load detay ---------- */
        $post->load(['author:id,name,email,avatar_path,bio', 'primaryCategory:id,name,slug', 'tags:id,name,slug', 'comments' => fn($q) => $q->approved()->latest(), 'comments.children' => fn($q) => $q->approved()->oldest(), 'comments.user:id,name,email,avatar_path', 'comments.customer:id,name,email,avatar_path', 'comments.children.user:id,name,email,avatar_path', 'comments.children.customer:id,name,email,avatar_path']);

        return view('frontend.pages.blogs.blog-detail', compact('post', 'related', 'prev', 'next'));
    }
}
