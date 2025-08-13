<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // bigint UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('phone')->nullable();
            $table->string('location')->nullable();
            $table->string('about_me')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->enum('role', ['admin', 'pengguna'])->default('pengguna');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
