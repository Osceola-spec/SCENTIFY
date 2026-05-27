<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    public function run()
    {
        $branches = [
            [
                'name' => 'Scentify Central Mall',
                'address' => 'Jl. Thamrin No. 10, SCentral Mall Lantai 1',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '10210',
                'phone' => '+62 21 555 0101',
                'email' => 'central@scentify.co',
                'latitude' => -6.200000,
                'longitude' => 106.816666,
                'opening_hours' => "Senin - Jumat: 10:00 - 21:00\nSabtu - Minggu: 10:00 - 22:00",
                'image_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Scentify Bandung',
                'address' => 'Jl. Braga No. 45',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'postal_code' => '40111',
                'phone' => '+62 22 555 0202',
                'email' => 'bandung@scentify.co',
                'latitude' => -6.917464,
                'longitude' => 107.619123,
                'opening_hours' => "Setiap Hari: 10:00 - 21:00",
                'image_url' => null,
                'is_active' => true,
            ],
        ];

        foreach ($branches as $b) {
            Branch::create($b);
        }
    }
}
