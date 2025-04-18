@extends('admin.layouts.layout')
@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-info text-white text-center">
            <h2 class="mb-0">Chi tiết yêu cầu hoàn tiền</h2>
        </div>
        <div class="card-body">
            <h5>Đơn hàng: {{ $order->code }}</h5>
            <p><strong>Người mua:</strong> {{ $order->user->name }}</p>
            <p><strong>Tổng giá:</strong> {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</p>
            <p><strong>Số lượng sản phẩm:</strong> {{ $order->items->sum('quantity') }}</p>

            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h5 class="mt-4">Tải ảnh chuyển khoản</h5>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($order->refund_proof_image)
                <p>Ảnh đã tải lên: <a href="{{ Storage::url($order->refund_proof_image) }}" target="_blank">Xem ảnh</a></p>
            @else
                <form action="{{ route('order.refund.upload', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="proof_image" class="form-label">Chọn ảnh chuyển khoản</label>
                        <input type="file" class="form-control @error('proof_image') is-invalid @enderror" id="proof_image" name="proof_image" required>
                        @error('proof_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <input type="hidden" name="notification_id" value="{{$notificationId}}">
                    <button type="submit" class="btn btn-primary">Tải lên</button>
                </form>
            @endif
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mt-3">Quay lại</a>
        </div>
    </div>
</div>
@endsection