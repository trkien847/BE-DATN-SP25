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
use App\Models\Comment;
use App\Models\Import;
use App\Models\ImportProduct;
use App\Models\ImportProductVariant;
use App\Models\Notification;
use App\Models\OrderImport;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
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
                'attributeValues.attribute',
                'importProducts.import.user',
                'importProducts.importProductVariants.productVariant'
            ])
            ->where('is_active', 1)
            ->withSum('variants', 'stock')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'LIKE', '%' . $search . '%');
            })
            ->whereHas('importProducts.importProductVariants', function ($query) {})
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

        try {
            $user = Auth::user();

            if ($user->role_id !== 3) {
                DB::beginTransaction();

                $pendingData = [
                    'brand_id' => $request->brand_id,
                    'name' => $request->name,
                    'content' => strip_tags($request->content),
                    'sku' => $request->sku,
                    'category_id' => $request->category_id,
                    'category_type_id' => $request->category_type_id,
                    'thumbnail' => null,
                    'images' => [],
                    'variants' => $request->has('variants') ? $request->variants : null,
                    'variant_prices' => $request->variant_prices ?? []
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
                    'product_id' => null,
                    'user_id' => $user->id,
                    'action_type' => 'create',
                    'data' => $pendingData,
                ]);

                $admins = User::where('role_id', 3)->get();

                if ($admins->isEmpty()) {
                    throw new \Exception('Không tìm thấy admin để xử lý yêu cầu');
                }

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
                                'view_details' => route('products.pending-update-detail', $pendingUpdate->id),
                                'approve_request' => route('products.approve-pending', $pendingUpdate->id),
                                'reject_request' => route('products.reject-pending', $pendingUpdate->id),
                            ],
                        ],
                        'is_read' => 0,
                    ]);
                }

                DB::commit();
                return redirect()->route('products.list')
                    ->with('success', 'Yêu cầu thêm sản phẩm đã được gửi, chờ phê duyệt!');
            } else {
                DB::beginTransaction();
                $product = Product::create([
                    'brand_id' => $request->brand_id,
                    'name' => $request->name,
                    'views' => 0,
                    'content' => $request->content,
                    'sku' => $request->sku,
                    'is_active' => 1
                ]);

                if ($request->hasFile('thumbnail')) {
                    $image = $request->file('thumbnail');
                    $imageName = time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('upload'), $imageName);
                    $product->thumbnail = $imageName;
                    $product->save();
                }

                CategoryProduct::create([
                    'category_id' => $request->category_id,
                    'product_id' => $product->id
                ]);

                CategoryTypeProduct::create([
                    'product_id' => $product->id,
                    'category_type_id' => $request->category_type_id
                ]);

                if ($request->hasFile('image')) {
                    foreach ($request->file('image') as $image) {
                        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('upload'), $imageName);

                        ProductGalleries::create([
                            'product_id' => $product->id,
                            'image' => $imageName
                        ]);
                    }
                }

                if ($request->has('variants')) {
                    $this->createProductVariants($product, $request);
                }

                DB::commit();
                return redirect()->route('products.list')->with('success', 'Sản phẩm đã được tạo thành công!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    private function createProductVariants($product, $request)
    {
        try {
            $existingAttributes = [];
            $variantCount = 0;


            if (!$request->has('variants') || !is_array($request->variants)) {
                throw new \Exception('Dữ liệu biến thể không hợp lệ');
            }

            foreach ($request->variants as $shapeId => $weights) {

                $shapeAttribute = AttributeValue::where('id', $shapeId)
                    ->where('attribute_id', 12)
                    ->where('is_active', 1)
                    ->first();

                if (!$shapeAttribute) {
                    continue;
                }

                foreach ($weights as $weightId) {

                    $weightAttribute = AttributeValue::where('id', $weightId)
                        ->where('attribute_id', 14)
                        ->where('is_active', 1)
                        ->first();

                    if (!$weightAttribute) {
                        continue;
                    }


                    $variantPrices = $request->input("variant_prices.{$shapeId}-{$weightId}");
                    if (!$variantPrices || !isset($variantPrices['price'])) {
                        continue;
                    }

                    DB::beginTransaction();
                    try {

                        $productVariant = ProductVariant::create([
                            'product_id' => $product->id,
                            'price' => $variantPrices['price'],
                            'sale_price' => $variantPrices['sale_price'] ?? null,
                            'sale_price_start_at' => $variantPrices['sale_start_at'] ?? null,
                            'sale_price_end_at' => $variantPrices['sale_end_at'] ?? null,
                            'stock' => 0,
                        ]);


                        foreach ([$shapeId, $weightId] as $attributeValueId) {

                            AttributeValueProductVariant::create([
                                'product_variant_id' => $productVariant->id,
                                'attribute_value_id' => $attributeValueId,
                            ]);


                            if (!in_array($attributeValueId, $existingAttributes)) {
                                AttributeValueProduct::firstOrCreate(
                                    [
                                        'product_id' => $product->id,
                                        'attribute_value_id' => $attributeValueId,
                                    ]
                                );
                                $existingAttributes[] = $attributeValueId;
                            }
                        }

                        $variantCount++;
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                    }
                }
            }

            if ($variantCount === 0) {
                throw new \Exception('Không có biến thể nào được tạo thành công');
            }

            return $variantCount;
        } catch (\Exception $e) {
            throw $e;
        }
    }


    public function edit($id)
    {
        try {
            $product = Product::query()
                ->with([
                    'brand',
                    'categories',
                    'categoryTypes',
                    'variants.attributeValues.attribute',
                    'attributeValues.attribute',
                    'variants' => function ($query) {
                        $query->with(['attributeValues' => function ($q) {
                            $q->select('attribute_values.*');
                        }]);
                    }
                ])
                ->findOrFail($id);

            $attributes = Attribute::with(['values' => function ($query) {
                $query->where('is_active', 1);
            }])->get();

            $brands = Brand::all();
            $categories = Category::with('categoryTypes')->get();
            $productGallery = ProductGalleries::where('product_id', $id)->get();
            $categoryTypes = CategoryType::whereIn('category_id', $product->categories->pluck('id'))->get();
            $weightAttribute = Attribute::where('name', 'Khối lượng')->first();
            $variantData = [];
            $selectedVariants = [];
            $weightValues = [];

            if ($weightAttribute) {
                $weightValues = $weightAttribute->values->map(function ($value) {
                    $parts = explode(' ', $value->value);
                    $unit = end($parts);
                    $number = implode(' ', array_slice($parts, 0, -1));
                    return [
                        'id' => $value->id,
                        'value' => $value->value,
                        'unit' => $unit,
                        'number' => $number,
                    ];
                })->toArray();
            }

            foreach ($product->variants as $variant) {
                $attributeValues = $variant->attributeValues->groupBy('attribute.id');

                $shape = $attributeValues->get(12)?->first();
                $weight = $attributeValues->get(14)?->first();

                if ($shape && $weight) {
                    $variantData[$variant->id] = [
                        'shape_id' => $shape->id,
                        'shape_name' => $shape->value,
                        'weight_id' => $weight->id,
                        'weight_name' => $weight->value,
                        'price' => $variant->price,
                        'sale_price' => $variant->sale_price,
                        'sale_price_start_at' => $variant->sale_price_start_at ?
                            Carbon::parse($variant->sale_price_start_at)->format('Y-m-d\TH:i') : null,
                        'sale_end_at' => $variant->sale_price_end_at ?
                            Carbon::parse($variant->sale_price_end_at)->format('Y-m-d\TH:i') : null,
                        'variant_id' => $variant->id
                    ];

                    $selectedVariants[] = [
                        'shape_id' => $shape->id,
                        'weight_id' => $weight->id,
                    ];
                }
            }

            return view('admin.products.productUpdateForm', compact(
                'product',
                'categories',
                'brands',
                'categoryTypes',
                'productGallery',
                'attributes',
                'variantData',
                'selectedVariants',
                'weightValues'
            ));
        } catch (\Exception $e) {
            return redirect()
                ->route('products.list')
                ->with('error', 'Có lỗi xảy ra khi tải thông tin sản phẩm: ' . $e->getMessage());
        }
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
        $product = Product::findOrFail($id);

        if ($user->role_id !== 3) {
            $pendingData = [
                'brand_id' => $request->brand_id,
                'name' => $request->name,
                'content' => strip_tags($request->content),
                'sku' => $request->sku,
                'category_id' => $request->category_id,
                'category_type_id' => $request->category_type_id,
                'thumbnail' => null,
                'images' => [],
                'variants' => $request->has('variants') ? $request->variants : null,
                'variant_prices' => $request->variant_prices ?? []
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
            } else {
                $detailUrl = route('products.pending-update-detail', $pendingUpdate->id);
                $approveUrl = route('products.approve-pending', $pendingUpdate->id);
                $rejectUrl = route('products.reject-pending', $pendingUpdate->id);

                foreach ($admins as $admin) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'title' => "Nhân viên {$user->fullname} đã yêu cầu sửa sản phẩm",
                        'content' => "Tên sản phẩm: {$request->name}",
                        'type' => 'product_pending_update',
                        'data' => [
                            'pending_id' => $pendingUpdate->id,
                            'requester_id' => $user->id,
                            'requester_name' => $user->fullname,
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

            return redirect()->route('products.list')->with('success', 'Yêu cầu chỉnh sửa sản phẩm đã được gửi, chờ phê duyệt!');
        }
        $product->brand_id = $request->brand_id;
        $product->name = $request->name;
        $product->content = $request->content;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->sell_price = $request->sell_price;
        $product->sale_price = $request->sale_price;
        $product->is_active = 1;

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail && File::exists(public_path('upload/' . $product->thumbnail))) {
                File::delete(public_path('upload/' . $product->thumbnail));
            }
            $image = $request->file('thumbnail');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload'), $imageName);
            $product->thumbnail = $imageName;
        }

        $product->save();
        $categoryProduct = CategoryProduct::updateOrCreate(
            ['product_id' => $id],
            ['category_id' => $request->category_id]
        );
        $categoryTypeProduct = CategoryTypeProduct::updateOrCreate(
            ['product_id' => $id],
            ['category_type_id' => $request->category_type_id]
        );

        if ($request->hasFile('image')) {
            ProductGalleries::where('product_id', $id)->delete();
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
            $existingAttributes = [];
            foreach ($request->variants as $shapeId => $weights) {
                $shapeAttribute = AttributeValue::where('id', $shapeId)->where('attribute_id', 12)->first();
                if (!$shapeAttribute) {
                    continue;
                }

                foreach ($weights as $weightId) {
                    $weightAttribute = AttributeValue::where('id', $weightId)->where('attribute_id', 14)->first();
                    if (!$weightAttribute) {
                        continue;
                    }

                    $variant = ProductVariant::where('product_id', $product->id)
                        ->whereHas('attributeValues', fn($q) => $q->where('attribute_value_id', $shapeId))
                        ->whereHas('attributeValues', fn($q) => $q->where('attribute_value_id', $weightId))
                        ->first();

                    $variantPriceData = $request->input("variant_prices.{$shapeId}-{$weightId}");

                    if (!$variant) {
                        $variant = ProductVariant::create([
                            'product_id' => $product->id,
                            'price' => $variantPriceData['price'] ?? null,
                            'sale_price' => $variantPriceData['sale_price'] ?? null,
                            'sale_price_start_at' => $variantPriceData['sale_start_at'] ?? null,
                            'sale_price_end_at' => $variantPriceData['sale_end_at'] ?? null,
                            'stock' => 0,
                        ]);

                        AttributeValueProductVariant::create([
                            'product_variant_id' => $variant->id,
                            'attribute_value_id' => $shapeId,
                        ]);
                        AttributeValueProductVariant::create([
                            'product_variant_id' => $variant->id,
                            'attribute_value_id' => $weightId,
                        ]);

                        if (!in_array($shapeId, $existingAttributes)) {
                            AttributeValueProduct::create([
                                'product_id' => $product->id,
                                'attribute_value_id' => $shapeId,
                            ]);
                            $existingAttributes[] = $shapeId;
                        }
                        if (!in_array($weightId, $existingAttributes)) {
                            AttributeValueProduct::create([
                                'product_id' => $product->id,
                                'attribute_value_id' => $weightId,
                            ]);
                            $existingAttributes[] = $weightId;
                        }
                    } else {
                        $variant->update([
                            'price' => $variantPriceData['price'] ?? $variant->price,
                            'sale_price' => $variantPriceData['sale_price'] ?? $variant->sale_price,
                            'sale_price_start_at' => $variantPriceData['sale_start_at'] ?? $variant->sale_price_start_at,
                            'sale_price_end_at' => $variantPriceData['sale_end_at'] ?? $variant->sale_price_end_at,
                        ]);
                    }

                    $variantIds[] = $variant->id;
                }
            }
        }

        ProductVariant::where('product_id', $product->id)->whereNotIn('id', $variantIds)->delete();

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
        $user = auth()->user();
        if ($user->role_id !== 3) {
            return redirect()->back()->with('error', 'Bạn không có quyền truy cập!');
        }

        $pendingUpdate = ProductPendingUpdate::with('user')->findOrFail($pendingId);
        $originalProduct = $pendingUpdate->product_id ? Product::find($pendingUpdate->product_id) : null;
        $brand = isset($pendingUpdate->data['brand_id']) ? Brand::find($pendingUpdate->data['brand_id']) : null;
        $notificationId = $request->input('notification_id');

        if ($notificationId) {
            Notification::where('id', $notificationId)
                ->where('user_id', $user->id)
                ->update(['is_read' => 1]);
        }

        return view('admin.products.pending-update-detail', compact('pendingUpdate', 'originalProduct', 'notificationId', 'brand'));
    }

    public function approvePendingUpdate(Request $request, $pendingId)
    {
        try {
            DB::beginTransaction();

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
                $product = Product::create([
                    'brand_id' => $data['brand_id'],
                    'name' => $data['name'],
                    'content' => $data['content'],
                    'sku' => $data['sku'],
                    'price' => $data['price'] ?? 0,
                    'sell_price' => $data['sell_price'] ?? 0,
                    'sale_price' => $data['sale_price'] ?? 0,
                    'thumbnail' => $data['thumbnail'],
                    'is_active' => 1
                ]);

                CategoryProduct::create([
                    'product_id' => $product->id,
                    'category_id' => $data['category_id'],
                ]);

                CategoryTypeProduct::create([
                    'product_id' => $product->id,
                    'category_type_id' => $data['category_type_id'],
                ]);
                if (!empty($data['images'])) {
                    $this->handleProductImages($product, $data['images']);
                }
                if (!empty($data['variants']) && !empty($data['variant_prices'])) {
                    $this->handleProductVariants($product, $data['variants'], $data['variant_prices']);
                }
            } else {
                $product = Product::findOrFail($pendingUpdate->product_id);
                $this->updateBasicProductInfo($product, $data);
                $this->updateProductCategories($product, $data);
                if (!empty($data['images'])) {
                    $this->handleProductImages($product, $data['images'], true);
                }

                if (!empty($data['variants']) && !empty($data['variant_prices'])) {
                    $this->handleProductVariants($product, $data['variants'], $data['variant_prices'], true);
                }
            }

            Notification::create([
                'user_id' => $pendingUpdate->user_id,
                'title' => "Yêu cầu thêm sản phẩm đã được chấp nhận",
                'content' => "Sản phẩm {$product->name} đã được duyệt bởi {$user->fullname}",
                'type' => 'product_approval_response',
                'data' => [
                    'product_id' => $product->id,
                    'status' => 'approved',
                    'actions' => [
                        'view_details' => route('products.edit', $product->id),
                        'acknowledge' => route('notifications.acknowledge', [
                            'type' => 'product_approval_response',
                            'id' => $product->id
                        ])
                    ]
                ],
                'is_read' => false
            ]);
            $pendingUpdate->delete();

            DB::commit();


            return redirect()->route('notifications.index')
                ->with('success', 'Đã duyệt thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    private function handleProductVariants($product, $variants, $variantPrices, $isUpdate = false)
    {
        $existingAttributes = [];
        $variantIds = [];

        foreach ($variants as $shapeId => $weights) {
            $shapeAttribute = AttributeValue::where('id', $shapeId)
                ->where('attribute_id', 12)
                ->where('is_active', 1)
                ->first();

            if (!$shapeAttribute) continue;

            foreach ($weights as $weightId) {
                $weightAttribute = AttributeValue::where('id', $weightId)
                    ->where('attribute_id', 14)
                    ->where('is_active', 1)
                    ->first();

                if (!$weightAttribute) continue;

                $priceKey = "{$shapeId}-{$weightId}";
                $variantPrice = $variantPrices[$priceKey] ?? null;

                if (!$variantPrice) continue;

                $variant = $isUpdate ?
                    $this->findExistingVariant($product, $shapeId, $weightId) :
                    ProductVariant::create(['product_id' => $product->id]);

                if ($variant) {
                    $variant->update([
                        'price' => $variantPrice['price'],
                        'sale_price' => $variantPrice['sale_price'] ?? null,
                        'sale_price_start_at' => $variantPrice['sale_start_at'] ?? null,
                        'sale_price_end_at' => $variantPrice['sale_end_at'] ?? null,
                        'stock' => $variant->stock ?? 0
                    ]);

                    foreach ([$shapeId, $weightId] as $attributeValueId) {
                        if (!in_array($attributeValueId, $existingAttributes)) {
                            AttributeValueProduct::firstOrCreate([
                                'product_id' => $product->id,
                                'attribute_value_id' => $attributeValueId
                            ]);
                            $existingAttributes[] = $attributeValueId;
                        }

                        AttributeValueProductVariant::firstOrCreate([
                            'product_variant_id' => $variant->id,
                            'attribute_value_id' => $attributeValueId
                        ]);
                    }

                    $variantIds[] = $variant->id;
                }
            }
        }

        if ($isUpdate && !empty($variantIds)) {
            ProductVariant::where('product_id', $product->id)
                ->whereNotIn('id', $variantIds)
                ->delete();
        }
    }


    private function updateProductCategories($product, $data)
    {
        if (!isset($data['category_id']) || !isset($data['category_type_id'])) {
            throw new \Exception('Dữ liệu danh mục không đầy đủ.');
        }

        if (!Category::find($data['category_id'])) {
            throw new \Exception('Danh mục không tồn tại.');
        }

        if (!CategoryType::find($data['category_type_id'])) {
            throw new \Exception('Loại danh mục không tồn tại.');
        }

        CategoryProduct::updateOrCreate(
            ['product_id' => $product->id],
            ['category_id' => $data['category_id']]
        );

        CategoryTypeProduct::updateOrCreate(
            ['product_id' => $product->id],
            ['category_type_id' => $data['category_type_id']]
        );
    }

    private function handleProductImages($product, $images, $isUpdate = false)
    {
        try {
            if ($isUpdate) {
                $existingImages = ProductGalleries::where('product_id', $product->id)->get();
                foreach ($existingImages as $existingImage) {
                    if (File::exists(public_path('upload/' . $existingImage->image))) {
                        File::delete(public_path('upload/' . $existingImage->image));
                    }
                    $existingImage->delete();
                }
            }

            if (is_array($images)) {
                foreach ($images as $image) {
                    if ($image instanceof \Illuminate\Http\UploadedFile) {
                        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('upload'), $imageName);
                    } else {
                        $imageName = $image;
                    }

                    ProductGalleries::create([
                        'product_id' => $product->id,
                        'image' => $imageName
                    ]);
                }
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function findExistingVariant($product, $shapeId, $weightId)
    {
        return ProductVariant::where('product_id', $product->id)
            ->whereHas('attributeValues', function ($query) use ($shapeId) {
                $query->where('attribute_value_id', $shapeId);
            })
            ->whereHas('attributeValues', function ($query) use ($weightId) {
                $query->where('attribute_value_id', $weightId);
            })
            ->first();
    }

    private function updateBasicProductInfo($product, $data)
    {
        $product->update([
            'brand_id' => $data['brand_id'],
            'name' => $data['name'],
            'content' => $data['content'],
            'sku' => $data['sku'],
            'price' => $data['price'] ?? $product->price,
            'sell_price' => $data['sell_price'] ?? $product->sell_price,
            'sale_price' => $data['sale_price'] ?? $product->sale_price,
            'thumbnail' => $this->handleThumbnailUpdate($product, $data['thumbnail']),
            'is_active' => 1
        ]);
    }

    private function handleThumbnailUpdate($product, $thumbnail)
    {
        if ($thumbnail && $product->thumbnail !== $thumbnail) {
            if ($product->thumbnail && File::exists(public_path('upload/' . $product->thumbnail))) {
                File::delete(public_path('upload/' . $product->thumbnail));
            }
            return $thumbnail;
        }
        return $product->thumbnail;
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
        $attributes = Attribute::with(['values' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->get();
        $groupedAttributes = $attributes->groupBy('name')->map(function ($group) {
            $firstAttribute = $group->first();
            return [
                'count' => $group->first()->values->count(),
                'firstAttribute' => $firstAttribute,
                'items' => $group
            ];
        });

        return view('admin.products.attributes', compact('groupedAttributes'));
    }

    public function storeAttribute(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:attributes',
            'is_active' => 'required|boolean',
        ]);

        $attribute = Attribute::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'is_variant' => true,
            'is_active' => $request->is_active,
        ]);

        return redirect()->back()->with('success', 'Thêm loại biến thể thành công!');
    }

    public function storeAttributeValue(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'variant_type' => 'required|string|in:Hình thù,Khối lượng',
            'unit' => 'required|string',
            'amount' => 'required_if:variant_type,Khối lượng|nullable|numeric|min:1',
            'is_active' => 'required|boolean',
        ]);

        $attribute = Attribute::find($request->attribute_id);
        if ($request->variant_type === 'Hình thù') {
            $value = $request->unit;
        } else {
            $value = $request->amount . ' ' . $request->unit;
        }
        $exists = AttributeValue::where('attribute_id', $request->attribute_id)
            ->where('value', $value)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Giá trị biến thể này đã tồn tại cho thuộc tính này!');
        }

        AttributeValue::create([
            'attribute_id' => $request->attribute_id,
            'value' => $value,
            'is_active' => $request->is_active,
        ]);

        return redirect()->back()->with('success', 'Thêm giá trị biến thể thành công!');
    }

    public function toggleStatus(Request $request, $id)
    {
        try {
            $attribute = AttributeValue::findOrFail($id);
            $attribute->is_active = $request->is_active;
            $attribute->save();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }


    // client
    public function productctad($id){
        $product = Product::query()
            ->with([
                'brand',
                'categories',
                'categoryTypes',
                'variants.attributeValues.attribute',
                'attributeValues.attribute',
                'importProducts.import.user', 
                'importProducts.import.supplier',
                'importProducts.importProductVariants.productVariant'
            ])->withSum('variants', 'stock')
            ->where('id', $id)->first();
        
            $productGallery = ProductGalleries::where('product_id', $id)->get();

            $images = [
                [
                    'src' => $product->thumbnail,
                    'alt' => $product->thumbnail_alt ?? 'Main product image',
                ],
            ];
        
            foreach ($productGallery as $galleryImage) {
                $images[] = [
                    'src' => $galleryImage->image,
                    'alt' => $galleryImage->alt ?? 'Gallery image',
                ];
            }

    return view('admin.products.chitiet', compact('product', 'productGallery', 'images'));
    }
    public function productct($id)
    {
        $user = auth()->user();
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

        $carts = collect();
        $subtotal = 0;
        $cart_count = 0;

        if ($user) {
            $carts = Cart::with([
                'product.brand',
                'product.categories',
                'product.categoryTypes',
                'product.variants.attributeValues.attribute',
                'product.attributeValues.attribute',
                'productVariant.attributeValues.attribute',
            ])->where('user_id', auth()->id())->get();

            $subtotal = $carts->sum(function ($cart) {
                $price = (!empty($cart->productVariant->sale_price) && $cart->productVariant->sale_price > 0)
                    ? $cart->productVariant->sale_price
                    : $cart->productVariant->price;
                return $cart->quantity * $price;
            });

            $cart_count = $carts->sum('quantity');
        }

        $cart_count = $carts->sum('quantity');
        $sevenDaysAgo = Carbon::now()->subDays(7);
        $productTop = OrderItem::with('product.variants')
            ->where('created_at', '>=', $sevenDaysAgo)
            ->select('product_id')
            ->selectRaw('SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(3)
            ->get();
        $comments = Comment::with(['user', 'replies.user'])
            ->where('product_id', $product->id)
            ->whereNull('parent_id')
            ->where('is_approved', true)
            ->latest()
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
            'min_variant_price',
            'cart_count',
            'productTop',
            'comments'
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

    //
    public function store(Request $request)
    {
        $request->validate([
            'import_at' => 'required|date',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
        ]);

        foreach ($request->products as $productId) {
            $product = Product::find($productId);
            if ($product) {
                $product->import_at = $request->import_at;
                $product->save();
            }
        }
    }
    //
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



    // nhập v2 
    public function createImport()
    {
        $products = Product::where('is_active', 1)
            ->with([
                'variants' => function ($query) {
                    $query->with(['attributeValues' => function ($q) {
                        $q->with('attribute')->orderBy('attribute_id');
                    }]);
                },
                'attributeValues.attribute'
            ])
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
                'proof_files.*' => 'required|file|mimes:jpeg,png,jpg,pdf',
                'order_import_id' => 'required|exists:order_imports,id'
            ]);

            $user = auth()->user();
            $isAdmin = $user->role_id === 3;

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
                'is_confirmed' => $isAdmin,
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

                    if ($isAdmin) {
                        $variant->update([
                            'import_price' => $price,
                            'stock' => DB::raw("stock + $quantity"),
                            'price' => $variantData['sell_price'] ?? $variant->price,
                            'sale_price' => $variantData['sale_price'] ?? $variant->sale_price,
                            'sale_price_start_at' => $variantData['sale_start_date'] ?? $variant->sale_price_start_at,
                            'sale_price_end_at' => $variantData['sale_end_date'] ?? $variant->sale_price_end_at,
                        ]);
                    }

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

            if (!$isAdmin) {
                $admins = User::where('role_id', 3)->get();
                foreach ($admins as $admin) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'title' => "Nhân viên {$user->fullname} đang yêu cầu xác nhận lô {$import->import_code}",
                        'content' => "Tổng số lượng: {$totalImportQuantity}, Tổng giá trị: " . number_format($totalImportPrice, 0, ',', '.') . " VNĐ",
                        'type' => 'import_confirmation',
                        'data' => [
                            'import_id' => $import->id,
                            'actions' => [
                                'view_details' => route('imports.show', $import->id),
                                'confirm' => route('imports.confirm', $import->id),
                                'cancel' => route('imports.cancel', $import->id)
                            ]
                        ],
                        'is_read' => false
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $isAdmin ? 'Nhập hàng thành công' : 'Yêu cầu nhập hàng đã được gửi đi',
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

    public function showImport(Import $import)
    {
        $import->load([
            'user',
            'supplier',
            'importProducts.variants',
            'importProducts.product'
        ]);

        return view('admin.imports.show', compact('import'));
    }

    public function confirmImport($id)
    {
        $import = Import::with('user')->findOrFail($id);
        $adminUser = auth()->user();

        if (!$import->is_confirmed) {
            DB::beginTransaction();
            try {
                $import->update(['is_confirmed' => true]);
                foreach ($import->importProducts as $importProduct) {
                    foreach ($importProduct->variants as $variant) {
                        $productVariant = ProductVariant::find($variant->product_variant_id);
                        if ($productVariant) {
                            $productVariant->update([
                                'import_price' => $variant->import_price,
                                'stock' => DB::raw("stock + {$variant->quantity}"),
                            ]);
                        }
                    }
                }

                Notification::where('type', 'import_confirmation')
                    ->where('data->import_id', $import->id)
                    ->update(['is_read' => true]);

                Notification::create([
                    'user_id' => $import->user_id,
                    'title' => "Yêu cầu nhập hàng đã được chấp nhận",
                    'content' => "Lô hàng #{$import->import_code} đã được xác nhận bởi {$adminUser->fullname}",
                    'type' => 'import_response',
                    'data' => [
                        'import_id' => $import->id,
                        'status' => 'confirmed',
                        'actions' => [
                            'view_details' => route('imports.show', $import->id),
                            'acknowledge' => route('notifications.acknowledge', ['type' => 'import_response', 'id' => $import->id])
                        ]
                    ],
                    'is_read' => false
                ]);

                DB::commit();
                return redirect()->back()->with('success', 'Yêu cầu nhập hàng đã được chấp nhận');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('success', 'lol');
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Đơn hàng đã được xử lý trước đó',
            'notification' => [
                'title' => 'Thông báo!',
                'icon' => 'warning',
                'text' => 'Đơn hàng này đã được xử lý trước đó',
                'confirmButtonText' => 'Đóng'
            ]
        ], 400);
    }

    public function cancelImport($id)
    {
        $import = Import::with('user')->findOrFail($id);
        $adminUser = auth()->user();

        if (!$import->is_confirmed) {
            DB::beginTransaction();
            try {
                Notification::where('type', 'import_confirmation')
                    ->where('data->import_id', $import->id)
                    ->update(['is_read' => true]);

                Notification::create([
                    'user_id' => $import->user_id,
                    'title' => "Yêu cầu nhập hàng đã bị từ chối",
                    'content' => "Lô hàng #{$import->import_code} đã bị từ chối bởi {$adminUser->fullname}",
                    'type' => 'import_response',
                    'data' => [
                        'import_id' => $import->id,
                        'status' => 'cancelled',
                        'actions' => [
                            'view_details' => route('imports.show', $import->id),
                            'acknowledge' => route('notifications.acknowledge', ['type' => 'import_response', 'id' => $import->id])
                        ]
                    ],
                    'is_read' => false
                ]);

                $import->delete();

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Đơn nhập hàng đã bị hủy',
                    'data' => [
                        'import_code' => $import->import_code,
                        'cancelled_by' => $adminUser->fullname,
                        'cancelled_at' => now()->format('H:i:s d/m/Y'),
                    ],
                    'notification' => [
                        'title' => 'Đã hủy!',
                        'icon' => 'success',
                        'text' => "Đơn nhập hàng #{$import->import_code} đã được hủy thành công (Được thiết kế bởi TG VN)",
                        'confirmButtonText' => 'Đồng ý',
                        'timer' => 3000
                    ]
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra',
                    'error' => [
                        'type' => class_basename($e),
                        'message' => $e->getMessage(),
                        'file' => basename($e->getFile()),
                        'line' => $e->getLine()
                    ],
                    'notification' => [
                        'title' => 'Lỗi!',
                        'icon' => 'error',
                        'text' => 'Không thể hủy đơn hàng. Vui lòng thử lại sau.',
                        'confirmButtonText' => 'Đóng',
                        'showCancelButton' => false
                    ]
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Đơn hàng đã được xử lý trước đó',
            'notification' => [
                'title' => 'Thông báo!',
                'icon' => 'warning',
                'text' => 'Đơn hàng này đã được xử lý trước đó',
                'confirmButtonText' => 'Đóng'
            ]
        ], 400);
    }

    public function acknowledgeNotification(Request $request, $type, $id)
    {
        try {
            if (!in_array($type, ['import_response', 'product_approval_response'])) {
                throw new \InvalidArgumentException('Loại thông báo không hợp lệ');
            }
            $updated = Notification::where([
                'user_id' => auth()->id(),
                'type' => $type,
                'is_read' => false
            ])->where(function ($query) use ($type, $id) {
                if ($type === 'import_response') {
                    $query->where('data->import_id', $id);
                } else {
                    $query->where('data->product_id', $id);
                }
            })->update(['is_read' => true]);

            if (!$updated) {
                throw new \Exception('Không tìm thấy thông báo hoặc đã được đánh dấu trước đó');
            }
            return redirect()->back()->with('success', 'Thông báo đã được đánh dấu là đã đọc!');
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'notification' => [
                    'title' => 'Lỗi!',
                    'icon' => 'error',
                    'text' => 'Loại thông báo không hợp lệ',
                    'confirmButtonText' => 'Đóng'
                ]
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra',
                'error' => [
                    'type' => class_basename($e),
                    'message' => $e->getMessage(),
                    'file' => basename($e->getFile()),
                    'line' => $e->getLine()
                ],
                'notification' => [
                    'title' => 'Lỗi!',
                    'icon' => 'error',
                    'text' => 'Không thể cập nhật trạng thái thông báo',
                    'confirmButtonText' => 'Đóng'
                ]
            ], 500);
        }
    }

    private function getVariantName($variant)
    {
        return $variant->attributeValues()
            ->with('attribute')
            ->get()
            ->filter(function ($value) {
                return in_array($value->attribute->name, ['Hình thù', 'Khối lượng']);
            })
            ->sortBy(function ($value) {
                return $value->attribute->name === 'Hình thù' ? 0 : 1;
            })
            ->map(function ($value) {
                return $value->value;
            })
            ->join(' ');
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
