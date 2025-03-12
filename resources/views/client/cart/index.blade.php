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
                                <table class="table" id="cart-table">
                                    <thead>
                                        <th><input type="checkbox" id="select-all"> Toàn bộ</th>
                                        <th class="cart-product-remove">Xóa khỏi giỏ hàng</th>
                                        <th class="cart-product-image">Ảnh sản phẩm</th>
                                        <th class="cart-product-info">Tên sản phẩm</th>
                                        <th class="cart-product-price">Giá</th>
                                        <th class="cart-product-quantity">Số lượng</th>
                                        <th class="cart-product-subtotal">Tổng tiền</th>
                                    </thead>
                                    <tbody id="cart-items">
                                        @foreach ($carts as $cart)
                                            <tr data-cart-id="{{ $cart->id }}" data-product-id="{{ $cart->product->id }}">
                                                <td><input type="checkbox" class="cart-item-checkbox" data-cart-id="{{ $cart->id }}" data-product-id="{{ $cart->product->id }}"></td>
                                                <td class="cart-product-remove">x</td>
                                                <td class="cart-product-image">
                                                    <a href="product-details.html"><img src="{{ asset('upload/' . $cart->product->thumbnail) }}" alt="#"></a>
                                                </td>
                                                <td class="cart-product-info">
                                                    <h4><a href="product-details.html">{{ \Illuminate\Support\Str::limit($cart->product->name, 10, '...') }}</a></h4>
                                                </td>
                                                <td class="cart-product-price">{{ number_format($cart->productVariant->sale_price) }}</td>
                                                <td class="cart-product-quantity">
                                                    <div class="cart-plus-minus">
                                                        <input type="text" value="{{ $cart->quantity }}" class="cart-plus-minus-box" min="1" readonly>
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
                                                <input type="text" name="cart-coupon" placeholder="Coupon code" id="coupon-code" {{ $appliedCoupon ? 'disabled' : '' }}>
                                                <button type="button" class="btn theme-btn-2 btn-effect-2" id="apply-coupon" {{ $appliedCoupon ? 'disabled' : '' }}>Sử dụng mã giảm giá</button>
                                                @if ($appliedCoupon)
                                                    <small id="applied-coupon-text">Đã áp dụng: {{ $appliedCoupon['code'] }} (Giảm {{ number_format($appliedCoupon['discount']) }}đ)</small>
                                                @endif
                                            </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn theme-btn-2 btn-effect-2" id="show-coupons">Lấy mã giảm giá</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="shoping-cart-total mt-50">
                                <h4>Thông tin Sikibidi</h4>
                                <table class="table">
                                    <tbody id="cart-details">
                                        <!-- Các sản phẩm được chọn sẽ hiển thị ở đây -->
                                    </tbody>
                                    <tbody id="totals-section">
                                        @if ($appliedCoupon)
                                            <tr id="discount-row">
                                                <td>Giảm giá ({{ $appliedCoupon['code'] }})</td>
                                                <td>-{{ number_format($appliedCoupon['discount']) }}đ</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td><strong>Số tiền bốc hơi của bạn</strong></td>
                                            <td><strong id="cart-grand-total">{{ number_format($subtotal - ($appliedCoupon['discount'] ?? 0)) }}đ</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="btn-wrapper text-right">
                                    <a href="checkout.html" class="theme-btn-1 btn btn-effect-1">Thanh toán</a>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SHOPING CART AREA END -->
    <div id="coupon-overlay" class="coupon-overlay" style="display: none;">
        <div class="coupon-content">
            <h3>Danh sách mã giảm giá</h3>
            <div id="coupon-list">
                <!-- Danh sách mã giảm giá sẽ được thêm bằng JS -->
            </div>
            <button class="btn theme-btn-2 btn-effect-2" id="close-coupons">Đóng</button>
        </div>
    </div>
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
    // Vô hiệu hóa mini cart
    if ($("#cart-page").length) {
        $(".mini-cart-icon-2 a.ltn__utilize-toggle").off("click");
        $(".mini-cart-icon-2 a.ltn__utilize-toggle").css("pointer-events", "none");
    }

    let updateTimer;
    const coupons = @json($coupons);
    const initialAppliedCoupon = @json(session('applied_coupon'));

    // Xử lý checkbox "chọn tất cả" restriction
    $('#select-all').on('change', function() {
        $('.cart-item-checkbox').prop('checked', this.checked).trigger('change');
    });

    // Xử lý checkbox trong bảng trên cùng
    $(document).on('change', '.cart-item-checkbox', function() {
        let $row = $(this).closest('tr');
        let isChecked = $(this).is(':checked');
        let cartId = $row.data('cart-id');

        if (isChecked) {
            $row.hide();
            addToCartDetails($row);
        } else {
            $row.show();
            removeFromCartDetails(cartId);
            removeAppliedCoupon(); // Xóa mã giảm giá khi bỏ chọn
        }

        if ($('.cart-item-checkbox:checked').length === $('.cart-item-checkbox').length) {
            $('#select-all').prop('checked', true);
        } else {
            $('#select-all').prop('checked', false);
        }

        updateCartTotal();
    });

   // Xử lý checkbox trong #cart-details
   $(document).on('change', '.cart-details-checkbox', function() {
        let $detailsRow = $(this).closest('tr');
        let cartId = $detailsRow.data('cart-id');
        let $cartRow = $(`#cart-items tr[data-cart-id="${cartId}"]`);

        if (!$(this).is(':checked')) {
            $cartRow.show();
            $cartRow.find('.cart-item-checkbox').prop('checked', false);
            removeFromCartDetails(cartId);
            removeAppliedCoupon(); // Xóa mã giảm giá khi bỏ chọn
            updateCartTotal();
        }
    });

    // Xử lý tăng/giảm số lượng
    $('.qtybutton').off('click');
    $(document).on('click', '.qtybutton', function() {
        let $button = $(this);
        let $input = $button.siblings('input.cart-plus-minus-box');
        let oldValue = parseInt($input.val());
        let newVal = $button.hasClass('inc') ? oldValue + 1 : (oldValue > 1 ? oldValue - 1 : 1);

        $input.val(newVal);
        clearTimeout(updateTimer);
        updateTimer = setTimeout(function() {
            $input.trigger('change');
        }, 500);
    });

    // Xử lý thay đổi số lượng
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
                    const price = parseFloat($row.find('.cart-product-price').text().replace(/[,.đ]/g, ''));
                    const newSubtotal = price * quantity;
                    $row.find('.cart-product-subtotal').text(new Intl.NumberFormat('vi-VN').format(newSubtotal) + 'đ');
                    if ($row.find('.cart-item-checkbox').is(':checked')) {
                        updateCartDetails($row);
                    }
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
                    updateCartTotal();
                    if ($('.cart-item-checkbox').length === $('.cart-item-checkbox:checked').length) {
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

     // Xử lý áp dụng mã giảm giá
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
                    const discountRow = `<tr id="discount-row"><td>Giảm giá (${couponCode})</td><td>-${response.discount}đ</td></tr>`;
                    $('#cart-details').after(discountRow);
                    $('#coupon-code').after(`<small id="applied-coupon-text">Đã áp dụng: ${couponCode} (Giảm ${response.discount}đ)</small>`);

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

     // Xử lý xóa sản phẩm show-coupons
     $(document).on('click', '.cart-product-remove', function() {
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

                    // Nếu sản phẩm bị xóa là sản phẩm áp dụng mã, xóa mã giảm giá
                    if (initialAppliedCoupon && initialAppliedCoupon.cart_id == cartId) {
                        $('#discount-row').remove();
                        $('#applied-coupon-text').remove();
                        $('#coupon-code, #apply-coupon').prop('disabled', false);
                        $.ajax({
                            url: "{{ route('cart.apply-coupon') }}",
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                'cart-coupon': '', // Gửi rỗng để xóa mã
                                selected_cart_ids: []
                            }
                        });
                    }

                    updateCartTotal();
                    if ($('.cart-item-checkbox').length === $('.cart-item-checkbox:checked').length) {
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

    // Hiển thị mã giảm giá cart-coupon addToCartDetails
    $('#show-coupons').on('click', function() {
        const selectedProductIds = $('.cart-item-checkbox:checked').map(function() {
            return $(this).data('product-id');
        }).get();

        let couponHtml = '';
        coupons.forEach(coupon => {
            const restriction = coupon.restriction || {}; // Sửa từ restriction thành restriction
            let validProducts = [];
            
            if (restriction.valid_products) {
                try {
                    validProducts = Array.isArray(restriction.valid_products) 
                        ? restriction.valid_products 
                        : JSON.parse(restriction.valid_products);
                } catch (e) {
                    console.error(`Invalid JSON in valid_products for coupon ${coupon.code}:`, restriction.valid_products);
                    validProducts = [];
                }
            }

            const isApplicable = selectedProductIds.length > 0 && validProducts.some(id => 
                selectedProductIds.includes(id)
            );

            if (isApplicable) {
                couponHtml += `
                    <div class="coupon-item valid">
                        <strong>code ${coupon.code}</strong><br>
                        <small>${coupon.description}</small><br>
                        <small>Giảm: ${coupon.discount_type === 'phan_tram' ? coupon.discount_value + '%' : new Intl.NumberFormat('vi-VN').format(coupon.discount_value) + 'đ'}</small>
                    </div>
                `;
            }
        });

        if (couponHtml === '') {
            couponHtml = '<p>Không có mã giảm giá nào khả dụng cho sản phẩm đã chọn.</p>';
        }

        $('#coupon-list').html(couponHtml);
        $('#coupon-overlay').fadeIn(300);
    });

    $('#close-coupons').on('click', function() {
        $('#coupon-overlay').fadeOut(300);
    });

    $(document).on('click', function(e) {
        if ($(e.target).is('#coupon-overlay')) {
            $('#coupon-overlay').fadeOut(300);
        }
    });

    // Khởi tạo ban đầu
    updateCartTotal();

    // Kiểm tra mã giảm giá khi load trang
    if (!initialAppliedCoupon) {
        $('#discount-row').remove();
        $('#applied-coupon-text').remove();
        $('#coupon-code, #apply-coupon').prop('disabled', false);
    }
});

// Hàm thêm sản phẩm vào #cart-details
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

// Hàm cập nhật Cart Totals removeFromCartDetails
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

// Hàm cập nhật Cart Totals
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

// Hàm xóa sản phẩm khỏi #cart-details
function removeFromCartDetails(cartId) {
        $(`#cart-details tr[data-cart-id="${cartId}"]`).remove();
    }

// Hàm cập nhật tổng tiền removeAppliedCoupon
function updateCartTotal() {
        let total = 0;
        $("#cart-details tr").each(function() {
            let subtotal = parseFloat($(this).find('td:last-child').text().replace(/[^0-9]/g, ''));
            total += subtotal;
        });

        total = isNaN(total) ? 0 : total;

        const discount = $('#discount-row').length ? parseFloat($('#discount-row td:last-child').text().replace(/[^0-9]/g, '')) : 0;
        total -= discount;

        $("#cart-grand-total").text(total.toLocaleString('vi-VN') + "đ");

        if (total > 0) {
            $('.theme-btn-1').removeClass('disabled').prop('disabled', false);
        } else {
            $('.theme-btn-1').addClass('disabled').prop('disabled', true);
        }
    }

    
    // Hàm xóa mã giảm giá
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
                    'cart-coupon': '', // Gửi rỗng để xóa mã
                    selected_cart_ids: []
                },
                success: function(response) {
                    if (response.status === 'success') {
                        showToast("Mã giảm giá đã được xóa.", "success");
                    }
                }
            });
        }
        updateCartTotal(); // Cập nhật lại tổng tiền sau khi xóa mã
    }

// Hàm hiển thị toast
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

        .cart-product-subtotal {
    </style>
@endpush
