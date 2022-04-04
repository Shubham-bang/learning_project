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
use Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        'merchant_id'       => 'required',
        'product_ids'       => 'required',
        'quantities'        => 'required',
        'total_price'       => 'required',
        'address_id'        => 'required',
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

        $add_order = new Orders();
        $add_order->order_number    = Str::orderedUuid();
        $add_order->user_id         = Auth::user()->id;
        $add_order->merchant_id     = $request->merchant_id;
        $add_order->total_price     = $request->total_price;
        $add_order->address_id      = $request->address_id;
        $add_order->save();

        foreach ($request->product_ids as $key => $product_id) {
            $add_order_products = new Orderdetails();
            $add_order_products->order_id   = $add_order->id;
            $add_order_products->product_id = $product_id;
            $add_order_products->save();
        }
        foreach ($request->quantities as $key => $quantity) {
            $find_order = Orderdetails::where('order_id', $add_order->id)->where('quantity', null)->first();
            $find_order->quantity = $quantity;
            $find_order->save();
        }

        return response()->json([
            'message' => "order request sent succesfully to merchant",
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $upcomming_orders = Orders::where('merchant_id', Auth::user()->id)->where('status', 1)->get();
        if ($upcomming_orders->isEmpty()) {
            return response()->json([
                'message' => "No Upcomming Orders",
            ], 400);
        } else {
            foreach ($upcomming_orders as $key => $upcomming_order) {
                $upcomming_order->user_details      = Customer::where('user_id', $upcomming_order->user_id)->first();
                $upcomming_order->address_details   = Address::where('id', $upcomming_order->address_id)->first();
                $upcomming_order->order_details     = Orderdetails::getDetails();
            }
            return response()->json([
                'message' => "order place succesfully",
                'data'    => $upcomming_orders
            ], 200);
        }
        
    }

    public function deliveredOrders()
    {
        $upcomming_orders = Orders::where('merchant_id', Auth::user()->id)->where('status', 2)->get();
        if ($upcomming_orders->isEmpty()) {
            return response()->json([
                'message' => "No Upcomming Orders",
            ], 400);
        } else {
            foreach ($upcomming_orders as $key => $upcomming_order) {
                $upcomming_order->user_details  = Customer::where('user_id', $upcomming_order->user_id)->first();
                $upcomming_order->order_details = Orderdetails::getDetails();
            }
            return response()->json([
                'message' => "order place succesfully",
                'data'    => $upcomming_orders
            ], 200);
        }
        
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
    public function orderDeliver(Request $request)
    {
        $order = Orders::find($request->order_id);
        $order->status = 2;
        $order->save();
        return response()->json([
            'message' => "order placed to user succesfully",
        ], 200);
    }

    public function orderCancel(Request $request)
    {
        $order = Orders::find($request->order_id);
        $order->status = 3;
        $order->save();
        return response()->json([
            'message' => "order placed to user succesfully",
        ], 200);
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
