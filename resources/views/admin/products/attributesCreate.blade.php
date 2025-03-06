@extends('admin.layouts.layout')

@section('content')
<div class="container">
    <h2>Thêm Thuộc tính</h2>
    <form action="{{ route('attributes.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Tên Thuộc tính</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Chọn loại biến thể</label>
            <select name="value" class="form-control">
                <option value="viên">Viên</option>
                <option value="ml">ml</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Số lượng/thuộc tính (30 viên/hộp hoặc 150ml/lọ)</label>
            <input type="number" name="slug" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Thêm</button>
    </form>
</div>
@endsection
