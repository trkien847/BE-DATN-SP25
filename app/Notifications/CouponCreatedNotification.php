<?php

namespace App\Notifications;

use App\Models\Coupon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class CouponCreatedNotification extends Notification
{
    use Queueable;

    protected $coupon;

    public function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }

    public function via($notifiable)
    {
        return ['database']; // Nếu dùng bảng notifications
    }

    public function toDatabase($notifiable)
    {
        return [
            'coupon_id' => $this->coupon->id,
            'message' => "Yêu cầu phê duyệt mã giảm giá <strong>{$this->coupon->code}</strong>",
            'url' => route('coupons.review', $this->coupon->id),
        ];
    }
}
