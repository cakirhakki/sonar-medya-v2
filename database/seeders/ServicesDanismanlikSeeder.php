<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;
use App\Models\Service;
use Illuminate\Support\Str;

class ServicesDanismanlikSeeder extends Seeder
{
    public function run(): void
    {
        // Kategori yoksa oluştur
        $category = ServiceCategory::firstOrCreate(
            ['slug' => 'danismanlik'],
            [
                'name'          => 'Danışmanlık',
                'description'   => 'Marka danışmanlığı, influencer & UGC, medya planlama, B2B, UX.',
                'is_active'     => true,
                'show_in_menu'  => true,
                'menu_mode'     => 0,   // 0=Hepsi, 1=Seçililer
                'display_order' => 7,
            ]
        );

        $d2m = fn(int $days) => $days * 1440;

        $items = [
            ['slug' => 'marka-danismanligi',   'name' => 'Marka Danışmanlığı',         'excerpt' => 'Sonar Medya ile markanızı yeniden konumlandırın, güçlü bir ton rehberi oluşturun.', 'description' => '...', 'tags' => ['marka','strateji']],
            ['slug' => 'influencer-ugc',       'name' => 'Influencer & UGC',           'excerpt' => 'Sonar Medya ile etkili influencer iş birlikleri ve UGC stratejileri oluşturun.',   'description' => '...', 'tags' => ['influencer','ugc']],
            ['slug' => 'outdoor-reklam',       'name' => 'Outdoor Reklam',             'excerpt' => 'Sonar Medya’dan markanız için stratejik açık hava reklam planlaması.',             'description' => '...', 'tags' => ['outdoor','atl']],
            ['slug' => 'tv-radyo-reklam',      'name' => 'TV & Radyo Reklam',          'excerpt' => 'Sonar Medya ile TV ve radyo reklamlarınızı doğru kitleye ulaştırın.',             'description' => '...', 'tags' => ['tv','radyo']],
            ['slug' => 'ux-web-danismanligi',  'name' => 'UX & Web Danışmanlığı',      'excerpt' => 'Sonar Medya’dan dönüşüm odaklı UX ve web danışmanlığı.',                          'description' => '...', 'tags' => ['ux','ui']],
            ['slug' => 'b2b-pazarlama',        'name' => 'B2B Pazarlama',              'excerpt' => 'Sonar Medya ile bayi ve B2B pazarlama stratejilerinizi güçlendirin.',             'description' => '...', 'tags' => ['b2b','lead']],
        ];

        $order = 1;
        foreach ($items as $data) {
            $payload = [
                'service_category_id' => $category->id,
                'name'                => $data['name'],
                'slug'                => $data['slug'],
                'excerpt'             => $data['excerpt'],
                'description'         => $data['description'],
                'is_active'           => true,
                'is_featured'         => false,
                'display_order'       => $order++,
                'unit'                => 'adet',
                'base_price'          => null,
                'setup_fee'           => null,
                'tax_rate_percent'    => 20,
                'duration_minutes'    => $d2m(7),
            ];

            // Kategoriye göre benzersizleştir
            $service = Service::withTrashed()->firstOrNew([
                'service_category_id' => $category->id,
                'slug'                => $data['slug'],
            ]);

            if ($service->exists && $service->trashed()) {
                $service->restore();
            }

            $service->fill($payload)->save();

            if (method_exists($service, 'syncTags') && !empty($data['tags'])) {
                $service->syncTags($data['tags']);
            }
        }

        // Eğer bu kategori Seçililer moduna alınacaksa pivot senkronu örneği:
        if ((int) $category->menu_mode === 1) {
            $ids = Service::where('service_category_id', $category->id)
                ->where('is_active', true)
                ->orderBy('display_order')->orderBy('name')
                ->pluck('id')->values()->all();

            $sync = [];
            foreach ($ids as $i => $sid) {
                $sync[$sid] = ['position' => $i];
            }
            $category->menuServices()->sync($sync);
        } else {
            // Hepsi modunda pivot boş kalsın
            $category->menuServices()->detach();
        }
    }
}
