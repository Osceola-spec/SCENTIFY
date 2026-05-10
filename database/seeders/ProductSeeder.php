<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Matikan pengecekan Foreign Key sementara agar aman saat truncate/insert
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('product_notes')->truncate();
        DB::table('product_variants')->truncate();
        DB::table('products')->truncate();
        DB::table('scent_notes')->truncate();
        DB::table('brands')->truncate();

        // 2. Insert Brands
        $brands = [
            'Chanel', 'Maison Francis Kurkdjian', 'HMNS', 'Le Labo', 
            'Armani', 'Dior', 'Carl & Claire', 'Byredo'
        ];
        
        $brandIds = [];
        foreach ($brands as $brand) {
            $brandIds[$brand] = DB::table('brands')->insertGetId([
                'name' => $brand,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Insert Scent Notes
        $notes = ['Woody', 'Citrus', 'Sweet', 'Floral', 'Spicy', 'Leather', 'Aquatic', 'Fresh', 'Tobacco'];
        $noteIds = [];
        foreach ($notes as $note) {
            $noteIds[$note] = DB::table('scent_notes')->insertGetId([
                'name' => $note,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Data Dummy Produk (Berdasarkan Mockup Scentify)
        $productsData = [
            [
                'name' => 'Bleu Ethereal', 'brand' => 'Chanel', 'category' => 'Designer', 'type' => 'Men', 
                'price' => 2850000, 'image' => 'https://images.unsplash.com/photo-1523293182086-7651a899d37f?auto=format&fit=crop&q=80&w=400', 
                'notes' => ['Woody', 'Citrus'], 'is_new' => false, 'discount' => 0
            ],
            [
                'name' => 'Rouge Elixir 54', 'brand' => 'Maison Francis Kurkdjian', 'category' => 'Niche', 'type' => 'Unisex', 
                'price' => 5500000, 'image' => 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&q=80&w=400', 
                'notes' => ['Sweet', 'Woody'], 'is_new' => true, 'discount' => 0
            ],
            [
                'name' => 'Midnight Senopati', 'brand' => 'HMNS', 'category' => 'Local', 'type' => 'Unisex', 
                'price' => 385000, 'image' => 'https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?auto=format&fit=crop&q=80&w=400', 
                'notes' => ['Floral', 'Spicy'], 'is_new' => false, 'discount' => 15
            ],
            [
                'name' => 'Santal Enigma', 'brand' => 'Le Labo', 'category' => 'Niche', 'type' => 'Unisex', 
                'price' => 4200000, 'image' => 'https://images.unsplash.com/photo-1615397323136-1e0f074d3da9?auto=format&fit=crop&q=80&w=400', 
                'notes' => ['Woody', 'Leather'], 'is_new' => false, 'discount' => 0
            ],
            [
                'name' => 'Aqua di Profondo', 'brand' => 'Armani', 'category' => 'Designer', 'type' => 'Men', 
                'price' => 2100000, 'image' => 'https://images.unsplash.com/photo-1595532542520-50d220b30d31?auto=format&fit=crop&q=80&w=400', 
                'notes' => ['Aquatic', 'Fresh'], 'is_new' => false, 'discount' => 0
            ],
            [
                'name' => 'Jasmine Blooms', 'brand' => 'Dior', 'category' => 'Designer', 'type' => 'Women', 
                'price' => 2600000, 'image' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&q=80&w=400', 
                'notes' => ['Floral', 'Fresh'], 'is_new' => false, 'discount' => 0
            ],
            [
                'name' => 'Oud Batavia', 'brand' => 'Carl & Claire', 'category' => 'Local', 'type' => 'Men', 
                'price' => 299000, 'image' => 'https://images.unsplash.com/photo-1592914610354-fd354d45e5b0?auto=format&fit=crop&q=80&w=400', 
                'notes' => ['Woody', 'Tobacco'], 'is_new' => true, 'discount' => 10
            ],
            [
                'name' => 'Gypsy Soul', 'brand' => 'Byredo', 'category' => 'Niche', 'type' => 'Women', 
                'price' => 3900000, 'image' => 'https://images.unsplash.com/photo-1590156156108-9ba249f07897?auto=format&fit=crop&q=80&w=400', 
                'notes' => ['Fresh', 'Citrus'], 'is_new' => false, 'discount' => 0
            ],
        ];

        // 5. Insert Products, Variants, dan Scent Notes (Pivot)
        foreach ($productsData as $data) {
            // Insert Product
            $productId = DB::table('products')->insertGetId([
                'brand_id' => $brandIds[$data['brand']],
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'category' => $data['category'],
                'gender_type' => $data['type'],
                'description' => 'Parfum eksklusif dengan karakter unik. Cocok digunakan sehari-hari maupun acara formal.',
                'image_url' => $data['image'],
                'is_new_arrival' => $data['is_new'],
                'discount_percent' => $data['discount'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert Product Variants (50ml & 100ml)
            DB::table('product_variants')->insert([
                [
                    'product_id' => $productId,
                    'size' => '50ml',
                    'price' => $data['price'],
                    'stock' => rand(10, 50),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'product_id' => $productId,
                    'size' => '100ml',
                    'price' => $data['price'] * 1.8, // Ukuran 100ml harganya 1.8x lipat
                    'stock' => rand(5, 20),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);

            // Insert Pivot Scent Notes
            foreach ($data['notes'] as $note) {
                DB::table('product_notes')->insert([
                    'product_id' => $productId,
                    'scent_note_id' => $noteIds[$note],
                ]);
            }
        }

        // Nyalakan kembali pengecekan Foreign Key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
