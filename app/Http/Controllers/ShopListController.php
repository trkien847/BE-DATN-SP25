<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\CategoryType;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CategoryService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShopListController extends Controller
{
    protected $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function show($categoryId = null, $subcategoryId = null)
    {
        $categories = $this->categoryService->getAllCategories();
        $sevenDaysAgo = Carbon::now()->subDays(7);
        $productTop = OrderItem::with('product.variants')
            ->where('created_at', '>=', $sevenDaysAgo)
            ->select('product_id')
            ->selectRaw('SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(3)
            ->get();
        $carts = Cart::where('user_id', auth()->id())->get();

        $subtotal = $carts->sum(function ($cart) {
            $price = !empty($cart->productVariant->sale_price) && $cart->productVariant->sale_price > 0
                ? $cart->productVariant->sale_price
                : $cart->productVariant->price;
            return $cart->quantity * $price;
        });

        $productsQuery = Product::selectRaw(
            'products.*, 
        (SELECT MIN(price) FROM product_variants WHERE product_variants.product_id = products.id) as min_price, 
        (SELECT MIN(sale_price) FROM product_variants WHERE product_variants.product_id = products.id AND sale_price > 0) as min_sale_price'
        );

        if ($categoryId) {
            $category = Category::findOrFail($categoryId);

            if ($subcategoryId) {
                $subcategory = CategoryType::where('id', $subcategoryId)
                    ->where('category_id', $category->id)
                    ->firstOrFail();
                $products = $productsQuery->whereHas('categoryTypes', function ($query) use ($subcategoryId) {
                    $query->where('id', $subcategoryId);
                })->get();
                $brands = $products->pluck('brand')->unique()->filter();
                return view('client.shopList.index', compact('category', 'subcategory', 'products', 'categories', 'brands', 'productTop', 'carts', 'subtotal'));
            }

            $products = $productsQuery->whereHas('categories', function ($query) use ($category) {
                $query->where('categories.id', $category->id);
            })->get();
            $brands = $products->pluck('brand')->unique()->filter();
            return view('client.shopList.index', compact('category', 'products', 'categories', 'brands', 'productTop', 'carts', 'subtotal'));
        }

        $products = $productsQuery->paginate(12);
        $brands = $products->pluck('brand')->unique()->filter();
        return view('client.shopList.index', compact('products', 'categories', 'brands', 'productTop', 'carts', 'subtotal'));
    }
    public function search(Request $request, $categoryId = null, $subcategoryId = null)
    {
        $categories = $this->categoryService->getAllCategories();
        $category = null;
        $subcategory = null;
        $productTop = Product::orderBy('views', 'desc')->take(3)->get();

        // Truy vấn cơ bản với giá
        $query = Product::selectRaw("
        products.*,
        (SELECT MIN(price) FROM product_variants WHERE product_variants.product_id = products.id) as min_price,
        (SELECT MIN(sale_price) FROM product_variants WHERE product_variants.product_id = products.id AND sale_price > 0) as min_sale_price
    ");

        // Lọc theo category/subcategory nếu có
        if ($categoryId) {
            $category = Category::findOrFail($categoryId);

            if ($subcategoryId) {
                $subcategory = CategoryType::where('id', $subcategoryId)
                    ->where('category_id', $category->id)
                    ->firstOrFail();

                $query->whereHas('categoryTypes', function ($q) use ($subcategoryId) {
                    $q->where('category_types.id', $subcategoryId);
                });
            } else {
                $query->whereHas('categories', function ($q) use ($categoryId) {
                    $q->where('categories.id', $categoryId);
                });
            }
        }

        // Lấy danh sách brand
        $brands = $query->get()->pluck('brand')->unique()->filter();

        // Lọc theo từ khóa
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        // Lọc theo brand
        if (!empty($request->brand)) {
            $query->whereIn('brand_id', $request->brand);
        }

        // Lọc theo khoảng giá thực tế (sale_price nếu có, ngược lại là price)
        if (!empty($request->price_range)) {
            $priceFilters = [
                'under_100' => [0, 100000],
                '100_300' => [100000, 300000],
                '300_500' => [300000, 500000],
                'above_500' => [500000, 10000000],
            ];

            $query->whereHas('variants', function ($q) use ($request, $priceFilters) {
                $q->where(function ($subQuery) use ($request, $priceFilters) {
                    foreach ($request->price_range as $range) {
                        if (isset($priceFilters[$range])) {
                            [$min, $max] = $priceFilters[$range];
                            $subQuery->orWhereRaw(
                                '(CASE WHEN sale_price IS NOT NULL AND sale_price > 0 THEN sale_price ELSE price END) BETWEEN ? AND ?',
                                [$min, $max]
                            );
                        }
                    }
                });
            });
        }

        // Sắp xếp
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'popularity':
                    $query->orderBy('views', 'desc');
                    break;
                case 'new':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'price_asc':
                    $query->orderByRaw('IFNULL(min_sale_price, min_price) ASC');
                    break;
                case 'price_desc':
                    $query->orderByRaw('IFNULL(min_sale_price, min_price) DESC');
                    break;
            }
        }

        // Lấy sản phẩm
        $products = $query->get();

        // Trả về Ajax hoặc view
        if ($request->ajax()) {
            return response()->json(['products' => $products]);
        }

        return view('client.shopList.index', compact(
            'category',
            'subcategory',
            'products',
            'categories',
            'brands',
            'productTop'
        ));
    }
}
