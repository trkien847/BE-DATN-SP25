<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ImportPendingNotification extends Notification
{
    use Queueable;

    protected $import;

    public function __construct($import)
    {
        $this->import = $import;
    }

    public function via($notifiable)
    {
        return ['database']; // Lưu thông báo vào bảng notifications
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Bạn đang có một đơn hàng chờ xác nhận.',
            'import_id' => $this->import->id,
            'imported_at' => $this->import->imported_at,
            'imported_by' => $this->import->imported_by,
        ];
    }
}
