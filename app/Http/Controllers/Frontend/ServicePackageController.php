<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ServicePackage;
use App\Models\PackageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ServicePackageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Paket Listeleme (Tüm Yayında Olanlar)
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $categories = PackageCategory::query()
            ->whereHas('servicePackages', fn($q) => $q->published())
            ->orderBy('name')
            ->with(['servicePackages' => function ($q) {
                $q->published()
                  ->ordered()
                  ->select([
                      'id',
                      'package_category_id',
                      'name',
                      'slug',
                      'short_description',
                      'description',
                      'override_price',
                      'currency',
                      'status',
                      'published_at',
                  ])
                  ->withCount('items');
            }])
            ->get(['id','name','slug','image_path','image_alt']);

        $breadcrumbs = [
            ['label' => 'Ana Sayfa', 'url' => route('site.home')],
            ['label' => 'Hizmet Paketleri', 'url' => null],
        ];

        return view('frontend.pages.packages.index', [
            'categories'  => $categories,
            'breadcrumbs' => $breadcrumbs,
            'metaTitle'   => 'Hizmet Paketleri - Sonar Medya',
            'metaDescription' => 'Sonar Medya tarafından sunulan profesyonel dijital hizmet paketleri. Tasarım, yazılım, pazarlama ve danışmanlık çözümlerini bir arada keşfedin.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Belirli Kategori Altındaki Paketler
    |--------------------------------------------------------------------------
    */
    public function category(string $slug)
    {
        $category = PackageCategory::query()
            ->where('slug', $slug)
            ->firstOrFail();

        $packages = $category->servicePackages()
            ->published()
            ->ordered()
            ->select([
                'id',
                'package_category_id',
                'name',
                'slug',
                'short_description',
                'description',
                'override_price',
                'currency',
                'status',
            ])
            ->withCount('items')
            ->get();

        $breadcrumbs = [
            ['label' => 'Ana Sayfa', 'url' => route('site.home')],
            ['label' => 'Hizmet Paketleri', 'url' => route('frontend.service-packages.index')],
            ['label' => $category->name, 'url' => null],
        ];

        return view('frontend.pages.packages.category', [
            'category'    => $category,
            'packages'    => $packages,
            'breadcrumbs' => $breadcrumbs,
            'metaTitle'   => $category->name . ' - Hizmet Paketleri | Sonar Medya',
            'metaDescription' => $category->image_alt ?: 'Sonar Medya tarafından sunulan ' . $category->name . ' paketleri.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Paket Detayı (İlişkiler Dahil)
    |--------------------------------------------------------------------------
    */
    public function show(string $slug)
    {
        $package = ServicePackage::query()
            ->published()
            ->where('slug', $slug)
            ->withDetail() // category + rootItems + children + service
            ->with(['faqs' => fn($q) => $q->ordered()])
            ->firstOrFail();

        // Paket içeriği: kök item’lar + alt item’lar
        $tabs = $package->rootItems;
        $solutionsById = [];
        $necessaryById = [];

        // Repeater veri uyumlu hale getirme (örnek olarak item-description ayrıştırması)
        foreach ($tabs as $tab) {
            $solutionsById[$tab->id] = collect($tab->children)->pluck('name')->toArray();
            $necessaryById[$tab->id] = $tab->description ?? '';
        }

        $breadcrumbs = [
            ['label' => 'Ana Sayfa', 'url' => route('site.home')],
            ['label' => 'Hizmet Paketleri', 'url' => route('frontend.service-packages.index')],
            ['label' => $package->category?->name, 'url' => $package->category ? route('frontend.service-packages.category', $package->category->slug) : null],
            ['label' => $package->name, 'url' => null],
        ];

        return view('frontend.pages.packages.show', [
            'package'        => $package,
            'category'       => $package->category,
            'tabs'           => $tabs,
            'solutionsById'  => $solutionsById,
            'necessaryById'  => $necessaryById,
            'breadcrumbs'    => $breadcrumbs,
            'metaTitle'      => $package->display_name . ' | Sonar Medya',
            'metaDescription'=> $package->short_description ?? strip_tags($package->description),
            'metaImage'      => $package->getFirstMediaUrl('cover') ?: asset('site/assets/img/meta/package-default.jpg'),
        ]);
    }
}
