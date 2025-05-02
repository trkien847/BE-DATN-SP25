<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reviews;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validate đầu vào
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'order_id' => 'required|exists:orders,id',
                'rating' => 'required|integer|between:1,5',
                'review_text' => 'required|string|max:1000',
            ]);

            // Kiểm tra xem đơn hàng có phải của user hiện tại không
            $order = Order::findOrFail($request->order_id);
            if ($order->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền đánh giá đơn hàng này'
                ], 403);
            }

            // Kiểm tra xem đơn hàng đã hoàn thành chưa
            if ($order->latestOrderStatus->name !== 'Hoàn thành') {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ có thể đánh giá đơn hàng đã hoàn thành'
                ], 400);
            }

            // Kiểm tra xem user đã đánh giá sản phẩm này trong đơn hàng này chưa
            $existingReview = Reviews::where([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'order_id' => $request->order_id,
            ])->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã đánh giá sản phẩm này trong đơn hàng này rồi'
                ], 400);
            }

            // Tạo đánh giá mới
            $review = new Reviews();
            $review->product_id = $request->product_id;
            $review->order_id = $request->order_id;
            $review->user_id = Auth::id();
            $review->rating = $request->rating;
            $review->review_text = $request->review_text;
            $review->is_active = true;
            $review->save();

            return response()->json([
                'success' => true,
                'message' => 'Đánh giá của bạn đã được gửi thành công',
                'data' => $review
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }


    public function index()
    {
        $reviews = Reviews::with(['user', 'product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function reply(Request $request, Reviews $review)
    {
        $request->validate([
            'reply' => 'required|string|max:1000'
        ]);

        $review->update([
            'admin_reply' => $request->reply,
            'replied_at' => now()
        ]);

        return back()->with('success', 'Đã trả lời đánh giá thành công');
    }
}
