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
    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image " data-bs-bg="img/bg/14.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">Shop Left Sidebar</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="index.html"><span class="ltn__secondary-color"><i
                                                class="fas fa-home"></i></span> Home</a></li>
                                <li>Shop Left Sidebar</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <!-- PRODUCT DETAILS AREA START -->
    <div class="ltn__product-area ltn__product-gutter mb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 order-lg-2 mb-120">
                    <div class="ltn__shop-options">
                        <ul>
                            <li>
                                <div class="ltn__grid-list-tab-menu ">
                                    <div class="nav">
                                        <a class="active show" data-bs-toggle="tab" href="#liton_product_grid"><i
                                                class="fas fa-th-large"></i></a>
                                        <a data-bs-toggle="tab" href="#liton_product_list"><i class="fas fa-list"></i></a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="showing-product-number text-right">
                                    <span>Showing 1–12 of {{ count($products) }} results</span>
                                </div>
                            </li>
                            <li>
                                <div class="short-by text-center">
                                    <form id="sortForm">
                                        <select name="sort" id="sortSelect">
                                            <option value="">Default Sorting</option>
                                            <option value="popularity">Sort by popularity</option>
                                            <option value="new">Sort by new arrivals</option>
                                            <option value="price_asc">Sort by price: low to high</option>
                                            <option value="price_desc">Sort by price: high to low</option>
                                        </select>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="liton_product_grid">
                            <div class="ltn__product-tab-content-inner ltn__product-grid-view">
                                <div id="product-list" class="row">
                                    @foreach ($products as $product)
                                        <div class="col-xl-4 col-sm-6 col-6">
                                            <div class="ltn__product-item ltn__product-item-3 text-center">
                                                <div class="product-img">
                                                    <a href="#"><img
                                                            src="{{ asset('upload/' . $product->thumbnail) }}"
                                                            alt="#"></a>
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
                                                                <a href="#" class="quick-view-btn"
                                                                    data-id="{{ $product->id }}" title="Quick View"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#quick_view_modal">
                                                                    <i class="far fa-eye"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" class="add-to-cart-btn"
                                                                    data-id="{{ $product->id }}" title="Thêm vào giỏ hàng">
                                                                    <i class="fas fa-shopping-cart"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="product-info">
                                                    <h2 class="product-title"><a
                                                            href="product-details.html">{{ $product->name }}</a></h2>
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
                    </div>
                    <div class="ltn__pagination-area text-center">
                        <div class="ltn__pagination">
                            <ul>
                                <li><a href="#"><i class="fas fa-angle-double-left"></i></a></li>
                                <li><a href="#">1</a></li>
                                <li class="active"><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">...</a></li>
                                <li><a href="#">10</a></li>
                                <li><a href="#"><i class="fas fa-angle-double-right"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4  mb-120">
                    <aside class="sidebar ltn__shop-sidebar ltn__right-sidebar">
                        <!-- Category Widget -->
                        <div class="widget ltn__menu-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Product categories</h4>
                            <ul>
                                @foreach ($categories as $category)
                                    <li><a href="{{ route('category.show', parameters: ['categoryId' => $category->id]) }}">{{ $category->name }}
                                            <span><i class="fas fa-long-arrow-alt-right"></i></span></a></li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Search Widget -->
                        <div class="widget ltn__search-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Search Objects</h4>
                            <input type="text" id="search-input" placeholder="Search your keyword...">
                        </div>

                        <!-- Brands Widget -->
                        <div class="widget ltn__tagcloud-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Brands</h4>
                            <ul>
                                @foreach ($brands as $brand)
                                    <li>
                                        <label>
                                            <input type="checkbox" name="brand[]" value="{{ $brand->id }}"
                                                class="filter-checkbox brand-checkbox">
                                            {{ $brand->name }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Product Price Widget -->
                        <div class="widget ltn__tagcloud-widget ltn__size-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Product Price</h4>
                            <ul>
                                @foreach ([
            'under_100' => 'Dưới 100.000đ',
            '100_300' => '100.000đ - 300.000đ',
            '300_500' => '300.000đ - 500.000đ',
            'above_500' => 'Trên 500.000đ',
        ] as $value => $label)
                                    <li>
                                        <label>
                                            <input type="checkbox" name="price_range[]" value="{{ $value }}"
                                                class="filter-checkbox price-checkbox">
                                            {{ $label }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- Top Rated Product Widget -->
                        <div class="widget ltn__top-rated-product-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Top Rated Product</h4>
                            <ul>
                                @foreach ($productTop as $product)
                                    <li>
                                        <div class="top-rated-product-item clearfix">
                                            <div class="top-rated-product-img">
                                                <a href="product-details.html"><img
                                                        src="{{ asset('upload/' . $product->thumbnail) }}"
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
                                                <h6><a href="product-details.html">{{ $product->name }}</a></h6>
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
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- Banner Widget -->
                        <div class="widget ltn__banner-widget">
                            <a href="shop.html"><img src="{{ asset('client/img/banner/banner-2.jpg') }}"
                                    alt="#"></a>
                        </div>

                    </aside>
                </div>
            </div>
        </div>
    </div>
    <!-- PRODUCT DETAILS AREA END -->

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
@push('css')
@endpush
@push('js')
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script>
        $(document).ready(function() {
            // Lấy categoryId và subcategoryId từ URL
            function getCategoryAndSubcategoryFromUrl() {
                let pathArray = window.location.pathname.split('/').filter(p => p);
                let categoryIndex = pathArray.indexOf("search") + 1;

                return {
                    categoryId: pathArray[categoryIndex] || null,
                    subcategoryId: pathArray[categoryIndex + 1] || ''
                };
            }

            let {
                categoryId,
                subcategoryId
            } = getCategoryAndSubcategoryFromUrl();

            console.log("Category ID:", categoryId, "Subcategory ID:", subcategoryId);

            // Bắt sự kiện thay đổi trên input tìm kiếm và checkbox
            $('.filter-checkbox, #search-input,#sortSelect').on('change keyup', filterProducts);

            function filterProducts() {
                let searchQuery = $('#search-input').val();
                let selectedBrands = $('.brand-checkbox:checked').map((_, el) => el.value).get();
                let selectedPrices = $('.price-checkbox:checked').map((_, el) => el.value).get();
                let sortOption = $('#sortSelect').val();
                $.ajax({
                    url: `/search/${categoryId}/${subcategoryId}`,
                    method: "GET",
                    data: {
                        q: searchQuery,
                        brand: selectedBrands,
                        price_range: selectedPrices,
                        sort: sortOption
                    },
                    success: function(response) {
                        console.log(response);
                        let productsHtml = response.products.map(product => `
                    <div class="col-xl-4 col-sm-6 col-6">
                        <div class="ltn__product-item ltn__product-item-3 text-center">
                            <div class="product-img">
                                <a href="#"><img src="/upload/${product.thumbnail}" alt="#"></a>
                                <div class="product-badge">
                                    <ul>
                                        ${product.sale_price > 0 ? `<li class="sale-badge">-${Math.round(((product.sell_price - product.sale_price) / product.sell_price) * 100)}%</li>` : ''}
                                    </ul>
                                </div>
                                <div class="product-hover-action">
                                    <ul>
                                        <li>
                                            <a href="#" class="quick-view-btn" data-id="${product.id}" title="Quick View" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                                <i class="far fa-eye"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" title="Add to Cart" data-bs-toggle="modal" data-bs-target="#add_to_cart_modal">
                                                <i class="fas fa-shopping-cart"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="product-info">
                                <h2 class="product-title"><a href="product-details.html">${product.name}</a></h2>
                                <div class="product-price">
                                    ${product.sale_price > 0 ? 
                                        `<span>${new Intl.NumberFormat().format(product.sale_price)}đ</span> <del>${new Intl.NumberFormat().format(product.sell_price)}đ</del>` :
                                        `<span>${new Intl.NumberFormat().format(product.sell_price)}đ</span>`
                                    }
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');

                        $('#product-list').html(productsHtml);
                    }
                });
            }

            // Xử lý Quick View
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
