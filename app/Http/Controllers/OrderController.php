<?php

namespace App\Http\Controllers;

use App\Exports\OrdersStatisticsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Order;
use App\Models\OrderOrderStatus;
use App\Http\Controllers\Controller;
use App\Models\ImportProduct;
use App\Models\Notification;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Order::with([
            'orderStatuses.orderStatus',
            'orderStatuses.modifier',
            'items.product' => function ($query) {
                $query->select('id', 'name');
            }
        ])->latest('created_at');

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
        $order = Order::findOrFail($id);
        $items = OrderItem::with('product')
            ->where('order_id', $id)
            ->get()
            ->map(function ($item) {
                // Tính thời gian hết hạn từ created_at đến expiry_date (nếu có)
                $daysUntilExpiry = null;
                if ($item->created_at && $item->expiry_date) {
                    $createdAt = \Carbon\Carbon::parse($item->created_at);
                    $expiryDate = \Carbon\Carbon::parse($item->expiry_date);
                    $daysUntilExpiry = $createdAt->diffInDays($expiryDate);
                }

                return [
                    'product' => $item->product,
                    'name_variant' => $item->name_variant,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'manufacture_date' => $item->manufacture_date,
                    'expiry_date' => $item->expiry_date,
                    'import_code' => $item->import_code,
                    'days_until_expiry' => $daysUntilExpiry, // Số ngày từ created_at đến expiry_date
                    'created_at' => $item->created_at,
                ];
            });

        return response()->json([
            'order' => $order,
            'items' => $items
        ]);
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
            $modifiedBy = $request->modified_by;

            // Get the user who is making the update
            $updatingUser = User::find($modifiedBy);

            // Check if user exists
            if (!$updatingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Người dùng không tồn tại'
                ], 404);
            }

            // If user's role_id is 3, proceed normally
            if ($updatingUser->role_id === 3) {
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
            }
            // If user is not role_id 3
            else {
                // Trong phần xử lý status_id = 6 hoặc 7
                if ($newStatusId == 6 || $newStatusId == 7) {
                    $order = Order::find($orderId);
                    $statusText = $newStatusId == 6 ? 'hoàn thành' : 'hủy';

                    $evidencePath = null;
                    if ($request->hasFile('evidence')) {
                        $image = $request->file('evidence');
                        $imageName = time() . '_' . $image->getClientOriginalName();
                        $image->move(public_path('upload'), $imageName);
                        $evidencePath = $imageName;
                    }

                    // Tạo URL cho các hành động
                    $acceptUrl = route('notifications.accept', ['order_id' => $orderId]); // Route để chấp nhận
                    $cancelUrl = route('notifications.cancel', ['order_id' => $orderId]); // Route để hủy
                    $detailsUrl = route('notifications.details', ['order_id' => $orderId]); // Route để xem chi tiết

                    $adminUsers = User::where('role_id', 3)->get();
                    foreach ($adminUsers as $admin) {
                        Notification::create([
                            'user_id' => $admin->id,
                            'title' => "Yêu cầu cập nhật trạng thái đơn hàng",
                            'content' => "Nhân viên {$updatingUser->fullname} đang yêu cầu cập nhật đơn hàng {$orderId} lên trạng thái {$statusText}",
                            'type' => 'order_status_request',
                            'data' => [
                                'order_id' => $orderId,
                                'request_status_id' => $newStatusId,
                                'requester_id' => $modifiedBy,
                                'requester_name' => $updatingUser->fullname,
                                'evidence' => $evidencePath,
                                'order_details' => [
                                    'customer_name' => $order->fullname ?? 'N/A',
                                    'amount' => $order->total_amount ?? 0,
                                    'processor_id' => $modifiedBy
                                ],
                                'actions' => [
                                    'accept_request' => $acceptUrl,
                                    'cancel_request' => $cancelUrl,
                                    'view_details' => $detailsUrl
                                ]
                            ]
                        ]);
                    }

                    return response()->json([
                        'success' => true,
                        'message' => 'Yêu cầu cập nhật trạng thái đã được gửi đến quản trị viên'
                    ]);
                }
                // If status_id is not 6 or 7, proceed normally
                else {
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
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function accept(Request $request, $order_id)
    {
        $notification = Notification::findOrFail($request->notification_id);
        $data = $notification->data;

        // Tạo bản ghi cập nhật trạng thái
        OrderOrderStatus::create([
            'order_id' => $data['order_id'],
            'order_status_id' => $data['request_status_id'],
            'modified_by' => $data['requester_id'],
            'note' => 'Đã được quản trị viên chấp nhận',
            'evidence' => $data['evidence']
        ]);

        // Đánh dấu thông báo là đã đọc
        $notification->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Yêu cầu đã được chấp nhận');
    }

    public function cancel(Request $request, $order_id)
    {
        $notification = Notification::findOrFail($request->notification_id);
        $notification->update(['is_read' => true]); // Đánh dấu đã đọc

        return redirect()->back()->with('success', 'Yêu cầu đã bị hủy');
    }

    public function details($order_id)
    {
        $notification = Notification::where('data->order_id', $order_id)
            ->orderBy('created_at', 'desc') // Lấy bản ghi mới nhất
            ->firstOrFail();
        $order = Order::where('id', $order_id)->first();
        $data = $notification->data;

        return view('admin.OrderManagement.notifications', [
            'order_id' => $order['code'] ?? 'N/A',
            'requester_name' => $data['requester_name'] ?? 'Không xác định',
            'customer_name' => $data['order_details']['customer_name'] ?? 'N/A',
            'amount' => $data['order_details']['amount'] ?? 0,
            'evidence' => $data['evidence'] ?? null,
            'status' => isset($data['request_status_id']) && $data['request_status_id'] == 6 ? 'Hoàn thành' : 'Hủy'
        ]);
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
        $filterType = $request->input('filter_type', 'day');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $month = $request->input('month');
        $year = $request->input('year', Carbon::today()->year);
        $currentYear = Carbon::today()->year;

        if ($filterType === 'range' && $startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
            $dateLabel = "Từ " . $start->format('d/m/Y') . " đến " . $end->format('d/m/Y');
        } elseif ($filterType === 'month' && $month) {
            $start = Carbon::create($year, $month, 1)->startOfMonth();
            $end = Carbon::create($year, $month, 1)->endOfMonth();
            $dateLabel = "Tháng $month/$year";
        } elseif ($filterType === 'year' && $year) {
            $start = Carbon::create($year, 1, 1)->startOfYear();
            $end = Carbon::create($year, 1, 1)->endOfYear();
            $dateLabel = "Năm $year";
        } else {
            $start = Carbon::today()->startOfDay();
            $end = Carbon::today()->endOfDay();
            $dateLabel = Carbon::today()->format('d/m/Y');
        }

        if ($request->has('export')) {
            return Excel::download(new OrdersStatisticsExport($start, $end, $filterType, $dateLabel), 'thong-ke-don-hang.xlsx');
        }

        $labels = [];
        $expectedRevenueData = [];
        $actualRevenueData = [];
        $canceledRevenueData = [];

        if ($filterType === 'day') {
            $labels = array_map(fn($h) => $h . 'h', range(0, 23));
            $expectedRevenueData = array_fill(0, 24, 0);
            $actualRevenueData = array_fill(0, 24, 0);
            $canceledRevenueData = array_fill(0, 24, 0);

            $expectedOrders = Order::whereHas('orderStatuses', function ($query) use ($start, $end) {
                $query->whereIn('order_status_id', [1, 2, 3, 4])
                    ->whereBetween('created_at', [$start, $end]);
            })->with(['orderStatuses' => function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            }])->get();

            foreach ($expectedOrders as $order) {
                foreach ($order->orderStatuses as $status) {
                    $hour = Carbon::parse($status->created_at)->hour;
                    $expectedRevenueData[$hour] += $order->total_amount;
                }
            }

            $actualOrders = Order::whereHas('orderStatuses', function ($query) use ($start, $end) {
                $query->where('order_status_id', 6)
                    ->whereBetween('created_at', [$start, $end]);
            })->with(['orderStatuses' => function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            }])->get();

            foreach ($actualOrders as $order) {
                foreach ($order->orderStatuses as $status) {
                    $hour = Carbon::parse($status->created_at)->hour;
                    $actualRevenueData[$hour] += $order->total_amount;
                }
            }

            $canceledOrders = Order::whereHas('orderStatuses', function ($query) use ($start, $end) {
                $query->where('order_status_id', 7)
                    ->whereBetween('created_at', [$start, $end]);
            })->with(['orderStatuses' => function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            }])->get();

            foreach ($canceledOrders as $order) {
                foreach ($order->orderStatuses as $status) {
                    $hour = Carbon::parse($status->created_at)->hour;
                    $canceledRevenueData[$hour] += $order->total_amount;
                }
            }
        } elseif ($filterType === 'range') {
            $days = $start->diffInDays($end) + 1;
            $labels = array_map(fn($d) => $start->copy()->addDays($d)->format('d/m'), range(0, $days - 1));
            $expectedRevenueData = array_fill(0, $days, 0);
            $actualRevenueData = array_fill(0, $days, 0);
            $canceledRevenueData = array_fill(0, $days, 0);

            $orders = Order::whereHas('orderStatuses', function ($query) use ($start, $end) {
                $query->whereIn('order_status_id', [1, 2, 3, 4, 6, 7])
                    ->whereBetween('created_at', [$start, $end]);
            })->with(['orderStatuses' => function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            }])->get();

            foreach ($orders as $order) {
                foreach ($order->orderStatuses as $status) {
                    $dayIndex = $start->diffInDays(Carbon::parse($status->created_at));
                    if (in_array($status->order_status_id, [1, 2, 3, 4])) {
                        $expectedRevenueData[$dayIndex] += $order->total_amount;
                    } elseif ($status->order_status_id == 6) {
                        $actualRevenueData[$dayIndex] += $order->total_amount;
                    } elseif ($status->order_status_id == 7) {
                        $canceledRevenueData[$dayIndex] += $order->total_amount;
                    }
                }
            }
        } elseif ($filterType === 'month') {
            $days = $start->daysInMonth;
            $labels = array_map(fn($d) => ($d + 1) . '/' . $month, range(0, $days - 1));
            $expectedRevenueData = array_fill(0, $days, 0);
            $actualRevenueData = array_fill(0, $days, 0);
            $canceledRevenueData = array_fill(0, $days, 0);

            $orders = Order::whereHas('orderStatuses', function ($query) use ($start, $end) {
                $query->whereIn('order_status_id', [1, 2, 3, 4, 6, 7])
                    ->whereBetween('created_at', [$start, $end]);
            })->with(['orderStatuses' => function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            }])->get();

            foreach ($orders as $order) {
                foreach ($order->orderStatuses as $status) {
                    $dayIndex = Carbon::parse($status->created_at)->day - 1;
                    if (in_array($status->order_status_id, [1, 2, 3, 4])) {
                        $expectedRevenueData[$dayIndex] += $order->total_amount;
                    } elseif ($status->order_status_id == 6) {
                        $actualRevenueData[$dayIndex] += $order->total_amount;
                    } elseif ($status->order_status_id == 7) {
                        $canceledRevenueData[$dayIndex] += $order->total_amount;
                    }
                }
            }
        } elseif ($filterType === 'year') {
            $labels = array_map(fn($m) => 'Tháng ' . ($m + 1), range(0, 11));
            $expectedRevenueData = array_fill(0, 12, 0);
            $actualRevenueData = array_fill(0, 12, 0);
            $canceledRevenueData = array_fill(0, 12, 0);

            $orders = Order::whereHas('orderStatuses', function ($query) use ($start, $end) {
                $query->whereIn('order_status_id', [1, 2, 3, 4, 6, 7])
                    ->whereBetween('created_at', [$start, $end]);
            })->with(['orderStatuses' => function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            }])->get();

            foreach ($orders as $order) {
                foreach ($order->orderStatuses as $status) {
                    $monthIndex = Carbon::parse($status->created_at)->month - 1;
                    if (in_array($status->order_status_id, [1, 2, 3, 4])) {
                        $expectedRevenueData[$monthIndex] += $order->total_amount;
                    } elseif ($status->order_status_id == 6) {
                        $actualRevenueData[$monthIndex] += $order->total_amount;
                    } elseif ($status->order_status_id == 7) {
                        $canceledRevenueData[$monthIndex] += $order->total_amount;
                    }
                }
            }
        }

        $expectedRevenue = Order::whereHas('orderStatuses', function ($query) use ($start, $end) {
            $query->whereIn('order_status_id', [1, 2, 3, 4])
                ->whereBetween('created_at', [$start, $end]);
        })->sum('total_amount');

        $actualRevenue = Order::whereHas('orderStatuses', function ($query) use ($start, $end) {
            $query->where('order_status_id', 6)
                ->whereBetween('created_at', [$start, $end]);
        })->sum('total_amount');

        $canceledRevenue = Order::whereHas('orderStatuses', function ($query) use ($start, $end) {
            $query->where('order_status_id', 7)
                ->whereBetween('created_at', [$start, $end]);
        })->sum('total_amount');

        $pendingOrdersCount = Order::whereHas('orderStatuses', function ($query) use ($start, $end) {
            $query->where('order_status_id', 1)
                ->whereBetween('created_at', [$start, $end]);
        })->count();

        $completedOrdersCount = Order::whereHas('orderStatuses', function ($query) use ($start, $end) {
            $query->where('order_status_id', 6)
                ->whereBetween('created_at', [$start, $end]);
        })->count();

        $canceledOrdersCount = Order::whereHas('orderStatuses', function ($query) use ($start, $end) {
            $query->where('order_status_id', 7)
                ->whereBetween('created_at', [$start, $end]);
        })->count();

        $topUsers = User::whereHas('orders.orderStatuses', function ($query) use ($start, $end) {
            $query->whereIn('order_status_id', [1, 2])
                ->whereBetween('created_at', [$start, $end]);
        })->with(['orders' => function ($query) use ($start, $end) {
            $query->whereHas('orderStatuses', function ($q) use ($start, $end) {
                $q->whereIn('order_status_id', [1, 2])
                    ->whereBetween('created_at', [$start, $end]);
            });
        }])->select('users.*')
            ->withCount(['orders as orders_count' => function ($query) use ($start, $end) {
                $query->whereHas('orderStatuses', function ($q) use ($start, $end) {
                    $q->whereIn('order_status_id', [1, 2])
                        ->whereBetween('created_at', [$start, $end]);
                });
            }])->orderBy('orders_count', 'desc')
            ->take(10)
            ->get()
            ->map(function ($user) {
                $user->total_spent = $user->orders->sum('total_amount');
                return $user;
            });

        $topProducts = Product::whereHas('items.order.orderStatuses', function ($query) use ($start, $end) {
            $query->whereIn('order_status_id', [1, 2])
                ->whereBetween('created_at', [$start, $end]);
        })->with(['items' => function ($query) use ($start, $end) {
            $query->whereHas('order.orderStatuses', function ($q) use ($start, $end) {
                $q->whereIn('order_status_id', [1, 2])
                    ->whereBetween('created_at', [$start, $end]);
            });
        }])->select('products.*')
            ->withCount(['items as items_sold' => function ($query) use ($start, $end) {
                $query->whereHas('order.orderStatuses', function ($q) use ($start, $end) {
                    $q->whereIn('order_status_id', [1, 2])
                        ->whereBetween('created_at', [$start, $end]);
                });
            }])->orderBy('items_sold', 'desc')
            ->take(10)
            ->get();

        return view('admin.OrderManagement.statistics', compact(
            'expectedRevenue',
            'actualRevenue',
            'canceledRevenue',
            'expectedRevenueData',
            'actualRevenueData',
            'canceledRevenueData',
            'pendingOrdersCount',
            'completedOrdersCount',
            'canceledOrdersCount',
            'topUsers',
            'topProducts',
            'filterType',
            'startDate',
            'endDate',
            'month',
            'year',
            'dateLabel',
            'currentYear',
            'labels'
        ));
    }
}
