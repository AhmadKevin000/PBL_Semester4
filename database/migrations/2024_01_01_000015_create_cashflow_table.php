<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cashflow', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('expense');
            $table->string('kategori')->default('gaji_guru');
            $table->string('source_snapshot')->nullable();
            $table->unsignedBigInteger('sumber_id')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reference_type')->nullable();
            $table->timestamps();

            $table->index('reference_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cashflow');
    }
};
