<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id('menu_id');
            $table->string('nama_menu', 100);
            $table->text('deskripsi')->nullable();

            $table->foreignId('kategori_id')->constrained('kategori', 'kategori_id')->restrictOnDelete()->cascadeOnUpdate();

            $table->foreignId('ikan_id')->nullable()->constrained('ikan', 'ikan_id')->nullOnDelete()->cascadeOnUpdate();

            $table->enum('tipe_harga', ['satuan', 'berat']);
            $table->decimal('harga', 10, 2)->nullable();
            $table->decimal('harga_per_100gr', 10, 2)->nullable();
            $table->string('gambar_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
