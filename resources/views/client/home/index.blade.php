@extends('client.layouts.layout')
@section('content')
    <!-- Utilize Cart Menu Start -->
    @include('client.components.CartMenuStart')
    <!-- Utilize Cart Menu End -->

    <!-- Utilize Mobile Menu Start -->
    @include('client.components.MobileMenuStart')
    <!-- Utilize Mobile Menu End -->

    <div class="ltn__utilize-overlay"></div>

    <!-- SLIDER AREA START (slider-3) -->
    <div class="ltn__slider-area ltn__slider-3---  section-bg-1--- mt-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <!-- CATEGORY-MENU-LIST START -->
                    <div class="ltn__category-menu-wrap">
                        <div class="ltn__category-menu-title">
                            <h2 class="section-bg-1 ltn__secondary-bg text-color-white">categories</h2>
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
                                        More categories <span class="cat-thumb  icon-plus"></span>
                                    </a>
                                    <a class="rx-show">
                                        close menu <span class="cat-thumb  icon-remove"></span>
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
                            data-bs-bg="img/slider/61.jpg">
                            <div class="ltn__slide-item-inner">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-7 col-md-7 col-sm-7 align-self-center">
                                            <div class="slide-item-info">
                                                <div class="slide-item-info-inner ltn__slide-animation">
                                                    <h5
                                                        class="slide-sub-title ltn__secondary-color animated text-uppercase">
                                                        Up To 50% Off Today Only!</h5>
                                                    <h1 class="slide-title  animated">Gold Standard <br>Pre-Workout
                                                    </h1>
                                                    <h5 class="color-orange  animated">Starting at &16.99</h5>
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
                                        </div>
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
                            data-bs-bg="img/slider/62.jpg">
                            <div class="ltn__slide-item-inner">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-7 col-md-7 col-sm-7 align-self-center">
                                            <div class="slide-item-info">
                                                <div class="slide-item-info-inner ltn__slide-animation">
                                                    <h4
                                                        class="slide-sub-title ltn__secondary-color animated text-uppercase">
                                                        Welcome to our shop</h4>
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
                                        </div>
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
                                <img src="{{ asset('client/img/icons/svg/8-t') }}rolley.svg" alt="#">
                            </div>
                            <div class="ltn__feature-info">
                                <h4>Free shipping</h4>
                                <p>On all orders over $49.00</p>
                            </div>
                        </div>
                        <div class="ltn__feature-item ltn__feature-item-8">
                            <div class="ltn__feature-icon">
                                <img src="{{ asset('client/img/icons/svg/9-m') }}oney.svg" alt="#">
                            </div>
                            <div class="ltn__feature-info">
                                <h4>15 days returns</h4>
                                <p>Moneyback guarantee</p>
                            </div>
                        </div>
                        <div class="ltn__feature-item ltn__feature-item-8">
                            <div class="ltn__feature-icon">
                                <img src="{{ asset('client/img/icons/svg/10-') }}credit-card.svg" alt="#">
                            </div>
                            <div class="ltn__feature-info">
                                <h4>Secure checkout</h4>
                                <p>Protected by Paypal</p>
                            </div>
                        </div>
                        <div class="ltn__feature-item ltn__feature-item-8">
                            <div class="ltn__feature-icon">
                                <img src="{{ asset('client/img/icons/svg/11-') }}gift-card.svg" alt="#">
                            </div>
                            <div class="ltn__feature-info">
                                <h4>Offer & gift here</h4>
                                <p>On all orders over</p>
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
                        <h1 class="section-title">Our Products</h1>
                        <p>A highly efficient slip-ring scanner for today's diagnostic requirements.</p>
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
                                                        <a href="">
                                                            <img src="{{ asset('upload/' . $product->thumbnail) }}"
                                                                alt="{{ $product->name }}">
                                                        </a>
                                                        <div class="product-badge">
                                                            <ul>
                                                                @if (!empty($product->sale_price) && $product->sale_price > 0)
                                                                    @php
                                                                        $discount = round(
                                                                            (($product->sell_price -
                                                                                $product->sale_price) /
                                                                                $product->sell_price) *
                                                                                100,
                                                                        );
                                                                    @endphp
                                                                    <li class="sale-badge">-{{ $discount }}%</li>
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
                                                                <li>
                                                                    <a href="#" class="add-to-cart-btn" data-id="{{ $product->id }}"
                                                                        title="Thêm vào giỏ hàng">
                                                                        <i class="fas fa-shopping-cart"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="product-info">
                                                        <h2 class="product-title">
                                                            <a href="product-details.html">{{ $product->name }}</a>
                                                        </h2>
                                                        <div class="product-price">
                                                            @if (!empty($product->sale_price) && $product->sale_price > 0)
                                                                <span>{{ number_format($product->sale_price) }}đ</span>
                                                                <del>{{ number_format($product->sell_price) }}đ</del>
                                                            @else
                                                                <span>{{ number_format($product->sell_price) }}đ</span>
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
    <div class="ltn__call-to-action-area section-bg-1 bg-image pt-120 pb-120" data-bs-bg="img/bg/27.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-4">
                    <!-- <img src="{{ asset('client/img/banner/15.png') }}" alt="#"> -->
                </div>
                <div class="col-lg-5 col-md-6 col-sm-8">
                    <div class="call-to-action-inner text-color-white--- text-center---">
                        <div class="section-title-area ltn__section-title-2--- text-center---">
                            <h6 class="ltn__secondary-color">Todays Hot Offer</h6>
                            <h1 class="section-title">Buy all your medicines <br> at 50% offer</h1>
                            <p>Get extra cashback with great deals and discounts </p>
                        </div>
                        <div class="ltn__countdown ltn__countdown-3 bg-white--" data-countdown="2021/12/28">
                        </div>
                        <div class="btn-wrapper animated">
                            <a href="shop.html" class="theme-btn-1 btn btn-effect-1 text-uppercase">Shop now</a>
                            <a href="shop.html" class="ltn__secondary-color text-decoration-underline">Deal of
                                The Day</a>
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
                        <h1 class="section-title">Best Selling Item</h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__tab-product-slider-one-active--- slick-arrow-1">
                <!-- ltn__product-item -->
                @foreach ($productBestSale as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="ltn__product-item ltn__product-item-3 text-center">
                            <div class="product-img">
                                <a href="">
                                    <img src="{{ asset('upload/' . $product->thumbnail) }}" alt="{{ $product->name }}">
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
                                            <li class="sale-badge">-{{ $discount }}%</li>
                                        @endif
                                    </ul>
                                </div>
                                <div class="product-hover-action">
                                    <ul>
                                        <li>
                                            <a href="#" class="quick-view-btn" data-id="{{ $product->id }}"
                                                title="Quick View" data-bs-toggle="modal"
                                                data-bs-target="#quick_view_modal">
                                                <i class="far fa-eye"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="add-to-cart-btn" data-id="{{ $product->id }}"
                                                title="Thêm vào giỏ hàng">
                                                <i class="fas fa-shopping-cart"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="product-info">
                                <h2 class="product-title">
                                    <a href="product-details.html">{{ $product->name }}</a>
                                </h2>
                                <div class="product-price">
                                    @if (!empty($product->sale_price) && $product->sale_price > 0)
                                        <span>{{ number_format($product->sale_price) }}đ</span>
                                        <del>{{ number_format($product->sell_price) }}đ</del>
                                    @else
                                        <span>{{ number_format($product->sell_price) }}đ</span>
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
    <div class="ltn__small-product-list-area section-bg-1 pt-115 pb-90 mb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h1 class="section-title">Featured Products</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- small-product-item -->
                @foreach ($productTop as $product)
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="ltn__small-product-item">
                            <div class="small-product-item-img">
                                <a href="product-details.html"><img src="{{ asset('upload/' . $product->thumbnail) }}"
                                        alt="Image"></a>
                            </div>
                            <div class="small-product-item-info">
                                {{-- <div class="product-ratting">
                                <ul>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                    <li><a href="#"><i class="far fa-star"></i></a></li>
                                </ul>
                            </div> --}}
                                <h2 class="product-title"><a href="product-details.html">{{ $product->name }}</a></h2>
                                <div class="product-price">
                                    @if (!empty($product->sale_price) && $product->sale_price > 0)
                                        <span>{{ number_format($product->sale_price) }}đ</span>
                                        <del>{{ number_format($product->sell_price) }}đ</del>
                                    @else
                                        <span>{{ number_format($product->sell_price) }}đ</span>
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
                        <h6 class="section-subtitle ltn__secondary-color">Testimonials</h6>
                        <h1 class="section-title">Clients Feedbacks<span>.</span></h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__testimonial-slider-3-active slick-arrow-1 slick-arrow-1-inner">
                <div class="col-lg-12">
                    <div class="ltn__testimonial-item ltn__testimonial-item-4">
                        <div class="ltn__testimoni-img">
                            <img src="{{ asset('client/img/testimonial/6') }}.jpg" alt="#">
                        </div>
                        <div class="ltn__testimoni-info">
                            <p>Lorem ipsum dolor sit amet, consectetur adipi sicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua. </p>
                            <h4>Rosalina D. William</h4>
                            <h6>Founder</h6>
                        </div>
                        <div class="ltn__testimoni-bg-icon">
                            <i class="far fa-comments"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__testimonial-item ltn__testimonial-item-4">
                        <div class="ltn__testimoni-img">
                            <img src="{{ asset('client/img/testimonial/7') }}.jpg" alt="#">
                        </div>
                        <div class="ltn__testimoni-info">
                            <p>Lorem ipsum dolor sit amet, consectetur adipi sicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua. </p>
                            <h4>Rosalina D. William</h4>
                            <h6>Founder</h6>
                        </div>
                        <div class="ltn__testimoni-bg-icon">
                            <i class="far fa-comments"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__testimonial-item ltn__testimonial-item-4">
                        <div class="ltn__testimoni-img">
                            <img src="{{ asset('client/img/testimonial/1') }}.jpg" alt="#">
                        </div>
                        <div class="ltn__testimoni-info">
                            <p>Lorem ipsum dolor sit amet, consectetur adipi sicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua. </p>
                            <h4>Rosalina D. William</h4>
                            <h6>Founder</h6>
                        </div>
                        <div class="ltn__testimoni-bg-icon">
                            <i class="far fa-comments"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__testimonial-item ltn__testimonial-item-4">
                        <div class="ltn__testimoni-img">
                            <img src="{{ asset('client/img/testimonial/2') }}.jpg" alt="#">
                        </div>
                        <div class="ltn__testimoni-info">
                            <p>Lorem ipsum dolor sit amet, consectetur adipi sicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua. </p>
                            <h4>Rosalina D. William</h4>
                            <h6>Founder</h6>
                        </div>
                        <div class="ltn__testimoni-bg-icon">
                            <i class="far fa-comments"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__testimonial-item ltn__testimonial-item-4">
                        <div class="ltn__testimoni-img">
                            <img src="{{ asset('client/img/testimonial/5') }}.jpg" alt="#">
                        </div>
                        <div class="ltn__testimoni-info">
                            <p>Lorem ipsum dolor sit amet, consectetur adipi sicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua. </p>
                            <h4>Rosalina D. William</h4>
                            <h6>Founder</h6>
                        </div>
                        <div class="ltn__testimoni-bg-icon">
                            <i class="far fa-comments"></i>
                        </div>
                    </div>
                </div>
                <!--  -->
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
                        <h6 class="section-subtitle section-subtitle-2--- ltn__secondary-color">News & Blogs</h6>
                        <h1 class="section-title">Leatest News Feeds</h1>
                    </div>
                </div>
            </div>
            <div class="row  ltn__blog-slider-one-active slick-arrow-1 ltn__blog-item-3-normal">
                <!-- Blog Item -->
                <div class="col-lg-12">
                    <div class="ltn__blog-item ltn__blog-item-3">
                        <div class="ltn__blog-img">
                            <a href="blog-details.html"><img src="{{ asset('client/img/blog/1.jpg') }}"
                                    alt="Blog Image"></a>
                        </div>
                        <div class="ltn__blog-brief">
                            <div class="ltn__blog-meta">
                                <ul>
                                    <li class="ltn__blog-author">
                                        <a href="#"><i class="far fa-user"></i>by: Admin</a>
                                    </li>
                                    <li class="ltn__blog-tags">
                                        <a href="#"><i class="fas fa-tags"></i>Decorate</a>
                                    </li>
                                </ul>
                            </div>
                            <h3 class="ltn__blog-title"><a href="blog-details.html">10 Brilliant Ways To
                                    Decorate Your Home</a></h3>
                            <div class="ltn__blog-meta-btn">
                                <div class="ltn__blog-meta">
                                    <ul>
                                        <li class="ltn__blog-date"><i class="far fa-calendar-alt"></i>June 24,
                                            2021</li>
                                    </ul>
                                </div>
                                <div class="ltn__blog-btn">
                                    <a href="blog-details.html">Read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Blog Item -->
                <div class="col-lg-12">
                    <div class="ltn__blog-item ltn__blog-item-3">
                        <div class="ltn__blog-img">
                            <a href="blog-details.html"><img src="{{ asset('client/img/blog/2.jpg') }}"
                                    alt="#"></a>
                        </div>
                        <div class="ltn__blog-brief">
                            <div class="ltn__blog-meta">
                                <ul>
                                    <li class="ltn__blog-author">
                                        <a href="#"><i class="far fa-user"></i>by: Admin</a>
                                    </li>
                                    <li class="ltn__blog-tags">
                                        <a href="#"><i class="fas fa-tags"></i>Interior</a>
                                    </li>
                                </ul>
                            </div>
                            <h3 class="ltn__blog-title"><a href="blog-details.html">The Most Inspiring Interior
                                    Design Of 2021</a></h3>
                            <div class="ltn__blog-meta-btn">
                                <div class="ltn__blog-meta">
                                    <ul>
                                        <li class="ltn__blog-date"><i class="far fa-calendar-alt"></i>July 23,
                                            2021</li>
                                    </ul>
                                </div>
                                <div class="ltn__blog-btn">
                                    <a href="blog-details.html">Read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Blog Item -->
                <div class="col-lg-12">
                    <div class="ltn__blog-item ltn__blog-item-3">
                        <div class="ltn__blog-img">
                            <a href="blog-details.html"><img src="{{ asset('client/img/blog/3.jpg') }}"
                                    alt="#"></a>
                        </div>
                        <div class="ltn__blog-brief">
                            <div class="ltn__blog-meta">
                                <ul>
                                    <li class="ltn__blog-author">
                                        <a href="#"><i class="far fa-user"></i>by: Admin</a>
                                    </li>
                                    <li class="ltn__blog-tags">
                                        <a href="#"><i class="fas fa-tags"></i>Estate</a>
                                    </li>
                                </ul>
                            </div>
                            <h3 class="ltn__blog-title"><a href="blog-details.html">Recent Commercial Real
                                    Estate Transactions</a></h3>
                            <div class="ltn__blog-meta-btn">
                                <div class="ltn__blog-meta">
                                    <ul>
                                        <li class="ltn__blog-date"><i class="far fa-calendar-alt"></i>May 22,
                                            2021</li>
                                    </ul>
                                </div>
                                <div class="ltn__blog-btn">
                                    <a href="blog-details.html">Read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Blog Item -->
                <div class="col-lg-12">
                    <div class="ltn__blog-item ltn__blog-item-3">
                        <div class="ltn__blog-img">
                            <a href="blog-details.html"><img src="{{ asset('client/img/blog/4.jpg') }}"
                                    alt="#"></a>
                        </div>
                        <div class="ltn__blog-brief">
                            <div class="ltn__blog-meta">
                                <ul>
                                    <li class="ltn__blog-author">
                                        <a href="#"><i class="far fa-user"></i>by: Admin</a>
                                    </li>
                                    <li class="ltn__blog-tags">
                                        <a href="#"><i class="fas fa-tags"></i>Room</a>
                                    </li>
                                </ul>
                            </div>
                            <h3 class="ltn__blog-title"><a href="blog-details.html">Renovating a Living Room?
                                    Experts Share Their Secrets</a></h3>
                            <div class="ltn__blog-meta-btn">
                                <div class="ltn__blog-meta">
                                    <ul>
                                        <li class="ltn__blog-date"><i class="far fa-calendar-alt"></i>June 24,
                                            2021</li>
                                    </ul>
                                </div>
                                <div class="ltn__blog-btn">
                                    <a href="blog-details.html">Read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Blog Item -->
                <div class="col-lg-12">
                    <div class="ltn__blog-item ltn__blog-item-3">
                        <div class="ltn__blog-img">
                            <a href="blog-details.html"><img src="{{ asset('client/img/blog/5.jpg') }}"
                                    alt="#"></a>
                        </div>
                        <div class="ltn__blog-brief">
                            <div class="ltn__blog-meta">
                                <ul>
                                    <li class="ltn__blog-author">
                                        <a href="#"><i class="far fa-user"></i>by: Admin</a>
                                    </li>
                                    <li class="ltn__blog-tags">
                                        <a href="#"><i class="fas fa-tags"></i>Trends</a>
                                    </li>
                                </ul>
                            </div>
                            <h3 class="ltn__blog-title"><a href="blog-details.html">7 home trends that will
                                    shape your house in 2021</a></h3>
                            <div class="ltn__blog-meta-btn">
                                <div class="ltn__blog-meta">
                                    <ul>
                                        <li class="ltn__blog-date"><i class="far fa-calendar-alt"></i>June 24,
                                            2021</li>
                                    </ul>
                                </div>
                                <div class="ltn__blog-btn">
                                    <a href="blog-details.html">Read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
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
        $(document).on('click', '.quick-view-btn', function(e) {
            e.preventDefault();

            let productId = $(this).data('id');
            console.log("Fetching product with ID:", productId);

            $.ajax({
                url: `/get-product/${productId}`,
                method: 'GET',
                success: function(response) {
                    console.log(response);
                    $('#quick_view_modal .modal-product-img img').attr('src',
                        `/upload/${response.thumbnail}`);
                    $('#quick_view_modal h3').text(response.name);
                    $('#quick_view_modal .product-price span').text(new Intl.NumberFormat()
                        .format(response.sale_price) + 'đ');
                    $('#quick_view_modal .product-price del').text(new Intl.NumberFormat()
                        .format(response.sell_price) + 'đ');

                    let categoriesHtml = response.categories.map(category =>
                        `<a href="#">${category.name}</a>`).join(", ");
                    $('#quick_view_modal .modal-product-meta span').html(categoriesHtml);
                },
                error: function(xhr) {
                    console.log("Lỗi khi tải dữ liệu:", xhr);
                }
            });
        });
        $(document).ready(function() {
            $(".add-to-cart-btn").click(function(e) {
                e.preventDefault();
                let productId = $(this).data("id");

                $.ajax({
                    url: "{{ route('cart.add') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: productId
                    },
                    success: function(response) {
                        if (response.status === "success") {
                            // Cập nhật số lượng giỏ hàng
                            $(".mini-cart-quantity").text(response.cart_count);

                            // Cập nhật danh sách sản phẩm trong giỏ hàng
                            let cartHtml = "";
                            response.cart_items.forEach(item => {
                                let price = item.product.sale_price && item.product
                                    .sale_price > 0 ?
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
                            $(".mini-cart-sub-total span").text(response.subtotal);

                            // Cập nhật thông tin sản phẩm trong modal
                            $("#add_to_cart_modal .modal-product-img img").attr("src", response
                                .cart_items[0].product.thumbnail);
                            $("#add_to_cart_modal .modal-product-info h5 a").text(response
                                .cart_items[0].product.name);

                            // Hiển thị modal Bootstrap
                            $("#add_to_cart_modal").modal("show");
                        }
                    },
                    error: function(xhr) {
                        alert("Có lỗi xảy ra, vui lòng thử lại!");
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endpush
