<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // Profil
            $table->string('name');
            $table->string('email')->unique();

            // Profil fotoğrafı
            $table->string('avatar_path')->nullable(); // SQLite ile uyumlu, AFTER kullanma

            // Auth
            $table->string('password')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();

            // İletişim & ek bilgiler
            $table->string('phone')->nullable()->unique(); // istersen unique
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('address')->nullable();

            // Sadakat & tercih
            $table->integer('loyalty_points')->default(0);
            $table->boolean('receive_newsletters')->default(false);

            // Yönetim
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
