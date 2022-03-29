<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function createNewProduct(Request $request)
    {
        $categories = Category::orderBy('id','DESC')->get();
        return view('admin.products.add_new', compact('categories'));
    }

    public function getAllProducts(Request $request)
    {
        $products = Product::orderBy('id','DESC')->get();
        return view('admin.products.index', compact('products'));
    }

    public function storeNewProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
              'category_id' => 'required',
              'name' => 'required',
              'pro_img' => 'file|mimes:png,jpg,jpeg|max:5000',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator);
        }

        try{
           $new_product  =  new Product();
           $new_product->cate_id = $request->get('category_id');
           $new_product->uid    = \Str::random(15);
           $new_product->name   = $request->get('name');
           $new_product->status = '1';
           if($request->hasFile('pro_img')){
                $file_name  =  time() . '.' . $request->file('pro_img')->getClientOriginalName();
                $public_path = public_path() . '/images/';
                $path = $request->pro_img->move($public_path ,$file_name);
                $filename = basename($path);
                $pro_pic =  \URL::asset('/images').'/'.$filename;
            }
            if($request->hasFile('pro_img')){
              $new_product->img = $pro_pic;
            }
           $new_product->save();
           return redirect()->route('admin.product_list')->with('message','Product Added Successfully!');
        } catch(\Exception $ex){
            return back()->withErrors(['error' => [$ex->getMessage()]]);
        }  
    }

    public function getAllCategory(Request $request)
    {
        $categories = Category::orderBy('id','DESC')->get();
        return view('admin.category.index', compact('categories'));
    }

    public function createNewCategory(Request $request)
    {
        return view('admin.category.add');
    }


    public function storeNewCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
              'name' => 'required|',
              // 'password' => 'required',
              'cate_img' => 'file|mimes:png,jpg,jpeg|max:5000',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator);
        }

        try{
           $new_category  =  new Category();
           $new_category->uid    = \Str::random(15);
           $new_category->name   = $request->get('name');
           $new_category->status = $request->get('status');
           if($request->hasFile('cate_img')){
                $file_name  =  time() . '.' . $request->file('cate_img')->getClientOriginalName();
                $public_path = public_path() . '/images/';
                $path = $request->cate_img->move($public_path ,$file_name);
                $filename = basename($path);
                $cate_pic =  \URL::asset('/images').'/'.$filename;
            }
            if($request->hasFile('cate_img')){
              $new_category->icon = $cate_pic;
            }
           $new_category->save();

           return redirect()->route('admin.cate_list')->with('message','Catgory Added Successfully!');
        } catch(\Exception $ex){
            return back()->withErrors(['error' => [$ex->getMessage()]]);
        }  
    }
}
