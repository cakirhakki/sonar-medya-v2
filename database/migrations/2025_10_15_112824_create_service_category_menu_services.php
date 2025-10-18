<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_category_menu_services', function (Blueprint $t) {
            $t->unsignedBigInteger('service_category_id');
            $t->unsignedBigInteger('service_id');
            $t->unsignedInteger('position')->default(0);

            // kısa birincil anahtar adı
            $t->primary(['service_category_id', 'service_id'], 'scms_pk');

            // kısa foreign key adları
            $t->foreign('service_category_id', 'scms_cat_fk')
              ->references('id')->on('service_categories')
              ->cascadeOnDelete();

            $t->foreign('service_id', 'scms_srv_fk')
              ->references('id')->on('services')
              ->cascadeOnDelete();

            // kısa index adı
            $t->index(['service_category_id', 'position'], 'scms_cat_pos_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_category_menu_services');
    }
};
