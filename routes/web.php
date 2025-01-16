<?php

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
