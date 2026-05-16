<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'logo_url'];
    protected $dates = ['deleted_at'];

    // Relasi: Satu brand punya banyak produk
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
