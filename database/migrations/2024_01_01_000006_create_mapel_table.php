<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mapel', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->foreignId('jenjang_id')->nullable()->constrained('jenjang')->onDelete('cascade');
            $table->foreignId('program_id')->nullable()->constrained('program')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mapel');
    }
};
