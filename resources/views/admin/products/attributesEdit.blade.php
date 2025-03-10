@extends('admin.layouts.layout')

@section('content')
<div class="container">
    <h2>Chỉnh sửa Thuộc tính</h2>
    <form action="{{ route('attributes.update', $attribute->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Tên Thuộc tính</label>
            <input type="text" name="name" class="form-control" value="{{ $attribute->name }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Chọn loại biến thể</label>
            <select name="value" class="form-control">
                @php
                $selectedValue = optional($attribute->values->first())->value; // Lấy giá trị đầu tiên hoặc null
                @endphp
                <option value="viên" {{ $selectedValue == 'viên' ? 'selected' : '' }}>Viên</option>
                <option value="ml" {{ $selectedValue == 'ml' ? 'selected' : '' }}>ml</option>
                <option value="g" {{ $selectedValue == 'g' ? 'selected' : '' }}>g</option>
            </select>

        </div>
        <div class="mb-3">
            <label class="form-label">Slug</label>
            <input type="number" name="slug" class="form-control" value="{{ $attribute->slug }}" required>
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
    </form>
</div>
@endsection