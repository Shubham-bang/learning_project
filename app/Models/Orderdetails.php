<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderdetails extends Model
{
    use HasFactory;
    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
    ];

    public static function  getDetails()
    {
    	return \DB::table('order_details')
            ->leftjoin('products', 'products.product_id', '=', 'order_details.product_id')
            ->leftjoin('categories_products', 'categories_products.id', '=', 'order_details.product_id')
            ->select('order_details.*',
                'categories_products.name as name',
                'categories_products.image as image',
                'products.weight as weight',
                'products.unit as unit',
                'products.price as price',
                'products.discount_price as discount_price'
            )
            ->orderBy('id', 'desc')
            ->get();
    }
}
