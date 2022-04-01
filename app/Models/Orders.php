<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'user_id',
        'merchant_id',
        'total_price',
        'address_id',
        'status',
    ];
}
