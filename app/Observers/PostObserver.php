<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

final class PostObserver
{
    public function saved(Post $m): void
    {
        Cache::forget('sb_recent_posts');
        Cache::forget('sb_categories');
        Cache::forget('nav_blog_categories_with_posts');
    }

    public function deleted(Post $m): void
    {
        $this->saved($m);
    }
}
