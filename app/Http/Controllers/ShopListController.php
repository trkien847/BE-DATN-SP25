<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\CategoryType;
use App\Models\Product;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class ShopListController extends Controller
{
    protected $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function show($categoryId, $subcategoryId = null)
    {
        $category = Category::findOrFail($categoryId);
        $categories = $this->categoryService->getAllCategories();
        $productTop = Product::orderBy('views', 'desc')->take(3)->get();
        if ($subcategoryId) {
            $subcategory = CategoryType::where('id', $subcategoryId)
                ->where('category_id', $category->id)
                ->firstOrFail();
            $products = $subcategory->products;
            $brands = $products->pluck('brand')->unique()->filter();
            $carts = Cart::where('user_id', auth()->id())->get();
            return view('client.shopList.index', compact('category', 'subcategory', 'products', 'categories', 'brands', 'productTop', 'carts'));
        } else {
            $products = $category->products;
            $brands = $products->pluck('brand')->unique()->filter();
            $carts = Cart::where('user_id', auth()->id())->get();
            return view('client.shopList.index', compact('category', 'products', 'categories', 'brands', 'productTop', 'carts'));
        }
    }
    public function search(Request $request, $categoryId, $subcategoryId = null)
    {
        $categories = $this->categoryService->getAllCategories();
        $category = Category::findOrFail($categoryId);
        $brands = $category->products->pluck('brand')->unique()->filter();
        $productTop = Product::orderBy('views', 'desc')->take(3)->get();
        $subcategory = null;
        if ($subcategoryId) {
            $subcategory = CategoryType::where('id', $subcategoryId)
                ->where('category_id', $category->id)
                ->firstOrFail();
            $query = $subcategory->products()->getQuery();
        } else {
            $query = Product::whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }
        // Lọc theo từ khóa
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        // Lọc theo thương hiệu
        if (!empty($request->brand)) {
            $query->whereIn('brand_id', $request->brand);
        }

        // Lọc theo khoảng giá
        if (!empty($request->price_range)) {
            $priceFilters = [
                'under_100' => [0, 100000],
                '100_300' => [100000, 300000],
                '300_500' => [300000, 500000],
                'above_500' => [500000, 10000000],
            ];

            $query->where(function ($q) use ($request, $priceFilters) {
                foreach ($request->price_range as $range) {
                    if (isset($priceFilters[$range])) {
                        $q->orWhere(function ($subQuery) use ($priceFilters, $range) {
                            $subQuery->where(function ($query) use ($priceFilters, $range) {
                                $query->whereNotNull('sale_price')
                                    ->whereBetween('sale_price', [$priceFilters[$range][0], $priceFilters[$range][1]]);
                            })->orWhere(function ($query) use ($priceFilters, $range) {
                                $query->whereNull('sale_price')
                                    ->whereBetween('sell_price', [$priceFilters[$range][0], $priceFilters[$range][1]]);
                            });
                        });
                    }
                }
            });
        }
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'popularity':
                    $query->orderBy('views', 'desc');
                    break;
                case 'new':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'price_asc':
                    $query->orderByRaw('IFNULL(sale_price, sell_price) ASC');
                    break;
                case 'price_desc':
                    $query->orderByRaw('IFNULL(sale_price, sell_price) DESC');
                    break;
            }
        }
        $products = $query->get();
        if ($request->ajax()) {
            return response()->json(['products' => $products]);
        }

        return view('client.shopList.index', compact('category', 'subcategory', 'products', 'categories', 'brands', 'productTop'));
    }
}
