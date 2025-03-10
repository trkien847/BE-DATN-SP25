<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Attributes;
use App\Models\AttributeValue;
use App\Models\AttributeValueProduct;
use App\Models\AttributeValueProductVariant;
use App\Models\AttributeValues;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use Illuminate\Support\Facades\File;
use App\Models\CategoryProduct;
use App\Models\CategoryType;
use App\Models\CategoryTypeProduct;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\ProductGalleries;
use App\Models\ProductVariant;

class ProductController extends Controller
{
    public function productList(Request $request)
    {
        $brands = Brand::all();
        $categories = Category::with('categoryTypes')->get();
        $search = $request->get('search');
        $products = Product::query()
            ->with([
                'brand',
                'categories',
                'categoryTypes',
                'variants.attributeValues.attribute',
                'attributeValues.attribute'
            ])
            ->where('is_active', 1)
            ->withSum('variants', 'stock')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'LIKE', '%' . $search . '%');
            })
            ->paginate(5);

        return view('admin.products.productList', compact('brands', 'categories', 'products'));
    }

    public function hidden()
    {
        $products = Product::query()
            ->with([
                'brand',
                'categories',
                'categoryTypes',
                'variants.attributeValues.attribute',
                'attributeValues.attribute'
            ])
            ->where('is_active', 0)
            ->withSum('variants', 'stock')
            ->paginate(5);
        return view('admin.products.hidden', compact('products'));
    }

    public function restore($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => 1]);
        return redirect()->route('products.hidden')->with('success', 'Sản phẩm đã bị loại khỏi trò chơi SAYGEX!');
    }

    public function productAdd()
    {
        $brands = Brand::all();
        $attributes = Attribute::with('values')->get();
        $categories = Category::with('categoryTypes')->get();
        return view('admin.products.viewProAdd', compact('brands', 'categories', 'attributes'));
    }

    public function productStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'category_type_id' => 'required',
            'name' => 'required|max:255',
            'sku' => 'required|max:100',
            'brand_id' => 'required',
            'sale_price_start_at' => 'nullable|date',
            'sale_price_end_at' => 'nullable|date|after_or_equal:sale_price_start_at',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'category_id.required' => 'Vui lòng chọn danh mục cha.',
            'category_type_id.required' => 'Vui lòng chọn danh mục con.',
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'sku.required' => 'Vui lòng nhập mã sản phẩm.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
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

        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                $productVariant = ProductVariant::create([
                    'product_id' => $product->id,
                    'price' => $variant['price'],
                    'sale_price' => $variant['sale_price'],
                    'stock' => $variant['stock'],
                ]);


                AttributeValueProductVariant::create([
                    'product_variant_id' => $productVariant->id,
                    'attribute_value_id' => $variant['attribute_value_id'],
                ]);

                AttributeValueProduct::create([
                    'product_id' => $product->id,
                    'attribute_value_id' => $variant['attribute_value_id'],
                ]);
            }
        }

        return redirect()->route('products.list')->with('success', 'Sản phẩm đã được lưu thành công!');
    }


    public function edit($id)
    {
        $product = Product::query()
            ->with([
                'brand',
                'categories',
                'categoryTypes',
                'variants.attributeValues.attribute',
                'attributeValues.attribute'
            ])
            ->where('id', $id)->first();

        $attributes = Attribute::with('values')->get();
        $brands = Brand::all();
        $categories = Category::with('categoryTypes')->get();
        $productGallery = ProductGalleries::where('product_id', $id)->get();
        $categoryTypes = CategoryType::whereIn('category_id', $product->categories->pluck('id'))->get();
        return view('admin.products.productUpdateForm', compact('product', 'categories', 'brands', 'categoryTypes', 'productGallery', 'attributes'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'category_type_id' => 'required',
            'name' => 'required|max:255',
            'sku' => 'required|max:100',
            'brand_id' => 'required',
            'sale_price_start_at' => 'nullable|date',
            'sale_price_end_at' => 'nullable|date|after_or_equal:sale_price_start_at',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'category_id.required' => 'Vui lòng chọn danh mục cha.',
            'category_type_id.required' => 'Vui lòng chọn danh mục con.',
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'sku.required' => 'Vui lòng nhập mã sản phẩm.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
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
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                if (isset($variantData['id']) && !empty($variantData['id'])) {

                    $variant = ProductVariant::findOrFail($variantData['id']);
                    $variant->update([
                        'price' => $variantData['price'],
                        'stock' => $variantData['stock'],
                    ]);
                } else {

                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'price' => $variantData['price'],
                        'sale_price' => $variantData['sale_price'],
                        'stock' => $variantData['stock'],
                    ]);
                    AttributeValueProductVariant::create([
                        'product_variant_id' => $variant->id,
                        'attribute_value_id' => $variantData['attribute_value_id'],
                    ]);
                    AttributeValueProduct::create([
                        'product_id' => $product->id,
                        'attribute_value_id' => $variantData['attribute_value_id'],
                    ]);
                }
                $variantIds[] = $variant->id;
            }
        }


        ProductVariant::where('product_id', $product->id)
            ->whereNotIn('id', $variantIds)
            ->delete();


        return redirect()->route('products.list')->with('success', 'Sản phẩm đã được sửa thành công!');
    }

    public function destroy(string $id)
    {
        $product = Product::find($id);
        $product->is_active = 0;
        $product->save();

        return redirect()->route('products.list')->with('success', 'Sản phẩm này đã bị cho tham gia trò chơi SAYGEX!');
    }


    public function getProduct($id)
    {
        $product = Product::with([
            'categories',
            'variants.attributeValues.attribute' // Eager load attribute values và attribute
        ])->findOrFail($id);

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'thumbnail' => $product->thumbnail,
            'sale_price' => $product->variants->isNotEmpty()
                ? ($product->variants->pluck('sale_price')->filter()->first() ?? $product->variants->pluck('price')->first())
                : ($product->sale_price ?? $product->sell_price),
            'sell_price' => $product->variants->isNotEmpty()
                ? $product->variants->pluck('price')->first()
                : $product->sell_price,
            'categories' => $product->categories,
            'variants' => $product->variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'price' => $variant->price,
                    'sale_price' => $variant->sale_price,
                    'stock' => $variant->stock,
                    'attributes' => $variant->attributeValues->map(function ($attrValue) {
                        return [
                            'attribute' => [
                                'name' => $attrValue->attribute->name,
                                'slug' => $attrValue->attribute->slug
                            ],
                            'value' => $attrValue->value
                        ];
                    })
                ];
            })
        ]);
    }






    // biến thể
    public function attributesList(Request $request)
    {
        $attributes = Attribute::with('values')->get();
        return view('admin.products.attributes', compact('attributes'));
    }

    public function attributesCreate()
    {
        return view('admin.products.attributesCreate');
    }

    public function attributesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:attributes',
            'is_variant' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $attributes = new Attribute();
        $attributes->name = $request->name;
        $attributes->slug = $request->slug;
        $attributes->is_variant = 1;
        $attributes->is_active = 1;
        $attributes->save();

        $attributesValues = new AttributeValue();
        $attributesValues->attribute_id = $attributes->id;
        $attributesValues->value = $request->value;
        $attributesValues->is_active = 1;
        $attributesValues->save();

        return redirect()->route('attributes.list')->with('success', 'Thuộc tính đã được thêm!');
    }

    public function attributesEdit($id)
    {
        $attribute = Attribute::with('values')->findOrFail($id);
        return view('admin.products.attributesEdit', compact('attribute'));
    }

    public function attributesUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:attributes,slug,' . $id,
            'is_variant' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $attributes = Attribute::find($id);
        $attributes->name = $request->name;
        $attributes->slug = $request->slug;
        $attributes->is_variant = 1;
        $attributes->is_active = 1;
        $attributes->save();

        $attributesValues = AttributeValue::where('attribute_id', $id)->first();
        $attributesValues->attribute_id = $attributes->id;
        $attributesValues->value = $request->value;
        $attributesValues->is_active = 1;
        $attributesValues->save();

        return redirect()->route('attributes.list')->with('success', 'Thuộc tính đã được cập nhật!');
    }




    // cli



    public function productct($id)
    {
        $carts = Cart::where('user_id', auth()->id())->get();
        $subtotal = $carts->sum(function ($cart) {
            $price = !empty($cart->product->sale_price) && $cart->product->sale_price > 0
                ? $cart->product->sale_price
                : $cart->product->sell_price;
            return $cart->quantity * $price;
        });
        $product = Product::query()
            ->with([
                'brand',
                'categories',
                'categoryTypes',
                'variants.attributeValues.attribute',
                'attributeValues.attribute'
            ])
            ->where('id', $id)->first();

        $min_variant_price = $product->variants->min('price');

        $brands = Brand::all();
        $categories = Category::with('categoryTypes')->get();
        $productGallery = ProductGalleries::where('product_id', $id)->get();
        $productGallery2 = ProductGalleries::where('product_id', $id)->get();
        $categoryTypes = CategoryType::whereIn('category_id', $product->categories->pluck('id'))->get();

        $categoryIds = $product->categories->pluck('id')->toArray();
        $categoryTypeIds = $product->categoryTypes->pluck('id')->toArray();

        $relatedProducts = Product::whereHas('categories', function ($query) use ($categoryIds) {
            $query->whereIn('categories.id', $categoryIds);
        })
            ->orWhereHas('categoryTypes', function ($query) use ($categoryTypeIds) {
                $query->whereIn('category_types.id', $categoryTypeIds);
            })
            ->where('id', '!=', $id)
            ->limit(10)
            ->get();
        $subtotal = $carts->sum(function ($cart) {
            $price = !empty($cart->productVariant->sale_price) && $cart->productVariant->sale_price > 0
                ? $cart->productVariant->sale_price
                : $cart->productVariant->price;
            return $cart->quantity * $price;
        });
        return view('client.product.productct', compact(
            'product',
            'categories',
            'brands',
            'categoryTypes',
            'productGallery',
            'productGallery2',
            'subtotal',
            'relatedProducts',
            'min_variant_price',
            'carts',
            'subtotal'
        ));
    }


    public function import()
    {
        $products = Product::with('variants.attributeValues.attribute')
            ->whereNull('import_at')
            ->get();


        $importedProducts = Product::whereNotNull('import_at')
            ->select('import_at')
            ->selectRaw('GROUP_CONCAT(name) as product_names')
            ->groupBy('import_at')
            ->get();

        return view('admin.products.import', compact('products', 'importedProducts'));
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'import_at' => 'required|date',
            'products' => 'required|array',
            'variants' => 'required|array',
            'import_prices' => 'required|array',
            'import_prices.*' => 'required|numeric|min:0',
        ]);

        $products = $request->input('products');
        $variants = $request->input('variants');
        $importPrices = $request->input('import_prices');
        $importAt = $request->input('import_at');

        Product::whereIn('id', $products)->update([
            'import_at' => $importAt,
            'updated_at' => now(),
        ]);

        foreach ($variants as $variantId) {
            if (isset($importPrices[$variantId])) {
                ProductVariant::where('id', $variantId)->update([
                    'import_price' => $importPrices[$variantId],
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('products.list')->with('success', 'Đã cập nhật giá nhập biến thể và thời gian nhập sản phẩm thành công!');
    }
}
