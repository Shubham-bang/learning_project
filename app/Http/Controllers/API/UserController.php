<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Merchant;
use App\Models\Orders;
use App\Models\Orderdetails;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $address = Address::where('user_id', Auth::user()->id)->get();
        if ($address->isEmpty()) {
            return response()->json([
                'message' => "No Address  Found",
            ], 400);
        } else {
            return response()->json([
                'message' => "list of address",
                'data'    => $address
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
    private $create_merchant_rules = [ 
        'name'                  => 'required',
        'address'               => 'required',
        'postal_code'           => 'required',
        'type_of_address'       => 'required',
    ];
    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(), $this->create_merchant_rules);
     
        if ($validator->fails()) { 
            $message = $validator->errors()->first();
            return response()->json([
                'message' => $message,
            ], 400);
        }

        $add_address = new Address();
        $add_address->user_id           = Auth::user()->id;
        $add_address->name              = $request->name;
        $add_address->address           = $request->address;
        $add_address->postal_code       = $request->postal_code;
        $add_address->type_of_address   = $request->type_of_address;
        $add_address->save();
        return response()->json([
            'message' => "address added succesfully",
        ], 200);
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
    public function updateUserProfile(Request $request)
    {
        $user_type = $request->get('user_type');
        $id = auth()->user()->id;
        if($user_type == '1'){
            $user = User::find($id);
            $user->name           = $request->get('name');
            $user->phone_number   = $request->get('phone');
            $user->save();
            $customer  = Customer::where('user_id', $id)->first();
            $customer->name  = $request->get('name');
            if($request->hasFile('profile_pic')){
                $file_name  =  time() . '.' . $request->file('profile_pic')->getClientOriginalName();
                $public_path = public_path() . '/images';
                $path = $request->profile_pic->move($public_path ,$file_name);
                $filename = basename($path);
                $profile_pic =  \URL::asset('/images').'/'.$filename;
            }
            if($request->hasFile('profile_pic')){
              $customer->profile_pic  = $profile_pic;
            }
            $customer->latitude  = $request->get('latitude');
            $customer->longitude  = $request->get('longitude');
            $customer->save();

            return response(['user' => $user,'customer' => $customer], 200);
        }elseif($user_type == '2') {   // Merchent
            $user = User::find($id);
            $user->name           = $request->get('name');
            $user->phone_number   = $request->get('phone');
            $merchent                = Merchant::where('user_id', $id)->first();
            $merchent->name          = $request->get('name');
            $merchent->shop_name     = $request->get('shop_name');
            $merchent->shop_address  = $request->get('shop_address');
            if($request->hasFile('shop_pic')){
                $file_name  =  time() . '.' . $request->file('shop_pic')->getClientOriginalName();
                $public_path = public_path() . '/images';
                $path = $request->shop_pic->move($public_path ,$file_name);
                $filename = basename($path);
                $shop_pic =  \URL::asset('/images').'/'.$filename;
            }
            if($request->hasFile('shop_pic')){
              $merchent->shop_photo  = $shop_pic;
            }
            $merchent->latitude          = $request->get('latitude');
            $merchent->longitude         = $request->get('longitude');
            $merchent->shop_description  = $request->get('shop_description');
            $merchent->opening_time      = $request->get('opening_time');
            $merchent->closing_time      = $request->get('closing_time');
            $merchent->shop_status       = $request->get('shop_status');
            $merchent->save();

            return response(['user' => $user,'shop' => $merchent], 200);
        } else{
            return response()->json(['messages' => 'Something went wrong!!'], 400);
        }
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
