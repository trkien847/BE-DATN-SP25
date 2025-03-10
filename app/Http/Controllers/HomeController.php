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
        $productBestSale = Product::selectRaw(
            'products.*, 
            (SELECT MIN(price) FROM product_variants WHERE product_variants.product_id = products.id) as min_price, 
            (SELECT MIN(sale_price) FROM product_variants WHERE product_variants.product_id = products.id AND sale_price > 0) as min_sale_price'
        )
            ->havingRaw('min_sale_price IS NOT NULL AND min_sale_price < min_price')
            ->orderByRaw('((min_price - min_sale_price) / min_price) DESC')
            ->take(8)
            ->get();
        $productTop = Product::orderBy('views', 'desc')->take(8)->get();
        $carts = Cart::where('user_id', auth()->id())->get();
        $subtotal = $carts->sum(function ($cart) {
            $price = !empty($cart->productVariant->sale_price) && $cart->productVariant->sale_price > 0 
                ? $cart->productVariant->sale_price 
                : $cart->productVariant->price;
            return $cart->quantity * $price;
        });
        return view('client.home.index', compact('categories', 'productBestSale', 'productTop', 'carts', 'subtotal'));
    }
}
