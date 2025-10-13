<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesSiteYonetimiFaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqMap = [
            'site-kurulum-hizmeti' => [
                ['q' => 'Kurulumda neler yapılır?', 'a' => 'Domain yönlendirme, SSL, CMS/framework kurulumu, temel güvenlik ve ortam ayarları yapılır.'],
                ['q' => 'Kurulum süresi nedir?', 'a' => 'Hazırlıklar tam ise 1-3 iş günü içinde tamamlanır.'],
                ['q' => 'Mevcut hosting kullanılabilir mi?', 'a' => 'Evet. Asgari PHP/DB sürümleri ve kaynaklar uygunsa mevcut altyapı kullanılır.'],
            ],
            'tema-kurulumu-ve-duzenlenmesi' => [
                ['q' => 'Temayı siz mi seçiyorsunuz?', 'a' => 'İhtiyaçlara göre kısa liste çıkarır, onayınıza göre kurulum yaparız.'],
                ['q' => 'Mobil uyumluluk nasıl doğrulanır?', 'a' => 'Break-point testleri ve Lighthouse kontrolleri ile.'],
                ['q' => 'Demo içerik aktarımı yapılıyor mu?', 'a' => 'Tema demoları içeri aktarılır, gereksiz bileşenler temizlenir.'],
            ],
            'e-ticaret-altyapi-paket-secimi' => [
                ['q' => 'Seçimde hangi kriterlere bakılır?', 'a' => 'Ürün sayısı, entegrasyon ihtiyacı, ekip kabiliyeti, bütçe ve TCO.'],
                ['q' => 'Taşınabilirlik nasıl sağlanır?', 'a' => 'Veri şeması ve API kabiliyetleri dikkate alınarak öneri sunulur.'],
                ['q' => 'Lisans maliyeti nasıl değerlendirilir?', 'a' => 'Aylık/yıllık bedeller ve gizli maliyetler karşılaştırılır.'],
            ],
            'pixel-ve-meta-entegrasyonu' => [
                ['q' => 'Hangi olaylar kurulur?', 'a' => 'ViewContent, AddToCart, InitiateCheckout, Purchase gibi temel ve gelişmiş olaylar.'],
                ['q' => 'GTM zorunlu mu?', 'a' => 'Önerilir. Yönetim ve versiyonlama kolaylığı sağlar.'],
                ['q' => 'Testi nasıl yapıyorsunuz?', 'a' => 'GTM preview ve ağ istekleri ile tetikleyici/parametre doğrulaması yapılır.'],
            ],
            'temel-site-ayarlari' => [
                ['q' => 'Hangi ayarlar yapılır?', 'a' => 'Site adı, dil, saat dilimi, logo/favikon ve temel meta şablonları.'],
                ['q' => 'Analitik entegrasyonu var mı?', 'a' => 'GA4 ve gerekirse Search Console bağlantıları yapılır.'],
                ['q' => 'Çok dilli yapı desteklenir mi?', 'a' => 'Altyapıya bağlıdır. Uygunsa dil anahtarlamaları hazırlanır.'],
            ],
            'banner-slider-urun-gorsel-destegi' => [
                ['q' => 'Hangi formatlarda teslim edilir?', 'a' => 'Kaynak dosya ve web için optimize export setleri.'],
                ['q' => 'Revizyon sayısı nedir?', 'a' => 'Genelde iki tur revizyon dahildir. Fazlası ek çalışma olarak ele alınır.'],
                ['q' => 'Görseller SEO’ya uygun mu?', 'a' => 'Alt metin, dosya adı ve boyut optimizasyonu uygulanır.'],
            ],
            'kampanya-kurgularinin-olusturulmasi' => [
                ['q' => 'Ne tür kampanyalar kurgulanır?', 'a' => 'Kupon, sepet kuralı, kategori indirimi ve dönemsel kampanyalar.'],
                ['q' => 'Kupon koşulları nasıl yönetilir?', 'a' => 'Minimum sepet, tarih aralığı ve kullanım adedi tanımlanır.'],
                ['q' => 'Zamanlama nasıl yapılır?', 'a' => 'Takvim ve tetikleyicilerle otomatik başlatma/bitirme ayarlanır.'],
            ],
            'pazarlama-iceriklerinin-olusturulmasi' => [
                ['q' => 'Hangi içerikler hazırlanır?', 'a' => 'Kategori/ürün metinleri, landing sayfası kopyaları ve CTA metinleri.'],
                ['q' => 'SEO uyumu sağlanır mı?', 'a' => 'Anahtar kelime ve HTML hiyerarşisine uygun yazılır.'],
                ['q' => 'Onay süreci nasıl işler?', 'a' => 'Taslak paylaşılır, yorumlarla revize edilip yayına alınır.'],
            ],
            'otomasyon-kurgularinin-olusturulmasi' => [
                ['q' => 'Hangi akışlar kurulur?', 'a' => 'Hoş geldin, sepet hatırlatma, yeniden kazanım ve çapraz satış.'],
                ['q' => 'Frekans sınırı uygulanır mı?', 'a' => 'Evet. Kanala ve segmente göre frekans limiti tanımlanır.'],
                ['q' => 'Hangi araçlar desteklenir?', 'a' => 'Kullanılan ESP/SMS sağlayıcısına göre kurulum yapılır.'],
            ],
            'rakip-analizi-ve-iyilestirme' => [
                ['q' => 'Neleri inceliyorsunuz?', 'a' => 'Kategori yapısı, filtreler, fiyat, kargo ve ödeme deneyimi.'],
                ['q' => 'Çıktı nedir?', 'a' => 'Hızlı kazanımlar ve önceliklendirilmiş iyileştirme listesi.'],
                ['q' => 'Periyot ne olur?', 'a' => 'Ayda bir temel kontrol, ihtiyaçta ara güncellemeler.'],
            ],
            'sms-mail-marketing-site' => [
                ['q' => 'İzin yönetimi nasıl yapılır?', 'a' => 'KVKK’ya uygun izin kayıtları ve tercih merkezi kurulur.'],
                ['q' => 'Performans nasıl artırılır?', 'a' => 'Konu satırı, zamanlama ve teklif A/B testleri yapılır.'],
                ['q' => 'Başlangıç entegrasyonu var mı?', 'a' => 'ESP/SMS sağlayıcı bağlantıları ve şablonlar kurulur.'],
            ],
            'akilli-bildirimler' => [
                ['q' => 'Hangi bildirim türleri?', 'a' => 'Web push, onsite bar ve çıkış niyeti bildirimleri.'],
                ['q' => 'İzin oranı nasıl artırılır?', 'a' => 'Doğru zamanlama ve değer önerisiyle mikro kancalar kullanılır.'],
                ['q' => 'Tarayıcı desteği nedir?', 'a' => 'Modern tarayıcılar desteklenir; kısıtlar dokümante edilir.'],
            ],
            'musteri-segmentasyonu' => [
                ['q' => 'Hangi yöntemler kullanılır?', 'a' => 'RFM, davranış ve ürün ilgisi temelli segmentasyon.'],
                ['q' => 'RFM nasıl hesaplanır?', 'a' => 'Yakınlık, sıklık ve parasal değer skoru hesaplanır.'],
                ['q' => 'Segmentler nerede kullanılır?', 'a' => 'Kampanyalar, otomasyonlar ve kişiselleştirme akışlarında.'],
            ],
            'pazaryeri-entegrasyonu-ve-surec-yonetimi' => [
                ['q' => 'Hangi pazaryerleri desteklenir?', 'a' => 'Kullanılan entegratöre bağlı yaygın pazar yerleri.'],
                ['q' => 'Stok senkronu nasıl sağlanır?', 'a' => 'Sipariş sonrası stok düşümü ve periyodik senkron kurulur.'],
                ['q' => 'İade akışı yönetiliyor mu?', 'a' => 'Politikalara göre iade ve iptal akışları tanımlanır.'],
            ],
            'muhasebe-programlari-entegrasyon-destegi' => [
                ['q' => 'Hangi yazılımlar entegre edilir?', 'a' => 'Yaygın muhasebe/ERP çözümleri, API kabiliyetine göre.'],
                ['q' => 'E-belge akışları var mı?', 'a' => 'E-fatura/e-arşiv akışları ve numara serileri kurgulanır.'],
                ['q' => 'Cari mutabakat yapılır mı?', 'a' => 'Cari hareketleri senkronlanır, periyodik mutabakat raporu alınır.'],
            ],
            'entegrator-yazilim-kurulumu' => [
                ['q' => 'Gereken bilgiler nelerdir?', 'a' => 'API anahtarları, endpoint, kullanıcı bilgileri ve yetkiler.'],
                ['q' => 'Güvenlik nasıl sağlanır?', 'a' => 'IP kısıtları, token saklama ve rol bazlı erişim ayarlanır.'],
                ['q' => 'Loglama yapılıyor mu?', 'a' => 'Webhook ve cron işlerine log tutulur, hata uyarıları yapılandırılır.'],
            ],
            'entegrator-yazilim-yonetim-hizmeti' => [
                ['q' => 'Neleri izlersiniz?', 'a' => 'Görev kuyrukları, başarısız istekler ve geri denemeler.'],
                ['q' => 'SLA nedir?', 'a' => 'Önceliğe göre yanıt ve çözüm süreleri tanımlıdır.'],
                ['q' => 'Güncellemeleri kim yapar?', 'a' => 'Sürüm notlarına göre planlı yükseltmeler yapılır.'],
            ],
            'kargo-entegrasyonu-ve-surec-iyilestirmeleri' => [
                ['q' => 'Hangi kargo entegrasyonları?', 'a' => 'Sağlayıcınızın API’si ile sipariş, etiket ve takip entegrasyonu.'],
                ['q' => 'Takip linki nasıl çalışır?', 'a' => 'Sipariş durumuna göre otomatik bildirim ve link paylaşımı yapılır.'],
                ['q' => 'Fiyat hesaplama desteklenir mi?', 'a' => 'Ağırlık/bölge tablosuna göre dinamik hesaplama yapılabilir.'],
            ],
            'odeme-sistemi-kurulumu-ve-surec-yonetimi' => [
                ['q' => 'Hangi sanal POS’lar?', 'a' => 'Banka POS’ları ve yaygın ödeme ağ geçitleri.'],
                ['q' => 'Fraud önlemleri neler?', 'a' => '3D Secure, hız limitleri, risk skoru ve kara liste kontrolleri.'],
                ['q' => 'İade/iptal süreci nasıl?', 'a' => 'Sipariş durumu ile entegre ters işlem akışları tanımlanır.'],
            ],
            'temel-seviye-seo-destegi' => [
                ['q' => 'Kapsam nedir?', 'a' => 'Title, meta, H yapısı, dahili linkler ve robots/sitemap ayarları.'],
                ['q' => 'Hız iyileştirme yapılıyor mu?', 'a' => 'Temel öneriler sunulur, uygulama altyapıya göre değişir.'],
                ['q' => 'Raporlama sağlanır mı?', 'a' => 'Öncesi/sonrası kontrol listesi ve izleme notları verilir.'],
            ],
            'statik-sayfalarin-olusturulmasi-ve-duzenlenmesi' => [
                ['q' => 'Hangi sayfalar hazırlanır?', 'a' => 'Hakkımızda, KVKK, iade/teslimat ve iletişim gibi temel sayfalar.'],
                ['q' => 'Hukuki metinleri siz mi yazıyorsunuz?', 'a' => 'Şablon uyarlaması yapılır. Nihai metin için hukuk onayı gerekir.'],
                ['q' => 'Güncelleme nasıl yapılır?', 'a' => 'Versiyonlanır ve değişiklik günlüğüne işlenir.'],
            ],
            'sistem-yapilandirmalari' => [
                ['q' => 'Cache ve queue nasıl ayarlanır?', 'a' => 'Sürücü seçimi, TTL değerleri ve işçi süreçleri yapılandırılır.'],
                ['q' => 'Bakım modu ne zaman kullanılır?', 'a' => 'Deploy ve kritik bakım sırasında kontrollü şekilde.'],
                ['q' => 'Ortam değişkenleri nasıl yönetilir?', 'a' => 'Gizli anahtarlar .env altında, erişim kısıtlarıyla tutulur.'],
            ],
            'teknik-destek-hizmeti' => [
                ['q' => 'Destek kanalı nedir?', 'a' => 'Ticket sistemi ve e-posta ile talepler alınır.'],
                ['q' => 'Yanıt süresi ne kadar?', 'a' => 'SLA’ya göre önceliklendirilir. Kritik konular önceliklidir.'],
                ['q' => 'Kapsam dışı işler?', 'a' => 'Yeni geliştirme ve büyük tasarım işleri proje kapsamına alınır.'],
            ],
            'gunluk-performans-raporu' => [
                ['q' => 'Hangi metrikler raporlanır?', 'a' => 'Trafik, dönüşüm, gelir, sepet ve hatalı istek trendleri.'],
                ['q' => 'Rapor formatı nedir?', 'a' => 'Özet tablo ve kısa yorumlarla e-posta paylaşımı.'],
                ['q' => 'Uyarı eşiği var mı?', 'a' => 'Kritik düşüşlerde e-posta uyarısı tetiklenir.'],
            ],
            'haftalik-performans-raporu-site' => [
                ['q' => 'Raporda neler olur?', 'a' => 'Kategori/kampanya içgörüleri ve gelecek hafta aksiyon listesi.'],
                ['q' => 'Toplantı yapılıyor mu?', 'a' => 'İhtiyaca göre kısa durum değerlendirme toplantısı yapılır.'],
                ['q' => 'Backlog nasıl yönetilir?', 'a' => 'Aksiyonlar önceliklendirilip takip listesine alınır.'],
            ],
            'aylik-performans-raporu-site' => [
                ['q' => 'Hangi KPI’lar incelenir?', 'a' => 'Gelir, CVR, AOV, iade oranı ve marj etkisi.'],
                ['q' => 'Yol haritası oluşturulur mu?', 'a' => 'Öğrenimler ve hedeflere göre aylık plan yazılır.'],
                ['q' => 'Bütçe önerisi veriyor musunuz?', 'a' => 'Evet. Kanal bazlı dağıtım ve beklenen etki paylaşılır.'],
            ],
            'urun-guncellemeleri' => [
                ['q' => 'Toplu içe aktarma yapılıyor mu?', 'a' => 'CSV/XML ile toplu ürün ve varyant güncellemesi yapılır.'],
                ['q' => 'Görsel standartları nedir?', 'a' => 'Oran, çözünürlük ve boyut kılavuzuna göre optimize edilir.'],
                ['q' => 'Hata kontrolleri nasıl?', 'a' => 'Ön izleme ve geriye alma planı ile ilerlenir.'],
            ],
            'mobil-uygulama-kurulum-destegi' => [
                ['q' => 'PWA mı native mi?', 'a' => 'İhtiyaca göre değerlendirilir. PWA hızlı devreye alınır.'],
                ['q' => 'Bildirim izinleri nasıl?', 'a' => 'Onboarding içinde izin kancaları ve değer önerisi gösterilir.'],
                ['q' => 'Mağaza süreçlerine destek var mı?', 'a' => 'Evet. Paketleme ve temel yönergeler konusunda rehberlik sağlanır.'],
            ],
            'b2b-satis-sistemi-kurulum-ve-yonetimi' => [
                ['q' => 'Bayi kaydı nasıl işler?', 'a' => 'Başvuru, onay ve rol atama adımları oluşturulur.'],
                ['q' => 'Fiyat listeleri yönetilir mi?', 'a' => 'Bayi seviyesine göre özel fiyat ve indirim setleri tanımlanır.'],
                ['q' => 'Teklif onay süreci nasıl?', 'a' => 'Sepet→teklif→onay akışı ve geçerlilik tarihleri kurgulanır.'],
            ],
        ];

        foreach ($faqMap as $slug => $items) {
            $service = Service::where('slug', $slug)->first();
            if (! $service) continue;

            $keep = [];
            $sort = 1;

            foreach ($items as $qa) {
                $keep[] = $qa['q'];

                $service->faqs()->updateOrCreate(
                    ['question' => $qa['q']],
                    ['answer' => $qa['a'], 'sort_order' => $sort++]
                );
            }

            $service->faqs()->whereNotIn('question', $keep)->delete();
        }
    }
}
