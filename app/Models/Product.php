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
        'discount_percent', 'search_context'
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
        // Setiap kali produk di-save, jalankan fungsi regenerasi konteks
        static::saved(function ($product) {
            $product->generateRichSearchContext();
        });
    }

    /**
     * Fungsi Mandiri untuk Merajut Data Produk + Varian + Scent Notes
     */
    public function generateRichSearchContext()
    {
        // 1. Load relasi secara segar agar datanya sinkron
        $this->load(['variants', 'notes']);

        // 2. Susun teks info varian (ukuran, harga, stok)
        $variantTexts = [];
        foreach ($this->variants as $variant) {
            // Sesuaikan nama kolom jika di database kamu berbeda (misal: size, price, stock)
            $variantTexts[] = "Ukuran {$variant->size}ml dengan Harga Rp " . number_format($variant->price, 0, ',', '.') . " (Sisa Stok: {$variant->stock})";
        }
        $variantString = count($variantTexts) > 0 
            ? " Pilihan Varian: " . implode(', ', $variantTexts) . "." 
            : " Varian produk saat ini belum tersedia.";

        // 3. Susun teks info scent notes (aroma)
        // Berdasarkan relasi belongsToMany 'notes', kita ambil kolom 'name' dari tabel scent_notes
        $noteNames = $this->notes->pluck('name')->toArray(); 
        $notesString = count($noteNames) > 0 
            ? " Karakter Aroma (Scent Notes): " . implode(', ', $noteNames) . "." 
            : "";

        // 4. Gabungkan semua data menjadi satu kesatuan teks konteks untuk di-vector oleh Hugging Face
        $context = "Produk: {$this->name}. Kategori: {$this->category}. Untuk Gender: {$this->gender_type}. Deskripsi: {$this->description}." . $notesString . $variantString;

        // 5. Simpan diam-diam ke database tanpa memicu looping event
        $this->updateQuietly([
            'search_context' => $context
        ]);
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
        return $this->hasMany(Review::class)->latest();
    }

    public function averageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }
}