<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->id('invoice_id');

            $table->foreignId('pesanan_id')->unique()->constrained('pesanan', 'pesanan_id')->restrictOnDelete()->cascadeOnUpdate();

            $table->foreignId('kasir_id')->nullable()->constrained('users', 'id')->nullOnDelete()->cascadeOnUpdate();

            $table->timestamp('waktu_pembayaran')->useCurrent();
            $table->string('metode_pembayaran');
            $table->decimal('total_bayar', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};
