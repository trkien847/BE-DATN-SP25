<?php

namespace App\Http\Controllers\Brands;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = Brand::query();

    // Kiểm tra nếu có từ khóa tìm kiếm
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where('name', 'LIKE', '%' . $search . '%');
    }

    // Lấy danh sách thương hiệu
    $brands = $query->paginate(10);

    // Trả về view cùng với dữ liệu
    return view('admin.brands.list', compact('brands'));
}

    // public function index()
    // {
    //     $brands = Brand::all();
    //     return view('admin.brands.list', compact('brands'));
    // }

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
            'description' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle the logo upload
        if ($request->hasFile('logo')) {
            // Lưu logo vào thư mục 'logos' trong thư mục public
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        // Create the brand
        Brand::create([
            'name' => $request->input('name'),
            //'slug' => null, // Slug will be generated automatically in the model
            'logo' => $logoPath ?? null, // Chỉ lưu đường dẫn logo nếu có
            'is_active' => $request->input('is_active', true),
            'description' => $request->input('description'),
        ]);

        // Redirect back with success message
        return redirect()->route('brands.list')->with('success', 'Brand created successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $brand = Brand::findOrFail($id); // Lấy thông tin thương hiệu theo ID
        return view('admin.brands.brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $brand = Brand::findOrFail($id); // Tìm thương hiệu theo ID
        $brand->update($request->only(['name', 'slug', 'is_active','description'])); // Cập nhật thương hiệu
      
        // Nếu có file logo, xử lý upload và lưu logo
        if ($request->hasFile('logo')) {
            // Xóa logo cũ nếu có
            if ($brand->logo && Storage::exists('public/' . $brand->logo)) {
                Storage::delete('public/' . $brand->logo);
            }
            // Lưu logo mới vào thư mục 'logos' trong thư mục public
            $brand->logo = $request->file('logo')->store('logos', 'public');
            $brand->save();
        }

        return redirect()->route('brands.list')->with('success', 'Brand updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::findOrFail($id); // Tìm thương hiệu theo ID
    
        // Xóa logo nếu có
        if ($brand->logo && Storage::exists('public/' . $brand->logo)) {
            Storage::delete('public/' . $brand->logo); // Xóa logo khỏi storage
        }
    
        $brand->delete(); // Xóa thương hiệu
    
        return redirect()->route('brands.list')->with('success', 'Brand deleted successfully!');
    }
    
}
