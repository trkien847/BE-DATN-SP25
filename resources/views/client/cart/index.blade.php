@extends('client.layouts.layout')
@section('content')
    {{-- @include('client.components.CartMenuStart') --}}
    <div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image " data-bs-bg="img/bg/14.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">Cart</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="index.html"><span class="ltn__secondary-color"><i
                                                class="fas fa-home"></i></span> Home</a></li>
                                <li id="cart-page">Cart</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <!-- SHOPING CART AREA START -->
    <div class="liton__shoping-cart-area mb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping-cart-inner">
                        <div class="shoping-cart-table table-responsive">
                            <table class="table">
                                <thead>
                                    <th class="cart-product-remove">Remove</th>
                                    <th class="cart-product-image">Image</th>
                                    <th class="cart-product-info">Product</th>
                                    <th class="cart-product-price">Price</th>
                                    <th class="cart-product-quantity">Quantity</th>
                                    <th class="cart-product-subtotal">Subtotal</th>
                                </thead>
                                <tbody>
                                    @foreach ($carts as $cart)
                                        <tr data-cart-id="{{ $cart->id }}">
                                            <td class="cart-product-remove">x</td>
                                            <td class="cart-product-image">
                                                <a href="product-details.html"><img
                                                        src="{{ asset('upload/' . $cart->product->thumbnail) }}"
                                                        alt="#"></a>
                                            </td>
                                            <td class="cart-product-info">
                                                <h4><a
                                                        href="product-details.html">{{ \Illuminate\Support\Str::limit($cart->product->name, 10, '...') }}</a>
                                                </h4>
                                            </td>
                                            <td class="cart-product-price">
                                                {{ number_format($cart->productVariant->sale_price) }}</td>
                                            <td class="cart-product-quantity">
                                                <div class="cart-plus-minus">
                                                    <input type="text" value="{{ $cart->quantity }}"
                                                        class="cart-plus-minus-box" min="1" readonly>
                                                </div>
                                            </td>
                                            <td class="cart-product-subtotal">
                                                {{ number_format(($cart->productVariant->sale_price && $cart->productVariant->sale_price > 0 ? $cart->productVariant->sale_price : $cart->productVariant->sell_price) * $cart->quantity) }}đ
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="cart-coupon-row">
                                        <td colspan="6">
                                            <div class="cart-coupon">
                                                <input type="text" name="cart-coupon" placeholder="Coupon code">
                                                <button type="submit" class="btn theme-btn-2 btn-effect-2">Apply
                                                    Coupon</button>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="submit" class="btn theme-btn-2 btn-effect-2-- disabled">Update
                                                Cart</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="shoping-cart-total mt-50">
                            <h4>Cart Totals</h4>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Cart Subtotal</td>
                                        <td>$618.00</td>
                                    </tr>
                                    <tr>
                                        <td>Shipping and Handing</td>
                                        <td>$15.00</td>
                                    </tr>
                                    <tr>
                                        <td>Vat</td>
                                        <td>$00.00</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Order Total</strong></td>
                                        <td><strong id="cart-grand-total">{{ number_format($subtotal) }}đ</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="btn-wrapper text-right">
                                <a href="checkout.html" class="theme-btn-1 btn btn-effect-1">Proceed to checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SHOPING CART AREA END -->

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
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            if ($("#cart-page").length) {
                $(".mini-cart-icon-2 a.ltn__utilize-toggle").off("click"); // Xóa sự kiện click
                $(".mini-cart-icon-2 a.ltn__utilize-toggle").css("pointer-events", "none"); // Ngăn click
            }
            let updateTimer;

            // Handle quantity button clicks
            $('.qtybutton').off('click');
            $(document).on('click', '.qtybutton', function() {
                let $button = $(this);
                let $input = $button.siblings('input.cart-plus-minus-box');
                let oldValue = parseInt($input.val());

                let newVal = oldValue;
                if ($button.hasClass('inc')) {
                    newVal = oldValue + 1;
                } else if ($button.hasClass('dec') && oldValue > 1) {
                    newVal = oldValue - 1;
                }

                $input.val(newVal);

                // Clear existing timeout
                clearTimeout(updateTimer);

                // Set new timeout to update quantity
                updateTimer = setTimeout(function() {
                    // Trigger the change event instead of custom event
                    $input.trigger('change');
                }, 500);
            });

            // Handle quantity changes
            $('.cart-plus-minus-box').on('change', function() {
                const $row = $(this).closest('tr');
                const cartId = $row.data('cart-id');
                const quantity = parseInt($(this).val(), 10);

                if (quantity < 1) {
                    $(this).val(1);
                    return;
                }

                $row.addClass('updating');

                $.ajax({
                    url: "{{ route('cart.update') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        cart_id: cartId,
                        quantity: quantity
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            const price = parseFloat($row.find('.cart-product-price').text()
                                .replace(/[,.đ]/g, ''));
                            const newSubtotal = price * quantity;
                            $row.find('.cart-product-subtotal').text(
                                new Intl.NumberFormat('vi-VN').format(newSubtotal) + 'đ'
                            );
                            updateCartTotal();
                            showToast("Cập nhật giỏ hàng thành công!", "success");
                        }
                    },
                    error: function(xhr) {
                        showToast("Có lỗi xảy ra khi cập nhật giỏ hàng!", "error");
                    },
                    complete: function() {
                        $row.removeClass('updating');
                    }
                });
            });

            // Xử lý xóa sản phẩm
            $(document).on('click', '.cart-product-remove', function() {
                let cartRow = $(this).closest('tr');
                let cartId = cartRow.data('cart-id');

                $.ajax({
                    url: "{{ route('cart.remove') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        cart_id: cartId
                    },
                    success: function(response) {
                        if (response.status === "success") {
                            cartRow.remove();
                            updateCartTotal();
                            showToast(response.message, "success");
                        } else {
                            showToast(response.message, "error");
                        }
                    }
                });
            });

        });

        // Các hàm helper
        function updateCartTotal() {
            let total = 0;

            $(".cart-product-subtotal").each(function() {
                let value = $(this).text().replace(/[^0-9]/g, ''); // Chỉ giữ lại số
                let price = value ? parseFloat(value) : 0; // Nếu rỗng thì gán 0 để tránh NaN
                total += price;
            });

            // Kiểm tra nếu total hợp lệ, nếu không gán 0
            total = isNaN(total) ? 0 : total;

            $("#cart-subtotal").text(total.toLocaleString('vi-VN') + "đ");
            $(".mini-cart-sub-total span").text(total.toLocaleString('vi-VN') + "đ");
            $("#cart-grand-total").text(total.toLocaleString('vi-VN') + "đ");
        }


        function showToast(message, type = "success") {
            let bgColor = type === "success" ? "#4caf50" : "#f44336";
            Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: bgColor,
                stopOnFocus: true
            }).showToast();
        }
    </script>
@endpush
@push('css')
    <style>
        .cart-plus-minus {
            position: relative;
            display: inline-flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .cart-plus-minus-box {
            width: 60px;
            height: 40px;
            text-align: center;
            border: none;
            background: none;
        }

        .qtybutton:hover {
            background: #f5f5f5;
        }

        .updating {
            opacity: 0.6;
            pointer-events: none;
        }

        .cart-product-subtotal {
    </style>
@endpush
