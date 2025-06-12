<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservasi', function (Blueprint $table) {
            $table->id('reservasi_id');
            $table->foreignId('meja_id')->constrained('meja', 'meja_id')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('nama_pelanggan', 100);
            $table->string('telepon', 20);
            $table->dateTime('waktu_reservasi');
            $table->integer('jumlah_tamu');
            $table->enum('status', ['dikonfirmasi', 'hadir', 'batal'])->default('dikonfirmasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasi');
    }
};
