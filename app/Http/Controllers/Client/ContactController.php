<?php

namespace App\Http\Controllers\Client;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    function contact()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('client.home.contact', compact('categories'));
    }
}
