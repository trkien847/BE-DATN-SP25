<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Services\CategoryService;
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
        $categories = $this->categoryService->getAllCategories()->where('is_active', true);
        $productBestSale = Product::whereNotNull('sale_price')
            ->whereColumn('sale_price', '<', 'sell_price')
            ->orderByRaw('((sell_price - sale_price) / sell_price) DESC')
            ->take(8)
            ->get();
        $productTop = Product::orderBy('views', 'desc')->take(8)->get();
        $carts = Cart::where('user_id', auth()->id())->get();
        $subtotal = $carts->sum(function ($cart) {
            $price = !empty($cart->product->sale_price) && $cart->product->sale_price > 0 
                ? $cart->product->sale_price 
                : $cart->product->sell_price;
            return $cart->quantity * $price;
        }); 
        return view('client.home.index', compact('categories', 'productBestSale', 'productTop', 'carts', 'subtotal'));
    }
}
