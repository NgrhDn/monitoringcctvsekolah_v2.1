<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePanoramaTable extends Migration
{
    public function up(): void
    {
        Schema::create('panorama', function (Blueprint $table) {
            $table->id();
            $table->string('namaWilayah');
            $table->string('namaTitik');
            $table->string('link');
            $table->enum('status', ['offline', 'online'])->default('offline');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panorama');
    }
}
