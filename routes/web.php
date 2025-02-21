<?php

use App\Http\Controllers\Brands\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Coupons\CoupoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopListController;
use Illuminate\Support\Facades\Auth;
//admin
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;

Route::get('/auto-login', function () {
    Auth::loginUsingId(1);
    return redirect('/');
});

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/{categoryId}/{subcategoryId?}', [ShopListController::class, 'show'])
    ->where(['categoryId' => '[0-9]+', 'subcategoryId' => '[0-9]*'])
    ->name('category.show');

Route::get('/search/{categoryId}/{subcategoryId?}', [ShopListController::class, 'search'])->name('search');
Route::get('/get-product/{id}', [ProductController::class, 'getProduct'])->name('get-product');

Route::get('/cart', [CartController::class, 'index'])->name('get-cart');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');

Route::get('/admin/categories', [CategoryController::class, 'index'])->name('categories.list');
Route::get('/admin/categories/create', [CategoryController::class, 'create'])->name('categories.create');
Route::post('/admin/categories/store', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/admin/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/admin/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/admin/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
Route::post('/categories/{id}/toggle-active', [CategoryController::class, 'toggleActive']);
Route::post('/categories/{id}/toggle-subcategory-active', [CategoryController::class, 'toggleSubcategoryActive'])->name('categories.toggleSubcategoryActive');

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
Route::get('/admin/edit_reviews/{reviews}', [ReviewsController::class, 'listedit'])->name('list.edit');
// Route::put('/admin/edit/{reviews}', [ReviewsController::class, 'edit'])->name('reviews.edit');
Route::delete('/admin/destroyReviews/{reviews}', [ReviewsController::class, 'destroy'])->name('reviews.destroy');



// // register
// Route::get('/user/form_register', [AuthController::class, 'formRegister'])->name('register.form'); 
// Route::post('/user/register', [AuthController::class, 'register'])->name('register.store'); 
// //login
// Route::get('/user/form_login', [AuthController::class, 'formLogin'])->name('login.form'); 
// Route::post('/user/login', [AuthController::class, 'login'])->name('login');

// product
Route::get('/admin/products', [ProductController::class, 'productList'])->name('products.list');
Route::get('/admin/products/add', [ProductController::class, 'productAdd'])->name('products.add');
Route::post('/admin/products/create', [ProductController::class, 'productStore'])->name('products.store');
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::get('/products/{id}/productct', [ProductController::class, 'productct'])->name('products.productct');
Route::put('/admin/products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/admin/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

Route::get('/admin/attributes', [ProductController::class, 'attributesList'])->name('attributes.list');
Route::get('/admin/attributes/add', [ProductController::class, 'attributesCreate'])->name('attributes.add');
Route::post('/admin/attributes/create', [ProductController::class, 'attributesStore'])->name('attributes.store');
Route::get('/attributes/{id}/edit', [ProductController::class, 'attributesEdit'])->name('attributes.edit');
Route::put('/admin/attributes/{id}', [ProductController::class, 'attributesUpdate'])->name('attributes.update');

Route::get('/admin/orders', [OrderController::class, 'index'])->name('order.list');
Route::post('/update-order-status', [OrderController::class, 'updateStatus'])->name('updateOrderStatus');