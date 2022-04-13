<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\CategoryProduct;
use App\Models\ProductRequest;
use App\Models\CategoryRequest;
use App\Models\Merchant;
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
        $products = CategoryProduct::orderBy('id','DESC')->get();
        foreach ($products as $key => $pro) {
            $pro->category = Category::where('id', $pro->category_id)->first();
        }
        return view('admin.products.index', compact('products'));
    }

    public function editProductById(Request $request, $id)
    {
        $data['categories']  = Category::orderBy('id','DESC')->get();
        $data['product'] = CategoryProduct::where('id', $id)->first();
        $data['category']  = Category::where('id', $data['product']->category_id)->first();

        return view('admin.products.edit', $data);
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
           $new_product  =  new CategoryProduct();
           $new_product->category_id = $request->get('category_id');
           $new_product->name   = $request->get('name');
           $new_product->description   = $request->get('description');
           $new_product->status = '1';
           if($request->hasFile('pro_img')){
                $file_name  =  time() . '.' . $request->file('pro_img')->getClientOriginalName();
                $public_path = public_path() . '/images/';
                $path = $request->pro_img->move($public_path ,$file_name);
                $filename = basename($path);
                $pro_pic =  \URL::asset('/images').'/'.$filename;
            }
            if($request->hasFile('pro_img')){
              $new_product->image = $pro_pic;
            }
           $new_product->save();
           return redirect()->route('admin.product_list')->with('message','Product Added Successfully!');
        } catch(\Exception $ex){
            return back()->withErrors(['error' => [$ex->getMessage()]]);
        }  
    }

    public function updateProductById(Request $request)
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
           $id = $request->get('pro_id');
           $update_product  =  CategoryProduct::where('id', $id)->first();
           $update_product->category_id = $request->get('category_id');
           $update_product->name   = $request->get('name');
           $update_product->description   = $request->get('description');
           $update_product->status = '1';
           if($request->hasFile('pro_img')){
                $file_name  =  time() . '.' . $request->file('pro_img')->getClientOriginalName();
                $public_path = public_path() . '/images/';
                $path = $request->pro_img->move($public_path ,$file_name);
                $filename = basename($path);
                $pro_pic =  \URL::asset('/images').'/'.$filename;
            }
            if($request->hasFile('pro_img')){
              $update_product->image = $pro_pic;
            }
           $update_product->save();
           return redirect()->route('admin.product_list')->with('message','Product Updated Successfully!');
        } catch(\Exception $ex){
            return back()->withErrors(['error' => [$ex->getMessage()]]);
        }  
    }

    public function getAllCategory(Request $request)
    {
        $categories = Category::orderBy('id','DESC')->get();
        return view('admin.category.index', compact('categories'));
    }

    // Category Request
    public function getAllCategoryRequest(Request $request)
    {
        $category_requests = CategoryRequest::orderBy('id','DESC')->get();
        foreach ($category_requests as $key => $value) {
            $value->merchent = Merchant::where('id',$value->merchant_id)->first();
        }
        return view('admin.category_request.index', compact('category_requests'));
    }

    public function changeCategoryRequestStatus(Request $request, $id)
    {
        $category_request = CategoryRequest::where('id', $id)->first();

        $request_status = $user->category_request;

         switch($request_status){
            case 0:   // inactive
              $category_request->status = 1; // active
              $category_request->save();
              break;
            case 1 :  // active 
              $category_request->status = 0; // inactive
              $category_request->save();
              break;
        }
        return redirect()->back()->with('message' , "Status Updated Successfully!!");
    }

    public function changeProductRequestStatus(Request $request, $id)
    {
        $category_request = ProductRequest::where('id', $id)->first();

        $request_status = $user->category_request;

         switch($request_status){
            case 0:   // inactive
              $category_request->status = 1; // active
              $category_request->save();
              break;
            case 1 :  // active 
              $category_request->status = 0; // inactive
              $category_request->save();
              break;
        }
        return redirect()->back()->with('message' , "Status Updated Successfully!!");
    }

    // Product Request
    public function getAllProductRequest(Request $request)
    {
        $products_requests = ProductRequest::orderBy('id','DESC')->get();
        foreach ($products_requests as $key => $value) {
            $value->merchent = Merchant::where('id',$value->merchant_id)->first();
            $value->category = Category::where('id',$value->category_id)->first();
        }
        return view('admin.category_request.product_request', compact('products_requests'));
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
           $new_category->name   = $request->get('name');
           $new_category->description   = $request->get('description');
           $new_category->status = $request->get('status');
           if($request->hasFile('cate_img')){
                $file_name  =  time() . '.' . $request->file('cate_img')->getClientOriginalName();
                $public_path = public_path() . '/images/';
                $path = $request->cate_img->move($public_path ,$file_name);
                $filename = basename($path);
                $cate_pic =  \URL::asset('/images').'/'.$filename;
            }
            if($request->hasFile('cate_img')){
              $new_category->image = $cate_pic;
            }
           $new_category->save();

           return redirect()->route('admin.cate_list')->with('message','Catgory Added Successfully!');
        } catch(\Exception $ex){
            return back()->withErrors(['error' => [$ex->getMessage()]]);
        }  
    }

    public function editCategoryById(Request $request, $id)
    {
        $data['category'] = Category::where('id', $id)->first();
        return view('admin.category.edit', $data);
    }

    public function updateCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
              'name' => 'required|',
              'cate_img' => 'file|mimes:png,jpg,jpeg|max:5000',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator);
        }

        try{
            $id = $request->get('cate_id');
           $category  =  Category::where('id',$id)->first();
           $category->name   = $request->get('name');
           $category->description   = $request->get('description');
           $category->status = $request->get('status');
           if($request->hasFile('cate_img')){
                $file_name  =  time() . '.' . $request->file('cate_img')->getClientOriginalName();
                $public_path = public_path() . '/images/';
                $path = $request->cate_img->move($public_path ,$file_name);
                $filename = basename($path);
                $cate_pic =  \URL::asset('/images').'/'.$filename;
            }
            if($request->hasFile('cate_img')){
              $category->image = $cate_pic;
            }
           $category->save();

           return redirect()->route('admin.cate_list')->with('message','Catgory Updated Successfully!');
        } catch(\Exception $ex){
            return back()->withErrors(['error' => [$ex->getMessage()]]);
        }  
    }
}
