@extends('admin.layouts.layout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<style>
    .order-detail-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); 
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000; 
    }

    .order-detail-card {
        width: 80%; 
        max-width: 900px;
        max-height: 80vh; 
        overflow-y: auto; 
    }
</style>
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="text-secondary">DANH SÁCH ĐƠN HÀNG</h4>
        {{-- <button class="btn btn-success btn-l" data-bs-toggle="modal" data-bs-target="#addProductModal">
          <i class="bi bi-plus-circle"></i> Thêm Sản Phẩm
        </button> --}}
    </div>
    <ul class="nav" id="statusFilter">
        <li class="nav-item">
            <a class="nav-link active" href="#" data-status="">Tất cả đơn giao hàng</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-status="1">Chờ xác nhận</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-status="2">Chờ giao hàng</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-status="3">Đang giao hàng</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-status="4">Đã giao hàng</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-status="6">Hoàn thành</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-status="7">Đã hủy</a>
        </li>
    </ul>
    <div class="mt-3">
        <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Mã đơn hàng</th>
                    <th scope="col">Tên khách hàng</th>
                    <th scope="col">Mã giảm giá</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Tổng hóa đơn</th>
                    <th scope="col">Người xử lý</th>
                    <th scope="col">Thao tác</th>
                </tr>
            </thead>
            <tbody id="orderBody">
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->code }}</td>
                    <td>{{ $order->fullname }}</td>
                    <td>{{ $order->coupon_code ?? 'Không có' }}</td>
                    <td class="status-cancelled">
                        <select name="status" class="form-select form-select-xs text-xs status-select" data-order-id="{{ $order->id }}">
                            @php
                            $latestStatus = $order->orderStatuses->sortByDesc('created_at')->first();
                            $currentStatus = $latestStatus ? $latestStatus->orderStatus->name : '';
                            @endphp
                            <option value="1" {{ $currentStatus == 'Chờ xác nhận' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="2" {{ $currentStatus == 'Chờ giao hàng' ? 'selected' : '' }}>Chờ giao hàng</option>
                            <option value="3" {{ $currentStatus == 'Đang giao hàng' ? 'selected' : '' }}>Đang giao hàng</option>
                            <option value="4" {{ $currentStatus == 'Đã giao hàng' ? 'selected' : '' }}>Đã giao hàng</option>
                            <option value="6" {{ $currentStatus == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="7" {{ $currentStatus == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                        <div class="evidence-upload" style="display: none;">
                            <input type="file" class="evidence-file" accept="image/*">
                        </div>
                    </td>
                    <td>{{ number_format($order->total_amount, 0, ',', '.') }} VND</td>
                    <td>{{ $latestStatus->modifier->fullname ?? 'Chưa có' }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm detail-btn" data-order-id="{{ $order->id }}">Chi tiết đơn hàng</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>



    <div id="orderDetail" class="order-detail-overlay" style="display: none;">
        <div class="card order-detail-card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Chi tiết đơn hàng <span id="orderCode"></span></h5>
                <button class="btn-close btn-close-white" id="closeDetail"></button>
            </div>
            <div class="card-body">
                <div class="row">
                   
                    <div class="col-md-8">
                        <h6>Danh sách sản phẩm</h6>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tên sản phẩm</th>
                                    <th>Biến thể</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                </tr>
                            </thead>
                            <tbody id="orderItems"></tbody>
                        </table>
                        <p><strong>Tổng đơn hàng:</strong> <span id="totalAmount"></span></p>
                        <p><strong>Mã giảm giá:</strong> <span id="couponCode"></span></p>
                    </div>
                   
                    <div class="col-md-4">
                        <h6>Thông tin khách hàng</h6>
                        <ul class="list-unstyled">
                            <li><strong>Tên:</strong> <span id="customerName"></span></li>
                            <li><strong>Địa chỉ:</strong> <span id="customerAddress"></span></li>
                            <li><strong>Số điện thoại:</strong> <span id="customerPhone"></span></li>
                            <li><strong>Email:</strong> <span id="customerEmail"></span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

// Lọc theo trạng thái
document.querySelectorAll('#statusFilter .nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const status = this.dataset.status;

        fetch(`/admin/orders?status=${status}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newOrderBody = doc.querySelector('#orderBody');
            document.getElementById('orderBody').innerHTML = newOrderBody.innerHTML;
            document.querySelectorAll('#statusFilter .nav-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: 'Không thể lọc đơn hàng: ' + error.message,
                confirmButtonColor: '#d33'
            });
        });
    });
});

document.querySelectorAll('.detail-btn').forEach(button => {
    button.addEventListener('click', function() {
        const orderId = this.dataset.orderId;

        fetch(`/admin/orders/${orderId}/details`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data.order || !data.items) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Không thể tải chi tiết đơn hàng',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            
            document.getElementById('orderCode').textContent = data.order.code;
            document.getElementById('totalAmount').textContent = Number(data.order.total_amount).toLocaleString('vi-VN') + ' VND';
            document.getElementById('couponCode').textContent = data.order.coupon_code || 'Không có';
            document.getElementById('customerName').textContent = data.order.fullname;
            document.getElementById('customerAddress').textContent = data.order.address;
            document.getElementById('customerPhone').textContent = data.order.phone_number;
            document.getElementById('customerEmail').textContent = data.order.email;

            const itemsBody = document.getElementById('orderItems');
            itemsBody.innerHTML = ''; 

            data.items.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.product ? item.product.name : 'Không có'}</td>
                    <td>${item.name_variant || 'Không có'}</td>
                    <td>${item.quantity}</td>
                    <td>${Number(item.price).toLocaleString('vi-VN')} VND</td>
                `;
                itemsBody.appendChild(row);
            });
            document.getElementById('orderDetail').style.display = 'flex';
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: 'Không thể tải chi tiết đơn hàng: ' + error.message,
                confirmButtonColor: '#d33'
            });
        });
    });
});

document.getElementById('closeDetail').addEventListener('click', function() {
    document.getElementById('orderDetail').style.display = 'none';
});


document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', function() {
        const orderId = this.dataset.orderId;
        const statusId = this.value;
        const evidenceUpload = this.closest('td').querySelector('.evidence-upload');
        const evidenceFile = this.closest('td').querySelector('.evidence-file');

       
        if (statusId == 6 || statusId == 7) {
            evidenceUpload.style.display = 'block'; 
            evidenceFile.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    uploadStatus(orderId, statusId, this.files[0]);
                }
            }, { once: true });
        } else {
            evidenceUpload.style.display = 'none';
            uploadStatus(orderId, statusId, null);
        }
    });
});

function uploadStatus(orderId, statusId, file) {
    const formData = new FormData();
    formData.append('order_id', orderId);
    formData.append('status_id', statusId);
    formData.append('modified_by', '{{ auth()->id() ?? 1 }}'); 
    if (file) {
        formData.append('evidence', file);
    }

    fetch('/admin/orders/update-status', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload(); 
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: data.message,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Đóng'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Lỗi hệ thống!',
            text: 'Đã có lỗi xảy ra: ' + error.message,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Đóng'
        });
    });
}
</script>
@endsection