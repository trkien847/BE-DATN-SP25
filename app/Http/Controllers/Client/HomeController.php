<?php

namespace App\Http\Controllers\Client;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    function index()
    {
        $newProducts = Product::newProducts(8)->get();

        $bestsellerProducts = Product::bestsellerProducts(6)->get();

        $instockProducts = Product::instockProducts(3)->get();

        // Kết hợp danh mục và số lượng sản phẩm
        $categories = Category::withCount('products')->orderBy('name', 'asc')->get();

        return view('client.home.home', compact('categories', 'newProducts', 'bestsellerProducts', 'instockProducts'));
    }
}
