<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('service_faqs', function (Blueprint $t) {
            $t->id();

            // Polimorfik ilişki: faqable_type (string) + faqable_id (bigint)
            $t->nullableMorphs('faqable'); // faqable_id, faqable_type + index

            $t->string('question');
            $t->text('answer')->nullable();
            $t->unsignedSmallInteger('sort_order')->default(0)->index();

            $t->timestamps();

            // Sorgu performansı için yardımcı indeks
            $t->index(['faqable_type', 'faqable_id', 'sort_order']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('service_faqs');
    }
};
