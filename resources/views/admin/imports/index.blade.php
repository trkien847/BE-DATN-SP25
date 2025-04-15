@extends('admin.layouts.layout')

@section('content')
<div> 
            <audio id="backgroundMusic" autoplay>
                <source src="{{ asset('audio/Fukashigi no KARTE.mp3') }}" type="audio/mpeg">
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div id="loading-overlay" class="loading-overlay">
        <div class="loading-content">
            <div class="loading-text">Designed by TG</div>
            <div class="loading-animation"></div>
        </div>
    </div>
<div class="container-fluid">
    <div class="imports-header">
        <h1><i class="fas fa-file-import"></i> Danh Sách Phiếu Nhập</h1>
        <a href="{{ route('admin.imports.create') }}" class="btn-create" style="background: #1bb394;">
            <i class="fas fa-plus"></i> Tạo Phiếu Nhập
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã Nhập</th>
                            <th>Ngày Nhập</th>
                            <th>Người Nhập</th>
                            <th>Nhà Cung Cấp</th>
                            <th>Trạng Thái</th>
                            <th>Tổng SL</th>
                            <th>Tổng Tiền</th>
                            <th>Ảnh minh chứng</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($imports as $import)
                        <tr>
                            <td>{{ $import->import_code }}</td>
                            <td>{{ date('d/m/Y', strtotime($import->import_date)) }}</td>
                            <td>{{ $import->user->fullname }}</td>
                            <td>{{ $import->supplier->name }}</td>
                            <td>
                                <span class="status-badge {{ $import->is_confirmed ? 'confirmed' : 'pending' }}">
                                    {{ $import->is_confirmed ? 'Đã xác nhận' : 'Chờ xác nhận' }}
                                </span>
                            </td>
                            <td>{{ number_format($import->total_quantity) }}</td>
                            <td>{{ number_format($import->total_price) }}đ</td>
                            <td>
                                @if($import->proof_image)
                                    @php
                                        $files = json_decode($import->proof_image, true);
                                    @endphp
                                    <div class="proof-files">
                                        @foreach($files as $file)
                                            @php
                                                $extension = pathinfo($file, PATHINFO_EXTENSION);
                                                $isPdf = strtolower($extension) === 'pdf';
                                            @endphp
                                            
                                            @if($isPdf)
                                                <a href="{{ asset('upload/imports/' . $file) }}"
                                                target="_blank"
                                                class="proof-file-link pdf-file"
                                                title="Xem PDF">
                                                    <i class="fas fa-file-pdf pdf-icon"></i>
                                                </a>
                                            @else
                                                <a href="javascript:void(0)" 
                                                onclick="showImagePreview('{{ asset('upload/imports/' . $file) }}')"
                                                class="proof-file-link image-file">
                                                    <img src="{{ asset('upload/imports/' . $file) }}" 
                                                        alt="Minh chứng" 
                                                        class="proof-image-thumb">
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="no-image">Không có tệp</span>
                                @endif
                            </td>
                            <td>
                                <a href="javascript:void(0)" 
                                   class="btn-detail"
                                   data-import-id="{{ $import->id }}"
                                   onclick="showImportDetail({{ $import->id }})" style="background: #1bb394;">
                                    <i class="fas fa-eye"></i> Chi tiết
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $imports->links() }}
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="importDetailModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi Tiết Phiếu Nhập ( Designed by TG )</h5>
            </div>
            <div class="modal-body">
                <div id="importDetail">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div id="imagePreviewModal" class="image-preview-modal">
    <div class="image-preview-content">
        <button type="button" class="close-preview" onclick="closeImagePreview()">
            <i class="fas fa-times"></i>
        </button>
        <img src="" alt="Preview" id="previewImage">
    </div>
</div>
@endsection

@push('styles')
<style>
    .imports-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .btn-create {
        padding: 10px 20px;
        background: #1a73e8;
        color: white;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .proof-files {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .proof-file-link {
        display: inline-block;
        position: relative;
        width: 50px;
        height: 50px;
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #ffffff;
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .loading-overlay.active {
        display: flex;
        opacity: 1;
    }

    .loading-content {
        text-align: center;
    }

    .loading-text {
        font-size: 2.5rem;
        font-weight: bold;
        color: #1a73e8;
        margin-bottom: 20px;
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.5s ease forwards;
    }

    .loading-animation {
        width: 50px;
        height: 50px;
        border: 3px solid #f3f3f3;
        border-radius: 50%;
        border-top: 3px solid #1a73e8;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Add hover effect for create button */
    .btn-create {
        position: relative;
        overflow: hidden;
    }

    .btn-create::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn-create:active::after {
        width: 300px;
        height: 300px;
        opacity: 0;
    }

    .proof-file-link.pdf-file {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        transition: all 0.3s;
    }

    .proof-file-link.pdf-file:hover {
        background: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .pdf-icon {
        font-size: 24px;
        color: #dc3545;
    }

    /* Thêm tooltip cho PDF */
    .proof-file-link.pdf-file::after {
        content: 'Xem PDF';
        position: absolute;
        bottom: -25px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        opacity: 0;
        transition: opacity 0.3s;
        white-space: nowrap;
    }

    .proof-file-link.pdf-file:hover::after {
        opacity: 1;
    }

    .proof-image-thumb {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 4px;
        transition: transform 0.2s;
    }

    .proof-file-link:hover .proof-image-thumb {
        transform: scale(1.1);
    }

    .btn-create:hover {
        background: #1557b0;
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.9em;
        font-weight: 500;
    }

    .status-badge.confirmed {
        background: #e6f4ea;
        color: #1e7e34;
    }

    .status-badge.pending {
        background: #fff3e0;
        color: #f57c00;
    }

    .btn-detail {
        padding: 6px 12px;
        background: #4285f4;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.9em;
        transition: all 0.3s;
    }

    .btn-detail:hover {
        background: #1557b0;
        color: white;
        text-decoration: none;
    }

    .modal-xl {
        max-width: 90%;
    }

    .proof-image-thumb {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .proof-image-thumb:hover {
        transform: scale(1.1);
    }

    .proof-image-link {
        display: inline-block;
    }

    .no-image {
        color: #999;
        font-style: italic;
        font-size: 0.9em;
    }

    /* Modal cho xem ảnh */
    .image-preview-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        z-index: 1050;
        align-items: center;
        justify-content: center;
    }

    .image-preview-content {
        position: relative;
        max-width: 90%;
        max-height: 90vh;
    }

    .image-preview-content img {
        max-width: 100%;
        max-height: 90vh;
        object-fit: contain;
    }

    .close-preview {
        position: absolute;
        top: -40px;
        right: -40px;
        color: white;
        font-size: 30px;
        cursor: pointer;
        background: none;
        border: none;
        padding: 10px;
    }
</style>
@endpush

@push('scripts')
<script>
async function showImportDetail(importId) {
    try {
        const response = await fetch(`/admin/imports/${importId}/detail`);
        const data = await response.json();
        
        if (data.success) {
            let html = `
                <div class="import-info">
                    <h4>Thông tin phiếu nhập</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Mã nhập:</strong> ${data.import.import_code}</p>
                            <p><strong>Ngày nhập:</strong> ${new Date(data.import.import_date).toLocaleDateString('vi-VN')}</p>
                            <p><strong>Người nhập:</strong> ${data.import.user.fullname}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Nhà cung cấp:</strong> ${data.import.supplier.name}</p>
                            <p><strong>Số điện thoại:</strong> ${data.import.supplier.phone}</p>
                            <p><strong>Địa chỉ:</strong> ${data.import.supplier.address}</p>
                        </div>
                    </div>
                </div>

                <div class="products-info mt-4">
                    <h4>Chi tiết sản phẩm</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Biến thể</th>
                                    <th>Số lượng</th>
                                    <th>Giá nhập</th>
                                    <th>NSX</th>
                                    <th>HSD</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>`;

                            data.import.import_products.forEach(product => {
                                product.variants.forEach(variant => {
                                    html += `
                                        <tr>
                                            <td>${product.product_name}</td>
                                            <td>${variant.variant_name}</td>
                                            <td>${variant.quantity}</td>
                                            <td>${new Intl.NumberFormat('vi-VN').format(variant.import_price)}đ</td>
                                            <td>${product.manufacture_date}</td>
                                            <td>${product.expiry_date}</td>
                                            <td>${new Intl.NumberFormat('vi-VN').format(variant.total_price)}đ</td>
                                        </tr>
                                    `;
                                });
                            });

                            html += `
                                            </tbody>
                                            <hr>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="2"><strong>Tổng cộng:</strong></td>
                                                    <td><strong>${new Intl.NumberFormat('vi-VN').format(data.import.total_quantity)}</strong></td>
                                                    <td></td>
                                                    <td><strong>${new Intl.NumberFormat('vi-VN').format(data.import.total_price)}đ</strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            `;

                            document.getElementById('importDetail').innerHTML = html;
                            $('#importDetailModal').modal('show');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi tải dữ liệu');
                    }
                }

function showImagePreview(imageUrl) {
    const modal = document.getElementById('imagePreviewModal');
    const previewImage = document.getElementById('previewImage');
    
    previewImage.src = imageUrl;
    modal.style.display = 'flex';
    
    
    modal.onclick = function(e) {
        if (e.target === modal) {
            closeImagePreview();
        }
    };
}

function closeImagePreview() {
    const modal = document.getElementById('imagePreviewModal');
    modal.style.display = 'none';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImagePreview();
    }
});

function showImagePreview(imageUrl) {
    const modal = document.getElementById('imagePreviewModal');
    const previewImage = document.getElementById('previewImage');
    
    previewImage.src = imageUrl;
    modal.style.display = 'flex';
}


document.querySelector('.btn-create').addEventListener('click', function(e) {
    e.preventDefault();
    const loadingOverlay = document.getElementById('loading-overlay');
    loadingOverlay.classList.add('active');
    const destination = this.href;
    setTimeout(() => {
        window.location.href = destination;
    }, 500);
});

window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        document.getElementById('loading-overlay').classList.remove('active');
    }
});
</script>
@endpush