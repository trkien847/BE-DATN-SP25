<?php

use App\Http\Controllers\Brands\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Coupons\CoupoController;
use App\Http\Controllers\ReviewsController;
use Illuminate\Support\Facades\Auth;
//admin
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;



Route::get('/', function () {
  return view('welcome');
});
// Route::prefix('admin')->name('admin.')->group(function(){
//   Route::get('/categories',)
// })
Route::get('/admin/categories', [CategoryController::class, 'index'])->name('categories.list');
Route::post('/admin/categories/create', [CategoryController::class, 'create'])->name('categories.create');

// thương hiệu
Route::get('/admin/brands', [BrandController::class, 'index'])->name('brands.list');
Route::get('/admin/brands/create', [BrandController::class, 'create'])->name('brands.create');
Route::post('/admin/brands/create', [BrandController::class, 'store'])->name('brands.store');
Route::get('/admin/brands/{id}/edit', [BrandController::class, 'edit'])->name('brands.edit');
Route::put('/admin/brands/{id}', [BrandController::class, 'update'])->name('brands.update'); // Cập nhật thương hiệu
Route::delete('/admin/brands/{id}', [BrandController::class, 'destroy'])->name('brands.destroy');

// mã giảm giá
Route::get('/admin/coupons', [CoupoController::class, 'index'])->name('coupons.list');
Route::get('/admin/coupons/create', [CoupoController::class, 'create'])->name('coupons.create');
Route::post('/admin/coupons/create', [CoupoController::class, 'store'])->name('coupons.store');

// reviews
Route::get('/admin/reviews', [ReviewsController::class, 'index'])->name('reviews.list');
Route::get('/admin/edit_reviews/{reviews}', [ReviewsController::class, 'index'])->name('list.edit');
// Route::put('/admin/edit/{reviews}', [ReviewsController::class, 'index'])->name('reviews.edit');
Route::delete('/admin/destroyReviews/{reviews}', [ReviewsController::class, 'index'])->name('reviews.destroy');



// register
Route::get('/user/form_register', [AuthController::class, 'formRegister'])->name('register.form'); 
Route::post('/user/register', [AuthController::class, 'register'])->name('register.store'); 
//login
Route::get('/user/form_login', [AuthController::class, 'formLogin'])->name('login.form'); 
Route::post('/user/login', [AuthController::class, 'login'])->name('login');

Route::get('/admin/orders', function () {
  return view('admin.order_management.order');
})->name('order.list');
// product
Route::get('/admin/products', function () {
  return view('admin.products.productList');
})->name('products.list');