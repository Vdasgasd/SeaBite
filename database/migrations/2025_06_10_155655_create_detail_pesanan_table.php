<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id('detail_id');

            $table->foreignId('pesanan_id')->constrained('pesanan', 'pesanan_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('menu_id')->constrained('menu', 'menu_id')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('metode_masak_id')->nullable()->constrained('metode_masak', 'metode_id')->nullOnDelete()->cascadeOnUpdate();

            $table->integer('jumlah')->nullable();
            $table->decimal('berat_gram', 10, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->decimal('subtotal', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
