<?php

namespace App\Services;

use App\Models\ServicePackage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ServicePackageFinder
{
    /** /services index için liste */
    public function listPublishedForIndex(int $perPage = 12): LengthAwarePaginator
    {
        return ServicePackage::query()
            ->published()
            ->withCategory() // category:id,name
            ->with([
                'items' => fn($q) => $q->orderBy('sort_order')->orderBy('id'),
                'faqs'  => fn($q) => $q->orderBy('sort_order')->orderBy('id'),
            ])
            ->latest('published_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    /** tek paket detayını tüm gerekli ilişkilerle getir */
    public function findPublishedBySlugWithDetail(string $slug): ServicePackage
    {
        return ServicePackage::query()
            ->where('slug', $slug)
            ->published()
            ->withDetail()
            ->firstOrFail();
    }

    /** aynı kategorideki (veya kategorisizse sadece kendisi) yayınlanmış paketler */
    public function peersOf(ServicePackage $package): Collection
    {
        return ServicePackage::query()
            ->published()
            ->peersOf($package)
            ->withItemsForPricing() // items (+service) minimal select
            ->ordered()             // sort_order varsa ona göre, yoksa ada göre
            ->get();
    }
}
