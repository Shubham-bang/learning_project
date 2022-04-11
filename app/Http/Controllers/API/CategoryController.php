<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\CategoryRequest;
use App\Models\ProductRequest;
use Illuminate\Support\Facades\Auth;
use Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::where('status', 1)->orderBy('name')->get();
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
            $message = $validator->errors()->first();
            return response()->json([
                'message' => $message,
            ], 400);
        }

        $products = CategoryProduct::where('category_id', $request->category_id)->where('status', 1)->limit(200)->get();
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
            $message = $validator->errors()->first();
            return response()->json([
                'message' => $message,
            ], 400);
        }

        $check_product = Product::where('user_id', Auth::user()->id)->where('product_id', $request->product_id)->first();
        if (isset($check_product)) {
            return response()->json([
                'message' => 'this product is already added in your shop',
            ], 400);
        }
        else{
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

    }

    private $update_product_rules = [ 
        'product_id'        => 'required',
    ];
    public function updateProduct(Request $request)
    {
        $validator = Validator::make($request->all(), $this->update_product_rules);
     
        if ($validator->fails()) { 
            $message = $validator->errors()->first();
            return response()->json([
                'message' => $message,
            ], 400);
        }
        
        $pro_id = $request->get('product_id');
        $user_id = auth()->user()->id;
        $update_product = Product::where('user_id', $user_id)->where('product_id', $pro_id)->first();
        $update_product->weight            = $request->weight;
        $update_product->unit              = $request->unit;
        $update_product->price             = $request->price;
        $update_product->discount_price    = $request->discount_price;
        $update_product->save();

        return response()->json([
            'message' => 'Product updated in your Shop',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    private $create_category_request_rules = [ 
        'category_name'         => 'required',
        'category_description'  => 'required',
    ];
    public function categoryRequest(Request $request)
    {
        $validator = Validator::make($request->all(), $this->create_category_request_rules);
     
        if ($validator->fails()) { 
            $message = $validator->errors();
            return response()->json([
                'message' => $message,
            ], 400);
        }

        $category = new CategoryRequest();
        $category->merchant_id          = Auth::user()->id;
        $category->category_name        = $request->category_name;
        $category->category_description = $request->category_description;
        $category->save();

        return response()->json([
            'message' => 'Request Send Successfully',
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    private $create_product_request_rules = [ 
        'category_id'            => 'required',
        'product_name'           => 'required',
        'product_description'    => 'required',
    ];
    public function productRequest(Request $request)
    {
        $validator = Validator::make($request->all(), $this->create_product_request_rules);
     
        if ($validator->fails()) { 
            $message = $validator->errors();
            return response()->json([
                'message' => $message,
            ], 400);
        }

        $category = new ProductRequest();
        $category->merchant_id          = Auth::user()->id;
        $category->category_id          = $request->category_id;
        $category->product_name         = $request->product_name;
        $category->product_description  = $request->product_description;
        $category->save();

        return response()->json([
            'message' => 'Request Send Successfully',
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    private $create_search_product_rules = [ 
        'category_id'            => 'required',
        'searching_data'         => 'required',
    ];
    public function searchProduct(Request $request)
    {
        $validator = Validator::make($request->all(), $this->create_search_product_rules);
     
        if ($validator->fails()) { 
            $message = $validator->errors();
            return response()->json([
                'message' => $message,
            ], 400);
        }
        $search_word    = $request->searching_data;
        $product        = product_request::where('product_name','LIKE','%'.$search_word.'%')->limit(20)->get();
        $result = null;
        foreach ($product as $key => $search_product) {
            if($search_product->category_id == $request->category_id){
                $result[] = $search_product;
            }
        }
        if ($result == null) {
            return response()->json([
                'message' => 'Not found',
            ], 400);
        }
        if (count($result) > 0 ) {
            return response()->json([
                'data' => $result,
            ], 200);
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
