<?php

use App\Http\Controllers\Brands\BrandController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Auth;
//admin
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
  return view('welcome');
});
// Route::prefix('admin')->name('admin.')->group(function(){
//   Route::get('/categories',)
// })
Route::get('/admin/categories', [CategoryController::class, 'index'])->name('categories.list');
Route::post('/admin/categories/create', [CategoryController::class, 'create'])->name('categories.create');

// mã giảm giá
Route::get('/admin/brands', [BrandController::class, 'index'])->name('brands.list');
Route::get('/admin/brands/create', [BrandController::class, 'create'])->name('brands.create');
Route::post('/admin/brands/create', [BrandController::class, 'store'])->name('brands.store');


