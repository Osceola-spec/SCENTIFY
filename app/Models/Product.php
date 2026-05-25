<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OpenAI\Laravel\Facades\OpenAI; // Tambahkan import ini
use Illuminate\Support\Facades\Http; // Tambahkan ini untuk hit Hugging Face
use Illuminate\Support\Facades\Log; // Untuk mencatat error jika API OpenAI gagal

class Product extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'brand_id', 'name', 'slug', 'category', 'gender_type', 
        'description', 'image_url', 'is_new_arrival', 
        'discount_percent', 'search_context' // Cukup kolom teks ini saja
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
        // Otomatis buat kalimat konteks untuk dibaca AI saat produk disimpan
        static::saved(function ($product) {
            $relevantFields = ['name', 'category', 'gender_type', 'description'];
            
            if ($product->isDirty($relevantFields) || empty($product->search_context)) {
                $context = "Produk: {$product->name}. Kategori: {$product->category}. Untuk Gender: {$product->gender_type}. Deskripsi Aroma: {$product->description}.";
                
                $product->updateQuietly([
                    'search_context' => $context
                ]);
            }
        });
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