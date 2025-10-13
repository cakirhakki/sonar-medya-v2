<?php

namespace App\View\Composers;

use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use App\Models\ServiceCategory;

final class FooterComposer
{
    public function compose(View $view): void
    {
        /** @var \App\Models\SiteSetting|null $site */
        $site = ViewFacade::shared('site');

        $copyright = $site?->copyright_text
            ? Str::of($site->copyright_text)->replace('{year}', (string) now()->year)->value()
            : null;

        $normalize = function (?string $u): ?string {
            if (blank($u)) return null;
            $u = trim($u);
            if (!str_starts_with($u, 'http')) $u = 'https://' . ltrim($u, '/');
            return $u;
        };

        $telHref = function (?string $p): ?string {
            if (blank($p)) return null;
            $d = preg_replace('/\D+/', '', $p);
            if (strlen($d)===10) $d='90'.$d;
            elseif (strlen($d)===11 && str_starts_with($d,'0')) $d='9'.substr($d,1);
            elseif (str_starts_with($d,'00')) $d=substr($d,2);
            return $d ? 'tel:+'.$d : null;
        };

        $social = array_filter([
            'facebook'  => $normalize($site?->facebook),
            'twitter'   => $normalize($site?->twitter),
            'instagram' => $normalize($site?->instagram),
            'linkedin'  => $normalize($site?->linkedin),
            'youtube'   => $normalize($site?->youtube),
        ]);

        $contact = [
            'company' => $site?->company_name,
            'address' => $site?->address,
            'map_url' => $normalize($site?->google_map_url),
            'email'   => $site?->email ? ['display'=>$site->email, 'href'=>'mailto:'.strtolower(trim($site->email))] : null,
            'phone'   => $site?->phone ? ['display'=>$site->phone, 'href'=>$telHref($site->phone)] : null,
            'mobile'  => $site?->mobile? ['display'=>$site->mobile,'href'=>$telHref($site->mobile)] : null,
        ];

        // ---- Hizmet menü kategorileri (footer için) ----
        $ttl = now()->addMinutes(10);

        if (!Schema::hasTable('service_categories') || !Schema::hasTable('services')) {
            $menuServiceCategories = collect();
        } else {
            $menuServiceCategories = Cache::remember('menu.services', $ttl, function () {
                return ServiceCategory::query()
                    ->where('is_active', true)
                    ->where('show_in_menu', true)
                    ->when(
                        Schema::hasColumn('service_categories','display_order'),
                        fn($q)=>$q->orderBy('display_order')->orderBy('name'),
                        fn($q)=>$q->orderBy('name')
                    )
                    ->with(['services'=>function($q){
                        $q->where('is_active', true)
                          ->when(
                              Schema::hasColumn('services','display_order'),
                              fn($qq)=>$qq->orderBy('display_order')->orderBy('name'),
                              fn($qq)=>$qq->orderBy('name')
                          )
                          ->select(['id','name','slug','service_category_id']);
                    }])
                    ->get(['id','name','slug','menu_mode','menu_selected_service_ids','menu_excluded_service_ids'])
                    ->map(function ($cat) {
                        $mode     = (int) ($cat->menu_mode ?? 0); // 0=hepsi, 1=yalnız seçilen, 2=hariç tut
                        $selected = collect($cat->menu_selected_service_ids ?? []);
                        $excluded = collect($cat->menu_excluded_service_ids ?? []);
                        $services = collect($cat->services);

                        if ($mode === 1 && $selected->isNotEmpty()) {
                            $services = $services
                                ->whereIn('id', $selected)
                                ->sortBy(fn ($s) => $selected->search($s->id))
                                ->values();
                        } elseif ($mode === 2 && $excluded->isNotEmpty()) {
                            $services = $services->reject(fn ($s) => $excluded->contains($s->id))->values();
                        } else {
                            $services = $services->values();
                        }

                        $cat->setRelation('services', $services);
                        return $cat;
                    });
            });
        }

        // View değişkenleri
        $view->with('siteFooter', [
            'description' => $site?->footer_description ?? null,
            'copyright'   => $copyright,
            'social'      => $social,
            'contact'     => $contact,
        ]);

        // Header ile aynı isim. Footer Blade’i doğrudan kullanabilir.
        $view->with('menuServiceCategories', $menuServiceCategories);
    }
}
