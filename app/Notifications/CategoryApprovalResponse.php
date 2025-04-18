<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CategoryApprovalResponse extends Notification
{
    use Queueable;

    protected $category;
    protected $type;
    protected $oldName;

    public function __construct($category, $type = 'create', $oldName = null)
    {
        $this->category = $category;
        $this->type = $type;
        $this->oldName = $oldName;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $title = $this->type === 'create' 
            ? 'Yêu cầu tạo danh mục mới'
            : 'Yêu cầu cập nhật danh mục';

        $content = $this->type === 'create'
            ? "Người dùng yêu cầu tạo danh mục: {$this->category->name}"
            : "Yêu cầu cập nhật danh mục từ '{$this->oldName}' thành '{$this->category->name}'";

        return [
            'user_id' => $notifiable->id,
            'title' => $title,
            'content' => $content,
            'type' => $this->type === 'create' ? 'category_pending_create' : 'category_pending_update',
            'data' => [
                'category' => $this->category,
                'requester_id' => $this->category->created_by,
                'actions' => [
                    'approve_request' => $this->type === 'create' 
                        ? route('categories.approve', $this->category->id)
                        : route('categories.update.approve', $this->category->id),
                    'reject_request' => $this->type === 'create'
                        ? route('categories.reject', $this->category->id)
                        : route('categories.update.reject', $this->category->id),
                    'view_details' => route('categories.pending', $this->category->id)
                ]
            ],
            'is_read' => false
        ];
    }
}
