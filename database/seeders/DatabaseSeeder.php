<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\MasterMenuSeeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        // User::factory(10)->create();

        DB::table('users')->insert([
            'name' => 'Admin',
            'username' => 'Admin',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        DB::table('users')->insert([
            'name' => 'Kitchen',
            'username' => 'Kitchen',
            'role' => 'kitchen',
            'password' => Hash::make('password'),
        ]);

        DB::table('users')->insert([
            'name' => 'Kasir',
            'username' => 'Kasir',
            'role' => 'kasir',
            'password' => Hash::make('password'),
        ]);

        DB::table('users')->insert([
            'name' => 'Cust',
            'username' => 'Cust',
            'role' => 'cust',
            'password' => Hash::make('password'),
        ]);


        $this->call([
            MasterMenuSeeder::class,
            MejaSeeder::class,
            MetodeMasakSeeder::class,
        ]);
    }
}
