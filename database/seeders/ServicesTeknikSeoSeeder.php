<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;
use App\Models\Service;

class ServicesTeknikSeoSeeder extends Seeder
{
    public function run(): void
    {
        $category = ServiceCategory::where('slug', 'teknik-seo')->firstOrFail();
        $d2m = fn(int $days) => $days * 1440;

        $items = [
            ['slug' => 'crawl-budget', 'name' => 'Crawl Budget', 'excerpt' => 'Sonar Medya ile gelişmiş tarama bütçesi (Crawl Budget) optimizasyonu hizmeti.', 'description' => 'Sonar Medya olarak arama motorlarının web sitenizi en verimli şekilde tarayabilmesi için kapsamlı Crawl Budget optimizasyonu gerçekleştiriyoruz. Robots.txt, canonical etiketleri, dahili bağlantı yapısı ve log analizleriyle sitenizin tarama bütçesini maksimum verimle kullanmasını sağlıyoruz. Gereksiz yönlendirmeler, yinelenen sayfalar ve düşük değerli URL’ler temizlenir, arama motorlarının odaklanması gereken stratejik sayfalara yönlendirilir. Teknik SEO açısından en önemli unsurlardan biri olan tarama verimliliği, özellikle büyük ölçekli sitelerde sıralama performansına doğrudan etki eder. Sonar Medya’nın uzman ekibi, tarama istatistiklerini analiz ederek sitenizin arama motorlarıyla iletişimini güçlendirir ve organik görünürlüğünüzü artırır.', 'tags' => ['seo', 'crawl']],
            ['slug' => 'xml-sitemap-robots', 'name' => 'XML Sitemap & Robots.txt', 'excerpt' => 'Sonar Medya ile XML Sitemap ve Robots.txt yapılandırmasıyla tam tarama kontrolü.', 'description' => 'Sonar Medya, arama motorlarının web sitenizi doğru ve eksiksiz bir şekilde tarayabilmesi için sitemap ve robots.txt dosyalarınızı en iyi SEO standartlarına göre yapılandırır. XML sitemap içinde öncelikli sayfalar, güncellenme tarihleri ve tarama sıklıkları belirlenir. Robots.txt kurallarıyla gereksiz veya düşük değerli sayfalar engellenir, önemli sayfaların erişilebilirliği sağlanır. Bu teknik optimizasyon sayesinde arama motorları web sitenizin hiyerarşisini daha net anlar. Sonar Medya’nın SEO odaklı teknik ekibi, tarama hatalarını ve erişim problemlerini ortadan kaldırarak Googlebot ve Bingbot gibi arama motorlarının sitenizi daha hızlı ve doğru taramasını sağlar.', 'tags' => ['sitemap', 'robots']],
            ['slug' => 'yonlendirme-http-kodlari', 'name' => 'Yönlendirme & HTTP Kodları', 'excerpt' => 'Sonar Medya’nın yönlendirme (redirect) ve HTTP hata kodu yönetimi hizmeti.', 'description' => 'Sonar Medya olarak web sitenizdeki 301/302 yönlendirmeleri, 404 ve 500 hata kodlarını detaylı şekilde analiz ederek temiz bir yönlendirme yapısı oluşturuyoruz. Hatalı veya zincirleme yönlendirmeleri ortadan kaldırarak hem kullanıcı deneyimini hem de SEO performansını artırıyoruz. Doğru yapılandırılmış yönlendirmeler sayesinde arama motoru botlarının sitenizde kaybolması engellenir ve tarama bütçesi korunur. Ayrıca kırık linkler, eski sayfalar ve taşınmış içerikler doğru hedeflere yönlendirilir. Bu süreçte canonical etiketleri ve site haritası uyumunu da kontrol ediyoruz. Sonar Medya’nın teknik SEO ekibi, web sitenizin hatasız ve güvenilir bir yapıya kavuşmasını sağlar.', 'tags' => ['redirect', 'http']],
            ['slug' => 'core-web-vitals', 'name' => 'Core Web Vitals', 'excerpt' => 'Sonar Medya tarafından Core Web Vitals (LCP, FID, CLS) performans optimizasyonu.', 'description' => 'Sonar Medya, web sitenizin Google Core Web Vitals metriklerinde yüksek skorlar elde etmesi için hız ve performans odaklı optimizasyonlar uygular. LCP (Largest Contentful Paint), FID (First Input Delay) ve CLS (Cumulative Layout Shift) değerlerini iyileştirerek hem kullanıcı deneyimini hem de sıralama performansınızı artırır. Görsellerin sıkıştırılması, kod küçültme (minify), lazy load, CDN entegrasyonu ve tarayıcı önbellekleme gibi yöntemlerle site yüklenme süresini minimuma indiriyoruz. Sonar Medya’nın deneyimli ekibi, performans raporlarını sürekli izler ve PageSpeed Insights ile Lighthouse sonuçlarına göre iyileştirmeler yapar. Böylece web siteniz hem hızlı hem de SEO açısından rakiplerinin önüne geçer.', 'tags' => ['web-vitals', 'performans']],
            ['slug' => 'yapisal-veri-schema', 'name' => 'Yapısal Veri (Schema)', 'excerpt' => 'Sonar Medya ile gelişmiş schema (yapısal veri) işaretleme optimizasyonu.', 'description' => 'Sonar Medya, web sitenizi Google ve diğer arama motorları tarafından daha iyi anlaşılır hale getirmek için JSON-LD tabanlı schema işaretlemeleri uygular. Ürün, hizmet, organizasyon, blog yazısı, değerlendirme ve FAQ gibi yapılandırılmış veri türlerini optimize ederek zengin sonuç (rich result) elde etme olasılığınızı artırıyoruz. Bu optimizasyon sayesinde web siteniz arama sonuçlarında yıldız puanları, fiyat bilgileri, sık sorulan sorular ve etkinlik detayları gibi dikkat çekici ögelerle öne çıkar. Sonar Medya’nın SEO uzmanları, schema.org standartlarına uygun yapılandırma yaparak hem teknik doğruluk hem de kullanıcı deneyimi açısından mükemmel bir denge kurar.', 'tags' => ['schema', 'json-ld']],
            ['slug' => 'kanonik-indexleme', 'name' => 'Kanonik & Indexleme', 'excerpt' => 'Sonar Medya’dan canonical ve hreflang etiketleriyle uluslararası SEO yönetimi.', 'description' => 'Sonar Medya, yinelenen içerik sorunlarını önlemek ve çok dilli sitelerde doğru indexleme sağlamak için canonical ve hreflang etiketlerini doğru biçimde uygular. Canonical etiketleriyle arama motorlarına hangi sayfanın orijinal olduğunu net şekilde bildiriyoruz. Hreflang yapılandırmasıyla farklı dillerdeki veya bölgelere özel sayfaların birbiriyle bağlantısı kurulur. Bu sayede hem Google hem de kullanıcılar doğru içeriğe yönlendirilir. Yanlış yapılandırılmış canonical veya hreflang etiketleri organik trafiği düşürebilir; bu nedenle Sonar Medya ekibi her detayı titizlikle kontrol eder. Teknik analiz, Search Console verileri ve dil hedefleme stratejileriyle SEO performansınızı global ölçekte optimize ediyoruz.', 'tags' => ['canonical', 'index']],
        ];

        $order = 1;

        foreach ($items as $data) {
            $payload = [
                'service_category_id' => $category->id,
                'name' => $data['name'],
                'slug' => $data['slug'],
                'excerpt' => $data['excerpt'],
                'description' => $data['description'],
                'is_active' => true,
                'is_featured' => false,
                'display_order' => $order++,
                'unit' => 'adet',
                'base_price' => null,
                'setup_fee' => null,
                'tax_rate_percent' => 20,
                'duration_minutes' => $d2m(7),
            ];

            $service = Service::withTrashed()->firstOrNew(['slug' => $data['slug']]);

            if ($service->exists && $service->trashed()) {
                $service->restore();
            }

            $service->fill($payload)->save();

            if (method_exists($service, 'syncTags') && !empty($data['tags'])) {
                $service->syncTags($data['tags']);
            }
        }
    }
}
