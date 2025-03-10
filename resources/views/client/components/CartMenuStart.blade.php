<div id="ltn__utilize-cart-menu" class="ltn__utilize ltn__utilize-cart-menu">
    <div class="ltn__utilize-menu-inner ltn__scrollbar">
        <div class="ltn__utilize-menu-head">
            <span class="ltn__utilize-menu-title">Cart</span>
            <button class="ltn__utilize-close">×</button>
        </div>
        <div class="mini-cart-list">
            @foreach ($carts as $cart)
                <div class="mini-cart-item clearfix">
                    <div class="mini-cart-img">
                        <a href="#"><img src="{{ asset('upload/' . $cart->product->thumbnail) }}"
                                alt="{{ $cart->product->name }}"></a>
                    </div>
                    <div class="mini-cart-info">
                        <h6><a href="#">{{ $cart->product->name }}</a></h6>
                        <span class="mini-cart-quantity">
                            {{ $cart->quantity }} x
                            <span
                                class="mini-cart-price">{{ number_format($cart->productVariant->sale_price, 2) }}đ</span>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mini-cart-footer">
            <div class="mini-cart-sub-total">
                <h5>Subtotal: <span>{{ number_format($subtotal, 2) }}đ</span></h5>
            </div>
            <div class="btn-wrapper">
                <a href="{{ route('get-cart') }}" class="theme-btn-1 btn btn-effect-1">View Cart</a>
                <a href="cart.html" class="theme-btn-2 btn btn-effect-2">Checkout</a>
            </div>
            <p>Free Shipping on All Orders Over $100!</p>
        </div>

    </div>
</div>
