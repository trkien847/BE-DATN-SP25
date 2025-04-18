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
    <style>
        .product-image {
            width: 250px;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-image:hover {
            transform: scale(1.1);
        }

        a {
            display: inline-block;
            overflow: visible;
        }

        .section-title {
            display: inline-block;
            position: relative;
            overflow: hidden;
        }


        .section-title .char:nth-child(1) {
            animation-delay: 0s, 2s, 4s;
        }

        .section-title .char:nth-child(2) {
            animation-delay: 0.1s, 2s, 4s;
        }

        .section-title .char:nth-child(3) {
            animation-delay: 0.2s, 2s, 4s;
        }

        .section-title .char:nth-child(4) {
            animation-delay: 0.3s, 2s, 4s;
        }

        .section-title .char:nth-child(5) {
            animation-delay: 0.4s, 2s, 4s;
        }

        .section-title .char:nth-child(6) {
            animation-delay: 0.5s, 2s, 4s;
        }

        .section-title .char:nth-child(7) {
            animation-delay: 0.6s, 2s, 4s;
        }

        .section-title .char:nth-child(8) {
            animation-delay: 0.7s, 2s, 4s;
        }

        .section-title .char:nth-child(9) {
            animation-delay: 0.8s, 2s, 4s;
        }

        .section-title .char:nth-child(10) {
            animation-delay: 0.9s, 2s, 4s;
        }

        .section-title .char:nth-child(11) {
            animation-delay: 1s, 2s, 4s;
        }

        .section-title .char:nth-child(12) {
            animation-delay: 1.1s, 2s, 4s;
        }

        .section-title .char:nth-child(13) {
            animation-delay: 1.2s, 2s, 4s;
        }

        .section-title .char:nth-child(14) {
            animation-delay: 1.3s, 2s, 4s;
        }

        .section-title .char:nth-child(15) {
            animation-delay: 1.4s, 2s, 4s;
        }

        .section-title .char:nth-child(16) {
            animation-delay: 1.5s, 2s, 4s;
        }

        .section-title .char:nth-child(17) {
            animation-delay: 1.6s, 2s, 4s;
        }

        .section-title .char:nth-child(18) {
            animation-delay: 1.7s, 2s, 4s;
        }

        .section-title .char:nth-child(19) {
            animation-delay: 1.8s, 2s, 4s;
        }

        .section-title .char:nth-child(20) {
            animation-delay: 1.9s, 2s, 4s;
        }

        .section-title .char:nth-child(21) {
            animation-delay: 2s, 2s, 4s;
        }

        .section-title .char:nth-child(1) {
            animation-delay: 0s, 2s, 4s;
        }

        .section-title .char:nth-child(2) {
            animation-delay: 0.1s, 2s, 4s;
        }

        .section-title .char:nth-child(3) {
            animation-delay: 0.2s, 2s, 4s;
        }

        .section-title .char:nth-child(4) {
            animation-delay: 0.3s, 2s, 4s;
        }

        .section-title .char:nth-child(5) {
            animation-delay: 0.4s, 2s, 4s;
        }

        .section-title .char:nth-child(6) {
            animation-delay: 0.5s, 2s, 4s;
        }

        .section-title .char:nth-child(7) {
            animation-delay: 0.6s, 2s, 4s;
        }

        .section-title .char:nth-child(8) {
            animation-delay: 0.7s, 2s, 4s;
        }

        .section-title .char:nth-child(9) {
            animation-delay: 0.8s, 2s, 4s;
        }

        .section-title .char:nth-child(10) {
            animation-delay: 0.9s, 2s, 4s;
        }

        .section-title .char:nth-child(11) {
            animation-delay: 1s, 2s, 4s;
        }

        .section-title .char:nth-child(12) {
            animation-delay: 1.1s, 2s, 4s;
        }

        .section-title .char:nth-child(13) {
            animation-delay: 1.2s, 2s, 4s;
        }

        .section-title .char:nth-child(14) {
            animation-delay: 1.3s, 2s, 4s;
        }

        .section-title .char:nth-child(15) {
            animation-delay: 1.4s, 2s, 4s;
        }

        .section-title .char:nth-child(16) {
            animation-delay: 1.5s, 2s, 4s;
        }

        .section-title .char:nth-child(17) {
            animation-delay: 1.6s, 2s, 4s;
        }

        .section-title .char:nth-child(18) {
            animation-delay: 1.7s, 2s, 4s;
        }

        .section-title .char:nth-child(19) {
            animation-delay: 1.8s, 2s, 4s;
        }

        .section-title .char:nth-child(20) {
            animation-delay: 1.9s, 2s, 4s;
        }

        .section-title .char:nth-child(21) {
            animation-delay: 2s, 2s, 4s;
        }

        @keyframes fadeOut {
            0% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        @keyframes fadeInFromBottom {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes greenFill {
            0% {
                color: green;
            }

            100% {
                color: #000;
            }
        }
    </style>
    <div class="ltn__utilize-overlay"></div>

    <!-- SLIDER AREA START (slider-3) -->
    <div class="ltn__slider-area ltn__slider-3---  section-bg-1--- mt-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <!-- CATEGORY-MENU-LIST START -->
                    <div class="ltn__category-menu-wrap">
                        <div class="ltn__category-menu-title">
                            <h2 class="section-bg-1 ltn__secondary-bg text-color-white">DANH MỤC</h2>
                        </div>
                        <div class="ltn__category-menu-toggle ltn__one-line-active">
                            <ul>
                                @foreach ($categories as $category)
                                    @if ($category->is_active)
                                        <li class="ltn__category-menu-item ltn__category-menu-drop">
                                            <a href="{{ route('category.show', ['categoryId' => $category->id]) }}">
                                                <i class="icon-shopping-bags"></i> {{ $category->name }}
                                            </a>

                                            @if ($category->categoryTypes->where('is_active', true)->isNotEmpty())
                                                <ul class="ltn__category-submenu ltn__category-column-5">
                                                    @foreach ($category->categoryTypes->where('is_active', true) as $type)
                                                        <li class="ltn__category-submenu-title ltn__category-menu-drop">
                                                            <a
                                                                href="{{ route('category.show', ['categoryId' => $category->id, 'subcategoryId' => $type->id]) }}">
                                                                {{ $type->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endif
                                @endforeach
                                <!-- Submenu Column - 4 -->
                                <li class="ltn__category-menu-more-item-parent">
                                    <a class="rx-default">
                                        Danh mục khác <span class="cat-thumb  icon-plus"></span>
                                    </a>
                                    <a class="rx-show">
                                        Đóng danh mục <span class="cat-thumb  icon-remove"></span>
                                    </a>
                                </li>
                                <!-- Single menu end -->
                            </ul>
                        </div>
                    </div>
                    <!-- END CATEGORY-MENU-LIST -->
                </div>
                <div class="col-lg-9">
                    <div class="ltn__slide-active-2 slick-slide-arrow-1 slick-slide-dots-1">
                        <!-- ltn__slide-item -->
                        <div class="ltn__slide-item ltn__slide-item-10 section-bg-1 bg-image"
                            data-bs-bg="http://127.0.0.1:8000/upload/baner1.jpg">
                            <div class="ltn__slide-item-inner">
                                <div class="container">
                                    <div class="row">

                                        <!-- <div class="col-lg-7 col-md-7 col-sm-7 align-self-center">
                                            <div class="slide-item-info">
                                                <div class="slide-item-info-inner ltn__slide-animation">
                                                    <h5
                                                        class="slide-sub-title ltn__secondary-color animated text-uppercase">
                                                        Giảm giá đến 50% chỉ hôm nay!</h5>
                                                    <h1 class="slide-title  animated">Tiêu chuẩn vàng <br>Pre-Workout
                                                    </h1>
                                                    <h5 class="color-orange  animated">Giá chỉ từ &16.99</h5>
                                                    <div class="slide-brief animated d-none">
                                                        <p>Chúng tôi cam kết cung cấp các sản phẩm chất lượng cao với giá cả
                                                            phải chăng. Đặt sức khỏe của bạn lên hàng đầu.</p>
                                                    </div>
                                                    <div class="btn-wrapper  animated">
                                                        <a href="{{ route('category.show') }}"
                                                            class="theme-btn-1 btn btn-effect-1 text-uppercase">Mua ngay</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->


                                        <div class="col-lg-5 col-md-5 col-sm-5 align-self-center">
                                            <div class="slide-item-img">
                                                <!-- <a href="shop.html"><img src="{{ asset('client/img/product/1.png') }}" alt="Image"></a> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ltn__slide-item -->
                        <div class="ltn__slide-item ltn__slide-item-10 section-bg-1 bg-image"
                            data-bs-bg="http://127.0.0.1:8000/upload/baner2.jpg">
                            <div class="ltn__slide-item-inner">
                                <div class="container">
                                    <div class="row">
                                        <!-- <div class="col-lg-7 col-md-7 col-sm-7 align-self-center">
                                            <div class="slide-item-info">
                                                <div class="slide-item-info-inner ltn__slide-animation">
                                                    <h4
                                                        class="slide-sub-title ltn__secondary-color animated text-uppercase">
                                                        Chào mừng đến với cửa hàng của chúng tôi</h4>
                                                    <h1 class="slide-title  animated">Gold Standard <br>Pre-Workout
                                                    </h1>
                                                    <div class="slide-brief animated d-none">
                                                        <p>Predictive analytics is drastically changing the real
                                                            estate industry. In the past, providing data for quick
                                                        </p>
                                                    </div>
                                                    <div class="btn-wrapper  animated">
                                                        <a href="shop.html"
                                                            class="theme-btn-1 btn btn-effect-1 text-uppercase">Shop
                                                            now</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="col-lg-5 col-md-5 col-sm-5 align-self-center">
                                            <div class="slide-item-img">
                                                <!-- <a href="shop.html"><img src="{{ asset('client/img/slider/62.jpg') }}" alt="Image"></a> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SLIDER AREA END -->

    <!-- FEATURE AREA START ( Feature - 3) -->
    <div class="ltn__feature-area mt-35 mt--65---">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__feature-item-box-wrap ltn__feature-item-box-wrap-2 ltn__border section-bg-1">
                        <div class="ltn__feature-item ltn__feature-item-8">
                            <div class="ltn__feature-icon">
                                <img src="{{ asset('client/img/icons/svg/8-t') }}rolley.svg" alt="Icon vận chuyển">
                            </div>
                            <div class="ltn__feature-info">
                                <h4>Miễn phí vận chuyển</h4>
                                <p>Cho tất cả các đơn hàng trên 49,000</p>
                            </div>
                        </div>
                        {{-- <div class="ltn__feature-item ltn__feature-item-8">
                            <div class="ltn__feature-icon">
                                <img src="{{ asset('client/img/icons/svg/9-m') }}oney.svg" alt="Icon hoàn tiền">
                            </div>
                            <div class="ltn__feature-info">
                                <h4>Đổi trả trong 15 ngày</h4>
                                <p>Đảm bảo hoàn tiền</p>
                            </div>
                        </div> --}}
                        <div class="ltn__feature-item ltn__feature-item-8">
                            <div class="ltn__feature-icon">
                                <img src="{{ asset('client/img/icons/svg/11-') }}gift-card.svg" alt="Icon quà tặng">
                            </div>
                            <div class="ltn__feature-info">
                                <h4>Thanh toán nhanh</h4>
                                <p>Liên kết toàn bộ ngân hàng trên cả nước</p>
                            </div>
                        </div>
                        <div class="ltn__feature-item ltn__feature-item-8">
                            <div class="ltn__feature-icon">
                                <img src="{{ asset('client/img/icons/svg/11-') }}gift-card.svg" alt="#">
                            </div>
                            <div class="ltn__feature-info">
                                <h4>Ưu đãi & quà tặng</h4>
                                <p>Cho tất cả các đơn hàng trên 99,000</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- FEATURE AREA END -->

    <!-- PRODUCT TAB AREA START (product-item-3) -->
    <div class="ltn__product-tab-area ltn__product-gutter pt-115 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2--- text-center">
                        <!-- <h6 class="section-subtitle ltn__secondary-color">// Cars</h6> -->
                        <h1 class="section-title">
                            <span class="char">S</span><span class="char">ả</span><span class="char">n</span>
                            <span class="char"> </span>
                            <span class="char">p</span><span class="char">h</span><span class="char">ẩ</span><span
                                class="char">m</span>
                            <span class="char"> </span>
                            <span class="char">c</span><span class="char">ủ</span><span class="char">a</span>
                            <span class="char"> </span>
                            <span class="char">c</span><span class="char">h</span><span class="char">ú</span><span
                                class="char">n</span><span class="char">g</span>
                            <span class="char"> </span>
                            <span class="char">t</span><span class="char">ô</span><span class="char">i</span>
                        </h1>
                        <p>Chữa bệnh bằng thuốc – Gìn giữ sức khỏe bằng niềm tin.(nguồn chatGPT)</p>
                    </div>
                    <div class="ltn__tab-menu ltn__tab-menu-2 ltn__tab-menu-top-right-- text-uppercase text-center">
                        <div class="nav">
                            @foreach ($categories->take(5) as $index => $category)
                                <a data-bs-toggle="tab" href="#liton_tab_3_{{ $index + 1 }}"
                                    class="{{ $loop->first ? 'active show' : '' }}">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="tab-content">
                        @foreach ($categories->take(5) as $index => $category)
                            <div class="tab-pane fade {{ $loop->first ? 'active show' : '' }}"
                                id="liton_tab_3_{{ $index + 1 }}">
                                <div class="ltn__product-tab-content-inner">
                                    <div class="row ltn__tab-product-slider-one-active slick-arrow-1">

                                        @foreach ($category->products as $product)
                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                <div class="ltn__product-item ltn__product-item-3 text-center">
                                                    <div class="product-img">
                                                        <a href="{{ route('products.productct', $product->id) }}">
                                                            <img src="{{ asset('upload/' . $product->thumbnail) }}"
                                                                alt="{{ $product->name }}" class="product-image"
                                                                width="250px" height="200px">
                                                        </a>
                                                        <div class="product-badge">
                                                            <ul>
                                                                @if (!empty($salePrice) && $salePrice > 0)
                                                                    @php
                                                                        $discount = round(
                                                                            (($regularPrice - $salePrice) /
                                                                                $regularPrice) *
                                                                                100,
                                                                        );
                                                                    @endphp
                                                                    <li class="sale-badge bg-danger rounded-1">-
                                                                        {{ $discount }}%</li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                        <div class="product-hover-action">
                                                            <ul>
                                                                <li>
                                                                    <a href="#" class="quick-view-btn"
                                                                        data-id="{{ $product->id }}" title="Quick View"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#quick_view_modal">
                                                                        <i class="far fa-eye"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="product-info">
                                                        <h2 class="product-title">
                                                            <a
                                                                href="{{ route('products.productct', $product->id) }}">{{ $product->name }}</a>
                                                        </h2>
                                                        <div class="product-price">
                                                            @php
                                                                $salePrice = $product->variants
                                                                    ->where('sale_price', '>', 0)
                                                                    ->min('sale_price');
                                                                $regularPrice = $product->variants->min('price');
                                                            @endphp

                                                            @if (!empty($salePrice) && $salePrice > 0)
                                                                <del class="text-danger fs-6 d-block mb-2">{{ number_format($regularPrice) }}
                                                                    VND</del>
                                                                <span
                                                                    class="text-success fs-6 d-block mb-2">{{ number_format($salePrice) }}
                                                                    VND</span>
                                                            @else
                                                                <span>{{ number_format($regularPrice) }}đ</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- PRODUCT TAB AREA END -->

    <!-- ABOUT US AREA START -->
    <div class="ltn__about-us-area bg-image pt-115 pb-110 d-none" data-bs-bg="img/bg/26.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 align-self-center">
                    <div class="about-us-img-wrap about-img-left">
                        <!-- <img src="{{ asset('client/img/others/7.png"') }} alt="About Us Image"> -->
                    </div>
                </div>
                <div class="col-lg-6 align-self-center">
                    <div class="about-us-info-wrap">
                        <div class="section-title-area ltn__section-title-2--- mb-20">
                            <h6 class="section-subtitle section-subtitle-2--- ltn__secondary-color">N95 Facial
                                Covering Mask</h6>
                            <h1 class="section-title">Grade A Safety Masks
                                For Sale. Haurry Up!</h1>
                            <p>Over 39,000 people work for us in more than 70 countries all over the
                                This breadth of global coverage, combined with specialist services</p>
                        </div>
                        <ul class="ltn__list-item-half clearfix">
                            <li>
                                <i class="flaticon-home-2"></i>
                                Activated Carbon
                            </li>
                            <li>
                                <i class="flaticon-mountain"></i>
                                Breathing Valve
                            </li>
                            <li>
                                <i class="flaticon-heart"></i>
                                6 Layer Filteration
                            </li>
                            <li>
                                <i class="flaticon-secure"></i>
                                Rewashes & Reusable
                            </li>
                        </ul>
                        <div class="btn-wrapper animated">
                            <a href="service.html"
                                class="ltn__secondary-color text-uppercase text-decoration-underline">View
                                Products</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ABOUT US AREA END -->

    <!-- COUNTDOWN AREA START -->
    <div class="ltn__call-to-action-area section-bg-1 bg-image pt-120 pb-120" data-bs-bg="">
        <div class="container">
            <div class="row">
                <div class="http://127.0.0.1:8000/upload/.jpg">
                    <!-- <img src="{{ asset('client/img/banner/15.png') }}" alt="#"> -->
                </div>
                <div class="col-lg-5 col-md-6 col-sm-8">
                    <div class="call-to-action-inner text-color-white--- text-center---">
                        <div class="section-title-area ltn__section-title-2--- text-center---">
                            <h6 class="ltn__secondary-color">Ưu đãi hot hôm nay</h6>
                            <h1 class="section-title">Mua thuốc với<br>giá giảm 50%</h1>
                            <p>Nhận thêm tiền hoàn lại với các ưu đãi và giảm giá đặc biệt</p>
                        </div>
                        <div class="ltn__countdown ltn__countdown-3 bg-white--" data-countdown="2021/12/28">
                        </div>
                        <div class="btn-wrapper animated">
                            <a href="{{ route('category.show') }}"
                                class="theme-btn-1 btn btn-effect-1 text-uppercase">Mua
                                ngay</a>
                            <a href="{{ route('category.show') }}"
                                class="ltn__secondary-color text-decoration-underline">Ưu đãi
                                trong ngày</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- COUNTDOWN AREA END -->

    <!-- PRODUCT AREA START (product-item-3) -->
    <div class="ltn__product-area ltn__product-gutter pt-115 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h1 class="section-title">SẢN PHẨM BÁN CHẠY</h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__tab-product-slider-one-active--- slick-arrow-1">
                <!-- ltn__product-item -->
                @foreach ($productBestSale as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="ltn__product-item ltn__product-item-3 text-center">
                            <div class="product-img">
                                <a href="{{ route('products.productct', $product->product->id) }}">
                                    <img src="{{ asset('upload/' . $product->product->thumbnail) }}"
                                        alt="{{ $product->name }} " width="250px" height="200px">
                                        
                                </a>
                                <div class="product-badge">
                                    <ul>
                                        @if (!empty($product->sale_price) && $product->sale_price > 0)
                                            @php
                                                $discount = round(
                                                    (($product->sell_price - $product->sale_price) /
                                                        $product->sell_price) *
                                                        100,
                                                );
                                            @endphp
                                            <li class="sale-badge bg-danger rounded-1">- {{ $discount }}%</li>
                                           
                                        @endif
                                    </ul>
                                </div>
                                <div class="product-hover-action">
                                    <ul>
                                        <li>
                                            <a href="#" class="quick-view-btn"
                                                data-id="{{ $product->product->id }}" title="Quick View"
                                                data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                                <i class="far fa-eye"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="product-info">
                                <h2 class="product-title">
                                    <a
                                        href="{{ route('products.productct', $product->product->id) }}">{{ $product->product->name }}</a>
                                </h2>
                                <div class="product-price">
                                    @php
                                        $variants = $product->product->variants ?? collect();
                                        $salePrice = $variants->where('sale_price', '>', 0)->min('sale_price');
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
                    </div>
                @endforeach
                <!--  -->
            </div>
        </div>
    </div>
    <!-- PRODUCT AREA END -->

    <!-- SMALL PRODUCT LIST AREA START -->
    <div class="ltn__small-product-list-area section-bg-1 pt-115 pb-70 mb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h1 class="section-title">SẢN PHẨM NỔI BẬT</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- small-product-item -->
                @foreach ($productTop as $product)
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="ltn__small-product-item">
                            <div class="small-product-item-img">
                                <a href="{{ route('products.productct', $product->id) }}"><img
                                        src="{{ asset('upload/' . $product->thumbnail) }}" alt="Image" width="250px"
                                        height="100px"></a>
                            </div>
                            <div class="small-product-item-info">
                                <div class="product-ratting">
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
                                        $salePrice = $product->variants->where('sale_price', '>', 0)->min('sale_price');
                                        $regularPrice = $product->variants->min('price');
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
                    </div>
                @endforeach
                <!--  -->
            </div>
        </div>
    </div>
    <!-- SMALL PRODUCT LIST AREA END -->

    <!-- TESTIMONIAL AREA START (testimonial-4) -->
    <div class="ltn__testimonial-area section-bg-1 pt-290 pb-70">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h6 class="section-subtitle ltn__secondary-color">Các Thông Tin Bài ViếtViết</h6>
                        <h1 class="section-title">Bài Viết Nổi Bật<span>.</span></h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__testimonial-slider-3-active slick-arrow-1 slick-arrow-1-inner">
                <div class="col-lg-12">
                    <div class="ltn__testimonial-item ltn__testimonial-item-4">
                        <div class="ltn__testimoni-img">
                            <img src="{{ asset('client/img/testimonial/6') }}.jpg" alt="Ảnh khách hàng">
                        </div>
                        <div class="ltn__testimoni-info">
                            <p>Tôi rất hài lòng với chất lượng sản phẩm và dịch vụ. Đội ngũ nhân viên nhiệt tình,
                                tư vấn chuyên nghiệp giúp tôi chọn được sản phẩm phù hợp.</p>
                            <h4>Nguyễn Thị Hương</h4>
                            <h6>Khách hàng thân thiết</h6>
                        </div>
                        <div class="ltn__testimoni-bg-icon">
                            <i class="far fa-comments"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__testimonial-item ltn__testimonial-item-4">
                        <div class="ltn__testimoni-img">
                            <img src="{{ asset('client/img/testimonial/7') }}.jpg" alt="Ảnh khách hàng">
                        </div>
                        <div class="ltn__testimoni-info">
                            <p>Sản phẩm chính hãng, giá cả hợp lý. Đặc biệt ấn tượng với chính sách bảo hành và
                                chăm sóc khách hàng sau bán hàng rất tốt.</p>
                            <h4>Trần Văn Nam</h4>
                            <h6>Khách hàng thường xuyên</h6>
                        </div>
                        <div class="ltn__testimoni-bg-icon">
                            <i class="far fa-comments"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__testimonial-item ltn__testimonial-item-4">
                        <div class="ltn__testimoni-img">
                            <img src="{{ asset('client/img/testimonial/1') }}.jpg" alt="Ảnh khách hàng">
                        </div>
                        <div class="ltn__testimoni-info">
                            <p>Mua hàng online rất thuận tiện, giao hàng nhanh chóng. Nhân viên tư vấn nhiệt tình,
                                giải đáp mọi thắc mắc của tôi.</p>
                            <h4>Lê Thị Minh</h4>
                            <h6>Khách hàng mới</h6>
                        </div>
                        <div class="ltn__testimoni-bg-icon">
                            <i class="far fa-comments"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- TESTIMONIAL AREA END -->

    <!-- BLOG AREA START (blog-3) -->
    <div class="ltn__blog-area pt-115 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2--- text-center">
                        <h6 class="section-subtitle section-subtitle-2--- ltn__secondary-color">Tin tức & Bài viết</h6>
                        <h1 class="section-title">Bài viết mới nhất</h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__blog-slider-one-active slick-arrow-1 ltn__blog-item-3-normal">
                <!-- Blog Item -->
                <div class="col-lg-12">
                    <div class="ltn__blog-item ltn__blog-item-3">
                        <div class="ltn__blog-img">
                            <a href="blog-details.html"><img src="{{ asset('client/img/blog/1.jpg') }}"
                                    alt="Ảnh bài viết"></a>
                        </div>
                        <div class="ltn__blog-brief">
                            <div class="ltn__blog-meta">
                                <ul>
                                    <li class="ltn__blog-author">
                                        <a href="#"><i class="far fa-user"></i>Đăng bởi: Quản trị viên</a>
                                    </li>
                                    <li class="ltn__blog-tags">
                                        <a href="#"><i class="fas fa-tags"></i>Sức khỏe</a>
                                    </li>
                                </ul>
                            </div>
                            <h3 class="ltn__blog-title">
                                <a href="blog-details.html">10 Cách hiệu quả để bảo vệ sức khỏe mỗi ngày</a>
                            </h3>
                            <div class="ltn__blog-meta-btn">
                                <div class="ltn__blog-meta">
                                    <ul>
                                        <li class="ltn__blog-date">
                                            <i class="far fa-calendar-alt"></i>24 Tháng 6, 2024
                                        </li>
                                    </ul>
                                </div>
                                <div class="ltn__blog-btn">
                                    <a href="blog-details.html">Xem thêm</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Thêm các blog item khác tương tự -->
                <div class="col-lg-12">
                    <div class="ltn__blog-item ltn__blog-item-3">
                        <div class="ltn__blog-img">
                            <a href="blog-details.html"><img src="{{ asset('client/img/blog/1.jpg') }}"
                                    alt="Ảnh bài viết"></a>
                        </div>
                        <div class="ltn__blog-brief">
                            <div class="ltn__blog-meta">
                                <ul>
                                    <li class="ltn__blog-author">
                                        <a href="#"><i class="far fa-user"></i>Đăng bởi: Quản trị viên</a>
                                    </li>
                                    <li class="ltn__blog-tags">
                                        <a href="#"><i class="fas fa-tags"></i>Sức khỏe</a>
                                    </li>
                                </ul>
                            </div>
                            <h3 class="ltn__blog-title">
                                <a href="blog-details.html">10 Cách hiệu quả để bảo vệ sức khỏe mỗi ngày</a>
                            </h3>
                            <div class="ltn__blog-meta-btn">
                                <div class="ltn__blog-meta">
                                    <ul>
                                        <li class="ltn__blog-date">
                                            <i class="far fa-calendar-alt"></i>24 Tháng 6, 2024
                                        </li>
                                    </ul>
                                </div>
                                <div class="ltn__blog-btn">
                                    <a href="blog-details.html">Xem thêm</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__blog-item ltn__blog-item-3">
                        <div class="ltn__blog-img">
                            <a href="blog-details.html"><img src="{{ asset('client/img/blog/1.jpg') }}"
                                    alt="Ảnh bài viết"></a>
                        </div>
                        <div class="ltn__blog-brief">
                            <div class="ltn__blog-meta">
                                <ul>
                                    <li class="ltn__blog-author">
                                        <a href="#"><i class="far fa-user"></i>Đăng bởi: Quản trị viên</a>
                                    </li>
                                    <li class="ltn__blog-tags">
                                        <a href="#"><i class="fas fa-tags"></i>Sức khỏe</a>
                                    </li>
                                </ul>
                            </div>
                            <h3 class="ltn__blog-title">
                                <a href="blog-details.html">10 Cách hiệu quả để bảo vệ sức khỏe mỗi ngày</a>
                            </h3>
                            <div class="ltn__blog-meta-btn">
                                <div class="ltn__blog-meta">
                                    <ul>
                                        <li class="ltn__blog-date">
                                            <i class="far fa-calendar-alt"></i>24 Tháng 6, 2024
                                        </li>
                                    </ul>
                                </div>
                                <div class="ltn__blog-btn">
                                    <a href="blog-details.html">Xem thêm</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BLOG AREA END -->

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
    @if (session('no_access'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toastify({
                    text: "{{ session('no_access') }}",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#ff4444",
                    stopOnFocus: true,
                    close: true,
                    style: {
                        fontSize: '14px',
                        fontWeight: '500',
                        padding: '12px 20px',
                        borderRadius: '4px',
                        boxShadow: '0 2px 5px rgba(0,0,0,0.2)'
                    }
                }).showToast();
            });
        </script>
    @endif


    <script>
        let selectedVariantId = null; // Biến lưu ID biến thể đã chọn

        $(document).on('click', '.quick-view-btn', function(e) {
            e.preventDefault();
            let productId = $(this).data('id');

            $.ajax({
                url: `/get-product/${productId}`,
                method: 'GET',
                success: function(response) {
                    console.log(response);
                    $('#quick_view_modal').attr('data-product-id', response.id); // Cập nhật productId
                    $('#quick_view_modal .modal-product-img').html(`
                <a href="{{ route('products.productct', ':id') }}" target="_blank">
                    <img src="/upload/${response.thumbnail}" alt="${response.name}" class="w-full h-auto rounded-lg">
                </a>
            `.replace(':id', response.id));

                    $('#quick_view_modal h3').text(response.name);
                    $('#quick_view_modal .product-price span')
                        .css('font-size', '30px')
                        .text(new Intl.NumberFormat().format(response.sale_price) + 'đ');

                    $('#quick_view_modal .product-price del')
                        .css('font-size', '30px')
                        .text(new Intl.NumberFormat().format(response.sell_price) + 'đ');
                    let categoriesHtml = response.categories.map(category =>
                        `<a href="#">${category.name}</a>`
                    ).join(", ");
                    $('#quick_view_modal .modal-product-meta span').html(categoriesHtml);

                    // Làm mới danh sách biến thể
                    if (response.variants && response.variants.length > 0) {
                        let variantsHtml = '<div class="variant-buttons">';
                        variantsHtml += response.variants.map((variant, index) => {
                            let shapeAttr = variant.attributes.find(attr => attr.attribute?.name
                                .includes('Hình'));
                            let weightAttr = variant.attributes.find(attr => attr.attribute
                                ?.name.includes('Khối'));

                            let variantName = [shapeAttr?.value, weightAttr?.value].filter(
                                Boolean).join(' ') || 'Không có thuộc tính';

                            return `
                        <button class="btn btn-outline-primary variant-btn border border-solid border-primary-500  
                            text-primary-500 disabled:border-neutral-200 disabled:text-neutral-600 disabled:!bg-white 
                            text-sm px-4 py-2 items-center rounded-lg h-8 min-w-[82px] md:h-8 !bg-primary-50 
                            hover:border-primary-500 hover:text-primary-500 md:hover:border-primary-200 md:hover:text-primary-200"
                            data-product-id="${response.id}"
                            data-variant-id="${variant.id}"
                            data-price="${variant.price}"
                            data-sale-price="${variant.sale_price}"
                            data-stock="${variant.stock}"
                            data-variant-index="${index}">
                            ${variantName || 'Không có thuộc tính'}
                        </button>
                    `;
                        }).join('');
                        variantsHtml += '</div>';
                        $('#quick_view_modal .modal-product-variants .variant-list').html(variantsHtml);

                        // Xóa sự kiện cũ và gắn sự kiện mới
                        $('.variant-btn').off('click').on('click', function() {
                            const variantPrice = $(this).data('sale-price') || $(this).data(
                                'price');
                            const variantStock = $(this).data('stock');

                            // Cập nhật giá chính
                            $('#quick_view_modal .product-price span').text(
                                new Intl.NumberFormat().format(variantPrice) + 'đ'
                            );

                            if (variantStock > 0) {
                                $('.cart-plus-minus-box')
                                    .val(1)
                                    .attr('max', variantStock)
                                    .prop('disabled', false);
                                $('#quick-add-to-cart-btn').prop('disabled', false);
                            } else {
                                $('.cart-plus-minus-box')
                                    .val(0)
                                    .attr('max', 0)
                                    .prop('disabled', true);
                                $('#quick-add-to-cart-btn').prop('disabled', true);
                            }

                            $('.variant-btn').removeClass('active');
                            $(this).addClass('active');
                        });
                    } else {
                        $('#quick_view_modal .modal-product-variants .variant-list').html('');
                        $('.cart-plus-minus-box')
                            .val(1)
                            .attr('max', response.stock || 0)
                            .prop('disabled', false);
                        $('#quick-add-to-cart-btn').prop('disabled', false);
                    }

                    $('#quick_view_modal').modal('show'); // Hiển thị modal sau khi load xong
                },
                error: function(xhr) {
                    console.log("Lỗi khi tải dữ liệu:", xhr);
                }
            });
        });

        $(document).on('click', '#quick-add-to-cart-btn', function(e) {
            e.preventDefault();

            let selectedButton = $('.variant-btn.active'); // Lấy nút biến thể được chọn
            let productId = selectedButton.length ? selectedButton.data('product-id') : $('#quick_view_modal').data(
                'product-id'); // Dùng product_id từ nút, nếu không có thì dùng từ modal
            let selectedVariantId = selectedButton.length ? selectedButton.data('variant-id') :
                null; // Lấy variant_id từ nút
            let quantity = $('.cart-plus-minus-box').val();
            $.ajax({
                url: "{{ route('cart.add') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId,
                    product_variant_id: selectedVariantId, // Null nếu không có biến thể
                    quantity: quantity
                },
                success: function(response) {
                    if (response.status === "success") {
                        $(".mini-cart-quantity").text(response.cart_count);
                        let cartHtml = "";
                        response.cart_items.forEach(item => {
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
                                <span class="mini-cart-quantity">
                                    ${item.quantity} x 
                                    <span class="mini-cart-price">${price.toLocaleString('vi-VN')}đ</span>
                                </span>
                            </div>
                        </div>`;
                        });
                        $(".mini-cart-list").html(cartHtml);
                        $(".mini-cart-sub-total span").text(response.subtotal);
                        $("#cart-subtotal").text(response.subtotal);
                        $('sup').text(response.cart_count);

                        Toastify({
                            text: "Sản phẩm đã được thêm vào giỏ hàng!",
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#4caf50",
                            stopOnFocus: true
                        }).showToast();
                        $('#quick_view_modal').modal('hide');
                    }
                },
                error: function(xhr) {
                    alert("Có lỗi xảy ra, vui lòng thử lại!");
                    console.error(xhr.responseText);
                }
            });
        });
    </script>
@endpush
@push('css')
    <style>
        .toastify {
            padding: 12px 20px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
        }

        .toastify-success {
            background: linear-gradient(to right, #00b09b, #96c93d);
        }

        .toastify-error {
            background: linear-gradient(to right, #ff5f6d, #ffc371);
        }

        .modal-product-variants {
            margin: 15px 0;
        }

        .variant-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            /* tương đương gap-2 của Bootstrap */
            margin-top: 0.5rem;
            /* tương đương mt-2 */
        }

        .ltn__product-item {
            min-height: 380px;
            /* hoặc chiều cao bạn thấy hợp lý */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 10px;
        }

        .product-info {
            margin-top: 10px;
        }

        .product-title {
            font-size: 16px;
            min-height: 48px;
            /* Đảm bảo chiếm 2 dòng */
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* Hiển thị 2 dòng */
            -webkit-box-orient: vertical;
            text-overflow: ellipsis;
            line-height: 1.4em;
            height: 2.8em;
        }

        .product-img img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-price {
            min-height: 48px;
            /* Chiều cao cố định cho khu vực giá */
        }

        .product-price span,
        .product-price del {
            display: block;
            font-size: 14px;
            line-height: 1.4;
        }

        .product-price del {
            color: #999;
            text-decoration: line-through;
        }

        .product-img {
            width: 100%;
            padding: 0;
            overflow: hidden;
        }
    </style>
@endpush
