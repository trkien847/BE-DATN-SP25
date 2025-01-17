@extends('admin.layouts.layout')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-1 anchor" id="basic">
                        Quản lý thương hiệu <a class="anchor-link" href="#basic">#</a>
                    </h5>

                    <!-- Hiển thị thông báo thành công -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Hiển thị lỗi validate -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form thêm thương hiệu -->
                    <div>
                        <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên thương hiệu</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="logo" class="form-label">Logo</label>
                                <input type="file" id="logo" name="logo" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="is_active" class="form-label">Kích hoạt</label>
                                <select id="is_active" name="is_active" class="form-control">
                                    <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Có</option>
                                    <option value="0" {{ old('is_active', 1) == 0 ? 'selected' : '' }}>Không</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Thêm thương hiệu</button>
                        </form>
                    </div>
                </div>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->

@endsection
