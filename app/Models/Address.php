<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'label',
        'first_name',
        'last_name',
        'phone',
        'address',
        'city',
        'postal_code',
        'is_default',
    ];

    // Relasi: Alamat ini milik satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
