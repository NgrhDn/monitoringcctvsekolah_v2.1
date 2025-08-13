<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cctvs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sekolah_id')->nullable();
            $table->unsignedBigInteger('wilayah_id')->nullable();
            $table->string('nama_titik');
            $table->string('link_stream', 500)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            // Foreign key
            $table->foreign('sekolah_id')->references('id')->on('sekolah')->onDelete('cascade');
            $table->foreign('wilayah_id')->references('id')->on('wilayah')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cctvs');
    }
};
