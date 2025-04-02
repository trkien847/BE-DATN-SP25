<?php 
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CouponCreatedNotification extends Notification
{
    use Queueable;

    protected $coupon;

    public function __construct($coupon)
    {
        $this->coupon = $coupon;
    }

    public function via($notifiable)
    {
        return ['database']; // Gửi thông báo vào database
    }

    public function toArray($notifiable)
    { 
        return [
            'message' => 'Một mã giảm giá mới đã được tạo: ' . $this->coupon->code,
            'coupon_id' => $this->coupon->id,
            'status' => $this->coupon->status
        ];
    }
}
