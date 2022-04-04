<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ShopController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*APIs without Token*/
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot_password', [AuthController::class, 'forgot_password']);
Route::post('/verify_otp', [AuthController::class, 'verifyOTP']);
Route::post('/change_password_with_token', [AuthController::class, 'changePasswordWithToken']);
Route::post('/verifying_mobile_number', [AuthController::class, 'verifyMobileOTP']);
Route::post('/change_number', [AuthController::class, 'changeMobileNumber']);
/*APIs with Token*/

/*APIs for merhant*/
Route::group(['middleware' => ['auth:api'], 'namespace' => 'merhant'], function () {

    Route::get('categories_listing', [CategoryController::class, 'index']);
    Route::post('category_by_product', [CategoryController::class, 'categoryByProduct']);
    Route::post('add_product_in_shop', [CategoryController::class, 'store']);

    /*APIs for order requests*/
    Route::get('upcomming_orders', [OrderController::class, 'show']);
    Route::get('delivered_orders', [OrderController::class, 'deliveredOrders']);
    Route::post('order_deliver', [OrderController::class, 'orderDeliver']);
    Route::post('order_cancel', [OrderController::class, 'orderCancel']);

    /*APIs for shop open and close requests*/
    Route::post('open_shop', [ShopController::class, 'openShop']);
    Route::post('close_shop', [ShopController::class, 'closeShop']);

    /*APIs for category and Product-request*/
    Route::post('request_for_category', [CategoryController::class, 'categoryRequest']);
    Route::post('request_for_product_in_category', [CategoryController::class, 'productRequest']);

    /*APIs for searching products in categories*/
    Route::post('search_product', [CategoryController::class, 'searchProduct']);

});

/*APIs for customer*/
Route::group(['middleware' => ['auth:api'], 'namespace' => 'customer'], function () {

    Route::post('shop_products', [ShopController::class, 'index']);
    Route::post('find_merhant', [ShopController::class, 'store']);

    /*APIs for placing order*/
    Route::post('place_order', [OrderController::class, 'store']);

    /*APIs for address*/
    Route::post('list_of_address', [UserController::class, 'index']);
    Route::post('add_address', [UserController::class, 'store']);
});

