<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chi tiết đơn hàng #{{ $order->code }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .order-header {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .status-badge {
            font-size: 0.9em;
            padding: 5px 10px;
            border-radius: 15px;
        }

        .status-waiting-cancel {
            background-color: #ffc107;
            color: #fff;
        }

        .status-cancelled {
            background-color: #dc3545;
            color: #fff;
        }

        .cancel-reason {
            background-color: #f1f3f5;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .cancel-reason {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
    }
    .reason-info p {
        margin: 0; /* Xóa khoảng cách mặc định của <p> */
    }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="order-header">
            <h2 class="mb-0">Chi tiết đơn hàng #{{ $order->code }}</h2>
        </div>

        <!-- Thông tin tổng quan -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Thông tin đơn hàng</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Mã đơn hàng:</strong> {{ $order->code }}</p>
                        <p><strong>Khách hàng:</strong> {{ $order->user->fullname ?? 'Khách vãng lai' }}</p>
                        <p><strong>Số điện thoại:</strong> {{ $order->user->phone_number ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                        <p><strong>Tổng giá trị:</strong> {{ number_format($order->total_amount) }} VNĐ</p>
                        <p><strong>Trạng thái:</strong>
                            <span class="status-badge 
                                {{ $order->latestOrderStatus->name === 'Chờ hủy' ? 'status-waiting-cancel' : '' }}
                                {{ $order->latestOrderStatus->name === 'Đã hủy' ? 'status-cancelled' : '' }}">
                                {{ $order->latestOrderStatus->name }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Lý do hủy (nếu có) -->
                @if ($order->latestOrderStatus->name === 'Chờ hủy' || $order->latestOrderStatus->name === 'Đã hủy')
                <div class="cancel-reason">
                    <p><strong>Lý do hủy:</strong> {{ $order->orderStatuses->last()->note ?? 'Không có lý do' }}</p>
                    <p><strong>Người yêu cầu:</strong> {{ $order->user->fullname ?? 'Khách vãng lai' }}</p>
                    <p><strong>Thời gian yêu cầu:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                </div>
                @endif
                @if ($order->latestOrderStatus->name === 'Yêu cầu hoàn hàng')
                <div class="cancel-reason" style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                    <div class="reason-info" style="display: flex; gap: 15px;">
                        <p><strong>Lý do hủy:</strong> {{ $order->orderStatuses->last()->note ?? 'Không có lý do' }}</p>
                        <p><strong>Người yêu cầu:</strong> {{ $order->user->fullname ?? 'Khách vãng lai' }}</p>
                        <p><strong>Thời gian yêu cầu:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    @php
                    // Sửa lỗi typo: $orderstar -> $order->orderStatuses->last()
                    $images = json_decode($order->orderStatuses->last()->evidence ?? '[]', true);
                    $images = is_array($images) ? $images : [];
                    @endphp
                    @if(!empty($images))
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        @foreach($images as $image)
                        <img src="{{ asset('upload/' . $image) }}" alt="Evidence" style="max-width: 100px; max-height: 100px; object-fit: cover;">
                        @endforeach
                    </div>
                    @endif
                </div>

                @endif
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Sản phẩm trong đơn hàng</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $detail)
                        <tr>
                            <td>{{ $detail->product->name ?? 'Sản phẩm không tồn tại' }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>{{ number_format($detail->price) }} VNĐ</td>
                            <td>{{ number_format($detail->quantity * $detail->price) }} VNĐ</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                            <td>{{ number_format($order->total_amount) }} VNĐ</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Nút hành động (cho admin) -->
        @if ($order->latestOrderStatus->name === 'Chờ hủy' && Auth::user()->role_id === 3)
        <div class="d-flex gap-2 justify-content-end">
            <form action="{{ route('order.acceptCancel', $order->id) }}" method="POST" style="display:inline;">
                @csrf
                <input type="hidden" name="notification_id" value="{{ $notificationId }}">
                <button type="submit" class="btn btn-success">Chấp nhận hủy</button>
            </form>
            <form action="{{ route('order.rejectCancel', $order->id) }}" method="POST" style="display:inline;">
                @csrf
                <input type="hidden" name="notification_id" value="{{ $notificationId }}">
                <button type="submit" class="btn btn-danger">Từ chối hủy</button>
            </form>
        </div>
        @endif
        @if ($order->latestOrderStatus->name === 'Yêu cầu hoàn hàng' && Auth::user()->role_id === 3)
        <div class="d-flex gap-2 justify-content-end">
            <form action="{{ route('order.acceptCancel', $order->id) }}" method="POST" style="display:inline;">
                @csrf
                <input type="hidden" name="notification_id" value="{{ $notificationId }}">
                <button type="submit" class="btn btn-success">Chấp nhận hủy</button>
            </form>
            <form action="{{ route('order.rejectCancel', $order->id) }}" method="POST" style="display:inline;">
                @csrf
                <input type="hidden" name="notification_id" value="{{ $notificationId }}">
                <button type="submit" class="btn btn-danger">Từ chối hủy</button>
            </form>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>