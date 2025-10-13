<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServicePackage;

class IkasKurulumPackageFaqsSeeder extends Seeder
{
    public function run(): void
    {
        // Paket slug -> FAQ listesi (en az 3)
        $faqMap = [
            'ikas-kurulum-eco' => [
                ['q' => 'Bu paket kimler için uygun?', 'a' => 'İlk kez İkas ile yayına çıkacak küçük/orta ölçekli mağazalar için uygundur. Temel kurulum ve ölçümleme içerir.'],
                ['q' => 'Kurulum süresi nedir?', 'a' => 'Hazırlıklar tamam ise 1–3 iş günü içinde kurulum bitirilir ve temel testler yapılır.'],
                ['q' => 'Dahil olanlar nelerdir?', 'a' => 'Tema kurulumu, temel ayarlar, GA4/Pixel entegrasyonu ve statik sayfa düzenlemeleri dahildir.'],
            ],
            'ikas-kurulum-start' => [
                ['q' => 'ECO paketten farkı nedir?', 'a' => 'ECO kapsamına ek olarak banner/görsel desteği, kampanya ve temel otomasyon kurguları eklenir.'],
                ['q' => 'Görsel teslim formatı nedir?', 'a' => 'Web için optimize edilmiş görseller ve kaynak dosyalar (uygunsa) teslim edilir.'],
                ['q' => 'Teslim sonrası destek var mı?', 'a' => 'Teslimde kısa eğitim ve ilk hafta temel yönlendirmeler sağlanır. İhtiyaç halinde ek destek verilir.'],
            ],
            'ikas-kurulum-pro' => [
                ['q' => 'PRO paket neleri ekler?', 'a' => 'START kapsamına ek olarak gelişmiş entegrasyonlar, segmentasyon, raporlama ve danışmanlık bulunur.'],
                ['q' => 'Hangi entegrasyonlar desteklenir?', 'a' => 'Muhasebe, kargo ve pazaryeri entegrasyonları, mevcut altyapıya ve API kabiliyetine göre kurulur.'],
                ['q' => 'Raporlama ve izleme nasıl yapılır?', 'a' => 'Düzenli performans raporları ve aksiyon listeleri sunulur; kritik metrikler takip edilir.'],
            ],
        ];

        foreach ($faqMap as $pkgSlug => $items) {
            $pkg = ServicePackage::where('slug', $pkgSlug)->first();
            if (! $pkg) {
                $this->command?->warn("Paket bulunamadı: {$pkgSlug}");
                continue;
            }

            $keep = [];
            $sort = 1;

            foreach ($items as $qa) {
                $keep[] = $qa['q'];

                // Idempotent: aynı soruyu güncelle, yoksa oluştur
                $pkg->faqs()->updateOrCreate(
                    ['question' => $qa['q']],
                    ['answer' => $qa['a'], 'sort_order' => $sort++]
                );
            }

            // Harici eski FAQ'ları temizle (soft delete varsa soft)
            $pkg->faqs()->whereNotIn('question', $keep)->delete();
        }
    }
}
