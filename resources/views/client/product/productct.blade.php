@extends('client.layouts.layout')
@section('content')
    <!-- Utilize Cart Menu Start -->
    @include('client.components.CartMenuStart')
    <!-- Utilize Cart Menu End -->

    <!-- Utilize Mobile Menu Start -->
    @include('client.components.MobileMenuStart')
    <!-- Utilize Mobile Menu End -->

    <div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .ltn__product-details-menu-3 h5 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .product-details-list {
            list-style: none;
            padding: 0;
        }

        .product-details-list li {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
            line-height: 1.5;
        }

        .product-details-list li strong {
            font-weight: 700;
            color: #333;
            width: 40%;
        }

        .product-details-list li span {
            width: 60%;
            text-align: left;
            color: #555;
        }

        .ltn__shop-details-inner {
            margin-bottom: 60px;
        }

        .ltn__shop-details-img-gallery {}

        .ltn__shop-details-large-img .single-large-img img {
            width: 100%;
            border-radius: 8px;
        }

        .ltn__shop-details-small-img .single-small-img img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            margin: 5px;
        }

        .modal-product-info h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .product-ratting ul {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .product-ratting ul li {
            margin-right: 5px;
        }

        .variant-buttons button {
            margin: 5px;
        }

        .fade-out {
            animation: fadeOut 0.3s ease forwards;
        }

        .fade-in {
            animation: fadeIn 0.3s ease forwards;
        }

        @keyframes fadeOut {
            0% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .product-price span,
        .product-price del {
            display: inline-block;
        }

        .variant-btn {
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .variant-btn.zoomed {
            animation: zoomIn 0.3s ease forwards;
            background-color: #e3f2fd;
        }

        @keyframes zoomIn {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1.05);
            }
        }
    </style>
    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image" data-bs-bg="img/bg/14.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">Product Details</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="index.html"><span class="ltn__secondary-color"><i
                                                class="fas fa-home"></i></span> Home</a></li>
                                <li>Product Details</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <!-- SHOP DETAILS AREA START -->
    <div class="ltn__shop-details-area pb-85">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-12">

                <div class="ltn__shop-details-inner mb-60">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="ltn__shop-details-img-gallery">
                                <div class="ltn__shop-details-large-img">
                                    <div class="single-large-img">
                                        <a href="{{ asset('upload/'.$product->thumbnail) }}" data-rel="lightcase:myCollection">
                                            <img src="{{ asset('upload/'.$product->thumbnail) }}" alt="Image">
                                        </a>
                                    </div>
                                    @foreach($productGallery as $productGallery)
                                    <div class="single-large-img">
                                        <a href="{{ asset('upload/'.$productGallery->image) }}" data-rel="lightcase:myCollection">
                                            <img src="{{ asset('upload/'.$productGallery->image) }}" alt="Image">
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="ltn__shop-details-small-img slick-arrow-2">
                                    <div class="single-small-img">
                                        <img src="{{ asset('upload/'.$product->thumbnail) }}" alt="Image">
                                    </div>
                                    @foreach($productGallery2 as $gallery)
                                    <div class="single-small-img">
                                        <img src="{{ asset('upload/'.$gallery->image) }}" alt="Image">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="modal-product-info shop-details-info pl-0">
                                <div class="product-ratting">
                                    <ul>
                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                        <li class="review-total"><a href="#">( 95 Reviews )</a></li>
                                    </ul>
                                </div>
                                <h3>{{ $product->name }}</h3>
                                @php
                                $min_variant_price = $product->variants->min('price');
                                $min_variant_sale_price = $product->variants->min('sale_price');
                                @endphp


                                <div class="product-price">
                                    <span id="current-price">{{ number_format($min_variant_sale_price, 0, ',', '.') }} VND</span>
                                    <del id="original-price">{{ number_format($min_variant_price, 0, ',', '.') }} VND</del>
                                    <span id="stock-info">Số lượng: {{ $product->variants->first()->stock }}</span>
                                </div>
                                <div class="modal-product-meta ltn__product-details-menu-1">
                                    <ul>
                                        <li>
                                            <strong>Biến thể:</strong>
                                            <div class="variant-buttons rounded-sm width: 200px; height: 200px">
                                                @foreach($product->variants as $variant)
                                                <button class="btn btn-outline-primary variant-btn border border-solid border-primary-500  
                                                    text-primary-500 disabled:border-neutral-200 disabled:text-neutral-600 disabled:!bg-white 
                                                    text-sm px-4 py-2 items-center rounded-lg h-8 min-w-[82px] md:h-8 !bg-primary-50 
                                                    hover:border-primary-500 hover:text-primary-500 md:hover:border-primary-200 md:hover:text-primary-200"
                                                    data-price="{{ $variant->price }}"
                                                    data-sale-price="{{ $variant->sale_price }}"
                                                    data-stock="{{ $variant->stock }}">
                                                    @foreach($variant->attributeValues as $attributeValue)
                                                    {{ $attributeValue->attribute->name }}: {{ $attributeValue->attribute->slug }}{{ $attributeValue->value }} <br>
                                                    @endforeach
                                                </button>
                                                @endforeach
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <script>
                                    document.querySelectorAll('.variant-btn').forEach(button => {
                                        button.addEventListener('click', function() {
                                           
                                            document.querySelectorAll('.variant-btn').forEach(btn => {
                                                btn.classList.remove('zoomed');
                                            });

                                           
                                            this.classList.add('zoomed');

                                            
                                            let salePrice = this.getAttribute('data-sale-price');
                                            let originalPrice = this.getAttribute('data-price');
                                            let stock = this.getAttribute('data-stock');

                                            if (!salePrice || salePrice == '0') {
                                                salePrice = originalPrice;
                                            }

                                           
                                            const currentPriceElement = document.getElementById('current-price');
                                            const originalPriceElement = document.getElementById('original-price');
                                            const stockElement = document.getElementById('stock-info');

                                            
                                            currentPriceElement.classList.remove('slide-in');
                                            originalPriceElement.classList.remove('slide-in');
                                            stockElement.classList.remove('slide-in');
                                            currentPriceElement.classList.add('slide-out');
                                            originalPriceElement.classList.add('slide-out');
                                            stockElement.classList.add('slide-out');

                                            
                                            setTimeout(() => {
                                                currentPriceElement.innerHTML = new Intl.NumberFormat('vi-VN').format(salePrice) + " VND";
                                                originalPriceElement.innerHTML = new Intl.NumberFormat('vi-VN').format(originalPrice) + " VND";
                                                stockElement.innerHTML = "Số lượng: " + stock;

                                               
                                                currentPriceElement.classList.remove('slide-out');
                                                originalPriceElement.classList.remove('slide-out');
                                                stockElement.classList.remove('slide-out');
                                                currentPriceElement.classList.add('slide-in');
                                                originalPriceElement.classList.add('slide-in');
                                                stockElement.classList.add('slide-in');
                                            }, 800); 
                                        });
                                    });
                                </script>
                                <div class="ltn__product-details-menu-3">
                                    <h5>Thông tin chi tiết sản phẩm</h5>
                                    <ul class="product-details-list">
                                        <li><strong>Danh mục:</strong> <span>{{ $product->categories->isNotEmpty() ? $product->categories->first()->name : 'Không có danh mục' }}</span></li>
                                        <li><strong>Xuất xứ thương hiệu:</strong> <span>{{ $product->brand->name ?? 'Không có thương hiệu' }}</span></li>
                                        <li><strong>Nhà sản xuất:</strong> <span>{{ $product->brand->name ?? 'Chưa xác định' }}</span></li>
                                        <li><strong>Nước sản xuất:</strong> <span>Việt Nam</span></li>
                                        <li><strong>Lưu ý:</strong> <span>Mọi thông tin trên đây chỉ mang tính chất tham khảo. Đọc kỹ hướng dẫn sử dụng trước khi dùng</span></li>
                                    </ul>
                                </div>
                                <div class="ltn__product-details-menu-2">
                                    <ul>
                                        <li>
                                            <div class="cart-plus-minus">
                                                <input type="text" value="02" name="qtybutton" class="cart-plus-minus-box">
                                            </div>
                                        </li>
                                        <li>
                                            <a href="#" class="theme-btn-1 btn btn-effect-1" title="Add to Cart" data-bs-toggle="modal" data-bs-target="#add_to_cart_modal">
                                                <i class="fas fa-shopping-cart"></i>
                                                <span>Thêm vào giỏ hàng</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

    <!-- PRODUCT SLIDER AREA START -->
    <div class="ltn__product-slider-area ltn__product-gutter pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2">
                        <h4 class="title-2">Related Products<span>.</span></h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__related-product-slider-one-active slick-arrow-1">
                <!-- ltn__product-item -->
                @foreach ($relatedProducts as $product)
                    <div class="col-lg-12">
                        <div class="ltn__product-item ltn__product-item-3 text-center">
                            <div class="product-img">
                                <a href="{{ route('products.productct', $product->id) }}"><img
                                        src="{{ asset('upload/' . $product->thumbnail) }}" alt="#"></a>
                                <div class="product-badge">
                                    <ul>
                                        <li class="sale-badge">New</li>
                                    </ul>
                                </div>
                                <div class="product-hover-action">
                                    <ul>
                                        <li>
                                            <a href="#" title="Quick View" data-bs-toggle="modal"
                                                data-bs-target="#quick_view_modal">
                                                <i class="far fa-eye"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" title="Add to Cart" data-bs-toggle="modal"
                                                data-bs-target="#add_to_cart_modal">
                                                <i class="fas fa-shopping-cart"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" title="Wishlist" data-bs-toggle="modal"
                                                data-bs-target="#liton_wishlist_modal">
                                                <i class="far fa-heart"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="product-info">
                                <div class="product-ratting">
                                    <ul>
                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                    </ul>
                                </div>
                                <h2 class="product-title"><a href="product-details.html">{{ $product->name }}</a></h2>
                                <div class="product-price">
                                    <span>{{ number_format($product->sale_price, 0, ',', '.') }} VND</span>
                                    <del>{{ number_format($product->sell_price, 0, ',', '.') }} VND</del>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <!--  -->
            </div>
        </div>
    </div>
    <!-- PRODUCT SLIDER AREA END -->

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

    <!-- CALL TO ACTION END -->
    <!-- MODAL AREA START (Quick View Modal) -->
    @include('client.components.QuickViewModal')
    <!-- MODAL AREA END -->

    <!-- MODAL AREA START (Add To Cart Modal) -->
    @include('client.components.AddToCartModal')
    <!-- MODAL AREA END -->

    <!-- MODAL AREA START (Wishlist Modal) -->
    @include('client.components.WishlistModal')
    <!-- MODAL AREA END -->
@endsection
@push('js')
    <script>
        let selectedVariantId = null;

        document.querySelectorAll('.variant-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.variant-btn').forEach(btn => btn.classList.remove('zoomed'));
                this.classList.add('zoomed');

                selectedVariantId = this.getAttribute('data-variant-id');
            });
        });

        document.getElementById('add-to-cart-btn').addEventListener('click', function(event) {
            event.preventDefault();

            let quantity = document.getElementById('quantity-input').value;
            let productId = document.getElementById('add-to-cart-btn').getAttribute('data-product-id');

            if (!selectedVariantId) {
                showToast("Vui lòng chọn một biến thể trước khi thêm vào giỏ hàng!", "error");
                return;
            }

            fetch("{{ route('cart.add') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        product_variant_id: selectedVariantId,
                        quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        showToast("Đã thêm vào giỏ hàng!", "success");

                        // ✅ Cập nhật tổng số lượng sản phẩm trong giỏ hàng
                        $(".cart-count").text(data.cart_count);

                        // ✅ Cập nhật danh sách sản phẩm trong giỏ hàng
                        let cartHtml = "";
                        data.cart_items.forEach(item => {
                            let price = item.product.sale_price && item.product.sale_price > 0 ?
                                parseFloat(item.product.sale_price) :
                                parseFloat(item.product.sell_price);

                            cartHtml += `
                    <div class="mini-cart-item clearfix">
                        <div class="mini-cart-img">
                            <a href="#"><img src="${item.product.thumbnail}" alt="${item.product.name}"></a>
                        </div>
                        <div class="mini-cart-info">
                            <h6><a href="#">${item.product.name}</a></h6>
                            <span class="mini-cart-quantity">${item.quantity} x ${price.toLocaleString('vi-VN')}đ</span>
                        </div>
                    </div>`;
                        });

                        $(".mini-cart-list").html(cartHtml);

                        // ✅ Cập nhật tổng tiền trên header & giỏ hàng
                        $(".mini-cart-sub-total span").text(data.subtotal);
                        $("#cart-subtotal").text(data.subtotal);
                    } else {
                        showToast("Lỗi: " + data.message, "error");
                    }
                })
                .catch(error => {
                    console.error("Lỗi:", error);
                    showToast("Đã xảy ra lỗi khi thêm vào giỏ hàng!", "error");
                });
        });


        function showToast(message, type = "success") {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: type === "success" ? "#4CAF50" : "#FF3B30",
                close: true
            }).showToast();
        }
    </script>
@endpush
