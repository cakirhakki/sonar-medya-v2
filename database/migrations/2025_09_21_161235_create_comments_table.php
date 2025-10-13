<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            // Polymorphic hedef
            $table->morphs('commentable'); // commentable_type, commentable_id

            // İlişkiler
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();       // users.id
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();   // customers.id

            // Misafir bilgileri
            $table->string('author_name')->nullable();
            $table->string('author_email')->nullable();

            // İçerik
            $table->text('content');

            // Threading
            $table->foreignId('parent_id')->nullable()->constrained('comments')->cascadeOnDelete();

            // Durum
            $table->string('status')->default('pending')->index(); // pending|approved|spam

            // İz bilgisi
            $table->ipAddress('ip')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
