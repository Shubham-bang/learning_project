<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
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
Route::get('/user/account/verification' , [AuthController::class , 'userAccountVerification'])->name('user.account_verify');
/*APIs with Token*/
