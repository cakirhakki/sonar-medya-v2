<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $categories = ServiceCategory::query()
            ->where('is_active', true)
            ->where('show_in_menu', true) // menüde gösterilecekler
            ->when(Schema::hasColumn('service_categories', 'display_order'), fn($q) => $q->orderBy('display_order')->orderBy('name'), fn($q) => $q->orderBy('name'))
            ->with([
                'services' => function ($q) {
                    $q->where('is_active', true)
                        ->when(Schema::hasColumn('services', 'display_order'), fn($qq) => $qq->orderBy('display_order')->orderBy('name'), fn($qq) => $qq->orderBy('name'))
                        // summary accessor için gerçek kolonlar
                        ->select(['id', 'name', 'slug', 'service_category_id', 'excerpt', 'description']);
                },
            ])
            // menü alanlarını al
            ->get(['id', 'name', 'slug', 'description', 'menu_mode', 'menu_selected_service_ids']);

        // Menü modu: 0=Hepsi, 1=Seçililer
        $categories = $categories->map(function ($cat) {
            if ((int) ($cat->menu_mode ?? 0) === 1) {
                $selected = collect($cat->menu_selected_service_ids ?? [])
                    ->filter()
                    ->map(fn($v) => (int) $v)
                    ->all();
                if (!empty($selected)) {
                    $cat->setRelation('services', $cat->services->whereIn('id', $selected)->values());
                } else {
                    // seçili boş ise hiçbir servis göstermemek istersen:
                    // $cat->setRelation('services', collect());
                    // veya fallback: hepsini bırak -> dokunma
                }
            }
            return $cat;
        });

        $breadcrumbs = [['label' => 'Ana Sayfa', 'url' => route('site.home')], ['label' => 'Hizmetler', 'url' => null]];

        return view('frontend.pages.services.index', [
            'categories' => $categories,
            'breadcrumbs' => $breadcrumbs,
            'metaTitle' => 'Hizmetler',
            'metaDescription' => null,
        ]);
    }

    public function category(ServiceCategory $category)
    {
        abort_unless($category->is_active, 404);

        $services = Service::query()
            ->where('service_category_id', $category->id)
            ->where('is_active', true)
            ->when(Schema::hasColumn('services', 'display_order'), fn($q) => $q->orderBy('display_order')->orderBy('name'), fn($q) => $q->orderBy('name'))
            ->select(['id', 'name', 'slug', 'service_category_id', 'excerpt', 'description'])
            ->paginate(12);

        $breadcrumbs = [['label' => 'Ana Sayfa', 'url' => route('site.home')], ['label' => 'Hizmetler', 'url' => route('frontend.services.index')], ['label' => $category->name, 'url' => null]];

        return view('frontend.pages.services.category', [
            'category' => $category,
            'services' => $services,
            'breadcrumbs' => $breadcrumbs,
            'metaTitle' => $category->name,
            'metaDescription' => \Illuminate\Support\Str::of((string) $category->description)->stripTags()->limit(160)->value(),
        ]);
    }

    public function show(string $slug)
    {
        $service = Service::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with(['category' => fn($q) => $q->select('id', 'name', 'slug')])
            ->firstOrFail();

        $breadcrumbs = [['label' => 'Ana Sayfa', 'url' => route('site.home')], ['label' => 'Hizmetler', 'url' => route('frontend.services.index')], ['label' => $service->category?->name, 'url' => $service->category ? route('frontend.services.category', $service->category->slug) : null], ['label' => $service->name, 'url' => null]];

        return view('frontend.pages.services.show', [
            'service' => $service,
            'category' => $service->category,
            'breadcrumbs' => $breadcrumbs,
            'metaTitle' => $service->name,
            'metaDescription' => $service->summary ?? null, // accessor
            'metaImage' => method_exists($service, 'getFirstMediaUrl') ? $service->getFirstMediaUrl('images') : null,
        ]);
    }
}
