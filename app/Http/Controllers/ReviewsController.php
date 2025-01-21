<?php

namespace App\Http\Controllers;

use App\Models\Reviews;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function index(){
        $data = Reviews::query()
        ->where('is_active', 1) // Lấy các đánh giá đang hoạt động
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        return view('admin.reviews.listReviews',compact('data'));
    }
    public function listedit(Reviews $reviews){
        return view('admin.reviews.editReviews',compact('reviews'));
    }
    public function edit(Request $request,Reviews $reviews){
         // Validate dữ liệu
         $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string',
            'reason' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        // Cập nhật đánh giá
        $reviews->update([
            'rating' => $request->rating,
            'review_text' => $request->review_text,
            'reason' => $request->reason,
            'is_active' => $request->is_active,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.reviews.listReviews')->with('message', 'Sửa thành công 1 đánh giá.');
    }  
    public function destroy(Reviews $reviews){
        $reviews->update([
            'is_active' => 0, // chuyển trạng thái hoạt động về unactive để ẩn đi 
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.reviews.listReviews')->with('message', 'Đã ẩn thành công 1 đánh giá.');
    }
}
