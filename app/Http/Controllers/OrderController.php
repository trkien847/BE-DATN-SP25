<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderOrderStatus;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Order::with(['orderStatuses.orderStatus', 'orderStatuses.modifier'])->latest('created_at');

        if ($status) {
            $query->whereHas('orderStatuses', function ($q) use ($status) {
                $q->where('order_status_id', $status)
                    ->whereIn('id', function ($subQuery) {
                        $subQuery->selectRaw('MAX(id)')
                            ->from('order_order_status')
                            ->whereColumn('order_id', 'orders.id')
                            ->groupBy('order_id');
                    });
            });
        }

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->has('customer_name') && $request->customer_name) {
            $query->where('fullname', 'like', '%' . $request->customer_name . '%');
        }

        $orders = $query->get();
        $currentUserId = Auth::id();
        return view('admin.OrderManagement.order', compact('orders', 'currentUserId'));
    }

    public function getOrderDetails($id)
    {
        try {
            $order = Order::with(['items.product'])->findOrFail($id);
            return response()->json([
                'order' => $order,
                'items' => $order->items
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Không thể lấy chi tiết đơn hàng',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'status_id' => 'required|exists:order_statuses,id',
                'modified_by' => 'required|integer',
            ]);

            $orderId = $request->order_id;
            $newStatusId = $request->status_id;

            $latestStatus = OrderOrderStatus::where('order_id', $orderId)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($latestStatus) {
                $currentStatusId = $latestStatus->order_status_id;

                if ($newStatusId < $currentStatusId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không thể cập nhật về trạng thái nhỏ hơn trạng thái hiện tại!'
                    ], 403);
                }
            }

            $data = [
                'order_id' => $request->order_id,
                'order_status_id' => $request->status_id,
                'modified_by' => $request->modified_by,
                'note' => 'đã được xác nhận',
            ];

            if ($request->hasFile('evidence')) {
                $image = $request->file('evidence');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('upload'), $imageName);
                $data['evidence'] = $imageName;
            }

            OrderOrderStatus::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateBulkStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'order_ids' => 'required|array',
                'order_ids.*' => 'exists:orders,id',
                'status_id' => 'required|exists:order_statuses,id',
                'modified_by' => 'required|integer',
            ]);

            $orderIds = $request->order_ids;
            $newStatusId = $request->status_id;
            $modifiedBy = $request->modified_by;

            foreach ($orderIds as $orderId) {
                $latestStatus = OrderOrderStatus::where('order_id', $orderId)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($latestStatus) {
                    $currentStatusId = $latestStatus->order_status_id;
                    if ($newStatusId < $currentStatusId) {
                        return response()->json([
                            'success' => false,
                            'message' => "Không thể cập nhật trạng thái nhỏ hơn trạng thái hiện tại cho đơn hàng $orderId!"
                        ], 403);
                    }
                }

                OrderOrderStatus::create([
                    'order_id' => $orderId,
                    'order_status_id' => $newStatusId,
                    'modified_by' => $modifiedBy,
                    'note' => 'Cập nhật hàng loạt',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái hàng loạt thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // thông kê
    public function statistics(Request $request)
    {
        // Lấy ngày từ request, mặc định là hôm nay nếu không có input
        $date = $request->input('date') ?: Carbon::today()->toDateString();
        $startOfDay = Carbon::parse($date)->startOfDay();
        $endOfDay = Carbon::parse($date)->endOfDay();

        // Tạo mảng dữ liệu theo giờ (24 giờ)
        $hours = range(0, 23);
        $expectedRevenueData = array_fill(0, 24, 0);
        $actualRevenueData = array_fill(0, 24, 0);
        $canceledRevenueData = array_fill(0, 24, 0);

        // Doanh số dự tính (order_status_id: 1, 2, 3, 4)
        $expectedOrders = Order::whereHas('orderStatuses', function ($query) use ($startOfDay, $endOfDay) {
            $query->whereIn('order_status_id', [1, 2, 3, 4])
                  ->whereBetween('created_at', [$startOfDay, $endOfDay]);
        })->with(['orderStatuses' => function ($query) use ($startOfDay, $endOfDay) {
            $query->whereBetween('created_at', [$startOfDay, $endOfDay]);
        }])->get();

        foreach ($expectedOrders as $order) {
            foreach ($order->orderStatuses as $status) {
                $hour = Carbon::parse($status->created_at)->hour;
                $expectedRevenueData[$hour] += $order->total_amount;
            }
        }

        // Doanh số thực tế (order_status_id: 6)
        $actualOrders = Order::whereHas('orderStatuses', function ($query) use ($startOfDay, $endOfDay) {
            $query->where('order_status_id', 6)
                  ->whereBetween('created_at', [$startOfDay, $endOfDay]);
        })->with(['orderStatuses' => function ($query) use ($startOfDay, $endOfDay) {
            $query->whereBetween('created_at', [$startOfDay, $endOfDay]);
        }])->get();

        foreach ($actualOrders as $order) {
            foreach ($order->orderStatuses as $status) {
                $hour = Carbon::parse($status->created_at)->hour;
                $actualRevenueData[$hour] += $order->total_amount;
            }
        }

        // Doanh số bị hủy (order_status_id: 7)
        $canceledOrders = Order::whereHas('orderStatuses', function ($query) use ($startOfDay, $endOfDay) {
            $query->where('order_status_id', 7)
                  ->whereBetween('created_at', [$startOfDay, $endOfDay]);
        })->with(['orderStatuses' => function ($query) use ($startOfDay, $endOfDay) {
            $query->whereBetween('created_at', [$startOfDay, $endOfDay]);
        }])->get();

        foreach ($canceledOrders as $order) {
            foreach ($order->orderStatuses as $status) {
                $hour = Carbon::parse($status->created_at)->hour;
                $canceledRevenueData[$hour] += $order->total_amount;
            }
        }

        // Tổng doanh số
        $expectedRevenue = array_sum($expectedRevenueData);
        $actualRevenue = array_sum($actualRevenueData);
        $canceledRevenue = array_sum($canceledRevenueData);

        return view('admin.OrderManagement.statistics', compact(
            'expectedRevenue', 'actualRevenue', 'canceledRevenue',
            'expectedRevenueData', 'actualRevenueData', 'canceledRevenueData',
            'date'
        ));
    }

}
