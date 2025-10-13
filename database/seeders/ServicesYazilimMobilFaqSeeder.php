<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesYazilimMobilFaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqMap = [
            // İhtiyaca Özel Yazılım
            'ihtiyaca-ozel-yazilim' => [
                ['q' => 'Sürece nasıl başlıyorsunuz?', 'a' => 'İhtiyaç analizi, kapsam dokümanı ve kabaca zaman/maliyet tahmini ile başlarız.'],
                ['q' => 'Hangi teknoloji yığınlarını kullanıyorsunuz?', 'a' => 'Laravel/PHP, MySQL, Redis, Queue, gerektiğinde Node.js ve modern frontend bileşenleri.'],
                ['q' => 'Bakım ve destek sağlıyor musunuz?', 'a' => 'Evet. Versiyon güncellemeleri, güvenlik yamaları ve küçük geliştirmeler için SLA sunarız.'],
            ],

            // Mobil Uygulama
            'mobil-uygulama' => [
                ['q' => 'iOS ve Android için çıktı alıyor musunuz?', 'a' => 'Evet. Mağaza yükleme (App Store/Google Play) süreçleri dahil destek veriyoruz.'],
                ['q' => 'Performans ve UX yaklaşımınız nedir?', 'a' => 'Native hissiyat, düşük açılış süresi, erişilebilirlik ve analitik ile sürekli iyileştirme.'],
                ['q' => 'Bildirim ve analitik entegrasyonu var mı?', 'a' => 'Firebase/OneSignal push bildirim, Crashlytics ve GA4/BigQuery entegrasyonları mümkündür.'],
            ],

            // Entegrasyon & Teknik Danışmanlık
            'entegrasyon-teknik-danismanlik' => [
                ['q' => 'Hangi entegrasyon tiplerini yapıyorsunuz?', 'a' => 'REST/GraphQL API, ödeme sağlayıcıları, ERP/CRM, kargo ve pazaryeri entegrasyonları.'],
                ['q' => 'Güvenlik ve hata yönetimi nasıl ele alınıyor?', 'a' => 'Rate limit, retry/pattern, imzalama, günlükleme ve uyarı (alerts) pratikleri uygularız.'],
                ['q' => 'Dokümantasyon sağlanıyor mu?', 'a' => 'Evet. Akış şemaları, uç noktalar, örnek istek/yanıt ve hata kodları belgelenir.'],
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

            // Eski, map’te olmayan soruları kaldır
            $service->faqs()->whereNotIn('question', $keep)->delete();
        }
    }
}
