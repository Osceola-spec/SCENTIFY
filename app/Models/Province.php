<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    // Menentukan nama tabel secara eksplisit
    protected $table = 'provinces';

    // Karena ID diambil langsung dari API Komerce/RajaOngkir (bukan auto-increment database)
    public $incrementing = false;

    // Matikan timestamps jika di tabel Anda tidak ada kolom created_at & updated_at
    public $timestamps = false;

    // Kolom yang boleh diisi secara mass-assignment
    protected $fillable = [
        'id',
        'name',
    ];

    /**
     * Relasi: Satu Provinsi memiliki banyak Kota/Kabupaten
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'province_id', 'id');
    }
}