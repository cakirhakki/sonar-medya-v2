<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Service;
use App\Models\ServiceCategory;

class ServicesReklamPerformansSeeder extends Seeder
{
    public function run(): void
    {
        $category = ServiceCategory::query()
            ->where('slug', 'reklam-ve-performans-yonetimi')
            ->firstOrFail();

        $d2m = fn (int $days) => $days * 1440;

        $items = [
            [
                'slug' => 'isletme-hesap-kurulumu-ve-baglantilari',
                'name' => 'İşletme Hesap Kurulumu ve Bağlantıları',
                'excerpt' => 'Meta Business, Google Ads, Merchant, GA4 ve Search Console hesaplarının açılması, doğrulanması ve birbirine bağlanması. Yetkilendirme ve erişim rolleri düzenlenir.',
                'description' => 'Marka adına gerekli tüm reklam ve analitik hesapları oluşturulur, domain ve işletme doğrulamaları yapılır. Merchant Center, GA4 ve Search Console bağlantıları kurulur, dönüşüm hedefleri tanımlanır. Erişim rolleri ve güvenlik ayarları standardize edilir. Tüm kurulumlar test edilip raporlanır.',
                'tags' => ['hesap-kurulumu', 'dogrulama', 'platform-baglanti'],
            ],
            [
                'slug' => 'meta-ads-yonetimi',
                'name' => 'Meta Ads Yönetimi',
                'excerpt' => 'Meta üzerinde kampanya mimarisi, hedefleme, bütçe kontrolü ve yaratıcı testleri. ROAS’ı artırmaya odaklı sürekli optimizasyon.',
                'description' => 'Trafik, Dönüşüm ve Katalog kampanyaları kurulur. İlgi alanı, davranış ve lookalike hedeflemeleri planlanır. Bütçe, yerleşim ve teklif stratejileri test edilir. Kreatif varyasyonlar A/B testleriyle ölçülür, düşük performanslı kombinasyonlar elenir. Haftalık içgörülerle optimizasyon döngüsü sürdürülür.',
                'tags' => ['meta-ads', 'kampanya-yonetimi', 'optimizasyon'],
            ],
            [
                'slug' => 'google-reklam-yonetimi',
                'name' => 'Google Reklam Yönetimi',
                'excerpt' => 'Arama, Görüntülü ve Alışveriş kampanyaları için anahtar kelime ve feed stratejisi. Kalite puanı ve CPA odaklı optimizasyon.',
                'description' => 'Match type mimarisi (exact, phrase, broad) oluşturulur. Negatif anahtar kelime listeleri ve reklam uzantıları düzenlenir. Performance Max ve Shopping kampanyaları ürün feed’ine göre yapılandırılır. Teklif stratejileri (tROAS/tCPA) test edilerek maliyet-etkin ölçekleme sağlanır.',
                'tags' => ['google-ads', 'arama-agi', 'shopping'],
            ],
            [
                'slug' => 'pixel-kurulumu-ve-entegrasyonu',
                'name' => 'Pixel Kurulumu ve Entegrasyonu',
                'excerpt' => 'Meta Pixel ve Google etiketlerinin GTM ile kurulumu. Dönüşüm olayları ve e-ticaret ölçümleri doğrulanır.',
                'description' => 'GTM üzerinden temel ve gelişmiş e-ticaret olayları tanımlanır. Consent mode ve cross-domain ölçüm gereksinimleri değerlendirilir. Test modunda tetikleyiciler ve veri katmanı kontrol edilir. Hata ayıklama notları ve doğrulama ekran görüntüleri teslim edilir.',
                'tags' => ['pixel', 'gtm', 'izleme-altyapisi'],
            ],
            [
                'slug' => 'is-akisi-ve-icerik-plani-olusturma',
                'name' => 'İş Akışı ve İçerik Planı Oluşturma',
                'excerpt' => 'Aylık takvim, kampanya dönemleri ve üretim akışı. İçerik temaları, formatları ve teslim tarihleri netleştirilir.',
                'description' => 'Kampanya dönemleri ve indirim pencereleri belirlenir. İçerik türleri (story, reel, statik, blog) ve platform dağılımı planlanır. Brif şablonları, onay süreçleri ve teslim SLA’ları tanımlanır. Takvim paylaşılıp ekip sorumlulukları atanır.',
                'tags' => ['icerik-plani', 'yayın-takvimi', 'operasyon'],
            ],
            [
                'slug' => 'hedef-kitle-optimizasyonu',
                'name' => 'Hedef Kitle Optimizasyonu',
                'excerpt' => 'Segmentasyon, lookalike üretimi ve yeniden pazarlama kurguları. Kitle kırılımları performansa göre rafine edilir.',
                'description' => 'Pixel, GA4 ve CRM verileri kullanılarak ısı haritası çıkarılır. Yeni kullanıcı, sepet, satın alma ve içerik etkileşimi segmentleri oluşturulur. Frekans sınırları ve hariç tutmalar ile verimli gösterim sağlanır. Kitle performansı periyodik olarak kıyaslanıp temize çekilir.',
                'tags' => ['segmentasyon', 'lookalike', 'retargeting'],
            ],
            [
                'slug' => 'kampanya-amaci-ve-stratejileri-olusturma',
                'name' => 'Kampanya Amacı ve Stratejileri Oluşturma',
                'excerpt' => 'Huni temelli kampanya mimarisi ve bütçe dağılımı. Amaç, başarı metriği ve test hipotezleri tanımlanır.',
                'description' => 'TOFU-MOFU-BOFU kurgusu çıkarılır. Kanal ve format bazında rol tanımı yapılır. Başarı metrikleri (CPA, ROAS, CTR, CVR) ve beklenen aralıklar netleştirilir. Test hipotezleri yazılır, deney takvimi ve karar kriterleri belirlenir.',
                'tags' => ['strateji', 'funnel', 'butce-plani'],
            ],
            [
                'slug' => 'reklam-metni-olusturma',
                'name' => 'Reklam Metni Oluşturma',
                'excerpt' => 'Başlık, açıklama ve CTA kütüphanesi. USP ve itiraz kırıcı mesajlar A/B testleriyle doğrulanır.',
                'description' => 'Farklı niyet düzeylerine uygun mesaj çerçevesi hazırlanır. Başlık ve açıklamalar karakter sınırlarına uygun optimize edilir. Dinamik keyword insertion ve site link metinleri geliştirilir. Test sonuçlarına göre metin kütüphanesi sürümlenir.',
                'tags' => ['copywriting', 'ab-test', 'cta'],
            ],
            [
                'slug' => 'rekabet-analizi-marka-urun',
                'name' => 'Rekabet Analizi (Marka & Ürün)',
                'excerpt' => 'Rakip teklifleri, kreatif yaklaşımlar ve anahtar kelime boşlukları. Fiyat ve değer önerisi karşılaştırmaları yapılır.',
                'description' => 'Rakiplerin kampanya yapıları ve kreatif örnekleri incelenir. Anahtar kelime payı, SERP özellikleri ve açılış sayfası deneyimi analiz edilir. Fiyatlandırma, promosyon ve kargo politikaları kıyaslanır. Farklılaşma alanları için öneri listesi üretilir.',
                'tags' => ['rekabet-analizi', 'benchmark', 'pazar-haritasi'],
            ],
            [
                'slug' => 'anahtar-kelime-yonetimi',
                'name' => 'Anahtar Kelime Yönetimi',
                'excerpt' => 'Seed, long-tail ve negatif listelerle arama mimarisi. Sorgu raporlarıyla sürekli rafine edilir.',
                'description' => 'Kısa ve uzun kuyruklu kelimeler niyet düzeyine göre gruplandırılır. Negatif listeler düzenli güncellenir. Arama terimi raporlarından yeni fırsatlar çıkarılır. Reklam metni ve açılış sayfası ile alaka puanı artırılır.',
                'tags' => ['keyword-mimarisi', 'long-tail', 'negatif-list'],
            ],
            [
                'slug' => 'sms-mail-marketing',
                'name' => 'SMS & Mail Marketing',
                'excerpt' => 'Zamanlanmış akışlar ve kampanya gönderimleri. Segment bazlı içerik ve tetikleyici otomasyonlar kurulur.',
                'description' => 'Hoş geldin, sepet hatırlatma, kazanım ve yeniden etkinleştirme akışları hazırlanır. İçerikler segmentlere göre kişiselleştirilir. A/B testleriyle konu satırı, gönderim zamanı ve teklif optimizasyonu yapılır. Teslim edilebilirlik ve şikayet oranları izlenir.',
                'tags' => ['sms', 'email-marketing', 'otomasyon-akislari'],
            ],
            [
                'slug' => 'haftalik-performans-raporu',
                'name' => 'Haftalık Performans Raporu',
                'excerpt' => 'Kısa döngü metrik özeti ve hızlı aksiyon listesi. Trend kırılmaları tespit edilir.',
                'description' => 'KPI tablosu ve geçen haftaya göre değişimler sunulur. Kanal, kampanya ve kreatif bazlı öne çıkanlar listelenir. Sorunlu alanlar için pratik aksiyonlar ve test önerileri verilir. Rapor tek sayfalık özete indirgenir.',
                'tags' => ['haftalik-rapor', 'kpi-takibi', 'dashboard'],
            ],
            [
                'slug' => 'aylik-performans-raporu',
                'name' => 'Aylık Performans Raporu',
                'excerpt' => 'ROAS, ROI ve bütçe kullanımının derin analizi. Öğrenimler ve sonraki ayın planı netleştirilir.',
                'description' => 'Aylık hedef karşılaştırmaları ve eğilim grafikleri sunulur. Kanal/kampanya katkı analizi ve marj hassasiyeti değerlendirilir. Öğrenimler, iptal edilen testler ve başarılı deneyler özetlenir. Sonraki ay için yol haritası ve bütçe önerisi yazılır.',
                'tags' => ['aylik-rapor', 'roas', 'roi'],
            ],
            [
                'slug' => 'tiktok-for-business-reklam-yonetimi',
                'name' => 'TikTok for Business Reklam Yönetimi',
                'excerpt' => 'Kısa video odaklı kampanya kurguları. Hook, tempo ve kopya varyasyonları test edilir.',
                'description' => 'Native içerik formatlarına uygun kreatif üretim brifi hazırlanır. Geniş hedefleme ve ilgi kümeleri test edilir. Hook-first yaklaşım ile ilk 3 saniyede dikkat optimizasyonu yapılır. CPA ve izlenme oranlarına göre ölçekleme planlanır.',
                'tags' => ['tiktok-ads', 'kisa-video', 'hook-test'],
            ],
            [
                'slug' => 'youtube-reklam-yonetimi',
                'name' => 'YouTube Reklam Yönetimi',
                'excerpt' => 'In-Stream, In-Feed ve Shorts taktikleri. Yerleşim ve hedefleme kombinasyonları karşılaştırılır.',
                'description' => 'Kampanyalar huni aşamalarına göre ayrılır. Sıcak kitleler ve benzer kitleler ayrı test edilir. Kreatif uzunluğu ve mesaj yoğunluğu kıyaslanır. Brand lift ve dönüşüm metrikleri birlikte değerlendirilir.',
                'tags' => ['youtube-ads', 'video-kampanya', 'brand-lift'],
            ],
            [
                'slug' => 'kreatif-reklam-gorseli-tasarimi',
                'name' => 'Kreatif Reklam Görseli Tasarımı',
                'excerpt' => 'Platform ve oranlara uygun statik/animasyon setleri. Net değer önerisi ve güçlü CTA vurgusu.',
                'description' => 'Formatlara göre kırpma ve tipografi hiyerarşisi belirlenir. Ürün odak, fayda odak ve sosyal kanıt temaları için varyasyonlar üretilir. A/B testinden gelen sonuçlarla iterasyon yapılır. Teslimat dosyaları kaynak ve export halinde paylaşılır.',
                'tags' => ['kreatif-set', 'gorsel-uretim', 'animasyon'],
            ],
            [
                'slug' => 'sosyal-medya-danismanligi',
                'name' => 'Sosyal Medya Danışmanlığı',
                'excerpt' => 'Platform stratejisi, içerik takvimi ve büyüme playbook’u. Performans takip çerçevesi kurulur.',
                'description' => 'Hedef, ton ve içerik sütunları tanımlanır. Topluluk yönetimi süreçleri ve geri bildirim döngüsü yazılır. Raporlama şablonları ve performans barajları belirlenir. Ekibe periyodik kontrol listeleri verilir.',
                'tags' => ['sosyal-medya', 'icerik-stratejisi', 'buyume'],
            ],
            [
                'slug' => '3-parti-uygulama-reklamlari-criteo-rte-house',
                'name' => '3. Parti Uygulama Reklamları (Criteo / RTE House)',
                'excerpt' => 'Katalog bazlı dinamik retargeting. Feed kalitesi ve envanter eşleşmesi optimize edilir.',
                'description' => 'Ürün kataloğu alanları ve kategoriler temizlenir. Segment bazlı teklif ve sıklık sınırları ayarlanır. Dönüşüm penceresi ve atıf modeli gözden geçirilir. Kampanya katkısı kanal karması içinde raporlanır.',
                'tags' => ['criteo', 'rte-house', 'dinamik-urun'],
            ],
            [
                'slug' => 'tv-ve-radyo-reklam-danismanligi',
                'name' => 'TV ve Radyo Reklam Danışmanlığı',
                'excerpt' => 'ATL planlama, medya satın alma ve ölçümleme çerçevesi. Dijital ile bütünleşik plan yapılır.',
                'description' => 'Hedef GRP ve yayın planı çıkarılır. Zaman kuşağı ve program seçimi yapılır. Uçtan uca kampanya koordinasyonu ve içerik uyarlamaları yönetilir. Sonuçlar dijital metriklerle birlikte yorumlanır.',
                'tags' => ['tv-reklam', 'radyo-reklam', 'atl-planlama'],
            ],
        ];

        $order = 1;

        foreach ($items as $data) {
            $payload = [
                'service_category_id' => $category->id,
                'name'                => $data['name'],
                'slug'                => $data['slug'], // sabit, idempotent
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

            // Etiketleri idempotent şekilde ayarla
            $service->syncTags($data['tags']);
        }
    }
}
