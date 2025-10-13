<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesDanismanlikFaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqMap = [
            'marka-danismanligi' => [
                ['q' => 'Teslimatlar neleri içerir?', 'a' => 'Konumlandırma çerçevesi, mesaj haritası, ton rehberi ve örnek kullanım dokümanları.'],
                ['q' => 'Süreç nasıl ilerler?', 'a' => 'Brief → araştırma (persona/rakip) → atölye → taslak → revizyon → final kılavuz.'],
                ['q' => 'Başarıyı nasıl ölçersiniz?', 'a' => 'Tutan mesajlar, marka hatırlanırlığı anketleri ve kanal bazlı etkileşim metrikleri.'],
            ],
            'influencer-ugc' => [
                ['q' => 'Seçim kriterleri nelerdir?', 'a' => 'Kitle uyumu, etkileşim oranı, içerik kalitesi, marka güvenliği ve geçmiş iş birliği performansı.'],
                ['q' => 'Sözleşme kapsamı?', 'a' => 'Kullanım süresi, mecralar, hak devri, revizyon ve yayın takvimi maddeleri netleştirilir.'],
                ['q' => 'Performans takibi?', 'a' => 'UTM/kod/kupon, tıklama ve dönüşüm izleme, içerik takvimine göre raporlama.'],
            ],
            'outdoor-reklam' => [
                ['q' => 'Lokasyon seçimi nasıl yapılır?', 'a' => 'Hedef kitle yoğunluğu, trafik verisi ve rakip görünürlüğü dikkate alınır.'],
                ['q' => 'GRP ve frekans hedefi?', 'a' => 'Kampanya amacına göre erişim/frekans dengesi kurulur, mecraya göre GRP planlanır.'],
                ['q' => 'Kreatif teslim ölçüleri?', 'a' => 'Billboard, raket, megalight ve citylight için medya planına uygun teknik spesifikasyon seti verilir.'],
            ],
            'tv-radyo-reklam' => [
                ['q' => 'Medya planı nasıl oluşturulur?', 'a' => 'Hedef kitle, yayın saatleri ve program uyumu analiz edilerek spot dağılımı yapılır.'],
                ['q' => 'Spot üretimi desteklenir mi?', 'a' => 'Evet. Senaryo, seslendirme, jingle ve miksaj süreçlerinde prodüksiyon desteği verilir.'],
                ['q' => 'Ölçümleme?', 'a' => 'Reyting/dinlenme verileri, frekans ve erişim kırılımları ile raporlanır.'],
            ],
            'ux-web-danismanligi' => [
                ['q' => 'Hangi analizler yapılır?', 'a' => 'Heuristik denetim, ısı haritası, oturum kayıtları, form analizi ve anketler.'],
                ['q' => 'Çıktılar?', 'a' => 'Önceliklendirilmiş öneri listesi, wireframe/maketler ve A/B test hipotezleri.'],
                ['q' => 'Ne zaman etki görürüm?', 'a' => 'Hızlı kazanımlar 2–4 hafta; büyük revizyonlar sonrası 4–8 hafta içinde metriklerde etki.'],
            ],
            'b2b-pazarlama' => [
                ['q' => 'Hedef hesap yaklaşımı?', 'a' => 'ICP tanımı, hedef hesap listesi ve çok kanallı temas akışları (email/LinkedIn/etkinlik).'],
                ['q' => 'Lead niteliği nasıl artırılır?', 'a' => 'İçerik nurturu, skorlama, MQL/SQL tanımları ve satış uyum toplantıları.'],
                ['q' => 'Raporlama?', 'a' => 'Pipeline katkısı, fırsat aşamaları, dokunuş sayısı ve kanal ROI raporları.'],
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
