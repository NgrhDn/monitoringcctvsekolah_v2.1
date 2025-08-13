<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSekolahTable extends Migration
{
    public function up(): void
    {
        Schema::create('sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah');
            $table->unsignedBigInteger('wilayah_id')->nullable();
            $table->timestamps();

            $table->foreign('wilayah_id')->references('id')->on('wilayah')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sekolah');
    }
}
