<?php

namespace App\Http\Controllers\Coupons;

use App\Http\Requests\CouponRequest;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CouponRestriction;
use App\Models\CouponUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CoupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Coupon::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('code', 'LIKE', "%$search%")
                ->orWhere('title', 'LIKE', "%$search%")
                ->orWhere('description', 'LIKE', "%$search%");
        }

        $coupons = $query->orderBy('created_at', 'desc')->paginate(5);


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
            'usage_limit' => $request->usage_limit ?? null, // 
            'usage_count' => 0,
            'start_date' => $request->start_date ?? null,
            'end_date' => $request->end_date ?? null,
        ]);

        // Tạo ràng buộc mã giảm giá (nếu có)

        
        if ($request->filled(['min_order_value', 'max_discount_value', 'valid_categories', 'valid_products'])) {
            CouponRestriction::create([
                'coupon_id' => $coupon->id,
                'min_order_value' => $request->min_order_value,
                'max_discount_value' => $request->max_discount_value,
                'valid_categories' => json_encode(array_map('intval', $request->valid_categories ?? [])),
                'valid_products' => json_encode(array_map('intval', $request->valid_products ?? [])),
            ]);
        }

        // Gán mã giảm giá cho user nếu có
        if ($request->has('user_id')) {
            try {
                $coupon->users()->sync($request->user_id);
                Log::info("Thêm user vào bảng coupon_user thành công.");
            } catch (\Exception $e) {
                Log::error("Lỗi khi thêm vào bảng coupon_user: " . $e->getMessage());
                throw new \Exception("Không thể thêm user vào mã giảm giá.");
            }
        }

        DB::commit();
        Log::info("Mã giảm giá '{$coupon->code}' đã được tạo thành công.");

        return redirect()->route('coupons.list')->with('success', 'Thêm mã giảm giá thành công!');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Lỗi khi thêm mã giảm giá: " . $e->getMessage());

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

}