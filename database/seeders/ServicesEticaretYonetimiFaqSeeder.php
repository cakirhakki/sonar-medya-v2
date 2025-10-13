<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesEticaretYonetimiFaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqMap = [
            'altyapi-teknik-kurulum' => [
                ['q' => 'Kurulumda neler yapılır?', 'a' => 'Tema kurulumu, temel sayfa şablonları, ödeme/kargo ayarları ve temel güvenlik konfigürasyonları yapılır.'],
                ['q' => 'Teslim süresi nedir?', 'a' => 'İhtiyaca göre değişir. Standart kurulum 3–7 iş günü içinde tamamlanır.'],
                ['q' => 'Mevcut veriler taşınır mı?', 'a' => 'Uygunsa ürün ve kategori verileri içeri aktarılır. Eski sistemle eşleme planı hazırlanır.'],
            ],

            'sistem-entegrasyonlari' => [
                ['q' => 'Hangi entegrasyonlar mümkün?', 'a' => 'Kargo, ödeme, muhasebe (e-fatura/e-arşiv), pazaryeri ve ERP/CRM entegrasyonları kurulur.'],
                ['q' => 'Hata ve loglama nasıl yapılır?', 'a' => 'Webhook ve API çağrıları için ayrıntılı loglama, retry ve alarm mekanizması uygulanır.'],
                ['q' => 'Canlıya geçişte kesinti olur mu?', 'a' => 'Planlı geçiş penceresi ve geri alma planı ile kesinti en aza indirilir.'],
            ],

            'pazarlama-iletisim' => [
                ['q' => 'İçerik üretimini kapsar mı?', 'a' => 'Ürün açıklamaları, kategori metinleri ve kampanya mesaj şablonları oluşturulur.'],
                ['q' => 'Müşteri segmentasyonu yapıyor musunuz?', 'a' => 'Evet. Davranış, sipariş ve değer bazlı segmentasyon ile hedefli iletişim kurguları yapılır.'],
                ['q' => 'Hangi kanallar desteklenir?', 'a' => 'E-posta, SMS, push bildirim ve sosyal reklam kurguları entegre çalışır.'],
            ],

            'seo-icerik-yonetimi' => [
                ['q' => 'SEO tarafında neler yapılır?', 'a' => 'Site haritası, dahili linkleme, şema işaretlemeleri ve sayfa başlık/meta optimizasyonu uygulanır.'],
                ['q' => 'Ürün içerikleri nasıl ele alınır?', 'a' => 'Benzersiz açıklamalar, varyant/özellik tabloları ve FAQ yapıları hazırlanır.'],
                ['q' => 'Takip nasıl yapılır?', 'a' => 'GSC, Analytics ve pozisyon izleme ile periyodik raporlama sağlanır.'],
            ],

            'otomasyon-surec-tasarimi' => [
                ['q' => 'Hangi otomasyonlar kurulur?', 'a' => 'Sepet terk, stok bildirimleri, tekrar satın alma hatırlatmaları ve kampanya tetikleyicileri kurulur.'],
                ['q' => 'No-code/low-code araçlar kullanılır mı?', 'a' => 'Mümkün olduğunda platform içi akışlar ve webhook tabanlı otomasyonlar tercih edilir.'],
                ['q' => 'Bakım ve değişiklik yönetimi?', 'a' => 'Senaryo değişiklikleri versiyonlanır, test ortamında doğrulanır ve canlıya alınır.'],
            ],

            'destek-egitim-raporlama' => [
                ['q' => 'Eğitim kapsamı nedir?', 'a' => 'Panel kullanımı, sipariş/iadeler, kampanya ve içerik yönetimi başlıklarında oturumlar verilir.'],
                ['q' => 'Raporlama periyodu?', 'a' => 'Aylık veya haftalık raporlar: satış, dönüşüm oranı, sepet terk ve kanal performansı.'],
                ['q' => 'Destek kanalları?', 'a' => 'Ticket sistemi, e-posta ve planlı toplantılar ile SLA’ya uygun destek sağlanır.'],
            ],
        ];

        foreach ($faqMap as $slug => $items) {
            $service = Service::where('slug', $slug)->first();
            if (!$service) {
                continue;
            }

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
