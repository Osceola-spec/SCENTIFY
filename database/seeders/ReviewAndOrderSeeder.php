<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReviewAndOrderSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('reviews')->truncate();
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $users    = DB::table('users')->get();
        $products = DB::table('products')->get();
        $variants = DB::table('product_variants')->get();

        if ($users->isEmpty() || $products->isEmpty() || $variants->isEmpty()) {
            $this->command->warn('Data users/products/variants kosong. Jalankan UserSeeder dan ProductSeeder dulu.');
            return;
        }

        $statuses = ['Pending', 'Paid', 'Shipped', 'Completed', 'Cancelled'];

        $reviewTitles = [
            'Sangat puas dengan produk ini!',
            'Wanginya tahan lama dan elegan',
            'Rekomendasi banget, worth it!',
            'Packaging mewah, parfum juara',
            'Aroma unik, bikin percaya diri',
            'Sesuai ekspektasi, mantap!',
            'Pengiriman cepat, produk original',
            'Cocok untuk acara formal',
        ];

        $reviewComments = [
            'Parfum ini benar-benar luar biasa. Wanginya tahan lebih dari 8 jam dan selalu dapat pujian dari orang sekitar.',
            'Sudah beli kedua kalinya karena memang kualitasnya tidak mengecewakan. Highly recommended!',
            'Aroma top note-nya segar, kemudian dry down ke base note yang hangat dan sensual. Sempurna!',
            'Packaging sangat premium, cocok banget dijadikan hadiah. Penerimanya pasti senang.',
            'Saya sudah coba banyak parfum lokal, tapi ini yang paling tahan lama dan proyeksinya bagus.',
            'Wanginya persis seperti deskripsi. Tidak terlalu berat, cocok untuk daily wear.',
            'Harga sangat sebanding dengan kualitas. Akan beli lagi varian lainnya.',
            'Respon penjual cepat, barang sampai dalam kondisi sempurna dan tersegel rapi.',
        ];

        $cities = [
            'Jakarta Selatan 12345', 'Surabaya 60271', 'Bandung 40123',
            'Yogyakarta 55111', 'Medan 20111', 'Semarang 50131',
        ];

        $now = Carbon::now();

        foreach ($users as $user) {
            $orderCount = rand(2, 4);

            for ($o = 0; $o < $orderCount; $o++) {
                $status      = $statuses[array_rand($statuses)];
                $orderDate   = $now->copy()->subDays(rand(1, 120));
                $city        = $cities[array_rand($cities)];
                $shippingAddr = "{$user->name} | 08" . rand(100000000, 999999999) . " | Jl. Contoh No.{$o}, {$city}";

                $selectedVariants = $variants->random(rand(1, 3));
                $subtotal = 0;
                foreach ($selectedVariants as $v) {
                    $subtotal += $v->price * rand(1, 2);
                }
                $tax   = $subtotal * 0.11;
                $total = $subtotal + $tax + 50000;

                $orderId = DB::table('orders')->insertGetId([
                    'user_id'          => $user->id,
                    'order_number'     => 'ORD-' . strtoupper(Str::random(8)),
                    'subtotal'         => $subtotal,
                    'tax_amount'       => $tax,
                    'total_amount'     => $total,
                    'status'           => $status,
                    'shipping_address' => $shippingAddr,
                    'created_at'       => $orderDate,
                    'updated_at'       => $orderDate,
                ]);

                $orderItemIds = [];
                foreach ($selectedVariants as $v) {
                    $itemId = DB::table('order_items')->insertGetId([
                        'order_id'           => $orderId,
                        'product_variant_id' => $v->id,
                        'quantity'           => rand(1, 2),
                        'price_at_purchase'  => $v->price,
                        'created_at'         => $orderDate,
                        'updated_at'         => $orderDate,
                    ]);
                    $orderItemIds[] = [
                        'item_id'    => $itemId,
                        'product_id' => $v->product_id,
                    ];
                }

                // Review hanya untuk order Completed, 80% chance
                if ($status === 'Completed') {
                    foreach ($orderItemIds as $itemData) {
                        if (rand(1, 10) <= 8) {
                            $exists = DB::table('reviews')
                                ->where('user_id', $user->id)
                                ->where('order_item_id', $itemData['item_id'])
                                ->exists();

                            if (!$exists) {
                                DB::table('reviews')->insert([
                                    'user_id'       => $user->id,
                                    'product_id'    => $itemData['product_id'],
                                    'order_id'      => $orderId,
                                    'order_item_id' => $itemData['item_id'],
                                    'rating'        => rand(3, 5),
                                    'title'         => $reviewTitles[array_rand($reviewTitles)],
                                    'comment'       => $reviewComments[array_rand($reviewComments)],
                                    'created_at'    => $orderDate->copy()->addDays(rand(1, 7)),
                                    'updated_at'    => $orderDate->copy()->addDays(rand(1, 7)),
                                ]);
                            }
                        }
                    }
                }
            }
        }

        $this->command->info('Seeder selesai:');
        $this->command->info('  Orders  : ' . DB::table('orders')->count());
        $this->command->info('  Items   : ' . DB::table('order_items')->count());
        $this->command->info('  Reviews : ' . DB::table('reviews')->count());
    }
}