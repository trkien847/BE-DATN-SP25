<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Reviews;
use Carbon\Carbon;

class AutoReviewCommand extends Command
{
    protected $signature = 'reviews:auto';
    protected $description = 'Tự động đánh giá 5 sao cho đơn hàng hoàn thành sau 7 ngày';

    public function handle()
    {
        // Lấy các đơn hàng hoàn thành trước 7 ngày và chưa có đánh giá
        $orders = Order::whereHas('latestOrderStatus', function($query) {
            $query->where('name', 'Hoàn thành');
        })
        ->where('updated_at', '<=', Carbon::now()->subDays(7))
        ->get();

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                // Kiểm tra xem sản phẩm đã được đánh giá chưa
                $exists = Reviews::where([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                ])->exists();

                if (!$exists) {
                    // Tạo đánh giá tự động
                    Reviews::create([
                        'user_id' => $order->user_id,
                        'product_id' => $item->product_id,
                        'order_id' => $order->id,
                        'rating' => 5,
                        'review_text' => 'Tốt',
                        'is_active' => true,
                    ]);

                    $this->info("Đã tạo đánh giá tự động cho đơn hàng {$order->code}, sản phẩm ID: {$item->product_id}");
                }
            }
        }
    }
}
