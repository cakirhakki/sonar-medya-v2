<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_packages', function (Blueprint $t) {
            $t->id();

            $t->string('name');
            $t->string('slug')->unique();                 // zorunlu slug

            // Kategori ilişkisi (birleştirildi)
            $t->foreignId('package_category_id')
              ->nullable()
              ->constrained('package_categories')
              ->nullOnDelete();                          // kategori silinirse null

            $t->string('code', 64)->unique();            // teklif/paket numarası
            $t->text('description')->nullable();
            $t->string('short_description', 255)->nullable(); // özet

            // Para birimi
            $t->char('currency', 3)->default('TRY')->index(); // ISO 4217
            $t->decimal('currency_rate', 12, 6)->nullable();

            // Fiyat
            $t->decimal('override_price', 12, 2)->nullable();
            $t->boolean('show_price')->default(true);

            // Durum
            $t->enum('status', ['draft', 'sent', 'accepted', 'published'])
              ->default('draft')->index();

            // Durum tarihleri
            $t->timestamp('sent_at')->nullable();
            $t->timestamp('accepted_at')->nullable();
            $t->timestamp('published_at')->nullable();   // locked_at yok
            $t->timestamp('expires_at')->nullable(); 

            $t->softDeletes();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_packages');
    }
};
