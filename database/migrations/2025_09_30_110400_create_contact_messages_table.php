<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $t) {
            $t->id();

            // Zorunlu
            $t->string('name', 120);
            $t->string('email', 160);
            $t->text('message');

            // Opsiyonel
            $t->string('phone', 40)->nullable();
            $t->string('company', 160)->nullable();

            // Bağlam / iz bilgileri
            $t->string('page_url', 255)->nullable();
            $t->string('ip', 45)->nullable();
            $t->string('user_agent', 255)->nullable();

            // KVKK/GDPR onayı
            $t->timestamp('consent_at')->nullable();

            // İş akışı
            $t->enum('status', ['new','contacted','spam'])->default('new');

            $t->timestamps();
            $t->softDeletes(); // ← eklendi

            // İndeksler
            $t->index(['status','created_at']);
            $t->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
