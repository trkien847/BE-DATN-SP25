<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderOrderStatus;
use App\Http\Controllers\Controller;
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
}
