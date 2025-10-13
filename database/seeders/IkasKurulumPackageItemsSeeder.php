<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\ServicePackage;
use App\Models\Service;
use App\Models\PackageItem;

class IkasKurulumPackageItemsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $now = Carbon::now();

            // Hiyerarşi: ECO ⊆ START ⊆ PRO
            $eco = [
                'site-kurulum-hizmeti',
                'tema-kurulumu-ve-duzenlenmesi',
                'pixel-ve-meta-entegrasyonu',
                'temel-site-ayarlari',
                'statik-sayfalarin-olusturulmasi-ve-duzenlenmesi',
                'haftalik-performans-raporu-site',
            ];

            $startOnly = [
                'banner-slider-urun-gorsel-destegi',
                'kampanya-kurgularinin-olusturulmasi',
                'pazarlama-iceriklerinin-olusturulmasi',
                'otomasyon-kurgularinin-olusturulmasi',
                'pazaryeri-entegrasyonu-ve-surec-yonetimi',
                'odeme-sistemi-kurulumu-ve-surec-yonetimi',
                'temel-seviye-seo-destegi',
                'aylik-performans-raporu-site',
            ];

            $proOnly = [
                'rakip-analizi-ve-iyilestirme',
                'akilli-bildirimler',
                'musteri-segmentasyonu',
                'muhasebe-programlari-entegrasyon-destegi',
                'entegrator-yazilim-kurulumu',
                'entegrator-yazilim-yonetim-hizmeti',
                'kargo-entegrasyonu-ve-surec-iyilestirmeleri',
                'gunluk-performans-raporu',
            ];

            // Slug -> hizmet listesi
            $map = [
                'ikas-kurulum-eco'   => $eco,
                'ikas-kurulum-start' => array_values(array_unique(array_merge($eco, $startOnly))),
                'ikas-kurulum-pro'   => array_values(array_unique(array_merge($eco, $startOnly, $proOnly))),
            ];

            // Görünecek isim + sıralama
            $labels = [
                'ikas-kurulum-eco'   => ['name' => 'ECO',   'order' => 1],
                'ikas-kurulum-start' => ['name' => 'START', 'order' => 2],
                'ikas-kurulum-pro'   => ['name' => 'PRO',   'order' => 3],
            ];
            $hasDisplayOrder = Schema::hasColumn('service_packages', 'display_order');

            // Test için hiyerarşik override fiyatları (TRY)
            $pricing = [
                'ikas-kurulum-eco'   =>  9900.00,
                'ikas-kurulum-start' => 19900.00,
                'ikas-kurulum-pro'   => 34900.00,
            ];

            foreach ($map as $pkgSlug => $serviceSlugs) {
                $pkg = ServicePackage::where('slug', $pkgSlug)->first();
                if (! $pkg) {
                    $this->command?->warn("Paket bulunamadı: {$pkgSlug}");
                    continue;
                }

                // İsim, sıralama ve fiyat görünürlüğü
                if (isset($labels[$pkgSlug])) {
                    $pkg->name = $labels[$pkgSlug]['name'];
                    if ($hasDisplayOrder) {
                        $pkg->display_order = $labels[$pkgSlug]['order'];
                    }
                }

                // Fiyat: override + gösterim
                if (isset($pricing[$pkgSlug])) {
                    $pkg->currency       = $pkg->currency       ?? 'TRY';
                    $pkg->currency_rate  = $pkg->currency_rate  ?? 1;
                    $pkg->override_price = $pricing[$pkgSlug];
                    $pkg->show_price     = true;
                }

                $pkg->save();

                // Hizmetleri bağla/güncelle
                $services = Service::withTrashed()
                    ->whereIn('slug', $serviceSlugs)
                    ->get(['id','slug','name','excerpt','description','unit','base_price','tax_rate_percent'])
                    ->keyBy('slug');

                $missing = array_values(array_diff($serviceSlugs, $services->keys()->all()));
                if ($missing) {
                    $this->command?->warn("Eksik hizmet slug(ları) ({$pkgSlug}): " . implode(', ', $missing));
                }

                foreach ($serviceSlugs as $i => $slug) {
                    $svc = $services->get($slug);
                    if (! $svc) continue;

                    $snapshot = [
                        'Kurulum doğrulama'     => 'Adımların eksiksiz ve hatasız tamamlandığını kanıtlar.',
                        'Ölçümleme (GA4/Pixel)' => 'Kampanya ve dönüşümlerin doğru izlenmesini sağlar.',
                        'Operasyon hızlandırma' => 'Tekrarlı işleri otomasyona bağlayarak zaman kazandırır.',
                    ];

                    $item = PackageItem::firstOrNew([
                        'service_package_id' => $pkg->id,
                        'type'               => 'service',
                        'service_id'         => $svc->id,
                    ]);

                    $unitPrice  = $item->unit_price ?? ($svc->base_price ?? 0);
                    $discount   = $item->discount_amount ?? 0;
                    $taxPercent = $item->tax_rate_percent ?? ($svc->tax_rate_percent ?? 20);

                    $item->fill([
                        'parent_item_id'   => null,
                        'name'             => $item->name ?: $svc->name,
                        'description'      => $item->description ?: ($svc->excerpt ?? $svc->description ?? null),
                        'image_path'       => $item->image_path ?: "https://picsum.photos/seed/{$pkgSlug}-{$slug}/1600/900",
                        'image_alt'        => $item->image_alt ?: $svc->name,
                        'unit'             => 'adet',
                        'qty'              => $item->qty ?? 1,
                        'unit_price'       => $unitPrice,
                        'discount_amount'  => $discount,
                        'tax_rate_percent' => $taxPercent,
                        'snapshot_json'    => $item->snapshot_json ?: $snapshot,
                        'sort_order'       => $i,
                    ])->save();
                }
            }
        });
    }
}
