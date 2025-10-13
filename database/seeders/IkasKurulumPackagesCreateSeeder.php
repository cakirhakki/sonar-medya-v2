<?php

namespace Database\Seeders;

// Database\Seeders\IkasKurulumPackagesCreateSeeder.php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\PackageCategory;
use App\Models\ServicePackage;

class IkasKurulumPackagesCreateSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $now = Carbon::now();

            // Kategori (idempotent + restore)
            $cat = PackageCategory::withTrashed()->firstOrCreate(
                ['slug' => 'ikas-kurulum'],
                [
                    'name'       => 'İkas Kurulum',
                    'image_path' => 'https://picsum.photos/seed/ikas-kurulum/1200/630',
                    'image_alt'  => 'İkas Kurulum Paket Kategorisi',
                    'is_active'  => true,
                    'sort'       => 10,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
            if ($cat->trashed()) $cat->restore();

            $defs = [
                [
                    'slug'  => 'ikas-kurulum-eco',
                    'name'  => 'İkas Kurulum ECO',
                    'short' => 'İkas için temel kurulum ve bağlantılar.',
                    'desc'  => 'Tema kurulumu, temel ayarlar ve piksel entegrasyonu ile hızlı başlangıç. İçerik ve entegrasyon kapsamı minimaldir.',
                ],
                [
                    'slug'  => 'ikas-kurulum-start',
                    'name'  => 'İkas Kurulum START',
                    'short' => 'Kurulum + görsel destek + temel otomasyon.',
                    'desc'  => 'ECO kapsamına ek olarak görsel/banner desteği, kampanya kurguları ve temel otomasyon.',
                ],
                [
                    'slug'  => 'ikas-kurulum-pro',
                    'name'  => 'İkas Kurulum PRO',
                    'short' => 'Gelişmiş entegrasyon ve danışmanlık.',
                    'desc'  => 'START kapsamına ek gelişmiş entegrasyonlar, segmentasyon ve danışmanlık.',
                ],
            ];

            foreach ($defs as $d) {
                // create: code otomatik; update: code dokunma
                $pkg = ServicePackage::withTrashed()->where('slug', $d['slug'])->first();

                if (! $pkg) {
                    $pkg = ServicePackage::create([
                        'package_category_id' => $cat->id,
                        'name'               => $d['name'],
                        'slug'               => $d['slug'],   // varsa bunu kullan, yoksa creating slug üretir
                        'short_description'  => $d['short'],
                        'description'        => $d['desc'],
                        'show_price'         => true,
                        'currency'           => 'TRY',
                        'currency_rate'      => null,
                        'override_price'     => null,
                        'status'             => ServicePackage::STATUS_PUBLISHED,
                        'sent_at'            => null,
                        'accepted_at'        => null,
                        'published_at'       => $now,
                        'expires_at'         => null,
                        'created_at'         => $now,
                        'updated_at'         => $now,
                    ]);
                } else {
                    if ($pkg->trashed()) $pkg->restore();

                    $pkg->fill([
                        'package_category_id' => $cat->id,
                        'name'               => $d['name'],
                        'short_description'  => $d['short'],
                        'description'        => $d['desc'],
                        'show_price'         => true,
                        'currency'           => 'TRY',
                        'currency_rate'      => null,
                        'override_price'     => null,
                        'status'             => ServicePackage::STATUS_PUBLISHED,
                        'sent_at'            => null,
                        'accepted_at'        => null,
                        'published_at'       => $now,
                        'expires_at'         => null,
                        'updated_at'         => $now,
                    ])->save();
                }
            }
        });
    }
}

