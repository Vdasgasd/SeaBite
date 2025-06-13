<?php

use Carbon\Traits\Timestamp;
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
            $table->enum('status_pesanan', ['antrian', 'diproses', 'selesai', 'dibayar', 'dibatalkan'])->default('antrian');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('total_harga', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
