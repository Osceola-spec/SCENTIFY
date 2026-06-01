<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BranchSeeder::class,
            ProductSeeder::class,
            ReviewAndOrderSeeder::class,
            RegionSeeder::class,
                // Tambahkan seeder lainnya di sini
                // Contoh: StoreSeeder::class,
            // Jika ada seeder lain seperti StoreSeeder, masukkan di sini
        ]);
    }
}
