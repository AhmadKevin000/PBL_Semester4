<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_kelas_id')->constrained('sesi_kelas')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->enum('status', ['hadir', 'tidak hadir', 'izin', 'alpha'])->default('hadir');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_siswa');
    }
};
