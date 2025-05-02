@extends('client.layouts.layout')
@section('content')
<style>
    .form-container {
        max-width: 500px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .submit-btn {
        padding: 10px 20px;
        background-color: #f44336;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .submit-btn:hover {
        background-color: #da190b;
    }
</style>

<div class="form-container">
    <div class="alert alert-warning" style="color: #b94a48; background: #f2dede; padding: 10px; border-radius: 5px;">
        Hôm nay bạn đã hủy {{ $cancelCountToday }} đơn hàng.
        @if($cancelCountToday >= 3)
        <br><strong>Cảnh báo:</strong> Hủy quá 3 đơn/ngày sẽ bị khóa tài khoản 3 ngày!
        @endif
    </div>
    <h2>Hủy đơn hàng {{ $order->code }}</h2>
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
@endsection