<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('harga_berat_tiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menu', 'menu_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('min_gram', 10, 2);
            $table->decimal('max_gram', 10, 2);
            $table->decimal('harga', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harga_berat_tiers');
    }
};
