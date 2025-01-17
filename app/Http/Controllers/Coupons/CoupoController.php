<?php

namespace App\Http\Controllers\Coupons;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CoupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.coupons.list');
       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.coupons.coupon.add');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:phan_tram,co_dinh',
            'discount_value' => 'nullable|numeric',
            'usage_limit' => 'nullable|integer',
            'is_expired' => 'required|boolean',
            'is_active' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            // 'min_usage_amount' => 'nullable|numeric',
            // 'max_usage_amount' => 'nullable|numeric',
            'applicable_products' => 'nullable|array',
            'applicable_categories' => 'nullable|array',
        ]);
    
        // Lưu mã giảm giá
        $coupon = Coupon::create([
            'code' => $request->code,
            'title' => $request->title,
            'description' => $request->description,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'usage_limit' => $request->usage_limit,
            'is_expired' => $request->is_expired,
            'is_active' => $request->is_active,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            // 'min_usage_amount' => $request->min_usage_amount,
            // 'max_usage_amount' => $request->max_usage_amount,
            'applicable_products' => json_encode($request->applicable_products),
            'applicable_categories' => json_encode($request->applicable_categories),
        ]);
    
        return redirect()->route('coupons.list')->with('success', 'Mã giảm giá đã được thêm thành công!');
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
        //
    }
}
