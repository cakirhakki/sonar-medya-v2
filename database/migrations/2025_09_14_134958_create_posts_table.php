<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('posts', function (Blueprint $t) {
            $t->id();

            // Temel
            $t->string('title');
            $t->string('slug')->unique();

            // Liste/grid için kısa özet
            $t->string('excerpt', 600)->nullable();

            // Detay gövdesi (WYSIWYG/HTML)
            $t->longText('content')->nullable();

            // Kapak görseli + metaveriler
            $t->string('featured_image_path', 512)->nullable();
            $t->string('featured_image_alt')->nullable();
            $t->string('featured_image_caption')->nullable();
            $t->string('featured_image_credit_text')->nullable();
            $t->string('featured_image_credit_url', 512)->nullable();

            // >>> blocks JSON KALDIRILDI <<<

            // Meta
            $t->timestamp('published_at')->nullable();
            $t->enum('status', ['draft','scheduled','published'])->default('draft');
            $t->unsignedBigInteger('views')->default(0)->index();
            $t->unsignedSmallInteger('reading_time')->nullable(); // dk

            // İlişkiler
            $t->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $t->foreignId('primary_post_category_id')->nullable()->constrained('post_categories')->nullOnDelete();

            // SEO
            $t->string('meta_title')->nullable();
            $t->string('meta_description', 300)->nullable();
            $t->string('meta_image', 512)->nullable();

            // Soft deletes & timestamps
            $t->softDeletes();
            $t->timestamps();

            // Sorgu performansı
            $t->index(['status', 'published_at']);

            // FULLTEXT
            if (Schema::getConnection()->getDriverName() === 'mysql') {
                $t->fullText(['title', 'excerpt', 'content']);
            }
        });
    }

    public function down(): void {
        Schema::dropIfExists('posts');
    }
};
