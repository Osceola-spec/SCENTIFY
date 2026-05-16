<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'subtotal',
        'tax_amount',
        'total_amount',
        'status',
        'shipping_address'
    ];
}
