<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meja', function (Blueprint $table) {
            $table->id('meja_id');
            $table->string('nomor_meja', 10)->unique();
            $table->integer('kapasitas')->default(1);
            $table->enum('status', ['tersedia', 'terisi', 'direservasi'])->default('tersedia');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meja');
    }
};
