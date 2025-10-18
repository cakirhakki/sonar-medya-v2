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
        if (! Schema::hasTable('post_categories') || ! Schema::hasTable('posts')) {
            $view->with('navBlogCategories', collect());
        } else {
            $cats = Cache::remember('nav_blog_categories_with_posts', $ttlNav, function () {
                return PostCategory::query()
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->with([
                        'posts' => fn ($q) => $q->published()
                            ->latest('published_at')
                            ->limit(6)
                            ->select(['id','title','slug','primary_post_category_id','published_at']),
                    ])
                    ->get(['id','name','slug']);
            });
            $view->with('navBlogCategories', $cats);
        }

        // Paket menüsü devre dışı
        $view->with('menuServicePackages', collect());

        // Servis menüsü
        if (! Schema::hasTable('service_categories') || ! Schema::hasTable('services')) {
            $view->with('menuServiceCategories', collect());
            $view->with('menuCategoryServices', []);
            return;
        }

        // Menü kategorileri + pivot eager load (N+1 yok)
        $svcCats = Cache::remember('menu.services', $ttlNav, function () {
            return ServiceCategory::query()
                ->menu() // is_active + show_in_menu + ordered
                ->with([
                    'menuServices' => fn ($q) =>
                        $q->where('is_active', true)
                          ->orderBy('service_category_menu_services.position'),
                ])
                ->get([
                    'id','name','slug',
                    'show_in_menu',
                    'menu_mode',
                ]);
        });

        // İstenirse view’a hazır hizmet map’i ver
        $serviceMap = [];
        foreach ($svcCats as $c) {
            if ((int) $c->menu_mode === 1) {
                $serviceMap[$c->id] = $c->menuServices;              // pivot’tan
            } else {
                // Hepsi için aktifleri çek (gerekirse eager load eklenebilir)
                $serviceMap[$c->id] = $c->services()->where('is_active', true)->get();
            }
        }

        $view->with('menuServiceCategories', $svcCats);
        $view->with('menuCategoryServices', $serviceMap);
    }
}
