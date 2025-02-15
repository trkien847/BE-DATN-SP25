<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\File;
use App\Models\CategoryProduct;
use App\Models\CategoryType;
use App\Models\CategoryTypeProduct;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\ProductGalleries;

class ProductController extends Controller
{
    public function productList(Request $request)
    {
        $brands = Brand::all();
        $categories = Category::with('categoryTypes')->get();
        $search = $request->get('search');
        $products = Product::query()
            ->with(['brand', 'categories', 'categoryTypes'])
            ->when($search, function ($query, $search) {
                return $query->where('name', 'LIKE', '%' . $search . '%');
            })
            ->paginate(5);
        return view('admin.products.productList', compact('brands', 'categories', 'products'));
    }

    public function productAdd()
    {
        $brands = Brand::all();
        $categories = Category::with('categoryTypes')->get();
        return view('admin.products.viewProAdd', compact('brands', 'categories'));
    }

    public function productStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'category_type_id' => 'required',
            'name' => 'required|max:255',
            'sku' => 'required|max:100',
            'brand_id' => 'required',
            'sell_price' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sale_price_start_at' => 'nullable|date',
            'sale_price_end_at' => 'nullable|date|after_or_equal:sale_price_start_at',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'category_id.required' => 'Vui lòng chọn danh mục cha.',
            'category_type_id.required' => 'Vui lòng chọn danh mục con.',
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'sku.required' => 'Vui lòng nhập mã sản phẩm.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
            'sell_price.required' => 'Vui lòng nhập giá bán.',
            'price.required' => 'Vui lòng nhập giá nhập.',
            'sale_price.numeric' => 'Giá khuyến mãi phải là số.',
            'sale_price_start_at.date' => 'Ngày bắt đầu giảm giá phải là định dạng ngày.',
            'sale_price_end_at.after_or_equal' => 'Ngày kết thúc giảm giá phải sau hoặc bằng ngày bắt đầu.',
            'thumbnail.image' => 'Ảnh không hợp lệ, vui lòng chọn tệp ảnh.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Dữ liệu không hợp lệ, vui lòng kiểm tra lại.');
        }

        $price = $request->input('price');
        $sell_price = $request->input('sell_price');
        $sale_price = $request->input('sale_price');

        $customErrors = [];

        if ($price > $sell_price) {
            $customErrors['price'] = 'Giá nhập không được lớn hơn giá bán.';
        }

        if (!is_null($sale_price) && $sale_price > $sell_price) {
            $customErrors['sale_price'] = 'Giá khuyến mãi không được lớn hơn giá bán.';
        }

        if (!empty($customErrors)) {
            return redirect()->back()
                ->withErrors($customErrors)
                ->withInput()
                ->with('error', 'Dữ liệu không hợp lệ, vui lòng kiểm tra lại.');
        }

        $product = new Product();
        $product->brand_id = $request->brand_id;
        $product->name = $request->name;
        $product->views = 0;
        $product->content = $request->content;
        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload'), $imageName);
            $product->thumbnail = $imageName;
        }
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->sell_price = $request->sell_price;
        $product->sale_price = $request->sale_price;
        $product->sale_price_start_at = $request->sale_price_start_at;
        $product->sale_price_end_at = $request->sale_price_end_at;
        $product->is_active = 1;
        $product->save();

        $categoryProduct =  new CategoryProduct();
        $categoryProduct->category_id = $request->category_id;
        $categoryProduct->product_id = $product->id;
        $categoryProduct->save();

        $categoryTypeProduct =  new CategoryTypeProduct();
        $categoryTypeProduct->product_id = $product->id;
        $categoryTypeProduct->category_type_id = $request->category_type_id;
        $categoryTypeProduct->save();

        if ($request->hasFile('image')) {
            foreach ((array) $request->file('image') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('upload'), $imageName);

                $productGallery = new ProductGalleries();
                $productGallery->product_id = $product->id;
                $productGallery->image = $imageName;
                $productGallery->save();
            }
        }

        return redirect()->route('products.list')->with('success', 'Sản phẩm đã được lưu thành công!');
    }


    public function edit($id)
    {
        $product = Product::query()
            ->with(['brand', 'categories', 'categoryTypes'])
            ->where('id', $id)->first();
        $brands = Brand::all();
        $categories = Category::with('categoryTypes')->get();
        $productGallery = ProductGalleries::where('product_id', $id)->get();
        $categoryTypes = CategoryType::whereIn('category_id', $product->categories->pluck('id'))->get();
        return view('admin.products.productUpdateForm', compact('product', 'categories', 'brands', 'categoryTypes', 'productGallery'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'category_type_id' => 'required',
            'name' => 'required|max:255',
            'sku' => 'required|max:100',
            'brand_id' => 'required',
            'sell_price' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sale_price_start_at' => 'nullable|date',
            'sale_price_end_at' => 'nullable|date|after_or_equal:sale_price_start_at',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'category_id.required' => 'Vui lòng chọn danh mục cha.',
            'category_type_id.required' => 'Vui lòng chọn danh mục con.',
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'sku.required' => 'Vui lòng nhập mã sản phẩm.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
            'sell_price.required' => 'Vui lòng nhập giá bán.',
            'price.required' => 'Vui lòng nhập giá nhập.',
            'sale_price.numeric' => 'Giá khuyến mãi phải là số.',
            'sale_price_start_at.date' => 'Ngày bắt đầu giảm giá phải là định dạng ngày.',
            'sale_price_end_at.after_or_equal' => 'Ngày kết thúc giảm giá phải sau hoặc bằng ngày bắt đầu.',
            'thumbnail.image' => 'Ảnh không hợp lệ, vui lòng chọn tệp ảnh.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Dữ liệu không hợp lệ, vui lòng kiểm tra lại.');
        }

        $price = $request->input('price');
        $sell_price = $request->input('sell_price');
        $sale_price = $request->input('sale_price');

        $customErrors = [];

        if ($price > $sell_price) {
            $customErrors['price'] = 'Giá nhập không được lớn hơn giá bán.';
        }

        if (!is_null($sale_price) && $sale_price > $sell_price) {
            $customErrors['sale_price'] = 'Giá khuyến mãi không được lớn hơn giá bán.';
        }

        if (!empty($customErrors)) {
            return redirect()->back()
                ->withErrors($customErrors)
                ->withInput()
                ->with('error', 'Dữ liệu không hợp lệ, vui lòng kiểm tra lại.');
        }

        $product = Product::find($id);
        $product->brand_id = $request->brand_id;
        $product->name = $request->name;
        $product->views = 0;
        $product->content = $request->content;
        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail && File::exists(public_path('uploads/' . $product->thumbnail))) {
                File::delete(public_path('upload/' . $product->thumbnail));
            }
            $image = $request->file('thumbnail');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload'), $imageName);
            $product->thumbnail = $imageName;
        }
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->sell_price = $request->sell_price;
        $product->sale_price = $request->sale_price;
        $product->sale_price_start_at = $request->sale_price_start_at;
        $product->sale_price_end_at = $request->sale_price_end_at;
        $product->is_active = 1;
        $product->save();

        $categoryProduct = CategoryProduct::where('product_id', $id)->first();
        $categoryProduct->category_id = $request->category_id;
        $categoryProduct->product_id = $product->id;
        $categoryProduct->save();

        $categoryTypeProduct = CategoryTypeProduct::where('product_id', $id)->first();
        $categoryTypeProduct->product_id = $product->id;
        $categoryTypeProduct->category_type_id = $request->category_type_id;
        $categoryTypeProduct->save();

        if ($request->hasFile('image')) {
            $productGalleries = ProductGalleries::where('product_id', $id)->get();

            foreach ($productGalleries as $gallery) {
                if (File::exists(public_path('upload/' . $gallery->image))) {
                    File::delete(public_path('upload/' . $gallery->image));
                }
                $gallery->delete();
            }

            foreach ($request->file('image') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('upload'), $imageName);

                ProductGalleries::create([
                    'product_id' => $id,
                    'image' => $imageName,
                ]);
            }
        }


        return redirect()->route('products.list')->with('success', 'Sản phẩm đã được sửa thành công!');
    }

    public function destroy(string $id)
    {
        $product = Product::find($id);

        if ($product->thumbnail && File::exists(public_path('uploads/' . $product->thumbnail))) {
            File::delete(public_path('upload/' . $product->thumbnail));
        }

        $product->delete();

        return redirect()->route('products.list')->with('success', 'Xóa thành công!');
    }

    public function productct($id)
    {
        $product = Product::query()
            ->with(['brand', 'categories', 'categoryTypes'])
            ->where('id', $id)->first();
        $brands = Brand::all();
        $categories = Category::with('categoryTypes')->get();
        $productGallery = ProductGalleries::where('product_id', $id)->get();
        $categoryTypes = CategoryType::whereIn('category_id', $product->categories->pluck('id'))->get();
        return view('admin.products.productct', compact('product', 'categories', 'brands', 'categoryTypes', 'productGallery'));
    }
}
