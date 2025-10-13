<?php

namespace Database\Seeders;

use App\Models\PackageCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PackageCategoryIkasKurulumSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $rows = [
            [
                'name'       => 'İkas Kurulum',
                'slug'       => Str::slug('İkas Kurulum'),
                'image_path' => 'https://picsum.photos/seed/ikas-kurulum/1200/630',
                'image_alt'  => 'İkas Kurulum Paket Kategorisi',
                'is_active'  => true,
                'sort'       => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Idempotent: var ise güncelle, soft-deleted ise dirilt
        PackageCategory::upsert(
            $rows,
            ['slug'],
            ['name', 'image_path', 'image_alt', 'is_active', 'sort', 'updated_at']
        );

        PackageCategory::withTrashed()
            ->whereIn('slug', array_column($rows, 'slug'))
            ->restore();
    }
}
