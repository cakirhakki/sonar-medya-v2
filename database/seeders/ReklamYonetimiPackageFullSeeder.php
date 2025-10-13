<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ServicePackage;
use App\Models\Service;
use App\Models\PackageItem;

class ReklamYonetimiPackageFullSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $now  = Carbon::now();
            $slug = 'reklam-yonetimi-paketi';

            // 1) Paket (kategorisiz) — create: slug ver, update: slug/code'a dokunma
            $pkg = ServicePackage::withTrashed()->where('slug', $slug)->first();

            if (! $pkg) {
                $pkg = ServicePackage::create([
                    'package_category_id' => null,
                    'name'                => 'Reklam Yönetimi Paketi',
                    'slug'                => $slug,              // yalnızca create'te
                    'short_description'   => 'Meta ve Google reklamları için uçtan uca yönetim.',
                    'description'         => 'İşletme hesap kurulumu, GA4/Pixel, strateji, kreatif metin ve sürekli optimizasyon.',
                    'show_price'          => true,
                    'currency'            => 'TRY',
                    'currency_rate'       => null,
                    'override_price'      => null,
                    'status'              => ServicePackage::STATUS_PUBLISHED,
                    'sent_at'             => null,
                    'accepted_at'         => null,
                    'published_at'        => $now,
                    'expires_at'          => null,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ]);
            } else {
                if ($pkg->trashed()) $pkg->restore();

                // slug, code DEĞİŞMEZ
                $pkg->fill([
                    'package_category_id' => null,
                    'name'                => 'Reklam Yönetimi Paketi',
                    'short_description'   => 'Meta ve Google reklamları için uçtan uca yönetim.',
                    'description'         => 'İşletme hesap kurulumu, GA4/Pixel, strateji, kreatif metin ve sürekli optimizasyon.',
                    'show_price'          => true,
                    'currency'            => 'TRY',
                    'currency_rate'       => $pkg->currency_rate,
                    'override_price'      => $pkg->override_price,
                    'status'              => ServicePackage::STATUS_PUBLISHED,
                    'sent_at'             => $pkg->sent_at,
                    'accepted_at'         => $pkg->accepted_at,
                    'expires_at'          => $pkg->expires_at,
                ]);
                if (blank($pkg->published_at)) $pkg->published_at = $now;
                $pkg->updated_at = $now;
                $pkg->save();
            }

            // 2) Satırlar
            $serviceSlugs = [
                'isletme-hesap-kurulumu-ve-baglantilari',
                'meta-ads-yonetimi',
                'google-reklam-yonetimi',
                'pixel-kurulumu-ve-entegrasyonu',
                'hedef-kitle-optimizasyonu',
                'kampanya-amaci-ve-stratejileri-olusturma',
                'reklam-metni-olusturma',
                'anahtar-kelime-yonetimi',
                'sms-mail-marketing',
                'haftalik-performans-raporu',
                'aylik-performans-raporu',
            ];

            $services = Service::withTrashed()
                ->whereIn('slug', $serviceSlugs)
                ->get(['id','slug','name','excerpt','description','unit','base_price','tax_rate_percent'])
                ->keyBy('slug');

            $missing = array_values(array_diff($serviceSlugs, $services->keys()->all()));
            if ($missing) $this->command?->warn('Eksik Service slug(ları): ' . implode(', ', $missing));

            foreach ($serviceSlugs as $i => $sSlug) {
                $svc = $services->get($sSlug);
                if (! $svc) continue;

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
                    'image_path'       => $item->image_path ?: "https://picsum.photos/seed/{$slug}-{$sSlug}/1600/900",
                    'image_alt'        => $item->image_alt ?: $svc->name,
                    'unit'             => 'adet',
                    'qty'              => $item->qty ?? 1,
                    'unit_price'       => $unitPrice,
                    'discount_amount'  => $discount,
                    'tax_rate_percent' => $taxPercent,
                    'snapshot_json'    => $item->snapshot_json ?: [
                        'Veri temelli yönetim' => 'GA4/Pixel ile ölçülebilir performans sağlar.',
                        'Sürekli test'         => 'Kreatif ve hedefleme A/B testleri ile iyileştirme yapılır.',
                        'Şeffaf raporlama'     => 'Haftalık ve aylık raporlarla görünürlük sağlanır.',
                    ],
                    'sort_order'       => $i,
                ]);
                $item->save();
            }

            // 3) Paket SSS
            $faqs = [
                ['q' => 'Hangi kanallar yönetiliyor?', 'a' => 'Meta Ads ve Google Ads temel kapsamdır. İsteğe bağlı TikTok ve YouTube eklenebilir.'],
                ['q' => 'Kurulum ne kadar sürer?', 'a' => 'Erişimler hazırsa 1–3 iş günü içinde kurulum ve ilk kampanyalar açılır.'],
                ['q' => 'Raporlama sıklığı nedir?', 'a' => 'Haftalık özet ve aylık detay rapor sunulur.'],
            ];

            $keep = [];
            $sort = 1;
            foreach ($faqs as $qa) {
                $keep[] = $qa['q'];
                $pkg->faqs()->updateOrCreate(
                    ['question' => $qa['q']],
                    ['answer' => $qa['a'], 'sort_order' => $sort++]
                );
            }
            $pkg->faqs()->whereNotIn('question', $keep)->delete();
        });
    }
}
