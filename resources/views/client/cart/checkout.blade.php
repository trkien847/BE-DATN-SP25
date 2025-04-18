@extends('client.layouts.layout')
@section('content')
    <style>
        /* General container styling */
        .checkout-container {
            margin-top: 40px;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            animation: fadeIn 1s ease-in-out;
        }

        /* Form group and inputs */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: #28a745; /* Green focus border */
            box-shadow: 0 0 8px rgba(40, 167, 69, 0.3);
            outline: none;
            transform: scale(1.01);
        }

        /* Payment method group */
        .payment-method-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            display: block;
        }

        .payment-options {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .payment-option {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            background: #f9f9f9;
            transition: all 0.3s ease;
            position: relative;
            flex: 1;
            min-width: 150px;
        }

        .payment-option:hover {
            border-color: #28a745;
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .payment-option.selected {
            border-color: #28a745;
            background: #e6f4ea;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .payment-option input[type="radio"] {
            margin-right: 10px;
            cursor: pointer;
            accent-color: #28a745;
        }

        .payment-option i {
            margin-right: 8px;
            font-size: 20px;
            color: #666;
            transition: color 0.3s ease;
        }

        .payment-option input[type="radio"]:checked + i {
            color: #28a745;
        }

        .payment-option input[type="radio"]:checked ~ span {
            color: #28a745;
            font-weight: 600;
        }

        /* Submit button */
        .theme-btn-1 {
            background-color: #28a745; /* Green button */
            color: white;
            border-radius: 8px;
            padding: 12px 0;
            width: 100%;
            border: none;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .theme-btn-1:hover {
            background-color: #218838; /* Darker green */
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .theme-btn-1::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .theme-btn-1:hover::before {
            left: 100%;
        }

        /* Order summary */
        .order-summary {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .table {
            margin-bottom: 0;
        }

        .table td, .table th {
            vertical-align: middle;
            border: none;
            padding: 12px;
            font-size: 15px;
        }

        .table tr {
            transition: background 0.3s ease;
        }

        .table tr:hover {
            background: #e9ecef;
        }

        .product-image {
            border-radius: 8px;
            transition: transform 0.3s ease;
            width: 50px;
            height: 50px;
            object-fit: cover;
        }

        .product-image:hover {
            transform: scale(1.2);
        }

        /* Headings */
        h4 {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            position: relative;
            animation: slideIn 1s ease-in-out;
        }

        h4::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 50px;
            height: 3px;
            background: #28a745;
            border-radius: 2px;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .checkout-container .row > div {
                margin-bottom: 30px;
            }

            .payment-options {
                flex-direction: column;
            }

            .payment-option {
                width: 100%;
            }

            .theme-btn-1 {
                font-size: 14px;
                padding: 10px 0;
            }
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
            display: none;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            z-index: 9999;
        }

        .loading-spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .loading-dots {
            display: inline-flex;
            margin-left: 10px;
        }

        .loading-dot {
            width: 10px;
            height: 10px;
            background-color: #3498db;
            border-radius: 50%;
            margin: 0 3px;
            animation: moveDots 1.5s infinite ease-in-out;
        }

        .loading-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .loading-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        .loading-dot:nth-child(4) {
            animation-delay: 0.6s;
        }

        @keyframes moveDots {
            0% {
                transform: translateX(-20px);
                opacity: 0;
            }
            50% {
                transform: translateX(0);
                opacity: 1;
            }
            100% {
                transform: translateX(20px);
                opacity: 0;
            }
        }

        .designed-by {
            font-size: 14px;
            color: #777;
        }

        /* Ẩn nội dung trang khi loading */
        .hidden-content {
            display: none;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @if(session('error'))
        <div class="alert alert-danger p-3 mb-4 rounded-lg bg-red-100 text-red-700 border border-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="container checkout-container">
        <div class="row">
            <div class="col-lg-6">
                <h4>Thông tin giao hàng</h4>
            <div id="page-content">
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
                    
                    <div class="form-group">
                        <label for="name">Họ tên</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $user->fullname ?? '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ $user->email ?? '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ $user->phone_number ?? '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Địa chỉ</label>
                        <input type="text" name="address" id="address" class="form-control" value="{{ $userAddress ? $userAddress->address : '' }}" required>
                    </div>
                    <div class="form-group payment-method-group">
                        <label>Phương thức thanh toán</label>
                        <div class="payment-options">
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="cash" checked>
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Tiền mặt</span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="vnpay">
                                <i class="fab fa-cc-visa"></i>
                                <span>VNPay</span>
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn theme-btn-1">Đặt hàng</button>
                </form>
            </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-6">
                <h4>Thông tin đơn hàng</h4>
                <div class="order-summary">
                    <table class="table">
                        <tbody>
                            @foreach ($selectedProducts as $product)
                                <tr>
                                    <td><img src="{{ $product['thumbnail'] }}" alt="{{ $product['name'] }}" class="product-image"></td>
                                    <td>{{ $product['name'] }}</td>
                                    <td>{{ $product['name_variant'] }}</td>
                                    <td>{{ $product['quantity'] }}</td>
                                    <td>{{ number_format($product['price'] * $product['quantity']) }}₫</td>
                                </tr>
                            @endforeach
                            @if ($couponCode)
                                <tr>
                                    <td colspan="3">Giảm giá ({{ $couponCode }})</td>
                                    <td>-{{ number_format($discount) }}₫</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="3"><strong>Tổng tiền</strong></td>
                                <td><strong>{{ number_format($grandTotal) }}₫</strong></td>
                            </tr>
                        </tbody>
                    </table>
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
                <div class="loading-dot"></div>
            </div>
        </div>
        <div class="designed-by">Designed by TG</div>
    </div>
    <script>


        document.getElementById('checkout-form').addEventListener('submit', function (event) {
            event.preventDefault();

            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            const loadingMessage = document.getElementById('loading-message').querySelector('span');

            if (paymentMethod === 'cash') {
                loadingMessage.textContent = 'Đang tiến hành thanh toán vui lòng đợi';
            } else if (paymentMethod === 'vnpay') {
                loadingMessage.textContent = 'Đang chuyển đến giao diện VNPay';
            }

            document.getElementById('page-content').classList.add('hidden-content');

            document.getElementById('loading-overlay').style.display = 'flex';

            setTimeout(() => {
                this.submit();
            }, 2000);
        });

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
    </script>
@endsection