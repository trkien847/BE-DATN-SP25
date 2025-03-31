<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCancelRequested
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function broadcastOn()
    {
        $admins = \App\Models\User::where('role_id', 3)->pluck('id');
        $channels = [];
        foreach ($admins as $adminId) {
            $channels[] = new PrivateChannel('notifications.' . $adminId . '.admin');
        }
        return $channels;
    }

    public function broadcastWith()
    {
        return [
            'title' => 'Yêu cầu hủy đơn hàng',
            'content' => 'Đơn hàng #' . $this->order->id . ' cần được xem xét.',
            'actions' => [
                'cancel_request' => route('orders.cancel', $this->order->id),
                'accept_request' => route('orders.accept', $this->order->id),
                'view_details' => route('orders.show', $this->order->id),
            ],
        ];
    }
}
