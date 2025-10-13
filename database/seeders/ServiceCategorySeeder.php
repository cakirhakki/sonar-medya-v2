<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1) Mevcut 3 kategori (değiştirmiyoruz)
        $base = [
            [
                'name' => 'Reklam ve Performans Yönetimi',
                'slug' => 'reklam-ve-performans-yonetimi',
                'description' => 'Meta/Google Ads, piksel entegrasyonu, hedef kitle optimizasyonu, kampanya stratejisi, kreatif metin, anahtar kelime ve rekabet analizi, SMS & e-posta pazarlama, haftalık/aylık raporlar; opsiyonel: TikTok, YouTube, Criteo/RTB House, TV & radyo, kreatif tasarım, sosyal medya danışmanlığı.',
                'is_active' => true,
                'show_in_menu' => false,
                'menu_mode' => 0,
                'menu_selected_service_ids' => [],
                'menu_excluded_service_ids' => [],
                'display_order' => 1,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Site Kurulumu ve Yönetimi',
                'slug' => 'site-kurulumu-ve-yonetimi',
                'description' => 'E-ticaret paket seçimi, tema kurulumu, piksel/Meta entegrasyonu, görsel destek, kampanya ve otomasyon kurguları, pazaryeri–muhasebe–kargo–ödeme entegrasyonları, temel SEO, statik sayfalar, teknik destek ve raporlama.',
                'is_active' => true,
                'show_in_menu' => false,
                'menu_mode' => 0,
                'menu_selected_service_ids' => [],
                'menu_excluded_service_ids' => [],
                'display_order' => 2,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Danışmanlık ve Eğitim Hizmetleri',
                'slug' => 'danismanlik-ve-egitim-hizmetleri',
                'description' => 'Panel eğitimi, destek talebi, online toplantı, canlı destek, özel marka/iletişim yöneticisi; opsiyonel: marka danışmanlığı, influencer & UGC, outdoor/TV/radyo, B2B marketing, UX, front-end/yazılım/içerik danışmanlığı, 3. parti uygulama danışmanlığı.',
                'is_active' => true,
                'show_in_menu' => false,
                'menu_mode' => 0,
                'menu_selected_service_ids' => [],
                'menu_excluded_service_ids' => [],
                'display_order' => 3,
                'created_at' => $now, 'updated_at' => $now,
            ],
        ];

        // upsert öncesi JSON alanlarını stringe çevir
        $base = array_map(function ($r) {
            $r['menu_selected_service_ids'] = json_encode($r['menu_selected_service_ids'] ?? []);
            $r['menu_excluded_service_ids'] = json_encode($r['menu_excluded_service_ids'] ?? []);
            return $r;
        }, $base);

        // 2) PDF’ten gelen kategoriler + tüm hizmetleri, menüde göster
        $pdfCats = [
            [
                'name' => 'Dijital Pazarlama',
                'slug' => 'dijital-pazarlama',
                'description' => 'Meta Ads, Google Ads, Criteo, YouTube Ads, TikTok Ads, RTB House ile bütçeye uygun doğru kanal–hedefleme stratejileri.',
                'display_order' => 4,
                'services' => [
                    'Google Ads Kurulum ve Yönetim',
                    'Meta (Facebook/Instagram) Ads Yönetimi',
                    'YouTube Ads Kampanya Yönetimi',
                    'TikTok Ads Kampanyaları',
                    'Criteo / RTB House Programatik',
                    'Dönüşüm İzleme ve Pixel Entegrasyonu',
                ],
            ],
            [
                'name' => 'E-Ticaret Yönetimi',
                'slug' => 'e-ticaret-yonetimi',
                'description' => 'Altyapı & teknik kurulum, entegrasyon ve sistem yönetimi, pazarlama & iletişim kurguları, SEO & içerik, otomasyon ve süreç tasarımı, destek–eğitim–raporlama.',
                'display_order' => 5,
                'services' => [
                    'Altyapı Seçimi ve Kurulum (İkas/Tsoft vb.)',
                    'Tema Kurulumu ve Görsel Düzenleme',
                    'Ödeme/Kargo/ERP Entegrasyonları',
                    'Kampanya ve Otomasyon Kurguları',
                    'Mağaza İçi SEO ve İçerik Yönetimi',
                    'Aylık Raporlama ve KPI Takibi',
                ],
            ],
            [
                'name' => 'Teknik SEO',
                'slug' => 'teknik-seo',
                'description' => 'Crawl budget, XML sitemap & robots.txt, yönlendirme ve HTTP durum kodları, Core Web Vitals, yapısal veri (schema), kanonik & indexleme yönetimi.',
                'display_order' => 6,
                'services' => [
                    'Site Teknik SEO Denetimi (Audit)',
                    'Core Web Vitals İyileştirmeleri',
                    'Schema.org Yapısal Veri Uygulamaları',
                    'Yönlendirme ve Durum Kodları Düzenleme',
                    'Indexleme ve Kanonik Yönetimi',
                    'Site Haritası ve Robots Ayarları',
                ],
            ],
            [
                'name' => 'Danışmanlık',
                'slug' => 'danismanlik',
                'description' => 'Marka danışmanlığı, influencer & UGC, outdoor, TV & radyo, UX & web danışmanlığı, B2B pazarlama.',
                'display_order' => 7,
                'services' => [
                    'Marka ve İletişim Danışmanlığı',
                    'UGC/Influencer Stratejisi',
                    'Medya Planlama (TV/Radio/Outdoor)',
                    'B2B Pazarlama Danışmanlığı',
                    'UX ve Dönüşüm Optimizasyonu',
                ],
            ],
            [
                'name' => 'Görsel Tasarım',
                'slug' => 'gorsel-tasarim',
                'description' => 'Marka odaklı görseller, kreatif kampanya tasarımları, sosyal medya içerik tasarımları, kurumsal kimlik çalışmaları.',
                'display_order' => 8,
                'services' => [
                    'Sosyal Medya İçerik Tasarımları',
                    'Banner ve Kampanya Görselleri',
                    'Kurumsal Kimlik ve Logo',
                    'Landing Page UI Tasarım',
                ],
            ],
            [
                'name' => 'Yazılım ve Mobil Uygulama',
                'slug' => 'yazilim-ve-mobil-uygulama',
                'description' => 'İhtiyaca özel yazılım geliştirme, iOS & Android mobil uygulamalar, entegrasyon ve teknik danışmanlık.',
                'display_order' => 9,
                'services' => [
                    'Özel Yazılım Geliştirme',
                    'API ve Üçüncü Parti Entegrasyonlar',
                    'iOS Mobil Uygulama',
                    'Android Mobil Uygulama',
                    'Bakım ve DevOps Danışmanlığı',
                ],
            ],
        ];

        DB::transaction(function () use ($base, $pdfCats, $now) {
            // a) İlk 3 kategori upsert + restore
            ServiceCategory::upsert(
                $base,
                ['slug'],
                [
                    'name', 'description', 'display_order', 'is_active',
                    'show_in_menu', 'menu_mode',
                    'menu_selected_service_ids', 'menu_excluded_service_ids', 'updated_at'
                ]
            );

            ServiceCategory::withTrashed()
                ->whereIn('slug', array_column($base, 'slug'))
                ->restore();

            // b) PDF kategorileri: menüde göster + tüm hizmetler
            foreach ($pdfCats as $c) {
                $category = ServiceCategory::withTrashed()
                    ->firstOrNew(['slug' => $c['slug']]);

                $category->fill([
                    'name' => $c['name'],
                    'description' => $c['description'],
                    'display_order' => $c['display_order'],
                    'is_active' => true,
                    'show_in_menu' => true,
                    'menu_mode' => 0,
                    'menu_selected_service_ids' => [],
                    'menu_excluded_service_ids' => [],
                    'updated_at' => $now,
                    'created_at' => $category->exists ? $category->created_at : $now,
                ])->save();

                if ($category->trashed()) {
                    $category->restore();
                }

                $order = 1;
                foreach ($c['services'] as $svcName) {
                    $svcSlug = Str::slug($svcName, '-', 'tr');

                    $service = Service::withTrashed()
                        ->where('service_category_id', $category->id)
                        ->where('slug', $svcSlug)
                        ->first();

                    if ($service) {
                        $service->fill([
                            'name' => $svcName,
                            'is_active' => true,
                            'display_order' => $order++,
                            'updated_at' => $now,
                        ])->save();

                        if ($service->trashed()) {
                            $service->restore();
                        }
                    } else {
                        Service::create([
                            'service_category_id' => $category->id,
                            'name' => $svcName,
                            'slug' => $svcSlug,
                            'description' => null,
                            'is_active' => true,
                            'display_order' => $order++,
                            'created_at' => $now, 'updated_at' => $now,
                        ]);
                    }
                }
            }
        });
    }
}
