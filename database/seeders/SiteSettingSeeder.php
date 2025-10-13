<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\SiteSetting;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $now = Carbon::now();

            // Verilen yolları storage-relative formata çevir
            $norm = function (?string $p): ?string {
                if (!$p) return null;
                $p = str_replace('\\', '/', $p);
                $p = preg_replace('#^public/storage/#i', '', $p); // "public/storage/site/..." → "site/..."
                $p = preg_replace('#^storage/#i', '', $p);        // "storage/site/..."      → "site/..."
                $p = preg_replace('#^public/#i', '', $p);         // "public/site/..."       → "site/..."
                return trim($p, '/');
            };

            $logoPath    = $norm('public\storage\site\01K5QRDW9DJ3W3B8N62KDS65G3.png');
            $faviconPath = $norm('public\storage\site\01K5QRDW9VBX8GMMJPFA8AT7J0.png');
            $metaImgPath = $norm('public\storage\site\01K5QRDW9W9GDJ2VSRDNYXMA42.png');

            $data = [
                // Meta / başlıklar
                'site_title'       => 'Sonar Medya',
                'meta_title'       => 'Sonar Medya | E-ticaret ve Performans Ajansı',
                'meta_description' => 'E-ticaret kurulumu, reklam yönetimi, entegrasyon ve analitik odaklı tam kapsamlı ajans.',
                'copyright_text'   => '© ' . $now->year . ' Sonar Medya. Tüm hakları saklıdır.',

                // Firma
                'company_name'   => 'Sonar Medya Yazılım ve Danışmanlık A.Ş.',
                'tax_office'     => 'Levent VD',
                'tax_number'     => '1234567890',
                'phone'          => '+90 (212) 000 00 00',
                'mobile'         => '+90 (530) 000 00 00',
                'email'          => 'info@sonarmedya.com',
                'address'        => 'Esentepe Mah., Büyükdere Cd. No:1 Şişli / İstanbul',
                'google_map_url' => 'https://maps.google.com/?q=Şişli+İstanbul',

                // WhatsApp
                'show_whatsapp_fab' => true,
                'whatsapp'          => '+90 (530) 000 00 00',

                // Sosyal
                'facebook'  => 'https://www.facebook.com/sonarmedya',
                'instagram' => 'https://www.instagram.com/sonarmedya',
                'twitter'   => 'https://x.com/sonarmedya',
                'linkedin'  => 'https://www.linkedin.com/company/sonarmedya',
                'youtube'   => 'https://www.youtube.com/@sonarmedya',

                // Görseller (storage-relative)
                'logo_path'       => $logoPath,    // örn: site/01K5...G3.png
                'favicon_path'    => $faviconPath, // örn: site/01K5...J0.png
                'meta_image_path' => $metaImgPath, // örn: site/01K5...42.png

                // Banka
                'bank_accounts' => [
                    [
                        'bank'     => 'Ziraat Bankası',
                        'iban'     => 'TR00 0000 0000 0000 0000 0000 00',
                        'name'     => 'Sonar Medya Yazılım ve Danışmanlık A.Ş.',
                        'currency' => 'TRY',
                    ],
                ],

                // Footer
                'footer_description' => 'Sonar Medya, e-ticaret kurulumu, performans pazarlaması ve entegrasyonlarda uzman bir ajanstır.',

                // Analytics / Pixel
                'ga_measurement_id' => 'G-XXXXXX0000',
                'gtm_id'            => 'GTM-XXXX000',
                'meta_pixel_id'     => '123456789000000',

                // Özel scriptler
                'head_scripts' => "<!-- head -->",
                'body_scripts' => "<!-- body -->",
            ];

            $settings = SiteSetting::query()->first();
            if ($settings) {
                $settings->fill($data);
                $settings->updated_at = $now;
                $settings->save();
            } else {
                $settings = new SiteSetting($data);
                $settings->created_at = $now;
                $settings->updated_at = $now;
                $settings->save();
            }
        });
    }
}
