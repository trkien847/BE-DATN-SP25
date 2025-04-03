@extends('client.layouts.layout')
@section('content')
<style>
    /* Table Styles */
    .order-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .order-table th,
    .order-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }

    .order-table th {
        background-color: #4CAF50;
        color: white;
        font-weight: 600;
    }

    .order-table tr:hover {
        background-color: #f5f5f5;
    }

    .detail-btn,
    .cancel-btn,
    .return-btn {
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-right: 5px;
    }

    .detail-btn {
        background-color: #4CAF50;
        color: white;
    }

    .detail-btn:hover {
        background-color: #45a049;
    }

    .cancel-btn {
        background-color: #f44336;
        color: white;
    }

    .cancel-btn:hover {
        background-color: #da190b;
    }

    .return-btn {
        background-color: #ff9800;
        color: white;
    }

    .return-btn:hover {
        background-color: #e68a00;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        position: relative;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 24px;
        color: #888;
        cursor: pointer;
        border: none;
        background: none;
    }

    .close-btn:hover {
        color: #333;
    }

    .order-details h3 {
        color: #4CAF50;
        margin-bottom: 15px;
    }

    .order-details p {
        margin: 8px 0;
        font-size: 16px;
    }

    .order-details ul {
        list-style: none;
        padding: 0;
    }

    .order-details ul li {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .order-details ul li:last-child {
        border-bottom: none;
    }

    .status-cancelled {
        color: #ff0000;
    }
    .status-pending-cancel {
        color: #ffc107; 
    }
    .status-default {
        color: #28a745; 
    }
</style>

<div class="container">
    <h1>Lịch Sử Mua Hàng</h1>

    @if(session('success'))
    <div style="padding: 10px; background-color: #dff0d8; color: #3c763d; margin-bottom: 15px;">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div style="padding: 10px; background-color: #f2dede; color: #a94442; margin-bottom: 15px;">
        {{ session('error') }}
    </div>
    @endif

    <table class="order-table">
        <thead>
            <tr>
                <th>Mã đơn hàng</th>
                <th>Số lượng sản phẩm</th>
                <th>Tổng giá trị</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->code }}</td>
                <td>{{ $order->items->sum('quantity') }}</td>
                <td>{{ number_format($order->total_amount) }} VNĐ</td>
                <td>
                    @php
                        $statusName = $order->latestOrderStatus->name ?? 'Chưa có trạng thái';
                    @endphp
                    <span class="{{ $statusName === 'Đã hủy' ? 'text-danger' : ($statusName === 'Chờ hủy' ? 'text-warning' : 'text-success') }}">
                        {{ $statusName }}
                    </span>
                </td>
                <td>
                    <button class="detail-btn" onclick="showModal('order{{ $order->id }}')">
                        Xem chi tiết
                    </button>
                    @if(in_array($order->latestOrderStatus->name ?? '', ['Chờ xác nhận', 'Chờ giao hàng']))
                        <a href="{{ route('order.cancel', $order->id) }}" class="cancel-btn">Hủy đơn hàng</a>
                    @endif
                    @if(($order->latestOrderStatus->name ?? '') === 'Hoàn thành' && $order->completedStatusTimestamp() && \Carbon\Carbon::parse($order->completedStatusTimestamp())->diffInDays(\Carbon\Carbon::now()) <= 7)
                        <a href="{{ route('order.return', $order->id) }}" class="return-btn">Hoàn hàng</a>
                    @endif
                    @if(in_array($order->latestOrderStatus->name ?? '', ['Chờ hoàn tiền']))
                        <a href="{{ route('order.refund.form', $order->id) }}" class="cancel-btn">Nhập thông tin tài khoản</a>
                    @endif
                    @if(in_array($order->latestOrderStatus->name ?? '', ['Chuyển khoản thành công']))
                        <a href="{{ route('order.refund.confirm', $order->id) }}" class="cancel-btn">Xác nhận nhận tiền</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @foreach($orders as $order)
    <div id="order{{ $order->id }}" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="hideModal('order{{ $order->id }}')">×</button>
            <div class="order-details">
                <h3>Chi tiết đơn hàng {{ $order->code }}</h3>
                <p><strong>Ngày mua:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                <p><strong>Trạng thái:</strong> {{ $order->latestOrderStatus->name ?? 'Chưa có trạng thái' }}</p>
                <p><strong>Mã đơn hàng:</strong> {{ $order->code }}</p>
                <p><strong>Ảnh hoàn tiền:</strong> <img src="{{ asset('upload/'.$order->refund_proof_image) }}" class="img-thumbnail" alt="Product Image" width="100px" height="100px"></p>

                <h4>Thông tin sản phẩm:</h4>
                <ul>
                    @foreach($order->items as $item)
                    <li>
                        <strong>Sản phẩm:</strong> {{ $item->name }} <br>
                        <strong>Biến thể:</strong> {{ $item->name_variant ?? 'Không có' }}
                        @if($item->attributes_variant)
                        ({{ $item->attributes_variant }})
                        @endif <br>
                        <strong>Giá:</strong> {{ number_format($item->price_variant ?? $item->price) }} VNĐ <br>
                        <strong>Số lượng:</strong> {{ $item->quantity }}
                    </li>
                    @endforeach
                </ul>

                @if($order->coupon_code)
                <p><strong>Mã giảm giá:</strong> {{ $order->coupon_code }}
                    (Giảm {{ $order->coupon_discount_value }}
                    {{ $order->coupon_discount_type === 'percent' ? '%' : 'VNĐ' }})
                </p>
                @endif
                <p><strong>Tổng cộng:</strong> {{ number_format($order->total_amount) }} VNĐ</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

<script>
    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.style.display = 'flex';
    }

    function hideModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.style.display = 'none';
    }

    window.onclick = function(event) {
        const modals = document.getElementsByClassName('modal');
        for (let i = 0; i < modals.length; i++) {
            if (event.target === modals[i]) {
                modals[i].style.display = 'none';
            }
        }
    }
</script>
@endsection