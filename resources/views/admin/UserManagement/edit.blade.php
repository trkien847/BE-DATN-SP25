@extends('admin.layouts.layout')
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card">
                        <div class="card-header fs-2 fw-bold">
                            Chỉnh sửa người dùng
                        </div>
                    </div>                    
                    <div class="card-body">
                        <form id="userForm" action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                        
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                        
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                                       id="phone_number" name="phone_number" 
                                       value="{{ old('phone_number', $user->phone_number) }}">
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                        
                            <!-- Full Name -->
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="fullname" name="fullname" 
                                       value="{{ old('fullname', $user->fullname) }}">
                            </div>
                        
                            <!-- Role (Dropdown) -->
                            <div class="mb-3">
                                <label for="role_id" class="form-label">Vai trò</label>
                                <select class="form-control" id="role_id" name="role_id">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" 
                                                {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        
                            <!-- Gender -->
                            <div class="mb-3">
                                <label class="form-label">Giới tính</label>
                                <select class="form-control" name="gender">
                                    <option value="Nam" {{ $user->gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="Nữ" {{ $user->gender == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                    <option value="Khác" {{ $user->gender == 'Khác' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                        
                            <!-- Birthday -->
                            <div class="mb-3">
                                <label for="birthday" class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control" id="birthday" name="birthday" 
                                       value="{{ old('birthday', $user->birthday) }}">
                            </div>
                        
                            <!-- Status -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng thái</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="Online" {{ $user->status == 'Online' ? 'selected' : '' }}>Online</option>
                                    <option value="Offline" {{ $user->status == 'Offline' ? 'selected' : '' }}>Offline</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                       id="address" name="address" 
                                       value="{{ old('address', $user->address->address ?? '') }}">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="mb-3">
                                <label for="avatar" class="form-label">Ảnh đại diện</label>
                                <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/'.$user->avatar) }}" alt="Avatar" width="100" class="img-thumbnail mt-2">
                                @endif
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                        
                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu mới (nếu muốn đổi)</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                        
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </form>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection




