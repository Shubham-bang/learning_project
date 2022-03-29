<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::where('status', 1)->get();
        if ($category->isEmpty()) {
            return response()->json([
                'message' => 'No Category Found',
            ], 400);
        } else {
            return response()->json([
                'message' => 'listing of category',
                'data'    => $category
            ], 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $create_category_product_rules = [ 
        'category_id'   => 'required',
    ];
    public function categoryByProduct(Request $request)
    {
        $validator = Validator::make($request->all(), $this->create_category_product_rules);
     
        if ($validator->fails()) { 
            $message = $validator->errors();
            return response()->json([
                'message' => $message,
            ], 400);
        }

        $products = CategoryProduct::where('category_id', $request->category_id)->where('status', 1)->get();
        if ($products->isEmpty()) {
            return response()->json([
                'message' => 'No Product Found in this Category',
            ], 400);
        } else {
            return response()->json([
                'message' => 'listing of products',
                'data'    => $products
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private $create_product_rules = [ 
        'category_id'       => 'required',
        'product_id'        => 'required',
        'weight'            => 'required',
        'unit'              => 'required',
        'price'             => 'required',
        'discount_price'    => 'required',
    ];
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->create_product_rules);
     
        if ($validator->fails()) { 
            $message = $validator->errors();
            return response()->json([
                'message' => $message,
            ], 400);
        }

        $add_product = new Product();
        $add_product->user_id           = Auth::user()->id;
        $add_product->category_id       = $request->category_id;
        $add_product->product_id        = $request->product_id;
        $add_product->weight            = $request->weight;
        $add_product->unit              = $request->unit;
        $add_product->price             = $request->price;
        $add_product->discount_price    = $request->discount_price;
        $add_product->save();

        return response()->json([
            'message' => 'Product added in your Shop',
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
