@extends('client.layouts.layout')
@section('content')
    <div>
        <audio id="backgroundMusic" autoplay>
            <source src="{{ asset('audio/wake-up.mp3') }}" type="audio/mpeg">
        </audio>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const audio = document.getElementById('backgroundMusic');
                audio.volume = 1;
                let playPromise = audio.play();

                if (playPromise !== undefined) {
                    playPromise.catch(error => {
                        console.log("Autoplay was prevented");
                    });
                }
                document.addEventListener('visibilitychange', function() {
                    if (!document.hidden && !audio.ended) {
                        audio.play();
                    }
                });
            });
        </script>
    </div>
    <style>
        .cart-coupon {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .coupon-btn {
            position: relative;
            padding: 10px 20px;
            border: 2px solid #22C55E;
            background-color: #fff;
            color: #22C55E;
            font-weight: 700;
            font-size: 14px;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 0 0 transparent;
        }

        .coupon-btn:hover:not(:disabled),
        .coupon-btn:focus-visible:not(:disabled) {
            background-color: #22C55E;
            color: #22C55E;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
            transform: translateY(-2px);
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
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loading-text {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .designed-by {
            font-size: 14px;
            color: #777;
        }

        .hidden-content {
            display: none;
        }
    </style>
    <div class="ltn__utilize-overlay"></div>

    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image " data-bs-bg="img/bg/14.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">Giỏ hàng</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="{{ route('index') }}"><span class="ltn__secondary-color"><i
                                                class="fas fa-home"></i></span>Trang chủ</a></li>
                                <li id="cart-page">Giỏ hàng</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="liton__shoping-cart-area mb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping-cart-inner">

                        <div class="shoping-cart-table table-responsive">
                            <table class="table shopping-cart-main-table" id="cart-table">
                                <thead>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th class="cart-product-remove-header">Xóa</th>
                                    <th class="cart-product-image">Ảnh</th>
                                    <th class="cart-product-info">Tên sản phẩm</th>
                                    <th class="cart-product-price">Giá</th>
                                    <th class="cart-product-quantity">Số lượng</th>
                                    <th class="cart-product-subtotal">Tổng tiền</th>
                                </thead>
                                <tbody id="cart-items">
                                    @foreach ($carts as $cart)
                                        @php
                                            $variant = $cart->productVariant;
                                            $shapeValue = $variant?->attributeValues->firstWhere('attribute_id', 12);
                                            $weightValue = $variant?->attributeValues->firstWhere('attribute_id', 14);
                                            $variantName = '';

                                            if ($shapeValue && $weightValue) {
                                                $variantName = "{$shapeValue->value} {$weightValue->value}";
                                            } elseif ($variant) {
                                                $variantName = $variant->attributeValues
                                                    ->map(fn($av) => "{$av->attribute->name}: {$av->value}")
                                                    ->join(', ');
                                            }
                                        @endphp
                                        <tr data-cart-id="{{ $cart->id }}" data-product-id="{{ $cart->product->id }}">
                                            <td><input type="checkbox" class="cart-item-checkbox"
                                                    data-cart-id="{{ $cart->id }}"
                                                    data-product-id="{{ $cart->product->id }}"></td>
                                            <td class="cart-product-remove"><i class="fas fa-trash-alt"></td>
                                            <td class="cart-product-image">
                                                <a href="{{ route('products.productct', $cart->product->id) }}">
                                                    <img src="{{ asset('upload/' . $cart->product->thumbnail) }}"
                                                        alt="#">
                                                </a>
                                            </td>
                                            <td class="cart-product-info">
                                                <h4><a
                                                        href="{{ route('products.productct', $cart->product->id) }}">{{ $cart->product->name }}</a>
                                                </h4>
                                                <p class="text-sm text-gray-500">{{ $variantName }}</p>
                                            </td>
                                            <td class="cart-product-price">
                                                {{ number_format($cart->productVariant->sale_price > 0 ? $cart->productVariant->sale_price : $cart->productVariant->price) }}đ
                                            </td>

                                            <td class="cart-product-quantity">
                                                <div class="cart-plus-minus">
                                                    <input type="text" value="{{ $cart->quantity }}"
                                                        class="cart-plus-minus-box" min="1" readonly>
                                                </div>
                                                <small class="text-warning quantity-warning" style="display: none;">Tối đa
                                                    30 sản phẩm!</small>
                                            </td>

                                            <td class="cart-product-subtotal">
                                                @php
                                                    $price =
                                                        $cart->productVariant->sale_price > 0
                                                            ? $cart->productVariant->sale_price
                                                            : $cart->productVariant->price;
                                                    $subtotal = $price * $cart->quantity;
                                                @endphp
                                                {{ number_format($subtotal) }}đ
                                            </td>

                                            <td class="cart-product-attributes" style="display: none;">
                                                {{ $cart->productVariant->id }}
                                            </td>


                                        </tr>
                                    @endforeach
                                    <tr class="cart-coupon-row">
                                        <td colspan="6">
                                            <div class="cart-coupon flex items-center gap-3">
                                                <input type="text" name="cart-coupon" placeholder="Coupon code"
                                                    id="coupon-code"
                                                    class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                                    {{ $appliedCoupon ? 'disabled' : '' }}>
                                                <button type="button" class="btn btn-effect-2 coupon-btn" id="apply-coupon"
                                                    {{ $appliedCoupon ? 'disabled' : '' }}>
                                                    <span class="btn-text">Sử dụng mã giảm giá</span>

                                                </button>
                                                @if ($appliedCoupon)
                                                    <small id="applied-coupon-text">Đã áp dụng:
                                                        {{ $appliedCoupon['code'] }} (Giảm
                                                        {{ number_format($appliedCoupon['discount']) }}đ)</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-effect-2 coupon-btn" id="show-coupons">
                                                <span class="btn-text">Lấy mã giảm giá</span>

                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <script>
                            document.querySelectorAll('.coupon-btn').forEach(button => {
                                button.addEventListener('click', () => {
                                    if (button.disabled) return;
                                    console.log(`${button.id} clicked`);
                                });
                            });
                        </script>
                        <div class="shoping-cart-total mt-50">
                            <h4>Thông tin đơn hàng:</h4>
                            <table class="table">
                                <tbody id="cart-details">

                                </tbody>
                                <tbody id="totals-section">
                                    @if ($appliedCoupon)
                                        <tr id="discount-row">
                                            <td>Giảm giá ({{ $appliedCoupon['code'] }})</td>
                                            <td>-{{ number_format($appliedCoupon['discount']) }}đ</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><strong>Số tiền cần thanh toán: </strong></td>
                                        <td><strong
                                                id="cart-grand-total">{{ number_format($subtotal - ($appliedCoupon['discount'] ?? 0)) }}đ</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="btn-wrapper text-right" id="page-content">
                                <form id="checkout-form" action="{{ route('checkout') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="selected_products" id="selected-products">
                                    <input type="hidden" name="coupon_code" id="coupon-code-hidden">
                                    <input type="hidden" name="discount" id="discount-hidden">
                                    <input type="hidden" name="grand_total" id="grand-total-hidden">
                                    <button type="submit" class="theme-btn-1 btn btn-effect-1">Thanh toán</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="coupon-overlay" class="coupon-overlay" style="display: none;">
        <div class="coupon-content">
            <h3>Danh sách mã giảm giá</h3>
            <div id="coupon-list">

            </div>
            <button class="btn theme-btn-2 btn-effect-2" id="close-coupons">Đóng</button>
        </div>
    </div>
    <div class="ltn__call-to-action-area call-to-action-6 before-bg-bottom" data-bs-bg="img/1.jpg--">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div
                        class="call-to-action-inner call-to-action-inner-6 ltn__secondary-bg position-relative text-center---">
                        <div class="coll-to-info text-color-white">
                            <h1>Mua khẩu trang y tế dùng một lần <br> để bảo vệ người thân yêu của bạn</h1>
                        </div>
                        <div class="btn-wrapper">
                            <a class="btn btn-effect-3 btn-white" href="shop.html">
                                Khám phá sản phẩm <i class="icon-next"></i>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="loading-overlay" id="loading-overlay">
        <div class="loading-spinner"></div>
        <div class="loading-text">Đang tiến hành lên đơn</div>
        <div class="designed-by">BeePhamarcy</div>
    </div>
@endsection
@push('js')
    <script>
        document.getElementById('checkout-form').addEventListener('submit', function(event) {
            event.preventDefault();
            document.getElementById('page-content').classList.add('hidden-content');
            document.getElementById('loading-overlay').style.display = 'flex';
            setTimeout(() => {
                this.submit();
            }, 2000);
        });

        $(document).ready(function() {
            function animateRow(row) {
                row.addClass('animate__animated animate__fadeIn');
                setTimeout(() => row.removeClass('animate__animated animate__fadeIn'), 1000);
            }

            $('.cart-plus-minus-box').on('change', function() {
                $(this).closest('tr').addClass('updating');
            });

            $('.cart-product-remove').on('click', function() {
                const row = $(this).closest('tr');
                row.addClass('animate__animated animate__fadeOutRight');
                setTimeout(() => row.remove(), 500);
            });

            $('.cart-item-checkbox').on('change', function() {
                const checkbox = $(this);
                checkbox.addClass('pulse');
                setTimeout(() => checkbox.removeClass('pulse'), 500);
            });

            $('.theme-btn-2').hover(
                function() {
                    $(this).addClass('hover');
                },
                function() {
                    $(this).removeClass('hover');
                }
            );
        });

        $(document).ready(function() {

            if ($("#cart-page").length) {
                $(".mini-cart-icon-2 a.ltn__utilize-toggle").off("click");
                $(".mini-cart-icon-2 a.ltn__utilize-toggle").css("pointer-events", "none");
            }

            let updateTimer;
            const coupons = @json($coupons);
            const initialAppliedCoupon = @json(session('applied_coupon'));

            $('#select-all').on('change', function() {
                $('.cart-item-checkbox').prop('checked', this.checked).trigger('change');
            });

            $(document).on('change', '.cart-item-checkbox', function() {
                let $row = $(this).closest('tr');
                let isChecked = $(this).is(':checked');
                let cartId = $row.data('cart-id');

                if (isChecked) {
                    addToCartDetails($row);
                } else {
                    removeFromCartDetails(cartId);
                    removeAppliedCoupon();
                }

                if ($('.cart-item-checkbox:checked').length === $('.cart-item-checkbox').length) {
                    $('#select-all').prop('checked', true);
                } else {
                    $('#select-all').prop('checked', false);
                }

                updateCartTotal();
            });


            $(document).on('change', '.cart-details-checkbox', function() {
                let $detailsRow = $(this).closest('tr');
                let cartId = $detailsRow.data('cart-id');
                let $cartRow = $(`#cart-items tr[data-cart-id="${cartId}"]`);

                if (!$(this).is(':checked')) {
                    $cartRow.show();
                    $cartRow.find('.cart-item-checkbox').prop('checked', false);
                    removeFromCartDetails(cartId);
                    removeAppliedCoupon();
                    updateCartTotal();
                }
            });



            $(document).off('click', '.qtybutton').on('click', '.qtybutton', function(e) {
                if ($(this).hasClass('disabled')) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }

                let $button = $(this);
                let $input = $button.siblings('input.cart-plus-minus-box');
                let oldValue = parseInt($input.val());
                let $row = $button.closest('tr');
                let cartId = $row.data('cart-id');

                // Lưu giá trị cũ
                $input.data('old-value', oldValue);
                let newVal = oldValue;

                if ($button.hasClass('inc')) {

                    $.ajax({
                        url: "{{ route('cart.check-quantity') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            cart_id: cartId,
                            quantity: newVal
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                $input.val(newVal);
                                $input.trigger('change');
                            } else {
                                showToast(response.message, "error");
                                // Reset về số lượng cũ
                                $input.val(response.old_quantity);

                                // Cập nhật lại subtotal
                                const price = parseFloat($row.find('.cart-product-price').text()
                                    .replace(/[,.đ]/g, ''));
                                const subtotal = price * response.old_quantity;
                                $row.find('.cart-product-subtotal').text(new Intl.NumberFormat(
                                    'vi-VN').format(subtotal) + 'đ');
                                updateCartTotal();

                                // Disable nút tăng
                                $button.addClass('disabled').css({
                                    'opacity': '0.5',
                                    'cursor': 'not-allowed',
                                    'pointer-events': 'none'
                                });
                            }
                        },
                        error: function() {
                            showToast("Có lỗi xảy ra khi cập nhật số lượng!", "error");
                            // Reset về số lượng cũ
                            $input.val(oldValue);

                            // Cập nhật lại subtotal
                            const price = parseFloat($row.find('.cart-product-price').text()
                                .replace(/[,.đ]/g, ''));
                            const subtotal = price * oldValue;
                            $row.find('.cart-product-subtotal').text(new Intl.NumberFormat(
                                'vi-VN').format(subtotal) + 'đ');
                            updateCartTotal();
                        }
                    });
                } else if ($button.hasClass('dec') && oldValue > 1) {
                    $input.val(newVal);
                    $input.trigger('change');

                    // Kích hoạt lại nút tăng
                    $row.find('.inc').removeClass('disabled').css({
                        'opacity': '1',
                        'cursor': 'pointer',
                        'pointer-events': 'auto'
                    });
                }
            });

            $(document).off('change', '.cart-plus-minus-box').on('change', '.cart-plus-minus-box', function() {
                const $input = $(this);
                const $row = $input.closest('tr');
                const cartId = $row.data('cart-id');
                const quantity = parseInt($input.val(), 10);
                const oldValue = $input.data('old-value');

                if (isNaN(quantity) || quantity < 1) {
                    $input.val(oldValue);
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
                            $row.find('.cart-product-subtotal').text(new Intl.NumberFormat(
                                'vi-VN').format(newSubtotal) + 'đ');
                            updateCartTotal();
                            showToast(response.message, "success");

                            // Cập nhật lại old-value
                            $input.data('old-value', quantity);
                        } else {
                            $input.val(oldValue);
                            showToast(response.message, "error");
                        }
                    },
                    error: function() {
                        $input.val(oldValue);
                        showToast("Có lỗi xảy ra khi cập nhật giỏ hàng!", "error");
                    },
                    complete: function() {
                        $row.removeClass('updating');
                    }
                });
            });

            $(document).ready(function() {
                const MAX_QUANTITY = 30;

                function checkCartQuantities() {
                    $('#cart-items tr:not(.cart-coupon-row)').each(function() {
                        const $row = $(this);
                        const $tdCheckbox = $row.find('td:first-child'); // Lấy td chứa checkbox
                        const $warning = $row.find('.quantity-warning');
                        const quantity = parseInt($row.find('.cart-plus-minus-box').val());
                        const cartId = $row.data('cart-id');
                        const productId = $row.data('product-id');

                        if (quantity > MAX_QUANTITY) {
                            // Xóa checkbox cũ
                            $tdCheckbox.find('.cart-item-checkbox').remove();

                            // Thêm checkbox ẩn để giữ layout
                            const hiddenCheckbox = $('<input>', {
                                type: 'checkbox',
                                class: 'cart-item-checkbox',
                                disabled: true,
                                style: 'visibility: hidden; pointer-events: none;',
                                'data-cart-id': cartId,
                                'data-product-id': productId,
                                'data-max-quantity': true // Đánh dấu checkbox đã vượt quá số lượng
                            });
                            $tdCheckbox.append(hiddenCheckbox);

                            $warning.text('Tối đa 30 sản phẩm!').show();
                            removeFromCartDetails(cartId);
                        } else {
                            // Xóa checkbox cũ nếu tồn tại
                            const $existingCheckbox = $tdCheckbox.find('.cart-item-checkbox');
                            if ($existingCheckbox.length && $existingCheckbox.data(
                                'max-quantity')) {
                                $existingCheckbox.remove();
                            }

                            // Thêm checkbox mới nếu chưa có
                            if ($tdCheckbox.find('.cart-item-checkbox').length === 0) {
                                const newCheckbox = $('<input>', {
                                    type: 'checkbox',
                                    class: 'cart-item-checkbox',
                                    'data-cart-id': cartId,
                                    'data-product-id': productId
                                });
                                $tdCheckbox.append(newCheckbox);
                            }

                            if (quantity === MAX_QUANTITY) {
                                $warning.text('Tối đa 30 sản phẩm!').show();
                            } else {
                                $warning.hide();
                            }
                        }
                    });

                    updateCartTotal();
                    validateCheckboxStates();
                }

                // Hàm kiểm tra và xác thực trạng thái checkbox
                function validateCheckboxStates() {
                    $('.cart-item-checkbox').each(function() {
                        const $checkbox = $(this);
                        const $row = $checkbox.closest('tr');
                        const quantity = parseInt($row.find('.cart-plus-minus-box').val());

                        // Nếu số lượng vượt quá và checkbox vẫn được chọn
                        if (quantity > MAX_QUANTITY && $checkbox.is(':checked')) {
                            $checkbox.prop('checked', false);
                            removeFromCartDetails($row.data('cart-id'));
                        }
                    });
                }

                // Chặn sự kiện click trên checkbox đã bị vô hiệu hóa
                $(document).on('click', '.cart-item-checkbox[data-max-quantity]', function(e) {
                    e.preventDefault();
                    return false;
                });

                // Kiểm tra định kỳ trạng thái checkbox
                setInterval(validateCheckboxStates, 1000);


                // Gọi hàm kiểm tra khi trang được tải
                checkCartQuantities();

                // Gọi lại hàm kiểm tra khi số lượng thay đổi
                $(document).on('change', '.cart-plus-minus-box', checkCartQuantities);

                // Gọi lại hàm kiểm tra khi nhấn nút tăng/giảm số lượng
                $(document).on('click', '.qtybutton', checkCartQuantities);
            });


            $('#apply-coupon').on('click', function() {
                const couponCode = $('#coupon-code').val().trim();
                if (!couponCode) {
                    showToast("Vui lòng nhập mã giảm giá!", "error");
                    return;
                }

                const selectedCartIds = $('.cart-item-checkbox:checked').map(function() {
                    return $(this).data('cart-id');
                }).get();

                if (selectedCartIds.length === 0) {
                    showToast("Vui lòng chọn ít nhất một sản phẩm!", "error");
                    return;
                }

                $.ajax({
                    url: "{{ route('cart.apply-coupon') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        'cart-coupon': couponCode,
                        selected_cart_ids: selectedCartIds
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#discount-row').remove();
                            $('#applied-coupon-text').remove();

                            $('#cart-grand-total').text(response.new_subtotal + 'đ');
                            const discountRow =
                                `<tr id="discount-row"><td>Giảm giá (${couponCode})</td><td>-${response.discount}đ</td></tr>`;
                            $('#cart-details').after(discountRow);
                            $('#coupon-code').after(
                                `<small id="applied-coupon-text">Đã áp dụng: ${couponCode} (Giảm ${response.discount}đ)</small>`
                            );

                            $('#coupon-code, #apply-coupon').prop('disabled', true);
                            showToast(response.message, "success");
                        } else {
                            showToast(response.message, "error");
                        }
                    },
                    error: function() {
                        showToast("Có lỗi xảy ra khi áp dụng mã giảm giá!", "error");
                    }
                });
            });


            $(document).on('click', 'tbody .cart-product-remove', function() {
                let $row = $(this).closest('tr');
                let cartId = $row.data('cart-id');

                $.ajax({
                    url: "{{ route('cart.remove') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        cart_id: cartId
                    },
                    success: function(response) {
                        if (response.status === "success") {
                            $row.remove();
                            removeFromCartDetails(cartId);

                            if (initialAppliedCoupon && initialAppliedCoupon.cart_id ==
                                cartId) {
                                $('#discount-row').remove();
                                $('#applied-coupon-text').remove();
                                $('#coupon-code, #apply-coupon').prop('disabled', false);
                                $.ajax({
                                    url: "{{ route('cart.apply-coupon') }}",
                                    type: 'POST',
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        'cart-coupon': '',
                                        selected_cart_ids: []
                                    }
                                });
                            }

                            updateCartTotal();
                            $('sup').text(response.cart_count);
                            if ($('.cart-item-checkbox').length === $(
                                    '.cart-item-checkbox:checked').length) {
                                $('#select-all').prop('checked', true);
                            } else {
                                $('#select-all').prop('checked', false);
                            }
                            showToast(response.message, "success");
                        } else {
                            showToast(response.message, "error");
                        }
                    }
                });
            });


            $('#show-coupons').on('click', function() {
                const selectedProductIds = $('.cart-item-checkbox:checked').map(function() {
                    return $(this).data('product-id');
                }).get();

                console.log('✅ Sản phẩm được chọn:', selectedProductIds);

                let couponHtml = '';

                coupons.forEach(coupon => {
                    console.log(`👉 Đang kiểm tra mã: ${coupon.code}`);

                    const restriction = coupon.restriction || {};
                    let validProducts = [];

                    if (restriction.valid_products) {
                        try {
                            validProducts = Array.isArray(restriction.valid_products) ?
                                restriction.valid_products :
                                JSON.parse(restriction.valid_products);

                            console.log(`✅ Sản phẩm hợp lệ cho mã ${coupon.code}:`, validProducts);
                        } catch (e) {
                            console.error(
                                `❌ Lỗi khi parse valid_products của mã ${coupon.code}:`,
                                restriction.valid_products,
                                e
                            );
                            validProducts = [];
                        }
                    } else {
                        console.warn(`⚠️ Mã ${coupon.code} không có giới hạn sản phẩm.`);
                    }

                    // const isApplicable =
                    //     selectedProductIds.length > 0 &&
                    //     validProducts.some(id => selectedProductIds.includes(id));

                    // đoạn code trên đang so sánh 1 id với 1 chuỗi 1 = '1' đổi thành 1 = 1


                    const isApplicable = selectedProductIds.length > 0 &&
                        validProducts.some(id => selectedProductIds.includes(parseInt(id)));

                    console.log(
                        `➡️ Mã ${coupon.code} ${isApplicable ? 'áp dụng được' : 'không áp dụng được'}`
                    );

                    if (isApplicable) {
                        couponHtml += `
                                <div class="coupon-item valid">
                        <button class="copy-coupon-btn" data-coupon-code="${coupon.code}">
                            📋 Sao chép
                        </button>
                        Mã giảm giá: <strong>${coupon.code}</strong><br>
                        <small>Giảm: ${coupon.discount_type === 'phan_tram' ? coupon.discount_value + '%' : new Intl.NumberFormat('vi-VN').format(coupon.discount_value) + 'đ'}</small>
                    </div>

            `;
                    }
                });
                $(document).on('click', '.copy-coupon-btn', function() {
                    const code = $(this).data('coupon-code');

                    // Tạo một input ẩn để copy
                    const $tempInput = $('<input>');
                    $('body').append($tempInput);
                    $tempInput.val(code).select();
                    document.execCommand('copy');
                    $tempInput.remove();

                    alert(`Đã sao chép mã giảm giá: ${code}`);
                });
                if (couponHtml === '') {
                    couponHtml = '<p>Không có mã giảm giá nào khả dụng cho sản phẩm đã chọn.</p>';
                    console.warn('⚠️ Không có mã giảm giá nào hợp lệ được áp dụng.');
                }

                $('#coupon-list').html(couponHtml);
                $('#coupon-overlay').fadeIn(300);
                console.log('🎉 Hiển thị overlay mã giảm giá');
            });

            // Đóng overlay
            $('#close-coupons').on('click', function() {
                $('#coupon-overlay').fadeOut(300);
                console.log('🔒 Đóng overlay mã giảm giá');
            });

            $(document).on('click', function(e) {
                if ($(e.target).is('#coupon-overlay')) {
                    $('#coupon-overlay').fadeOut(300);
                    console.log('🔒 Overlay bị đóng khi click ra ngoài');
                }
            });

            // Kiểm tra mã đã áp dụng ban đầu
            if (!initialAppliedCoupon) {
                $('#discount-row').remove();
                $('#applied-coupon-text').remove();
                $('#coupon-code, #apply-coupon').prop('disabled', false);
                console.log('📌 Không có mã giảm giá nào được áp dụng ban đầu.');
            }
        });


        function addToCartDetails($row) {
            let productName = $row.find('.cart-product-info h4 a').text().trim();
            let price = parseFloat($row.find('.cart-product-price').text().replace(/[,.đ]/g, ''));
            let quantity = parseInt($row.find('.cart-plus-minus-box').val());
            let subtotal = price * quantity;
            let cartId = $row.data('cart-id');

            let html = `
            <tr data-cart-id="${cartId}">
                <td><input type="checkbox" class="cart-details-checkbox" checked> ${productName}: ${new Intl.NumberFormat('vi-VN').format(price)}đ x ${quantity}</td>
                <td>${new Intl.NumberFormat('vi-VN').format(subtotal)}đ</td>
            </tr>
        `;
            $('#cart-details').append(html);
        }


        function addToCartDetails($row) {
            let productName = $row.find('.cart-product-info h4 a').text().trim();
            let price = parseFloat($row.find('.cart-product-price').text().replace(/[,.đ]/g, ''));
            let quantity = parseInt($row.find('.cart-plus-minus-box').val());
            let subtotal = price * quantity;
            let cartId = $row.data('cart-id');

            let html = `
                <tr data-cart-id="${cartId}">
                    <td><input type="checkbox" class="cart-details-checkbox" checked> ${productName}: ${new Intl.NumberFormat('vi-VN').format(price)}đ x ${quantity}</td>
                    <td>${new Intl.NumberFormat('vi-VN').format(subtotal)}đ</td>
                </tr>
            `;
            $('#cart-details').append(html);
        }


        function updateCartDetails($row) {
            let productName = $row.find('.cart-product-info h4 a').text().trim();
            let price = parseFloat($row.find('.cart-product-price').text().replace(/[,.đ]/g, ''));
            let quantity = parseInt($row.find('.cart-plus-minus-box').val());
            let subtotal = price * quantity;
            let cartId = $row.data('cart-id');

            $(`#cart-details tr[data-cart-id="${cartId}"]`).html(`
        <td><input type="checkbox" class="cart-details-checkbox" checked> ${productName}: ${new Intl.NumberFormat('vi-VN').format(price)}đ x ${quantity}</td>
        <td>${new Intl.NumberFormat('vi-VN').format(subtotal)}đ</td>
    `);
        }


        function removeFromCartDetails(cartId) {
            $(`#cart-details tr[data-cart-id="${cartId}"]`).remove();
        }


        function updateCartTotal() {
            let total = 0;
            let total2 = 0;
            $("#cart-details tr").each(function() {
                let subtotal = parseFloat($(this).find('td:last-child').text().replace(/[^0-9]/g, ''));
                total += subtotal;
            });

            $(".cart-product-subtotal").each(function() {
                let value = $(this).text().replace(/[^0-9]/g, '');
                let price = value ? parseFloat(value) : 0;
                total2 += price;
            });

            total = isNaN(total) ? 0 : total;
            total2 = isNaN(total2) ? 0 : total2;

            const discount = $('#discount-row').length ? parseFloat($('#discount-row td:last-child').text().replace(
                /[^0-9]/g, '')) : 0;
            total -= discount;

            $("#cart-grand-total").text(total.toLocaleString('vi-VN') + "đ");
            $("#cart-subtotal").text(total2.toLocaleString('vi-VN') + "đ");
            $(".mini-cart-sub-total span").text(total2.toLocaleString('vi-VN') + "đ");
            if (total > 0) {
                $('.theme-btn-1').removeClass('disabled').prop('disabled', false);
            } else {
                $('.theme-btn-1').addClass('disabled').prop('disabled', true);
            }
        }



        function removeAppliedCoupon() {
            if ($('#discount-row').length) {
                $('#discount-row').remove();
                $('#applied-coupon-text').remove();
                $('#coupon-code, #apply-coupon').prop('disabled', false);
                $.ajax({
                    url: "{{ route('cart.apply-coupon') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        'cart-coupon': '',
                        selected_cart_ids: []
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            showToast("Mã giảm giá đã được xóa.", "success");
                        }
                    }
                });
            }
            updateCartTotal();
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


        $('#checkout-form').on('submit', function(e) {
            e.preventDefault();


            let selectedProducts = [];
            $('.cart-item-checkbox:checked').each(function() {
                let $row = $(this).closest('tr');
                let product = {
                    id: $row.data('product-id'),
                    name: $row.find('.cart-product-info h4 a').text().trim(),
                    thumbnail: $row.find('.cart-product-image img').attr('src'),
                    quantity: parseInt($row.find('.cart-plus-minus-box').val()),
                    price: parseFloat($row.find('.cart-product-price').text().replace(/[,.đ]/g, '')),

                    id_variant: $row.find('.cart-product-attributes').text().trim(),
                    name_variant: $row.find('.cart-product-attributes-name').text().trim(),
                    variant_value: $row.find('.cart-product-attributes-value').text().trim(),
                };
                selectedProducts.push(product);
            });


            if (selectedProducts.length === 0) {
                showToast("Vui lòng chọn ít nhất một sản phẩm để thanh toán!", "error");
                return;
            }


            let couponCode = $('#applied-coupon-text').length ? $('#applied-coupon-text').text().split(' ')[3] : '';
            let discount = $('#discount-row').length ? parseFloat($('#discount-row td:last-child').text().replace(
                /[^0-9]/g, '')) : 0;
            let grandTotal = parseFloat($('#cart-grand-total').text().replace(/[^0-9]/g, ''));


            $('#selected-products').val(JSON.stringify(selectedProducts));
            $('#coupon-code-hidden').val(couponCode);
            $('#discount-hidden').val(discount);
            $('#grand-total-hidden').val(grandTotal);


            this.submit();
        });
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

        .coupon-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .coupon-content {
            background: white;
            padding: 20px;
            border-radius: 5px;
            max-width: 500px;
            width: 100%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .coupon-item {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .coupon-item.valid {
            color: black;
        }

        .coupon-item.invalid {
            color: #888;
        }

        .shoping-cart-table {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin: 20px 0;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            color: #495057;
            font-weight: 600;
            padding: 15px;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
            border-top: 1px solid #eee;
        }

        .cart-product-image img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .cart-product-image img:hover {
            transform: scale(1.1);
        }

        .cart-product-info h4 {
            margin: 0;
        }

        .cart-product-info a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .cart-product-info a:hover {
            color: #0d6efd;
        }

        .cart-product-price {
            font-weight: 600;
            color: #2c3e50;
        }

        .cart-plus-minus {
            border: 1px solid #dee2e6;
            border-radius: 25px;
            display: inline-flex;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .cart-plus-minus-box {
            width: 60px;
            border: none;
            text-align: center;
            font-weight: 600;
            padding: 8px;
            background: transparent;
        }

        .cart-product-remove {
            cursor: pointer;
            color: #dc3545;
            transition: all 0.3s ease;
        }

        .cart-product-remove:hover {
            color: #c82333;
            transform: scale(1.2);
        }

        .cart-item-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            position: relative;
            border: 2px solid #0d6efd;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .cart-item-checkbox:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .cart-coupon-row {
            background-color: #f8f9fa;
        }

        .cart-coupon {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
        }

        .cart-summary-row {
            background-color: #f8f9fa;
            border-top: 2px solid #dee2e6;
        }

        .cart-summary-row td {
            padding: 20px !important;
            font-size: 1.2rem;
        }

        .cart-summary-text {
            color: #2c3e50;
            font-weight: 600;
            text-transform: uppercase;
            text-align: left !important;
        }

        .cart-summary-amount {
            color: #0d6efd;
            font-weight: 700;
        }

        /* Add animation for price updates */
        .cart-summary-amount.updating {
            animation: priceUpdate 0.3s ease-in-out;
        }

        @keyframes priceUpdate {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        .cart-coupon input {
            padding: 8px 15px;
            border: 1px solid #dee2e6;
            border-radius: 25px;
            flex-grow: 1;
            transition: all 0.3s ease;
        }

        .cart-summary-amount {
            transition: all 0.3s ease;
        }

        .cart-summary-amount.updating {
            animation: priceUpdate 0.3s ease-in-out;
        }

        @keyframes priceUpdate {
            0% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(1.05);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .cart-coupon input:focus {
            outline: none;
            border-color: #0d6efd;
            box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
        }

        .cart-product-subtotal {
            font-weight: 600;
            color: #2c3e50;
            transition: all 0.3s ease;
        }

        .updating .cart-product-subtotal {
            opacity: 0.5;
        }


        .theme-btn-2 {
            padding: 8px 20px;
            border-radius: 25px;
            border: none;
            background: #0d6efd;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .theme-btn-2:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }


        .updating {
            opacity: 0.7;
            pointer-events: none;
            position: relative;
        }

        .updating::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7) url('data:image/svg+xml,...') center no-repeat;
            background-size: 30px;
        }

        /* Empty Cart State */
        .empty-cart {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-cart i {
            font-size: 48px;
            color: #dee2e6;
            margin-bottom: 15px;
        }


        @media (max-width: 768px) {
            .cart-product-image img {
                width: 60px;
                height: 60px;
            }

            .table td {
                padding: 10px;
            }

            .cart-coupon {
                flex-direction: column;
            }
        }

        .shopping-cart-main-table thead th {
            text-align: center;
            vertical-align: middle;
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            color: #495057;
            font-weight: 600;
            padding: 15px;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        /* Căn chỉnh từng cột */
        .shopping-cart-main-table th.cart-product-remove,
        .shopping-cart-main-table td.cart-product-remove {
            text-align: center;
            width: 50px !important;
        }

        .shopping-cart-main-table .cart-product-image,
        .shopping-cart-main-table td.cart-product-image {
            text-align: center;
            width: 100px;
        }

        .shopping-cart-main-table th.cart-product-info,
        .shopping-cart-main-table td.cart-product-info {
            text-align: left !important;
        }

        .shopping-cart-main-table th.cart-product-price,
        .shopping-cart-main-table td.cart-product-price {
            text-align: right !important;
            width: 150px;
        }

        .shopping-cart-main-table .cart-product-quantity,
        .shopping-cart-main-table td.cart-product-quantity {
            text-align: center;
            width: 120px;
        }

        .shopping-cart-main-table .cart-product-subtotal,
        .shopping-cart-main-table td.cart-product-subtotal {
            text-align: right !important;
            width: 150px;
        }

        /* Căn chỉnh checkbox */
        .shopping-cart-main-table th:first-child,
        .shopping-cart-main-table td:first-child {
            text-align: center;
            width: 40px;
        }

        /* Căn chỉnh input số lượng */
        .shopping-cart-main-table .cart-plus-minus {
            margin: 0 auto;
        }

        /* Căn chỉnh giá tiền */
        .shopping-cart-main-table .cart-product-price,
        .shopping-cart-main-table .cart-product-subtotal {
            white-space: nowrap;
        }

        /* Căn chỉnh tổng tiền */
        .shopping-cart-main-table .cart-summary-row td:first-child {
            text-align: left;
        }

        .shopping-cart-main-table .cart-summary-row td:last-child {
            text-align: left;
            font-weight: bold;
        }

        /* Căn chỉnh tổng tiền */
        .shopping-cart-main-table .cart-summary-row td:first-child {
            text-align: left;
        }

        .shopping-cart-main-table .cart-summary-row td:last-child {
            text-align: right;
            font-weight: bold;
        }

        .shopping-cart-main-table td {
            vertical-align: middle !important;
            text-align: center;
        }

        .shopping-cart-main-table td.cart-product-info {
            text-align: left;
            /* Giữ text-align left cho cột thông tin sản phẩm */
        }

        .shopping-cart-main-table td.cart-product-price,
        .shopping-cart-main-table td.cart-product-subtotal {
            text-align: rt;
            /* Giữ text-align right cho cột giá */
        }

        .shopping-cart-main-table td h4 {
            margin-bottom: 5px;
        }

        .shopping-cart-main-table td p {
            margin-bottom: 0;
        }

        .cart-product-info a {
            display: block;
            text-align: left;
        }

        .shopping-cart-main-table thead th {
            padding: 15px;
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            text-align: center !important;
            vertical-align: middle !important;
        }

        .shopping-cart-main-table .cart-product-subtotal {
            width: 150px;
            text-align: right;
        }

        .shopping-cart-main-table .cart-product-quantity {
            width: 170px;
            text-align: center;
        }

        /* Style cho header cột xóa */
        .cart-product-remove-header {
            text-align: center;
            width: 50px;
        }

        /* Style cho cell chứa icon xóa */
        tbody .cart-product-remove {
            text-align: center;
            cursor: pointer;
            color: #dc3545;
        }

        tbody .cart-product-remove:hover {
            color: #c82333;
        }

        tbody .cart-product-remove i {
            transition: all 0.3s ease;
        }

        tbody .cart-product-remove i:hover {
            transform: scale(1.2);
        }

        .quantity-warning {
            display: block;
            margin-top: 5px;
            font-size: 0.875rem;
            color: #dc3545;
        }

        .cart-plus-minus {
            height: 60px;
            /* Giảm chiều cao */
        }
    </style>
@endpush
