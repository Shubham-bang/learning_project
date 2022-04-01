<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Customer;
use App\Models\Merchant;
use Validator;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $check_producdts = Product::where('user_id', $request->user_id)->where('status', 1)->get();
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
            $message = $validator->errors();
            return response()->json([
                'message' => $message,
            ], 400);
        }

        $lat = $request->latitude;
        $lon = $request->longitude;
        $max_distance = $request->distance;

        $user = User::find(Auth::user()->id);
        $find_location = Merchant::getLocation($lat, $lon);
        $list = [];
        $merchants = [];
        foreach ($find_location as $key => $find_locations) {
            if ($find_locations->distance < $max_distance ) {
                $merchants[] = Merchant::find($find_locations->id);
            } 
        }
        if ($merchants == []) {
            return response()->json([
                'message' => 'No Merchant Found in between ' . $max_distance . "KM Distance",
            ], 400);
        } else {
            return response()->json([
                'message' => 'listing of products',
                'data1' => $merchants,
                'data2' => $find_location,
            ], 200);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
