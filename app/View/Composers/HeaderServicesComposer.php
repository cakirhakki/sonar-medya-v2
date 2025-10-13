<?php

namespace App\View\Composers;

use App\Models\PostCategory;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

final class HeaderServicesComposer
{
    public function compose(View $view): void
    {
        $ttlNav = now()->addMinutes(10);

        // Blog kategorileri
        if (!Schema::hasTable('post_categories') || !Schema::hasTable('posts')) {
            $view->with('navBlogCategories', collect());
        } else {
            $cats = Cache::remember('nav_blog_categories_with_posts', $ttlNav, function () {
                return PostCategory::query()
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->with(['posts' => fn($q) => $q->published()->latest('published_at')->limit(6)
                        ->select(['id','title','slug','primary_post_category_id','published_at'])])
                    ->get(['id','name','slug']);
            });
            $view->with('navBlogCategories', $cats);
        }

        // Paket menüsü devre dışı
        $view->with('menuServicePackages', collect());

        // Servis menüsü
        if (!Schema::hasTable('service_categories') || !Schema::hasTable('services')) {
            $view->with('menuServiceCategories', collect());
            $view->with('menuServices', collect());
            return;
        }

        $svcCats = Cache::remember('menu.services', $ttlNav, function () {
            return ServiceCategory::query()
                ->where('is_active', true)
                ->where('show_in_menu', true)
                ->when(Schema::hasColumn('service_categories','display_order'),
                    fn($q)=>$q->orderBy('display_order')->orderBy('name'),
                    fn($q)=>$q->orderBy('name'))
                ->with(['services'=>function($q){
                    $q->where('is_active', true)
                      ->when(Schema::hasColumn('services','display_order'),
                        fn($qq)=>$qq->orderBy('display_order')->orderBy('name'),
                        fn($qq)=>$qq->orderBy('name'))
                      ->select(['id','name','slug','service_category_id']);
                }])
                ->get(['id','name','slug','menu_mode','menu_selected_service_ids'])
                ->map(function ($cat) {
                    $selected = collect($cat->menu_selected_service_ids ?? []);
                    if ($selected->isNotEmpty()) {
                        $cat->setRelation('services', $cat->services->whereIn('id',$selected)->values());
                    }
                    return $cat;
                });
        });

        $view->with('menuServiceCategories', $svcCats);
        $view->with('menuServices', collect());
    }
}
