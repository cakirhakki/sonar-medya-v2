<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;
use App\Models\Service;

class ServicesYazilimMobilSeeder extends Seeder
{
    public function run(): void
    {
        $category = ServiceCategory::where('slug', 'yazilim-ve-mobil-uygulama')->firstOrFail();
        $d2m = fn(int $days) => $days * 1440;

        $items = [
            [
                'slug' => 'ihtiyaca-ozel-yazilim',
                'name' => 'İhtiyaca Özel Yazılım',
                'excerpt' => 'Sonar Medya tarafından geliştirilen size özel yazılım çözümleriyle iş süreçlerinizi dijitalleştirin.',
                'description' => 'Sonar Medya olarak, her işletmenin kendine özgü ihtiyaçlarına göre özelleştirilmiş yazılım çözümleri geliştiriyoruz. Hazır sistemlerin sınırlarını aşan, tamamen hedeflerinize ve operasyonel yapınıza uygun özel projeler üretiyoruz. Yönetim panelleri, müşteri portalları, rezervasyon sistemleri, raporlama araçları ve otomasyon altyapıları gibi geniş bir yelpazede kurumsal yazılımlar inşa ediyoruz. Geliştirme sürecinde modern yazılım mimarilerini, Laravel, Vue.js ve API tabanlı servisleri kullanıyoruz. Kod kalitesi, güvenlik, performans ve ölçeklenebilirlik ilkelerine bağlı kalarak sürdürülebilir projeler ortaya koyuyoruz. Sonar Medya’nın uzman ekibi, analizden canlıya geçişe kadar tüm aşamaları titizlikle yönetir. SEO uyumlu altyapı, hızlı yüklenme süreleri ve temiz kod yapısı sayesinde yazılımınız arama motorlarında da güçlü bir konum elde eder. Böylece markanız dijital dünyada hem teknik hem görünürlük açısından fark yaratır.',
                'tags' => ['yazılım', 'özelleştirme'],
            ],
            [
                'slug' => 'mobil-uygulama',
                'name' => 'Mobil Uygulama',
                'excerpt' => 'Sonar Medya ile iOS ve Android için yüksek performanslı mobil uygulama çözümleri.',
                'description' => 'Sonar Medya olarak markanızın mobil dünyadaki varlığını güçlendiren, kullanıcı odaklı ve yüksek performanslı uygulamalar geliştiriyoruz. Flutter, Swift ve Kotlin gibi güncel teknolojilerle hem iOS hem de Android platformlarında kusursuz çalışan uygulamalar üretiyoruz. UX/UI prensiplerine dayalı modern arayüzler, sezgisel navigasyon yapıları ve optimize edilmiş performans mimarileri sayesinde kullanıcılarınıza üst düzey bir deneyim sunuyoruz. Gerektiğinde CRM, e-ticaret, ERP veya özel API sistemleriyle entegre çalışan güçlü altyapılar oluşturuyoruz. Uygulama geliştirme sürecinde SEO ve ASO (App Store Optimization) standartlarını dikkate alarak markanızın dijital görünürlüğünü artırıyoruz. Sonar Medya ekibi, test, bakım ve sürekli iyileştirme süreçleriyle uygulamanızın uzun ömürlü, güvenli ve güncel kalmasını sağlar.',
                'tags' => ['mobil', 'ios', 'android'],
            ],
            [
                'slug' => 'entegrasyon-teknik-danismanlik',
                'name' => 'Entegrasyon & Teknik Danışmanlık',
                'excerpt' => 'Sonar Medya’dan API, sistem entegrasyonu ve dijital altyapı danışmanlığı çözümleri.',
                'description' => 'Sonar Medya, karmaşık dijital sistemlerin birbiriyle kusursuz iletişim kurmasını sağlayan profesyonel entegrasyon çözümleri sunar. ERP, CRM, muhasebe, kargo, pazaryeri, e-ticaret ve üçüncü parti servisler arasında güvenli, hızlı ve optimize veri akışı kuruyoruz. RESTful API, webhook, GraphQL ve mikroservis teknolojilerini kullanarak esnek ve ölçeklenebilir altyapılar tasarlıyoruz. Tüm süreçlerde veri güvenliği, kimlik doğrulama (OAuth 2.0, JWT) ve hata yönetimi prensiplerine dikkat ediyoruz. Entegrasyon öncesi detaylı teknik analiz yaparak sistemlerin birbirine en uygun şekilde bağlanmasını sağlıyoruz. Sonar Medya’nın teknik danışmanlık ekibi, projeyi sadece geliştirip teslim etmekle kalmaz, performans izleme, bakım ve sürdürülebilirlik desteği de sağlar. Bu sayede dijital ekosisteminiz bütünleşik, verimli ve SEO dostu bir yapıya kavuşur. Sonar Medya farkıyla altyapınızı geleceğe taşıyın.',
                'tags' => ['entegrasyon', 'api'],
            ],
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
