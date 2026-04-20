<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi_guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_kelas_id')->constrained('sesi_kelas')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('guru')->onDelete('cascade');
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
            $table->foreignId('mapel_id')->nullable()->constrained('mapel')->onDelete('set null');
            
            $table->dateTime('check_in')->nullable();
            $table->dateTime('check_out')->nullable();
            $table->enum('status', ['ongoing', 'selesai'])->default('ongoing');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_guru');
    }
};
