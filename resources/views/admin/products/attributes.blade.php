@extends('admin.layouts.layout')

@section('content')
<div class="container">
    <h2>Danh sách Thuộc tính</h2>
    <a href="{{ route('attributes.add') }}" class="btn btn-primary">Thêm Thuộc tính</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Loại biến thể</th>
                <th>Thông số</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attributes as $attribute)
                <tr>
                    <td>{{ $attribute->id }}</td>
                    <td>{{ $attribute->name }}</td>
                    <td>{{ $attribute->slug }} @foreach ($attribute->values as $value)
                            <span class="badge bg-primary">{{ $value->value }}</span>
                        @endforeach</td>
                    <td>{{ $attribute->is_active ? 'Hiển thị' : 'Ẩn' }}</td>
                    <td><a href="{{ route('attributes.edit', $attribute->id) }}" class="btn btn-primary">Sửa Thuộc tính</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection