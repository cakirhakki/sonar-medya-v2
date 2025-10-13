<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesReklamPerformansFaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqMap = [
            'isletme-hesap-kurulumu-ve-baglantilari' => [
                ['q' => 'Hangi hesaplar kuruluyor ve bağlanıyor?', 'a' => 'Meta Business, Ads Manager, Google Ads, Merchant Center, GA4 ve Search Console kurulur. Domain/işletme doğrulamaları tamamlanır ve hesaplar birbiriyle ilişkilendirilir.'],
                ['q' => 'Mevcut hesapları kullanabilir miyiz?', 'a' => 'Evet. Müşteri sahipli hesaplara erişim devralınır. Sahiplik müşteride kalır, biz yönetsel erişimle yapılandırırız.'],
                ['q' => 'Teslimatta hangi çıktıları veriyorsunuz?', 'a' => 'Erişim ve rol listesi, doğrulama ekran görüntüleri, bağlantı şeması ve ölçüm hedefleri dokümantasyonu teslim edilir.'],
            ],
            'meta-ads-yonetimi' => [
                ['q' => 'Hangi kampanya türlerini yönetiyorsunuz?', 'a' => 'Dönüşüm, trafik, katalog ve lead kampanyaları. Yerleşim, bütçe ve teklif stratejileri hedefe göre seçilir.'],
                ['q' => 'A/B test yaklaşımınız nedir?', 'a' => 'Tek değişken prensibiyle kreatif, hedefleme ve yerleşimleri ayrı ayrı test eder, istatistiksel eşiğe göre kararları alırız.'],
                ['q' => 'Raporlama sıklığı nedir?', 'a' => 'Haftalık özet, aylık derin rapor sağlanır. ROAS, CPA, CTR ve frekans gibi metrikler izlenir.'],
            ],
            'google-reklam-yonetimi' => [
                ['q' => 'Hangi ağlarda çalışıyorsunuz?', 'a' => 'Arama, Görüntülü, Discovery, Performance Max ve Alışveriş. Hesap ve kampanya yapısı amaca göre kurulur.'],
                ['q' => 'Anahtar kelime stratejiniz nasıl?', 'a' => 'Exact/phrase/broad dengesi, negatif listeler ve arama terimi analizleriyle kalite puanı ve maliyet optimize edilir.'],
                ['q' => 'Feed yönetimi gerekiyor mu?', 'a' => 'Alışveriş ve PMax için evet. Merchant Center feed’ini kategori, başlık ve öznitelik açısından temizleriz.'],
            ],
            'pixel-kurulumu-ve-entegrasyonu' => [
                ['q' => 'Hangi olaylar izlenir?', 'a' => 'Sayfa görüntüleme, add_to_cart, begin_checkout, purchase gibi temel ve gelişmiş e-ticaret olayları.'],
                ['q' => 'GTM mi direkt kod mu?', 'a' => 'Öncelik GTM. Yönetilebilirlik, versiyonlama ve test kolaylığı sağlar.'],
                ['q' => 'Doğrulama nasıl yapılır?', 'a' => 'Önizleme ve debug modlarında tetikleyici kontrolleri, ağ istekleri ve ölçüm ekran görüntüleriyle doğrularız.'],
            ],
            'is-akisi-ve-icerik-plani-olusturma' => [
                ['q' => 'Plan neleri kapsar?', 'a' => 'Aylık takvim, kampanya dönemleri, içerik temaları, formatlar, teslim tarihleri ve onay akışları.'],
                ['q' => 'Onay süreci nasıl işler?', 'a' => 'Brif şablonu iletilir, içerik taslakları paylaşılır. Geri bildirimler sürümlenip yayına alınır.'],
                ['q' => 'KPI’lar nasıl tanımlanır?', 'a' => 'Hedefe göre erişim, tıklama, dönüşüm ve gelir katkısı KPI seti belirlenir.'],
            ],
            'hedef-kitle-optimizasyonu' => [
                ['q' => 'Hangi verilerle segment oluşturursunuz?', 'a' => 'Pixel ve GA4 davranışları, CRM listeleri, satın alma geçmişi ve etkileşim verileri.'],
                ['q' => 'Frekans ve hariç tutma nasıl ayarlanır?', 'a' => 'Aşırı tekrarı önlemek için frekans sınırı konur, satın almış ya da ilgisiz kitleler hariç tutulur.'],
                ['q' => 'Lookalike kitleler nasıl üretilir?', 'a' => 'Değer ve ömür boyu değer sinyallerine göre kaynak listelerden benzer kitleler türetilir ve test edilir.'],
            ],
            'kampanya-amaci-ve-stratejileri-olusturma' => [
                ['q' => 'Strateji çerçeveniz nedir?', 'a' => 'TOFU-MOFU-BOFU huni kurgusu, hedef metrikler ve test hipotezleriyle plan kurarız.'],
                ['q' => 'Bütçe dağılımı nasıl yapılır?', 'a' => 'Amaç, mevsimsellik ve marj yapısına göre kanal ve aşama bazlı dağıtım önerilir.'],
                ['q' => 'Başarı kriterlerini nasıl belirlersiniz?', 'a' => 'Tarihsel veriye dayanarak CPA, ROAS, CVR ve frekans için makul aralıklar tanımlarız.'],
            ],
            'reklam-metni-olusturma' => [
                ['q' => 'Metinlerde hangi unsurları test edersiniz?', 'a' => 'Başlık, değer önerisi, itiraz kırıcı, sosyal kanıt ve CTA varyasyonları.'],
                ['q' => 'Karakter sınırlarına uyum nasıl sağlanır?', 'a' => 'Platform yönergeleri doğrultusunda kısa ve uzun format alternatifleri hazırlanır.'],
                ['q' => 'Dönüşümlere etkisini nasıl ölçersiniz?', 'a' => 'A/B test ve çok değişkenli test sonuçlarını kampanya KPI’larıyla ilişkilendiririz.'],
            ],
            'rekabet-analizi-marka-urun' => [
                ['q' => 'Neleri inceliyorsunuz?', 'a' => 'Rakip kampanya yapıları, kreatifler, açılış sayfaları, fiyat ve teklif politikaları.'],
                ['q' => 'Çıktı nasıl sunulur?', 'a' => 'Boşluk analizi, farklılaşma önerileri ve önceliklendirilmiş aksiyon listesiyle rapor sunulur.'],
                ['q' => 'Ne sıklıkla güncellenir?', 'a' => 'Aylık periyotta temel güncelleme, büyük değişimlerde ara rapor yapılır.'],
            ],
            'anahtar-kelime-yonetimi' => [
                ['q' => 'Kelime mimarisini nasıl kurarsınız?', 'a' => 'Niyet düzeyine göre seed ve long-tail grupları oluşturur, reklam gruplarıyla hizalarız.'],
                ['q' => 'Negatif listeyi nasıl yönetirsiniz?', 'a' => 'Arama terimi raporlarından çıkan alakasız terimler düzenli olarak listeye eklenir.'],
                ['q' => 'Kalite puanını nasıl iyileştirirsiniz?', 'a' => 'Anahtar kelime–reklam metni–açılış sayfası uyumunu artırırız.'],
            ],
            'sms-mail-marketing' => [
                ['q' => 'Hangi otomasyon akışları kurulur?', 'a' => 'Hoş geldin, sepet hatırlatma, kazanım, yeniden etkileşim ve tetiklenmiş kampanyalar.'],
                ['q' => 'Kişiselleştirme nasıl yapılır?', 'a' => 'Segment, davranış ve ürün ilgisine göre dinamik içerik kullanılır.'],
                ['q' => 'KVKK ve izin yönetimi nasıl ele alınır?', 'a' => 'İzinli pazarlama ilkelerine uyulur, ret ve tercih merkezi yapılandırılır.'],
            ],
            'haftalik-performans-raporu' => [
                ['q' => 'Raporda neler olur?', 'a' => 'KPI özeti, haftalık değişimler, öne çıkan kampanyalar ve hızlı aksiyonlar.'],
                ['q' => 'Sunum formatı nedir?', 'a' => 'Tek sayfalık özet ve destekleyici grafiklerle paylaşılır.'],
                ['q' => 'Toplantı yapılır mı?', 'a' => 'İhtiyaca göre kısa bir değerlendirme çağrısı planlanır.'],
            ],
            'aylik-performans-raporu' => [
                ['q' => 'Aylık raporda ekstra ne var?', 'a' => 'Kanal katkı analizi, ölçekleme önerileri ve sonraki ayın yol haritası.'],
                ['q' => 'Bütçe verimliliği nasıl incelenir?', 'a' => 'ROAS/CPA trendleri ve marj hassasiyeti birlikte değerlendirilir.'],
                ['q' => 'Test sonuçları nasıl işlenir?', 'a' => 'Başarılı ve başarısız deneyler öğrenim havuzuna eklenir.'],
            ],
            'tiktok-for-business-reklam-yonetimi' => [
                ['q' => 'Hangi kreatif yaklaşımı önerirsiniz?', 'a' => 'Hook-first, hızlı tempo ve native dil; ilk 3 saniyede dikkat kazanımı.'],
                ['q' => 'Hedefleme nasıl başlar?', 'a' => 'Geniş hedefleme ile başlanır, performansa göre ilgi ve benzer kitlelere daraltılır.'],
                ['q' => 'Başarıyı nasıl ölçersiniz?', 'a' => 'CPA, izlenme oranı, izlenme başına maliyet ve tıklama metriği birlikte izlenir.'],
            ],
            'youtube-reklam-yonetimi' => [
                ['q' => 'Hangi formatlar kullanılır?', 'a' => 'In-Stream, In-Feed ve Shorts. Huni aşamalarına göre ayrılır.'],
                ['q' => 'Yerleşim ve hedefleme nasıl test edilir?', 'a' => 'Kitle, yerleşim ve kreatif uzunluğu ayrı hipotezlerle denenir.'],
                ['q' => 'Ölçüm çerçeveniz nedir?', 'a' => 'Brand lift göstergeleri ile dönüşüm metrikleri birlikte yorumlanır.'],
            ],
            'kreatif-reklam-gorseli-tasarimi' => [
                ['q' => 'Teslim formatları nelerdir?', 'a' => 'Platform oranlarına uygun kaynak dosya ve export setleri teslim edilir.'],
                ['q' => 'Ne tür temalar önerirsiniz?', 'a' => 'Ürün odak, fayda odak, sosyal kanıt ve kampanya temaları.'],
                ['q' => 'Performans nasıl iyileştirilir?', 'a' => 'A/B test sonuçlarına göre görsel ve metin hiyerarşisi revize edilir.'],
            ],
            'sosyal-medya-danismanligi' => [
                ['q' => 'Strateji dokümanı neleri içerir?', 'a' => 'Hedefler, ton, içerik sütunları, raporlama ve büyüme çerçevesi.'],
                ['q' => 'Takvim nasıl yönetilir?', 'a' => 'Aylık plan, haftalık revizyon ve onay akışıyla yürütülür.'],
                ['q' => 'Topluluk yönetimi kapsama dahil mi?', 'a' => 'Yanıt süresi, SSS ve yönlendirme şablonları tanımlanır.'],
            ],
            '3-parti-uygulama-reklamlari-criteo-rte-house' => [
                ['q' => 'Gereken altyapı nedir?', 'a' => 'Ürün kataloğu/ feed, pixel olayları ve izleme parametreleri.'],
                ['q' => 'Dinamik ürün reklamları nasıl çalışır?', 'a' => 'Kullanıcı davranışına göre ürün eşleşmesi ve teklif optimizasyonu yapılır.'],
                ['q' => 'Atıf modeli nasıl ele alınır?', 'a' => 'Kanal karması bağlamında katkı analiziyle raporlanır.'],
            ],
            'tv-ve-radyo-reklam-danismanligi' => [
                ['q' => 'Planlamayı nasıl yaparsınız?', 'a' => 'Hedef GRP, zaman kuşağı ve program seçimiyle medya planı çıkarılır.'],
                ['q' => 'Dijital ile entegrasyon sağlanır mı?', 'a' => 'Evet. Uçtan uca mesaj bütünlüğü ve ölçümleme senaryoları kurgulanır.'],
                ['q' => 'Başarı nasıl ölçülür?', 'a' => 'Reach/frequency ve kampanya sonrası artış göstergeleri raporlanır.'],
            ],
        ];

        foreach ($faqMap as $slug => $items) {
            $service = Service::where('slug', $slug)->first();
            if (! $service) continue;

            $keepQuestions = [];
            $sort = 1;

            foreach ($items as $qa) {
                $keepQuestions[] = $qa['q'];

                // idempotent: varsa güncelle, yoksa oluştur
                $service->faqs()->updateOrCreate(
                    ['question' => $qa['q']],
                    ['answer' => $qa['a'], 'sort_order' => $sort++]
                );
            }

            // senkron: map’te olmayan eski SSS’leri temizle
            $service->faqs()
                ->whereNotIn('question', $keepQuestions)
                ->delete();
        }
    }
}
