<div id="ltn__utilize-cart-menu" class="ltn__utilize ltn__utilize-cart-menu">
    <div class="ltn__utilize-menu-inner ltn__scrollbar">
        <div class="ltn__utilize-menu-head">
            <span class="ltn__utilize-menu-title">Giỏ Hàng</span>
            <button class="ltn__utilize-close">×</button>
        </div>
        <div class="mini-cart-list">
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

                <div class="mini-cart-item clearfix">
                    <div class="mini-cart-img">
                        <a href="#"><img src="{{ asset('upload/' . $cart->product->thumbnail) }}"
                                alt="{{ $cart->product->name }}"></a>
                    </div>
                    <div class="mini-cart-info">
                        <h6><a href="#">{{ $cart->product->name }}</a></h6>
                        <p class="text-sm text-gray-500">{{ $variantName }}</p>
                        <span class="mini-cart-quantity">
                            {{ $cart->quantity }} x
                            <span class="mini-cart-price">
                                @php
                                    $price = $cart->productVariant->sale_price > 0 ? 
                                        $cart->productVariant->sale_price : 
                                        $cart->productVariant->price;
                                @endphp
                                {{ number_format($price) }}đ
                            </span>
                        </span>
                    </div>
                </div>
            @endforeach

        </div>
        <div class="mini-cart-footer">
            <div class="mini-cart-sub-total">
                <h5>Tổng Tiền: <span>{{ number_format($subtotal, 2) }}đ</span></h5>
            </div>
            <div class="btn-wrapper">
                <a href="{{ route('get-cart') }}" class="theme-btn-1 btn btn-effect-1" style="margin-left: 80px;">Xem
                    Giỏ Hàng</a>
                <!-- <a href="cart.html" class="theme-btn-2 btn btn-effect-2">Checkout</a> -->
            </div>
            <p style="margin-left: 73px;">Miễn Phí Vận Chuyển Nội Thành !</p>
        </div>

    </div>
</div>
