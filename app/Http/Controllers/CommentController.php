<?php

namespace App\Http\Controllers;

use App\Events\CommentPosted;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'content' => 'required',
            ], [
                'content.required' => 'Vui lòng nhập bình luận',
            ]);

            $comment = Comment::create([
                'product_id' => $request->product_id,
                'user_id' => auth()->id(),
                'content' => strip_tags($request->content),
            ]);

            // Load relationships before broadcasting
            $comment->load(['user', 'product']);

            broadcast(new CommentPosted($comment))->toOthers();

            return response()->json([
                'status' => 'success',
                'message' => 'Bình luận đã được gửi thành công',
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user_name' => $comment->user->name,
                    'created_at' => $comment->created_at->diffForHumans(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Comment broadcast error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi gửi bình luận'
            ], 500);
        }
    }
    public function index()
    {
        $comments = Comment::with(['user', 'product'])->where('is_approved', '0')->latest()->paginate(10);
        $pendingCount = Comment::where('is_approved', 0)->count();

        return view('admin.comments.index', compact('comments', 'pendingCount'));
    }


    // Xóa bình luận
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bình luận đã được xóa thành công',
        ]);
    }

    // Trả lời bình luận (nếu cần thiết)
    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|min:2',
        ], [
            'reply.required' => 'Vui lòng nhập nội dung trả lời',
            'reply.min' => 'Nội dung trả lời quá ngắn',
        ]);
        $user = auth()->user();
        $parentComment = Comment::findOrFail($id);

        $parentComment->update([
            'is_approved' => true
        ]);

        $reply = Comment::create([
            'product_id' => $parentComment->product_id,
            'user_id' => $user->id,
            'content' => strip_tags($request->reply),
            'parent_id' => $parentComment->id,
            'is_approved' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Trả lời bình luận thành công',
        ]);
    }
    public function productComments(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $comments = Comment::with(['user', 'parent'])
            ->where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.comments.product-comments', compact('product', 'comments'));
    }
    public function productsList(Request $request)
    {
        $query = Product::withCount(['comments', 'pendingComments'])
            ->orderBy('comments_count', 'desc');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhereHas('categories', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('categoryTypes', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $products = $query->paginate(10);
        $pendingCount = Comment::where('is_approved', 0)->count();
        if ($request->ajax()) {
            return view('admin.comments.product-list-partial', compact('products'))->render();
        }

        return view('admin.comments.product-list', compact('products', 'pendingCount'));
    }
}
