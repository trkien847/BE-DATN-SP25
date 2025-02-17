@extends('auth.master')

@section('content')
<h2>Chào Mừng Đến Với BeePharmacy !</h2>

<div class="fxt-form">
    <!-- Hiển thị thông báo thành công (nếu có) -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Full Name -->
        <div class="form-group">
            <input type="text" id="fullname" class="form-control" name="fullname" placeholder="Full Name" value="{{ old('fullname') }}" required>
            @error('fullname')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email -->
        <div class="form-group">
            <input type="email" id="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" required>
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Phone Number -->
        <div class="form-group">
            <input type="tel" id="phone_number" class="form-control" name="phone_number" placeholder="Phone Number" value="{{ old('phone_number') }}" required>
            @error('phone_number')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Birthday -->
        <div class="form-group">
            <input type="date" id="birthday" class="form-control" name="birthday" value="{{ old('birthday') }}" required>
            @error('birthday')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Gender -->
        <div class="form-group">
            <select name="gender" id="gender" class="form-control">
                <option value="Nam" {{ old('gender') == 'Nam' ? 'selected' : '' }} style="color: black">Nam</option>
                <option value="Nữ" {{ old('gender') == 'Nữ' ? 'selected' : '' }} style="color: black">Nữ</option>
            </select>
            @error('gender')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
      <!-- Submit Button -->
        <div class="form-group">
            <button type="submit" class="fxt-btn-fill">Register</button>
        </div>
    </form>
</div>
<div class="fxt-footer">
    <div class="fxt-transformY-50 fxt-transition-delay-9">
        <p>Don't have an account?<a href="{{ route('login.form') }}" class="switcher-text2 inline-text">Login</a></p>
    </div>
</div>
@endsection