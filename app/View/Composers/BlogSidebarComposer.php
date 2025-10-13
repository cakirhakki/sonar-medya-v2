<?php

namespace App\View\Composers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

final class BlogSidebarComposer
{
    public function compose(View $view): void
    {
        $ttl = now()->addMinutes(10);
        $recent = collect();
        $cats   = collect();
        $site   = ViewFacade::shared('site');

        if (Schema::hasTable('posts')) {
            $recent = Cache::remember('sb_recent_posts', $ttl, fn() =>
                Post::published()->latest('published_at')->limit(5)
                    ->get(['id','title','slug','featured_image_path','featured_image_alt','published_at'])
            );
        }

        if (Schema::hasTable('post_categories') && Schema::hasTable('posts')) {
            $cats = Cache::remember('sb_categories', $ttl, fn() =>
                PostCategory::query()
                    ->where('is_active', true)
                    ->withCount(['posts as posts_count'=>fn($q)=>$q->published()])
                    ->orderByDesc('posts_count')->limit(10)
                    ->get(['id','name','slug'])
            );
        }

        $thumb = $site?->thumbLogo(120,120,'cover') ?? asset('site/assets/img/blog/sidebar/sidebar-author.jpg');

        $view->with([
            'sidebarRecent'      => $recent,
            'sidebarCats'        => $cats,
            'sidebarAuthorThumb' => $thumb,
        ]);
    }
}
