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
                        <h1 class="page-title">Sản phầm</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="{{ route('index') }}"><span class="ltn__secondary-color"><i
                                                class="fas fa-home"></i></span>Trang chủ</a></li>
                                <li>Sản phẩm</li>
                                {{-- <h1 class="page-title"> 
                            @foreach ($category->categoryTypes->where('is_active', true) as $type)
                            
                                <a href="{{ route('category.show', ['categoryId' => $category->id, 'subcategoryId' => $type->id]) }}">
                                    {{ $type->name }}
                                </a>
                            
                        @endforeach</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="{{ route('index') }}"><span class="ltn__secondary-color"><i
                                                class="fas fa-home"></i></span> Trang chủ</a></li>
                                               
                                 @foreach ($category->categoryTypes->where('is_active', true) as $type)
                                    <li>
                                        <a href="{{ route('category.show', ['categoryId' => $category->id, 'subcategoryId' => $type->id]) }}">
                                            {{ $type->name }}
                                        </a>
                                    </li>
                                @endforeach --}}
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

                                </div>
                            </li>
                            <li>
                                <div class="short-by text-center">
                                    <form id="sortForm">
                                        <select name="sort" id="sortSelect">
                                            <option value="">Sắp xếp mặc định</option>
                                            <option value="popularity">Phổ biến nhất</option>
                                            <option value="new">Hàng mới về</option>
                                            <option value="price_asc">Giá: thấp đến cao</option>
                                            <option value="price_desc">Giá: cao đến thấp</option>

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
                                            <div class="ltn__product-item ltn__product-item-3 text-center"
                                                style="height: 300px; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;">
                                                {{-- fix tràn ô  --}}
                                                <div class="product-img">
                                                    <a href="#">
                                                        <img src="{{ asset('upload/' . $product->thumbnail) }}"
                                                            alt="{{ $product->name }}"
                                                            style="width: 250px; height: 200px; object-fit: cover;">
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
                                                    <h2 class="product-title"><a
                                                            href="{{ route('products.productct', $product->id) }}">{{ $product->name }}</a></h2><h2 class="product-title"><a href="{{ route('products.productct', $product->id) }}">{{ $product->name }}</a>
                                                    </h2>
                                                    <div class="product-price">
                                                        @php
                                                            $salePrice = $product->variants
                                                                ->where('sale_price', '>', 0)
                                                                ->min('sale_price');
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
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="ltn__pagination-area text-center">
                        <div class="ltn__pagination">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
                <div class="col-lg-4  mb-120">
                    <aside class="sidebar ltn__shop-sidebar ltn__right-sidebar">
                        <!-- Category Widget -->
                        <div class="widget ltn__menu-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Danh mục sản phẩm</h4>
                            <ul>
                                @foreach ($categories as $category)
                                    <li><a
                                            href="{{ route('category.show', parameters: ['categoryId' => $category->id]) }}">{{ $category->name }}
                                            <span><i class="fas fa-long-arrow-alt-right"></i></span></a></li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Search Widget -->
                        <div class="widget ltn__search-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Tên sản phẩm</h4>
                            <input type="text" id="search-input" placeholder="Nhập tên sản phẩm...">
                        </div>

                        <!-- Brands Widget -->
                        <div class="widget ltn__tagcloud-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Thương hiệu</h4>
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
                            <h4 class="ltn__widget-title ltn__widget-title-border">Giá tiền</h4>
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
                                        ${product.min_sale_price > 0 ? 
                                            `<li class="sale-badge">-${Math.round(((product.min_price - product.min_sale_price) / product.min_price) * 100)}%</li>` 
                                            : ''
                                        }
                                    </ul>
                                </div>
                                <div class="product-hover-action">
                                    <ul>
                                        <li>
                                            <a href="#" class="quick-view-btn" data-id="${product.id}" title="Quick View" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                                <i class="far fa-eye"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="product-info">
                                <h2 class="product-title"><a href="product-details.html">${product.name}</a></h2>
                                <div class="product-price">
                                    ${product.min_sale_price > 0 ? 
                                        `<span>${new Intl.NumberFormat().format(product.min_sale_price)}đ</span> 
                                                                <del>${new Intl.NumberFormat().format(product.min_price)}đ</del>` :
                                        `<span>${new Intl.NumberFormat().format(product.min_price)}đ</span>`
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
                        $('#quick_view_modal').attr('data-product-id', response
                            .id);
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
                                let shapeAttr = variant.attributes.find(attr => attr
                                    .attribute?.name
                                    .includes('Hình'));
                                let weightAttr = variant.attributes.find(attr => attr
                                    .attribute
                                    ?.name.includes('Khối'));

                                let variantName = [shapeAttr?.value, weightAttr?.value]
                                    .filter(
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
                            $('#quick_view_modal .modal-product-variants .variant-list').html(
                                variantsHtml);

                            // Xóa sự kiện cũ và gắn sự kiện mới
                            $('.variant-btn').off('click').on('click', function() {
                                const variantPrice = $(this).data('sale-price') || $(
                                    this).data(
                                    'price');
                                const variantStock = $(this).data('stock');

                                // Cập nhật giá chính
                                $('#quick_view_modal .product-price span').text(
                                    new Intl.NumberFormat().format(variantPrice) +
                                    'đ'
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
                            $('#quick_view_modal .modal-product-variants .variant-list').html(
                                '');
                            $('.cart-plus-minus-box')
                                .val(1)
                                .attr('max', response.stock || 0)
                                .prop('disabled', false);
                            $('#quick-add-to-cart-btn').prop('disabled', false);
                        }

                        $('#quick_view_modal').modal(
                            'show'); // Hiển thị modal sau khi load xong
                    },
                    error: function(xhr) {
                        console.log("Lỗi khi tải dữ liệu:", xhr);
                    }
                });
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
                        console.log(response.cart_items);
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

        .variant-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            /* tương đương gap-2 của Bootstrap */
            margin-top: 0.5rem;
            padding-bottom: 20px
                /* tương đương mt-2 */
        }
    </style>
@endpush
