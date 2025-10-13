<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;
use App\Models\Service;

class ServicesDanismanlikEgitimSeeder extends Seeder
{
    public function run(): void
    {
        $category = ServiceCategory::where('slug', 'danismanlik-ve-egitim-hizmetleri')->firstOrFail();

        $d2m = fn (int $days) => $days * 1440;

        $items = [
            [
                'slug' => 'panel-egitimi',
                'name' => 'Panel Eğitimi',
                'excerpt' => 'Panel kullanımı, rapor okuma ve temel ayarlar eğitimi.',
                'description' => 'Oturum planı çıkarılır. Canlı demo ile temel–ileri modüller gösterilir. Kullanıcı rolleri ve yetkiler anlatılır. Kayıt ve dokümantasyon paylaşılır. Soru–cevap yapılır.',
                'tags' => ['egitim', 'panel', 'dokumantasyon'],
            ],
            [
                'slug' => 'destek-talebi-yonetimi',
                'name' => 'Destek Talebi Yönetimi',
                'excerpt' => 'Ticket süreç tasarımı, SLA ve şablonlar.',
                'description' => 'Kategori ve öncelik setleri kurulur. Yanıt/çözüm süreleri tanımlanır. Makro şablonlar, etiketler ve KPI panosu hazırlanır.',
                'tags' => ['destek', 'ticket', 'sla'],
            ],
            [
                'slug' => 'online-toplanti-ve-canli-destek',
                'name' => 'Online Toplantı ve Canlı Destek',
                'excerpt' => 'Planlı video görüşme ve canlı yardım oturumları.',
                'description' => 'Ajanda oluşturulur. Ekran paylaşımı ile sorun giderme yapılır. Toplantı notu ve aksiyon listesi paylaşılır.',
                'tags' => ['toplanti', 'canli-destek', 'ekran-paylasimi'],
            ],
            [
                'slug' => 'ozel-marka-iletisim-yoneticisi',
                'name' => 'Özel Marka/İletişim Yöneticisi',
                'excerpt' => 'Tek temas noktası ve haftalık koordinasyon.',
                'description' => 'Durum toplantıları, OKR takibi ve paydaş iletişimi yönetilir. Risk ve bağımlılıklar raporlanır.',
                'tags' => ['account-yonetimi', 'koordinasyon', 'okr'],
            ],
            [
                'slug' => 'marka-danismanligi',
                'name' => 'Marka Danışmanlığı',
                'excerpt' => 'Konumlandırma, değer önerisi ve ton rehberi.',
                'description' => 'Persona ve rakip haritası çıkarılır. Mesaj çerçevesi ve görsel dil ilkeleri yazılır. Uygulama örnekleri verilir.',
                'tags' => ['marka', 'konumlandirma', 'mesaj'],
            ],
            [
                'slug' => 'influencer-ugc-danismanligi',
                'name' => 'Influencer & UGC Danışmanlığı',
                'excerpt' => 'Influencer seçimi, sözleşme ve UGC planı.',
                'description' => 'Kısa liste hazırlanır. Brief, hak yönetimi ve teslim standartları belirlenir. Takip ve performans raporu sunulur.',
                'tags' => ['influencer', 'ugc', 'brief'],
            ],
            [
                'slug' => 'billboard-ve-b2b-marketing-danismanligi',
                'name' => 'Billboard ve B2B Marketing Danışmanlığı',
                'excerpt' => 'ATL outdoor ve B2B kanal stratejisi.',
                'description' => 'Lokasyon, bütçe ve GRP hedefi planlanır. B2B için lead kazanım, nurture ve satış senaryoları kurgulanır.',
                'tags' => ['atl', 'b2b', 'lead-nurture'],
            ],
            [
                'slug' => 'ux-gelistirme-danismanligi',
                'name' => 'UX Geliştirme Danışmanlığı',
                'excerpt' => 'Kullanılabilirlik denetimi ve test önerileri.',
                'description' => 'Heuristik analiz, ısı haritası ve oturum kayıtları incelenir. Hipotez listesi ve A/B test önerileri verilir.',
                'tags' => ['ux', 'heuristik', 'ab-test-oneri'],
            ],
            [
                'slug' => 'front-end-gelistirme-danismanligi',
                'name' => 'Front-end Geliştirme Danışmanlığı',
                'excerpt' => 'Performans, erişilebilirlik ve bileşen mimarisi.',
                'description' => 'Lighthouse ve Core Web Vitals analiz edilir. Kod inceleme yapılır, refactor ve dokümantasyon planı yazılır.',
                'tags' => ['frontend', 'web-vitals', 'kod-inceleme'],
            ],
            [
                'slug' => 'yazilim-danismanligi',
                'name' => 'Yazılım Danışmanlığı',
                'excerpt' => 'Mimari, veri modeli ve entegrasyon önerileri.',
                'description' => 'Yol haritası, sprint planı ve kod standartları belirlenir. Güvenlik ve test stratejisi tanımlanır.',
                'tags' => ['mimari', 'entegrasyon', 'guvenlik'],
            ],
            [
                'slug' => 'icerik-yonetimi-danismanligi',
                'name' => 'İçerik Yönetimi Danışmanlığı',
                'excerpt' => 'İçerik takvimi, ton ve çoklu kanal dağıtımı.',
                'description' => 'İçerik sütunları, brief şablonları ve yayın akışı kurulur. SEO uyumu ve ölçüm planı eklenir.',
                'tags' => ['icerik', 'takvim', 'seo-uyum'],
            ],
            [
                'slug' => 'ugc-video-takibi-ve-raporlama',
                'name' => 'UGC Video Takibi ve Raporlama',
                'excerpt' => 'UGC toplama, izin ve performans takibi.',
                'description' => 'Yayın ve link takibi yapılır. İçerik ID eşleme, görüntülenme ve dönüşüm etkisi raporlanır.',
                'tags' => ['ugc-video', 'izin-yonetimi', 'raporlama'],
            ],
            [
                'slug' => '3-parti-uygulama-danismanligi',
                'name' => '3. Parti Uygulama Danışmanlığı',
                'excerpt' => 'Eklenti seçimi, maliyet ve bakım planı.',
                'description' => 'Alternatifler karşılaştırılır. POC planı, entegrasyon yolu ve risk yönetimi dokümante edilir.',
                'tags' => ['3rd-party', 'eklenti', 'poc'],
            ],
        ];

        $order = 1;

        foreach ($items as $data) {
            $payload = [
                'service_category_id' => $category->id,
                'name'                => $data['name'],
                'slug'                => $data['slug'],
                'excerpt'             => $data['excerpt'],
                'description'         => $data['description'],
                'is_active'           => true,
                'is_featured'         => false,
                'display_order'       => $order++,
                'unit'                => 'adet',
                'base_price'          => null,
                'setup_fee'           => null,
                'tax_rate_percent'    => 20,
                'duration_minutes'    => $d2m(7),
            ];

            $service = Service::withTrashed()->where('slug', $data['slug'])->first();

            if ($service) {
                $service->fill($payload);
                $service->restore();
                $service->save();
            } else {
                $service = Service::create($payload);
            }

            $service->syncTags($data['tags']);
        }
    }
}
