<!DOCTYPE html>
<html>
<head>
    <title>Chi tiết thay đổi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .changed { color: red; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Chi tiết thay đổi</h1>
        <p><strong>Người gửi:</strong> {{ $pendingUpdate->user->fullname }}</p>
        <p><strong>Thời gian:</strong> {{ $pendingUpdate->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Hành động:</strong> {{ $pendingUpdate->action_type === 'create' ? 'Thêm mới' : 'Sửa' }}</p>

        @if ($pendingUpdate->action_type === 'create')
            <h3>Thông tin sản phẩm mới</h3>
            <ul>
                <li><strong>Tên:</strong> {{ $pendingUpdate->data['name'] }}</li>
                <li><strong>SKU:</strong> {{ $pendingUpdate->data['sku'] }}</li>
                <li><strong>Thương hiệu ID:</strong> {{ $pendingUpdate->data['brand_id'] }}</li>
                <li><strong>Nội dung:</strong> {{ $pendingUpdate->data['content'] }}</li>
                <li><strong>Thumbnail:</strong> 
                    @if ($pendingUpdate->data['thumbnail'])
                        <img src="{{ asset('upload/' . $pendingUpdate->data['thumbnail']) }}" width="100">
                    @else
                        Không có
                    @endif
                </li>
                <li><strong>Danh mục cha:</strong> {{ $pendingUpdate->data['category_id'] }}</li>
                <li><strong>Danh mục con:</strong> {{ $pendingUpdate->data['category_type_id'] }}</li>
                @if (!empty($pendingUpdate->data['images']))
                    <li><strong>Ảnh gallery:</strong>
                        @foreach ($pendingUpdate->data['images'] as $image)
                            <img src="{{ asset('upload/' . $image) }}" width="100">
                        @endforeach
                    </li>
                @endif
            </ul>
        @else
            <h3>So sánh thay đổi</h3>
            <table class="table table-bordered">
                <thead>
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
                        <td>{{ $originalProduct->content }}</td>
                        <td class="{{ $originalProduct->content !== $pendingUpdate->data['content'] ? 'changed' : '' }}">{{ $pendingUpdate->data['content'] }}</td>
                    </tr>
                    <tr>
                        <td>Thumbnail</td>
                        <td>
                            @if ($originalProduct->thumbnail)
                                <img src="{{ asset('upload/' . $originalProduct->thumbnail) }}" width="100">
                            @else
                                Không có
                            @endif
                        </td>
                        <td class="{{ $originalProduct->thumbnail !== $pendingUpdate->data['thumbnail'] ? 'changed' : '' }}">
                            @if ($pendingUpdate->data['thumbnail'])
                                <img src="{{ asset('upload/' . $pendingUpdate->data['thumbnail']) }}" width="100">
                            @else
                                Không có
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif

        <form action="{{ route('products.approve-pending', $pendingUpdate->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-success">Duyệt</button>
        </form>
        <form action="{{ route('products.reject-pending', $pendingUpdate->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Từ chối</button>
        </form>
        <a href="{{ route('products.pending-updates') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</body>
</html>