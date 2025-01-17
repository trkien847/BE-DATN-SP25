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
Route::get('/admin/categories/create', [CategoryController::class, 'create'])->name('categories.create');
Route::post('/admin/categories/store', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/admin/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/admin/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/admin/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');