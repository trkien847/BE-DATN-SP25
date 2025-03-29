<!DOCTYPE html>
<html>
<head>
    <title>Danh sách thay đổi chờ duyệt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Danh sách thay đổi chờ duyệt</h1>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Người tạo/sửa</th>
                    <th>Thời gian</th>
                    <th>Hành động</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pendingUpdates as $update)
                    <tr>
                        <td>{{ $update->data['name'] }}</td>
                        <td>{{ $update->user->name }}</td>
                        <td>{{ $update->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $update->action_type === 'create' ? 'Thêm mới' : 'Sửa' }}</td>
                        <td>
                            <a href="{{ route('products.pending-update-detail', $update->id) }}" class="btn btn-primary btn-sm">Xem chi tiết</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>