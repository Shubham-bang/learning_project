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
    Route::get('/admin/products/edit/{id}', [ProductController::class, 'editProductById'])->name('admin.product_edit');
    Route::post('/admin/products/update', [ProductController::class, 'updateProductById'])->name('admin.product_update');
    Route::get('/admin/category/list', [ProductController::class, 'getAllCategory'])->name('admin.cate_list');
    Route::get('/admin/category/request/list', [ProductController::class, 'getAllCategoryRequest'])->name('cate.request');
    Route::get('/admin/product/request/list', [ProductController::class, 'getAllProductRequest'])->name('product.req');
    Route::get('/product/request/status/change/{id}', [ProductController::class, 'changeProductRequestStatus'])->name('product.req.status');
    Route::get('/category/request/status/change/{id}', [ProductController::class, 'changeCategoryRequestStatus'])->name('category.req.status');
    Route::get('/admin/category/edit/{id}', [ProductController::class, 'editCategoryById'])->name('admin.cate_edit');
    Route::post('/admin/category/update', [ProductController::class, 'updateCategory'])->name('admin.cate_update');
    Route::get('/admin/category/create/new', [ProductController::class, 'createNewCategory'])->name('admin.cate_new');
    Route::post('/admin/category/store/new', [ProductController::class, 'storeNewCategory'])->name('admin.cate_store');

// });

Route::get('/admin/users/merchent/list', [UserController::class, 'getAllMerchents'])->name('admin.merchents');
Route::get('/admin/users/merchent/view/{id}', [UserController::class, 'viewMerchentsDetails'])->name('admin.view_merchent');
Route::get('/admin/users/users/list', [UserController::class, 'getAllusers'])->name('admin.users');
Route::get('change/merchent/status/{id}', [UserController::class, 'changeMerchentStatus'])->name('merhant.status');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('admin.admin_dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/user/account/verification/{token}/{id}' , [CommonController::class , 'userAccountVerification'])->name('user.account_verify');

require __DIR__.'/auth.php';
