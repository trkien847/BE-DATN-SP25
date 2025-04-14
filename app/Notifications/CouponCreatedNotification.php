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

    // public function toArray($notifiable)
    // { 
    //     return [
    //         'title' => 'Thông báo mã giảm giá mới',
    //         'message' => 'Một mã giảm giá mới đã được tạo: ' . $this->coupon->code,
    //         'coupon_id' => $this->coupon->id,
    //         'status' => $this->coupon->status
    //     ];
    // }
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Thông báo mã giảm giá mới',
            'content' => 'Một mã giảm giá mới đã được tạo: ' . $this->coupon->code,
            'type' => 'coupon_created', // hoặc bất kỳ chuỗi định danh nào bạn muốn
            'data' => [ // bạn có thể dùng thêm nếu muốn
                'message' => 'Một mã giảm giá mới đã được tạo: ' . $this->coupon->code,
                'coupon_id' => $this->coupon->id,
                'status' => $this->coupon->status,
            ],
        ];
    }
}
