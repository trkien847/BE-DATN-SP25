@extends('client.layouts.layout')
@section('content')
    <style>
      
        .checkout-container {
            margin-top: 20px;
        }

        
        .form-group {
            margin-bottom: 15px;
        }
        .form-control {
            border-radius: 5px;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #66afe9;
            box-shadow: 0 0 8px rgba(102, 175, 233, 0.6);
            outline: none;
        }

       
        .theme-btn-1 {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .theme-btn-1:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

       
        .order-summary {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .product-image {
            border-radius: 5px;
            transition: transform 0.3s ease;
        }
        .product-image:hover {
            transform: scale(1.1);
        }

       
        h4 {
            animation: fadeIn 1s ease-in-out;
        }
        
    .checkout-container {
        margin-top: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-control {
        border-radius: 5px;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #66afe9;
        box-shadow: 0 0 8px rgba(102, 175, 233, 0.6);
        outline: none;
    }

   
    .theme-btn-1 {
        background-color: #007bff;
        color: white;
        border-radius: 5px;
        padding: 10px 0;
        width: 100%; 
        border: 2px solid #28a745; 
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .theme-btn-1:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

   
    .payment-method-group {
        margin-bottom: 15px;
    }

    .payment-options {
        display: flex;
        gap: 15px;
    }

    .payment-option {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        border: 2px solid #ddd; 
        border-radius: 5px;
        cursor: pointer;
        transition: border-color 0.3s ease, transform 0.2s ease;
    }

    .payment-option:hover {
        border-color: #28a745;
        transform: translateY(-2px);
    }

    .payment-option input[type="radio"] {
        margin-right: 8px;
        cursor: pointer;
    }

    .payment-option i {
        margin-right: 8px;
        font-size: 18px;
    }

    
    .payment-option input[type="radio"]:checked + i {
        color: #28a745; 
    }

    .payment-option input[type="radio"]:checked ~ * {
        color: #28a745; 
    }

    .payment-option input[type="radio"]:checked + * + * {
        border-color: #dc3545; 
    }

    
    h4 {
        animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    
    @media (max-width: 768px) {
        .checkout-container .row > div {
            margin-bottom: 20px;
        }
    }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @media (max-width: 768px) {
            .checkout-container .row > div {
                margin-bottom: 20px;
            }
        }
    </style>
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

    <div class="container checkout-container">
        <div class="row">
           
            <div class="col-lg-6">
                <h4>Thông tin giao hàng</h4>
                <form action="{{ route('checkout.process') }}" method="POST">
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
                        <label>Họ tên</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->fullname ?? '' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email ?? '' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" value="{{ $user->phone_number ?? '' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ</label>
                        <input type="text" name="address" class="form-control" value="{{ $userAddress ? $userAddress->address : '' }}" required>
                    </div>
                    <div class="form-group payment-method-group">
                        <label>Phương thức thanh toán</label>
                        <div class="payment-options">
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="cash" checked>
                                <i class="fas fa-money-bill-wave"></i> Tiền mặt
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="vnpay">
                                <i class="fab fa-cc-visa"></i> VNPay
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn theme-btn-1">Đặt hàng</button>
                </form>
            </div>

            
            <div class="col-lg-6">
                <h4>Thông tin đơn hàng</h4>
                <div class="order-summary">
                    <table class="table">
                        <tbody>
                            @foreach ($selectedProducts as $product)
                                <tr>
                                    <td><img src="{{ $product['thumbnail'] }}" width="50" class="product-image"></td>
                                    <td>{{ $product['name'] }}</td>
                                    <td>{{ $product['quantity'] }}</td>
                                    <td>{{ number_format($product['price'] * $product['quantity']) }}đ</td>
                                </tr>
                            @endforeach
                           
                            @if ($couponCode)
                                <tr>
                                    <td colspan="3">Giảm giá ({{ $couponCode }})</td>
                                    <td>-{{ number_format($discount) }}đ</td>
                                </tr>
                            @endif

                            <tr>
                                <td colspan="3"><strong>Tổng tiền</strong></td>
                                <td><strong>{{ number_format($grandTotal) }}đ</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <script>
    $(document).ready(function() {
        $('.payment-option input[type="radio"]').on('change', function() {
            $('.payment-option').removeClass('selected');
            if ($(this).is(':checked')) {
                $(this).closest('.payment-option').addClass('selected');
            }
        });

        $('.payment-option input[type="radio"]:checked').closest('.payment-option').addClass('selected');
    });

    
</script>
@endsection