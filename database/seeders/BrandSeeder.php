<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $dir = database_path('seeders/assets/brands');
        $exts = ['png','jpg','jpeg','webp','svg'];

        if (! is_dir($dir)) {
            $this->command?->warn("Klasör yok: {$dir}");
            return;
        }

        $files = collect(File::files($dir))
            ->filter(fn($f) => in_array(strtolower($f->getExtension()), $exts))
            ->values();

        $orderTop = 1;
        $group    = 'top';

        foreach ($files as $f) {
            $stem = pathinfo($f->getFilename(), PATHINFO_FILENAME);
            $name = preg_replace('/[_\-]+/u', ' ', $stem);
            $name = trim($name);
            if (function_exists('mb_convert_case')) {
                $name = mb_convert_case($name, MB_CASE_TITLE, 'UTF-8');
            }

            $slug = Str::slug($name, '-', 'tr');

            $brand = Brand::updateOrCreate(
                ['slug' => $slug],
                [
                    'name'          => $name,
                    'url'           => null,
                    'group'         => $group,
                    'display_order' => $orderTop++,
                    'is_active'     => true,
                ]
            );

            $brand->addMedia($f->getPathname())
                ->preservingOriginal()
                ->toMediaCollection('logo');
        }
    }
}
