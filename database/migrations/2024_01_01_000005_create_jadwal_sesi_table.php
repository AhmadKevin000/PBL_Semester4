<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_sesi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('durasi')->nullable();
            $table->integer('max_capacity')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_sesi');
    }
};
