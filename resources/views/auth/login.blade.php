@extends('auth.master')
@section('content')
<h2>Chào Mừng Đến Với BeePharmacy!</h2>

<!-- Hiển thị thông báo thành công -->
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Hiển thị thông báo lỗi -->
@if(session('loginError'))
    <div class="alert alert-danger">{{ session('loginError') }}</div>
@endif

<div class="fxt-form">
    <form action="{{ route('login') }}" method="POST">
        @csrf

        <!-- Trường Email -->
        <div class="form-group">
            <div class="fxt-transformY-50 fxt-transition-delay-1">
                <input type="email" id="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" required>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Trường Password -->
        <div class="form-group">
            <div class="fxt-transformY-50 fxt-transition-delay-2">
                <input id="password" type="password" class="form-control" name="password" placeholder="********" required>
                <i toggle="#password" class="fa fa-fw fa-eye toggle-password field-icon"></i>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Ghi nhớ đăng nhập -->
        <div class="form-group">
            <div class="fxt-transformY-50 fxt-transition-delay-3">
                <div class="fxt-checkbox-area">
                    <div class="checkbox">
                        <input id="checkbox1" name="remember" type="checkbox">
                        <label for="checkbox1">Keep me logged in</label>
                    </div>
                    <a href="#" class="switcher-text">Forgot Password</a>
                </div>
            </div>
        </div>

        <!-- Nút đăng nhập -->
        <div class="form-group">
            <div class="fxt-transformY-50 fxt-transition-delay-4">
                <button type="submit" class="fxt-btn-fill">Log in</button>
            </div>
        </div>
    </form>
</div>

<!-- Chuyển hướng đăng ký -->
<div class="fxt-footer">
    <div class="fxt-transformY-50 fxt-transition-delay-9">
        <p>Don't have an account?<a href="{{ route('register.form') }}" class="switcher-text2 inline-text">Register</a></p>
    </div>
</div>
@endsection
