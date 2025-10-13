<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesDanismanlikEgitimFaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqMap = [
            'panel-egitimi' => [
                ['q' => 'Eğitim içeriği nedir?', 'a' => 'Panel modülleri, rol ve yetkiler, rapor okuma ve temel ayarlar.'],
                ['q' => 'Süre ve format?', 'a' => '2-3 saatlik çevrim içi oturum. Kayıt ve doküman paylaşılır.'],
                ['q' => 'Kimler katılmalı?', 'a' => 'Operasyon, pazarlama ve raporlama ekipleri.'],
            ],
            'destek-talebi-yonetimi' => [
                ['q' => 'Hangi süreçler kurulur?', 'a' => 'Kategori, öncelik, SLA ve onay akışları.'],
                ['q' => 'Şablon desteği var mı?', 'a' => 'Sık senaryolar için yanıt makroları ve etiketler sağlanır.'],
                ['q' => 'Başarı nasıl ölçülür?', 'a' => 'İlk yanıt, çözüm süresi ve memnuniyet puanı izlenir.'],
            ],
            'online-toplanti-ve-canli-destek' => [
                ['q' => 'Toplantı kapsamı?', 'a' => 'Ajanda, ekran paylaşımı ile sorun giderme ve aksiyon listesi.'],
                ['q' => 'Ne sıklıkla yapılır?', 'a' => 'İhtiyaca göre haftalık veya iki haftada bir.'],
                ['q' => 'Kayıt paylaşılır mı?', 'a' => 'Evet. Kayıt ve notlar toplantı sonrası gönderilir.'],
            ],
            'ozel-marka-iletisim-yoneticisi' => [
                ['q' => 'Rol nedir?', 'a' => 'Tek temas noktası, haftalık koordinasyon ve OKR takibi.'],
                ['q' => 'Raporlama sağlanır mı?', 'a' => 'Durum raporu, risk ve bağımlılık listesi paylaşılır.'],
                ['q' => 'Kapsam?', 'a' => 'Planlama, takip ve paydaş iletişimi. Operatif işler ayrı değerlendirilir.'],
            ],
            'marka-danismanligi' => [
                ['q' => 'Teslimatlar neler?', 'a' => 'Konumlandırma çerçevesi, mesaj haritası ve ton rehberi.'],
                ['q' => 'Metodoloji?', 'a' => 'Persona ve rakip analizi, atölye ve iteratif revizyon.'],
                ['q' => 'Uygulama örnekleri?', 'a' => 'Sosyal kopya, banner, sayfa metni örnekleri.'],
            ],
            'influencer-ugc-danismanligi' => [
                ['q' => 'Seçim kriterleri?', 'a' => 'Uygun kitle, etkileşim oranı, marka uyumu ve içerik kalitesi.'],
                ['q' => 'Sözleşme ve haklar?', 'a' => 'Kullanım süresi, mecralar ve yeniden kullanım hakları belirlenir.'],
                ['q' => 'Takip nasıl yapılır?', 'a' => 'UTM, kod/kupon ve içerik takvimiyle performans izlenir.'],
            ],
            'billboard-ve-b2b-marketing-danismanligi' => [
                ['q' => 'Outdoor planı nasıl?', 'a' => 'Lokasyon seçimi, GRP hedefi ve dönem planı.'],
                ['q' => 'B2B yaklaşımınız?', 'a' => 'Hedef hesap listesi, içerik nurturu ve satış uyumu.'],
                ['q' => 'Ölçümleme?', 'a' => 'Ulaşım tahmini, web trafiği lift ve lead kalitesi.'],
            ],
            'ux-gelistirme-danismanligi' => [
                ['q' => 'Hangi analizler?', 'a' => 'Heuristik denetim, ısı haritası ve oturum kayıtları.'],
                ['q' => 'Çıktılar?', 'a' => 'Hipotez listesi, A/B test önerileri ve önceliklendirme.'],
                ['q' => 'Takvim?', 'a' => 'Kısa vadeli hızlı kazanımlar ve sprint planı.'],
            ],
            'front-end-gelistirme-danismanligi' => [
                ['q' => 'Odak alanları?', 'a' => 'Performans, erişilebilirlik ve bileşen mimarisi.'],
                ['q' => 'Nasıl değerlendirirsiniz?', 'a' => 'Core Web Vitals ve kod inceleme oturumları.'],
                ['q' => 'Teslim?', 'a' => 'İyileştirme listesi ve refactor öneri PR’ları.'],
            ],
            'yazilim-danismanligi' => [
                ['q' => 'Kapsam?', 'a' => 'Mimari, veri modeli, entegrasyon ve güvenlik.'],
                ['q' => 'Süreç?', 'a' => 'Mevcut durum analizi, yol haritası ve sprintlere yayılım.'],
                ['q' => 'Dokümantasyon?', 'a' => 'Kod standartları, test stratejisi ve API sözleşmeleri.'],
            ],
            'icerik-yonetimi-danismanligi' => [
                ['q' => 'Neleri kuruyorsunuz?', 'a' => 'İçerik sütunları, takvim ve çoklu kanal dağıtımı.'],
                ['q' => 'SEO uyumu?', 'a' => 'Anahtar kelime eşleşmesi ve HTML hiyerarşisi.'],
                ['q' => 'Ölçümleme?', 'a' => 'Hedef KPI’lar ve içerik performans raporları.'],
            ],
            'ugc-video-takibi-ve-raporlama' => [
                ['q' => 'Toplama yöntemi?', 'a' => 'Hashtag ve link izleme, içerik ID eşleme.'],
                ['q' => 'İzin yönetimi?', 'a' => 'Kullanım onayı ve süre/kanal kısıtlarının takibi.'],
                ['q' => 'Rapor kapsamı?', 'a' => 'Görüntülenme, etkileşim ve dönüşüm etkisi.'],
            ],
            '3-parti-uygulama-danismanligi' => [
                ['q' => 'Seçim nasıl yapılır?', 'a' => 'İhtiyaç, maliyet, uyumluluk ve bakım kriterleri ile.'],
                ['q' => 'POC süreci?', 'a' => 'Kısıtlı kapsam, başarı kriterleri ve geri alma planı.'],
                ['q' => 'Riskler?', 'a' => 'Güvenlik, performans ve lisans bağımlılıkları analiz edilir.'],
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
