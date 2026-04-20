<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarif_gaji', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenjang_id')->constrained('jenjang')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('program')->onDelete('cascade');
            $table->enum('status_guru', ['baru', 'semi', 'senior', 'all'])->default('all');
            $table->integer('jumlah_siswa_min')->default(1);
            $table->decimal('rate', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarif_gaji');
    }
};
