<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ServicePackage;
use App\Models\Service;
use App\Models\PackageItem;

class IndependentPackagesFullSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $now = Carbon::now();

            $packages = [
                [
                    'slug'      => 'sosyal-medya-yonetimi-paketi',
                    'name'      => 'Sosyal Medya Yönetimi Paketi',
                    'short'     => 'Planlı içerik, kreatif üretim ve temel danışmanlık.',
                    'desc'      => 'İçerik takvimi, kreatif görsel üretimi ve sosyal hesap yönetimi ile marka görünürlüğünü artırmaya odaklı paket.',
                    'services'  => [
                        'is-akisi-ve-icerik-plani-olusturma',
                        'pazarlama-iceriklerinin-olusturulmasi',
                        'kreatif-reklam-gorseli-tasarimi',
                        'sosyal-medya-danismanligi',
                        'haftalik-performans-raporu',
                        'aylik-performans-raporu',
                    ],
                    'faqs'      => [
                        ['q' => 'Hangi platformlar kapsama dahil?', 'a' => 'Öncelik Instagram ve Facebook. İhtiyaca göre X/LinkedIn eklenebilir.'],
                        ['q' => 'İçerik onayı nasıl işliyor?', 'a' => 'Aylık takvim taslak olarak paylaşılır, revizyon sonrası yayınlanır.'],
                        ['q' => 'Raporlama sıklığı nedir?', 'a' => 'Haftalık özet ve aylık detay performans raporu sağlanır.'],
                    ],
                    'snapshot'  => [
                        'Marka tutarlılığı'   => 'Tek bir içerik dili ve görsel sistemle tutarlılık sağlanır.',
                        'Takvimli üretim'     => 'Aylık plan ile düzenli ve sürdürülebilir içerik akışı kurulur.',
                        'Etkileşim odağı'     => 'Toplulukla etkileşimi artıran formatlar önceliklenir.',
                    ],
                ],
                [
                    'slug'      => 'e-ticaret-teknik-yonetim-paketi',
                    'name'      => 'E-Ticaret Teknik Yönetim Paketi',
                    'short'     => 'Kurulum, entegrasyon ve teknik işletim.',
                    'desc'      => 'Tema/kurulum, temel ayarlar, entegrasyonlar ve periyodik raporlama içeren teknik işletim paketi.',
                    'services'  => [
                        'site-kurulum-hizmeti',
                        'tema-kurulumu-ve-duzenlenmesi',
                        'temel-site-ayarlari',
                        'statik-sayfalarin-olusturulmasi-ve-duzenlenmesi',
                        'pixel-ve-meta-entegrasyonu',
                        'pazaryeri-entegrasyonu-ve-surec-yonetimi',
                        'odeme-sistemi-kurulumu-ve-surec-yonetimi',
                        'kargo-entegrasyonu-ve-surec-iyilestirmeleri',
                        'teknik-destek-hizmeti',
                        'haftalik-performans-raporu-site',
                        'aylik-performans-raporu-site',
                    ],
                    'faqs'      => [
                        ['q' => 'Hangi entegrasyonlar kuruluyor?', 'a' => 'Pazaryeri, ödeme ve kargo entegrasyonları, mevcut sağlayıcılarınıza göre kurulur.'],
                        ['q' => 'Kurulum süresi nedir?', 'a' => 'Erişimler hazırsa 3–7 iş günü içinde temel kurulum ve testler tamamlanır.'],
                        ['q' => 'Destek kapsamı neyi içerir?', 'a' => 'Temel teknik destek, hata takibi ve düzenli performans raporlaması.'],
                    ],
                    'snapshot'  => [
                        'Stabil altyapı'      => 'Temel yapılandırmalarla kesintisiz çalışmaya odaklanır.',
                        'Doğru ölçüm'         => 'GA4/Pixel ile veri kalitesi güvence altına alınır.',
                        'Entegrasyon verimi'  => 'Sipariş, stok ve ödeme akışları senkronize edilir.',
                    ],
                ],
            ];

            foreach ($packages as $def) {
                // 1) Paket: create -> slug ver; update -> slug/code dokunma
                $pkg = ServicePackage::withTrashed()->where('slug', $def['slug'])->first();

                if (! $pkg) {
                    $pkg = ServicePackage::create([
                        'package_category_id' => null,
                        'name'                => $def['name'],
                        'slug'                => $def['slug'],
                        'short_description'   => $def['short'],
                        'description'         => $def['desc'],
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

                    $pkg->fill([
                        'package_category_id' => null,
                        'name'                => $def['name'],
                        'short_description'   => $def['short'],
                        'description'         => $def['desc'],
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
                $services = Service::withTrashed()
                    ->whereIn('slug', $def['services'])
                    ->get(['id','slug','name','excerpt','description','unit','base_price','tax_rate_percent'])
                    ->keyBy('slug');

                $missing = array_values(array_diff($def['services'], $services->keys()->all()));
                if ($missing) {
                    $this->command?->warn('Eksik Service slug(ları) ['.$def['slug'].']: '.implode(', ', $missing));
                }

                foreach ($def['services'] as $i => $sSlug) {
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
                        'image_path'       => $item->image_path ?: "https://picsum.photos/seed/{$def['slug']}-{$sSlug}/1600/900",
                        'image_alt'        => $item->image_alt ?: $svc->name,
                        'unit'             => 'adet',
                        'qty'              => $item->qty ?? 1,
                        'unit_price'       => $unitPrice,
                        'discount_amount'  => $discount,
                        'tax_rate_percent' => $taxPercent,
                        'snapshot_json'    => $item->snapshot_json ?: $def['snapshot'],
                        'sort_order'       => $i,
                    ]);
                    $item->save();
                }

                // 3) Paket SSS
                $keep = [];
                $sort = 1;
                foreach ($def['faqs'] as $qa) {
                    $keep[] = $qa['q'];
                    $pkg->faqs()->updateOrCreate(
                        ['question' => $qa['q']],
                        ['answer' => $qa['a'], 'sort_order' => $sort++]
                    );
                }
                $pkg->faqs()->whereNotIn('question', $keep)->delete();
            }
        });
    }
}
