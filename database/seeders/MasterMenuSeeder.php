<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Ikan;
use App\Models\Menu;
use Faker\Factory as Faker;

class MasterMenuSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void {
        $faker = Faker::create('id_ID'); // Menggunakan data Faker Indonesia

        // Kategori (tidak duplikat)
        $kategoriNames = ['Panggang (Grilled)', 'Goreng (Fried)', 'Kukus (Steam)', 'Sup (Soup)', 'Bakar'];
        $kategoriIds = [];
        foreach ($kategoriNames as $name) {
            // Menggunakan firstOrCreate untuk menghindari duplikat
            $kategori = Kategori::firstOrCreate(['nama_kategori' => $name]);
            $kategoriIds[] = $kategori->kategori_id;
        }

        // Ikan (tidak duplikat)
        $ikanNames = ['Tuna', 'Salmon', 'Kakap', 'Gurame', 'Lele', 'Patin', 'Bandeng', 'Kembung', 'Cumi', 'Udang', 'Kepiting', 'Kerang'];
        $ikanIds = [];
        foreach ($ikanNames as $name) {
            $ikan = Ikan::firstOrCreate(['nama_ikan' => $name]);
            $ikanIds[] = $ikan->ikan_id;
        }

        // Daftar menu dengan URL gambar yang telah diperbarui dan lebih relevan
        $menus = [
            ['name' => 'Salmon Panggang Lemon', 'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=800&q=80', 'kategori' => 'Panggang (Grilled)', 'ikan' => 'Salmon'],
            ['name' => 'Cumi Goreng Tepung Krispi', 'image' => 'https://images.unsplash.com/photo-1599599810694-b5b37304c047?w=800&q=80', 'kategori' => 'Goreng (Fried)', 'ikan' => 'Cumi'],
            ['name' => 'Lobster Bakar Keju', 'image' => 'https://images.unsplash.com/photo-1625944002334-9e0c7e5b5d84?w=800&q=80', 'kategori' => 'Bakar', 'ikan' => null],
            ['name' => 'Udang Goreng Bawang Putih', 'image' => 'https://images.unsplash.com/photo-1625634358646-3a1a6b328a88?w=800&q=80', 'kategori' => 'Goreng (Fried)', 'ikan' => 'Udang'],
            ['name' => 'Paella Seafood Spesial', 'image' => 'https://images.unsplash.com/photo-1579532537598-459ecdaf39cc?w=800&q=80', 'kategori' => 'Panggang (Grilled)', 'ikan' => null],
            ['name' => 'Tuna Steak Bakar', 'image' => 'https://images.unsplash.com/photo-1633940113821-23760420755a?w=800&q=80', 'kategori' => 'Bakar', 'ikan' => 'Tuna'],
            ['name' => 'Gurame Bakar Sambal Matah', 'image' => 'https://plus.unsplash.com/premium_photo-1673582483196-a9c354786134?w=800&q=80', 'kategori' => 'Bakar', 'ikan' => 'Gurame'],
            ['name' => 'Kepiting Saus Padang', 'image' => 'https://images.unsplash.com/photo-1582570648037-2753e1987556?w=800&q=80', 'kategori' => 'Sup (Soup)', 'ikan' => 'Kepiting'],
            ['name' => 'Kerang Kukus Jahe', 'image' => 'https://images.unsplash.com/photo-1603056122699-2789a785984d?w=800&q=80', 'kategori' => 'Kukus (Steam)', 'ikan' => 'Kerang'],
            ['name' => 'Sup Ikan Kakap', 'image' => 'https://images.unsplash.com/photo-1574484284002-968d9224d3e8?w=800&q=80', 'kategori' => 'Sup (Soup)', 'ikan' => 'Kakap'],
        ];

        foreach ($menus as $item) {
            $tipeHarga = $faker->randomElement(['satuan', 'berat']);

            // Cari ID kategori berdasarkan nama
            $kategori = Kategori::where('nama_kategori', $item['kategori'])->first();

            // Cari ID ikan berdasarkan nama, jika ada
            $ikan = $item['ikan'] ? Ikan::where('nama_ikan', $item['ikan'])->first() : null;

            Menu::create([
                'nama_menu' => $item['name'],
                'deskripsi' => $faker->sentence(15),
                'kategori_id' => $kategori->kategori_id,
                'ikan_id' => $ikan ? $ikan->ikan_id : null,
                'tipe_harga' => $tipeHarga,
                'harga' => $tipeHarga === 'satuan' ? $faker->numberBetween(5, 15) * 5000 : null, // Harga satuan kelipatan 5000
                'harga_per_100gr' => $tipeHarga === 'berat' ? $faker->numberBetween(2, 7) * 5000 : null, // Harga berat kelipatan 5000
                'gambar_url' => $item['image'] . '&auto=format&fit=crop', // Menambahkan parameter Unsplash
            ]);
        }
    }
}
