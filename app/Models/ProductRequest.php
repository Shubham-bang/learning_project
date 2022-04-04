<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRequest extends Model
{
    use HasFactory;
    protected $table = 'product_request';

    protected $fillable = [
        'merchant_id',
        'category_id',
        'product_name',
        'product_description',
        'status',
    ];
}
