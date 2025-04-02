@extends('admin.layouts.layout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .changed {
        color: #dc3545; /* Màu đỏ cho giá trị thay đổi */
        font-weight: bold;
    }
    .card-header {
        background-color: #007bff;
        color: white;
    }
    .info-label {
        font-weight: 600;
        color: #333;
    }
    .image-gallery img {
        border: 1px solid #ddd;
        border-radius: 5px;
        transition: transform 0.2s;
    }
    .image-gallery img:hover {
        transform: scale(1.1);
    }
    .action-buttons .btn {
        margin-right: 10px;
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
                    <li><span class="info-label">Thương hiệu ID:</span> {{ $pendingUpdate->data['brand_id'] }}</li>
                    <li><span class="info-label">Nội dung:</span> {{ $pendingUpdate->data['content'] ?? 'Không có' }}</li>
                    <li><span class="info-label">Thumbnail:</span>
                        @if ($pendingUpdate->data['thumbnail'])
                            <img src="{{ asset('upload/' . $pendingUpdate->data['thumbnail']) }}" class="img-thumbnail" width="100" alt="Thumbnail">
                        @else
                            Không có
                        @endif
                    </li>
                    <li><span class="info-label">Danh mục cha:</span> {{ $pendingUpdate->data['category_id'] }}</li>
                    <li><span class="info-label">Danh mục con:</span> {{ $pendingUpdate->data['category_type_id'] }}</li>
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
                                <td>{{ $originalProduct->name }}</td>
                                <td class="{{ $originalProduct->name !== $pendingUpdate->data['name'] ? 'changed' : '' }}">{{ $pendingUpdate->data['name'] }}</td>
                            </tr>
                            <tr>
                                <td>SKU</td>
                                <td>{{ $originalProduct->sku }}</td>
                                <td class="{{ $originalProduct->sku !== $pendingUpdate->data['sku'] ? 'changed' : '' }}">{{ $pendingUpdate->data['sku'] }}</td>
                            </tr>
                            <tr>
                                <td>Thương hiệu ID</td>
                                <td>{{ $originalProduct->brand_id }}</td>
                                <td class="{{ $originalProduct->brand_id !== $pendingUpdate->data['brand_id'] ? 'changed' : '' }}">{{ $pendingUpdate->data['brand_id'] }}</td>
                            </tr>
                            <tr>
                                <td>Nội dung</td>
                                <td>{{ $originalProduct->content ?? 'Không có' }}</td>
                                <td class="{{ $originalProduct->content !== $pendingUpdate->data['content'] ? 'changed' : '' }}">{{ $pendingUpdate->data['content'] ?? 'Không có' }}</td>
                            </tr>
                            <tr>
                                <td>Thumbnail</td>
                                <td>
                                    @if ($originalProduct->thumbnail)
                                        <img src="{{ asset('upload/' . $originalProduct->thumbnail) }}" class="img-thumbnail" width="100" alt="Old Thumbnail">
                                    @else
                                        Không có
                                    @endif
                                </td>
                                <td class="{{ $originalProduct->thumbnail !== $pendingUpdate->data['thumbnail'] ? 'changed' : '' }}">
                                    @if ($pendingUpdate->data['thumbnail'])
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
                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Duyệt</button>
                </form>
                <form action="{{ route('products.reject-pending', $pendingUpdate->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Từ chối</button>
                </form>
                <a href="{{ route('products.pending-updates') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
            </div>
        </div>
    </div>
</div>
@endsection