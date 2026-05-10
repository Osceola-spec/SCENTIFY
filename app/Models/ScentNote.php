<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScentNote extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Relasi Pivot: Satu aroma bisa dimiliki banyak produk
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_notes');
    }
}