<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OpenAI\Laravel\Facades\OpenAI; // Tambahkan import ini
use Illuminate\Support\Facades\Log; // Untuk mencatat error jika API OpenAI gagal

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'brand_id',
        'name',
        'slug',
        'category',
        'gender_type',
        'description',
        'image_url',
        'is_new_arrival',
        'discount_percent',
        'search_context', // Tambahkan ini
        'embedding'       // Tambahkan ini
    ];

    // --- RELASI (Tetap seperti aslinya) ---

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function notes()
    {
        return $this->belongsToMany(ScentNote::class, 'product_notes');
    }

    // --- LOGIKA AI & VECTOR ---

    protected static function booted()
    {
        // Event ini dipanggil setiap kali produk dibuat atau diubah
        static::saved(function ($product) {
            
            // Kolom apa saja yang perubahannya penting buat dibaca AI?
            $relevantFields = ['name', 'category', 'gender_type', 'description'];
            
            // Cek jika ada perubahan data, atau jika vectornya memang masih kosong
            if ($product->isDirty($relevantFields) || empty($product->embedding)) {
                
                // 1. Susun kalimat konteks yang kaya untuk AI (disesuaikan dengan field parfum-mu)
                $context = "Produk Parfum: {$product->name}. Kategori: {$product->category}. Gender: {$product->gender_type}. Deskripsi: {$product->description}.";

                try {
                    // 2. Minta OpenAI mengubah teks jadi Vector
                    $response = OpenAI::embeddings()->create([
                        'model' => 'text-embedding-3-small',
                        'input' => $context,
                    ]);

                    $vector = $response->embeddings[0]->embedding;

                    // 3. Simpan ke database tanpa memicu event 'saved' lagi (agar tidak looping tak terbatas)
                    $product->updateQuietly([
                        'search_context' => $context,
                        'embedding' => json_encode($vector)
                    ]);
                    
                } catch (\Exception $e) {
                    // Jika API OpenAI sedang down/error, proses simpan data produk tetap berhasil 
                    // dan error-nya dicatat ke dalam log Laravel
                    Log::error("Gagal membuat vector untuk produk {$product->name}: " . $e->getMessage());
                }
            }
        });
    }

    /**
     * Mencari produk berdasarkan kemiripan vector (Cosine Similarity) di PHP
     */
    public static function searchByVector(array $userEmbedding, $limit = 5)
    {
        // 1. Ambil semua produk yang punya vector
        $products = self::whereNotNull('embedding')->get();

        // 2. Hitung jarak kemiripan
        $products->map(function ($product) use ($userEmbedding) {
            $productEmbedding = json_decode($product->embedding, true);
            $product->similarity_score = self::calculateCosineSimilarity($userEmbedding, $productEmbedding);
            return $product;
        });

        // 3. Urutkan dari yang paling mirip (skor tertinggi) lalu ambil sesuai limit
        return $products->sortByDesc('similarity_score')->take($limit)->values();
    }

    /**
     * Rumus Matematika Cosine Similarity
     */
    private static function calculateCosineSimilarity(array $vec1, array $vec2): float
    {
        $dotProduct = 0;
        $normA = 0;
        $normB = 0;
        $count = min(count($vec1), count($vec2));
        
        for ($i = 0; $i < $count; $i++) {
            $dotProduct += $vec1[$i] * $vec2[$i];
            $normA += $vec1[$i] ** 2;
            $normB += $vec2[$i] ** 2;
        }

        if ($normA == 0 || $normB == 0) return 0;
        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }

    // app/Models/Product.php — tambahkan relasi ini

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true)->orderBy('order');
    }

    // app/Models/Product.php — tambahkan

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }
}