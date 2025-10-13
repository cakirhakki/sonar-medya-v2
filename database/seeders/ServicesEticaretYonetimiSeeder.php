<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;
use App\Models\Service;

class ServicesEticaretYonetimiSeeder extends Seeder
{
    public function run(): void
    {
        $category = ServiceCategory::where('slug', 'e-ticaret-yonetimi')->firstOrFail();
        $d2m = fn(int $days) => $days * 1440;

        $items = [
            ['slug' => 'altyapi-teknik-kurulum', 'name' => 'Altyapı & Teknik Kurulum', 'excerpt' => 'Sonar Medya’dan satışa hazır profesyonel e-ticaret tema kurulumu.', 'description' => 'Sonar Medya, markanıza özel profesyonel e-ticaret tema kurulumu hizmetiyle sitenizi kısa sürede yayına hazır hale getirir. Tema seçimi, görsel yerleşim, tipografi, renk uyumu ve sayfa düzeni markanızın kimliğine göre uyarlanır. Ürün sayfaları, kategori yapıları ve ödeme adımları kullanıcı deneyimi (UX) prensiplerine göre optimize edilir. SEO ve hız performansı dikkate alınarak yapılan teknik düzenlemeler sayesinde siteniz hem Google’da hem kullanıcı gözünde fark yaratır. Sonar Medya ekibi, görsel destekle birlikte tam donanımlı bir başlangıç sağlar; markanız dijitalde güçlü bir vitrine kavuşur.', 'tags' => ['kurulum', 'tema']],
            ['slug' => 'sistem-entegrasyonlari', 'name' => 'Sistem Entegrasyonları', 'excerpt' => 'Sonar Medya ile tüm sistemlerinizi entegre ederek operasyonel verimliliği artırın.', 'description' => 'Sonar Medya, e-ticaret sitenizin tüm iş süreçlerini birbirine bağlayan kapsamlı sistem entegrasyonları sağlar. Kargo, ödeme, muhasebe, ERP ve pazaryeri sistemlerini tek bir merkezden yönetilebilir hale getiririz. Trendyol, Hepsiburada, İkas, Faprika, Mikro, Paraşüt, Logo ve benzeri altyapılarla sorunsuz bağlantılar kurarak stok, sipariş ve fatura süreçlerini otomatikleştiriyoruz. Bu sayede manuel hataları azaltır, operasyonel hız ve verimlilik sağlarsınız. Sonar Medya’nın teknik ekibi, API ve webhook entegrasyonlarıyla ölçeklenebilir bir dijital altyapı oluşturur; hem zamandan hem maliyetten tasarruf edersiniz.', 'tags' => ['entegrasyon', 'operasyon']],
            ['slug' => 'pazarlama-iletisim', 'name' => 'Pazarlama & İletişim', 'excerpt' => 'Sonar Medya’dan etkili pazarlama ve iletişim stratejileriyle marka bilinirliğini artırın.', 'description' => 'Sonar Medya, markanızın hedef kitlesine en doğru mesajla ulaşmasını sağlayan dijital pazarlama ve iletişim stratejileri geliştirir. İçerik üretimi, sosyal medya yönetimi, e-posta pazarlama ve CRM entegrasyonlarıyla müşteri etkileşimini sürdürülebilir hale getiriyoruz. Her mesajın tonu, zamanı ve kanalı analiz edilerek marka dilinizin tutarlılığı korunur. SEO uyumlu içerikler, kampanya metinleri ve otomatik iletişim akışlarıyla dönüşüm oranlarını artırırız. Sonar Medya’nın pazarlama ekibi, performans verilerini düzenli olarak analiz eder ve iletişim stratejinizi sürekli geliştirir. Böylece markanız hem görünür hem de akılda kalıcı hale gelir.', 'tags' => ['pazarlama', 'iletişim']],
            ['slug' => 'seo-icerik-yonetimi', 'name' => 'SEO & İçerik Yönetimi', 'excerpt' => 'Sonar Medya ile iş akışlarınızı otomatikleştirerek verimliliği artırın.', 'description' => 'Sonar Medya, e-ticaret süreçlerinizi hızlandıran ve manuel işleri ortadan kaldıran otomasyon çözümleri geliştirir. Kampanya kurguları, stok yönetimi, fiyat güncellemeleri, bildirim akışları ve müşteri segmentasyonu gibi operasyonel süreçleri akıllı tetikleyicilerle otomatik hale getiriyoruz. Örneğin stok azaldığında tedarik uyarısı, kampanya başladığında e-posta bildirimi veya müşteri eylemine göre kişisel indirim gibi senaryolar tasarlıyoruz. Bu yapı yalnızca zaman kazandırmakla kalmaz, müşteri memnuniyetini de artırır. Sonar Medya’nın teknik danışmanlık ekibi, tüm bu süreçleri analiz ederek ölçeklenebilir bir iş akışı otomasyonu kurar.', 'tags' => ['seo', 'içerik']],
            ['slug' => 'otomasyon-surec-tasarimi', 'name' => 'Otomasyon & Süreç Tasarımı', 'excerpt' => 'Sonar Medya ile e-ticaret süreçlerinizi otomatikleştirin.', 'description' => 'Sonar Medya, işletmelerin manuel operasyonlarını azaltarak verimliliği artıran iş akışı otomasyonları tasarlar. Kampanya yönetimi, stok güncellemeleri, sipariş bildirimleri ve müşteri etkileşimleri gibi süreçler için akıllı tetikleyiciler kurarız. Örneğin stok seviyesi azaldığında otomatik uyarı, yeni kampanya başladığında toplu e-posta bildirimi veya sipariş tamamlandığında kişisel teşekkür mesajı gibi senaryolar tanımlanabilir. Bu otomasyonlar hem zaman tasarrufu sağlar hem de müşteri memnuniyetini artırır. Sonar Medya’nın teknik ekibi, tüm süreçleri analiz ederek markanıza özel, ölçeklenebilir ve güvenli otomasyon altyapısı oluşturur. Böylece iş akışlarınız hatasız, hızlı ve sürdürülebilir hale gelir.', 'tags' => ['otomasyon', 'süreç']],
            ['slug' => 'destek-egitim-raporlama', 'name' => 'Destek, Eğitim & Raporlama', 'excerpt' => 'Sonar Medya’dan eğitim, destek ve performans raporlama hizmetleri.', 'description' => 'Sonar Medya, e-ticaret operasyonlarınızı sürdürülebilir hale getirmek için kapsamlı eğitim ve destek hizmetleri sunar. Yönetim paneli, ürün girişi, sipariş takibi ve pazarlama araçlarının kullanımı konularında ekibinizi bilgilendiririz. Teknik destek ekibimiz olası hataları ve geliştirme ihtiyaçlarını hızlıca çözer. Ayrıca aylık ve haftalık performans raporlamalarıyla site trafiği, satış dönüşüm oranı ve reklam performansını analiz ederiz. Bu raporlar, markanızın büyüme stratejisini veriye dayalı şekilde yönlendirmenizi sağlar. Sonar Medya’nın desteğiyle dijital operasyonlarınız hem güçlü hem sürdürülebilir bir yapıya kavuşur.', 'tags' => ['destek', 'eğitim', 'rapor']],
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
