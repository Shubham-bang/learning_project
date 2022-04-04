<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryRequest extends Model
{
    use HasFactory;
    protected $table = 'category_request';

    protected $fillable = [
        'merchant_id',
        'category_name',
        'category_description',
        'status',
    ];
}
