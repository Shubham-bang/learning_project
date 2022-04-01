<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ShopController;

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
});

/*APIs for customer*/
Route::group(['middleware' => ['auth:api'], 'namespace' => 'customer'], function () {

    Route::post('shop_products', [ShopController::class, 'index']);
});
