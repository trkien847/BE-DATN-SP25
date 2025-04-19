@extends('admin.layouts.layout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div> 
    <audio id="backgroundMusic" autoplay>
        <source src="{{ asset('audio/Champions 2022.mp3') }}" type="audio/mpeg">
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
    .select2-container .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 5px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d;
    }
    select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        background-color: #fff;
        appearance: none;
        background-image: url("data:image/svg+xml;base64,...");
        background-repeat: no-repeat;
        background-position: right 10px center;
    }
    .table thead th {
        color: #5d7186;
        background-color: rgb(243, 243, 243);
        text-align: center;
        vertical-align: middle;
    }
    .table tbody tr:hover {
        background-color: rgb(15, 15, 15);
    }
    .table img {
        object-fit: cover;
        border-radius: 5px;
    }
    .content-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .show-full-content {
        color: #007bff;
        cursor: pointer;
        text-decoration: underline;
    }
    .show-full-content:hover {
        color: #0056b3;
    }
    .search-bar {
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .search-bar input {
        flex: 1;
        border: 2px solid rgb(255, 255, 255);
        border-radius: 5px;
        padding: 0.5rem;
    }
    .search-bar button {
        background-color: #1e84c4;
        border-color: #1e84c4;
        color: #fff;
    }
    .variant-container {
        position: relative;
    }
    .variant-item {
        padding: 5px;
        transition: background-color 0.3s ease;
    }
    .variant-item:hover {
        background-color: #f5f5f5;
    }
    .variant-list {
        margin-top: 10px;
    }
    .variant-count .badge {
        font-size: 12px;
        padding: 5px 8px;
    }
    .variant-count {
        margin-top: 5px;
    }
    .search-bar button:hover {
        background-color: rgb(179, 0, 9);
        border-color: rgb(179, 0, 9);
    }
    .ripple {
        position: relative;
        overflow: hidden;
    }
    .ripple-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        pointer-events: none;
        z-index: 9999;
    }
    .ripple-effect {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 193, 7, 0.5);
        transform: scale(0);
        animation: rippleFull 0.8s ease-out;
        opacity: 1;
    }
    @keyframes rippleFull {
        to {
            transform: scale(20);
            opacity: 0;
        }
    }
    .shake-effect {
        display: inline-block;
        animation: shakeAndScale 0.6s ease-in-out forwards;
    }
    @keyframes shakeAndScale {
        0% {
            transform: scale(1);
        }
        20% {
            transform: scale(1.2) rotate(5deg);
        }
        40% {
            transform: scale(1.2) rotate(-5deg);
        }
        60% {
            transform: scale(1.2) rotate(5deg);
        }
        80% {
            transform: scale(1.2) rotate(-5deg);
        }
        100% {
            transform: scale(1.5);
            opacity: 0;
        }
    }
    .rainbow-text {
        background: #313b5e;
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        background-size: 200% 100%;
        animation: rainbowMove 5s linear infinite;
    }
    @keyframes rainbowMove {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: 0 0;
        }
    }
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #ffffff;
        z-index: 9999;
        display: none;
        justify-content: center;
        align-items: center;
    }
    .loading-overlay.active {
        display: flex;
    }
    .loading-content {
        text-align: center;
    }
    .loading-text {
        font-size: 3rem;
        font-weight: bold;
        color: #1a73e8;
    }
    .loading-text span {
        display: inline-block;
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.5s ease forwards;
    }
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .loading-text span:nth-child(1) { animation-delay: 0.1s; }
    .loading-text span:nth-child(2) { animation-delay: 0.2s; }
    .loading-text span:nth-child(3) { animation-delay: 0.3s; }
    .loading-text span:nth-child(4) { animation-delay: 0.4s; }
    .loading-text span:nth-child(5) { animation-delay: 0.5s; }
    .loading-text span:nth-child(6) { animation-delay: 0.6s; }
    .loading-text span:nth-child(7) { animation-delay: 0.7s; }
    .loading-text span:nth-child(8) { animation-delay: 0.8s; }
    .loading-text span:nth-child(9) { animation-delay: 0.9s; }
    .loading-text span:nth-child(10) { animation-delay: 1.0s; }
    .loading-text span:nth-child(11) { animation-delay: 1.1s; }
    .loading-text span:nth-child(12) { animation-delay: 1.2s; }
    .loading-text span:nth-child(13) { animation-delay: 1.3s; }
    .loading-text span:nth-child(14) { animation-delay: 1.4s; }

    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 20000;
        justify-content: center;
        align-items: center;
        border: 5px solid red; /* Debug */
    }
    .overlay-content {
        background: white !important;
        padding: 20px;
        border-radius: 8px;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        border: 3px solid blue; /* Debug */
    }
    .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: red;
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 3px;
    }
    .import-date {
        background-color: yellow;
        padding: 2px 5px;
        margin-right: 5px;
        cursor: pointer;
        border-radius: 3px;
    }
</style>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK'
        });
    });
</script>
@endif

<div id="loading-overlay" class="loading-overlay">
    <div class="loading-content">
        <div class="loading-text">
            <span>D</span><span>e</span><span>s</span><span>i</span><span>g</span><span>n</span><span>e</span><span>d</span><span>&nbsp;</span><span>b</span><span>y</span><span>&nbsp;</span><span>T</span><span>G</span>
        </div>
    </div>
</div>

<div class="container">
    <div class="d-flex flex-wrap justify-content-between gap-3">
        <h4 class="rainbow-text">DANH SÁCH SẢN PHẨM</h4>
        <div class="d-flex flex-wrap justify-content-between gap-3">
            <a href="{{ route('products.add') }}" class="btn btn-animate shake-link add-product-btn" style="background: #1bb394; color: white;">
                <i class="bi bi-plus-circle"></i><i class="bx bx-plus me-1"></i>
                Thêm Sản Phẩm
            </a>
            <a href="{{ route('products.hidden') }}" class="btn btn-secondary">
                <i class="bx bx-hide me-1"></i>
                Xem Sản Phẩm Đã Ẩn
            </a>
        </div>
    </div>

    <div class="d-flex flex-wrap justify-content-between gap-3">
        <form action="{{ route('products.list') }}" method="GET" class="search-bar">
            <span><i class="bx bx-search-alt"></i></span>
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Tìm Kiếm
            </button>
        </form>

        <table class="table table-hover table-bordered align-middle">
            <thead>
                <tr>
                    <th scope="col">Mã SP</th>
                    <th scope="col">Tên Sản Phẩm</th>
                    <th scope="col">Số lần nhập</th>
                    <th scope="col">Ảnh</th>
                    <th scope="col">Danh mục</th>
                    <th scope="col">Trạng Thái</th>
                    <th scope="col">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr class="product-row" data-id="{{ $product->id }}">
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->name }}</td>
                        <td>
                            @php
                                $importCount = 1;
                                $currentDate = \Carbon\Carbon::create(2025, 4, 19);
                            @endphp
                            @if($product->importProducts->isEmpty())
                                No imports found
                            @else
                                @foreach($product->importProducts as $importProduct)
                                    @php
                                        $expiryDate = \Carbon\Carbon::parse($importProduct->expiry_date);
                                        $dateDiff = $currentDate->diffInMonths($expiryDate);
                                        $bgColor = 'red';
                                        if ($dateDiff > 24) {
                                            $bgColor = 'green';
                                        } elseif ($dateDiff > 8) {
                                            $bgColor = 'yellow';
                                        }
                                    @endphp
                                    <span 
                                        class="import-date" 
                                        style="background-color: {{ $bgColor }};" 
                                        onclick="showDetails('details-{{ $product->id }}-{{ $importCount }}')"
                                    >
                                        {{ $importCount }}
                                    </span>
                                    @php
                                        $importCount++;
                                    @endphp
                                @endforeach
                            @endif
                        </td>
                        <td>
                            <img src="{{ asset('upload/'.$product->thumbnail) }}" class="img-thumbnail" alt="Product Image" width="100px" height="100px">
                        </td>
                        <td>
                            @foreach($product->categories as $category)
                                <div>
                                    <span class="category-name" style="cursor: pointer;" onclick="toggleSubcategories({{ $category->id }})">
                                        {{ $category->name }}
                                    </span>
                                    @if($category->categoryTypes->isNotEmpty())
                                        <div id="subcategories-{{ $category->id }}" style="display: none; margin-left: 20px;">
                                            @foreach($category->categoryTypes as $categoryType)
                                                <div>{{ $categoryType->name }}</div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </td>
                        <td>
                            <span class="badge {{ $product->variants_sum_stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->variants_sum_stock > 0 ? 'Còn Hàng' : 'Hết Hàng' }}
                                {{ $product->variants_sum_stock }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm ripple">
                                <i class="bx bx-edit fs-16"></i>
                            </a>
                            <a href="{{ route('products.productct', $product->id) }}" class="btn btn-info btn-sm" title="Chi tiết sản phẩm">
                                <i class="bx bx-detail fs-16"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-secondary btn-sm">
                                    <i class="bx bx-hide fs-16"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <nav aria-label="Page navigation">
        {{ $products->links('pagination::bootstrap-5') }}
    </nav>
</div>

@foreach($products as $product)
    @php
        $importCount = 1;
    @endphp
    @foreach($product->importProducts as $importProduct)
        @if($importProduct->import)
            <div id="details-{{ $product->id }}-{{ $importCount }}" class="overlay">
                <div class="overlay-content">
                    <button class="close-btn" onclick="closeDetails('details-{{ $product->id }}-{{ $importCount }}')">Close</button>
                    <h4>Thông tin lô nhập Designed by TG</h4>
                    <p><strong>Ngày nhập lô:</strong> {{ $importProduct->import->import_date }}</p>
                    <p><strong>Người nhập:</strong> {{ $importProduct->import->user->fullname ?? 'N/A' }}</p>
                    <p><strong>Số lượng nhập:</strong> {{ $importProduct->import->total_quantity }}</p>
                    <p><strong>Tổng giá:</strong> {{ $importProduct->import->total_price }}</p>
                    
                    <h4>Thông tin ngày sản xuất</h4>
                    <p><strong>NSX:</strong> {{ $importProduct->manufacture_date }}</p>
                    <p>
                        <strong>HSD:</strong> 
                        @php
                            $currentDate = \Carbon\Carbon::create(2025, 4, 19); 
                            $expiryDate = \Carbon\Carbon::parse($importProduct->expiry_date);
                            $monthsRemaining = $currentDate->diffInMonths($expiryDate, false); 
                        @endphp
                        @if($monthsRemaining < 0)
                            Hết Hạn (Expired)
                        @elseif($monthsRemaining < 8)
                            Hết Hạn
                        @else
                            Còn {{ $monthsRemaining }} tháng
                        @endif
                        ({{ $importProduct->expiry_date }})
                    </p>
              
                    <h4>Thông tin biến thể</h4>
                    @foreach($importProduct->importProductVariants as $variant)
                        <p><strong>Tên biến thể:</strong> {{ $variant->variant_name }}</p>
                        <p><strong>Số lượng:</strong> {{ $variant->quantity }}</p>
                        <p><strong>Giá Nhập:</strong> {{ $variant->import_price }}</p>
                        <p><strong>Tống giá trị:</strong> {{ $variant->total_price }}</p>
                        <hr>
                    @endforeach
                </div>
            </div>
        @else
            <div>No import data for import product {{ $importProduct->id }}</div>
        @endif
        @php
            $importCount++;
        @endphp
    @endforeach
@endforeach

<script>
    function showDetails(divId) {
        console.log('Showing details for: ' + divId);
        document.querySelectorAll('.overlay').forEach(div => {
            div.style.display = 'none';
        });
        const targetDiv = document.getElementById(divId);
        if (targetDiv) {
            targetDiv.style.display = 'flex';
            console.log('Overlay display set to: ' + targetDiv.style.display);
        } else {
            console.error('Overlay not found: ' + divId);
        }
    }

    function closeDetails(divId) {
        console.log('Closing details for: ' + divId);
        const targetDiv = document.getElementById(divId);
        if (targetDiv) {
            targetDiv.style.display = 'none';
        }
    }

    function toggleSubcategories(categoryId) {
        const subcategories = document.getElementById('subcategories-' + categoryId);
        if (subcategories.style.display === 'none') {
            subcategories.style.display = 'block';
        } else {
            subcategories.style.display = 'none';
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addProductBtn = document.querySelector('.add-product-btn');
        const loadingOverlay = document.getElementById('loading-overlay');

        if (addProductBtn) {
            addProductBtn.addEventListener('click', function(e) {
                e.preventDefault();
                loadingOverlay.classList.add('active');
                
                setTimeout(() => {
                    window.location.href = this.href;
                }, 2000);
            });
        }
    });
</script>
@endsection