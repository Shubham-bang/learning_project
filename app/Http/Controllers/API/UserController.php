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
            $message = $validator->errors();
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
