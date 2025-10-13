<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;
use App\Models\Service;

class ServicesSiteYonetimiSeeder extends Seeder
{
    public function run(): void
    {
        $category = ServiceCategory::where('slug', 'site-kurulumu-ve-yonetimi')->firstOrFail();

        $d2m = fn (int $days) => $days * 1440;

        $items = [
            [
                'slug' => 'site-kurulum-hizmeti',
                'name' => 'Site Kurulum Hizmeti',
                'excerpt' => 'Domain, hosting ve SSL yapılandırması. Çekirdek kurulum ve temel güvenlik.',
                'description' => 'Alan adı yönlendirmeleri, SSL etkinleştirme, temel güvenlik başlıkları ve CMS/framework kurulumları yapılır. Ortam değişkenleri ayarlanır, canlı ve test ortamı ayrılır.',
                'tags' => ['domain', 'ssl', 'hosting'],
            ],
            [
                'slug' => 'tema-kurulumu-ve-duzenlenmesi',
                'name' => 'Tema Kurulumu ve Düzenlenmesi',
                'excerpt' => 'Tema yükleme, renk/typografi uyarlama ve responsive kontroller.',
                'description' => 'Tema bileşenleri projeye entegre edilir. Marka renkleri, tipografi ve grid sistemi uyarlanır. Mobil ve masaüstü kırılımları test edilip düzeltmeler uygulanır.',
                'tags' => ['tema', 'ozellestirme', 'responsive'],
            ],
            [
                'slug' => 'e-ticaret-altyapi-paket-secimi',
                'name' => 'E-ticaret Altyapı/Paket Seçimi',
                'excerpt' => 'İhtiyaca göre platform karşılaştırması ve paket önerisi.',
                'description' => 'Ölçek, entegrasyon, bütçe ve ekip kabiliyetine göre altyapı analizi yapılır. Artı/eksi ve toplam sahip olma maliyeti üzerinden karar raporu sunulur.',
                'tags' => ['e-ticaret', 'platform-secimi', 'karsilastirma'],
            ],
            [
                'slug' => 'pixel-ve-meta-entegrasyonu',
                'name' => 'Pixel ve Meta Entegrasyonu',
                'excerpt' => 'Meta Pixel ve temel dönüşüm olaylarının devreye alınması.',
                'description' => 'GTM ile temel/gelişmiş e-ticaret olayları tanımlanır. Test ve doğrulama raporu ile teslim edilir.',
                'tags' => ['pixel', 'meta', 'gtm'],
            ],
            [
                'slug' => 'temel-site-ayarlari',
                'name' => 'Temel Site Ayarları',
                'excerpt' => 'Genel ayarlar, meta veriler ve analitik bağlantıları.',
                'description' => 'Site adı, dil, zaman dilimi, meta title/description şablonları ve GA4 bağlantıları yapılandırılır. Çerez ve gizlilik sayfaları bağlanır.',
                'tags' => ['ayarlar', 'analytics', 'gizlilik'],
            ],
            [
                'slug' => 'banner-slider-urun-gorsel-destegi',
                'name' => 'Banner, Slider ve Ürün Görsel Tasarım Desteği',
                'excerpt' => 'Ana sayfa görselleri ve ürün imajlarının hazırlanması.',
                'description' => 'Oranlara uygun kırpma, tipografi hiyerarşisi ve CTA yerleşimleri yapılır. Dosyalar kaynak ve export olarak teslim edilir.',
                'tags' => ['banner', 'slider', 'urun-gorsel'],
            ],
            [
                'slug' => 'kampanya-kurgularinin-olusturulmasi',
                'name' => 'Kampanya Kurgularının Oluşturulması',
                'excerpt' => 'İndirim/kupon, sepet kuralı ve sezon kurguları.',
                'description' => 'Kampanya koşulları, segmentler ve görünürlük kuralları tanımlanır. Takvim ve raporlama şablonları hazırlanır.',
                'tags' => ['kampanya', 'funnel', 'kupon'],
            ],
            [
                'slug' => 'pazarlama-iceriklerinin-olusturulmasi',
                'name' => 'Pazarlama İçeriklerinin Oluşturulması',
                'excerpt' => 'Kategori, ürün ve landing metinleri.',
                'description' => 'SEO uyumlu başlıklar, açıklamalar ve CTA metinleri üretilir. İçerik takvimiyle yayın akışı planlanır.',
                'tags' => ['icerik', 'metin', 'gorsel'],
            ],
            [
                'slug' => 'otomasyon-kurgularinin-olusturulmasi',
                'name' => 'Otomasyon Kurgularının Oluşturulması',
                'excerpt' => 'Tetikleyici bazlı e-posta/SMS akışları.',
                'description' => 'Hoş geldin, sepet hatırlatma, yeniden kazanım ve çapraz satış akışları kurulur. Zamanlama ve frekans sınırları optimize edilir.',
                'tags' => ['otomasyon', 'workflow', 'tetikleyici'],
            ],
            [
                'slug' => 'rakip-analizi-ve-iyilestirme',
                'name' => 'Rakip Analizi ve İyileştirme',
                'excerpt' => 'Fiyat, teklif ve UX kıyaslaması.',
                'description' => 'Rakiplerin kategori yapısı, filtreleri ve ödeme/kargo deneyimi incelenir. Hızlı iyileştirme listesi çıkarılır.',
                'tags' => ['rakip-analizi', 'benchmark', 'iyilestirme'],
            ],
            [
                'slug' => 'sms-mail-marketing-site',
                'name' => 'SMS & Mail Marketing',
                'excerpt' => 'Segment bazlı gönderimler ve akışlar.',
                'description' => 'Konu satırı, gönderim zamanı ve teklif testleriyle performans iyileştirilir. Teslim edilebilirlik takip edilir.',
                'tags' => ['sms', 'email', 'segment'],
            ],
            [
                'slug' => 'akilli-bildirimler',
                'name' => 'Akıllı Bildirimler',
                'excerpt' => 'Web push ve onsite bildirim kurguları.',
                'description' => 'Segmentlere göre tetiklenen bildirimler kurgulanır. Abonelik izni ve kapatma senaryoları tasarlanır.',
                'tags' => ['push', 'webpush', 'bildirim'],
            ],
            [
                'slug' => 'musteri-segmentasyonu',
                'name' => 'Müşteri Segmentasyonu',
                'excerpt' => 'RFM ve davranış temelli bölümlendirme.',
                'description' => 'Satın alma sıklığı, sepet tutarı ve etkileşim sinyalleriyle segmentler oluşturulur. Hedefli kampanyalar planlanır.',
                'tags' => ['segmentasyon', 'rfm', 'kumeleme'],
            ],
            [
                'slug' => 'pazaryeri-entegrasyonu-ve-surec-yonetimi',
                'name' => 'Pazaryeri Entegrasyonu ve Süreç Yönetimi',
                'excerpt' => 'Ürün aktarımı, sipariş ve stok senkronu.',
                'description' => 'Katalog haritalama, fiyat/indirim senkronu ve iade süreçleri yapılandırılır. KPI takibi için raporlar eklenir.',
                'tags' => ['pazaryeri', 'entegrasyon', 'listing'],
            ],
            [
                'slug' => 'muhasebe-programlari-entegrasyon-destegi',
                'name' => 'Muhasebe Programları Entegrasyon Desteği',
                'excerpt' => 'Fatura, cari ve muhasebe entegrasyonları.',
                'description' => 'Stok, cari ve e-belge akışları senkronize edilir. Test senaryoları ve günlüklemede hata yakalama eklenir.',
                'tags' => ['muhasebe', 'entegrasyon', 'e-belge'],
            ],
            [
                'slug' => 'entegrator-yazilim-kurulumu',
                'name' => 'Entegratör Yazılım Kurulumu',
                'excerpt' => 'Entegratör kurulum ve temel yapılandırma.',
                'description' => 'API anahtarları, webhook ve cron görevleri tanımlanır. İlk veri akışı test edilip loglar izlenir.',
                'tags' => ['entegrator', 'kurulum', 'api'],
            ],
            [
                'slug' => 'entegrator-yazilim-yonetim-hizmeti',
                'name' => 'Entegratör Yazılım Yönetim Hizmeti',
                'excerpt' => 'Günlük izleme ve sorun giderme.',
                'description' => 'Hata kuyruğu takibi, yeniden denemeler ve sürüm güncellemeleri yönetilir. SLA çerçevesinde raporlanır.',
                'tags' => ['entegrator', 'isletim', 'destek'],
            ],
            [
                'slug' => 'kargo-entegrasyonu-ve-surec-iyilestirmeleri',
                'name' => 'Kargo Entegrasyonu ve Süreç İyileştirmeleri',
                'excerpt' => 'Kargo API, fiyat ve teslimat akışları.',
                'description' => 'Siparişten teslimata süreç haritası çıkarılır. Takip linki ve bildirim akışları entegre edilir.',
                'tags' => ['kargo', 'takip', 'otomasyon'],
            ],
            [
                'slug' => 'odeme-sistemi-kurulumu-ve-surec-yonetimi',
                'name' => 'Ödeme Sistemi Kurulumu ve Süreç Yönetimi',
                'excerpt' => 'POS entegrasyonu, taksit ve fraud kontrolleri.',
                'description' => '3D Secure, iade/iptal akışları ve hata kodu haritaları kurulup test edilir. Uptime izleme eklenir.',
                'tags' => ['odeme', 'pos', 'fraud'],
            ],
            [
                'slug' => 'temel-seviye-seo-destegi',
                'name' => 'Temel Seviye SEO Desteği',
                'excerpt' => 'Site içi temel SEO düzenlemeleri.',
                'description' => 'Başlık, açıklama, H yapısı, dahili linkler ve robots/sitemap ayarları optimize edilir.',
                'tags' => ['seo', 'site-ici', 'teknik'],
            ],
            [
                'slug' => 'statik-sayfalarin-olusturulmasi-ve-duzenlenmesi',
                'name' => 'Statik Sayfaların Oluşturulması ve Düzenlenmesi',
                'excerpt' => 'Hakkımızda, KVKK, iade/teslimat gibi sayfalar.',
                'description' => 'Şablonlar markaya uyarlanır. İçerik versiyonlama ve onay akışı tanımlanır.',
                'tags' => ['sayfalar', 'hakkimizda', 'kvkk'],
            ],
            [
                'slug' => 'sistem-yapilandirmalari',
                'name' => 'Sistem Yapılandırmaları',
                'excerpt' => 'Cache, queue ve bakım modları.',
                'description' => 'Önbellek stratejisi, kuyruk işçileri ve bakım mod senaryoları yapılandırılır. Log rotasyonu uygulanır.',
                'tags' => ['sunucu', 'cache', 'guncelleme'],
            ],
            [
                'slug' => 'teknik-destek-hizmeti',
                'name' => 'Teknik Destek Hizmeti',
                'excerpt' => 'Ticket tabanlı yardım ve izleme.',
                'description' => 'SLA metrikleri, önceliklendirme ve kök neden analizleri ile sürekli destek sağlanır.',
                'tags' => ['ticket', 'sla', 'destek'],
            ],
            [
                'slug' => 'gunluk-performans-raporu',
                'name' => 'Günlük Performans Raporu',
                'excerpt' => 'Temel metriklerin günlük özeti.',
                'description' => 'Trafik, dönüşüm ve gelir eğrileri günlük olarak raporlanır. Anomaliler işaretlenir.',
                'tags' => ['rapor', 'gunluk', 'kpi'],
            ],
            [
                'slug' => 'haftalik-performans-raporu-site',
                'name' => 'Haftalık Performans Raporu',
                'excerpt' => 'Haftalık trendler ve aksiyon listesi.',
                'description' => 'Kanal/kategori bazlı içgörüler ve bir sonraki hafta için öneriler paylaşılır.',
                'tags' => ['rapor', 'haftalik', 'kpi'],
            ],
            [
                'slug' => 'aylik-performans-raporu-site',
                'name' => 'Aylık Performans Raporu',
                'excerpt' => 'Aylık hedef karşılaştırması ve plan.',
                'description' => 'Hedef sapmaları, öğrenimler ve izleyen ay yol haritası sunulur.',
                'tags' => ['rapor', 'aylik', 'roas'],
            ],
            [
                'slug' => 'urun-guncellemeleri',
                'name' => 'Ürün Güncellemeleri (Opsiyonel)',
                'excerpt' => 'Ürün, fiyat ve stok güncellemeleri.',
                'description' => 'Toplu içe aktarma, varyant eşleme ve görsel güncellemeleri planlanır ve uygulanır.',
                'tags' => ['urun', 'stok', 'fiyat'],
            ],
            [
                'slug' => 'mobil-uygulama-kurulum-destegi',
                'name' => 'Mobil Uygulama Kurulum Desteği (Opsiyonel)',
                'excerpt' => 'Mobil uygulama/PWA temel kurulumları.',
                'description' => 'PWA yapılandırma, bildirim izinleri ve mağaza yayın süreçleri için rehberlik sağlanır.',
                'tags' => ['mobil', 'kurulum', 'pwa'],
            ],
            [
                'slug' => 'b2b-satis-sistemi-kurulum-ve-yonetimi',
                'name' => 'B2B Satış Sistemi Kurulum ve Yönetimi (Opsiyonel)',
                'excerpt' => 'Bayi kaydı, fiyat listeleri ve teklif akışları.',
                'description' => 'Rol/izin setleri, bayi onay süreçleri ve toplu teklif mekanikleri kurulur. Raporlama ekranları yapılandırılır.',
                'tags' => ['b2b', 'bayi', 'teklif'],
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
