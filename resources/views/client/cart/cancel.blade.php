
@extends('client.layouts.layout')
@section('content')
<style>
    .form-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    .order-info, .cancel-form {
        flex: 1;
        min-width: 300px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        /* Animation properties */
        opacity: 0;
        transform: translateX(-100%);
        animation: slideInFromLeft 0.6s ease-out forwards;
    }
    .order-info {
        animation-delay: 0.2s; /* Appears first */
    }
    .cancel-form {
        animation-delay: 0.4s; /* Appears slightly later */
    }
    .order-status {
        margin-bottom: 20px;
    }
    .order-items ul {
        list-style: none;
        padding: 0;
    }
    .order-items li {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        padding: 10px;
        border-bottom: 1px solid #eee;
    }
    .order-items img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        margin-right: 15px;
        border-radius: 5px;
    }
    .item-details {
        flex: 1;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
    }
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .submit-btn {
        background-color: #ff4d4f;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .submit-btn:hover {
        background-color: #d9363e;
    }
    @keyframes slideInFromLeft {
        0% {
            opacity: 0;
            transform: translateX(-100%);
        }
        100% {
            opacity: 1;
            transform: translateX(0);
        }
    }
    @media (max-width: 768px) {
        .form-container {
            flex-direction: column;
        }
        .order-info, .cancel-form {
            width: 100%;
        }
    }
</style>

<div class="form-container">
    <!-- Order Information -->
    <div class="order-info">
        <h2>Thông tin đơn hàng {{ $order->code }}</h2>

        <!-- Display Order Status -->
        <div class="order-status">
            <h3>Trạng thái đơn hàng</h3>
            <p>
                <strong>Trạng thái:</strong> 
                {{ $order->latestOrderStatus ? $order->latestOrderStatus->name : 'Chưa xác định' }}
            </p>
        </div>

        <!-- Display Order Items -->
        <div class="order-items">
            <h3>Chi tiết đơn hàng</h3>
            <ul>
                @foreach ($order->items as $item)
                    <li>
                        <img src="{{ $item->product && $item->product->thumbnail ? asset('upload/' . $item->product->thumbnail) : asset('upload/placeholder.jpg') }}" alt="{{ $item->product ? $item->product->name : $item->name }}">
                        <div class="item-details">
                            <strong>Sản phẩm:</strong> {{ $item->product ? $item->product->name : $item->name }} <br>
                            <strong>Giá:</strong> {{ number_format($item->price, 0, ',', '.') }} VND <br>
                            <strong>Số lượng:</strong> {{ $item->quantity }} <br>
                            @if ($item->name_variant)
                                <strong>Biến thể:</strong> {{ $item->name_variant }} <br>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Cancel Form -->
    <div class="cancel-form">
        <h2>Hủy đơn hàng</h2>
        <form action="{{ route('order.cancel', $order->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="cancel_reason">Lý do hủy đơn hàng:</label>
                <textarea name="cancel_reason" id="cancel_reason" rows="4" required></textarea>
                @error('cancel_reason')
                    <span style="color: red;">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="submit-btn">Xác nhận hủy</button>
        </form>
    </div>
</div>
@endsection
