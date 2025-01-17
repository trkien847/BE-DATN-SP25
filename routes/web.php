<?php

use App\Http\Controllers\Brands\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Coupons\CoupoController;
use Illuminate\Support\Facades\Auth;
//admin
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
  return view('welcome');
});

Route::get('/admin/categories', [CategoryController::class, 'index'])->name('categories.list');
Route::get('/admin/categories/create', [CategoryController::class, 'create'])->name('categories.create');
Route::post('/admin/categories/store', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/admin/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/admin/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/admin/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

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


// oder
Route::get('/admin/orders', function () {
  return view('admin.order_management.order');
})->name('order.list');
// product
Route::get('/admin/products', function () {
  return view('admin.products.productList');
})->name('products.list');
