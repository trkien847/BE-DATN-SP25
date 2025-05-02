<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\ProductImport;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function checkNotifications(Request $request)
    {
        try {
            if (!auth()->check() || auth()->user()->role_id != 3) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $lastChecked = $request->input('last_checked');

            $notifications = ProductImport::with('user')
                ->where('is_active', 0)
                ->latest('imported_at')
                ->get();


            $notifications = $notifications->map(function ($import) {
                $user = $import->user;
                $importedAt = $import->imported_at instanceof \Carbon\Carbon 
                    ? $import->imported_at 
                    : ($import->imported_at ? Carbon::parse($import->imported_at) : null);

                return [
                    'import_id' => $import->id,
                    'user_name' => $user ? $user->fullname : 'Unknown User',
                    'avatar' => $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/avatars/default.jpg'),
                    'created_at' => $importedAt ? $importedAt->diffForHumans() : 'N/A',
                    'imported_at' => $importedAt ? $importedAt->toISOString() : null,
                    'imported_by' => $user ? $user->fullname : 'Unknown User',
                    'message' => $user ? "{$user->fullname} đang yêu cầu nhập hàng" : "Yêu cầu nhập hàng không xác định",
                ];
            });

            $newNotifications = $lastChecked
                ? $notifications->filter(function ($item) use ($lastChecked) {
                    try {
                        return $item['imported_at'] && (new DateTime($item['imported_at'])) > (new DateTime($lastChecked));
                    } catch (\Exception $e) {
                        return false;
                    }
                })->values()
                : $notifications;

            $imports = $newNotifications->map(function ($item) {
                return [
                    'import_id' => $item['import_id'],
                    'message' => 'Bạn đang có một đơn hàng chờ xác nhận.',
                    'imported_at' => $item['imported_at'],
                    'imported_by' => $item['imported_by'],
                ];
            })->toArray();

            return response()->json([
                'notifications' => $notifications->toArray(),
                'imports' => $imports
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


    public function index()
    {
        $notifications = Notification::userOrSystem(Auth::id())
            ->where('is_read', 0)
            ->latest()
            ->get();

        return view('admin.notifications.index', compact('notifications'));
    }

    public function confirm($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_read = 1;
        $notification->save();

        return back()->with('success', 'Đã xác nhận thông báo!');
    }

    public function getNotifications()
    {
        $notifications = Notification::userOrSystem(Auth::id())
            ->where('is_read', 0)
            ->latest()
            ->limit(10)
            ->get();

        $count = $notifications->count();

        return response()->json([
            'count' => $count,
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'content' => $notification->content,
                    'type' => $notification->type,
                    'data' => $notification->data,
                    'is_read' => $notification->is_read,
                ];
            })->toArray(),
        ]);
    }
}
