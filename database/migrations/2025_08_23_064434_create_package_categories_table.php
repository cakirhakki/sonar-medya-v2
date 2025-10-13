<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('package_categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('image_path')->nullable();       // 128x128 (1:1) görsel yolu
            $t->string('image_alt')->nullable();        // erişilebilirlik/SEO
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort')->default(0);
            $t->softDeletes();
            $t->timestamps();

            $t->index(['is_active', 'sort']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_categories');
    }
};
