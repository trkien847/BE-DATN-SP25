@extends('admin.layouts.layout')
@section('content')
<div> 
            <audio id="backgroundMusic" autoplay>
                <source src="{{ asset('audio/decade.mp3') }}" type="audio/mpeg">
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

<style>
    .changed {
        color: #dc3545;
        font-weight: bold;
        transition: all 0.3s ease;
    }
    
    .card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .card-header {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
        border-radius: 10px 10px 0 0 !important;
        padding: 1.5rem;
    }

    .info-label {
        font-weight: 600;
        color: #2c3e50;
        background: -webkit-linear-gradient(#2c3e50, #34495e);
        -webkit-background-clip: text;
        margin-right: 10px;
    }

    .image-gallery img {
        border: 2px solid #ddd;
        border-radius: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .image-gallery img:hover {
        transform: scale(1.1) rotate(2deg);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .action-buttons .btn {
        margin-right: 10px;
        transition: all 0.3s ease;
        transform: scale(1);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .action-buttons .btn:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .badge {
        transition: all 0.3s ease;
        font-size: 0.9em;
        padding: 8px 12px;
    }

    .badge:hover {
        transform: scale(1.1);
    }

    .table {
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
        border-radius: 8px;
        overflow: hidden;
    }

    .table thead th {
        background: linear-gradient(45deg, #f8f9fa, #e9ecef);
        border: none;
        padding: 15px;
        font-weight: 600;
        color: #2c3e50;
    }

    .table td {
        padding: 15px;
        vertical-align: middle;
    }

    .list-unstyled li {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
        transition: all 0.3s ease;
    }

    .list-unstyled li:hover {
        background-color: #f8f9fa;
        padding-left: 10px;
    }

    /* Animation classes */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    .slide-in {
        animation: slideIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideIn {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header">
            <h2 class="mb-0">Chi tiết thay đổi sản phẩm</h2>
        </div>
        <div class="card-body">
            <!-- Thông tin chung -->
            <div class="mb-4">
                <h5 class="border-bottom pb-2">Thông tin yêu cầu</h5>
                <p><span class="info-label">Người gửi:</span> {{ $pendingUpdate->user->fullname }}</p>
                <p><span class="info-label">Thời gian:</span> {{ $pendingUpdate->created_at->format('d/m/Y H:i') }}</p>
                <p><span class="info-label">Hành động:</span> 
                    <span class="badge {{ $pendingUpdate->action_type === 'create' ? 'bg-success' : 'bg-warning' }}">
                        {{ $pendingUpdate->action_type === 'create' ? 'Thêm mới' : 'Sửa' }}
                    </span>
                </p>
            </div>

            <!-- Thông tin sản phẩm -->
            @if ($pendingUpdate->action_type === 'create')
                <h5 class="border-bottom pb-2">Thông tin sản phẩm mới</h5>
                <ul class="list-unstyled">
                    <li><span class="info-label">Tên:</span> {{ $pendingUpdate->data['name'] }}</li>
                    <li><span class="info-label">SKU:</span> {{ $pendingUpdate->data['sku'] }}</li>
                    <li><span class="info-label">Thương hiệu ID:</span> {{ $brand->name ?? 'Không có' }}</li>
                    <li><span class="info-label">Nội dung:</span> {{ $pendingUpdate->data['content'] ?? 'Không có' }}</li>
                    <li><span class="info-label">Thumbnail:</span>
                        @if ($pendingUpdate->data['thumbnail'])
                            <img src="{{ asset('upload/' . $pendingUpdate->data['thumbnail']) }}" class="img-thumbnail" width="100" alt="Thumbnail">
                        @else
                            Không có
                        @endif
                    </li>
                    @if (!empty($pendingUpdate->data['images']))
                        <li><span class="info-label">Ảnh gallery:</span>
                            <div class="image-gallery mt-2 d-flex flex-wrap gap-2">
                                @foreach ($pendingUpdate->data['images'] as $image)
                                    <img src="{{ asset('upload/' . $image) }}" width="100" alt="Gallery Image">
                                @endforeach
                            </div>
                        </li>
                    @endif
                </ul>
            @else
                <h5 class="border-bottom pb-2">So sánh thay đổi</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Trường</th>
                                <th>Giá trị cũ</th>
                                <th>Giá trị mới</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tên</td>
                                <td>{{ $originalProduct?->name ?? 'Không có' }}</td>
                                <td class="{{ $originalProduct?->name !== ($pendingUpdate->data['name'] ?? null) ? 'changed' : '' }}">
                                    {{ $pendingUpdate->data['name'] ?? 'Không có' }}
                                </td>
                            </tr>
                            <tr>
                                <td>SKU</td>
                                <td>{{ $originalProduct?->sku ?? 'Không có' }}</td>
                                <td class="{{ $originalProduct?->sku !== ($pendingUpdate->data['sku'] ?? null) ? 'changed' : '' }}">
                                    {{ $pendingUpdate->data['sku'] ?? 'Không có' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Thương hiệu</td>
                                <td>{{ $originalProduct?->brand?->name ?? 'Không có' }}</td>
                                <td class="{{ $originalProduct?->brand_id !== ($pendingUpdate->data['brand_id'] ?? null) ? 'changed' : '' }}">
                                    {{ $brand?->name ?? 'Không có' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Nội dung</td>
                                <td>{{ $originalProduct?->content ? strip_tags($originalProduct->content) : 'Không có' }}</td>
                                <td class="{{ $originalProduct?->content !== ($pendingUpdate->data['content'] ?? null) ? 'changed' : '' }}">
                                    {{ isset($pendingUpdate->data['content']) ? strip_tags($pendingUpdate->data['content']) : 'Không có' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Thumbnail</td>
                                <td>
                                    @if ($originalProduct?->thumbnail)
                                        <img src="{{ asset('upload/' . $originalProduct->thumbnail) }}" class="img-thumbnail" width="100" alt="Old Thumbnail">
                                    @else
                                        Không có
                                    @endif
                                </td>
                                <td class="{{ $originalProduct?->thumbnail !== ($pendingUpdate->data['thumbnail'] ?? null) ? 'changed' : '' }}">
                                    @if ($pendingUpdate->data['thumbnail'] ?? false)
                                        <img src="{{ asset('upload/' . $pendingUpdate->data['thumbnail']) }}" class="img-thumbnail" width="100" alt="New Thumbnail">
                                    @else
                                        Không có
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- Nút hành động -->
            <div class="action-buttons mt-4">
                <form action="{{ route('products.approve-pending', $pendingUpdate->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="notification_id" value="{{ $notificationId }}">
                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Duyệt</button>
                </form>
                <form action="{{ route('products.reject-pending', $pendingUpdate->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="notification_id" value="{{ $notificationId }}">
                    <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Từ chối</button>
                </form>
                <a href="{{ route('products.pending-updates') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add animation classes to elements as they appear
    const elements = document.querySelectorAll('.list-unstyled li, .table tr');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('slide-in');
                observer.unobserve(entry.target);
            }
        });
    });

    elements.forEach(element => {
        observer.observe(element);
    });

    // Image preview functionality
    const images = document.querySelectorAll('.image-gallery img');
    images.forEach(img => {
        img.addEventListener('click', function() {
            const modal = document.createElement('div');
            modal.style.position = 'fixed';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.width = '100%';
            modal.style.height = '100%';
            modal.style.backgroundColor = 'rgba(0,0,0,0.8)';
            modal.style.display = 'flex';
            modal.style.justifyContent = 'center';
            modal.style.alignItems = 'center';
            modal.style.zIndex = '1000';
            
            const modalImg = document.createElement('img');
            modalImg.src = this.src;
            modalImg.style.maxHeight = '90%';
            modalImg.style.maxWidth = '90%';
            modalImg.style.objectFit = 'contain';
            modalImg.style.border = '3px solid white';
            modalImg.style.borderRadius = '8px';
            
            modal.appendChild(modalImg);
            document.body.appendChild(modal);
            
            modal.addEventListener('click', function() {
                modal.remove();
            });
        });
    });
});
</script>
@endsection