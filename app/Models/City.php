<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    protected $table = 'cities';
    
    public $incrementing = false;
    
    public $timestamps = false;

    protected $fillable = [
        'id',
        'province_id',
        'name',
        'type',        // Masukkan jika tabel Anda menyimpan tipe (Kota/Kabupaten)
        'postal_code'  // Masukkan jika tabel Anda menyimpan kode pos bawaan
    ];

    /**
     * Relasi: Kota ini milik sebuah Provinsi
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }
}