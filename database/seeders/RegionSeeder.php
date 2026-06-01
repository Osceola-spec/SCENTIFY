<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    public function run()
    {
        // Ambil API Key dari .env Anda
        $apiKey = env('RAJAONGKIR_API_KEY');
        $baseUrl = 'https://rajaongkir.komerce.id/api/v1/destination/';

        $this->command->info('Mengambil data Provinsi dari Komerce API...');
        
        // Request ke endpoint provinsi Komerce
        $responseProv = Http::withHeaders(['key' => $apiKey])
            ->timeout(60)
            ->retry(3, 2000)
            ->withoutVerifying()
            ->get($baseUrl . 'province');

        if ($responseProv->successful()) {
            // Format JSON Komerce dibungkus dalam properti 'data'
            $provinces = $responseProv->json('data'); 
            
            foreach ($provinces as $prov) {
                // Simpan Provinsi
                DB::table('provinces')->insertOrIgnore([
                    'id'   => $prov['id'],   // Komerce menggunakan 'id', bukan 'province_id'
                    'name' => $prov['name'] // Komerce menggunakan 'name', bukan 'province'
                ]);

                $this->command->info("Mengambil data Kota untuk Provinsi: {$prov['name']}...");
                
                // Request data Kota berdasarkan ID Provinsi saat ini
                $responseCity = Http::withHeaders(['key' => $apiKey])
                    ->timeout(60)
                    ->retry(3, 2000)
                    ->withoutVerifying()
                    ->get($baseUrl . 'city/' . $prov['id']);

                if ($responseCity->successful()) {
                    $cities = $responseCity->json('data');
                    foreach ($cities as $city) {
                        DB::table('cities')->insertOrIgnore([
                            'id'          => $city['id'], // Komerce menggunakan 'id', bukan 'city_id'
                            'province_id' => $prov['id'],
                            'name'        => $city['name'], // Komerce menggunakan 'name', bukan 'city_name'
                            // Catatan: Endpoint city/ Komerce hanya mengembalikan id dan name.
                            // Kolom seperti tipe kota atau kode pos tidak tersedia di endpoint ini 
                            // karena kode pos spesifik ada di level kecamatan/kelurahan.
                        ]);
                    }
                }
            }
            $this->command->info('🎉 Sinkronisasi Provinsi dan Kota dari Komerce selesai!');
        } else {
            $this->command->error('❌ Gagal mengambil data Provinsi. Status: ' . $responseProv->status());
        }
    }
}