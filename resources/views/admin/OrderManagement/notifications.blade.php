@extends('admin.layouts.layout')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Chi tiết yêu cầu cập nhật đơn hàng</h2>
        </div>
        <div class="card-body">
            <!-- Thông tin chi tiết -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="mb-2"><strong>Mã đơn hàng:</strong> <span class="text-muted">#{{ $order_id }}</span></p>
                    <p class="mb-2"><strong>Nhân viên yêu cầu:</strong> <span class="text-muted">{{ $requester_name }}</span></p>
                    <p class="mb-2"><strong>Tên khách hàng:</strong> <span class="text-muted">{{ $customer_name }}</span></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2"><strong>Số tiền:</strong> <span class="text-muted">{{ number_format($amount) }} VND</span></p>
                    <p class="mb-2"><strong>Trạng thái yêu cầu:</strong> 
                        <span class="badge {{ $status === 'Hoàn thành' ? 'bg-success' : 'bg-danger' }}">{{ $status }}</span>
                    </p>
                </div>
            </div>

            <!-- Ảnh minh chứng -->
            @if($evidence)
                <div class="mb-3">
                    <p><strong>Ảnh minh chứng:</strong></p>
                    <img src="{{ asset('upload/' . $evidence) }}" alt="Evidence" class="img-fluid rounded" style="max-width: 400px; max-height: 400px;">
                </div>
            @endif

            <!-- Nút hành động -->
            <div class="d-flex gap-2 justify-content-end">
                <!-- Form Xác nhận -->
                <form action="{{ route('notifications.accept', $order_id) }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="notification_id" value="{{ request()->query('notification_id') }}">
                    <button type="submit" class="btn btn-success">Xác nhận</button>
                </form>

                <!-- Form Từ chối -->
                <form action="{{ route('notifications.cancel', $order_id) }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="notification_id" value="{{ request()->query('notification_id') }}">
                    <button type="submit" class="btn btn-danger">Từ chối</button>
                </form>

                <!-- Nút Quay lại -->
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>
</div>
@endsection