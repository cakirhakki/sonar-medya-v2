<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;
use App\Models\Service;

class ServicesDijitalPazarlamaSeeder extends Seeder
{
    public function run(): void
    {
        $category = ServiceCategory::where('slug', 'dijital-pazarlama')->firstOrFail();
        $d2m = fn(int $days) => $days * 1440;

        $items = [
            ['slug' => 'meta-ads', 'name' => 'Meta Ads', 'excerpt' => 'Sonar Medya ile Facebook ve Instagram reklam performansınızı en üst seviyeye çıkarın.', 'description' => 'Sonar Medya, Facebook, Instagram, Messenger ve Audience Network platformlarında profesyonel reklam yönetimi hizmeti sunar. Hedef kitlenizi demografik, davranışsal ve ilgi alanlarına göre analiz ederek doğru kişilere ulaşmanızı sağlarız. Görsel, metin ve CTA (call to action) uyumunu optimize eder; dönüşüm odaklı kampanya yapıları oluştururuz. A/B testleri, piksel kurulumu, remarketing stratejileri ve kampanya optimizasyonlarıyla ROAS değerlerinizi yükseltiriz. Sonar Medya’nın dijital pazarlama uzmanları, markanızın görünürlüğünü artırırken bütçenizin maksimum verimle kullanılmasını sağlar.', 'tags' => ['meta', 'facebook', 'instagram']],
            ['slug' => 'google-ads', 'name' => 'Google Ads', 'excerpt' => 'Sonar Medya ile Google Ads kampanyalarınızı veriye dayalı yönetin.', 'description' => 'Sonar Medya, Google Ads üzerinde Arama, Görüntülü, Video ve Alışveriş kampanyaları kurarak markanızın dijital görünürlüğünü artırır. Anahtar kelime analizi, rakip incelemesi ve kullanıcı niyetine dayalı hedeflemelerle maksimum dönüşüm oranı elde etmenizi sağlarız. Smart Bidding, dinamik reklam grupları ve konum bazlı hedefleme gibi ileri seviye optimizasyon yöntemleri uygularız. Google Analytics ve Search Console entegrasyonlarıyla performans raporlarını düzenli olarak takip ederiz. Sonar Medya, kampanyalarınızı yalnızca tıklama değil, satış dönüşümüne göre yönetir.', 'tags' => ['google', 'search', 'shopping']],
            ['slug' => 'criteo', 'name' => 'Criteo', 'excerpt' => 'Sonar Medya’dan e-ticaret için dinamik ürün ve yeniden hedefleme stratejileri.', 'description' => 'Sonar Medya, Criteo ve benzeri platformlar aracılığıyla e-ticaret siteleri için dinamik ürün reklamları ve retargeting kampanyaları kurgular. Site ziyaretçilerinizi takip eder, ürün görüntüleme ve sepete ekleme davranışlarına göre kişiselleştirilmiş reklam akışları oluştururuz. Bu sayede potansiyel müşterileri satışa dönüştürür, dönüşüm oranlarını yükseltiriz. Dinamik ürün feed’leri, görsel optimizasyon ve teklif stratejileriyle her gösterimden maksimum geri dönüş sağlanır. Sonar Medya’nın uzman ekibi, kullanıcı yolculuğunu analiz ederek kampanya maliyetlerinizi optimize eder.', 'tags' => ['retargeting', 'dynamic-ads']],
            ['slug' => 'youtube-ads', 'name' => 'YouTube Ads', 'excerpt' => 'Sonar Medya ile YouTube reklamlarında markanızı ön plana çıkarın.', 'description' => 'Sonar Medya, YouTube Ads üzerinden dikkat çekici video kampanyaları tasarlar. Bumper, TrueView, Masthead ve in-stream reklam formatlarıyla markanızı doğru hedef kitleye ulaştırırız. 6 saniyelik kısa reklamlar veya uzun formatlı hikâye anlatımı ile kullanıcı etkileşimini artırırız. Reklamların tıklama oranı, izlenme süresi ve dönüşüm performansı düzenli olarak analiz edilerek optimize edilir. Kreatif ekibimiz, video içeriklerinizi marka kimliğinize uygun hale getirir. Sonar Medya’nın deneyimiyle YouTube reklamlarınız hem izlenir hem dönüşüm sağlar.', 'tags' => ['youtube', 'video']],
            ['slug' => 'tiktok-ads', 'name' => 'TikTok Ads', 'excerpt' => 'Sonar Medya ile TikTok Ads kampanyalarıyla genç kitleye ulaşın.', 'description' => 'Sonar Medya, TikTok Ads platformunda genç ve etkileşim odaklı kitlelere ulaşmanızı sağlar. Kısa video trendlerine uygun yaratıcı reklam içerikleri tasarlar, markanızı eğlenceli ve dikkat çekici biçimde öne çıkarırız. In-Feed, Spark Ads, Branded Hashtag Challenge ve TopView gibi formatlarla hedef kitlenize uygun kampanyalar oluştururuz. Görsel dinamizm, müzik seçimi ve etkileşim senaryoları markanızın sosyal medyada doğal ve etkileyici görünmesini sağlar. Sonar Medya’nın performans ekibi, TikTok reklamlarınızı dönüşüm hedeflerine göre sürekli optimize eder.', 'tags' => ['tiktok', 'video']],
            ['slug' => 'rtb-house', 'name' => 'RTB House', 'excerpt' => 'Sonar Medya’dan yapay zekâ destekli yeniden hedefleme çözümleri.', 'description' => 'Sonar Medya, RTB House ve benzeri yapay zekâ tabanlı reklam teknolojileriyle kişiselleştirilmiş yeniden hedefleme kampanyaları yürütür. Makine öğrenimi algoritmaları sayesinde her kullanıcının davranışını analiz eder, en yüksek dönüşüm potansiyeline sahip reklamı otomatik olarak sunarız. Görsel uyum, zamanlama ve teklif optimizasyonu tamamen dinamik biçimde gerçekleşir. Bu sistem, özellikle e-ticaret markaları için yeniden satış oranlarını belirgin şekilde artırır. Sonar Medya, AI destekli dijital reklam stratejileriyle bütçenizi verimli kullanmanızı ve hedef kitlenizle akıllı iletişim kurmanızı sağlar.', 'tags' => ['rtb', 'ai']],
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

            // slug'a göre mevcut kaydı (soft-deleted dahil) bul
            $service = Service::withTrashed()->firstOrNew(['slug' => $data['slug']]);

            // soft-deleted ise dirilt
            if ($service->exists && $service->trashed()) {
                $service->restore();
            }

            // alanları güncelle/kaydet (slug korunur)
            $service->fill($payload)->save();

            // etiketleri senkronla (varsa)
            if (method_exists($service, 'syncTags') && !empty($data['tags'])) {
                $service->syncTags($data['tags']);
            }
        }
    }
}
