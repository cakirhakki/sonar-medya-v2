<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_categories', function (Blueprint $t) {
            $t->id();

            // Temel
            $t->string('name');
            $t->string('slug')->unique();

            // Açıklama
            $t->text('description')->nullable();

            // Vitrin
            $t->boolean('is_active')->default(true)->index();
            $t->boolean('show_in_menu')->default(false)->index();

            // 0: Hepsi, 1: Seçililer
            $t->unsignedTinyInteger('menu_mode')
              ->default(0)
              ->comment('0=Hepsi, 1=Seçililer');

            // Sıra
            $t->unsignedSmallInteger('display_order')->default(0)->index();

            $t->timestamps();
            $t->softDeletes();

            // Yardımcı indeksler
            $t->index(['is_active', 'display_order']);
            $t->index(['show_in_menu', 'display_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_categories');
    }
};
