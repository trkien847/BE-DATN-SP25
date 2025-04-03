<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function fetchNotifications(Request $request)
    {
        $user = auth()->user();
        $notifications = $user->unreadNotifications()->get();
        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'message' => $notification->data['message'],
                    'created_by' => $notification->data['created_by'],
                    'url' => $notification->data['url'] ?? null,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'read_at' => $notification->read_at,
                    'avatar' => $notification->data['avatar'] ?? null,
                ];
            }),
        ]);
    }
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }
    public function getCount()
    {
        $count = auth()->user()->unreadNotifications()->count();
        return response()->json(['count' => $count]);
    }
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.notifications.list', compact('notifications'));
    }
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }
    public function clearAllRead()
    {
        try {
            auth()->user()->notifications()
                ->whereNotNull('read_at')
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa tất cả thông báo đã đọc'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
