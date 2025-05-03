<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CategoryService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = $this->categoryService->getAllCategoriesForClient()->where('is_active', true)->where('status', 'approved');
        $sevenDaysAgo = Carbon::now()->subDays(30);
        $productBestSale = OrderItem::with('product.variants')
            ->where('created_at', '>=', $sevenDaysAgo)
            ->select('product_id')
            ->selectRaw('SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(8)
            ->get();
            
        $productTop = Product::orderBy('views', 'desc')->take(6)->get();
        $carts = Cart::with([
            'product.brand',
            'product.categories',
            'product.categoryTypes',
            'product.variants.attributeValues.attribute',
            'product.attributeValues.attribute',
            'productVariant.attributeValues.attribute',
        ])->where('user_id', auth()->id())->get();
        $subtotal = $carts->sum(function ($cart) {
            $price = !empty($cart->productVariant->sale_price) && $cart->productVariant->sale_price > 0
                ? $cart->productVariant->sale_price
                : $cart->productVariant->price;
            return $cart->quantity * $price;
        });
        return view('client.home.index', compact('categories', 'productBestSale', 'productTop', 'carts', 'subtotal'));
    }
}
