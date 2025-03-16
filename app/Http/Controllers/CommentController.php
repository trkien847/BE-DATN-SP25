<?php

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'guest_name' => 'nullable|string|max:255',
            'guest_email' => 'nullable|email',
        ]);
    
        Comment::create([
            'product_id' => $request->product_id,
            'user_id' => auth()->check() ? auth()->user()->id : null,
            'guest_name' => $request->guest_name,
            'guest_email' => $request->guest_email,
            'content' => $request->content,
        ]);
    
        return redirect()->back()->with('success', 'Bình luận đã được gửi.');
    }


    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate([
            'content' => 'required',
        ]);

        $comment->update(['content' => $request->content]);

        return back()->with('success', 'Đã cập nhật bình luận');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();

        return back()->with('success', 'Đã xóa bình luận');
    }
}

