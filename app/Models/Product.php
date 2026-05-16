<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'discount_percent'
    ];

    // Relasi: Produk ini milik 1 brand (Kebalikan dari hasMany)
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Relasi: Satu produk punya banyak ukuran/varian (50ml, 100ml)
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Relasi Pivot: Satu produk punya banyak aroma (Many-to-Many)
    public function notes()
    {
        return $this->belongsToMany(ScentNote::class, 'product_notes');
    }
}
