<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;
    protected $table = 'merchant';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'shop_name',
        'shop_address',
        'shop_photo',
        'latitude',
        'longitude',
    ];
}
