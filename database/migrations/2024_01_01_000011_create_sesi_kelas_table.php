<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesi_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_sesi_id')->constrained('jadwal_sesi')->onDelete('cascade');
            $table->foreignId('pengajaran_id')->constrained('pengajaran')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('semester');
            $table->timestamps();

            $table->unique(['jadwal_sesi_id', 'pengajaran_id', 'tanggal'], 'sesi_pengajaran_tanggal_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesi_kelas');
    }
};
