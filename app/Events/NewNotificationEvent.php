<?php

namespace App\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewNotificationEvent extends Notification implements ShouldBroadcast
{
    use Queueable, InteractsWithSockets;

    public $message;
    public $createdBy;

    public function __construct($message, $createdBy)
    {
        $this->message = $message;
        $this->createdBy = $createdBy;
    }

    // Cấu hình để thông báo này được phát sóng
    public function broadcastOn()
    {
        return new PrivateChannel('notifications');
    }

    // Dữ liệu gửi kèm khi phát sóng
    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'created_by' => $this->createdBy
        ];
    }

    // Cấu hình thông báo lưu vào database
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    // Dữ liệu thông báo lưu vào cơ sở dữ liệu
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'created_by' => $this->createdBy,
            'url' => route('categories.pending'),
        ];
    }
    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'created_by' => $this->createdBy,
        ];
    }
}
