<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesGorselTasarimFaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqMap = [
            'marka-odakli-gorseller' => [
                ['q' => 'Teslimatlar neleri içerir?', 'a' => 'Logo varyasyonları, sunum şablonu, sosyal şablonlar ve temel kullanım kılavuzu.'],
                ['q' => 'Marka uyumu nasıl sağlanır?', 'a' => 'Renk, tipografi ve ikonografi için stil kılavuzu çıkarır ve tüm çıktıların buna uymasını garanti ederiz.'],
                ['q' => 'Revizyon süreci nasıl işler?', 'a' => 'Ön izleme → geri bildirim → revizyon turları. Standart pakette 2 tur revizyon bulunur.'],
            ],
            'kreatif-kampanyalar' => [
                ['q' => 'Hangi formatları üretirsiniz?', 'a' => 'Afiş, banner, video kapak, email görseli ve outdoor ölçüleri. Medya planına uygun kırılımlar yapılır.'],
                ['q' => 'Onay akışı nasıl?', 'a' => 'Teklif ve brief → moodboard → ana fikir → uyarlamalar → son onay ve teslim.'],
                ['q' => 'Telif ve kullanım hakları?', 'a' => 'Proje bedeline dahil olacak şekilde sınırsız süreli marka kullanım hakkı verilir. Stok görseller için lisans şartları ayrıca geçerlidir.'],
            ],
            'sosyal-medya-icerikleri' => [
                ['q' => 'Hangi platformlara uygun?', 'a' => 'Instagram, TikTok, YouTube, LinkedIn. Her platform için oran ve grid kurallarına göre uyarlanır.'],
                ['q' => 'Şablon teslimi var mı?', 'a' => 'Evet. Düzenlenebilir şablonlar (ör. Figma/PSD) ve kapak setleri teslim edilir.'],
                ['q' => 'Takvim ve varyasyonlar?', 'a' => 'Aylık içerik takvimi çıkarılır. Gönderi, story ve kapak için varyasyon üretimi yapılır.'],
            ],
            'kurumsal-kimlik' => [
                ['q' => 'Kılavuz kapsamı?', 'a' => 'Logo kullanım kuralları, renk paleti, tipografi, boşluklar, görsel stil ve yanlış kullanım örnekleri.'],
                ['q' => 'Basılı materyaller dahil mi?', 'a' => 'Kartvizit, antetli, zarf ve sunum şablonları dahildir. Ek kurumsal setler opsiyoneldir.'],
                ['q' => 'Dosya formatları?', 'a' => 'SVG/PDF vektörel, PNG/JPG raster ve yazı tipi dosyaları standart teslimata dahildir.'],
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
