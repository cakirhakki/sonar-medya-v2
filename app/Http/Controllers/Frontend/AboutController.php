<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AboutController extends Controller
{
    public function __invoke()
    {
        $categories = ServiceCategory::query()
            ->where('is_active', true)
            ->when(
                Schema::hasColumn('service_categories', 'show_in_menu'),
                fn ($q) => $q->where('show_in_menu', true)
            )
            ->when(
                Schema::hasColumn('service_categories', 'display_order'),
                fn ($q) => $q->orderBy('display_order')->orderBy('name'),
                fn ($q) => $q->orderBy('name')
            )
            ->select(['id', 'name', 'slug', 'description'])
            ->withCount(['services' => fn ($q) => $q->where('is_active', true)])
            ->get();

        $breadcrumbs = [
            ['label' => 'Ana Sayfa', 'url' => route('site.home')],
            ['label' => 'Hakkımızda', 'url' => null],
        ];

        return view('frontend.pages.about', [
            'categories'      => $categories,
            'breadcrumbs'     => $breadcrumbs,
            'metaTitle'       => 'Hakkımızda',
            'metaDescription' => Str::of('Sonar Medya hakkında bilgiler, hizmet kategorileri ve yaklaşımımız.')->limit(160)->value(),
        ]);
    }
}
