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
                                        @php
                                            // Lọc các biến thể còn hàng
                                            $availableVariants = $product->variants->filter(function ($variant) {
                                                return $variant->stock > 0;
                                            });

                                            // Nếu không có biến thể còn hàng thì bỏ qua sản phẩm này
                                            if ($availableVariants->isEmpty()) {
                                                continue;
                                            }

                                            $salePrice = $availableVariants
                                                ->where('sale_price', '>', 0)
                                                ->min('sale_price');
                                            $regularPrice = $availableVariants->min('price');
                                        @endphp

                                        <div class="col-xl-4 col-sm-6 col-6">
                                            <div class="ltn__product-item ltn__product-item-3 text-center"
                                                style="height: 300px; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;">
                                                <div class="product-img">
                                                    <a href="{{ route('products.productct', $product->id) }}">
                                                        <img src="{{ asset('upload/' . $product->thumbnail) }}"
                                                            alt="{{ $product->name }}"
                                                            style="width: 250px; height: 200px; object-fit: cover;">
                                                    </a>

                                                    <div class="product-badge">
                                                        <ul>
                                                            @if (!empty($salePrice) && $salePrice > 0)
                                                                @php
                                                                    $discount = round(
                                                                        (($regularPrice - $salePrice) / $regularPrice) *
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
                                                    <h2 class="product-title">
                                                        <a
                                                            href="{{ route('products.productct', $product->id) }}">{{ $product->name }}</a>
                                                    </h2>
                                                    <div class="product-price">
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
        const routes = {
            category_show: '{{ route('category.show', ':id') }}'
        };
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
                        console.log("Response:", response);

                        // Kiểm tra cấu trúc của response
                        if (response.products && response.products.data) {
                            let productsHtml = response.products.data.map(product => `
                                <div class="col-xl-4 col-sm-6 col-6">
                                    <div class="ltn__product-item ltn__product-item-3 text-center">
                                        <div class="product-img">
                                            <a href="/products/${product.id}/productct">
                                                <img src="/upload/${product.thumbnail}" alt="${product.name}">
                                            </a>
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
                                            <h2 class="product-title">
                                                <a href="/products/${product.id}/productct">${product.name}</a>
                                            </h2>
                                            <div class="product-price">
                                                ${product.min_sale_price > 0 ? 
                                                    `<span>${new Intl.NumberFormat('vi-VN').format(product.min_sale_price)}đ</span>
                                                                    <del>${new Intl.NumberFormat('vi-VN').format(product.min_price)}đ</del>` :
                                                    `<span>${new Intl.NumberFormat('vi-VN').format(product.min_price)}đ</span>`
                                                }
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `).join('');

                            $('#product-list').html(productsHtml);

                            // Cập nhật phân trang nếu có
                            if (response.products.links) {
                                $('.ltn__pagination').html(response.products.links);
                            }
                        } else {
                            $('#product-list').html(
                                '<div class="col-12"><p class="text-center">Không tìm thấy sản phẩm phù hợp</p></div>'
                            );
                        }
                    },
                    error: function(xhr) {
                        console.error("Lỗi khi tìm kiếm:", xhr);
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
                        $('#quick_view_modal').attr('data-product-id', response
                            .id);
                        $('#quick_view_modal .modal-product-img').html(`
                            <a href="{{ route('products.productct', ':id') }}" target="_blank">
                                <img src="/upload/${response.thumbnail}" alt="${response.name}" class="w-full h-auto rounded-lg">
                            </a>
                        `.replace(':id', response.id));

                        $('#quick_view_modal h3').text(response.name);
                        if (response.variants && response.variants.length > 0) {
                            // Tìm giá thấp nhất trong các variants
                            let minPrice = Math.min(...response.variants.map(v => v.price));
                            let minSalePrice = Math.min(...response.variants
                                .filter(v => v.sale_price > 0)
                                .map(v => v.sale_price) || [0]);

                            // Hiển thị giá
                            if (minSalePrice > 0) {
                                $('#quick_view_modal .product-price').html(`
                        <span style="font-size: 24px">${new Intl.NumberFormat('vi-VN').format(minSalePrice)}đ</span>
                        <del style="font-size: 18px">${new Intl.NumberFormat('vi-VN').format(minPrice)}đ</del>
                    `);
                            } else {
                                $('#quick_view_modal .product-price').html(`
                        <span style="font-size: 24px">${new Intl.NumberFormat('vi-VN').format(minPrice)}đ</span>
                    `);
                            }
                        }

                        let categoriesHtml = response.categories.map(category =>
                            `<a href="${routes.category_show.replace(':id', category.id)}" class="category-link">
                                ${category.name}
                            </a>`
                        ).join(", ");
                        $('#quick_view_modal .modal-product-meta span').html(categoriesHtml);

                        // Làm mới danh sách biến thể
                        if (response.variants && response.variants.length > 0) {
                            let variantsHtml = '<div class="variant-buttons">';

                            let availableVariants = response.variants.filter(v => v.stock > 0);

                            // Trong phần success của ajax quick view
                            if (availableVariants.length > 0) {
                                let variantsHtml = '<div class="variant-buttons">';

                                // Tìm variant có giá thấp nhất (kể cả không có sale_price)
                                let lowestPriceVariant = availableVariants.reduce((lowest,
                                    current) => {
                                    // Lấy giá cuối cùng của mỗi variant (sale_price nếu có, không thì lấy price)
                                    let currentFinalPrice = current.sale_price > 0 ?
                                        current
                                        .sale_price : current.price;
                                    let lowestFinalPrice = lowest.sale_price > 0 ?
                                        lowest
                                        .sale_price : lowest.price;

                                    // So sánh để tìm variant có giá cuối cùng thấp nhất
                                    return currentFinalPrice < lowestFinalPrice ?
                                        current : lowest;
                                }, availableVariants[0]);

                                // Chỉ hiển thị các variant còn hàng
                                variantsHtml += availableVariants.map((variant) => {
                                    let shapeAttr = variant.attributes.find(attr => attr
                                        .attribute
                                        ?.name.includes('Hình'));
                                    let weightAttr = variant.attributes.find(attr =>
                                        attr.attribute
                                        ?.name.includes('Khối'));
                                    let variantName = [shapeAttr?.value, weightAttr
                                        ?.value
                                    ].filter(
                                        Boolean).join(' ') || 'Không có thuộc tính';

                                    // Đánh dấu active cho variant có giá thấp nhất
                                    let isLowestPrice = variant.id ===
                                        lowestPriceVariant.id ?
                                        'active' : '';

                                    return `
                                        <button class="btn btn-outline-primary variant-btn ${isLowestPrice}"
                                            data-product-id="${response.id}"
                                            data-variant-id="${variant.id}"
                                            data-price="${variant.price}"
                                            data-sale-price="${variant.sale_price}"
                                            data-stock="${variant.stock}">
                                            ${variantName}
                                        </button>
                                    `;
                                }).join('');

                                variantsHtml += '</div>';
                                $('#quick_view_modal .modal-product-variants .variant-list')
                                    .html(
                                        variantsHtml);

                                // Tự động trigger click vào variant có giá thấp nhất
                                setTimeout(() => {
                                    $(`.variant-btn[data-variant-id="${lowestPriceVariant.id}"]`)
                                        .trigger('click');
                                }, 100);
                            }

                            // Xóa sự kiện cũ và gắn sự kiện mới
                            $('.variant-btn').off('click').on('click', function() {
                                const variantPrice = $(this).data('price');
                                const variantSalePrice = $(this).data('sale-price');
                                const variantStock = $(this).data('stock');

                                // Cập nhật hiển thị giá
                                if (variantSalePrice > 0) {
                                    $('#quick_view_modal .product-price').html(`
                                    <span style="font-size: 24px">${new Intl.NumberFormat('vi-VN').format(variantSalePrice)}đ</span>
                                    <del style="font-size: 18px">${new Intl.NumberFormat('vi-VN').format(variantPrice)}đ</del>
                                `);
                                } else {
                                    $('#quick_view_modal .product-price').html(`
                                    <span style="font-size: 24px">${new Intl.NumberFormat('vi-VN').format(variantPrice)}đ</span>
                                `);
                                }

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

        // Xử lý sự kiện click cho variant buttons
        $(document).off('click', '.variant-btn').on('click', '.variant-btn', function() {
            const variantPrice = $(this).data('price');
            const variantSalePrice = $(this).data('sale-price');
            const variantStock = $(this).data('stock');

            // Cập nhật hiển thị giá
            if (variantSalePrice > 0) {
                $('#quick_view_modal .product-price').html(`
            <span style="font-size: 24px">${new Intl.NumberFormat('vi-VN').format(variantSalePrice)}đ</span>
            <del style="font-size: 18px">${new Intl.NumberFormat('vi-VN').format(variantPrice)}đ</del>
        `);
            } else {
                $('#quick_view_modal .product-price').html(`
            <span style="font-size: 24px">${new Intl.NumberFormat('vi-VN').format(variantPrice)}đ</span>
        `);
            }

            // Cập nhật trạng thái nút và số lượng
            if (variantStock > 0) {
                $('.cart-plus-minus-box')
                    .val(1)
                    .attr('max', variantStock)
                    .prop('disabled', false);
                $('#quick-add-to-cart-btn').prop('disabled', false);

                // Kích hoạt nút tăng nếu số lượng < stock
                $('.inc').removeClass('disabled').css({
                    'opacity': '1',
                    'cursor': 'pointer',
                    'pointer-events': 'auto'
                });
            } else {
                $('.cart-plus-minus-box')
                    .val(0)
                    .attr('max', 0)
                    .prop('disabled', true);
                $('#quick-add-to-cart-btn').prop('disabled', true);

                // Vô hiệu hóa nút tăng nếu hết hàng
                $('.inc').addClass('disabled').css({
                    'opacity': '0.5',
                    'cursor': 'not-allowed',
                    'pointer-events': 'none'
                });
            }

            $('.variant-btn').removeClass('active');
            $(this).addClass('active');
        });

        $(document).off('click', '.qtybutton').on('click', '.qtybutton', function(e) {
            if ($(this).hasClass('disabled')) {
                e.preventDefault();
                return false;
            }

            let $input = $(this).siblings('input.cart-plus-minus-box');
            let currentValue = parseInt($input.val());
            let maxStock = parseInt($input.attr('max'));

            if ($(this).hasClass('inc')) {
                // Kiểm tra nếu đã đạt max stock
                if (currentValue >= maxStock) {
                    $(this).addClass('disabled').css({
                        'opacity': '0.5',
                        'cursor': 'not-allowed',
                        'pointer-events': 'none'
                    });
                    Toastify({
                        text: `Chỉ còn ${maxStock} sản phẩm trong kho!`,
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "#ff4444"
                        }
                    }).showToast();
                    return false;
                }

                // Kiểm tra sau khi tăng
                if (currentValue >= maxStock) {
                    $(this).addClass('disabled').css({
                        'opacity': '0.5',
                        'cursor': 'not-allowed',
                        'pointer-events': 'none'
                    });
                }
            } else if ($(this).hasClass('dec') && currentValue > 1) {
                // Kích hoạt lại nút tăng
                $('.inc').removeClass('disabled').css({
                    'opacity': '1',
                    'cursor': 'pointer',
                    'pointer-events': 'auto'
                });
            }
        });

        $(document).on('click', '#quick-add-to-cart-btn', function(e) {
            e.preventDefault();
            @if (!auth()->check())
                Toastify({
                    text: "Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#ff4444",
                        color: "white"
                    },
                    onClick: function() {
                        window.location.href = '{{ route('login') }}';
                    }
                }).showToast();
                return;
            @endif
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
                                            <span class="mini-cart-variant">${item.variant_name || 'Mặc định'}</span><br>
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
                            style: {
                                background: "#4caf50"
                            },
                            stopOnFocus: true
                        }).showToast();
                        $('#quick_view_modal').modal('hide');
                    }
                },
                error: function(xhr) {
                    Toastify({
                        text: "Có lỗi xảy ra, vui lòng thử lại!",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "#ff4444",
                            color: "white"
                        }
                    }).showToast();

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

        .variant-btn {
            padding: 5px 15px;
            font-size: 14px;
            margin: 5px;
            min-width: 80px;
            border: 1px solid #ddd;
            background: white;
            transition: all 0.3s ease;
        }

        .variant-btn.active {
            background: #2196F3;
            color: white;
            border-color: #2196F3;
        }

        .variant-btn:hover {
            background: #e9ecef;
            border-color: #dee2e6;
        }

        .variant-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 10px;
        }
    </style>
@endpush
