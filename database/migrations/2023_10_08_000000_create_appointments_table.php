<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('start_at');
            $table->timestamp('end_at')->nullable();
            $table->string('status');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['start_at', 'end_at', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
