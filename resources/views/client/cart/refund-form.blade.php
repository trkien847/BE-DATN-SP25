@extends('client.layouts.layout')
@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header bg-primary text-white text-center">
            <h2 class="mb-0">Nhập thông tin tài khoản để hoàn tiền</h2>
        </div>
        <div class="card-body">
            <p class="text-muted text-center mb-4">
                Đơn hàng <strong>{{ $order->code }}</strong> - Trạng thái: <span class="badge bg-info">{{ $order->latestOrderStatus->name }}</span>
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

            <form action="{{ route('order.refund.submit', $order->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="mb-3">
                    <label for="bank_name" class="form-label fw-bold">Tên ngân hàng <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('bank_name') is-invalid @enderror" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" placeholder="Ví dụ: Vietcombank" required>
                    @error('bank_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="account_number" class="form-label fw-bold">Số tài khoản <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('account_number') is-invalid @enderror" id="account_number" name="account_number" value="{{ old('account_number') }}" placeholder="Nhập số tài khoản" required>
                    @error('account_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="account_holder" class="form-label fw-bold">Tên chủ tài khoản <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('account_holder') is-invalid @enderror" id="account_holder" name="account_holder" value="{{ old('account_holder') }}" placeholder="Nhập tên đầy đủ" required>
                    @error('account_holder')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary px-4">Lưu thông tin</button>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script để validate form bằng Bootstrap -->
<script>
    (function () {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
@endsection