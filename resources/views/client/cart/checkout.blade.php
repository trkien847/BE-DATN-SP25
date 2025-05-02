<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @if(session('error'))
        <div class="alert alert-danger p-3 mb-4 rounded-lg bg-red-100 text-red-700 border border-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="container checkout-container py-4">
        <div class="row">
            <div class="col-lg-6">
            <div class="mb-4">
                <button type="button"
                    onclick="window.history.back();"
                    class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-semibold hover:bg-blue-100 hover:text-blue-700 transition duration-200 shadow-sm"
                >
                    <i class="fas fa-arrow-left"></i>
                    Quay lại
                </button>
            </div>

                <h4 class="text-lg font-semibold text-gray-800 mb-2">
                    Thông tin giao hàng
                </h4>
                <div id="page-content" class="bg-gray-100 p-3 rounded-lg">
                    <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        @if ($appliedCoupon)
                            <input type="hidden" name="coupon_id" value="{{$appliedCoupon->id}}">
                            <input type="hidden" name="coupon_code" value="{{$appliedCoupon->code}}">
                            <input type="hidden" name="coupon_description" value="{{$appliedCoupon->description}}">
                            <input type="hidden" name="coupon_discount_type" value="{{$appliedCoupon->discount_type}}">
                            <input type="hidden" name="coupon_discount_value" value="{{$discount}}">
                        @endif
                        <input type="hidden" name="total_amount" value="{{$grandTotal}}">
                        <input type="hidden" name="selected_products" value='{{ json_encode($selectedProducts) }}'>

                        <div class="mb-2">
                            <label class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-user mr-2 text-gray-500"></i> Thông tin người đặt
                            </label>
                            <input type="text" name="name" id="name" class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Họ và tên người đặt" value="{{ $user->fullname ?? '' }}" required>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="phone" id="phone" class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Số điện thoại" value="{{ $user->phone_number ?? '' }}" required>
                        </div>
                        <div class="mb-2">
                            <input type="email" name="email" id="email" class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Email (Không bắt buộc)" value="{{ $user->email ?? '' }}">
                        </div>

                        <div class="mb-2">
                            <label class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-map-marker-alt mr-2 text-gray-500"></i> Địa chỉ nhận hàng
                            </label>
                        </div>
                        <div class="mb-2">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                <select name="province" id="province" class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                                    <option value="">Chọn tỉnh/thành phố</option>
                                    <option value="hanoi" {{ $userAddress && $userAddress->province === 'hanoi' ? 'selected' : '' }}>Hà Nội</option>
                                    <option value="hcm" {{ $userAddress && $userAddress->province === 'hcm' ? 'selected' : '' }}>Hồ Chí Minh</option>
                                    <option value="hue" {{ $userAddress && $userAddress->province === 'hue' ? 'selected' : '' }}>Thừa Thiên Huế</option>
                                </select>
                                <select name="district" id="district" class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                                    <option value="">Chọn quận/huyện</option>
                                </select>
                                <select name="ward" id="ward" class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                                    <option value="">Chọn phường/xã</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="address" id="address_detail" class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Nhập địa chỉ cụ thể" value="{{ $userAddress ? $userAddress->address : '' }}" required>
                        </div>

                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phương thức thanh toán</label>
                            <div class="payment-options flex gap-2">
                                <label class="payment-option flex items-center px-2 py-1 border border-gray-300 rounded-md text-sm cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="cash" checked class="mr-2">
                                    <i class="fas fa-money-bill-wave text-green-600"></i>
                                    <span class="ml-1 text-gray-700">Tiền mặt</span>
                                </label>
                                <label class="payment-option flex items-center px-2 py-1 border border-gray-300 rounded-md text-sm cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="vnpay" class="mr-2">
                                    <i class="fab fa-cc-visa text-green-600"></i>
                                    <span class="ml-1 text-gray-700">VNPay</span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn theme-btn-1 w-full py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 transition duration-300">Hoàn tất</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-2">
                    Danh sách sản phẩm
                </h4>
                <div class="order-summary bg-gray-100 p-3 rounded-lg">
                    <div class="mb-2">
                        @foreach ($selectedProducts as $product)
                            <div class="flex items-center order-summary-item gap-3 py-2">
                                <img src="{{ $product['thumbnail'] }}" alt="{{ $product['name'] }}" class="w-14 h-14 object-cover rounded-md">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-800 font-semibold truncate">{{ $product['name'] }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $product['name_variant'] }}</p>
                                </div>
                                <div class="flex flex-col items-end min-w-[90px]">
                                    <span class="text-base text-red-600 font-bold">{{ number_format($product['price'] * $product['quantity']) }}đ</span>
                                    <span class="text-xs text-gray-400 line-through">{{ number_format($product['price'] * $product['quantity'] * 1.3) }}đ</span>
                                    <span class="text-xs text-gray-500">x{{ $product['quantity'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-t border-gray-200 pt-2">
                        <div class="flex justify-between text-sm text-gray-700 mb-1">
                            <span>Tạm tính</span>
                            <span>{{ number_format($grandTotal + ($discount ?? 0)) }}đ</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-700 mb-1">
                            <span>Giảm giá trực tiếp</span>
                            <span class="text-red-600">-{{ number_format($discount ?? 0) }}đ</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-700 mb-1">
                            <span>Giảm giá voucher</span>
                            <span>0đ</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-700">
                            <span>Tiền kiểm dư</span>
                            <span class="text-red-600">{{ number_format($discount ?? 0) }}đ</span>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-100 p-3 rounded-lg mt-2">
                    <div class="flex justify-between text-sm font-semibold text-gray-800">
                        <span>Thành tiền</span>
                        <span class="text-red-600">{{ number_format($grandTotal) }}đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="loading-overlay fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden" id="loading-overlay">
        <div class="loading-spinner border-4 border-gray-300 border-t-blue-500 rounded-full w-12 h-12 animate-spin"></div>
        <div class="loading-text text-white text-sm mt-2" id="loading-message">
            <span></span>
            <div class="loading-dots flex gap-1 mt-1">
                <div class="loading-dot w-2 h-2 bg-blue-500 rounded-full animate-blink"></div>
                <div class="loading-dot w-2 h-2 bg-blue-500 rounded-full animate-blink animation-delay-200"></div>
                <div class="loading-dot w-2 h-2 bg-blue-500 rounded-full animate-blink animation-delay-400"></div>
            </div>
        </div>
        <div class="designed-by text-gray-400 text-xs mt-4">BeePhamarcy</div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province');
            const districtSelect = document.getElementById('district');
            const wardSelect = document.getElementById('ward');
            const addressDetailInput = document.getElementById('address_detail');

            if (!provinceSelect || !districtSelect || !wardSelect || !addressDetailInput) {
                console.error('Không tìm thấy các phần tử cần thiết:', {
                    provinceSelect, districtSelect, wardSelect, addressDetailInput
                });
                return;
            }

            const locations = {
                'hanoi': {
                    districts: [
                        { value: 'dongda', text: 'Đống Đa' },
                        { value: 'baidinh', text: 'Ba Đình' }
                    ],
                    wards: {
                        'dongda': [
                            { value: 'langtrung', text: 'Láng Trung' },
                            { value: 'langha', text: 'Láng Hạ' }
                        ],
                        'baidinh': [
                            { value: 'ngocha', text: 'Ngọc Hà' },
                            { value: 'giangvo', text: 'Giảng Võ' }
                        ]
                    }
                },
                'hcm': {
                    districts: [
                        { value: 'quan1', text: 'Quận 1' },
                        { value: 'phunhuan', text: 'Phú Nhuận' }
                    ],
                    wards: {
                        'quan1': [
                            { value: 'lethanh', text: 'Lê Thánh Tôn' },
                            { value: 'bennghe', text: 'Bến Nghé' }
                        ],
                        'phunhuan': [
                            { value: 'leloi', text: 'Lê Lợi' },
                            { value: 'tanbinh', text: 'Tân Bình' }
                        ]
                    }
                },
                'hue': {
                    districts: [
                        { value: 'phunhuan_hue', text: 'Phú Nhuận' },
                        { value: 'huongtra', text: 'Hương Trà' }
                    ],
                    wards: {
                        'phunhuan_hue': [
                            { value: 'leloi_hue', text: 'Lê Lợi' },
                            { value: 'thuanthanh', text: 'Thuận Thành' }
                        ],
                        'huongtra': [
                            { value: 'huongxuan', text: 'Hương Xuân' },
                            { value: 'huongvan', text: 'Hương Văn' }
                        ]
                    }
                }
            };

            // Cập nhật dropdown Quận/Huyện khi chọn Tỉnh/Thành phố
            provinceSelect.addEventListener('change', function() {
                districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';

                if (this.value && locations[this.value]) {
                    locations[this.value].districts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.value;
                        option.textContent = district.text;
                        districtSelect.appendChild(option);
                    });
                }
                updateAddressDetail();
            });

            // Cập nhật dropdown Xã/Phường khi chọn Quận/Huyện
            districtSelect.addEventListener('change', function() {
                wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';

                const province = provinceSelect.value;
                if (province && this.value && locations[province].wards[this.value]) {
                    locations[province].wards[this.value].forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward.value;
                        option.textContent = ward.text;
                        wardSelect.appendChild(option);
                    });
                }
                updateAddressDetail();
            });

            // Cập nhật Địa chỉ chi tiết khi chọn Xã/Phường
            wardSelect.addEventListener('change', function() {
                updateAddressDetail();
            });

            // Hàm cập nhật giá trị Địa chỉ chi tiết
            function updateAddressDetail() {
                const province = provinceSelect.value;
                const district = districtSelect.value;
                const ward = wardSelect.value;

                if (province && district && ward) {
                    const provinceText = provinceSelect.options[provinceSelect.selectedIndex].text;
                    const districtText = districtSelect.options[districtSelect.selectedIndex].text;
                    const wardText = wardSelect.options[wardSelect.selectedIndex].text;
                    addressDetailInput.value = `${wardText}, ${districtText}, ${provinceText}`;
                } else {
                    addressDetailInput.value = '';
                }
            }

            // Khởi tạo giá trị ban đầu từ $userAddress
            if (provinceSelect.value) {
                provinceSelect.dispatchEvent(new Event('change'));
                if (districtSelect.value) {
                    districtSelect.dispatchEvent(new Event('change'));
                    if (wardSelect.value) {
                        wardSelect.dispatchEvent(new Event('change'));
                    }
                }
            }

            // Sự kiện submit form
            document.getElementById('checkout-form').addEventListener('submit', function(event) {
                event.preventDefault();
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
                const loadingMessage = document.getElementById('loading-message').querySelector('span');

                if (paymentMethod === 'cash') {
                    loadingMessage.textContent = 'Đang tiến hành thanh toán vui lòng đợi';
                } else if (paymentMethod === 'vnpay') {
                    loadingMessage.textContent = 'Đang chuyển đến giao diện VNPay';
                }

                document.getElementById('page-content').classList.add('opacity-0');
                document.getElementById('loading-overlay').classList.remove('hidden');

                setTimeout(() => {
                    this.submit();
                }, 2000);
            });

            // jQuery cho payment options
            $(document).ready(function() {
                $('.payment-option input[type="radio"]').on('change', function() {
                    $('.payment-option').removeClass('selected');
                    if ($(this).is(':checked')) {
                        $(this).closest('.payment-option').addClass('selected');
                    }
                });

                $('.payment-option input[type="radio"]:checked').closest('.payment-option').addClass('selected');

                const clickSound = new Audio('/sounds/click.mp3');
                $('.payment-option').on('click', function() {
                    if (localStorage.getItem('soundEnabled') !== 'false') {
                        clickSound.currentTime = 0;
                        clickSound.play().catch(err => console.log('Sound playback failed:', err));
                    }
                });

                $('.theme-btn-1').on('click', function() {
                    if (localStorage.getItem('soundEnabled') !== 'false') {
                        clickSound.currentTime = 0;
                        clickSound.play().catch(err => console.log('Sound playback failed:', err));
                    }
                });
            });
        });
    </script>
   <style>
.checkout-container {
    margin-top: 32px;
    margin-bottom: 32px;
}
.checkout-container .row {
    display: flex;
    gap: 40px;
    align-items: flex-start;
}
.checkout-container .col-lg-6 {
    flex: 1 1 0;
    min-width: 340px;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 32px rgba(0,0,0,0.09);
    padding: 32px 28px;
    margin-bottom: 0;
    transition: box-shadow 0.3s;
}
.checkout-container .col-lg-6:hover {
    box-shadow: 0 8px 40px rgba(59,130,246,0.13);
}
#page-content, .order-summary {
    background-color: #f7fafc;
    padding: 18px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(59,130,246,0.04);
}
h4 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #2563eb;
    margin-bottom: 18px;
}
.form-group label,
label.flex {
    font-size: 15px;
    color: #1e293b;
    margin-bottom: 4px;
    font-weight: 600;
}
.form-control, .form-select, input[type="text"], input[type="email"], select {
    width: 100%;
    padding: 10px 12px;
    border: 1.5px solid #dbeafe;
    border-radius: 8px;
    font-size: 15px;
    background-color: #f9fafb;
    margin-bottom: 8px;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.form-control:focus, .form-select:focus, input[type="text"]:focus, input[type="email"]:focus, select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px #dbeafe;
    outline: none;
}
.payment-options {
    display: flex;
    gap: 18px;
    margin-top: 8px;
}
.payment-option {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border: 2px solid #e0e7ef;
    border-radius: 10px;
    cursor: pointer;
    background: #f1f5f9;
    font-weight: 500;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    user-select: none;
}
.payment-option.selected, .payment-option:hover {
    border-color: #2563eb;
    background: #e0e7ff;
    box-shadow: 0 2px 12px rgba(59,130,246,0.08);
}
.payment-option input[type="radio"] {
    accent-color: #2563eb;
}
.theme-btn-1 {
    background: linear-gradient(90deg, #2563eb 0%, #60a5fa 100%);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 16px 0;
    font-size: 1.15rem;
    font-weight: 700;
    margin-top: 18px;
    box-shadow: 0 2px 8px rgba(59,130,246,0.08);
    transition: background 0.2s, box-shadow 0.2s;
    width: 100%;
    letter-spacing: 0.5px;
}
.theme-btn-1:hover {
    background: linear-gradient(90deg, #60a5fa 0%, #2563eb 100%);
    box-shadow: 0 4px 16px rgba(59,130,246,0.18);
}
.order-summary .product-image, .order-summary img {
    width: 52px;
    height: 52px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e0e7ef;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
}
.order-summary .flex {
    align-items: center;
}
.order-summary .bg-red-100 {
    background: #fee2e2;
}
.order-summary .text-red-600 {
    color: #dc2626;
}
.order-summary .text-pink-500 {
    color: #ec4899;
}
.order-summary .line-through {
    text-decoration: line-through;
}
.order-summary {
    margin-top: 16px;
    border-radius: 12px;
    background: #f8fafc;
    padding: 18px 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    transition: box-shadow 0.2s;
}
.order-summary tr {
    transition: background 0.2s;
}
.order-summary tr:hover {
    background: #e0e7ff;
}
.order-summary-item {
    border: 1.5px solid #e0e7ef;
    border-radius: 10px;
    background: #fff;
    margin-bottom: 10px;
    padding: 10px 12px;
    box-shadow: 0 1px 6px rgba(59,130,246,0.04);
    transition: border-color 0.2s, box-shadow 0.2s;
}
.order-summary-item:hover {
    border-color: #2563eb;
    box-shadow: 0 4px 16px rgba(59,130,246,0.10);
    background: #f0f6ff;
}
.loading-overlay {
    z-index: 9999;
    background: rgba(30,41,59,0.12);
    display: none;
    align-items: center;
    justify-content: center;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    flex-direction: column;
    transition: opacity 0.3s;
}
.order-summary-item {
    display: flex;
    align-items: center;
    gap: 16px;
    border: 1.5px solid #e0e7ef;
    border-radius: 10px;
    background: #fff;
    margin-bottom: 10px;
    padding: 10px 12px;
    box-shadow: 0 1px 6px rgba(59,130,246,0.04);
    transition: border-color 0.2s, box-shadow 0.2s;
}
.order-summary-item:hover {
    border-color: #2563eb;
    box-shadow: 0 4px 16px rgba(59,130,246,0.10);
    background: #f0f6ff;
}
.order-summary-item img {
    width: 56px;
    height: 56px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e0e7ef;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
}
.order-summary-item .flex-1 {
    min-width: 0;
}
.order-summary-item .text-base {
    font-size: 1.05rem;
}
.loading-overlay.active, .loading-overlay.show {
    display: flex !important;
    opacity: 1;
}
.loading-spinner {
    border: 6px solid #e0e7ef;
    border-top: 6px solid #2563eb;
    border-radius: 50%;
    width: 56px;
    height: 56px;
    animation: spin 1s linear infinite;
    margin-bottom: 18px;
}
@keyframes spin {
    0% { transform: rotate(0deg);}
    100% { transform: rotate(360deg);}
}
.loading-text {
    font-size: 1.2rem;
    color: #2563eb;
    font-weight: 600;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.loading-dots {
    display: inline-block;
    margin-left: 6px;
}
.loading-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    background: #2563eb;
    border-radius: 50%;
    margin-right: 2px;
    animation: bounce 1.2s infinite both;
}
.loading-dot:nth-child(2) { animation-delay: 0.2s;}
.loading-dot:nth-child(3) { animation-delay: 0.4s;}
.loading-dot:nth-child(4) { animation-delay: 0.6s;}
@keyframes bounce {
    0%, 80%, 100% { transform: scale(1);}
    40% { transform: scale(1.5);}
}
.hidden-content, #page-content.opacity-0 {
    opacity: 0.3;
    pointer-events: none;
    filter: blur(2px);
    transition: opacity 0.3s, filter 0.3s;
}
@media (max-width: 991px) {
    .checkout-container .row {
        flex-direction: column;
        gap: 20px;
    }
    .checkout-container .col-lg-6 {
        padding: 12px 4px;
    }
}
</style>