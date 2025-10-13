<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesTeknikSeoFaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqMap = [
            'crawl-budget' => [
                ['q' => 'Crawl budget neden düşer?', 'a' => 'Tarama tuzakları, çok sayıda düşük değerli URL ve yavaş yanıt süreleri nedeniyle düşer. Log analizi ve URL temizlikleriyle iyileştirilir.'],
                ['q' => 'Hangi verilerle ölçümlersiniz?', 'a' => 'Server access logları, GSC tarama istatistikleri, 5xx/4xx oranları ve bot-frekansı metrikleri kullanılır.'],
                ['q' => 'Ne kadar sürede etkisini görürüm?', 'a' => 'Genelde 2–4 hafta içinde tarama dağılımında, 4–8 hafta içinde index kapsamasında değişim görülür.'],
            ],
            'xml-sitemap-robots' => [
                ['q' => 'Sitemap kaç adet olmalı?', 'a' => 'Büyük sitelerde içerik türüne göre bölünmüş ve 50k URL veya 50MB sınırlarına uygun çoklu sitemap önerilir.'],
                ['q' => 'Robots.txt ile neyi engellersiniz?', 'a' => 'Filtreleme, arama sonuçları ve oturum parametreli URL’ler gibi değersiz veya tekrar eden sayfalar engellenir.'],
                ['q' => 'Öncelik ve değişim sıklığı işe yarar mı?', 'a' => 'Modern arama motorları için önemsizdir. Doğru URL kapsamı ve tazelik daha kritiktir.'],
            ],
            'yonlendirme-http-kodlari' => [
                ['q' => '301 ve 302 farkı nedir?', 'a' => '301 kalıcı, 302 geçici yönlendirmedir. Sinyal ve otorite aktarımı için kalıcı değişimlerde 301 tercih edilir.'],
                ['q' => 'Redirect chain zararlı mı?', 'a' => 'Evet. Tarama bütçesini tüketir ve gecikme yaratır. Zincirler tek adımda birleştirilmelidir.'],
                ['q' => '404/410 nasıl yönetilir?', 'a' => 'Gerçekten olmayan içerik 410 ile kapatılabilir. Eski önemli URL’ler tematik en yakın sayfaya 301’lenir.'],
            ],
            'core-web-vitals' => [
                ['q' => 'Hangi metriklere odaklanıyorsunuz?', 'a' => 'LCP, INP ve CLS. Kaynak optimizasyonu, preconnect/preload ve layout stabilitesi ana kaldıraçlardır.'],
                ['q' => 'Kaynak sıkıştırması nasıl yapılır?', 'a' => 'HTTP/2, brotli/gzip, kritik CSS ayrıştırma ve kullanılmayan JS/CSS temizliği uygulanır.'],
                ['q' => 'Mobil ve masaüstü farkı?', 'a' => 'Mobilde ağ ve CPU kısıtları daha serttir. Görsel boyutları ve JS maliyeti mobil öncelikli optimize edilir.'],
            ],
            'yapisal-veri-schema' => [
                ['q' => 'Hangi türleri uygularsınız?', 'a' => 'Organization, Breadcrumb, Article/BlogPosting, Product, FAQPage ve gerektiğinde LocalBusiness.'],
                ['q' => 'JSON-LD mi microdata mı?', 'a' => 'JSON-LD önerilir. Uygulaması kolay ve hata riski düşüktür.'],
                ['q' => 'Manuel mi otomatik mi?', 'a' => 'Şablon tabanlı otomasyon kurulur, kritik sayfalarda manuel zenginleştirme yapılır.'],
            ],
            'kanonik-indexleme' => [
                ['q' => 'Canonical neyi çözer?', 'a' => 'Yinelenen veya parametreli URL’lerde otoriteyi tek kanonik URL’de toplar.'],
                ['q' => 'Hreflang ile birlikte nasıl kullanılır?', 'a' => 'Her dil/ülke varyantı kendi dilinde kanoniğe işaret eder ve karşılıklı hreflang üçgeni tamamlanır.'],
                ['q' => 'Noindex ne zaman?', 'a' => 'Değersiz, tekil amaçlı veya kopya sayfalarda noindex, gerektiğinde robots engeli birlikte kullanılır.'],
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
