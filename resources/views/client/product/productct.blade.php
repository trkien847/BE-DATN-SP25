@extends('client.layouts.layout')
@section('content')
    <!-- Utilize Cart Menu Start -->
    @include('client.components.CartMenuStart')
    <!-- Utilize Cart Menu End -->

    <!-- Utilize Mobile Menu Start -->
    @include('client.components.MobileMenuStart')
    <!-- Utilize Mobile Menu End -->
    <div>
        <audio id="backgroundMusic" autoplay>
            <source src="{{ asset('audio/amine.mp3') }}" type="audio/mpeg">
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
    <div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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

        .slide-out {
            animation: slideOut 0.8s ease forwards;

        }

        .slide-in {
            animation: slideIn 0.8s ease forwards;

        }

        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-100%);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
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

        .variant-btn {
            transition: all 0.3s ease;
        }

        .variant-btn:hover:not(:disabled) {
            transform: scale(1.05);
        }

        .variant-btn.selected {
            background: #DBEAFE;
            border-color: #3B82F6;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            color: #1E40AF;
        }

        /* Scale-up animation on click */
        @keyframes scaleUp {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .variant-btn.clicked {
            animation: scaleUp 0.3s ease;
        }

        .variant-btn:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }
    </style>
    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image" data-bs-bg="img/bg/14.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">Chi tiết sản phẩm</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="{{ route('index') }}"><span class="ltn__secondary-color"><i
                                                class="fas fa-home"></i></span>Trang chủ</a></li>
                                <li>Chi tiết sản phẩm</li>
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
                                            <a href="{{ asset('upload/' . $product->thumbnail) }}"
                                                data-rel="lightcase:myCollection">
                                                <img src="{{ asset('upload/' . $product->thumbnail) }}" alt="Image">
                                            </a>
                                        </div>
                                        @foreach ($productGallery as $productGallery)
                                            <div class="single-large-img">
                                                <a href="{{ asset('upload/' . $productGallery->image) }}"
                                                    data-rel="lightcase:myCollection">
                                                    <img src="{{ asset('upload/' . $productGallery->image) }}" alt="Image">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="ltn__shop-details-small-img slick-arrow-2">
                                        <div class="single-small-img">
                                            <img src="{{ asset('upload/' . $product->thumbnail) }}" alt="Image">
                                        </div>
                                        @foreach ($productGallery2 as $gallery)
                                            <div class="single-small-img">
                                                <img src="{{ asset('upload/' . $gallery->image) }}" alt="Image">
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


                                    <div class="fw-bold fs-5 text-dark">
                                        <p id="current-price" class="text-success fs-3 mb-1">
                                            {{ number_format($min_variant_sale_price, 0, ',', '.') }} VND
                                        </p>
                                        <del id="original-price" class="text-danger fs-6 d-block mb-2">
                                            {{ number_format($min_variant_price, 0, ',', '.') }} VND
                                        </del>
                                        <p id="stock-info" class="text-muted fst-italic">
                                            Số lượng còn: {{ $product->variants->first()->stock }}
                                        </p>
                                    </div>

                                    <div class="modal-product-meta ltn__product-details-menu-1">
                                        <ul>
                                            <li>
                                                <h3>Phân loại:</h3>
                                                <div
                                                    class="variant-buttons grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 p-6 bg-gray-50 rounded-xl shadow-sm">
                                                    @if ($product->variants->isEmpty())
                                                        <p class="col-span-full text-center text-gray-500 font-medium">Không
                                                            có biến thể nào</p>
                                                    @else
                                                        @foreach ($product->variants as $variant)
                                                            @php
                                                                $shapeValue = $variant->attributeValues->firstWhere(
                                                                    'attribute_id',
                                                                    12,
                                                                );
                                                                $weightValue = $variant->attributeValues->firstWhere(
                                                                    'attribute_id',
                                                                    14,
                                                                );
                                                                $variantName =
                                                                    $shapeValue && $weightValue
                                                                        ? "{$shapeValue->value} {$weightValue->value}"
                                                                        : $variant->attributeValues
                                                                            ->map(
                                                                                fn(
                                                                                    $av,
                                                                                ) => "{$av->attribute->name}: {$av->value}",
                                                                            )
                                                                            ->join(', ');
                                                            @endphp
                                                            <button
                                                                class="variant-btn flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium 
                                                                bg-white border border-primary-300 rounded-lg shadow-sm 
                                                                hover:bg-primary-50 hover:border-primary-500 hover:shadow-md 
                                                                focus:outline-none focus:ring-2 focus:ring-primary-300 
                                                                disabled:bg-gray-100 disabled:border-gray-200 disabled:text-gray-400 
                                                                disabled:cursor-not-allowed transition-all duration-300 
                                                                {{ $variant->stock == 0 ? 'disabled' : '' }} 
                                                                {{ session('selected_variant') == $variant->id ? 'bg-primary-100 border-primary-600 text-primary-700' : '' }}"
                                                                data-variant-id="{{ $variant->id }}"
                                                                data-price="{{ $variant->price }}"
                                                                data-sale-price="{{ $variant->sale_price ?? 0 }}"
                                                                data-stock="{{ $variant->stock }}"
                                                                onclick="selectVariant(this)">
                                                                <span>{{ $variantName ?: 'Không có thuộc tính' }}</span>
                                                            </button>
                                                        @endforeach
                                                    @endif
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
                                                    currentPriceElement.innerHTML = new Intl.NumberFormat('vi-VN').format(
                                                        salePrice) + " VND";
                                                    originalPriceElement.innerHTML = new Intl.NumberFormat('vi-VN').format(
                                                        originalPrice) + " VND";
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
                                        <h4>THÔNG TIN CHI TIẾT SẢN PHẨM </h4>
                                        <ul class="product-details-list">
                                            <li><strong>Danh mục:</strong>
                                                <span>{{ $product->categories->isNotEmpty() ? $product->categories->first()->name : 'Không có danh mục' }}</span>
                                            </li>
                                            <li><strong>Thương hiệu:</strong>
                                                <span>{{ $product->brand->name ?? 'Không có thương hiệu' }}</span></li>
                                            <li><strong>Nhà sản xuất:</strong>
                                                <span>{{ $product->brand->name ?? 'Chưa xác định' }}</span></li><br>
                                            <li><strong>Xuất xứ:</strong> <span>Việt Nam</span></li>
                                            <li><strong>Lưu ý:</strong> <span>Mọi thông tin trên đây chỉ mang tính chất tham
                                                    khảo. Đọc kỹ hướng dẫn sử dụng trước khi dùng!</span></li>
                                        </ul>
                                    </div>
                                    <div class="ltn__product-details-menu-2">
                                        <ul>
                                            <li>
                                                <div class="cart-plus-minus">
                                                    <input type="number" value="1" name="qtybutton"
                                                        class="cart-plus-minus-box" id="quantity-input" min="1">
                                                </div>
                                            </li>
                                            <li>
                                                <button id="add-to-cart-btn" class="theme-btn-1 btn btn-effect-1"
                                                    title="Add to Cart" data-product-id="{{ $product->id }}">
                                                    <i class="fas fa-shopping-cart"></i>
                                                    <span>MUA NGAY</span>
                                                </button>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shop Tab Start -->
                    <div class="ltn__shop-details-tab-inner ltn__shop-details-tab-inner-2">
                        <div class="ltn__shop-details-tab-menu">
                            <div class="nav">
                                <a class="active show" data-bs-toggle="tab" href="#liton_tab_details_1_1">MÔ TẢ SẢN PHẨM</a>
                                <a data-bs-toggle="tab" href="#liton_tab_details_1_2" class="">ĐÁNH GIÁ</a>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="liton_tab_details_1_1">
                                <div class="ltn__shop-details-tab-content-inner">
                                    <p>{!! $product->content !!}</p>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="liton_tab_details_1_2">
                                <div class="ltn__shop-details-tab-content-inner">
                                    <h4 class="title-2">ĐÁNH GIÁ TỪ NGƯỜI MUA</h4>
                                    <div class="product-ratting">
                                        <ul>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                            <li><a href="#"><i class="far fa-star"></i></a></li>
                                            <li class="review-total"> <a href="#"> ( 95 Đánh gia )</a></li>
                                        </ul>
                                    </div>
                                    <hr>
                                    <!-- comment-area -->
                                    <div class="ltn__comment-area mb-30">
                                        <div class="ltn__comment-inner">
                                            <ul>
                                                <li>
                                                    <div class="ltn__comment-item clearfix">
                                                        <div class="ltn__commenter-img">
                                                            <img src="img/testimonial/1.jpg" alt="Image">
                                                        </div>
                                                        <div class="ltn__commenter-comment">
                                                            <h6><a href="#">Adam Smit</a></h6>
                                                            <div class="product-ratting">
                                                                <ul>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a>
                                                                    </li>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a>
                                                                    </li>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a>
                                                                    </li>
                                                                    <li><a href="#"><i
                                                                                class="fas fa-star-half-alt"></i></a></li>
                                                                    <li><a href="#"><i class="far fa-star"></i></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                                                Doloribus, omnis fugit corporis iste magnam ratione.</p>
                                                            <span class="ltn__comment-reply-btn">September 3, 2020</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="ltn__comment-item clearfix">
                                                        <div class="ltn__commenter-img">
                                                            <img src="img/testimonial/3.jpg" alt="Image">
                                                        </div>
                                                        <div class="ltn__commenter-comment">
                                                            <h6><a href="#">Adam Smit</a></h6>
                                                            <div class="product-ratting">
                                                                <ul>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a>
                                                                    </li>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a>
                                                                    </li>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a>
                                                                    </li>
                                                                    <li><a href="#"><i
                                                                                class="fas fa-star-half-alt"></i></a></li>
                                                                    <li><a href="#"><i class="far fa-star"></i></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                                                Doloribus, omnis fugit corporis iste magnam ratione.</p>
                                                            <span class="ltn__comment-reply-btn">September 2, 2020</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="ltn__comment-item clearfix">
                                                        <div class="ltn__commenter-img">
                                                            <img src="img/testimonial/2.jpg" alt="Image">
                                                        </div>
                                                        <div class="ltn__commenter-comment">
                                                            <h6><a href="#">Adam Smit</a></h6>
                                                            <div class="product-ratting">
                                                                <ul>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a>
                                                                    </li>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a>
                                                                    </li>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a>
                                                                    </li>
                                                                    <li><a href="#"><i
                                                                                class="fas fa-star-half-alt"></i></a></li>
                                                                    <li><a href="#"><i class="far fa-star"></i></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                                                Doloribus, omnis fugit corporis iste magnam ratione.</p>
                                                            <span class="ltn__comment-reply-btn">September 2, 2020</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- comment-reply -->
                                    <div class="ltn__comment-reply-area ltn__form-box mb-30">
                                        <form action="#">
                                            <h4 class="title-2">Add a Review</h4>
                                            <div class="mb-30">
                                                <div class="add-a-review">
                                                    <h6>Your Ratings:</h6>
                                                    <div class="product-ratting">
                                                        <ul>
                                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                            <li><a href="#"><i class="fas fa-star-half-alt"></i></a>
                                                            </li>
                                                            <li><a href="#"><i class="far fa-star"></i></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <textarea placeholder="Type your comments...."></textarea>
                                            </div>
                                            <div class="input-item input-item-name ltn__custom-icon">
                                                <input type="text" placeholder="Type your name....">
                                            </div>
                                            <div class="input-item input-item-email ltn__custom-icon">
                                                <input type="email" placeholder="Type your email....">
                                            </div>
                                            <div class="input-item input-item-website ltn__custom-icon">
                                                <input type="text" name="website" placeholder="Type your website....">
                                            </div>
                                            <label class="mb-0"><input type="checkbox" name="agree"> Save my name,
                                                email, and website in this browser for the next time I comment.</label>
                                            <div class="btn-wrapper">
                                                <button class="btn theme-btn-1 btn-effect-1 text-uppercase"
                                                    type="submit">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Shop Tab End -->
                </div>
                <div class="col-lg-4">
                    <aside class="sidebar ltn__shop-sidebar ltn__right-sidebar">
                        <!-- Top Rated Product Widget -->

                        <div class="widget ltn__top-rated-product-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Sản phẩm bán chạy</h4>
                            <ul>
                                @foreach ($productTop as $product)
                                    <li>
                                        <div class="top-rated-product-item clearfix">
                                            <div class="top-rated-product-img">
                                                <a href="{{ route('products.productct', $product->product->id) }}"><img
                                                        src="{{ asset('upload/' . $product->product->thumbnail) }}"
                                                        alt="#"></a>
                                            </div>
                                            <div class="top-rated-product-info">
                                                <div class="product-ratting">
                                                    {{-- <ul>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    </ul> --}}
                                                </div>
                                                <h6><a
                                                        href="{{ route('products.productct', $product->product->id) }}">{{ $product->product->name }}</a>
                                                </h6>
                                                <div class="product-price">
                                                    @php
                                                        $variants = $product->product->variants ?? collect();
                                                        $salePrice = $variants
                                                            ->where('sale_price', '>', 0)
                                                            ->min('sale_price');
                                                        $regularPrice = $variants->min('price');
                                                    @endphp
                                                    @if (!empty($salePrice) && $salePrice > 0)
                                                        <span>{{ number_format($salePrice) }}đ</span>
                                                        <del>{{ number_format($regularPrice) }}đ</del>
                                                    @else
                                                        <span>{{ number_format($regularPrice) }}đ</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Banner Widget -->
                        {{-- <div class="widget ltn__banner-widget">
                            <a href="shop.html"><img src="img/banner/2.jpg" alt="#"></a>
                        </div> --}}
                    </aside>
                </div>
            </div>
        </div>
    </div>
    <!-- SHOP DETAILS AREA END -->

    <!-- PRODUCT SLIDER AREA START -->
    <div class="ltn__product-slider-area ltn__product-gutter pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2">
                        <h4 class="title-2">SẢN PHẨM LIÊN QUAN<span>.</span></h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__related-product-slider-one-active slick-arrow-1">
                <!-- ltn__product-item -->
                @foreach ($relatedProducts as $product)
                    <div class="col-lg-12">
                        <div class="ltn__product-item ltn__product-item-3 text-center"
                            style="height: 420px; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;">

                            <div class="product-img d-flex justify-content-center align-items-center"
                                style="height: 200px;">
                                <a href="{{ route('products.productct', $product->id) }}">
                                    <img src="{{ asset('upload/' . $product->thumbnail) }}" alt="{{ $product->name }}"
                                        style="width: 250px; height: 200px; object-fit: cover; display: block;">
                                </a>
                                <div class="product-badge">
                                    <ul>
                                        <li class="sale-badge bg-danger rounded-1">New</li>
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
                                <div class="product-ratting d-flex justify-content-center">
                                    <ul>
                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                    </ul>
                                </div>
                                <h2 class="product-title"><a
                                        href="{{ route('products.productct', $product->id) }}">{{ $product->name }}</a>
                                </h2>
                                <div class="product-price">
                                    @php
                                        // Lấy giá rẻ nhất từ biến thể
                                        $minVariantSalePrice = $product->variants->min('sale_price');
                                        $minVariantPrice = $product->variants->min('price');
                                        // Nếu không có sale_price hoặc sale_price = 0, dùng price
                                        $displaySalePrice =
                                            $minVariantSalePrice && $minVariantSalePrice > 0
                                                ? $minVariantSalePrice
                                                : $minVariantPrice;
                                    @endphp
                                    <span
                                        class="text-success fs-6 d-block mb-2">{{ number_format($displaySalePrice, 0, ',', '.') }}
                                        VND</span>
                                    <del class="text-danger fs-6 d-block mb-2">{{ number_format($minVariantPrice, 0, ',', '.') }}
                                        VND</del>
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
                        $('sup').text(response.cart_count);
                    } else {
                        showToast("Lỗi: " + data.message, "error");
                    }
                })
                .catch(error => {
                    console.error("Lỗi:", error);
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
