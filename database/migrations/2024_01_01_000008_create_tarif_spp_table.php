<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarif_spp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenjang_id')->constrained('jenjang')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('program')->onDelete('cascade');

            $table->decimal('nominal', 15, 2);
            $table->enum('type', ['flat', 'per_mapel'])->default('flat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarif_spp');
    }
};
