<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!!
|
*/

// Admin Route
Route::get('/admin/login', [AdminController::class, 'adminLogin'])->name('admin.login');
Route::post('/admin/auth', [AdminController::class, 'adminMakeAuth'])->name('admin.auth');
Route::get('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');

// Route::group(['middleware' => ['auth'],'namespace' => 'Admin'], function () {
    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
// });

// Route::group(['middleware' => ['auth'],'namespace' => 'Admin:Product'], function () {
    Route::get('/admin/products/create/new', [ProductController::class, 'createNewProduct'])->name('admin.create_product');
    Route::post('/admin/products/store/new', [ProductController::class, 'storeNewProduct'])->name('admin.store_product');
    Route::get('/admin/products/list', [ProductController::class, 'getAllProducts'])->name('admin.product_list');
    Route::get('/admin/category/list', [ProductController::class, 'getAllCategory'])->name('admin.cate_list');
    Route::get('/admin/category/create/new', [ProductController::class, 'createNewCategory'])->name('admin.cate_new');
    Route::post('/admin/category/store/new', [ProductController::class, 'storeNewCategory'])->name('admin.cate_store');
// });

// Route::group(['middleware' => ['auth'],'namespace' => 'Admin:User'], function () {
    Route::get('/admin/users/merchent/list', [UserController::class, 'getAllMerchents'])->name('admin.merchents');
// });

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('admin.admin_dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/user/account/verification/{token}/{id}' , [CommonController::class , 'userAccountVerification'])->name('user.account_verify');

require __DIR__.'/auth.php';
