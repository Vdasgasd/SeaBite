<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ikan', function (Blueprint $table) {
            $table->id('ikan_id');
            $table->string('nama_ikan', 100)->unique();
            $table->decimal('stok_gram', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ikan');
    }
};
