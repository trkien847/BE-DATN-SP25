<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderOrderStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderStatus', 'orderStatusDetails'])->get();
        return view('admin.OrderManagement.order', compact('orders'));
    }

    public function updateStatus(Request $request)
{
    $order = OrderOrderStatus::where('order_id', $request->order_id)->first();

    if (!$order) {
        return response()->json(['error' => 'Đơn hàng không tồn tại'], 404);
    }

    $currentStatus = $order->order_status_id; 
    $newStatus = (int)$request->status;       

    if ($currentStatus < $newStatus) {
       
        $order->order_status_id = $newStatus;
        $order->save();
        return response()->json(['message' => 'Trạng thái đã được cập nhật']);
    } else {
        
        return response()->json(['error' => 'Trạng thái không được cập nhật ngược lại'], 400);
    }
}



}
