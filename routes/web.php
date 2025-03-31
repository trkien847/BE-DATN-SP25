<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\AiTgCtroller;
use App\Http\Controllers\Brands\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Coupons\CoupoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\ReviewsController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopListController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
//admin
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\Cart;
use App\Models\ProductImportDetail;
use App\Models\User;


Route::post('/broadcasting/auth', function () {
  $channelName = request()->input('channel_name');
  if ($channelName === 'private-notifications.' . auth()->id() . '.admin' && auth()->user()->role_id === 3) {
      return response()->json(['auth' => 'valid']);
  }
  return response()->json(['error' => 'Unauthorized'], 403);
})->middleware('auth');


Route::post('/login',                           [UserController::class, 'login'])->name('login.submit');
Route::get('/logout',                           [UserController::class, 'logout'])->name('logout');
Route::get('/loginForm',                        [UserController::class, 'showLogin'])->name('login');
Route::get('/registerForm',                     [UserController::class, 'showRegister'])->name('register');
Route::post('/register',                        [UserController::class, 'register'])->name('register.submit');
Route::get('/profile',                          [UserController::class, 'showProfile'])->name('profile');
Route::put('/profile',                          [UserController::class, 'updateProfile'])->name('profile.update');
Route::get('/forgot-password',                  [UserController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password',                 [UserController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}',           [UserController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password',                  [UserController::class, 'resetPassword'])->name('password.update');
Route::middleware(['check.auth'])->group(function () {
  Route::post('/profile/address', [UserController::class, 'storeAddress'])->name('profile.address.store');
  Route::put('/profile/address/{id}', [UserController::class, 'updateAddress']);
  Route::delete('/profile/address/{id}', [UserController::class, 'destroyAddress']);
});


Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/{categoryId}/{subcategoryId?}', [ShopListController::class, 'show'])
  ->where(['categoryId' => '[0-9]+', 'subcategoryId' => '[0-9]*'])
  ->name('category.show');

Route::get('/search/{categoryId}/{subcategoryId?}', [ShopListController::class, 'search'])->name('search');
Route::get('/get-product/{id}', [ProductController::class, 'getProduct'])->name('get-product');
Route::get('/products/{id}/productct', [ProductController::class, 'productct'])->name('products.productct');

Route::get('/cart', [CartController::class, 'index'])->name('get-cart');
Route::post('/cart/remove', [CartController::class, 'removeCartItem'])->name('cart.remove');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');


Route::post('/checkout', [CartController::class, 'showCheckout'])->name('checkout');
Route::get('/checkout', [CartController::class, 'showCheckout'])->name('checkout.get');

Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');
Route::get('/thank-you', function () {
  $carts = Cart::where('user_id', auth()->id())
    ->with(['productVariant.product', 'productVariant.attributeValues.attribute'])
    ->get();
  $subtotal = $carts->sum(function ($cart) {
    $price = !empty($cart->productVariant->sale_price) && $cart->productVariant->sale_price > 0
      ? $cart->productVariant->sale_price
      : $cart->productVariant->price;
    return $cart->quantity * $price;
  });
  return view('client.cart.thank-you', compact('carts', 'subtotal'));
})->name('thank-you');

Route::get('/checkout/return', [CartController::class, 'vnpayReturn'])->name('checkout.return');


// Ai thích hợp
Route::get('/ai-tg', function () {
  return view('ai.aitg');
})->name('ai-tg');
Route::match(['get', 'post'], '/api/virtual-assistant', [AiTgCtroller::class, 'handleRequest']);

Route::post('/notifications/check', [NotificationController::class, 'checkNotifications'])->name('notifications.check');

Route::middleware(['auth', 'auth.admin'])->group(function () {
  // Dashboard
  Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');

  // Quản lý danh mục
  Route::get('/admin/categories', [CategoryController::class, 'index'])->name('categories.list');
  Route::get('/admin/categories/create', [CategoryController::class, 'create'])->name('categories.create');
  Route::post('/admin/categories/store', [CategoryController::class, 'store'])->name('categories.store');
  Route::get('/admin/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
  Route::put('/admin/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
  Route::delete('/admin/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
  Route::post('/categories/{id}/toggle-active', [CategoryController::class, 'toggleActive']);
  Route::post('/categories/{id}/toggle-subcategory-active', [CategoryController::class, 'toggleSubcategoryActive'])->name('categories.toggleSubcategoryActive');

  // Quản lý thương hiệu
  Route::get('/admin/brands', [BrandController::class, 'index'])->name('brands.list');
  Route::get('/admin/brands/create', [BrandController::class, 'create'])->name('brands.create');
  Route::post('/admin/brands/create', [BrandController::class, 'store'])->name('brands.store');
  Route::get('/admin/brands/{id}/edit', [BrandController::class, 'edit'])->name('brands.edit');
  Route::put('/admin/brands/{id}', [BrandController::class, 'update'])->name('brands.update');
  Route::delete('/admin/brands/{id}', [BrandController::class, 'destroy'])->name('brands.destroy');
  Route::get('/admin/brands/is_active', [BrandController::class, 'indexQueryIs_active'])->name('brands.listActive');

  // Quản lý mã giảm giá
  Route::get('/admin/coupons', [CoupoController::class, 'index'])->name('coupons.list');
  Route::get('/admin/coupons/create', [CoupoController::class, 'create'])->name('coupons.create');
  Route::post('/admin/coupons/create', [CoupoController::class, 'store'])->name('coupons.store');
  Route::delete('/coupons/{id}', [CoupoController::class, 'destroy'])->name('coupons.destroy');
  Route::get('coupons/{id}/edit', [CoupoController::class, 'edit'])->name('coupons.edit');
  Route::put('coupons/{id}', [CoupoController::class, 'update'])->name('coupons.update');

  // Quản lý đánh giá
  Route::get('/admin/reviews', [ReviewsController::class, 'index'])->name('reviews.list');
  Route::get('/admin/edit_reviews/{reviews}', [ReviewsController::class, 'listedit'])->name('list.edit');
  Route::delete('/admin/destroyReviews/{reviews}', [ReviewsController::class, 'destroy'])->name('reviews.destroy');

  // Quản lý sản phẩm
  Route::get('/admin/products', [ProductController::class, 'productList'])->name('products.list');
  Route::get('/admin/products/add', [ProductController::class, 'productAdd'])->name('products.add');
  Route::post('/admin/products/create', [ProductController::class, 'productStore'])->name('products.store');
  Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
  Route::put('/admin/products/{id}', [ProductController::class, 'update'])->name('products.update');
  Route::patch('/admin/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
  Route::get('/products/hidden', [ProductController::class, 'hidden'])->name('products.hidden');
  Route::patch('/products/restore/{id}', [ProductController::class, 'restore'])->name('products.restore');
  


  //lịch sử mua hàng  acceptCancel
  Route::get('/cart/orderHistory', [CartController::class, 'orderHistory'])->name('orderHistory');

  // hủy đơn hàng
  Route::match(['get', 'post'], '/order/{orderId}/cancel', [CartController::class, 'cancelOrder'])->name('order.cancel');
  Route::post('/order/{orderId}/reject-cancel', [CartController::class, 'rejectCancel'])->name('order.rejectCancel');
  Route::post('/order/{orderId}/accept-cancel', [CartController::class, 'acceptCancel'])->name('order.acceptCancel');
  Route::get('/order/{orderId}/details', [CartController::class, 'orderDetails'])->name('order.details');

  Route::match(['get', 'post'], '/order/{orderId}/return', [CartController::class, 'returnOrder'])->name('order.return');

  // thông kê
  Route::get('/orders/statistics', [OrderController::class, 'statistics'])->name('orders.statistics');
  //nhập  search
  Route::get('/products/import', [ProductController::class, 'import'])->name('products.import');
  Route::post('/products/import', [ProductController::class, 'importStore'])->name('products.import.store');
  Route::patch('products/import/confirm/{id}', [ProductController::class, 'confirmImport'])->name('products.import.confirm');
  Route::patch('notifications/mark-as-read/{id}', [ProductController::class, 'markNotificationAsRead'])->name('notifications.markAsRead');
  //Route::post('notifications/check', [ProductController::class, 'checkNotifications'])->name('notifications.check');
  Route::patch('products/import/reject/{id}', [ProductController::class, 'rejectImport'])->name('products.import.reject');
  Route::post('/products/search', [ProductController::class, 'search'])->name('products.import.search');
  Route::get('/admin/imports/{id}/details', function ($id) {
    $details = ProductImportDetail::where('import_id', $id)
      ->join('products', 'product_import_details.product_id', '=', 'products.id')
      ->select('products.name as product_name', 'product_import_details.*')
      ->get();

    return response()->json($details);
  });

  // Quản lý thuộc tính sản phẩm broadcasting
  Route::get('/admin/attributes', [ProductController::class, 'attributesList'])->name('attributes.list');
  Route::get('/admin/attributes/add', [ProductController::class, 'attributesCreate'])->name('attributes.add');
  Route::post('/admin/attributes/create', [ProductController::class, 'attributesStore'])->name('attributes.store');
  Route::get('/attributes/{id}/edit', [ProductController::class, 'attributesEdit'])->name('attributes.edit');
  Route::put('/admin/attributes/{id}', [ProductController::class, 'attributesUpdate'])->name('attributes.update');
  Route::put('/products/approve-pending/{pendingId}', [ProductController::class, 'approvePendingUpdate'])->name('products.approve-pending');
  Route::get('/products/pending-updates', [ProductController::class, 'pendingUpdates'])->name('products.pending-updates');
  Route::get('/products/pending-update/{pendingId}', [ProductController::class, 'viewPendingUpdate'])->name('products.pending-update-detail');
  Route::put('/products/approve-pending/{pendingId}', [ProductController::class, 'approvePendingUpdate'])->name('products.approve-pending');
  Route::delete('/products/reject-pending/{pendingId}', [ProductController::class, 'rejectPendingUpdate'])->name('products.reject-pending');
  // Quản lý đơn hàng
  Route::get('/admin/orders', [OrderController::class, 'index'])->name('order.list');
  Route::post('/admin/orders/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
  Route::get('/admin/orders/{id}/details', [OrderController::class, 'getOrderDetails'])->name('orders.details');
  Route::post('/update-bulk-status', [OrderController::class, 'updateBulkStatus'])->name('update.bulk.status');

  Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
  Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
  Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
  Route::prefix('admin')->name('admin.')->middleware(['auth', 'auth.admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    // Quản lý người dùng
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('users.list');
    Route::get('/admin/users/{id}', [UserManagementController::class, 'detail'])->name('users.detail');
    Route::get('/admin/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/admin/users/store', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/admin/users/{id}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/admin/users/{id}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/admin/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');

    // Quản lý phân quyền
    Route::get('/admin/roles', [UserManagementController::class, 'rolesList'])->name('roles.list');
    Route::get('/admin/roles/create', [UserManagementController::class, 'rolesCreate'])->name('roles.create');
    Route::post('/admin/roles/store', [UserManagementController::class, 'rolesStore'])->name('roles.store');
    Route::get('/admin/roles/{id}/edit', [UserManagementController::class, 'rolesEdit'])->name('roles.edit');
    Route::put('/admin/roles/{id}', [UserManagementController::class, 'rolesUpdate'])->name('roles.update');
    Route::delete('/admin/roles/{id}', [UserManagementController::class, 'rolesDestroy'])->name('roles.destroy');
  });
});
