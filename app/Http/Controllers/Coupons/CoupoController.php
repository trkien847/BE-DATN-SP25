<?php

namespace App\Http\Controllers\Coupons;

use App\Models\User;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Category;
use App\Models\CouponUser;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\CouponRestriction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Notifications\CouponCreatedNotification;


class CoupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Coupon::with(['restriction', 'users']); // Eager load để tránh N+1 query

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('code', 'LIKE', "%$search%")
                ->orWhere('title', 'LIKE', "%$search%")
                ->orWhere('description', 'LIKE', "%$search%");
        }

        $coupons = $query->where('status', 'approved')->orderBy('created_at', 'desc')->paginate(10);
        // status	enum('pending', 'approved', 'rejected')	utf8mb4_unicode_ci	
        // Lấy tên sản phẩm và danh mục theo valid_products & valid_categories
        foreach ($coupons as $coupon) {
            if ($coupon->restriction) {
                $productIds = json_decode($coupon->restriction->valid_products, true) ?? [];
                $categoryIds = json_decode($coupon->restriction->valid_categories, true) ?? [];

                $coupon->productNames = Product::whereIn('id', $productIds)->pluck('name')->toArray();
                $coupon->categoryNames = Category::whereIn('id', $categoryIds)->pluck('name')->toArray();
            } else {
                $coupon->productNames = [];
                $coupon->categoryNames = [];
            }
        }

        return view('admin.coupons.list', compact('coupons'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $products = Product::all();
        $users = User::all();
        return view('admin.coupons.coupon.add', compact('categories', 'products', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CouponRequest $request)
    {

        DB::beginTransaction();

        try {

            // Tạo mã giảm giá
            $coupon = Coupon::create([
                'code' => $request->code,
                'title' => $request->title,
                'description' => $request->description,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value,
                'usage_limit' => $request->usage_limit ?? null,
                'usage_count' => 0,
                'start_date' => $request->start_date ?? null,
                'end_date' => $request->end_date ?? null,
                'status' => 'pending',
            ]);

            // Tạo CouponRestriction

            try {
                $maGiamGia = CouponRestriction::create([

                    'coupon_id' => $coupon->id,
                    'min_order_value' => $request->filled('min_order_value') ? $request->min_order_value : 1000,
                    'max_discount_value' => $request->filled('max_discount_value') ? $request->max_discount_value : 2000,
                    'valid_categories' => json_encode(array_map('intval', (array) ($request->valid_categories ?? []))),
                    'valid_products' => json_encode(array_map('intval', (array) ($request->valid_products ?? []))),
                ]);

            } catch (\Exception $e) {
                Log::error("Lỗi khi tạo CouponRestriction: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            }



            //Gửi thông báo đến Admin bằng Notification
            $adminUsers = User::where('role_id', 3)->get(); // Giả sử role_id = 3 là Admin
            $user = auth()->user();

            foreach ($adminUsers as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => "Nhân viên {$user->fullname} đã yêu cầu thêm mã giảm giá",
                    'content' => "Tên mã giảm giá: {$request->title} {$request->code}",
                    'type' => 'coupon_pending_create',
                    'data' => json_encode([
                        'pending_id' => $coupon->id,
                        'requester_id' => $user->id,
                        'requester_name' => $user->fullname,
                        'coupon_name' => $request->title,
                        'actions' => [
                            'view_details' => route('coupons.pending-update-detail', $coupon->id),
                            'approve_request' => route('coupons.approve', $coupon->id),
                            'reject_request' => route('coupons.rejected', $coupon->id),
                        ],

                    ]),
                    'is_read' => 0,
                ]);
            }

            DB::commit();
            Log::info("Mã giảm giá '{$coupon->code}' đã được tạo thành công.");

            return redirect()->route('coupons.list')->with('success', 'Thêm mã giảm giá thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error("Lỗi khi thêm mã giảm giá: " . $e->getMessage());
            echo $e->getMessage();

            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $coupon = Coupon::with('restriction', 'users')->findOrFail($id);
        $products = Product::all();
        $categories = Category::all();
        $users = User::all();
        $restriction = $coupon->restriction;

        // Lấy danh sách ID của sản phẩm và danh mục được áp dụng
        $validProducts = json_decode(optional($restriction)->valid_products, true) ?? [];
        $validCategories = json_decode(optional($restriction)->valid_categories, true) ?? [];
        $appliedUsers = $coupon->users->pluck('id')->toArray();

        // Lấy giá trị min_order_value và max_discount_value
        $minOrderValue = optional($restriction)->min_order_value;

        $maxDiscountValue = optional($restriction)->max_discount_value;

        return view('admin.coupons.coupon.edit', compact(
            'coupon',
            'products',
            'categories',
            'users',
            'restriction',
            'validProducts',
            'validCategories',
            'appliedUsers',
            'minOrderValue',
            'maxDiscountValue'
        ));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $coupon = Coupon::findOrFail($id);

            // Cập nhật thông tin mã giảm giá
            $coupon->update([
                'code' => $request->code,
                'title' => $request->title,
                'description' => $request->description,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value,
                'usage_limit' => $request->filled('usage_limit') ? $request->usage_limit : 0,
                'start_date' => $request->start_date ?? null,
                'end_date' => $request->end_date ?? null,
            ]);

            // Xử lý ràng buộc mã giảm giá
            $validCategories = is_array($request->valid_categories) ? $request->valid_categories : [];
            $validProducts = is_array($request->valid_products) ? $request->valid_products : [];
            $userIds = is_array($request->user_id) ? $request->user_id : [];

            Log::info("Valid Categories: " . json_encode($validCategories));
            Log::info("Valid Products: " . json_encode($validProducts));
            Log::info("User IDs: " . json_encode($userIds));

            // Kiểm tra xem có dữ liệu restriction không
            if ($request->filled(['min_order_value', 'max_discount_value'])) {
                $restriction = $coupon->restriction()->firstOrCreate(
                    ['coupon_id' => $coupon->id]
                );

                if (!$restriction) {
                    throw new \Exception("Không thể tìm hoặc tạo bản ghi restriction.");
                }
                Log::info("Đã updte xong user : " . json_encode($restriction->toArray()));

                // Cập nhật restriction
                $restriction->update([
                    'min_order_value' => $request->min_order_value,
                    'max_discount_value' => $request->max_discount_value,
                    'valid_categories' => json_encode($validCategories),
                    'valid_products' => json_encode($validProducts),
                ]);

                Log::info("Updated Restriction: " . json_encode($restriction->toArray()));
            }

            // Cập nhật danh sách user áp dụng
            $coupon->users()->sync($userIds);

            DB::commit();
            return redirect()->route('coupons.list')->with('success', 'Cập nhật mã giảm giá thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi khi cập nhật mã giảm giá: " . $e->getMessage());

            return redirect()->back()->with('error', 'Lỗi cập nhật mã giảm giá: ' . $e->getMessage());
        }
    }


    public function destroy(string $id)
    {

        DB::beginTransaction();

        try {
            $coupon = Coupon::findOrFail($id);

            // Xóa ràng buộc liên quan trong `coupon_restrictions`
            CouponRestriction::where('coupon_id', $coupon->id)->delete();

            // Xóa quan hệ với users trong `coupon_user`
            $coupon->users()->detach();

            // Xóa mã giảm giá
            $coupon->delete();

            DB::commit();
            return redirect()->route('coupons.list')->with('success', 'Xóa mã giảm giá thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi khi xóa mã giảm giá: " . $e->getMessage());
            return redirect()->route('coupons.list')->with('error', 'Có lỗi xảy ra, vui lòng thử lại!');
        }
    }

    public function approve(Request $request , $id)
    {
        $coupon = Coupon::findOrFail($id);
        $notificationId = $request->input('notification_id');
        // echo $notificationId;
        // die();
        if ($notificationId) {
            $notification = Notification::find($notificationId);
            if ($notification) {
                $notification->update(['is_read' => 1]);
            }
        }
       
        $coupon->status = 'approved';
        $coupon->save();

        return redirect()->back()->with('success', 'Mã giảm giá đã được duyệt!');
    }

    public function reject(Request $request , $id)
    {
        $coupon = Coupon::findOrFail($id);
        $notificationId = $request->input('notification_id');
        if ($notificationId) {
            $notification = Notification::find($notificationId);
            if ($notification) {
                $notification->update(['is_read' => 1]);
            }
        }
       
        $coupon->status = 'rejected';
        $coupon->save();

        return redirect()->back()->with('error', 'Mã giảm giá đã bị từ chối.');
    }





}