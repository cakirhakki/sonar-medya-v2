<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesDijitalPazarlamaFaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqMap = [
            'meta-ads' => [
                ['q' => 'Hedefleme nasıl yapılır?', 'a' => 'İlgi alanı, davranış, lookalike ve özel kitle (pixel/CRM) kombinasyonlarıyla hedeflenir.'],
                ['q' => 'Hangi kampanya türleri?', 'a' => 'Dönüşüm, trafik, katalog satışları, lead ve awareness kampanyaları.'],
                ['q' => 'Optimizasyon süreci?', 'a' => 'Öğrenme aşaması sonrası A/B test, kreatif rotasyonu ve event bazlı optimizasyon yapılır.'],
            ],
            'google-ads' => [
                ['q' => 'Hangi ağlar kullanılır?', 'a' => 'Arama, Görüntülü, Alışveriş, Performance Max ve YouTube.'],
                ['q' => 'Anahtar kelime stratejisi?', 'a' => 'Eşleme türleri (geniş/dar/ifade) ve negatif liste ile niyet odaklı yapı kurulur.'],
                ['q' => 'İzleme nasıl yapılır?', 'a' => 'GA4, dönüşüm etiketleri ve gelişmiş eşleştirme ile performans izlenir.'],
            ],
            'criteo' => [
                ['q' => 'Criteo ne zaman uygundur?', 'a' => 'Katalog ve yüksek ürün trafiğine sahip e-ticaret sitelerinde yeniden hedefleme için etkilidir.'],
                ['q' => 'Feed gereksinimi?', 'a' => 'Kategori, fiyat, stok, görsel ve URL alanlarını içeren güncel ürün feed’i gerekir.'],
                ['q' => 'Ölçümleme?', 'a' => 'View-through ve click-through dönüşümler raporlanır, deduplikasyon kuralları uygulanır.'],
            ],
            'youtube-ads' => [
                ['q' => 'Hangi formatlar?', 'a' => 'Bumper, skippable ve non-skippable In-Stream, In-Feed (Discovery) formatları.'],
                ['q' => 'Kreatif önerileri?', 'a' => 'İlk 5 sn’de mesaj, güçlü CTA, dikey ve yatay varyant, altyazı ve kısa versiyonlar.'],
                ['q' => 'Hedefleme?', 'a' => 'Özel hedef kitle, niyet sinyalleri, demografi ve yerleşim bazlı hedefleme.'],
            ],
            'tiktok-ads' => [
                ['q' => 'Dikkat çeken içerik nasıl olur?', 'a' => 'Hızlı açılan, doğal görünen, metin overlay ve trend seslerle uyumlu kısa videolar.'],
                ['q' => 'Optimizasyon neye göre?', 'a' => 'Tamamlama oranı, izlenme süresi, tıklama ve dönüşüm sinyallerine göre.'],
                ['q' => 'Hangi kampanyalar?', 'a' => 'Traffic, Community Interaction, Leads ve Conversions. Katalog entegrasyonu mümkündür.'],
            ],
            'rtb-house' => [
                ['q' => 'RTB House ne sunar?', 'a' => 'Makine öğrenimi ile kişiselleştirilmiş yeniden hedefleme ve prospeksiyon.'],
                ['q' => 'Kurulum gereksinimleri?', 'a' => 'Etiket kurulumu, ürün feed’i ve dönüşüm olaylarının tanımlanması.'],
                ['q' => 'Raporlama ve şeffaflık?', 'a' => 'Yerleşim, frekans, görünürlük ve dönüşüm kırılımları detaylı raporlanır.'],
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
