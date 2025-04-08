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
use App\Models\Import;
use App\Models\ImportProduct;
use App\Models\ImportProductVariant;
use App\Models\Notification;
use App\Models\OrderImport;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\ProductGalleries;
use App\Models\ProductImport;
use App\Models\ProductPendingUpdate;
use App\Models\ProductVariant;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\ImportPendingNotification;
use Illuminate\Support\Facades\DB;

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
            'thumbnail' => 'nullable',
        ], [
            'category_id.required' => 'Vui lòng chọn danh mục cha.',
            'category_type_id.required' => 'Vui lòng chọn danh mục con.',
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'sku.required' => 'Vui lòng nhập mã sản phẩm.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Dữ liệu không hợp lệ, vui lòng kiểm tra lại.');
        }

        $user = Auth::user();

        if ($user->role_id !== 3) {
            $pendingData = [
                'brand_id' => $request->brand_id,
                'name' => $request->name,
                'content' => $request->content,
                'sku' => $request->sku,
                'category_id' => $request->category_id,
                'category_type_id' => $request->category_type_id,
                'thumbnail' => null,
                'images' => $request->hasFile('image') ? [] : null,
                'variants' => $request->has('variants') ? $request->variants : null,
            ];

            if ($request->hasFile('thumbnail')) {
                $image = $request->file('thumbnail');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('upload'), $imageName);
                $pendingData['thumbnail'] = $imageName;
            }

            if ($request->hasFile('image')) {
                foreach ((array) $request->file('image') as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('upload'), $imageName);
                    $pendingData['images'][] = $imageName;
                }
            }

            $pendingUpdate = ProductPendingUpdate::create([
                'product_id' => null,
                'user_id' => $user->id,
                'action_type' => 'create',
                'data' => $pendingData,
            ]);


            $admins = User::where('role_id', 3)->get();
            if ($admins->isEmpty()) {
                \Log::warning("Không tìm thấy admin để gửi thông báo cho yêu cầu thêm sản phẩm từ user {$user->id}");
            } else {
                $detailUrl = route('products.pending-update-detail', $pendingUpdate->id);
                $approveUrl = route('products.approve-pending', $pendingUpdate->id);
                $rejectUrl = route('products.reject-pending', $pendingUpdate->id);

                foreach ($admins as $admin) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'title' => "Nhân viên {$user->fullname} đã yêu cầu thêm sản phẩm",
                        'content' => "Tên sản phẩm: {$request->name}",
                        'type' => 'product_pending_create',
                        'data' => [
                            'pending_id' => $pendingUpdate->id,
                            'requester_id' => $user->id,
                            'requester_name' => $user->fullname,
                            'product_name' => $request->name,
                            'actions' => [
                                'view_details' => $detailUrl,
                                'approve_request' => $approveUrl,
                                'reject_request' => $rejectUrl,
                            ],
                        ],
                        'is_read' => 0,
                    ]);
                }
            }

            return redirect()->route('products.list')->with('success', 'Yêu cầu thêm sản phẩm đã được gửi, chờ phê duyệt!');
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
        $product->is_active = 1;
        $product->save();

        $categoryProduct = new CategoryProduct();
        $categoryProduct->category_id = $request->category_id;
        $categoryProduct->product_id = $product->id;
        $categoryProduct->save();

        $categoryTypeProduct = new CategoryTypeProduct();
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
            foreach ($request->variants as $attributeId => $variantValues) {
                foreach ($variantValues as $valueId) {
                    $productVariant = ProductVariant::create([
                        'product_id' => $product->id,
                    ]);

                    AttributeValueProductVariant::create([
                        'product_variant_id' => $productVariant->id,
                        'attribute_value_id' => $valueId,
                    ]);

                    AttributeValueProduct::create([
                        'product_id' => $product->id,
                        'attribute_value_id' => $valueId,
                    ]);
                }
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
            ->where('id', $id)->firstOrFail();

        $attributes = Attribute::with('values')->get();
        $brands = Brand::all();
        $categories = Category::with('categoryTypes')->get();
        $productGallery = ProductGalleries::where('product_id', $id)->get();
        $categoryTypes = CategoryType::whereIn('category_id', $product->categories->pluck('id'))->get();

        $selectedVariantIds = $product->variants->pluck('attributeValues.*.id')->flatten()->toArray();

        return view('admin.products.productUpdateForm', compact('product', 'categories', 'brands', 'categoryTypes', 'productGallery', 'attributes', 'selectedVariantIds'));
    }

    // cập nhất sản phẩm
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'category_type_id' => 'required',
            'name' => 'required|max:255',
            'sku' => 'required|max:100',
            'brand_id' => 'required',
        ], [
            'category_id.required' => 'Vui lòng chọn danh mục cha.',
            'category_type_id.required' => 'Vui lòng chọn danh mục con.',
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'sku.required' => 'Vui lòng nhập mã sản phẩm.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Dữ liệu không hợp lệ, vui lòng kiểm tra lại.');
        }

        $user = Auth::user();
        $product = Product::findOrFail($id);

        if ($user->role_id !== 3) {
            $pendingData = [
                'brand_id' => $request->brand_id,
                'name' => $request->name,
                'content' => $request->content,
                'sku' => $request->sku,
                'price' => $request->price,
                'sell_price' => $request->sell_price,
                'sale_price' => $request->sale_price,
                'category_id' => $request->category_id,
                'category_type_id' => $request->category_type_id,
                'thumbnail' => null,
                'images' => $request->hasFile('image') ? [] : null,
                'variants' => $request->has('variants') ? $request->variants : null,
            ];

            if ($request->hasFile('thumbnail')) {
                $image = $request->file('thumbnail');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('upload'), $imageName);
                $pendingData['thumbnail'] = $imageName;
            }

            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('upload'), $imageName);
                    $pendingData['images'][] = $imageName;
                }
            }

            $pendingUpdate = ProductPendingUpdate::create([
                'product_id' => $id,
                'user_id' => $user->id,
                'action_type' => 'update',
                'data' => $pendingData,
            ]);


            $admins = User::where('role_id', 3)->get();
            if ($admins->isEmpty()) {
                \Log::warning("Không tìm thấy admin để gửi thông báo cho yêu cầu sửa sản phẩm từ user {$user->id}");
            } else {
                $detailUrl = route('products.pending-update-detail', $pendingUpdate->id);
                $approveUrl = route('products.approve-pending', $pendingUpdate->id);
                $rejectUrl = route('products.reject-pending', $pendingUpdate->id);

                foreach ($admins as $admin) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'title' => "Nhân viên {$user->name} đã yêu cầu sửa sản phẩm",
                        'content' => "Tên sản phẩm: {$request->name}",
                        'type' => 'product_pending_update',
                        'data' => [
                            'pending_id' => $pendingUpdate->id,
                            'requester_id' => $user->id,
                            'requester_name' => $user->name,
                            'product_name' => $request->name,
                            'product_id' => $id,
                            'actions' => [
                                'view_details' => $detailUrl,
                                'approve_request' => $approveUrl,
                                'reject_request' => $rejectUrl,
                            ],
                        ],
                        'is_read' => 0,
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Yêu cầu chỉnh sửa sản phẩm đã được gửi, chờ phê duyệt!');
        }


        $product->brand_id = $request->brand_id;
        $product->name = $request->name;
        $product->views = 0;
        $product->content = $request->content;

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail && File::exists(public_path('upload/' . $product->thumbnail))) {
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

        $variantIds = [];
        if ($request->has('variants')) {
            foreach ($request->variants as $attributeId => $valueIds) {
                foreach ($valueIds as $valueId) {
                    $variant = ProductVariant::where('product_id', $product->id)
                        ->whereHas('attributeValues', function ($query) use ($valueId) {
                            $query->where('attribute_value_id', $valueId);
                        })->first();

                    if (!$variant) {
                        $variant = ProductVariant::create([
                            'product_id' => $product->id,
                            'sale_price_start_at' => $request->sale_price_start_at,
                            'sale_price_end_at' => $request->sale_price_end_at,
                        ]);

                        AttributeValueProductVariant::create([
                            'product_variant_id' => $variant->id,
                            'attribute_value_id' => $valueId,
                        ]);

                        AttributeValueProduct::create([
                            'product_id' => $product->id,
                            'attribute_value_id' => $valueId,
                        ]);
                    }
                    $variantIds[] = $variant->id;
                }
            }
        }

        ProductVariant::where('product_id', $product->id)
            ->whereNotIn('id', $variantIds)
            ->delete();

        return redirect()->route('products.list')->with('success', 'Sản phẩm đã được sửa thành công!');
    }

    public function pendingUpdates()
    {
        $user = auth()->user();
        if ($user->role_id !== 3) {
            return redirect()->back()->with('error', 'Bạn không có quyền truy cập trang này!');
        }

        $pendingUpdates = ProductPendingUpdate::with('user')->get();
        return view('admin.products.pending-updates', compact('pendingUpdates'));
    }

    public function viewPendingUpdate(Request $request, $pendingId)
    {
        $notificationId = $request->input('notification_id');
        $user = auth()->user();
        if ($user->role_id !== 3) {
            return redirect()->back()->with('error', 'Bạn không có quyền truy cập!');
        }

        $pendingUpdate = ProductPendingUpdate::with('user')->findOrFail($pendingId);
        $originalProduct = $pendingUpdate->product_id ? Product::find($pendingUpdate->product_id) : null;

        return view('admin.products.pending-update-detail', compact('pendingUpdate', 'originalProduct', 'notificationId'));
    }

    public function approvePendingUpdate(Request $request, $pendingId)
    {
        $user = auth()->user();
        if ($user->role_id !== 3) {
            return redirect()->back()->with('error', 'Bạn không có quyền duyệt!');
        }

        $notificationId = $request->input('notification_id');
        $notification = Notification::find($notificationId);
        $notification->is_read = 1;
        $notification->save();

        $pendingUpdate = ProductPendingUpdate::findOrFail($pendingId);
        $data = $pendingUpdate->data;



        if ($pendingUpdate->action_type === 'create') {
            $product = new Product();
            $product->brand_id = $data['brand_id'];
            $product->name = $data['name'];
            $product->content = $data['content'];
            $product->sku = $data['sku'];
            $product->price = $data['price'] ?? 0;
            $product->sell_price = $data['sell_price'] ?? 0;
            $product->sale_price = $data['sale_price'] ?? 0;
            $product->thumbnail = $data['thumbnail'];
            $product->is_active = 1;
            $product->save();

            CategoryProduct::create([
                'product_id' => $product->id,
                'category_id' => $data['category_id'],
            ]);

            CategoryTypeProduct::create([
                'product_id' => $product->id,
                'category_type_id' => $data['category_type_id'],
            ]);

            if (!empty($data['images'])) {
                foreach ($data['images'] as $imageName) {
                    ProductGalleries::create([
                        'product_id' => $product->id,
                        'image' => $imageName,
                    ]);
                }
            }

            if (!empty($data['variants'])) {
                foreach ($data['variants'] as $attributeId => $valueIds) {
                    foreach ($valueIds as $valueId) {
                        $variant = ProductVariant::create(['product_id' => $product->id]);
                        AttributeValueProductVariant::create([
                            'product_variant_id' => $variant->id,
                            'attribute_value_id' => $valueId,
                        ]);
                        AttributeValueProduct::create([
                            'product_id' => $product->id,
                            'attribute_value_id' => $valueId,
                        ]);
                    }
                }
            }
        } else {
            $product = Product::findOrFail($pendingUpdate->product_id);
            $product->brand_id = $data['brand_id'];
            $product->name = $data['name'];
            $product->content = $data['content'];
            $product->sku = $data['sku'];
            $product->price = $data['price'];
            $product->sell_price = $data['sell_price'];
            $product->sale_price = $data['sale_price'];
            if ($data['thumbnail']) {
                if ($product->thumbnail && File::exists(public_path('upload/' . $product->thumbnail))) {
                    File::delete(public_path('upload/' . $product->thumbnail));
                }
                $product->thumbnail = $data['thumbnail'];
            }
            $product->is_active = 1;
            $product->save();

            CategoryProduct::where('product_id', $product->id)->update(['category_id' => $data['category_id']]);
            CategoryTypeProduct::where('product_id', $product->id)->update(['category_type_id' => $data['category_type_id']]);

            if (!empty($data['images'])) {
                ProductGalleries::where('product_id', $product->id)->delete();
                foreach ($data['images'] as $imageName) {
                    ProductGalleries::create([
                        'product_id' => $product->id,
                        'image' => $imageName,
                    ]);
                }
            }

            if (!empty($data['variants'])) {
                $variantIds = [];
                foreach ($data['variants'] as $attributeId => $valueIds) {
                    foreach ($valueIds as $valueId) {
                        $variant = ProductVariant::where('product_id', $product->id)
                            ->whereHas('attributeValues', function ($query) use ($valueId) {
                                $query->where('attribute_value_id', $valueId);
                            })->first();

                        if (!$variant) {
                            $variant = ProductVariant::create(['product_id' => $product->id]);
                            AttributeValueProductVariant::create([
                                'product_variant_id' => $variant->id,
                                'attribute_value_id' => $valueId,
                            ]);
                            AttributeValueProduct::create([
                                'product_id' => $product->id,
                                'attribute_value_id' => $valueId,
                            ]);
                        }
                        $variantIds[] = $variant->id;
                    }
                }
                ProductVariant::where('product_id', $product->id)->whereNotIn('id', $variantIds)->delete();
            }
        }

        $pendingUpdate->delete();

        return redirect()->route('notifications.index')->with('success', 'Đã duyệt thành công!');
    }

    public function rejectPendingUpdate(Request $request, $pendingId)
    {
        $user = auth()->user();
        if ($user->role_id !== 3) {
            return redirect()->back()->with('error', 'Bạn không có quyền từ chối!');
        }

        $pendingUpdate = ProductPendingUpdate::findOrFail($pendingId);

        if ($pendingUpdate->data['thumbnail'] && File::exists(public_path('upload/' . $pendingUpdate->data['thumbnail']))) {
            File::delete(public_path('upload/' . $pendingUpdate->data['thumbnail']));
        }
        if (!empty($pendingUpdate->data['images'])) {
            foreach ($pendingUpdate->data['images'] as $image) {
                if (File::exists(public_path('upload/' . $image))) {
                    File::delete(public_path('upload/' . $image));
                }
            }
        }

        $pendingUpdate->delete();
        $notificationId = $request->input('notification_id');
        $notification = Notification::find($notificationId);
        $notification->is_read = 1;
        $notification->save();
        return redirect()->route('notifications.index')->with('success', 'Đã từ chối yêu cầu!');
    }


    public function destroy(string $id)
    {
        $product = Product::find($id);
        $product->is_active = 0;
        $product->save();

        return redirect()->route('products.list')->with('success', 'Sản phẩm này đã bị cho tham gia trò chơi SAYGEX!');
    }

    public function storeOrder(Request $request)
    {
        try {
            $validated = $request->validate([
                'order_code' => 'required|string|max:255|unique:order_imports,order_code',
                'order_name' => 'required|string|max:255',
                'notes' => 'nullable|string'
            ]);

            $orderImport = OrderImport::create([
                'order_code' => $validated['order_code'],
                'order_name' => $validated['order_name'],
                'notes' => $validated['notes']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thêm lô hàng thành công',
                'data' => $orderImport
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showOrder($id)
    {
        try {
            $orderImport = OrderImport::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $orderImport
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 404);
        }
    }


    public function getProduct($id)
    {
        $product = Product::with([
            'categories',
            'variants.attributeValues.attribute'
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

    public function attributesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'is_variant' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $existingAttribute = Attribute::where('name', $request->name)
            ->where('slug', $request->slug)
            ->first();

        if ($existingAttribute) {

            $existingValue = AttributeValue::where('attribute_id', $existingAttribute->id)
                ->where('value', $request->value)
                ->first();

            if ($existingValue) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Thuộc tính '$request->name' với slug '$request->slug' và giá trị '$request->value' đã tồn tại!");
            }
        }


        $attribute = new Attribute();
        $attribute->name = $request->name;
        $attribute->slug = $request->slug;
        $attribute->is_variant = 1;
        $attribute->is_active = $request->has('is_active') ? $request->is_active : 1;
        $attribute->save();

        $attributeValue = new AttributeValue();
        $attributeValue->attribute_id = $attribute->id;
        $attributeValue->value = $request->value;
        $attributeValue->is_active = 1;
        $attributeValue->save();

        return redirect()->route('attributes.list')->with('success', 'Thuộc tính đã được thêm!');
    }

    public function toggleStatus(Request $request, Attribute $attribute)
    {
        try {
            $attribute->update([
                'is_active' => $request->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => $request->is_active ?
                    'Thuộc tính đã được hiển thị' :
                    'Thuộc tính đã được ẩn'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái'
            ], 500);
        }
    }


    // client
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
            ->with('variants')
            ->limit(10)
            ->get();

        return view('client.product.productct', compact(
            'product',
            'categories',
            'brands',
            'categoryTypes',
            'productGallery',
            'productGallery2',
            'carts',
            'subtotal',
            'relatedProducts',
            'min_variant_price'
        ));
    }




    // nhập 
    public function import()
    {
        $products = Product::with('variants')
            ->where('is_active', 1)
            ->get();

        $importedProducts = ProductImport::with(['details.product', 'details'])
            ->orderBy('imported_at', 'desc')
            ->get();
        $importedProductIds = $importedProducts->pluck('details')->flatten()->pluck('product_id')->unique()->toArray();
        $importedVariantIds = $importedProducts->pluck('details')->flatten()->pluck('product_variant_id')->unique()->toArray();
        $importedProductsList = $products->filter(function ($product) use ($importedProductIds, $importedVariantIds) {
            return in_array($product->id, $importedProductIds) || $product->variants->pluck('id')->intersect($importedVariantIds)->isNotEmpty();
        });
        $notImportedProductsList = $products->filter(function ($product) use ($importedProductIds, $importedVariantIds) {
            return !in_array($product->id, $importedProductIds) && $product->variants->pluck('id')->diff($importedVariantIds)->isNotEmpty();
        });

        return view('admin.products.import', compact('products', 'importedProducts', 'importedProductsList', 'notImportedProductsList', 'importedVariantIds'));
    }

    public function search(Request $request)
    {
        $query = ProductImport::query();

        if ($request->from_date) {
            $query->whereDate('imported_at', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->whereDate('imported_at', '<=', $request->to_date);
        }
        if ($request->imported_by) {
            $query->where('imported_by', 'LIKE', '%' . $request->imported_by . '%');
        }

        $results = $query->with('details')->get()->map(function ($import) {
            return [
                'id' => $import->id,
                'imported_at' => $import->imported_at,
                'imported_by' => $import->imported_by,
                'total_loss' => number_format($import->details->sum(fn($d) => $d->price * $d->quantity), 0, ',', '.'),
                'total_quantity' => $import->details->sum('quantity'),
                'status' => $import->is_active,
            ];
        });

        return response()->json($results);
    }


    public function importStore(Request $request)
    {
        $request->validate([
            'import_at' => 'required|date',
            'products' => 'required|array',
            'variants' => 'required|array',
            'import_prices' => 'required|array',
            'import_prices.*' => 'required|numeric|min:0',
            'quantities.*' => 'required|integer|min:1',
        ]);

        $products = $request->input('products');
        $variants = $request->input('variants');
        $importPrices = $request->input('import_prices');
        $quantities = $request->input('quantities', []);
        $prices = $request->input('prices');
        $salePrices = $request->input('sale_prices');
        $importAt = $request->input('import_at');
        $name_vars = $request->input('name_vars');
        $sale_price_start_at = $request->input('sale_price_start_at');
        $sale_price_end_at = $request->input('sale_price_end_at');
        $isActive = auth()->user()->role_id == 3 ? 1 : 0;

        $import = ProductImport::create([
            'user_id' => auth()->id(),
            'imported_by' => auth()->user()->fullname ?? 'Unknown',
            'imported_at' => $importAt,
            'is_active' => $isActive,
        ]);

        Product::whereIn('id', $products)->update([
            'import_at' => $importAt,
            'updated_at' => now(),
        ]);

        $totalAmount = 0;
        $totalQuantity = 0;

        foreach ($variants as $index => $variantId) {
            if (isset($importPrices[$variantId])) {
                $quantity = $quantities[$variantId] ?? 1;
                $productId = ProductVariant::find($variantId)->product_id;
                $import->details()->create([
                    'import_id' => $import->id,
                    'product_id' => $productId,
                    'quantity' => $quantities[$variantId],
                    'name_vari' => $name_vars[$variantId],
                    'price' => $importPrices[$variantId],
                ]);

                ProductVariant::where('id', $variantId)->update([
                    'import_price' => $importPrices[$variantId],
                    'price' => $prices[$variantId] ?? 0,
                    'sale_price' => $salePrices[$variantId] ?? 0,
                    'stock' => DB::raw("stock + $quantity"),
                    'sale_price_start_at' => $sale_price_start_at[$variantId],
                    'sale_price_end_at' => $sale_price_end_at[$variantId],
                    'updated_at' => now(),
                ]);
                $totalAmount += $importPrices[$variantId] * $quantity;
                $totalQuantity += $quantity;
            }
        }

        if ($isActive == 0) {
            $admins = User::where('role_id', 3)->get();
            if ($admins->isEmpty()) {
                \Log::warning("Không tìm thấy admin để gửi thông báo cho yêu cầu nhập hàng từ user " . auth()->id());
            } else {
                $confirmUrl = route('products.import.confirm', $import->id);
                $rejectUrl = route('products.import.reject', $import->id);

                foreach ($admins as $admin) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'title' => "Người dùng " . (auth()->user()->fullname ?? 'Unknown') . " đã nhập một đơn hàng",
                        'content' => "Số tiền: " . number_format($totalAmount, 0, ',', '.') . " VNĐ - Số lượng hàng nhập: $totalQuantity",
                        'type' => 'import_pending',
                        'data' => [
                            'import_id' => $import->id,
                            'user_id' => auth()->id(),
                            'user_name' => auth()->user()->fullname ?? 'Unknown',
                            'total_amount' => $totalAmount,
                            'total_quantity' => $totalQuantity,
                            'actions' => [
                                'confirm_request' => $confirmUrl,
                                'reject_request' => $rejectUrl,
                            ],
                        ],
                        'is_read' => 0,
                    ]);
                }
            }
        }

        return redirect()->route('products.import')->with('success', 'Đã nhập hàng và cập nhật giá/biến thể thành công!');
    }



    public function checkNotifications(Request $request)
    {
        if (!auth()->check() || auth()->user()->role_id != 3) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $lastChecked = $request->input('last_checked');
        $pendingImports = ProductImport::where('is_active', 0)
            ->where('created_at', '>', $lastChecked)
            ->get();

        $imports = $pendingImports->map(function ($import) {
            return [
                'message' => 'Bạn đang có một đơn hàng chờ xác nhận.',
                'import_id' => $import->id,
                'imported_at' => $import->imported_at,
                'imported_by' => $import->imported_by,
            ];
        })->toArray();

        return response()->json(['imports' => $imports]);
    }

    public function rejectImport(Request $request, $id)
    {
        $import = ProductImport::findOrFail($id);

        if (auth()->user()->role_id != 3) {
            return redirect()->back()->with('error', 'Bạn không có quyền từ chối đơn nhập hàng.');
        }
        $import->update(['is_active' => 2]);
        $notificationId = $request->input('notification_id');
        $notification = Notification::find($notificationId);
        $notification->is_read = 1;
        $notification->save();
        return redirect()->back()->with('success', 'Đã từ chối đơn nhập hàng thành công!');
    }

    public function confirmImport(Request $request, $id)
    {
        $import = ProductImport::findOrFail($id);

        if (auth()->user()->role_id != 3) {
            return redirect()->back()->with('error', 'Bạn không có quyền xác nhận đơn nhập hàng.');
        }

        $import->update(['is_active' => 1]);
        $notificationId = $request->input('notification_id');
        $notification = Notification::find($notificationId);
        $notification->is_read = 1;
        $notification->save();
        return redirect()->back()->with('success', 'Đã xác nhận đơn nhập hàng thành công!');
    }



    // nhập v2 
    public function createImport()
    {
        $products = Product::where('is_active', 1)
            ->with(['variants.attributeValues.attribute', 'attributeValues.attribute'])
            ->get();
        $suppliers = Supplier::all();
        $orderImport = OrderImport::where(function ($query) {
            $query->where('created_at', '>=', now()->subDays(2));
        })->get();
        return view('admin.imports.create', compact('products', 'suppliers', 'orderImport'));
    }

    public function showSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        return response()->json($supplier);
    }

    public function storeSupplier(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        $supplier = Supplier::create($validated);

        return response()->json($supplier);
    }

    // thêm mới nhập 
    public function storeImport(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'import_date' => 'required|date',
                'supplier_id' => 'required|exists:suppliers,id',
                'products' => 'required|json',
                'proof_files' => 'required|array',
                'proof_files.*' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
                'order_import_id' => 'required|exists:order_imports,id'
            ]);

            $orderImport = OrderImport::findOrFail($request->order_import_id);
            $products = json_decode($request->products, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Dữ liệu sản phẩm không hợp lệ');
            }

            $fileNames = [];
            if ($request->hasFile('proof_files')) {
                foreach ($request->file('proof_files') as $file) {
                    $fileName = 'import_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('upload/imports'), $fileName);
                    $fileNames[] = $fileName;
                }
            }

            $import = Import::create([
                'import_code' => $orderImport->order_code,
                'import_date' => $request->import_date,
                'user_id' => auth()->id(),
                'supplier_id' => $request->supplier_id,
                'is_confirmed' => false,
                'total_quantity' => 0,
                'total_price' => 0,
                'proof_image' => json_encode($fileNames)
            ]);

            $totalImportQuantity = 0;
            $totalImportPrice = 0;

            foreach ($products as $productId => $productData) {
                if (!isset($productData['variants']) || !is_array($productData['variants'])) {
                    throw new \Exception('Dữ liệu biến thể không hợp lệ');
                }
                $product = Product::findOrFail($productId);

                $firstVariant = reset($productData['variants']);
                $manufactureDate = $firstVariant['manufacture_date'] ?? null;
                $expiryDate = $firstVariant['expiry_date'] ?? null;

                $importProduct = ImportProduct::create([
                    'import_id' => $import->id,
                    'product_id' => $productId,
                    'product_name' => $product->name,
                    'quantity' => 0,
                    'total_price' => 0,
                    'manufacture_date' => $manufactureDate,
                    'expiry_date' => $expiryDate
                ]);

                $productTotalQuantity = 0;
                $productTotalPrice = 0;

                foreach ($productData['variants'] as $variantId => $variantData) {
                    $variant = ProductVariant::findOrFail($variantId);

                    $quantity = (int)$variantData['quantity'];
                    $price = (float)$variantData['price'];
                    $totalPrice = $quantity * $price;

                    ImportProductVariant::create([
                        'import_product_id' => $importProduct->id,
                        'product_variant_id' => $variantId,
                        'variant_name' => $this->getVariantName($variant),
                        'quantity' => $quantity,
                        'import_price' => $price,
                        'total_price' => $totalPrice
                    ]);

                    $productTotalQuantity += $quantity;
                    $productTotalPrice += $totalPrice;
                }

                $importProduct->update([
                    'quantity' => $productTotalQuantity,
                    'total_price' => $productTotalPrice
                ]);

                $totalImportQuantity += $productTotalQuantity;
                $totalImportPrice += $productTotalPrice;
            }

            $import->update([
                'total_quantity' => $totalImportQuantity,
                'total_price' => $totalImportPrice
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Import created successfully',
                'data' => $import
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            if (!empty($fileNames)) {
                foreach ($fileNames as $fileName) {
                    if (file_exists(public_path('upload/imports/' . $fileName))) {
                        unlink(public_path('upload/imports/' . $fileName));
                    }
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Error creating import: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getVariantName($variant)
    {
        return $variant->attributeValues()
            ->with('attribute')
            ->get()
            ->map(function ($value) {
                return $value->attribute->name . ': ' . $value->attribute->slug . $value->value;
            })
            ->join(', ');
    }

    public function indexImport()
    {
        $imports = Import::with(['user', 'supplier'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.imports.index', compact('imports'));
    }

    public function getDetail(Import $import)
    {
        $import->load([
            'user',
            'supplier',
            'importProducts.variants'
        ]);

        return response()->json([
            'success' => true,
            'import' => $import
        ]);
    }
}
