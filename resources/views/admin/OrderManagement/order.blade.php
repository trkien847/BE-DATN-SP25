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

    .nav-link {
    position: relative; 
    transition: transform 0.2s ease, background-color 0.3s ease;
    padding: 8px 16px; 
    display: inline-block;
}

.nav-link.active {
    background-color:rgb(208, 209, 209); 
    color: white; 
    transform: scale(1.1); 
}


.nav-link.clicked {
    transform: scale(1.15); 
    background-color: #1bb394; 
}

.nav-link::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.4s ease, height 0.4s ease;
    z-index: 0;
}

.pagination {
    display: flex;
    gap: 4px;
    align-items: center;
}

.pagination button {
    min-width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pagination button.active {
    background-color: #1bb394;
    color: white;
    border-color: #1bb394;
}

.pagination button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-info {
    color: #6c757d;
    font-size: 0.875rem;
}

.nav-link.clicked::after {
    width: 100px; 
    height: 100px;
    opacity: 0;
}
.nav-link.clicked {
    animation: bounce 0.5s ease;
}

.expiry-warning {
    color: #dc3545;
    font-weight: bold;
}

.table td:nth-child(5),
.table td:nth-child(6) {
    white-space: nowrap;
    font-size: 0.9em;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

.animated-word {
    display: inline-block;
    opacity: 0;
    animation: appearAndDisappear 1s forwards;
    margin-right: 5px;
}

@keyframes appearAndDisappear {
    0% { opacity: 0; }
    20% { opacity: 1; }
    80% { opacity: 1; }
    100% { opacity: 0; }
}

#animatedText {
    white-space: nowrap;
}
</style>

<div class="container">
    <ul class="nav" id="statusFilter">
        <li class="nav-item">
            <a class="nav-link active" href="#" data-status=""  style="color:rgb(3, 3, 3);">Tất cả đơn giao hàng</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-status="1"style="color:rgb(3, 3, 3);">Chờ xác nhận *</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-status="2"style="color:rgb(3, 3, 3);">Chờ giao hàng *</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-status="3"style="color:rgb(3, 3, 3);">Đang giao hàng *</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-status="4"style="color:rgb(3, 3, 3);">Đã giao hàng</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" data-status="6"style="color:rgb(3, 3, 3);">Hoàn thành</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-status="7"style="color:rgb(3, 3, 3);">Đã hủy</a>
        </li>
    </ul>
    
    <div class="mt-3">
        <form id="filterForm" class="row g-3">
            <div class="col-md-3">
                <label for="startDate" class="form-label">Từ ngày</label>
                <input type="date" class="form-control" id="startDate" name="start_date">
            </div>
            <div class="col-md-3">
                <label for="endDate" class="form-label">Đến ngày</label>
                <input type="date" class="form-control" id="endDate" name="end_date">
            </div>
            <div class="col-md-3">
                <label for="customerName" class="form-label">Tên khách hàng</label>
                <input type="text" class="form-control" id="customerName" name="customer_name" placeholder="Nhập tên khách hàng">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                <button type="button" id="resetFilter" class="btn btn-secondary ms-2">Hủy</button>
            </div>
        </form>
    </div>

    <div class="mt-3">
    <div class="mt-3 bulk-update-section" style="display: none;">
            <select id="bulkStatus" class="form-select w-auto d-inline-block">
                <option value="">Chọn trạng thái</option>
                <option value="1">Chờ xác nhận</option>
                <option value="2">Chờ giao hàng</option>
                <option value="3">Đang giao hàng</option>
                <option value="4">Đã giao hàng</option>
            </select>
            <button id="updateBulkStatus" class="btn btn-primary">Cập nhật trạng thái</button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col" class="checkbox-column" style="display: none;"><input type="checkbox" id="selectAll"></th>
                        <th scope="col">Mã đơn hàng</th>
                        <th scope="col">Tên khách hàng</th>
                        <th scope="col">Ngày mua</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Tổng hóa đơn</th>
                        <th scope="col">Người xử lý</th>
                        <th scope="col">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="orderBody">
                    @foreach($orders as $order)
                    <tr>
                        <td class="checkbox-column" style="display: none;"><input type="checkbox" class="orderCheckbox" value="{{ $order->id }}"></td>
                        <td>{{ $order->code }}</td>
                        <td>{{ $order->fullname }}</td>
                        <td>{{ $order->created_at }}</td>
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
                                <option><hr></option>
                                <option value="" {{ $currentStatus == 'Chờ hủy' ? 'selected' : '' }}>Chờ hủy</option>
                                <option value="" {{ $currentStatus == 'Chờ hoàn tiền' ? 'selected' : '' }}>Chờ hoàn tiền</option>
                                <option value="" {{ $currentStatus == 'Xác nhận thông tin' ? 'selected' : '' }}>Xác nhận thông tin</option>
                                <option value="" {{ $currentStatus == 'Chuyển khoản thành công' ? 'selected' : '' }}>Chuyển khoản thành công</option>
                                <option value="" {{ $currentStatus == 'Yêu cầu hoàn hàng' ? 'selected' : '' }}>Yêu cầu hoàn hàng</option>
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
        <!-- Add this pagination container -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="pagination-info">
                Hiển thị <span id="showing">0-0</span> trong tổng số <span id="total">0</span> đơn hàng
            </div>
            <div id="pagination" class="pagination"></div>
        </div>
        
        
    </div>



    <div id="orderDetail" class="order-detail-overlay" style="display: none;">
        <div class="card order-detail-card">
            <div class="card-header  text-white d-flex justify-content-between align-items-center" style="background: #1bb394;">
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
                                    <th>Tên</th>
                                    <th>Biến thể</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>HSD</th>
                                    <th>Mã lô</th>
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
                            <li><strong>Tên:</strong> <span id="customerNameo"></span></li>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const modifiedBy = {{ $currentUserId ?? 'null' }};

function attachStatusEvents() {
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
}

function attachDetailEvents() {
    document.querySelectorAll('.detail-btn').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;

            Swal.fire({
                imageUrl: 'https://th.bing.com/th/id/R.8019ed81282258112700446dfa572f4b?rik=YV9%2bwXN8AeFcQQ&pid=ImgRaw&r=0',
                imageWidth: 200, 
                imageHeight: 100, 
                imageAlt: 'Đang Say Gex...', 
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/admin/orders/${orderId}/details`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.close(); 

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
                document.getElementById('customerNameo').textContent = data.order.fullname;
                document.getElementById('customerAddress').textContent = data.order.address;
                document.getElementById('customerPhone').textContent = data.order.phone_number;
                document.getElementById('customerEmail').textContent = data.order.email;

                const itemsBody = document.getElementById('orderItems');
                itemsBody.innerHTML = ''; 
                data.items.forEach(item => {
                const row = document.createElement('tr');

                // Format manufacture_date
                const manufactureDate = item.manufacture_date 
                    ? new Date(item.manufacture_date).toLocaleDateString('vi-VN') 
                    : 'Không có';

                // Tính thời gian hết hạn (days_until_expiry)
                let expiryDays = item.days_until_expiry !== null ? item.days_until_expiry : 'Không có';
                let expiryClass = '';

                if (item.days_until_expiry !== null && item.days_until_expiry <= 30) {
                    expiryClass = 'expiry-warning';
                }

                // Lấy import_code
                const importCode = item.import_code || 'Không có';

                row.innerHTML = `
                    <td>${item.product ? item.product.name : 'Không có'}</td>
                    <td>${item.name_variant || 'Không có'}</td>
                    <td>${item.quantity}</td>
                    <td>${Number(item.price).toLocaleString('vi-VN')} VND</td>
                    <td class="${expiryClass}">${expiryDays} ngày</td>
                    <td>${importCode}</td>
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
}

// Hàm lọc đơn hàng
function filterOrders(status = '', startDate = '', endDate = '', customerName = '') {
    const url = new URL('/admin/orders', window.location.origin);
    if (status) url.searchParams.append('status', status);
    if (startDate) url.searchParams.append('start_date', startDate);
    if (endDate) url.searchParams.append('end_date', endDate);
    if (customerName) url.searchParams.append('customer_name', customerName);

    Swal.fire({
                imageUrl: 'https://th.bing.com/th/id/R.8019ed81282258112700446dfa572f4b?rik=YV9%2bwXN8AeFcQQ&pid=ImgRaw&r=0',
                imageWidth: 200, 
                imageHeight: 100, 
                imageAlt: 'Đang Say Gex...', 
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

    const startTime = Date.now();

    fetch(url, {
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

        const checkboxColumns = document.querySelectorAll('.checkbox-column');
        const bulkUpdateSection = document.querySelector('.bulk-update-section');

        if (startDate || endDate || customerName) {
            checkboxColumns.forEach(col => col.style.display = 'none');
            bulkUpdateSection.style.display = 'none';
        } else {
            if (status === '6' || status === '7' || status === '4' || status === '') {
                checkboxColumns.forEach(col => col.style.display = 'none');
                bulkUpdateSection.style.display = 'none';
            } else {
                checkboxColumns.forEach(col => col.style.display = 'table-cell');
                bulkUpdateSection.style.display = 'block';
            }
        }

        attachDetailEvents();

        const elapsedTime = Date.now() - startTime;
        const minDisplayTime = 1000; 

        if (elapsedTime < minDisplayTime) {
            setTimeout(() => {
                Swal.close();
            }, minDisplayTime - elapsedTime);
        } else {
            Swal.close();
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: 'Không thể lọc đơn hàng: ' + error.message,
            confirmButtonColor: '#d33'
        });
    });
}

// Lọc theo form
document.getElementById('filterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const status = document.querySelector('#statusFilter .nav-link.active').dataset.status || '';
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const customerName = document.getElementById('customerName').value;
    const checkboxColumns = document.querySelectorAll('.checkbox-column');
    const bulkUpdateSection = document.querySelector('.bulk-update-section');

    checkboxColumns.forEach(col => col.style.display = 'none');
    bulkUpdateSection.style.display = 'none';

    filterOrders(status, startDate, endDate, customerName);
});

// Xóa bộ lọc
document.getElementById('resetFilter').addEventListener('click', function() {
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    document.getElementById('customerName').value = '';
    const activeLink = document.querySelector('#statusFilter .nav-link.active');
    const status = activeLink ? activeLink.dataset.status : '';
    filterOrders(status);
});

$(document).ready(function() {
    $('#selectAll').on('click', function() {
        $('.orderCheckbox').prop('checked', this.checked);
    });

    $('#updateBulkStatus').on('click', function() {
        const selectedOrders = $('.orderCheckbox:checked').map(function() {
            return $(this).val();
        }).get();

        const newStatus = $('#bulkStatus').val();

        if (selectedOrders.length === 0) {
            alert('Vui lòng chọn ít nhất một đơn hàng!');
            return;
        }

        if (!newStatus) {
            alert('Vui lòng chọn trạng thái để cập nhật!');
            return;
        }

        if (!modifiedBy) {
            alert('Bạn cần đăng nhập để thực hiện thao tác này!');
            return;
        }

        $.ajax({
            url: '/update-bulk-status',
            method: 'POST',
            data: {
                order_ids: selectedOrders,
                status_id: newStatus,
                modified_by: modifiedBy,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    alert('Cập nhật trạng thái thành công!');
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                alert('Đã xảy ra lỗi: ' + xhr.responseJSON.message);
            }
        });
    });
});


attachStatusEvents();
document.getElementById('closeDetail').addEventListener('click', function() {
    document.getElementById('orderDetail').style.display = 'none';
});


document.querySelectorAll('.detail-btn').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;

            Swal.fire({
                imageUrl: 'https://th.bing.com/th/id/R.8019ed81282258112700446dfa572f4b?rik=YV9%2bwXN8AeFcQQ&pid=ImgRaw&r=0',
                imageWidth: 200, 
                imageHeight: 100, 
                imageAlt: 'Đang Say Gex...', 
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/admin/orders/${orderId}/details`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.close(); 

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
                document.getElementById('customerNameo').textContent = data.order.fullname;
                document.getElementById('customerAddress').textContent = data.order.address;
                document.getElementById('customerPhone').textContent = data.order.phone_number;
                document.getElementById('customerEmail').textContent = data.order.email;

                const itemsBody = document.getElementById('orderItems');
                itemsBody.innerHTML = ''; 
                data.items.forEach(item => {
                const row = document.createElement('tr');
                const manufactureDate = item.manufacture_date 
                    ? new Date(item.manufacture_date).toLocaleDateString('vi-VN') 
                    : 'Không có';
                let expiryDays = item.days_until_expiry !== null ? item.days_until_expiry : 'Không có';
                let expiryClass = '';

                if (item.days_until_expiry !== null && item.days_until_expiry <= 30) {
                    expiryClass = 'expiry-warning';
                }
                const importCode = item.import_code || 'Không có';

                row.innerHTML = `
                    <td>${item.product ? item.product.name : 'Không có'}</td>
                    <td>${item.name_variant || 'Không có'}</td>
                    <td>${item.quantity}</td>
                    <td>${Number(item.price).toLocaleString('vi-VN')} VND</td>
                    <td class="${expiryClass}">${expiryDays} ngày</td>
                    <td>${importCode}</td>
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


document.querySelectorAll('#statusFilter .nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault(); 

        document.querySelectorAll('#statusFilter .nav-link').forEach(l => l.classList.remove('active'));
        this.classList.add('active');
        const status = this.dataset.status;

        this.classList.add('clicked');
        setTimeout(() => {
            this.classList.remove('clicked');
        }, 300);

        const checkboxColumns = document.querySelectorAll('.checkbox-column');
        const bulkUpdateSection = document.querySelector('.bulk-update-section');
        const filterForm = document.getElementById('filterForm');

        
        if (status === '6' || status === '7' || status === '4') {
           
            checkboxColumns.forEach(col => col.style.display = 'none');
            bulkUpdateSection.style.display = 'none';
        } else {
           
            checkboxColumns.forEach(col => col.style.display = 'table-cell');
            bulkUpdateSection.style.display = 'block';
        }

        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const customerName = document.getElementById('customerName').value;

        filterOrders(status, startDate, endDate, customerName);
    });
});


document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    const rowsPerPage = 5;
    const tableBody = document.getElementById('orderBody');
    const paginationContainer = document.getElementById('pagination');
    
    function initializePagination() {
        const rows = Array.from(tableBody.getElementsByTagName('tr'));
        const totalPages = Math.ceil(rows.length / rowsPerPage);
        
        updateShowingText(rows.length);
        createPaginationButtons(totalPages);
        
        showPage(1);
    }
    
    function showPage(page) {
        const rows = Array.from(tableBody.getElementsByTagName('tr'));
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        rows.forEach(row => row.style.display = 'none');
        
        rows.slice(start, end).forEach(row => row.style.display = '');
        
        currentPage = page;
        
        updateShowingText(rows.length, start + 1, Math.min(end, rows.length));
        
        updatePaginationButtons();
    }
    
    function createPaginationButtons(totalPages) {
        paginationContainer.innerHTML = '';
        
        const prevButton = createButton('«', () => {
            if (currentPage > 1) showPage(currentPage - 1);
        });
        prevButton.classList.add('btn-prev');
        paginationContainer.appendChild(prevButton);
        
        for (let i = 1; i <= totalPages; i++) {
            const button = createButton(i.toString(), () => showPage(i));
            button.dataset.page = i;
            paginationContainer.appendChild(button);
        }
        
        const nextButton = createButton('»', () => {
            if (currentPage < totalPages) showPage(currentPage + 1);
        });
        nextButton.classList.add('btn-next');
        paginationContainer.appendChild(nextButton);
    }
    
    function createButton(text, onClick) {
        const button = document.createElement('button');
        button.textContent = text;
        button.className = 'btn btn-outline-primary btn-sm mx-1';
        button.addEventListener('click', onClick);
        return button;
    }
    
    function updatePaginationButtons() {
        paginationContainer.querySelectorAll('button').forEach(button => {
            if (button.dataset.page) {
                button.classList.toggle('active', parseInt(button.dataset.page) === currentPage);
            }
        });
        
        const prevButton = paginationContainer.querySelector('.btn-prev');
        const nextButton = paginationContainer.querySelector('.btn-next');
        const totalPages = Math.ceil(tableBody.getElementsByTagName('tr').length / rowsPerPage);
        
        prevButton.disabled = currentPage === 1;
        nextButton.disabled = currentPage === totalPages;
    }
    
    function updateShowingText(total, start = 0, end = 0) {
        document.getElementById('showing').textContent = start && end ? `${start}-${end}` : '0-0';
        document.getElementById('total').textContent = total;
    }
    
    initializePagination();
    window.reinitializePagination = initializePagination;
});
</script>
@endsection