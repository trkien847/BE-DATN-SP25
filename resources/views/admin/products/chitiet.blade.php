@extends('admin.layouts.layout')
@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<style>
    @keyframes fadeDown {
        0% {
            opacity: 0;
            transform: translateY(-10px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-down-enter {
        animation: fadeDown 0.6s ease forwards;
    }

    /* Fade in/out for images */
    .fade-image {
        transition: opacity 0.5s ease;
        opacity: 1;
    }

    .fade-image.fade-out {
        opacity: 0;
    }

    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }

    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.3s ease;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const images = @json($images);

        let currentIndex = 0;

        const mainImage = document.getElementById("main-image");
        const mainImageAlt = document.getElementById("main-image-alt");
        const prevBtn = document.getElementById("prev-btn");
        const nextBtn = document.getElementById("next-btn");
        const thumbnails = document.querySelectorAll(".thumbnail-image");

        function fadeToImage(index) {
            if (index === currentIndex) return;
            mainImage.classList.add("fade-out");
            setTimeout(() => {
                currentIndex = index;
                mainImage.src = '{{ asset("upload/") }}/' + images[index].src;
                mainImage.alt = images[index].alt;
                mainImageAlt.textContent = images[index].alt;
                mainImage.classList.remove("fade-out");
                thumbnails.forEach((thumb, i) => {
                    if (i === index) {
                        thumb.classList.add("border-indigo-700", "border-2");
                    } else {
                        thumb.classList.remove("border-indigo-700", "border-2");
                    }
                });
            }, 500);
        }

        prevBtn.addEventListener("click", () => {
            let newIndex = currentIndex - 1;
            if (newIndex < 0) newIndex = images.length - 1;
            fadeToImage(newIndex);
        });

        nextBtn.addEventListener("click", () => {
            let newIndex = currentIndex + 1;
            if (newIndex >= images.length) newIndex = 0;
            fadeToImage(newIndex);
        });

        thumbnails.forEach((thumb, i) => {
            thumb.addEventListener("click", () => {
                fadeToImage(i);
            });
        });

        const tabButtons = document.querySelectorAll("[data-tab-button]");
        const tabContents = document.querySelectorAll("[data-tab-content]");

        tabButtons.forEach((btn) => {
            btn.addEventListener("click", () => {
                const target = btn.getAttribute("data-tab-button");

                tabButtons.forEach((b) => {
                    if (b === btn) {
                        b.classList.add(
                            "border-indigo-700",
                            "text-indigo-700",
                            "font-semibold",
                            "border-b-2"
                        );
                        b.classList.remove("border-transparent");
                        b.setAttribute("aria-current", "page");
                    } else {
                        b.classList.remove(
                            "border-indigo-700",
                            "text-indigo-700",
                            "font-semibold",
                            "border-b-2"
                        );
                        b.classList.add("border-transparent");
                        b.removeAttribute("aria-current");
                    }
                });

                tabContents.forEach((content) => {
                    if (content.getAttribute("data-tab-content") === target) {
                        content.classList.remove("hidden");
                        content.classList.add("fade-down-enter");
                        content.addEventListener(
                            "animationend",
                            () => {
                                content.classList.remove("fade-down-enter");
                            }, {
                                once: true
                            }
                        );
                    } else {
                        content.classList.add("hidden");
                        content.classList.remove("fade-down-enter");
                    }
                });
            });
        });

        mainImage.classList.add("fade-image");
        thumbnails.forEach((thumb, i) => {
            if (i === 0) {
                thumb.classList.add("border-indigo-700", "border-2");
            }
        })
    });
</script>
</head>

<body class="bg-white font-sans text-gray-900">
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Left side: main image and thumbnails -->
            <div class="flex flex-col">
                <div class="relative rounded-lg overflow-hidden max-w-md md:max-w-lg">
                    <img
                        alt="{{ $images[0]['alt'] }}"
                        class="w-full h-auto object-cover rounded-lg fade-image"
                        height="600"
                        id="main-image"
                        src="{{ asset('upload/' . $images[0]['src']) }}"
                        width="600" />
                    <span class="sr-only" id="main-image-alt">{{ $images[0]['alt'] }}</span>
                    <button
                        aria-label="Previous image"
                        class="absolute top-1/2 left-2 -translate-y-1/2 bg-white bg-opacity-90 hover:bg-opacity-100 text-gray-700 rounded-full w-8 h-8 flex items-center justify-center shadow"
                        id="prev-btn"
                        type="button">
                        <i class="fas fa-arrow-left text-sm"></i>
                    </button>
                    <button
                        aria-label="Next image"
                        class="absolute top-1/2 right-2 -translate-y-1/2 bg-white bg-opacity-90 hover:bg-opacity-100 text-gray-700 rounded-full w-8 h-8 flex items-center justify-center shadow"
                        id="next-btn"
                        type="button">
                        <i class="fas fa-arrow-right text-sm"></i>
                    </button>
                </div>
                <div class="flex gap-4 mt-4">
                    @foreach($images as $index => $image)
                    <img
                        alt="{{ $image['alt'] }}"
                        class="w-20 h-20 rounded-lg object-cover cursor-pointer thumbnail-image"
                        height="80"
                        src="{{ asset('upload/' . $image['src']) }}"
                        width="80"
                        data-index="{{ $index }}" />
                    @endforeach
                </div>
            </div>
            <!-- Right side: tabs and product info -->
            <div class="flex-1 border border-gray-100 rounded-lg p-6">
                <nav
                    class="flex space-x-8 border-b border-gray-200 text-gray-700 text-sm font-normal mb-6 overflow-x-auto">
                    <button
                        class="pb-2 whitespace-nowrap border-indigo-700 text-indigo-700 font-semibold border-b-2"
                        data-tab-button="info-thuoc"
                        type="button"
                        aria-current="page">
                        Thông tin thuốc
                    </button>
                    <button
                        class="pb-2 border-b border-transparent hover:border-gray-300 whitespace-nowrap"
                        data-tab-button="info-lo"
                        type="button">
                        Thông tin lô
                    </button>
                    <button
                        class="pb-2 border-b border-transparent hover:border-gray-300 whitespace-nowrap"
                        data-tab-button="bien-the"
                        type="button">
                        Biến thể
                    </button>
                    <button
                        class="pb-2 border-b border-transparent hover:border-gray-300 whitespace-nowrap"
                        data-tab-button="danh-gia"
                        type="button">
                        Đánh giá của sản phẩm
                    </button>
                    <button
                        class="pb-2 border-b border-transparent hover:border-gray-300 whitespace-nowrap"
                        data-tab-button="thong-ke"
                        type="button">
                        Thống kê
                    </button>
                </nav>
                <div data-tab-content="info-thuoc" class="">
                    <h2 class="text-xl font-extrabold mb-4">{{$product->name}}</h2>
                    <section>
                        <h3 class="font-semibold mb-4">Thông tin thuốc</h3>
                        <div class="grid grid-cols-2 gap-x-12 gap-y-4 text-sm text-gray-500 mb-6">
                            <div>
                                <p class="mb-1">Danh mục</p>
                                <p class="font-bold text-gray-900">{{ $product->categories->isNotEmpty() ? $product->categories->first()->name : 'Không có danh mục' }}</p>
                            </div>
                            <div>
                                <p class="mb-1">Thương hiệu</p>
                                <p class="font-bold text-gray-900">{{ $product->brand->name ?? 'Không có thương hiệu' }}</p>
                            </div>
                            <div>
                                <p class="mb-1">Mã sản phẩm</p>
                                <p class="font-bold text-gray-900">{{$product->sku}}</p>
                            </div>
                            <div>
                                <p class="mb-1">Số lượng còn lại</p>
                                <p class="font-bold text-gray-900">
                                    {{ $product->variants_sum_stock > 0 ? 'Còn Hàng' : 'Hết Hàng' }}
                                    {{ $product->variants_sum_stock }}
                                </p>
                            </div>
                        </div>
                    </section>
                    <section>
                        <h3 class="font-semibold mb-3">Mô tả</h3>
                        <div id="description-content-{{ $product->id }}">
                            @php
                            $decodedContent = html_entity_decode($product->content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                            $contentText = strip_tags($decodedContent);
                            $words = array_filter(explode(' ', $contentText));
                            $shortContentWords = array_slice($words, 0, 100);
                            $shortContent = implode(' ', $shortContentWords);
                            $totalWords = count($words);
                            $fullContent = $decodedContent;
                            @endphp

                            <div class="space-y-2 text-gray-700 text-sm short-content">
                                {{ $shortContent }}@if($totalWords > 100) ...@endif
                            </div>

                            <div class="space-y-2 text-gray-700 text-sm full-content hidden">
                                {!! $fullContent !!}
                            </div>

                            @if($totalWords > 100)
                            <button
                                class="toggle-description-btn text-indigo-600 hover:text-indigo-800 font-medium mt-2"
                                data-target="description-content-{{ $product->id }}">
                                Xem thêm
                            </button>
                            @endif
                        </div>
                    </section>
                </div>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const toggleButtons = document.querySelectorAll(".toggle-description-btn");

                        toggleButtons.forEach(button => {
                            button.addEventListener("click", function() {
                                const targetId = this.getAttribute("data-target");
                                const targetElement = document.getElementById(targetId);
                                const shortContent = targetElement.querySelector(".short-content");
                                const fullContent = targetElement.querySelector(".full-content");

                                if (fullContent.classList.contains("hidden")) {
                                    fullContent.classList.remove("hidden");
                                    shortContent.classList.add("hidden");
                                    this.textContent = "Thu gọn";
                                } else {
                                    fullContent.classList.add("hidden");
                                    shortContent.classList.remove("hidden");
                                    this.textContent = "Xem thêm";
                                }
                            });
                        });
                    });
                </script>
                <div data-tab-content="info-lo" class="hidden">
                    <h2 class="text-xl font-extrabold mb-4">Thông tin lô hàng</h2>
                    @forelse($product->importProducts as $importProduct)
                    @if($importProduct->import)
                    <section class="border border-gray-300 rounded-lg p-4 mb-4">
                        <h3 class="font-semibold mb-4">Chi tiết lô sản phẩm {{ $importProduct->import->import_code }}</h3>

                        <!-- Thông tin phiếu nhập -->
                        <div class="import-info">
                            <h4 class="font-semibold mb-2">Thông tin phiếu nhập (Designed by TG)</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Mã nhập:</strong> {{ $importProduct->import->import_code ?? 'N/A' }}</p>
                                    <p><strong>Ngày nhập:</strong> {{ \Carbon\Carbon::parse($importProduct->import->import_date)->format('d/m/Y') }}</p>
                                    <p><strong>Người nhập:</strong> {{ $importProduct->import->user->fullname ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Nhà cung cấp:</strong> {{ $importProduct->import->supplier->name ?? 'Công ty Dược phẩm ABC' }}</p>
                                    <p><strong>Số điện thoại:</strong> {{ $importProduct->import->supplier->phone ?? 'N/A' }}</p>
                                    <p><strong>Địa chỉ:</strong> {{ $importProduct->import->supplier->address ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Nút Xem chi tiết -->
                        @if($importProduct->importProductVariants->isNotEmpty())
                        <button
                            class="toggle-details-btn bg-indigo-600 text-white px-4 py-2 rounded-lg mt-4 hover:bg-indigo-700"
                            data-target="product-details-{{ $importProduct->id }}">
                            Xem chi tiết
                        </button>

                        <!-- Chi tiết sản phẩm (ẩn ban đầu) -->
                        <div id="product-details-{{ $importProduct->id }}" class="products-info mt-4 hidden">
                            <h4 class="font-semibold mb-2">Chi tiết sản phẩm</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Biến thể</th>
                                            <th>Số lượng</th>
                                            <th>Giá nhập</th>
                                            <th>NSX</th>
                                            <th>HSD</th>
                                            <th>Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($importProduct->importProductVariants as $variant)
                                        <tr>
                                            <td>{{ $variant->variant_name }}</td>
                                            <td>{{ $variant->quantity }}</td>
                                            <td>{{ number_format($variant->import_price, 0, ',', '.') }}đ</td>
                                            <td>{{ $importProduct->manufacture_date }}</td>
                                            <td>{{ $importProduct->expiry_date }}</td>
                                            <td>{{ number_format($variant->total_price, 0, ',', '.') }}đ</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <hr>
                                    <tfoot>
                                        <tr>
                                            <td colspan="1"><strong>Tổng cộng:</strong></td>
                                            <td><strong>{{ number_format($importProduct->import->total_quantity, 0, ',', '.') }}</strong></td>
                                            <td colspan="2"></td>
                                            <td><strong>{{ number_format($importProduct->import->total_price, 0, ',', '.') }}đ</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        @endif
                    </section>
                    @else
                    <p>No import data for import product {{ $importProduct->id }}</p>
                    @endif
                    @empty
                    <p>Không có thông tin lô hàng.</p>
                    @endforelse
                </div>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const toggleButtons = document.querySelectorAll(".toggle-details-btn");

                        toggleButtons.forEach(button => {
                            button.addEventListener("click", function() {
                                const targetId = this.getAttribute("data-target");
                                const targetElement = document.getElementById(targetId);

                                if (targetElement.classList.contains("hidden")) {
                                    targetElement.classList.remove("hidden");
                                    targetElement.classList.add("fade-enter-active");
                                    this.textContent = "Ẩn chi tiết";
                                } else {
                                    targetElement.classList.add("fade-leave-active");
                                    setTimeout(() => {
                                        targetElement.classList.add("hidden");
                                    }, 300);
                                    this.textContent = "Xem chi tiết";
                                }
                            });
                        });
                    });
                </script>
                <div data-tab-content="bien-the" class="hidden">
                    <h2 class="text-xl font-extrabold mb-4">Biến thể sản phẩm</h2>
                    <section>
                        <h3 class="font-semibold mb-4">Các biến thể có sẵn</h3>
                        @if($product->variants->isEmpty())
                        <p class="text-gray-500 text-sm">Không có biến thể nào</p>
                        @else
                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-sm">
                            @foreach($product->variants as $variant)
                            @php
                            // Lấy giá trị hình dạng (attribute_id = 12)
                            $shapeValue = $variant->attributeValues->firstWhere('attribute_id', 12);
                            // Lấy giá trị trọng lượng (attribute_id = 14)
                            $weightValue = $variant->attributeValues->firstWhere('attribute_id', 14);
                            // Lấy giá trị số lượng viên (giả sử attribute_id = 15)
                            $quantityValue = $variant->attributeValues->firstWhere('attribute_id', 15);

                            // Tạo tên biến thể
                            $variantName = $shapeValue && $weightValue
                            ? "{$shapeValue->value} {$weightValue->value}"
                            : $variant->attributeValues
                            ->map(fn($av) => "{$av->attribute->name}: {$av->value}")
                            ->join(', ');

                            // Số lượng viên (nếu không có attribute_id 15, có thể dùng $variant->stock hoặc giá trị mặc định)
                            $quantity = $quantityValue ? $quantityValue->value : ($variant->stock ?? 'N/A');

                            // Giá bán (ưu tiên sale_price, nếu không có thì dùng price)
                            $price = $variant->sale_price ?? $variant->price ?? 'N/A';
                            if ($price !== 'N/A') {
                            $price = number_format($price, 0, ',', '.') . 'đ';
                            }

                            // Số lượng trong kho
                            $stock = $variant->stock ?? 'N/A';
                            @endphp
                            <li class="{{ $variant->stock == 0 ? 'text-gray-400' : '' }}">
                                {{ $variantName ?: 'Không có thuộc tính' }}<br>
                                <strong>Giá bán:</strong> {{ $price }}<br>
                                <strong>Số lượng trong kho:</strong> {{ $stock }}
                                @if($variant->stock == 0)
                                (Hết hàng)
                                @endif
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </section>
                </div>
                <div data-tab-content="danh-gia" class="hidden">
                    <h2 class="text-xl font-extrabold mb-4">Đánh giá của sản phẩm (đang bảo chì)</h2>
                    <section>
                        <h3 class="font-semibold mb-4">Nhận xét từ khách hàng</h3>
                        <ul class="space-y-4 text-gray-700 text-sm">
                            <li class="border border-gray-200 rounded p-4">
                                <p class="font-semibold">Nguyễn Văn A</p>
                                <p>Thuốc hiệu quả, giá cả hợp lý. Sẽ mua lại.</p>
                                <p class="text-xs text-gray-500">Ngày 10/04/2024</p>
                            </li>
                            <li class="border border-gray-200 rounded p-4">
                                <p class="font-semibold">Trần Thị B</p>
                                <p>Giao hàng nhanh, thuốc đúng mô tả.</p>
                                <p class="text-xs text-gray-500">Ngày 08/04/2024</p>
                            </li>
                            <li class="border border-gray-200 rounded p-4">
                                <p class="font-semibold">Lê Văn C</p>
                                <p>Chưa thấy tác dụng rõ rệt, cần dùng thêm thời gian.</p>
                                <p class="text-xs text-gray-500">Ngày 05/04/2024</p>
                            </li>
                        </ul>
                    </section>
                </div>
                <div data-tab-content="thong-ke" class="hidden">
                    <h2 class="text-xl font-extrabold mb-4">Thống kê sản phẩm (đang bảo chì)</h2>
                    <section>
                        <h3 class="font-semibold mb-4">Thống kê bán hàng và đánh giá</h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-sm mb-6">
                            <li>Tổng số hộp bán ra: 10,000</li>
                            <li>Đánh giá trung bình: 4.2/5 sao</li>
                            <li>Số lượng đánh giá: 256</li>
                            <li>Tỷ lệ hài lòng: 89%</li>
                        </ul>
                        <div>
                            <p class="font-semibold mb-2">Biểu đồ đánh giá (giả lập):</p>
                            <div class="space-y-1 max-w-xs">
                                <div class="flex items-center">
                                    <span class="w-12 text-xs">5 sao</span>
                                    <div class="bg-indigo-700 h-4 rounded" style="width: 60%"></div>
                                    <span class="ml-2 text-xs">60%</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-12 text-xs">4 sao</span>
                                    <div class="bg-indigo-500 h-4 rounded" style="width: 20%"></div>
                                    <span class="ml-2 text-xs">20%</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-12 text-xs">3 sao</span>
                                    <div class="bg-indigo-300 h-4 rounded" style="width: 10%"></div>
                                    <span class="ml-2 text-xs">10%</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-12 text-xs">2 sao</span>
                                    <div class="bg-gray-300 h-4 rounded" style="width: 5%"></div>
                                    <span class="ml-2 text-xs">5%</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-12 text-xs">1 sao</span>
                                    <div class="bg-gray-200 h-4 rounded" style="width: 5%"></div>
                                    <span class="ml-2 text-xs">5%</span>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    @endsection