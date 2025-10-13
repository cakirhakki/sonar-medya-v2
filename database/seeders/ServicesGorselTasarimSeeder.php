<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;
use App\Models\Service;

class ServicesGorselTasarimSeeder extends Seeder
{
    public function run(): void
    {
        $category = ServiceCategory::where('slug', 'gorsel-tasarim')->firstOrFail();
        $d2m = fn(int $days) => $days * 1440;

        $items = [
            ['slug' => 'marka-odakli-gorseller', 'name' => 'Marka Odaklı Görseller', 'excerpt' => 'Sonar Medya’dan markanızın kimliğini güçlendiren kurumsal görsel setleri.', 'description' => 'Sonar Medya, markaların görsel kimliğini dijital dünyada güçlü bir şekilde temsil edebilmesi için özel kurumsal görsel setleri tasarlar. Logo varyasyonlarından kurumsal sunum şablonlarına, sosyal medya paylaşımlarından kampanya materyallerine kadar tüm görsel ihtiyaçlarınızı tek bir bütünlük içinde ele alıyoruz. Tasarımlarımızda renk uyumu, tipografi, oran, boşluk ve hiyerarşi gibi detaylara özen gösteriyor; hem estetik hem de stratejik bir bütünlük sağlıyoruz. Sonar Medya’nın deneyimli tasarım ekibi, markanızın vizyonuna uygun modern, sade ve etkileyici görseller üretir. Bu sayede hem dijital hem de basılı mecralarda markanızın güvenilirliği ve algısı güçlenir.', 'tags' => ['kurumsal', 'görsel']],
            ['slug' => 'kreatif-kampanyalar', 'name' => 'Kreatif Kampanyalar', 'excerpt' => 'Sonar Medya ile fark yaratan yaratıcı reklam tasarımları.', 'description' => 'Sonar Medya, markanızı öne çıkaran özgün ve dikkat çekici reklam tasarımları oluşturur. Hedef kitlenizi tanır, onların ilgisini çekecek yaratıcı konseptleri stratejik olarak geliştiririz. Afiş, billboard, sosyal medya duyurusu, dijital kampanya ve banner tasarımları gibi çok kanallı çözümler sunarız. Renk psikolojisi, görsel denge ve çağrışım gücü ilkelerini kullanarak hem markanızın mesajını güçlendirir hem de etkileşim oranlarını artırırız. Sonar Medya’nın kreatif ekibi, reklam tasarımlarında sadece estetik değil, performans odaklı düşünür. Tüm kampanyalar SEO, performans pazarlaması ve marka algısı açısından optimize edilir, böylece markanız dijital dünyada fark yaratır.', 'tags' => ['kreatif', 'kampanya']],
            ['slug' => 'sosyal-medya-icerikleri', 'name' => 'Sosyal Medya İçerikleri', 'excerpt' => 'Sonar Medya tarafından hazırlanan sosyal medya platformlarına özel görsel şablonlar.', 'description' => 'Sonar Medya, her sosyal medya platformunun dinamiklerine uygun özgün görsel içerikler üretir. Instagram, TikTok, LinkedIn, YouTube ve Facebook gibi mecralar için formatlara özel, kullanıcı etkileşimini artıran şablonlar tasarlarız. Görseller, markanızın renk paletine ve iletişim tonuna uygun şekilde hazırlanır; paylaşımlarınızda profesyonel bir bütünlük sağlar. Hareketli postlar, reels kapakları, hikâye şablonları ve etkileşim odaklı tasarımlar ile kitlenizi daha fazla çekeriz. Sonar Medya’nın sosyal medya tasarım ekibi, trendleri yakından takip eder ve her tasarımı SEO ve marka bilinirliği açısından optimize eder. Böylece dijital mecralarda hem estetik hem stratejik olarak güçlü bir varlık oluşturursunuz.', 'tags' => ['sosyal-medya', 'şablon']],
            ['slug' => 'kurumsal-kimlik', 'name' => 'Kurumsal Kimlik', 'excerpt' => 'Sonar Medya’dan profesyonel kurumsal kimlik ve marka rehberi tasarımı.', 'description' => 'Sonar Medya, markanızın tüm görsel iletişim unsurlarını kapsayan profesyonel bir kurumsal kimlik sistemi oluşturur. Logo, renk paleti, tipografi, ikonografi, kartvizit, e-posta imzası, sunum ve dijital doküman şablonları gibi tüm bileşenleri tek bir marka rehberinde toplarız. Bu rehber, markanızın tüm mecralarda tutarlı bir şekilde temsil edilmesini sağlar. Tasarım sürecinde hedef kitlenizi, sektörel konumunuzu ve marka değerlerinizi analiz ederiz. Minimalist, modern ve zamana karşı dayanıklı tasarım ilkeleriyle uzun ömürlü bir kimlik geliştiririz. Sonar Medya’nın uzman ekibi, kurumsal kimliğinizi sadece görsel değil, duygusal bir deneyime dönüştürür; böylece markanızın profesyonel ve güvenilir algısı güçlenir.', 'tags' => ['kimlik', 'branding']],
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
