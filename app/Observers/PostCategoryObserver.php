<?php

namespace App\Observers;

use App\Models\PostCategory;
use Illuminate\Support\Facades\Cache;

final class PostCategoryObserver
{
    public function saved(PostCategory $m): void
    {
        Cache::forget('sb_categories');
        Cache::forget('nav_blog_categories_with_posts');
    }

    public function deleted(PostCategory $m): void
    {
        $this->saved($m);
    }
}
