@extends('admin.layouts.layout')

@section('content')
<html>

<head>
    <!-- Thêm Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm mt-5">
                    <div class="card-header fs-2 fw-bold">
                        Thêm mới người dùng
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.users.store') }}" id="myForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <!-- Email -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" placeholder="Nhập email" required>
                                    @error('email')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Phone Number -->
                                <div class="col-md-6">
                                    <label for="phone_number" class="form-label">Số Điện Thoại</label>
                                    <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="Nhập số điện thoại">
                                    @error('phone_number')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Fullname -->
                                <div class="col-md-6">
                                    <label for="fullname" class="form-label">Họ và Tên</label>
                                    <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Nhập họ và tên">
                                    @error('fullname')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Role -->
                                <div class="col-md-6">
                                    <label for="role_id" class="form-label">Vai Trò</label>
                                    <select id="role_id" name="role_id" class="form-select" required>
                                        <option value="">Chọn vai trò</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Gender -->
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Giới Tính</label>
                                    <select id="gender" name="gender" class="form-select">
                                        <option value="">Chọn giới tính</option>
                                        <option value="Nam">Nam</option>
                                        <option value="Nữ">Nữ</option>
                                        <option value="Khác">Khác</option>
                                    </select>
                                </div>

                                <!-- Birthday -->
                                <div class="col-md-6">
                                    <label for="birthday" class="form-label">Ngày Sinh</label>
                                    <input type="date" id="birthday" name="birthday" class="form-control">
                                </div>

                           <!-- Status (Mặc định Online và ẩn đi) -->
                                    <div class="col-md-6" hidden>
                                        <label for="status" class="form-label">Trạng Thái</label>
                                        <select id="status" name="status" class="form-select">
                                            <option value="Online" selected>Online</option>
                                            <option value="Offline">Offline</option>
                                        </select>
                                    </div>


                                <!-- Avatar -->
                                <div class="col-md-6">
                                    <label for="avatar" class="form-label">Ảnh Đại Diện</label>
                                    <input type="file" id="avatar" name="avatar" class="form-control">
                                </div>

                                <!-- Password -->
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Mật Khẩu</label>
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                                    @error('password')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="submit" class="btn btn-success btn-sm fw-bold shake">
                                    <i class="bi bi-plus-circle"></i><i class="bx bx-plus me-1"></i>
                                    Thêm Người Dùng
                                </button>
                                
                                
                                <a href="{{ route('admin.users.list') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Quay Lại
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
@endsection
