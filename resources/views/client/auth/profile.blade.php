@extends('client.layouts.layout')
@section('content')
    <!-- Utilize Cart Menu Start -->
    @include('client.components.CartMenuStart')
    <!-- Utilize Cart Menu End -->

    <!-- Utilize Mobile Menu Start -->
    @include('client.components.MobileMenuStart')
    <div class="ltn__utilize-overlay"></div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image " data-bs-bg="img/bg/14.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">Tài khoản</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="index.html"><span class="ltn__secondary-color"><i
                                                class="fas fa-home"></i></span> Trang chủ</a></li>
                                <li>Tài khoản</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <!-- WISHLIST AREA START -->
    <div class="liton__wishlist-area pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- PRODUCT TAB AREA START -->
                    <div class="ltn__product-tab-area">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="ltn__tab-menu-list mb-50">
                                        <div class="nav">
                                            <a class="active show" data-bs-toggle="tab" href="#liton_tab_1_1">Tài khoản <i
                                                    class="fas fa-home"></i></a>
                                            <a data-bs-toggle="tab" href="#liton_tab_1_2">Đơn hàng <i
                                                    class="fas fa-file-alt"></i></a>
                                            <a data-bs-toggle="tab" href="#liton_tab_1_4">Địa chỉ <i
                                                    class="fas fa-map-marker-alt"></i></a>
                                            <a data-bs-toggle="tab" href="#liton_tab_1_5">Thông tin tài khoản <i
                                                    class="fas fa-user"></i></a>
                                            <a href="{{ route('logout') }}">Đăng xuất <i
                                                    class="fas fa-sign-out-alt"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="tab-content">
                                        <div class="tab-pane fade active show" id="liton_tab_1_1">
                                            <div class="ltn__myaccount-tab-content-inner">
                                                <p>Xin chào <strong>{{ Auth::user()->fullname }}</strong> </p>
                                                <p>Từ bảng điều khiển tài khoản của bạn, bạn có thể xem<span>lịch sử mua
                                                        hàng</span>
                                                    của bạn <span>, địa chỉ vận chuyển và thanh toán</span>, và <span>chỉnh
                                                        sửa mật khẩu và thông tin tài khoản</span>.</p>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="liton_tab_1_2">
                                            <div class="ltn__myaccount-tab-content-inner">
                                                <div class="table-responsive">
                                                    <div class="container">
                                                        <h1>Lịch Sử Mua Hàng</h1>

                                                        <div class="filter-container">
                                                            <h5>Lọc theo trạng thái</h5>
                                                            <div class="status-filters">
                                                                <div class="status-filter all-filter active"
                                                                    data-status="all">Tất cả</div>
                                                                <div class="status-filter" data-status="Chờ xác nhận">Chờ
                                                                    xác nhận</div>
                                                                <div class="status-filter" data-status="Chờ giao hàng">Chờ
                                                                    giao hàng</div>
                                                                <div class="status-filter" data-status="Đang giao hàng">Đang
                                                                    giao hàng</div>
                                                                <div class="status-filter" data-status="Đã giao hàng">Đã
                                                                    giao hàng</div>
                                                                <div class="status-filter" data-status="Hoàn thành">Hoàn
                                                                    thành</div>
                                                                <div class="status-filter" data-status="Đã hủy">Đã hủy</div>
                                                                <div class="status-filter" data-status="Chờ hủy">Chờ hủy
                                                                </div>
                                                                <div class="status-filter" data-status="Chờ hoàn tiền">Chờ
                                                                    hoàn tiền</div>
                                                                <div class="status-filter"
                                                                    data-status="Chuyển khoản thành công">Chuyển khoản thành
                                                                    công</div>
                                                            </div>
                                                        </div>

                                                        <table class="order-table" id="order-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sản phẩm</th>
                                                                    <th>Số lượng</th>
                                                                    <th>Tổng giá trị</th>
                                                                    <th>Trạng thái</th>
                                                                    <th>Thanh toán</th>
                                                                    <th>Hành động</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="order-table-body">
                                                                @foreach ($orders as $order)
                                                                    <tr class="order-row" style="display: none;">
                                                                        <td>
                                                                            <div class="product-list">
                                                                                @foreach ($order->items as $item)
                                                                                    <div
                                                                                        class="product-item d-flex align-items-center mb-2">
                                                                                        <img src="{{ asset('upload/' . $item->product->thumbnail) }}"
                                                                                            alt="{{ $item->product->name }}"
                                                                                            class="product-image me-2"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-placement="top"
                                                                                            title="{{ $item->product->name }}">
                                                                                        <span
                                                                                            class="product-name">{{ $item->product->name }}</span>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </td>
                                                                        <td>{{ $order->items->sum('quantity') }}</td>
                                                                        <td>{{ number_format($order->total_amount) }} VNĐ
                                                                        </td>
                                                                        <td>
                                                                            @php
                                                                                $statusName =
                                                                                    $order->latestOrderStatus->name ??
                                                                                    'Chưa có trạng thái';
                                                                            @endphp
                                                                            <span
                                                                                class="{{ $statusName === 'Đã hủy' ? 'text-danger' : ($statusName === 'Chờ hủy' ? 'text-warning' : 'text-success') }}">
                                                                                {{ $statusName }}
                                                                            </span>
                                                                        </td>
                                                                        <td>
                                                                            @php
                                                                                $paymentMethods = [
                                                                                    1 => [
                                                                                        'name' => 'Tiền mặt',
                                                                                        'class' => 'bg-success',
                                                                                    ],
                                                                                    2 => [
                                                                                        'name' => 'VNPAY',
                                                                                        'class' => 'bg-info',
                                                                                    ],
                                                                                ];
                                                                                $payment = $paymentMethods[
                                                                                    $order->payment_id
                                                                                ] ?? [
                                                                                    'name' => 'Không xác định',
                                                                                    'class' => 'bg-secondary',
                                                                                ];
                                                                            @endphp
                                                                            <span
                                                                                class="badge {{ $payment['class'] }}">{{ $payment['name'] }}</span>
                                                                        </td>
                                                                        <td>
                                                                            @if (($order->latestOrderStatus->name ?? '') === 'Hoàn thành')
                                                                                <a href="#" class="action-icon"
                                                                                    onclick="showModal('order{{ $order->id }}')"
                                                                                    title="Đánh giá sản phẩm">
                                                                                    <i class="fas fa-comment"></i>
                                                                                </a>
                                                                            @else
                                                                                <a href="#" class="action-icon"
                                                                                    onclick="showModal('order{{ $order->id }}')"
                                                                                    title="Xem chi tiết">
                                                                                    <i class="fas fa-eye"></i>
                                                                                </a>
                                                                            @endif
                                                                            @if (in_array($order->latestOrderStatus->name ?? '', ['Chờ xác nhận', 'Chờ giao hàng']))
                                                                                <a href="{{ route('order.cancel', $order->id) }}"
                                                                                    class="action-icon cancel-order-link"
                                                                                    data-cancel-count="{{ $cancelCountToday ?? 0 }}"
                                                                                    title="Hủy đơn hàng">
                                                                                    <i class="fas fa-times-circle"></i>
                                                                                </a>
                                                                            @endif
                                                                            @if (
                                                                                ($order->latestOrderStatus->name ?? '') === 'Hoàn thành' &&
                                                                                    $order->completedStatusTimestamp() &&
                                                                                    \Carbon\Carbon::parse($order->completedStatusTimestamp())->diffInDays(\Carbon\Carbon::now()) <= 7)
                                                                                <a href="{{ route('order.return', $order->id) }}"
                                                                                    class="action-icon" title="Hoàn hàng">
                                                                                    <i class="fas fa-undo"></i>
                                                                                </a>
                                                                            @endif
                                                                            @if (in_array($order->latestOrderStatus->name ?? '', ['Chờ hoàn tiền']))
                                                                                <a href="{{ route('order.refund.form', $order->id) }}"
                                                                                    class="action-icon"
                                                                                    title="Nhập thông tin tài khoản">
                                                                                    <i class="fas fa-money-check-alt"></i>
                                                                                </a>
                                                                            @endif
                                                                            @if (in_array($order->latestOrderStatus->name ?? '', ['Chuyển khoản thành công']))
                                                                                <a href="{{ route('order.refund.confirm', $order->id) }}"
                                                                                    class="action-icon"
                                                                                    title="Xác nhận nhận tiền">
                                                                                    <i class="fas fa-check-circle"></i>
                                                                                </a>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>


                                                        <div class="pagination" id="pagination-controls"></div>
                                                        @foreach ($orders as $order)
                                                            <div id="order{{ $order->id }}" class="modal">
                                                                <div class="modal-content">
                                                                    <button class="close-btn"
                                                                        onclick="hideModal('order{{ $order->id }}')">×</button>
                                                                    <div class="order-details">
                                                                        <h3>Chi tiết đơn hàng {{ $order->code }}</h3>
                                                                        <p><strong>Ngày mua:</strong>
                                                                            {{ $order->created_at->format('d/m/Y H:i:s') }}
                                                                        </p>
                                                                        <p><strong>Trạng thái:</strong>
                                                                            {{ $order->latestOrderStatus->name ?? 'Chưa có trạng thái' }}
                                                                        </p>
                                                                        <p><strong>Mã đơn hàng:</strong>
                                                                            {{ $order->code }}</p>
                                                                        @if ($order->refund_proof_image)
                                                                            <p>
                                                                                <strong>Ảnh hoàn tiền:</strong>
                                                                                <img src="{{ asset('upload/' . $order->refund_proof_image) }}"
                                                                                    class="img-thumbnail"
                                                                                    alt="Ảnh chứng minh hoàn tiền"
                                                                                    width="100px" height="100px"
                                                                                    onclick="showFullImage('{{ asset('upload/' . $order->refund_proof_image) }}')"
                                                                                    style="cursor: pointer;">
                                                                            </p>
                                                                        @endif

                                                                        <h4>Thông tin sản phẩm:</h4>
                                                                        <ul>
                                                                            @foreach ($order->items as $item)
                                                                                <li>
                                                                                    <strong><a
                                                                                            href="{{ route('products.productct', $item->id) }}">Sản
                                                                                            phẩm:</a></strong>
                                                                                    {{ $item->name }} <br>
                                                                                    <strong>Biến thể:</strong>
                                                                                    {{ $item->name_variant ?? 'Không có' }}
                                                                                    @if ($item->attributes_variant)
                                                                                        ({{ $item->attributes_variant }})
                                                                                    @endif <br>
                                                                                    <strong>Giá:</strong>
                                                                                    {{ number_format($item->price_variant ?? $item->price) }}
                                                                                    VNĐ <br>
                                                                                    <strong>Số lượng:</strong>
                                                                                    {{ $item->quantity }} <br>
                                                                                    @if ($item->product && $item->product->importProducts->isNotEmpty())
                                                                                        <strong>Ngày sản xuất:</strong>
                                                                                        {{ $item->product->importProducts->first()->manufacture_date
                                                                                            ? \Carbon\Carbon::parse($item->product->importProducts->first()->manufacture_date)->format('d/m/Y')
                                                                                            : 'Không có' }}
                                                                                        <br>
                                                                                        <strong>Hạn sử dụng:</strong>
                                                                                        @php
                                                                                            $expiryDate = $item->product->importProducts->first()
                                                                                                ->expiry_date;
                                                                                            $daysUntilExpiry = $expiryDate
                                                                                                ? \Carbon\Carbon::parse(
                                                                                                    $expiryDate,
                                                                                                )->diffInDays(now())
                                                                                                : null;
                                                                                        @endphp
                                                                                        <span
                                                                                            class="{{ $daysUntilExpiry && $daysUntilExpiry <= 30 ? 'text-danger' : '' }}">
                                                                                            {{ $expiryDate ? \Carbon\Carbon::parse($expiryDate)->format('d/m/Y') : 'Không có' }}
                                                                                            @if ($daysUntilExpiry && $daysUntilExpiry <= 30)
                                                                                                (Còn {{ $daysUntilExpiry }}
                                                                                                ngày)
                                                                                            @endif
                                                                                        </span>
                                                                                    @else
                                                                                        <strong>Ngày sản xuất:</strong>
                                                                                        Không có <br>
                                                                                        <strong>Hạn sử dụng:</strong> Không
                                                                                        có
                                                                                    @endif
                                                                                    @if (($order->latestOrderStatus->name ?? '') === 'Hoàn thành')
                                                                                        <div class="review-section mt-2">
                                                                                            <button
                                                                                                class="btn btn-sm btn-primary"
                                                                                                onclick="showReviewForm('{{ $item->product_id }}', '{{ $order->id }}', '{{ $item->name }}')"
                                                                                                {{ App\Models\Reviews::where('product_id', $item->product_id)->where('order_id', $order->id)->where('user_id', auth()->id())->exists()
                                                                                                    ? 'disabled'
                                                                                                    : '' }}>
                                                                                                {{ App\Models\Reviews::where('product_id', $item->product_id)->where('order_id', $order->id)->where('user_id', auth()->id())->exists()
                                                                                                    ? 'Đã đánh giá'
                                                                                                    : 'Đánh giá sản phẩm' }}
                                                                                            </button>
                                                                                        </div>
                                                                                    @endif
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>

                                                                        @if ($order->coupon_code)
                                                                            <p><strong>Mã giảm giá:</strong>
                                                                                {{ $order->coupon_code }}
                                                                                (Giảm {{ $order->coupon_discount_value }}
                                                                                {{ $order->coupon_discount_type === 'percent' ? '%' : 'VNĐ' }})
                                                                            </p>
                                                                        @endif
                                                                        <p><strong>Tổng cộng:</strong>
                                                                            {{ number_format($order->total_amount) }} VNĐ
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                                                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                                                    return new bootstrap.Tooltip(tooltipTriggerEl);
                                                });
                                            });

                                            function showReviewForm(productId, orderId, productName) {
                                                console.log('Debug values:', {
                                                    productId,
                                                    orderId,
                                                    productName
                                                });
                                                Swal.fire({
                                                    title: `Đánh giá sản phẩm ${productName}`,
                                                    html: `
                                                            <div class="rating-stars mb-3">
                                                                <span class="star" data-rating="1">★</span>
                                                                <span class="star" data-rating="2">★</span>
                                                                <span class="star" data-rating="3">★</span>
                                                                <span class="star" data-rating="4">★</span>
                                                                <span class="star" data-rating="5">★</span>
                                                            </div>
                                                            <input type="hidden" id="rating-value" value="5">
                                                            <textarea id="swal-input-review" class="swal2-textarea" 
                                                                    placeholder="Nhập nhận xét của bạn" rows="3"></textarea>
                                                        `,
                                                    showCancelButton: true,
                                                    confirmButtonText: 'Gửi đánh giá',
                                                    cancelButtonText: 'Hủy',
                                                    didOpen: () => {

                                                        const stars = Swal.getPopup().querySelectorAll('.star');
                                                        const ratingInput = Swal.getPopup().querySelector('#rating-value');
                                                        const reviewText = Swal.getPopup().querySelector('#swal-input-review');


                                                        const initialRating = parseInt(ratingInput.value);
                                                        for (let i = 0; i < initialRating; i++) {
                                                            stars[i].classList.add('active');
                                                        }


                                                        stars.forEach(star => {
                                                            star.addEventListener('click', function() {
                                                                const rating = parseInt(this.getAttribute('data-rating'));
                                                                ratingInput.value = rating;

                                                                stars.forEach(s => s.classList.remove('active'));
                                                                for (let i = 0; i < rating; i++) {
                                                                    stars[i].classList.add('active');
                                                                }
                                                            });

                                                            star.addEventListener('mouseover', function() {
                                                                const rating = parseInt(this.getAttribute('data-rating'));
                                                                for (let i = 0; i < rating; i++) {
                                                                    stars[i].style.color = '#f1c40f';
                                                                }
                                                            });

                                                            star.addEventListener('mouseout', function() {
                                                                stars.forEach(s => {
                                                                    if (!s.classList.contains('active')) {
                                                                        s.style.color = '#ccc';
                                                                    }
                                                                });
                                                            });
                                                        });


                                                        reviewText.addEventListener('input', function() {
                                                            if (this.value.trim() !== '') {
                                                                this.style.borderColor = '#28a745';
                                                            } else {
                                                                this.style.borderColor = '#ccc';
                                                            }
                                                        });
                                                    },
                                                    preConfirm: () => {
                                                        const rating = document.getElementById('rating-value').value;
                                                        const reviewText = document.getElementById('swal-input-review').value;

                                                        if (!reviewText.trim()) {
                                                            Swal.showValidationMessage('Vui lòng nhập nội dung đánh giá');
                                                            return false;
                                                        }

                                                        console.log('Sending data:', {
                                                            product_id: productId,
                                                            order_id: orderId,
                                                            rating: rating,
                                                            review_text: reviewText
                                                        });

                                                        return fetch('/reviews', {
                                                                method: 'POST',
                                                                headers: {
                                                                    'Content-Type': 'application/json',
                                                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                                                },
                                                                body: JSON.stringify({
                                                                    product_id: productId,
                                                                    order_id: orderId,
                                                                    rating: rating,
                                                                    review_text: reviewText
                                                                })
                                                            })
                                                            .then(response => response.json())
                                                            .then(data => {
                                                                if (!data.success) {
                                                                    throw new Error(data.message || 'Có lỗi xảy ra');
                                                                }
                                                                return data;
                                                            });
                                                    }
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        Swal.fire('Thành công!', 'Đánh giá của bạn đã được gửi', 'success');
                                                        const reviewButton = document.querySelector(
                                                            `button[onclick="showReviewForm('${productId}', '${orderId}', '${productName}')"]`);
                                                        if (reviewButton) {
                                                            reviewButton.disabled = true;
                                                            reviewButton.textContent = 'Đã đánh giá';
                                                        }
                                                    }
                                                });
                                            }

                                            document.addEventListener('DOMContentLoaded', function() {

                                                document.querySelectorAll('.cancel-order-link').forEach(function(link) {
                                                    link.addEventListener('click', function(e) {
                                                        e.preventDefault();
                                                        const cancelCount = parseInt(this.getAttribute('data-cancel-count'), 10);

                                                        if (cancelCount >= 2) {
                                                            const message =
                                                                `Hôm nay bạn đã hủy <strong>${cancelCount} đơn hàng</strong>.`;
                                                            const confirmText = "Xác nhận hủy";
                                                            const warning = cancelCount >= 3 ?
                                                                '<span class="warning-text">CẢNH BÁO: Hủy quá 3 đơn/ngày sẽ khóa tài khoản 3 ngày!</span>' :
                                                                '';

                                                            Swal.fire({
                                                                title: cancelCount >= 3 ? 'CẢNH BÁO HỦY ĐƠN!' :
                                                                    'Xác nhận hủy đơn',
                                                                html: `${message}<br>${warning}<br>${cancelCount >= 3 ? '<strong>Bạn có chắc chắn muốn tiếp tục?</strong>' : 'Bạn có chắc chắn muốn hủy đơn này?'}`,
                                                                icon: cancelCount >= 3 ? 'warning' : 'question',
                                                                iconColor: cancelCount >= 3 ? '#ff3333' : '#3085d6',
                                                                showCancelButton: true,
                                                                confirmButtonText: confirmText,
                                                                cancelButtonText: 'Hủy bỏ',
                                                                buttonsStyling: false,
                                                                customClass: {
                                                                    confirmButton: 'swal2-confirm-button',
                                                                    cancelButton: 'swal2-cancel-button'
                                                                },
                                                                animation: true,
                                                                allowOutsideClick: false
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    window.location.href = this.getAttribute('href');
                                                                }
                                                            });
                                                        } else {
                                                            window.location.href = this.getAttribute('href');
                                                        }
                                                    });
                                                });

                                                const orderRows = document.querySelectorAll('.order-row');
                                                orderRows.forEach(row => {
                                                    row.style.display = 'table-row';
                                                });

                                                const statusFilters = document.querySelectorAll('.status-filter');
                                                statusFilters.forEach(filter => {
                                                    filter.addEventListener('click', function() {

                                                        statusFilters.forEach(f => f.classList.remove('active'));

                                                        this.classList.add('active');


                                                        const statusToFilter = this.getAttribute('data-status');


                                                        orderRows.forEach(row => {
                                                            const statusCell = row.querySelector('td:nth-child(4) span');
                                                            const statusText = statusCell.textContent.trim();

                                                            if (statusToFilter === 'all') {
                                                                row.style.display = 'table-row';
                                                            } else {
                                                                row.style.display = statusText === statusToFilter ?
                                                                    'table-row' : 'none';
                                                            }
                                                        });
                                                    });
                                                });
                                            });

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

                                            document.addEventListener('DOMContentLoaded', function() {
                                                const tableBody = document.getElementById('order-table-body');
                                                const rows = Array.from(document.querySelectorAll('.order-row'));
                                                const paginationControls = document.getElementById('pagination-controls');
                                                const rowsPerPage = 10;
                                                let currentPage = 1;


                                                function displayRows() {
                                                    const start = (currentPage - 1) * rowsPerPage;
                                                    const end = start + rowsPerPage;
                                                    rows.forEach(row => (row.style.display = 'none'));
                                                    rows.slice(start, end).forEach(row => (row.style.display = ''));


                                                    updatePagination();
                                                }


                                                function updatePagination() {
                                                    const totalPages = Math.ceil(rows.length / rowsPerPage);
                                                    let paginationHTML = '';


                                                    paginationHTML += `
                                                        <a href="#" class="page-link ${currentPage === 1 ? 'disabled' : ''}" onclick="changePage(${currentPage - 1})">Previous</a>
                                                    `;

                                                    for (let i = 1; i <= totalPages; i++) {
                                                        paginationHTML += `
                                                            <a href="#" class="page-link ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">${i}</a>
                                                        `;
                                                    }

                                                    paginationHTML += `
                                                        <a href="#" class="page-link ${currentPage === totalPages ? 'disabled' : ''}" onclick="changePage(${currentPage + 1})">Next</a>
                                                    `;

                                                    paginationControls.innerHTML = paginationHTML;
                                                }

                                                window.changePage = function(page) {
                                                    if (page < 1 || page > Math.ceil(rows.length / rowsPerPage)) return;
                                                    currentPage = page;
                                                    displayRows();
                                                };

                                                displayRows();
                                            });
                                        </script>
                                        <div class="tab-pane fade" id="liton_tab_1_4">
                                            <div class="ltn__myaccount-tab-content-inner">
                                                <p>Các địa chỉ sau sẽ được sử dụng trên trang thanh toán theo mặc định.</p>
                                                <div class="row" id="addressList">
                                                    @php
                                                        $address = auth()->user()->address;
                                                    @endphp

                                                    @if ($address)
                                                        <div class="col-md-6 col-12 learts-mb-30"
                                                            id="address-{{ $address->id }}">
                                                            <h4> Địa chỉ
                                                                <small><a href="#" class="edit-address"
                                                                        data-id="{{ $address->id }}"
                                                                        data-address="{{ $address->address }}">edit</a></small>
                                                                <small><a href="#"
                                                                        class="delete-address text-danger"
                                                                        data-id="{{ $address->id }}">delete</a></small>
                                                            </h4>
                                                            <address>
                                                                <p><strong>{{ auth()->user()->fullname }}</strong></p>
                                                                <p class="address-text">{{ $address->address }}</p>
                                                                <p>Mobile: {{ auth()->user()->phone_number ?? 'Chưa có' }}
                                                                </p>
                                                            </address>
                                                        </div>
                                                    @endif
                                                </div>
                                                <!-- Nút Thêm Địa Chỉ -->
                                                <button class="btn btn-primary" id="addAddressBtn">Thêm địa chỉ</button>

                                                <!-- Form thêm địa chỉ (Ẩn mặc định) -->
                                                <div id="addAddressForm" style="display: none; margin-top: 10px;">
                                                    <h4>Thêm địa chỉ mới</h4>
                                                    <form id="newAddressForm" method="POST"
                                                        action="{{ route('profile.address.store') }}">
                                                        @csrf
                                                        <input type="hidden" id="address_id" value="">
                                                        <div class="form-group">
                                                            <label for="address">Địa chỉ:</label>
                                                            <input type="text" class="form-control" id="address">
                                                        </div>
                                                        <button type="submit" class="btn btn-success">Lưu</button>
                                                        <button type="button" class="btn btn-secondary"
                                                            id="cancelAddAddress">Hủy</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="liton_tab_1_5">
                                            <div class="ltn__myaccount-tab-content-inner">
                                                <div class="ltn__form-box">
                                                    <form id="profileForm" action="{{ route('profile.update') }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')

                                                        <!-- Avatar -->
                                                        <div class="avatar-upload-container">
                                                            <label for="avatar">Ảnh đại diện</label>
                                                            <div class="avatar-wrapper">
                                                                @if (auth()->user()->avatar)
                                                                    <img id="avatarPreview"
                                                                        src="{{ asset(auth()->user()->avatar) }}"
                                                                        alt="Avatar" class="avatar-img">
                                                                @else
                                                                    <img id="avatarPreview"
                                                                        src="{{ asset('admin/images/users/dummy-avatar.jpg') }}"
                                                                        alt="Default Avatar" class="avatar-img">
                                                                @endif
                                                                <input type="file" id="avatar" name="avatar"
                                                                    accept="image/*" class="file-input">
                                                                <button type="button" class="change-avatar-btn"
                                                                    onclick="document.getElementById('avatar').click()">Đổi</button>
                                                            </div>
                                                        </div>
                                                        <!-- Thông tin cá nhân -->
                                                        <div class="row mb-50 profile-info">
                                                            <div class="form-group col-md-6">
                                                                <label for="fullname">Tên hiển thị:</label>
                                                                <input type="text" name="fullname" id="fullname"
                                                                    class="form-control"
                                                                    value="{{ old('display_name', auth()->user()->fullname) }}">
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label for="email">Email:</label>
                                                                <input type="email" name="email" id="email"
                                                                    class="form-control"
                                                                    value="{{ old('email', auth()->user()->email) }}">
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label for="phone_number">Số điện thoại:</label>
                                                                <input type="text" name="phone_number"
                                                                    id="phone_number" class="form-control"
                                                                    value="{{ old('phone_number', auth()->user()->phone_number) }}">
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label for="birthday">Ngày sinh:</label>
                                                                <input type="date" name="birthday" id="birthday"
                                                                    class="form-control"
                                                                    value="{{ old('birthday', auth()->user()->birthday) }}">
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label for="gender">Giới tính:</label>
                                                                <select name="gender" id="gender"
                                                                    class="form-control">
                                                                    <option value="">-- Chọn giới tính --</option>
                                                                    <option value="Nam"
                                                                        {{ auth()->user()->gender == 'Nam' ? 'selected' : '' }}>
                                                                        Nam</option>
                                                                    <option value="Nữ"
                                                                        {{ auth()->user()->gender == 'Nữ' ? 'selected' : '' }}>
                                                                        Nữ</option>
                                                                    <option value="Khác"
                                                                        {{ auth()->user()->gender == 'Khác' ? 'selected' : '' }}>
                                                                        Khác</option>
                                                                </select>
                                                            </div>
                                                        </div>



                                                        <!-- Mật khẩu -->
                                                        <fieldset
                                                            style="border: 1px solid #ddd; padding: 20px; border-radius: 10px; margin-top: 30px;">
                                                            <legend
                                                                style="padding: 0 10px; font-weight: bold;margin-bottom: 10px;">
                                                                Thay đổi
                                                                mật khẩu</legend>

                                                            <div class="password-group">
                                                                <div class="form-row">
                                                                    <label for="current_password">Mật khẩu hiện
                                                                        tại:</label>
                                                                    <input type="password" name="current_password"
                                                                        id="current_password">
                                                                </div>
                                                                <div class="form-row">
                                                                    <label for="new_password">Mật khẩu mới:</label>
                                                                    <input type="password" name="new_password"
                                                                        id="new_password">
                                                                </div>
                                                                <div class="form-row">
                                                                    <label for="new_password_confirmation">Nhập lại mật
                                                                        khẩu:</label>
                                                                    <input type="password"
                                                                        name="new_password_confirmation"
                                                                        id="new_password_confirmation">
                                                                </div>
                                                                <span id="password_error" class="error-text">Mật khẩu xác
                                                                    nhận không khớp.</span>
                                                            </div>
                                                        </fieldset>


                                                        <!-- Nút lưu -->
                                                        <div class="btn-wrapper">
                                                            <button type="submit"
                                                                class="btn theme-btn-1 btn-effect-1 text-uppercase">Lưu
                                                                thay đổi</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- PRODUCT TAB AREA END -->
                </div>
            </div>
        </div>
    </div>
    <!-- WISHLIST AREA START -->

    <!-- CALL TO ACTION START (call-to-action-6) -->
    <div class="ltn__call-to-action-area call-to-action-6 before-bg-bottom" data-bs-bg="img/1.jpg--">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div
                        class="call-to-action-inner call-to-action-inner-6 ltn__secondary-bg position-relative text-center---">
                        <div class="coll-to-info text-color-white">
                            <h1>Buy medical disposable face mask <br> to protect your loved ones</h1>
                        </div>
                        <div class="btn-wrapper">
                            <a class="btn btn-effect-3 btn-white" href="shop.html">Explore Products <i
                                    class="icon-next"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CALL TO ACTION END -->
@endsection
@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const password = document.getElementById("new_password");
            const confirmPassword = document.getElementById("new_password_confirmation");
            const errorText = document.getElementById("password_error");
            const form = document.querySelector("form"); // Lấy form

            function validatePassword() {
                if (password.value !== confirmPassword.value) {
                    errorText.style.display = "block"; // Hiển thị lỗi
                    return false;
                } else {
                    errorText.style.display = "none"; // Ẩn lỗi nếu khớp
                    return true;
                }
            }

            // Kiểm tra khi người dùng nhập
            password.addEventListener("input", validatePassword);
            confirmPassword.addEventListener("input", validatePassword);

            // Kiểm tra trước khi gửi form
            form.addEventListener("submit", function(event) {
                if (!validatePassword()) {
                    event.preventDefault(); // Ngăn không cho submit nếu có lỗi
                }
            });
        });
        $(document).ready(function() {
            $("#profileForm").on("submit", function(event) {
                event.preventDefault(); // Ngăn form gửi truyền thống

                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr("action"),
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Toastify({
                            text: "Cập nhật thành công!",
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "green",
                        }).showToast();
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = "Có lỗi xảy ra!";

                        if (errors) {
                            errorMessage = Object.values(errors).map(err => err[0]).join("\n");
                        }

                        Toastify({
                            text: errorMessage,
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "red",
                        }).showToast();
                    }
                });
            });

            // Kiểm tra mật khẩu xác nhận
            $("#new_password, #new_password_confirmation").on("input", function() {
                let password = $("#new_password").val();
                let confirmPassword = $("#new_password_confirmation").val();
                let errorText = $("#password_error");

                if (password !== confirmPassword) {
                    errorText.show();
                } else {
                    errorText.hide();
                }
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            let csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const addBtn = document.getElementById("addAddressBtn");
            const cancelBtn = document.getElementById("cancelAddAddress");

            if (addBtn) {
                addBtn.addEventListener("click", function() {
                    document.getElementById("addAddressForm").style.display = "block";
                });
            }

            if (cancelBtn) {
                cancelBtn.addEventListener("click", function() {
                    document.getElementById("addAddressForm").style.display = "none";
                    document.getElementById("newAddressForm").reset();
                    document.getElementById("address_id").value = "";
                    document.querySelector("#addAddressForm h4").innerText = "Thêm địa chỉ mới";
                });
            }

            // 🟢 Thêm địa chỉ mới
            document.getElementById("newAddressForm").addEventListener("submit", function(event) {
                event.preventDefault();

                let address = document.getElementById("address").value;
                let addressId = document.getElementById("address_id").value;
                let url = addressId ? `/profile/address/${addressId}` : "/profile/address";
                let method = addressId ? "PUT" : "POST";

                fetch(url, {
                        method: method,
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken,
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        body: JSON.stringify({
                            address: address
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (addressId) {
                                // 🟡 Cập nhật địa chỉ
                                document.querySelector(`#address-${addressId} .address-text`)
                                    .innerText = address;
                            } else {
                                // 🟢 Thêm địa chỉ mới
                                let newAddressHTML = `
                        <div class="col-md-6 col-12 learts-mb-30" id="address-${data.id}">
                            <h4> Address 
                                <small><a href="#" class="edit-address" data-id="${data.id}" data-address="${data.address}">edit</a></small>
                                <small><a href="#" class="delete-address text-danger" data-id="${data.id}">delete</a></small>
                            </h4>
                            <address>
                                <p><strong>${data.fullname}</strong></p>
                                <p class="address-text">${data.address}</p>
                                <p>Mobile: ${data.phone_number ?? 'Chưa có'}</p>
                            </address>
                        </div>
                    `;
                                document.getElementById("addressList").insertAdjacentHTML("beforeend",
                                    newAddressHTML);
                                attachEditEvent(); // Cập nhật sự kiện edit
                                attachDeleteEvent(); // Cập nhật sự kiện delete
                            }
                            document.getElementById("addAddressForm").style.display = "none";
                            document.getElementById("newAddressForm").reset();
                            document.getElementById("address_id").value = "";
                        }
                    })
                    .catch(error => console.error("Lỗi Fetch:", error));
            });

            // 🟡 Sự kiện chỉnh sửa địa chỉ
            function attachEditEvent() {
                document.querySelectorAll(".edit-address").forEach(button => {
                    button.addEventListener("click", function(event) {
                        event.preventDefault();
                        let id = this.getAttribute("data-id");
                        let address = this.getAttribute("data-address");

                        document.getElementById("address_id").value = id;
                        document.getElementById("address").value = address;
                        document.getElementById("addAddressForm").style.display = "block";
                    });
                });
            }

            // 🔴 Sự kiện xóa địa chỉ
            function attachDeleteEvent() {
                document.querySelectorAll(".delete-address").forEach(button => {
                    button.addEventListener("click", function(event) {
                        event.preventDefault();
                        let id = this.getAttribute("data-id");

                        if (confirm("Bạn có chắc chắn muốn xóa địa chỉ này không?")) {
                            fetch(`/profile/address/${id}`, {
                                    method: "DELETE",
                                    headers: {
                                        "X-CSRF-TOKEN": csrfToken,
                                        "X-Requested-With": "XMLHttpRequest"
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        document.getElementById(`address-${id}`).remove();
                                    }
                                })
                                .catch(error => console.error("Lỗi Fetch:", error));
                        }
                    });
                });
            }

            attachEditEvent();
            attachDeleteEvent();
        });

        document.getElementById('avatar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
@push('css')
    <style>
        .avatar-upload-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            padding: 16px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .avatar-upload-container label {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            letter-spacing: 0.02em;
        }

        .avatar-wrapper {
            position: relative;
            width: 128px;
            height: 128px;
            border-radius: 50%;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .avatar-wrapper:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border: 3px solid #ffffff;
            border-radius: 50%;
        }

        .file-input {
            display: none;
        }

        .change-avatar-btn {
            position: absolute;
            bottom: 8px;
            right: 8px;
            background: #3b82f6;
            color: white;
            border: none;
            padding: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .change-avatar-btn:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }

        .change-avatar-btn:active {
            transform: translateY(0);
        }

        .profile-info .form-group {
            margin-bottom: 20px;
        }

        .profile-info label {
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
            color: #333;
        }

        .profile-info .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            transition: border-color 0.3s;
            height: 45px;
            /* 👈 Thêm để các ô bằng chiều cao */
        }

        .profile-info .form-control:focus {
            border-color: #007bff;
            background-color: #fff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
            outline: none;
        }

        .form-control {
            height: 45px;
            /* Đảm bảo chiều cao bằng input */
            padding: 10px 14px;
            font-size: 14px;
            line-height: 1.5;
            border-radius: 8px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }

        select.form-control {
            appearance: none;
            /* Xóa kiểu native của trình duyệt */
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23666' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 10px;
        }

        .password-group .form-row {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .password-group label {
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }

        .password-group input {
            padding: 10px 12px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #fdfdfd;
        }

        .password-group input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.15);
        }

        .error-text {
            color: red;
            font-size: 13px;
            display: none;
        }



        /* Table Styles */
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .text-danger {
            color: #dc3545;
            font-weight: bold;
        }

        .order-details ul li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            line-height: 1.6;
        }

        .order-table th,
        .order-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
            white-space: nowrap;
            /* Ngăn không cho xuống dòng */
            vertical-align: middle;
            /* Căn giữa theo chiều dọc cho đẹp */
        }


        .order-table th {
            background-color: rgb(0, 157, 115);
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
            background-color: rgb(0, 157, 115);
            color: white;
        }

        .detail-btn:hover {
            background-color: rgb(0, 157, 115);
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

        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .order-table th,
        .order-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .order-table th {
            background-color: rgb(0, 0, 0);
        }

        .action-icon {
            margin: 0 5px;
            color: #333;
            font-size: 18px;
            text-decoration: none;
        }

        .action-icon:hover {
            color: #007bff;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination .page-link {
            padding: 8px 12px;
            margin: 0 5px;
            border: 1px solid #ddd;
            color: #007bff;
            text-decoration: none;
        }

        .pagination .page-link:hover {
            background-color: rgb(0, 0, 0);
        }

        .pagination .page-link.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .pagination .page-link.disabled {
            color: #ccc;
            cursor: not-allowed;
        }

        .filter-container {
            margin-bottom: 50px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
            box-sizing: border-box;
        }

        .status-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .status-filter {
            padding: 8px 15px;
            border-radius: 20px;
            background-color: #e9ecef;
            cursor: pointer;
            transition: all 0.3s;
        }

        .status-filter:hover {
            background-color: #dae0e5;
        }

        .status-filter.active {
            background-color: #007bff;
            color: white;
        }

        .all-filter {
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .status-filters {
                flex-direction: column;
            }
        }

        .swal2-confirm-button {
            background-color: #d33;
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
        }

        .swal2-cancel-button {
            background-color: #3085d6;
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
        }

        .rating-stars {
            font-size: 24px;
        }

        .rating-stars .star {
            cursor: pointer;
            color: #ddd;
        }

        .rating-stars .star.active {
            color: #ffd700;
        }

        .rating-stars {
            display: flex;
            gap: 5px;
            cursor: pointer;
            margin-bottom: 1rem;
        }

        .star {
            font-size: 30px;
            color: #ccc;
            transition: transform 0.2s ease, color 0.3s ease;
        }

        .star:hover {
            transform: scale(1.2);
        }

        .star.active {
            color: #f1c40f;
        }

        .swal2-popup {
            width: 700px !important;
            /* Tăng chiều rộng để chứa nội dung */
            overflow-x: hidden !important;
            max-width: 90vw;
            /* Giới hạn tối đa để không vượt màn hình nhỏ */
            border-radius: 10px;
        }

        .swal2-textarea {
            width: 500px;
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            resize: none;
        }

        .swal2-textarea:focus {
            outline: none;
            border-color: #28a745;
            box-shadow: 0 0 8px rgba(40, 167, 69, 0.3);
        }

        .swal2-textarea:not(:placeholder-shown) {
            border-color: #28a745;
        }

        .product-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .product-item {
            transition: all 0.3s ease;
        }

        .product-item:hover {
            background-color: #f1f3f5;
            border-radius: 5px;
            padding: 5px;
        }

        .product-image {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #dee2e6;
            transition: transform 0.3s ease;
        }

        .product-item:hover .product-image {
            transform: scale(1.1);
        }

        .product-name {
            display: inline-block;
            max-width: 180px;
            /* Có thể điều chỉnh để phù hợp */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .product-item:hover .product-name {
            color: #007bff;
        }
    </style>
@endpush
