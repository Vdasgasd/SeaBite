<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('pesanan_id');
            $table->foreignId('meja_id')->constrained('meja', 'meja_id')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamp('waktu_pesanan')->useCurrent();
            $table->enum('status_pesanan', ['baru', 'dimasak', 'selesai', 'dibayar', 'dibatalkan'])->default('baru');
            $table->decimal('total_harga', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
