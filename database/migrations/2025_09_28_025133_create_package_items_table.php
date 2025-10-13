<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('package_items', function (Blueprint $t) {
            $t->id();

            // İlişkiler
            $t->foreignId('service_package_id')
              ->constrained('service_packages')
              ->cascadeOnDelete();

            $t->foreignId('parent_item_id')
              ->nullable()
              ->constrained('package_items')
              ->nullOnDelete();

            $t->foreignId('service_id')
              ->nullable()
              ->constrained('services')
              ->nullOnDelete();

            // Tür
            $t->enum('type', ['service', 'custom'])->default('service')->index();

            // Snapshot alanları
            $t->string('name');
            $t->text('description')->nullable();

            // Görsel
            $t->string('image_path')->nullable();     // public disk path
            $t->string('image_alt', 255)->nullable();

            $t->string('unit', 32)->nullable();       // adet/saat/gün
            $t->decimal('qty', 12, 3)->default(1);
            $t->decimal('unit_price', 12, 2)->default(0);

            $t->decimal('discount_amount', 12, 2)->default(0);
            $t->unsignedTinyInteger('tax_rate_percent')->nullable(); // 0–100

            $t->json('snapshot_json')->nullable();    // ekstra meta

            // Sıralama
            $t->unsignedSmallInteger('sort_order')->default(0)->index();

            $t->timestamps();

            // Yardımcı indeks
            $t->index(['service_package_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_items');
    }
};
