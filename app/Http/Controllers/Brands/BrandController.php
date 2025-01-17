<?php

namespace App\Http\Controllers\Brands;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.brands.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brands.brand.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input data
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle the logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        // Create the brand
        Brand::create([
            'name' => $request->input('name'),
            'slug' => null, // Slug will be generated automatically in the model
            'logo' => $logoPath ?? null,
            'is_active' => $request->input('is_active', true),
        ]);

        // Redirect back with success message
        return redirect()->route('brands.list')->with('success', 'Brand created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
