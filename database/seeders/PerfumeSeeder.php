<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ScentNote;
use Illuminate\Support\Str;

class PerfumeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Define Scent Notes
        $notes = [
            'Citrus', 'Woody', 'Amber', 'Musk', 'Vanilla', 'Floral', 'Fresh Spicy', 'Aromatic', 'Fruity', 'Leather'
        ];
        foreach ($notes as $note) {
            ScentNote::firstOrCreate(['name' => $note]);
        }

        // 2. Define Brands
        $brandsData = [
            ['name' => 'Louis Vuitton'],
            ['name' => 'Xerjoff'],
            ['name' => 'HMNS'],
            ['name' => 'Mykonos'],
            ['name' => 'Dior'],
            ['name' => 'Chanel'],
            ['name' => 'Giorgio Armani'],
            ['name' => 'Creed']
        ];

        foreach ($brandsData as $brandData) {
            Brand::firstOrCreate(['name' => $brandData['name']]);
        }

        // 3. Define Products
        $products = [
            [
                'brand_name' => 'Louis Vuitton',
                'name' => 'Imagination',
                'category' => 'Designer',
                'gender_type' => 'Men',
                'description' => 'A magical key that helps you achieve the boldest dreams. Ambroxan, the white gold of perfumery, reinvented in a contemporary way.',
                'image_url' => 'hero-perfumes/lv_imagination.jpg',
                'price' => 5200000,
                'notes' => ['Citrus', 'Amber', 'Fresh Spicy']
            ],
            [
                'brand_name' => 'Xerjoff',
                'name' => 'Naxos',
                'category' => 'Niche',
                'gender_type' => 'Unisex',
                'description' => 'A tribute to the grandeur and glamour of Sicily. Naxos blends classical, light and fruity citrus top notes with precious scents ranging from exotic notes to cinnamon and sweet vanilla and honey.',
                'image_url' => 'hero-perfumes/xerjoff_naxos.jpg',
                'price' => 4500000,
                'notes' => ['Citrus', 'Vanilla', 'Fresh Spicy']
            ],
            [
                'brand_name' => 'HMNS',
                'name' => 'Orgasm',
                'category' => 'Local',
                'gender_type' => 'Women',
                'description' => 'The best selling local premium perfume in Indonesia. An addictive blend of red apple, rose, jasmine and vanilla.',
                'image_url' => 'hero-perfumes/hmns_orgasm.jpg',
                'price' => 325000,
                'notes' => ['Fruity', 'Floral', 'Vanilla']
            ],
            [
                'brand_name' => 'Mykonos',
                'name' => 'Vanilla Clouds',
                'category' => 'Local',
                'gender_type' => 'Women',
                'description' => 'A beautiful fluffy, airy, creamy vanilla scent that transports you to a dream. Warm, inviting and deliciously gourmand.',
                'image_url' => 'hero-perfumes/mykonos.jpg',
                'price' => 285000,
                'notes' => ['Vanilla', 'Musk']
            ],
            [
                'brand_name' => 'Dior',
                'name' => 'Sauvage',
                'category' => 'Designer',
                'gender_type' => 'Men',
                'description' => 'A radically fresh composition, dictated by a name that has the ring of a manifesto. Raw and noble all at once.',
                'image_url' => 'hero-perfumes/dior_sauvage.jpg',
                'price' => 2800000,
                'notes' => ['Fresh Spicy', 'Amber', 'Woody']
            ],
            [
                'brand_name' => 'Chanel',
                'name' => 'Bleu de Chanel',
                'category' => 'Designer',
                'gender_type' => 'Men',
                'description' => 'The essence of independence and determination. A timeless, anti-conformist fragrance housed in an enigmatic blue bottle.',
                'image_url' => 'hero-perfumes/bleu_de_chanel.jpg',
                'price' => 2950000,
                'notes' => ['Woody', 'Citrus', 'Aromatic']
            ],
            [
                'brand_name' => 'Giorgio Armani',
                'name' => 'Acqua Di Gio',
                'category' => 'Designer',
                'gender_type' => 'Men',
                'description' => 'A mythic, fresh and aquatic fragrance that captures the full power of the sea in an iconic glass bottle.',
                'image_url' => 'hero-perfumes/acqua_di_gio.jpg',
                'price' => 2100000,
                'notes' => ['Citrus', 'Aromatic', 'Woody']
            ],
            [
                'brand_name' => 'Creed',
                'name' => 'Aventus',
                'category' => 'Niche',
                'gender_type' => 'Men',
                'description' => 'The bestselling men\'s fragrance in the history of the House of Creed. Aventus celebrates strength, power, success and heritage.',
                'image_url' => 'hero-perfumes/creed_aventus.jpg',
                'price' => 5800000,
                'notes' => ['Fruity', 'Woody', 'Leather']
            ]
        ];

        foreach ($products as $data) {
            $brand = Brand::where('name', $data['brand_name'])->first();

            if ($brand) {
                // Prevent duplicate products
                $product = Product::firstOrCreate(
                    ['name' => $data['name'], 'brand_id' => $brand->id],
                    [
                        'slug' => Str::slug($data['name']) . '-' . time(),
                        'category' => $data['category'],
                        'gender_type' => $data['gender_type'],
                        'description' => $data['description'],
                        'image_url' => $data['image_url'],
                        'is_new_arrival' => true,
                        'discount_percent' => 0,
                    ]
                );

                if ($product->wasRecentlyCreated) {
                    // Create default variant 100ml
                    $product->variants()->create([
                        'size' => '100',
                        'price' => $data['price'],
                        'stock' => 50,
                    ]);

                    // Attach notes
                    $noteIds = ScentNote::whereIn('name', $data['notes'])->pluck('id');
                    $product->notes()->sync($noteIds);
                }
            }
        }
    }
}
