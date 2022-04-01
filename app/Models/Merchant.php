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
        'merchant_id',
        'name',
        'email',
        'shop_name',
        'shop_address',
        'shop_photo',
        'latitude',
        'longitude',
    ];

    public static function getLocation($lat, $lon){
        return \DB::table("merchant")
                    ->select("merchant.id"
                        ,\DB::raw("6371 * acos(cos(radians(" . $lat . ")) 
                        * cos(radians(merchant.latitude)) 
                        * cos(radians(merchant.longitude) - radians(" . $lon . ")) 
                        + sin(radians(" .$lat. ")) 
                        * sin(radians(merchant.latitude))) AS distance"))
                        ->groupBy("merchant.id")
                        ->get();
    }
}
