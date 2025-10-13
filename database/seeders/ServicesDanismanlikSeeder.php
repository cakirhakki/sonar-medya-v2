<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;
use App\Models\Service;

class ServicesDanismanlikSeeder extends Seeder
{
    public function run(): void
    {
        $category = ServiceCategory::where('slug', 'danismanlik')->firstOrFail();
        $d2m = fn(int $days) => $days * 1440;

        $items = [
            ['slug' => 'marka-danismanligi', 'name' => 'Marka Danışmanlığı', 'excerpt' => 'Sonar Medya ile markanızı yeniden konumlandırın, güçlü bir ton rehberi oluşturun.', 'description' => 'Sonar Medya, markanızın kimliğini güçlendirmek için kapsamlı konumlandırma ve ton rehberi çalışmaları yürütür. Persona analizi, rakip araştırması ve mesaj çerçevesi oluşturma süreçleriyle markanızın pazarda nasıl algılandığını netleştiririz. Hedef kitleyle duygusal bağ kuran, tutarlı bir iletişim dili geliştiririz. Logo, renk, içerik dili ve sosyal medya tınısı dahil olmak üzere tüm temas noktalarınızı stratejik bir bütünlük içinde kurgularız. Sonar Medya’nın marka danışmanlığı ekibi, iletişim stratejinizi güçlendirir ve markanızı kalıcı hale getirir.', 'tags' => ['marka', 'strateji']],
            ['slug' => 'influencer-ugc', 'name' => 'Influencer & UGC', 'excerpt' => 'Sonar Medya ile etkili influencer iş birlikleri ve UGC stratejileri oluşturun.', 'description' => 'Sonar Medya, markanızı temsil edecek doğru içerik üreticilerini seçerek etkili UGC (User Generated Content) kampanyaları tasarlar. Influencer seçiminden brief oluşturulmasına, içerik takvimi yönetiminden performans analizine kadar tüm süreci yönetiriz. Markanızın hedef kitlesine en uygun iş birliklerini planlar, içeriklerin özgün, samimi ve ölçülebilir olmasını sağlarız. Raporlama süreçleriyle kampanya verimliliğini analiz eder, ROI değerlerini maksimize ederiz. Sonar Medya’nın influencer ve UGC danışmanlığı hizmetiyle marka güveninizi güçlendirir, sosyal etkileşimlerinizi artırırsınız.', 'tags' => ['influencer', 'ugc']],
            ['slug' => 'outdoor-reklam', 'name' => 'Outdoor Reklam', 'excerpt' => 'Sonar Medya’dan markanız için stratejik açık hava reklam planlaması.', 'description' => 'Sonar Medya, billboard, raket, megalight ve benzeri açık hava mecralarında stratejik medya planlaması yapar. Lokasyon analizi, görünürlük süresi, hedef kitle trafiği ve bütçe optimizasyonunu dikkate alarak markanız için en etkili yerleşimleri belirleriz. Bölgesel hedefleme ve mevsimsel kampanyalarla yatırım getirisini maksimize ederiz. Ayrıca dijital out-of-home (DOOH) reklam çözümleriyle kampanyalarınızı dinamik hale getiririz. Sonar Medya’nın deneyimi sayesinde açık hava reklam yatırımlarınız hem estetik hem stratejik değer kazanır.', 'tags' => ['outdoor', 'atl']],
            ['slug' => 'tv-radyo-reklam', 'name' => 'TV & Radyo Reklam', 'excerpt' => 'Sonar Medya ile TV ve radyo reklamlarınızı doğru kitleye ulaştırın.', 'description' => 'Sonar Medya, televizyon ve radyo kanallarında profesyonel medya planlama hizmeti sunar. Hedef kitlenize uygun yayın saatlerini ve program türlerini analiz eder, en yüksek erişimi sağlayacak yayın planlarını oluştururuz. Spot süreleri, frekans, GRP ve bütçe dengesi optimize edilerek marka mesajınızın doğru zamanda doğru kişiye ulaşmasını sağlarız. Kreatif ekibimizle birlikte senaryo, seslendirme ve prodüksiyon desteği de sunarız. Sonar Medya, geleneksel medyayı dijital stratejilerle entegre ederek bütüncül bir iletişim ağı kurar.', 'tags' => ['tv', 'radyo']],
            ['slug' => 'ux-web-danismanligi', 'name' => 'UX & Web Danışmanlığı', 'excerpt' => 'Sonar Medya’dan dönüşüm odaklı UX ve web danışmanlığı.', 'description' => 'Sonar Medya, web sitenizin kullanıcı deneyimini (UX) analiz eder ve dönüşüm oranlarını artıracak öneriler sunar. Kullanıcı yolculuğu haritalarını çıkarır, tıklama ve etkileşim verilerini inceler, form, CTA ve navigasyon gibi unsurları optimize ederiz. Arayüz tasarımında (UI) marka kimliğinize uygun, sade ve dönüşüm odaklı çözümler üretiriz. SEO, performans ve mobil uyumluluk testleriyle deneyimi güçlendiririz. Sonar Medya’nın UX danışmanlığı hizmetiyle web siteniz sadece güzel görünmekle kalmaz, satış ve etkileşim performansını da maksimize eder.', 'tags' => ['ux', 'ui']],
            ['slug' => 'b2b-pazarlama', 'name' => 'B2B Pazarlama', 'excerpt' => 'Sonar Medya ile bayi ve B2B pazarlama stratejilerinizi güçlendirin.', 'description' => 'Sonar Medya, toptancı, bayi ve iş ortaklarına özel B2B pazarlama stratejileri geliştirir. Satış temsilcisi ağı, bayi iletişimi ve lead kazanımı süreçlerini analiz ederek sürdürülebilir büyüme modelleri oluştururuz. Otomatik nurture senaryoları, CRM entegrasyonları ve e-posta zincirleriyle potansiyel müşterileri düzenli olarak besleriz. B2B kampanyalarınızda içerik, reklam ve satış kanallarını uyumlu hale getirir, iş ilişkilerinizi güçlendiririz. Sonar Medya, veri odaklı stratejilerle markanızı sektörde lider konuma taşır.', 'tags' => ['b2b', 'lead']],
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
