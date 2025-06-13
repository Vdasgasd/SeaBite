<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MejaSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['tersedia', 'terisi', 'direservasi'];

        for ($i = 1; $i <= 10; $i++) {
            DB::table('meja')->insert([
                'nomor_meja' => 'M' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'kapasitas' => rand(2, 6),
                'status' => $statuses[array_rand($statuses)],
            ]);
        }
    }
}
