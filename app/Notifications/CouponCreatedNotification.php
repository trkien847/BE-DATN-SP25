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

    /**
     * Constructor to initialize the notification with a Coupon instance.
     *
     * @param Coupon $coupon
     */
    public function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }

    /**
     * Define the notification delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Format the notification data for database storage.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => __('Yêu cầu phê duyệt mã giảm giá :code', ['code' => $this->coupon->code]),
            'coupon_id' => $this->coupon->id,
            'created_at' => now(),
            'created_by' => auth()->user()->name ?? __('Hệ thống'),
        ];
    }
}
