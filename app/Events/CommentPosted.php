<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentPosted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
        $this->comment->load('user', 'product');
    }

    public function broadcastOn()
    {
        return new Channel('comments');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->comment->id,
            'content' => $this->comment->content,
            'user' => [
                'name' => $this->comment->user->fullname,
                'avatar' => $this->comment->user->avatar ?? asset('images/default-avatar.png'),
                'email' => $this->comment->user->email
            ],
            'product' => [
                'id' => $this->comment->product->id,
                'name' => $this->comment->product->name
            ],
            'created_at' => $this->comment->created_at->format('H:i, d/m/Y')
        ];
    }
}

