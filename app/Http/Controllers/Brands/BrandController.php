<?php

namespace App\Http\Controllers\Brands;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{

    public function index(Request $request)
    {
        $query = Brand::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'LIKE', '%' . $search . '%');
        }


        $brands = $query->paginate(5);

        return view('admin.brands.list', compact('brands'));
    }

    public function indexQueryIs_active(Request $request)
    {
        $query = Brand::query()->where('is_active', 1);
        //slect vào bảng brand và có điều kiện is_active = 1 (chỉ lấy các cột có is_active = 1)

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'LIKE', '%' . $search . '%');
        }


        $brands = $query->paginate(10);

        return view('admin.brands.listActive', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.brand.add');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'description' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'nullable|boolean',
        ]);


        if ($request->hasFile('logo')) {

            $logoPath = $request->file('logo')->store('logos', 'public');
        }


        Brand::create([
            'name' => $request->input('name'),

            'logo' => $logoPath ?? null,
            'is_active' => $request->input('is_active', true),
            'description' => $request->input('description'),
        ]);


        return redirect()->route('brands.list')->with('success', 'Brand created successfully!');
    }


    public function show(string $id)
    {
    }


    public function edit(string $id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brands.brand.edit', compact('brand'));
    }


    public function update(Request $request, string $id)
    {
        $brand = Brand::findOrFail($id);
        $brand->update($request->only(['name', 'slug','description']));

        if ($request->hasFile('logo')) {

            if ($brand->logo && Storage::exists('public/' . $brand->logo)) {
                Storage::delete('public/' . $brand->logo);
            }

            $brand->logo = $request->file('logo')->store('logos', 'public');
            $brand->save();
        }

        return redirect()->route('brands.list')->with('success', 'Brand updated successfully!');
    }



    public function destroy(string $id)
    {
        $brand = Brand::findOrFail($id);


        if ($brand->logo && Storage::exists('public/' . $brand->logo)) {
            Storage::delete('public/' . $brand->logo);
        }

        $brand->delete();

        return redirect()->route('brands.list')->with('success', 'Brand deleted successfully!');
    }

    public function toggleActive(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $brand->is_active = $request->is_active;
        $brand->save();

        return response()->json(['success' => true, 'is_active' => $brand->is_active]);
    }


}
