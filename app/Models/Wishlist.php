<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi
    protected $fillable = ['user_id', 'product_id'];

    // Relasi ke tabel User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke tabel Produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}