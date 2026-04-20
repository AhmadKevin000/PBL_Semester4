<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penggajian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('absensi_guru_id')->constrained('absensi_guru')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('guru')->onDelete('cascade');
            $table->foreignId('tarif_gaji_id')->nullable()->constrained('tarif_gaji')->onDelete('set null');
            
            $table->decimal('nominal', 15, 2)->default(0.00);
            $table->decimal('rate_snapshot', 12, 2)->nullable();
            $table->string('status_pembayaran')->default('paid'); // 'pending', 'paid', 'cancelled'
            $table->dateTime('tanggal_pembayaran')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penggajian');
    }
};
