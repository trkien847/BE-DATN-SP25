
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .checkout-container {
        margin: 40px auto;
        max-width: 1200px;
        padding: 0 15px;
    }
    .checkout-container .row {
        display: flex;
        gap: 30px;
        align-items: flex-start;
    }
    .checkout-container .col-lg-6 {
        flex: 1;
        min-width: 320px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.1);
        padding: 24px;
        transition: transform 0.3s, box-shadow 0.3s;
        opacity: 0;
        animation: slideIn 0.6s ease-out forwards;
    }
    .col-lg-6.form-column {
        animation: slideInFromLeft 0.6s ease-out forwards;
    }
    .col-lg-6.summary-column {
        animation: slideInFromRight 0.6s ease-out forwards;
        animation-delay: 0.2s;
    }
    .col-lg-6:hover {
        box-shadow: 0 8px 32px rgba(59, 130, 246, 0.15);
        transform: translateY(-4px);
    }
    h4 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e3a8a;
        margin-bottom: 20px;
        letter-spacing: 0.5px;
    }
    .form-group label,
    label.flex {
        font-size: 0.95rem;
        color: #1e293b;
        margin-bottom: 6px;
        font-weight: 600;
        display: flex;
        align-items: center;
    }
    .form-control, .form-select, input[type="text"], input[type="email"], select {
        width: 100%;
        padding: 12px;
        border: 1px solid #e0e7ff;
        border-radius: 10px;
        font-size: 0.95rem;
        background: #f8fafc;
        transition: border-color 0.3s, box-shadow 0.3s;
    }
    .form-control:focus, .form-select:focus, input[type="text"]:focus, input[type="email"]:focus, select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }
    .payment-options {
        display: flex;
        gap: 12px;
        margin: 12px 0;
    }
    .payment-option {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px;
        border: 2px solid #e0e7ff;
        border-radius: 12px;
        cursor: pointer;
        background: #ffffff;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .payment-option.selected, .payment-option:hover {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
        transform: scale(1.02);
    }
    .payment-option input[type="radio"] {
        accent-color: #2563eb;
    }
    .theme-btn-1 {
        background: linear-gradient(90deg, #2563eb 0%, #60a5fa 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 14px;
        font-size: 1.1rem;
        font-weight: 600;
        margin-top: 20px;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        transition: all 0.3s ease;
        width: 100%;
    }
    .theme-btn-1:hover {
        background: linear-gradient(90deg, #60a5fa 0%, #2563eb 100%);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.3);
        transform: scale(1.03);
    }
    .order-summary {
    background: #f8fafc;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    max-width: 480px;
    margin: auto;
}

.order-summary-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    border: 1px solid #e0e7ff;
    border-radius: 12px;
    background: #ffffff;
    margin-bottom: 16px;
    padding: 16px;
    transition: all 0.3s ease;
    opacity: 0;
    animation: fadeIn 0.5s ease-out forwards;
}

.order-summary-item:nth-child(1) { animation-delay: 0.3s; }

.order-summary-item:hover {
    border-color: #2563eb;
    box-shadow: 0 4px 16px rgba(59, 130, 246, 0.15);
    transform: scale(1.01);
}

.order-summary-item img {
    width: 64px;
    height: 64px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid #e0e7ff;
}

.order-summary-item .text-base {
    font-size: 1.05rem;
}

.order-summary .flex-1 {
    min-width: 0;
}
    .alert-danger {
        background: #fee2e2;
        color: #dc2626;
        padding: 12px;
        border: 1px solid #f87171;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .back-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        background: #f1f5f9;
        color: #1e293b;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .back-btn:hover {
        background: #dbeafe;
        color: #2563eb;
        transform: scale(1.03);
    }
    .loading-overlay {
        z-index: 9999;
        background: rgba(30, 41, 59, 0.7);
        display: none;
        align-items: center;
        justify-content: center;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        flex-direction: column;
    }
    .loading-overlay.active, .loading-overlay.show {
        display: flex !important;
        opacity: 1;
    }
    .loading-spinner {
        border: 6px solid #e0e7ff;
        border-top: 6px solid #2563eb;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        margin-bottom: 16px;
    }
    .loading-text {
        font-size: 1.1rem;
        color: #ffffff;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .loading-dots {
        display: inline-flex;
        gap: 4px;
    }
    .loading-dot {
        width: 8px;
        height: 8px;
        background: #2563eb;
        border-radius: 50%;
        animation: bounce 1.2s infinite both;
    }
    .loading-dot:nth-child(2) { animation-delay: 0.2s; }
    .loading-dot:nth-child(3) { animation-delay: 0.4s; }
    .designed-by {
        color: #94a3b8;
        font-size: 0.85rem;
        margin-top: 12px;
    }
    @keyframes slideInFromLeft {
        0% { opacity: 0; transform: translateX(-50px); }
        100% { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideInFromRight {
        0% { opacity: 0; transform: translateX(50px); }
        100% { opacity: 1; transform: translateX(0); }
    }
    @keyframes fadeIn {
        0% { opacity: 0; transform: translateY(10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    @keyframes bounce {
        0%, 80%, 100% { transform: scale(1); }
        40% { transform: scale(1.5); }
    }
    @media (max-width: 991px) {
        .checkout-container .row {
            flex-direction: column;
            gap: 20px;
        }
        .checkout-container .col-lg-6 {
            padding: 16px;
        }
    }
</style>

<div class="container checkout-container py-4">
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-6 form-column">
            <div class="mb-4">
                <button type="button" onclick="window.history.back();" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </button>
            </div>

            <h4>Thông tin giao hàng</h4>
            <div id="page-content" class="bg-gray-50 p-4 rounded-lg">
                <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    @if ($appliedCoupon)
                        <input type="hidden" name="coupon_id" value="{{ $appliedCoupon->id }}">
                        <input type="hidden" name="coupon_code" value="{{ $appliedCoupon->code }}">
                        <input type="hidden" name="coupon_description" value="{{ $appliedCoupon->description }}">
                        <input type="hidden" name="coupon_discount_type" value="{{ $appliedCoupon->discount_type }}">
                        <input type="hidden" name="coupon_discount_value" value="{{ $discount }}">
                    @endif
                    <input type="hidden" name="total_amount" value="{{ $grandTotal }}">
                    <input type="hidden" name="selected_products" value='{{ json_encode($selectedProducts) }}'>

                    <div class="form-group mb-3">
                        <label><i class="fas fa-user mr-2 text-gray-500"></i> Họ và tên</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Họ và tên người đặt" value="{{ $user->fullname ?? '' }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label><i class="fas fa-phone mr-2 text-gray-500"></i> Số điện thoại</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="Số điện thoại" value="{{ $user->phone_number ?? '' }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label><i class="fas fa-envelope mr-2 text-gray-500"></i> Email (Không bắt buộc)</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="{{ $user->email ?? '' }}">
                    </div>

                    <div class="form-group mb-3">
                        <label><i class="fas fa-map-marker-alt mr-2 text-gray-500"></i> Địa chỉ nhận hàng</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <select name="province" id="province" class="form-select" required>
                                <option value="">Chọn tỉnh/thành phố</option>
                            </select>
                            <select name="district" id="district" class="form-select" required>
                                <option value="">Chọn quận/huyện</option>
                            </select>
                            <select name="ward" id="ward" class="form-select" required>
                                <option value="">Chọn phường/xã</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="address" id="address_detail" class="form-control" placeholder="Nhập địa chỉ cụ thể" value="{{ $userAddress ? $userAddress->address : '' }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Phương thức thanh toán</label>
                        <div class="payment-options">
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="cash" checked>
                                <i class="fas fa-money-bill-wave text-green-600"></i>
                                <span>Tiền mặt</span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="vnpay">
                                <i class="fab fa-cc-visa text-blue-600"></i>
                                <span>VNPay</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="theme-btn-1">Hoàn tất</button>
                </form>
            </div>
        </div>

        <div class="col-lg-6 summary-column">
            <h4>Danh sách sản phẩm</h4>
            <div class="order-summary">
                <div class="mb-3">
                    @foreach ($selectedProducts as $product)
                        <div class="order-summary-item">
                            <img src="{{ $product['thumbnail'] }}" alt="{{ $product['name'] }}">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $product['name'] }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $product['name_variant'] ?? 'Mặc định' }}</p>
                            </div>
                            <div class="flex flex-col items-end min-w-[90px] space-y-0.5">
                            <div class="text-sm text-gray-600 space-y-0.5">
                            <div class="text-sm text-gray-600 space-y-0.5">
                        <div>
                            <span class="text-gray-500">Giá bán:</span>
                            <span class="text-red-600 font-bold">
                            {{ number_format($product['sell_price'] ?? $product['original_price'] ?? $product['price']) }}đ
                            </span>
                        </div>
                        <div>
                        <span class="text-gray-500">Giá gốc:</span>
                        <span class="line-through text-gray-400">
                        {{ number_format($product['price']) }}đ
                        </span>
                        </div>
                        <div>
                            <span class="text-gray-500">Số lượng:</span>
                            <span class="text-gray-800 font-medium">x{{ $product['quantity'] }}</span>
                        </div>
                        </div>

                        </div>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-gray-200 pt-3">
                    <div class="flex justify-between text-sm text-gray-700 mb-2">
                        <span>Tạm tính :</span>
                        <span>{{ number_format($grandTotal + ($discount ?? 0)) }}đ</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-700 mb-2">
                        <span>Giảm giá trực tiếp :</span>
                        <span class="text-red-600">-{{ number_format($discount ?? 0) }}đ</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-700 mb-2">
                        <span>Giảm giá voucher :</span>
                        <span>0đ</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500">Tổng thanh toán :</span>
                    <span class="text-[22px] font-semibold text-[#f44336] underline decoration-1 decoration-[#f44336]" style="color: rgb(59, 185, 145);"><h3>{{ number_format($grandTotal) }}đ</h3></span>
                </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="loading-overlay" id="loading-overlay">
    <div class="loading-spinner"></div>
    <div class="loading-text" id="loading-message">
        <span></span>
        <div class="loading-dots">
            <div class="loading-dot"></div>
            <div class="loading-dot"></div>
            <div class="loading-dot"></div>
        </div>
    </div>
    <div class="designed-by">BeePhamarcy</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        const provinceSelect = $('#province');
        const districtSelect = $('#district');
        const wardSelect = $('#ward');
        const addressDetailInput = $('#address_detail');

        if (!provinceSelect.length || !districtSelect.length || !wardSelect.length || !addressDetailInput.length) {
            console.error('Không tìm thấy các phần tử cần thiết:', {
                provinceSelect, districtSelect, wardSelect, addressDetailInput
            });
            return;
        }

        // Fetch provinces
        $.getJSON('https://provinces.open-api.vn/api/p/', function(data) {
            $.each(data, function(index, province) {
                provinceSelect.append(`<option value="${province.code}" data-name="${province.name}">${province.name}</option>`);
            });

            // Pre-select province if userAddress exists
            if ("{{ $userAddress ? $userAddress->province : '' }}") {
                provinceSelect.val("{{ $userAddress ? $userAddress->province : '' }}").trigger('change');
            }
        }).fail(function() {
            console.error('Không thể tải danh sách tỉnh/thành phố');
            provinceSelect.append('<option value="">Không thể tải tỉnh/thành phố</option>');
        });

        // Fetch districts when province changes
        provinceSelect.on('change', function() {
            const provinceCode = $(this).val();
            districtSelect.html('<option value="">Chọn quận/huyện</option>');
            wardSelect.html('<option value="">Chọn phường/xã</option>');
            addressDetailInput.val('');

            if (provinceCode) {
                $.getJSON(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`, function(data) {
                    if (data.districts) {
                        $.each(data.districts, function(index, district) {
                            districtSelect.append(`<option value="${district.code}" data-name="${district.name}">${district.name}</option>`);
                        });
                    }

                    // Pre-select district if userAddress exists
                    if ("{{ $userAddress ? $userAddress->district : '' }}") {
                        districtSelect.val("{{ $userAddress ? $userAddress->district : '' }}").trigger('change');
                    }
                }).fail(function() {
                    console.error('Không thể tải danh sách quận/huyện');
                    districtSelect.append('<option value="">Không thể tải quận/huyện</option>');
                });
            }
        });

        // Fetch wards when district changes
        districtSelect.on('change', function() {
            const districtCode = $(this).val();
            wardSelect.html('<option value="">Chọn phường/xã</option>');
            addressDetailInput.val('');

            if (districtCode) {
                $.getJSON(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`, function(data) {
                    if (data.wards) {
                        $.each(data.wards, function(index, ward) {
                            wardSelect.append(`<option value="${ward.code}" data-name="${ward.name}">${ward.name}</option>`);
                        });
                    }

                    // Pre-select ward if userAddress exists
                    if ("{{ $userAddress ? $userAddress->ward : '' }}") {
                        wardSelect.val("{{ $userAddress ? $userAddress->ward : '' }}").trigger('change');
                    }
                }).fail(function() {
                    console.error('Không thể tải danh sách phường/xã');
                    wardSelect.append('<option value="">Không thể tải phường/xã</option>');
                });
            }
        });

        // Update address_detail when ward changes
        wardSelect.on('change', function() {
            const provinceName = provinceSelect.find('option:selected').data('name') || '';
            const districtName = districtSelect.find('option:selected').data('name') || '';
            const wardName = $(this).find('option:selected').data('name') || '';

            if (provinceName && districtName && wardName) {
                addressDetailInput.val(`${wardName}, ${districtName}, ${provinceName}`);
            } else {
                addressDetailInput.val('');
            }
        });

        // Form submission handling
        $('#checkout-form').on('submit', function(event) {
            event.preventDefault();
            const paymentMethod = $('input[name="payment_method"]:checked').val();
            const loadingMessage = $('#loading-message').find('span');

            if (paymentMethod === 'cash') {
                loadingMessage.text('Đang tiến hành thanh toán vui lòng đợi');
            } else if (paymentMethod === 'vnpay') {
                loadingMessage.text('Đang chuyển đến giao diện VNPay');
            }

            $('#page-content').addClass('opacity-0');
            $('#loading-overlay').removeClass('hidden');

            setTimeout(() => {
                this.submit();
            }, 2000);
        });

        // Payment option handling
        $('.payment-option input[type="radio"]').on('change', function() {
            $('.payment-option').removeClass('selected');
            if ($(this).is(':checked')) {
                $(this).closest('.payment-option').addClass('selected');
            }
        });

        $('.payment-option input[type="radio"]:checked').closest('.payment-option').addClass('selected');

        const clickSound = new Audio('/sounds/click.mp3');
        $('.payment-option, .theme-btn-1').on('click', function() {
            if (localStorage.getItem('soundEnabled') !== 'false') {
                clickSound.currentTime = 0;
                clickSound.play().catch(err => console.log('Sound playback failed:', err));
            }
        });
    });
</script>
