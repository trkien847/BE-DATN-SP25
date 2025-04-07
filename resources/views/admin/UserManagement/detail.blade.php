@extends('admin.layouts.layout')

@section('content')
<html>

<head>
    <!-- Thêm Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header text-white" style="background-color: #6c757d;">
                <h2 class="mb-0">Thông Tin Chi Tiết</h2>
            </div>            
            <div class="card-body">
                <div class="row">
                    <!-- Ảnh đại diện -->
                    {{-- Nếu avatar lỗi hoặc ko có ảnh sẽ hiện ảnh mặc định --}}
                    <div class="col-md-4 text-center">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/avatars/default.jpg') }}" 
                             alt="{{ $user->avatar ? 'Ảnh đại diện' : 'Ảnh mặc định' }}" 
                             class="img-fluid rounded-circle" 
                             style="width: 250px; height: 250px; object-fit: cover;"
                             onerror="this.onerror=null; this.src='{{ asset('storage/avatars/default.jpg') }}';">
                    </div>
                
                    <!-- Thông tin người dùng -->
                    <div class="col-md-8">
                        <h3 class="mb-3">{{ $user->fullname }}</h3>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Email:</strong> {{ $user->email }}
                            </li>
                            <li class="list-group-item">
                                <strong>Số điện thoại:</strong> {{ $user->phone_number}}
                            </li>
                            <li class="list-group-item">
                                <strong>Địa chỉ:</strong> {{ $user->address->address ?? 'Không có địa chỉ' }}
                            </li>                            
                            <li class="list-group-item">
                                <strong>Ngày sinh:</strong> {{ \Carbon\Carbon::parse($user->birthday)->format('d/m/Y') }}
                            </li>
                            <li class="list-group-item">
                                <strong>Giới tính:</strong> 
                                @if($user->gender == 'Nam')
                                    Nam
                                @elseif($user->gender == 'Nu')
                                    Nữ
                                @else
                                    Khác
                                @endif
                            </li>
                            <li class="list-group-item">
                                <strong>Vai trò:</strong>
                                @if($user->role_id == 1)
                                    Khách
                                @elseif($user->role_id == 2)
                                    Nhân viên
                                @else
                                    Quản trị viên
                                @endif
                            </li>
                            
                        </ul>
                    </div>
                </div>

                <!-- Nút chức năng -->
                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.users.list') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
@endsection
