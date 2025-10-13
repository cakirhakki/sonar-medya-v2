<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('site_settings', function (Blueprint $t) {
            $t->id();

            // Genel
            $t->string('site_title')->nullable();
            $t->string('meta_title')->nullable();
            $t->text('meta_description')->nullable();
            $t->text('copyright_text')->nullable();

            // İletişim
            $t->string('company_name')->nullable();
            $t->string('tax_office')->nullable();
            $t->string('tax_number')->nullable();
            $t->string('phone')->nullable();
            $t->string('mobile')->nullable();
            $t->string('email')->nullable();
            $t->text('address')->nullable();
            $t->string('google_map_url')->nullable();

            // WhatsApp FAB
            $t->boolean('show_whatsapp_fab')->default(false);
            $t->string('whatsapp')->nullable();

            // Sosyal
            $t->string('facebook')->nullable();
            $t->string('instagram')->nullable();
            $t->string('twitter')->nullable();
            $t->string('linkedin')->nullable();
            $t->string('youtube')->nullable();

            // Görseller (public disk)
            $t->string('logo_path')->nullable();
            $t->string('favicon_path')->nullable();
            $t->string('meta_image_path')->nullable();

            // JSON alanlar
            $t->json('bank_accounts')->nullable();

            // Footer
            $t->text('footer_description')->nullable();

            // Hakkımızda Galerisi (ayarlar)
            $t->boolean('about_gallery_enabled')->default(true);
            $t->unsignedTinyInteger('about_gallery_max_items')->default(12);
            $t->string('about_gallery_aspect_ratio', 16)->default('16:9');
            $t->text('about_gallery_note')->nullable();

            // Analitik / Kod
            $t->string('ga_measurement_id')->nullable();
            $t->string('gtm_id')->nullable();
            $t->string('meta_pixel_id')->nullable();
            $t->text('head_scripts')->nullable();
            $t->text('body_scripts')->nullable();

            $t->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('site_settings');
    }
};
