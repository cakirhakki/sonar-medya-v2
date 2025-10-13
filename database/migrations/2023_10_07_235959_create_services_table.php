<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('services', function (Blueprint $t) {
            $t->id();

            // Kategori (opsiyonel)
            $t->foreignId('service_category_id')
                ->nullable()
                ->constrained('service_categories')
                ->nullOnDelete();

            // İçerik (minimum)
            $t->string('name');
            $t->string('slug')->unique();
            $t->text('excerpt')->nullable();
            $t->longText('description')->nullable();

            // Vitrin (minimum)
            $t->boolean('is_active')->default(true)->index();
            $t->boolean('is_featured')->default(false)->index();
            $t->unsignedSmallInteger('display_order')->default(0)->index();

            // Süre / Fiyat (minimum)
            $t->enum('unit', ['adet', 'saat', 'gün'])->nullable();
            $t->decimal('base_price', 12, 2)->nullable();
            $t->decimal('setup_fee', 12, 2)->nullable();
            $t->unsignedTinyInteger('tax_rate_percent')->nullable(); // 0-100
            $t->unsignedSmallInteger('duration_minutes')->nullable();

            $t->softDeletes();
            $t->timestamps();

            $t->index(['service_category_id', 'is_active', 'display_order']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('services');
    }
};
