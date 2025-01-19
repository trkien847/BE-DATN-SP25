<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function productList(Request $request){
        $brands = Brand::all();
        $categories = Category::with('categoryTypes')->get();
        $search = $request->get('search');

        $products = Product::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'LIKE', '%' . $search . '%');
            })
            ->paginate(5);
        return view('admin.products.productList', compact('brands', 'categories', 'products'));
    }
}
