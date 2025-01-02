<?php

namespace App\Http\Controllers\Client;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AboutController extends Controller
{
     function about()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('client.home.about', compact('categories'));
    }
}
