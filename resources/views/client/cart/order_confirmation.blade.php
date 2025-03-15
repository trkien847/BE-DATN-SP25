<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đơn hàng</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 10px; text-align: center; }
        .table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Xác nhận đơn hàng #{{ $order->code }}</h2>
        </div>

        <p>Xin chào {{ $order->fullname }},</p>
        <p>Cảm ơn bạn đã đặt hàng! Dưới đây là thông tin chi tiết:</p>

        <h3>Thông tin người mua</h3>
        <p><strong>Họ tên:</strong> {{ $order->fullname }}</p>
        <p><strong>Email:</strong> {{ $order->email }}</p>
        <p><strong>Số điện thoại:</strong> {{ $order->phone_number }}</p>
        <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>

        <h3>Chi tiết đơn hàng</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($selectedProducts as $product)
                    <tr>
                        <td><img src="{{ $product['thumbnail'] }}" width="50" alt="{{ $product['name'] }}"></td>
                        <td>{{ $product['name'] }} ({{ $product['name_variant'] }}: {{ $product['variant_value'] }})</td>
                        <td>{{ $product['quantity'] }}</td>
                        <td>{{ number_format($product['price']) }}đ</td>
                        <td>{{ number_format($product['price'] * $product['quantity']) }}đ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount) }}đ</p>
        <p><strong>Ngày giờ đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
        <p><strong>Phương thức thanh toán:</strong> 
            @if ($order->payment_id == 1)
                Tiền mặt
            @elseif ($order->payment_id == 2)
                Tài khoản (VNPay)
            @else
                Chưa xác định
            @endif
        </p>

        <div class="footer">
            <p>Trân trọng,<br>{{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>