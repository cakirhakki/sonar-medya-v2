<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            $table->string('name');                              // Hizmet adı
            $table->text('summary')->nullable();                 // Kısa açıklama
            $table->unsignedSmallInteger('duration_minutes')
                  ->default(30);                                 // Süre (dk)
            $table->decimal('price', 10, 2)->default(0);         // TL olarak fiyat (örn. 199.90)
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
