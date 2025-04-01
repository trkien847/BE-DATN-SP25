<div class="container py-4">
    <div class="card shadow-sm border-0" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header bg-success text-white text-center">
            <h2 class="mb-0">Xác nhận nhận tiền hoàn</h2>
        </div>
        <div class="card-body">
            <p class="text-muted text-center mb-4">
                Đơn hàng <strong>{{ $order->code }}</strong> - Trạng thái: <span class="badge bg-success">{{ $order->latestOrderStatus->name }}</span>
            </p>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('order.refund.confirm.submit', $order->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <p>Bạn đã nhận được tiền hoàn cho đơn hàng này chưa? Vui lòng xác nhận.</p>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success px-4">Xác nhận</button>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>