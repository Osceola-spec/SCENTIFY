<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Matikan pengecekan Foreign Key sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('users')->truncate();

        // 1. Insert Admin User
        DB::table('users')->insert([
            [
                'username' => 'admin_scentify', // Maksimal 20 karakter & Unik
                'first_name' => 'Admin',
                'last_name' => 'Scentify',
                'email' => 'admin@scentify.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '0812-3456-7890',
                'bio' => 'Administrator Scentify Platform',
                'profile_picture' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 2. Insert Customer Users
        $customers = [
            [
                'username' => 'budis',
                'first_name' => 'Budi',
                'last_name' => 'Santoso',
                'email' => 'budi@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'phone' => '0811-1111-1111',
                'bio' => 'Pecinta parfum premium',
                'profile_picture' => null,
            ],
            [
                'username' => 'sitin',
                'first_name' => 'Siti',
                'last_name' => 'Nurhaliza',
                'email' => 'siti@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'phone' => '0812-2222-2222',
                'bio' => 'Collector parfum niche',
                'profile_picture' => null,
            ],
            [
                'username' => 'andiw',
                'first_name' => 'Andi',
                'last_name' => 'Wijaya',
                'email' => 'andi@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'phone' => '0813-3333-3333',
                'bio' => 'Penggemar parfum lokal',
                'profile_picture' => null,
            ],
            [
                'username' => 'dewil',
                'first_name' => 'Dewi',
                'last_name' => 'Lestari',
                'email' => 'dewi@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'phone' => '0814-4444-4444',
                'bio' => 'Beauty enthusiast',
                'profile_picture' => null,
            ],
            [
                'username' => 'rickyf',
                'first_name' => 'Ricky',
                'last_name' => 'Fernando',
                'email' => 'ricky@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'phone' => '0815-5555-5555',
                'bio' => 'Fragrance explorer',
                'profile_picture' => null,
            ],
        ];

        foreach ($customers as $customer) {
            DB::table('users')->insert(array_merge($customer, [
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Nyalakan kembali pengecekan Foreign Key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}