@extends('admin.layouts.layout')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" />
<form id="importForm" method="POST" action="{{ route('admin.imports.store') }}" enctype="multipart/form-data">
@csrf
<div class="container">
    <div class="panel">
        <h2><i class="fas fa-box"></i> Danh Sách Sản Phẩm</h2>
        <a href="javascript:void(0)" id="add-product-btn" class="btn-add-product">
            <i class="fas fa-plus"></i> Thêm Sản Phẩm
        </a>
        
        <!-- Product selection modal -->
        <div id="productModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Chọn Sản Phẩm</h3>
                    <span class="close-product">&times;</span>
                </div>
                <div class="search-box">
                    <input type="text" placeholder="Tìm kiếm sản phẩm..." id="search-product">
                </div>
                <div class="product-list">
                @foreach($products as $product)
                <div class="product-item">
                    <div class="product-header">
                        <div class="product-main">
                            <input type="checkbox" id="product-{{ $product->id }}" class="product-checkbox" 
                                data-id="{{ $product->id }}" 
                                data-name="{{ $product->name }}"
                                data-variants='@json($product->variants)'>
                            <label for="product-{{ $product->id }}">
                                {{ $product->name }}
                                <span class="variant-count">({{ count($product->variants) }} biến thể)</span>
                            </label>
                        </div>
                    </div>

                    <div class="variants-container hidden">
                        <div class="variants-controls">
                            <!-- Search bar on top -->
                            <div class="variants-search">
                                <input type="text" placeholder="Tìm biến thể..." class="variant-search-input">
                            </div>
                            <!-- Buttons row below -->
                            <div class="variants-actions">    
                                <div class="select-all-variants">
                                    <input type="checkbox" id="select-all-{{ $product->id }}" class="select-all-checkbox">
                                    <label for="select-all-{{ $product->id }}">Chọn tất cả</label>
                                </div>
                                <button class="remove-selection-btn hidden" title="Bỏ chọn">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="variants-list">
                            @foreach($product->variants as $variant)
                            <div class="variant-item">
                                <input type="checkbox" id="variant-{{ $variant->id }}" 
                                    class="variant-checkbox"
                                    data-variant-id="{{ $variant->id }}"
                                    data-variant-name="{{ $variant->name }}">
                                <label for="variant-{{ $variant->id }}">
                                    @foreach($variant->attributeValues as $value)
                                        {{ $value->attribute->name }}: {{ $value->attribute->slug }}{{ $value->value }}
                                        @if(!$loop->last), @endif
                                    @endforeach
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
                @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-submit" id="confirm-products">Xác nhận</button>
                    <button type="button" class="btn-cancel" id="cancel-products">Hủy</button>
                </div>
            </div>
        </div>

        <!-- Selected products list -->
        <div id="selected-products" class="selected-products">
            <!-- Selected products will be displayed here -->
        </div>

    </div>

    <div class="panel">
        <h2><i class="fas fa-file-import"></i> Thông Tin Nhập Hàng</h2>
        <div class="user-info">
            <div class="header-row">
                <div class="info-label">Người Nhập</div>
                <p><i class="fas fa-user"></i> <span id="user-name">{{ auth()->user()->fullname }}</span></p>
                <a href="javascript:void(0)" id="view-more-btn" class="view-more-btn">
                    <i class="fas fa-info-circle"></i> Chi Tiết
                </a>
            </div>
            <div id="user-details" class="user-details hidden">
                <p><i class="fas fa-phone"></i> {{ auth()->user()->phone_number }}</p>
                <p><i class="fas fa-envelope"></i> {{ auth()->user()->email }}</p>
                <p><i class="fas fa-user-shield"></i> {{ auth()->user()->role->name ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="import-info">
            <div class="info-label">Ngày Nhập</div>
            <input type="date" id="import-date" name="import_date" value="{{ date('Y-m-d') }}">
            
            <div class="image-upload-container">
                <div class="info-label">Ảnh chứng từ</div>
                <div class="image-preview-area">
                    <label for="proof-image" class="upload-label">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Chọn ảnh hoặc kéo thả vào đây</span>
                    </label>
                    <input type="file" 
                        id="proof-image" 
                        name="proof_image" 
                        accept="image/*"
                        class="proof-image-input" 
                        hidden>
                    <div id="image-preview" class="image-preview hidden">
                        <img src="#" alt="Preview" id="preview-img">
                        <button type="button" class="remove-image" id="remove-image">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="supplier-info">
            <div class="supplier-header">
                <div class="info-label">Nhà Cung Cấp</div>
                <a href="javascript:void(0)" id="add-supplier-btn" class="btn-add-supplier">
                    <i class="fas fa-plus"></i> Thêm Mới
                </a>
            </div>
            <select id="supplier-select">
                <option value="">Chọn Nhà Cung Cấp</option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
            <div id="supplier-details" class="supplier-details hidden">

            </div>
        </div>

        <button type="submit" form="importForm" class="btn-submit-import">
            <i class="fas fa-save"></i> Lưu Phiếu Nhập
        </button>

    </div>
    </div>
    </form>


<div class="modal" id="supplierModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Thêm Nhà Cung Cấp Mới</h3>
                <span class="close">&times;</span>
            </div>
            <form id="supplierForm">
                @csrf
                <div class="form-group">
                    <label for="name">Tên nhà cung cấp <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input type="text" id="phone" name="phone">
                </div>
                <div class="form-group">
                    <label for="address">Địa chỉ</label>
                    <textarea id="address" name="address"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-submit">Lưu</button>
                    <button type="button" class="btn-cancel">Hủy</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    :root {
        --primary-color: #1a73e8;
        --primary-hover: #1557b0;
        --secondary-color: #4285f4;
        --background-color: #f0f6ff;
        --card-background: #ffffff;
        --text-color: #333333;
        --border-radius: 12px;
        --shadow: 0 4px 6px rgba(26, 115, 232, 0.1);
        --transition: all 0.3s ease;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        padding: 20px;
    }

    .panel {
        background: var(--card-background);
        padding: 25px;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        transition: var(--transition);
    }

    .image-upload-container {
        margin-top: 20px;
    }

    .image-preview-area {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        margin-top: 10px;
        position: relative;
        min-height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }

    .image-preview-area.drag-over {
        border-color: var(--primary-color);
        background: #f0f7ff;
    }

    .upload-label {
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        color: #666;
    }

    .upload-label i {
        font-size: 2em;
        color: var(--primary-color);
    }

    .image-preview {
        position: relative;
        max-width: 100%;
        margin-top: 10px;
    }

    .image-preview.hidden {
        display: none;
    }

    #preview-img {
        max-width: 100%;
        max-height: 300px;
        border-radius: 6px;
        object-fit: contain;
    }

    .remove-image {
        position: absolute;
        top: -10px;
        right: -10px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        padding: 0;
        font-size: 12px;
    }

    .remove-image:hover {
        background: #bd2130;
        transform: scale(1.1);
    }

    .btn-add-supplier {
        padding: 8px 15px;
        font-size: 0.9em;
        background-color: var(--primary-color);
        color: white;
        border-radius: 6px;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-add-supplier:hover {
        background-color: var(--primary-hover);
        transform: scale(1.05);
        color: white;
        text-decoration: none;
    }

    .btn-add-supplier i {
        font-size: 1.1em;
    }

    .view-more-btn {
        margin: 0;
        padding: 8px 15px;
        font-size: 0.9em;
        white-space: nowrap;
        background-color: var(--primary-color);
        color: white;
        border-radius: 6px;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .view-more-btn:hover {
        background-color: var(--primary-hover);
        transform: scale(1.05);
        color: white;
        text-decoration: none;
    }

    .view-more-btn i {
        font-size: 1.1em;
    }

    .btn-add-product {
        width: 100%;
        margin-bottom: 20px;
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none; /* Thêm style cho thẻ a */
        font-size: 14px;
    }

    .btn-add-product:hover {
        background-color: var(--primary-hover);
        transform: scale(1.05);
        color: white; /* Giữ màu chữ khi hover */
        text-decoration: none; /* Không gạch chân khi hover */
    }

    .btn-add-product i {
        font-size: 1.2em;
    }

    .btn-submit-import {
        width: 100%;
        margin-top: 20px;
        padding: 15px;
        background-color: var(--primary-color);
        color: white;
        font-size: 16px;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .btn-submit-import:hover {
        background-color: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-submit-import i {
        font-size: 1.2em;
    }

    .panel:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    h2 {
        color: var(--primary-color);
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--primary-color);
    }

    .user-info,
    .import-info,
    .supplier-info {
        margin-bottom: 30px;
        padding: 15px;
        border-radius: 8px;
        background: #f8f9fa;
    }

    .user-details {
        margin-top: 15px;
        padding: 15px;
        border-left: 3px solid var(--secondary-color);
        background: #fff;
        transition: var(--transition);
        opacity: 1;
    }

    .user-details.hidden {
        opacity: 0;
        height: 0;
        padding: 0;
        margin: 0;
        overflow: hidden;
    }

    button {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    button:hover {
        background-color: var(--primary-hover);
        transform: scale(1.05);
    }

    input[type="date"],
    select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        margin-top: 5px;
        font-size: 14px;
    }

    .product-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
    }

    .product-item {
        background: white;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #eee;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .product-item:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .product-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--primary-color);
    }

    .info-label {
        font-weight: 600;
        color: #666;
        margin-bottom: 5px;
    }

    .supplier-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .supplier-header .info-label {
        margin-bottom: 0;
    }

    .supplier-info select {
        margin-bottom: 10px;
    }

    .supplier-header button {
        padding: 8px 15px;
        font-size: 0.9em;
    }

    .supplier-details {
        margin-top: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        transition: var(--transition);
    }

    .supplier-details.hidden {
        display: none;
    }

    /* Search box styling */
    .search-box {
        margin-bottom: 20px;
    }

    .search-box input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }

    /* Scrollbar styling  user-info p */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border-radius: var(--border-radius);
        width: 500px;
        position: relative;
        animation: slideIn 0.3s ease;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .close {
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .modal-footer {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-submit {
        background-color: var(--primary-color);
    }

    .btn-cancel {
        background-color: #dc3545;
    }

    @keyframes slideIn {
        from {
            transform: translateY(-100px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .btn-add-product {
        width: 100%;
        margin-bottom: 20px;
    }

    .selected-products {
        margin-top: 20px;
    }

    .selected-product-item {
        background: white;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #eee;
        margin-bottom: 15px;
    }

    .selected-product-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .selected-product-form {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-top: 10px;
    }

    .select-all-variants {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-input {
        width: 100%;
    }

    .form-input label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #666;
    }

    .form-input input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .remove-product {
        color: #dc3545;
        cursor: pointer;
        background: none;
        border: none;
        padding: 5px;
    }

    .remove-product:hover {
        background: none;
        color: #bd2130;
        transform: scale(1.1);
    }

    .remove-product {
        color: #dc3545;
        cursor: pointer;
        background: none;
        border: none;
        padding: 8px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .remove-product:hover {
        background-color: #dc3545;
        color: white;
        transform: rotate(90deg);
    }

    .remove-product i {
        font-size: 1.2em;
    }

    .remove-selection-btn {
        padding: 5px 10px;
        color: #dc3545;
        display: none;
    }

    .remove-selection-btn.visible {
        display: flex;
        align-items: center;
    }

    .remove-selection-btn i {
        font-size: 1.1em;
    }

    .selected-product-header h4 {
        margin: 0;
        color: var(--primary-color);
    }
    
    .user-info {
        position: relative;
    }

    .user-info .header-row {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 10px;
    }

    .user-info .info-label {
        margin: 0;
        white-space: nowrap;
        font-weight: 600;
        color: #666;
    }

    .user-info p {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
    }

    #view-more-btn {
        margin: 0;
        padding: 8px 15px;
        font-size: 0.9em;
        white-space: nowrap;
    }

    .user-details {
        margin-top: 15px;
        padding: 15px;
        border-left: 3px solid var(--secondary-color);
        background: #fff;
        transition: var(--transition);
        opacity: 1;
        width: 100%;
    }

    .user-details.hidden {
        opacity: 0;
        height: 0;
        padding: 0;
        margin: 0;
        overflow: hidden;
    }

    .product-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        background: #fff;
        cursor: pointer;
    }

    .product-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
        max-height: 60vh;
        overflow-y: auto;
        padding-right: 10px;
    }

    .product-item {
        background: white;
        border: 1px solid #eee;
        border-radius: 8px;
        transition: var(--transition);
        width: 100%;
    }

    .product-main {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
    }

    .remove-selection-btn {
        background: none;
        border: none;
        color: #dc3545;
        padding: 5px;
        cursor: pointer;
        display: none;
    }

    .remove-selection-btn.visible {
        display: block;
    }

    .remove-selection-btn:hover {
        color: #bd2130;
        transform: scale(1.1);
    }

    .variants-container.hidden {
        display: none !important;
    }

    .variants-container {
        padding: 0 15px 15px 15px;
        border-top: 1px solid #eee;
        background: #f8f9fa;
    }

    .variant-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px;
        background: white;
        border-radius: 6px;
        transition: background-color 0.2s;
    }

    .variant-item:hover {
        background-color: #f0f0f0;
    }

    .product-checkbox,
    .variant-checkbox {
        width: 18px;
        height: 18px;
        accent-color: var(--primary-color);
    }

    .variants-controls {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 15px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 6px;
    }

    .variants-search {
        width: 100%;
    }

    .variants-search input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .variants-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .select-all-variants {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .variants-list {
        margin-top: 10px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding-left: 20px;
    }

</style>
@endpush

@push('scripts')
<script>
    // Copy toàn bộ JavaScript từ file HTML cũ và thêm các chức năng mới
    document.addEventListener('DOMContentLoaded', () => {
        const viewMoreBtn = document.getElementById('view-more-btn');
        const userDetails = document.getElementById('user-details');
        const supplierSelect = document.getElementById('supplier-select');
        const supplierDetails = document.getElementById('supplier-details');
        const addSupplierBtn = document.getElementById('add-supplier-btn');
        const searchInput = document.getElementById('search-product');

        const modal = document.getElementById('supplierModal');
        const closeBtn = document.querySelector('.close');
        const cancelBtn = document.querySelector('.btn-cancel');
        const supplierForm = document.getElementById('supplierForm');

        const addProductBtn = document.getElementById('add-product-btn');
        const productModal = document.getElementById('productModal');
        const closeProductBtn = document.querySelector('.close-product');
        const cancelProductsBtn = document.getElementById('cancel-products');
        const confirmProductsBtn = document.getElementById('confirm-products');
        const selectedProductsContainer = document.getElementById('selected-products');
        const productCheckboxes = document.querySelectorAll('.product-checkbox');
        // Image Upload Preview
        const imageInput = document.getElementById('proof-image');
        const imagePreview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
        const removeImageBtn = document.getElementById('remove-image');
        const uploadArea = document.querySelector('.image-preview-area');
        // Toggle user details with animation
        viewMoreBtn.addEventListener('click', () => {
            userDetails.classList.toggle('hidden');
        });

       //Chức năng lấy thông tin nhà cung cấp  
        supplierSelect.addEventListener('change', async () => {
            const supplierId = supplierSelect.value;
            if (supplierId) {
                try {
                    const response = await fetch(`/suppliers/${supplierId}`);
                    const supplier = await response.json();
                    document.getElementById('supplier-details').innerHTML = `
                        <p><i class="fas fa-map-marker-alt"></i> ${supplier.address}</p>
                        <p><i class="fas fa-envelope"></i> ${supplier.email}</p>
                        <p><i class="fas fa-phone"></i> ${supplier.phone}</p>
                    `;
                    document.getElementById('supplier-details').classList.remove('hidden');
                } catch (error) {
                    console.error('Error:', error);
                }
            } else {
                document.getElementById('supplier-details').classList.add('hidden');
            }
        });

        // Add new supplier
        addSupplierBtn.addEventListener('click', () => {
            modal.style.display = 'block';
        });

        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        cancelBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target == modal) {
                modal.style.display = 'none';
            }
        });

        //
        supplierForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(supplierForm);
            try {
                const response = await fetch('/admin/suppliers', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const supplier = await response.json();
                
                // Thêm supplier mới vào select box
                const option = new Option(supplier.name, supplier.id);
                supplierSelect.add(option);
                
                // Chọn supplier mới thêm
                supplierSelect.value = supplier.id;
                
                // Hiển thị thông tin chi tiết
                document.getElementById('supplier-details').innerHTML = `
                    <p><i class="fas fa-map-marker-alt"></i> ${supplier.address || 'N/A'}</p>
                    <p><i class="fas fa-envelope"></i> ${supplier.email || 'N/A'}</p>
                    <p><i class="fas fa-phone"></i> ${supplier.phone || 'N/A'}</p>
                `;
                document.getElementById('supplier-details').classList.remove('hidden');

                // Đóng modal và reset form
                modal.style.display = 'none';
                supplierForm.reset();

                // Hiển thị thông báo thành công
                alert('Thêm nhà cung cấp thành công!');

            } catch (error) {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thêm nhà cung cấp!');
            }
        });

        // Show/hide product modal
        addProductBtn.addEventListener('click', () => {
            productModal.style.display = 'block';
        });

        closeProductBtn.addEventListener('click', () => {
            productModal.style.display = 'none';
        });

        cancelProductsBtn.addEventListener('click', () => {
            productModal.style.display = 'none';
        });

        // Thêm vào phần DOMContentLoaded
        document.querySelectorAll('.product-header').forEach(header => {
            header.addEventListener('click', (e) => {
                if (!e.target.matches('input[type="checkbox"]')) {
                    const container = header.nextElementSibling;
                    const btn = header.querySelector('.view-variants-btn i');
                    container.classList.toggle('hidden');
                    btn.style.transform = container.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
                }
            });
        });

        // Xử lý chọn tất cả biến thể
        document.querySelectorAll('.select-all-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const variantsList = this.closest('.variants-container')
                    .querySelector('.variants-list');
                const variants = variantsList.querySelectorAll('.variant-checkbox');
                variants.forEach(v => v.checked = this.checked);
            });
        });

        // Tìm kiếm biến thể
        document.querySelectorAll('.variant-search-input').forEach(input => {
            input.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const variants = this.closest('.variants-container')
                    .querySelectorAll('.variant-item');
                
                variants.forEach(variant => {
                    const text = variant.querySelector('label').textContent.toLowerCase();
                    variant.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        });

        // Handle product selection DOMContentLoaded
        confirmProductsBtn.addEventListener('click', () => {
            const checkedProducts = document.querySelectorAll('.product-checkbox:checked');
            
            if (checkedProducts.length === 0) {
                alert('Vui lòng chọn ít nhất một sản phẩm!');
                return;
            }

            let hasSelectedVariants = false;
            
            checkedProducts.forEach(checkbox => {
                const productId = checkbox.dataset.id;
                const productName = checkbox.dataset.name;
                
                // Kiểm tra và thêm sản phẩm
                addSelectedProduct(productId, productName);
            });
        });

        function addSelectedProduct(productId, productName) {
            const productElement = document.querySelector(`#product-${productId}`);
            if (!productElement) {
                console.error('Không tìm thấy sản phẩm:', productId);
                return;
            }

            const selectedVariants = productElement.closest('.product-item')
                .querySelectorAll('.variant-checkbox:checked');

            if (selectedVariants.length === 0) {
                alert(`Vui lòng chọn ít nhất một biến thể cho sản phẩm "${productName}"!`);
                return;
            }

            selectedVariants.forEach(variantCheckbox => {
                const variantId = variantCheckbox.dataset.variantId;
                
                // Kiểm tra xem biến thể đã được thêm chưa
                if (document.querySelector(`.selected-product-item[data-variant-id="${variantId}"]`)) {
                    console.log('Biến thể đã được thêm:', variantId);
                    return;
                }

                const productHtml = `
                    <div class="selected-product-item" data-product-id="${productId}" data-variant-id="${variantId}">
                        <div class="selected-product-header">
                            <div class="product-info">
                                <h4>${productName}</h4>
                                <p class="variant-info">Biến thể: ${variantCheckbox.nextElementSibling.textContent}</p>
                            </div>
                            <button class="remove-product" data-product-id="${productId}" data-variant-id="${variantId}" title="Xóa sản phẩm">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                        <div class="selected-product-form">
                            <div class="form-input">
                                <label>Giá nhập</label>
                                <input type="number" name="products[${productId}][variants][${variantId}][price]" required min="0">
                            </div>
                            <div class="form-input">
                                <label>Số lượng</label>
                                <input type="number" name="products[${productId}][variants][${variantId}][quantity]" required min="1">
                            </div>
                            <div class="form-input">
                                <label>Ngày sản xuất</label>
                                <input type="date" name="products[${productId}][variants][${variantId}][manufacture_date]" required>
                            </div>
                            <div class="form-input">
                                <label>Ngày hết hạn</label>
                                <input type="date" name="products[${productId}][variants][${variantId}][expiry_date]" required>
                            </div>
                        </div>
                    </div>
                `;
                
                selectedProductsContainer.insertAdjacentHTML('beforeend', productHtml);

                // Thêm event listener cho nút xóa
                const newRemoveButton = selectedProductsContainer.querySelector(
                    `.remove-product[data-variant-id="${variantId}"]`
                );
                
                if (newRemoveButton) {
                    newRemoveButton.addEventListener('click', function() {
                        removeProduct(this);
                    });
                }
            });

            // Đóng modal sau khi thêm thành công
            productModal.style.display = 'none';
        }

        function removeProduct(button) {
            const productItem = button.closest('.selected-product-item');
            const variantId = button.dataset.variantId;
            
            // Bỏ check checkbox của variant trong modal
            const variantCheckbox = document.querySelector(`.variant-checkbox[data-variant-id="${variantId}"]`);
            if (variantCheckbox) {
                variantCheckbox.checked = false;
            }
            
            productItem.remove();
        }

       // Cập nhật lại event listener cho product checkbox
        document.querySelectorAll('.product-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const productItem = this.closest('.product-item');
                const variantsContainer = productItem.querySelector('.variants-container');
                const removeBtn = productItem.querySelector('.remove-selection-btn');
                
                if (this.checked) {
                    variantsContainer.classList.remove('hidden');
                    removeBtn.classList.add('visible');
                } else {
                    variantsContainer.classList.add('hidden');
                    removeBtn.classList.remove('visible');
                    // Uncheck all variants
                    variantsContainer.querySelectorAll('.variant-checkbox').forEach(v => v.checked = false);
                    // Uncheck select all
                    variantsContainer.querySelector('.select-all-checkbox').checked = false;
                }
            });
        });

        // Handle drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('drag-over');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('drag-over');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('drag-over');
        
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            handleImageUpload(e.dataTransfer.files[0]);
        }
    });

    // Handle file input change
    imageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            handleImageUpload(this.files[0]);
        }
    });

    // Remove image
    removeImageBtn.addEventListener('click', () => {
        imageInput.value = '';
        imagePreview.classList.add('hidden');
        previewImg.src = '#';
        uploadArea.querySelector('.upload-label').style.display = 'flex';
    });

    function handleImageUpload(file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Vui lòng chọn file ảnh!');
            return;
        }

        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('Kích thước ảnh không được vượt quá 5MB!');
            return;
        }

        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            imagePreview.classList.remove('hidden');
            uploadArea.querySelector('.upload-label').style.display = 'none';
        }
        
        reader.readAsDataURL(file);
    }

        document.querySelectorAll('.remove-selection-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const productItem = this.closest('.product-item');
                const checkbox = productItem.querySelector('.product-checkbox');
                checkbox.checked = false;
                // Trigger the change event
                checkbox.dispatchEvent(new Event('change'));
            });
        });

        // Improve variant search functionality
        document.querySelectorAll('.variant-search-input').forEach(input => {
            input.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const variantsList = this.closest('.variants-container')
                    .querySelector('.variants-list');
                
                variantsList.querySelectorAll('.variant-item').forEach(item => {
                    const text = item.querySelector('label').textContent.toLowerCase();
                    item.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        });

        function removeProduct(button) {
            const productItem = button.closest('.selected-product-item');
            const productId = productItem.dataset.productId;
            
            // Uncheck the checkbox in the modal
            const checkbox = document.querySelector(`#product-${productId}`);
            if (checkbox) {
                checkbox.checked = false;
            }
            
            productItem.remove();
        }

        // Search functionality
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const productItems = document.querySelectorAll('.product-item');

            productItems.forEach(item => {
                const productName = item.querySelector('label').textContent.toLowerCase();
                if (productName.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });


        const importForm = document.getElementById('importForm');
    
        importForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    try {
        const importDate = document.getElementById('import-date').value;
        const supplierId = document.getElementById('supplier-select').value;
        const imageFile = document.getElementById('proof-image').files[0];
        const selectedProducts = document.querySelectorAll('.selected-product-item');

        // Validations
        if (!importDate) {
            alert('Vui lòng chọn ngày nhập hàng!');
            return;
        }

        if (!supplierId) {
            alert('Vui lòng chọn nhà cung cấp!');
            return;
        }

        if (!imageFile) {
            alert('Vui lòng chọn ảnh chứng từ!');
            return;
        }

        if (selectedProducts.length === 0) {
            alert('Vui lòng chọn ít nhất một sản phẩm!');
            return;
        }

        const formData = new FormData();
        formData.append('import_date', importDate);
        formData.append('supplier_id', supplierId);
        formData.append('proof_image', imageFile);

        // Build products data
        const productsData = {};
        selectedProducts.forEach(product => {
            const productId = product.dataset.productId;
            const variantId = product.dataset.variantId;
            const priceInput = product.querySelector('input[name$="[price]"]');
            const quantityInput = product.querySelector('input[name$="[quantity]"]');
            const manufactureDateInput = product.querySelector('input[name$="[manufacture_date]"]');
            const expiryDateInput = product.querySelector('input[name$="[expiry_date]"]');

            if (!productsData[productId]) {
                productsData[productId] = {
                    variants: {}
                };
            }

            // Validate required inputs
            if (!priceInput.value || !quantityInput.value || 
                !manufactureDateInput.value || !expiryDateInput.value) {
                throw new Error('Vui lòng điền đầy đủ thông tin cho tất cả sản phẩm');
            }

            productsData[productId].variants[variantId] = {
                price: parseFloat(priceInput.value),
                quantity: parseInt(quantityInput.value),
                manufacture_date: manufactureDateInput.value,
                expiry_date: expiryDateInput.value
            };
        });

        // Convert products data to JSON string and append to formData
        formData.append('products', JSON.stringify(productsData));

        // Debug log
        console.log('Sending data:', {
            import_date: importDate,
            supplier_id: supplierId,
            products: productsData
        });

        const response = await fetch(importForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            alert('Nhập hàng thành công!');
            window.location.href = '/admin/imports';
        } else {
            throw new Error(result.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Có lỗi xảy ra: ' + error.message);
    }
});


    });



</script>
@endpush