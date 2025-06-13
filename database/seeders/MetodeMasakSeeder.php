<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MetodeMasak;

class MetodeMasakSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_metode' => 'Goreng',
                'biaya_tambahan' => 5000.00
            ],
            [
                'nama_metode' => 'Bakar',
                'biaya_tambahan' => 7000.00
            ],
            [
                'nama_metode' => 'Rebus',
                'biaya_tambahan' => 3000.00
            ],
            [
                'nama_metode' => 'Panggang',
                'biaya_tambahan' => 8000.00
            ],
        ];

        foreach ($data as $item) {
            MetodeMasak::create($item);
        }
    }
}
