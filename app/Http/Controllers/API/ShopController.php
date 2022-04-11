<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Customer;
use App\Models\CategoryProduct;
use App\Models\Merchant;
use Validator;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $create_merchant_rules = [ 
        'merchant_id'       => 'required',
    ];
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), $this->create_merchant_rules);
     
        if ($validator->fails()) { 
            $message = $validator->errors()->first();
            return response()->json([
                'message' => $message,
            ], 400);
        }

        $check_producdts = Product::where('user_id', $request->merchant_id)->where('status', 1)->get();
        foreach ($check_producdts as $key => $check_producdt) {
            $check_producdt->product_detail =  CategoryProduct::find($check_producdt->product_id);
        }
        if ($check_producdts->isEmpty()) {
            return response()->json([
                'message' => 'This Shop-keeper is not added any product in shop',
            ], 400);
        } else {
            return response()->json([
                'message' => 'listing of products',
                'data'    => $check_producdts
            ], 200);
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private $create_find_location_rules = [ 
        'latitude'       => 'required',
        'longitude'      => 'required',
        'distance'       => 'required',
    ];
    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(), $this->create_find_location_rules);
     
        if ($validator->fails()) { 
            $message = $validator->errors()->first();
            return response()->json([
                'message' => $message,
            ], 400);
        }

        $lat = $request->latitude;
        $lon = $request->longitude;
        $max_distance = $request->distance;

        $customer = Customer::where('user_id', Auth::user()->id)->first();
        $customer->latitude     = $lat;
        $customer->longitude    = $lon;
        $customer->save();

        $user = User::find(Auth::user()->id);
        $find_location = Merchant::getLocation($lat, $lon);
        $list = [];
        $merchants = [];
        foreach ($find_location as $key => $find_locations) {
            if ($find_locations->distance < $max_distance ) {
                $merchants[] = Merchant::where('id', $find_locations->id)->where('status', 1)->first();
            } 
        }
        if ($merchants == []) {
            return response()->json([
                'message' => 'No Merchant Found in between ' . $max_distance . "KM Distance",
            ], 400);
        } else {
            return response()->json([
                'message'   => 'listing of products',
                'data'      => $merchants,
            ], 200);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function openShop(Request $request)
    {
        $shop_status = Merchant::find(Auth::user()->id);
        $shop_status->shop_status = 1;
        $shop_status->save();
        return response()->json([
            'message'   => 'shop open successfully',
        ], 200);
    }

    public function closeShop(Request $request)
    {
        $shop_status = Merchant::find(Auth::user()->id);
        $shop_status->shop_status = 0;
        $shop_status->save();
        return response()->json([
            'message'   => 'shop close successfully',
        ], 200);
    }

    public function shopToggle(Request $request)
    {
        $shop = Merchant::where('user_id', Auth::user()->id)->first();
        $shop_status = $shop->shop_status;


        switch($shop_status){
            case 0:   // inactive
              $shop->shop_status = 1; // active
              $shop->save();
              break;
            case 1 :  // active 
              $shop->shop_status = 0; // inactive
              $shop->save();
              break;
        }
        return response()->json([
            'message'   => 'shop status changed successfully',
            'status' =>  $shop->shop_status,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
