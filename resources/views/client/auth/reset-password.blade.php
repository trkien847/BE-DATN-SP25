@extends('client.layouts.layout')

@section('content')
    <div class="ltn__utilize-overlay"></div>

    <!-- KHU VỰC ĐƯỜNG DẪN BẮT ĐẦU -->
    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image" data-bs-bg="img/bg/14.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">Đặt Lại Mật Khẩu</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="{{ route('index') }}"><span class="ltn__secondary-color"><i class="fas fa-home"></i></span> Trang Chủ</a></li>
                                <li>Đặt Lại Mật Khẩu</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- KHU VỰC ĐƯỜNG DẪN KẾT THÚC -->

    <!-- KHU VỰC ĐẶT LẠI MẬT KHẨU BẮT ĐẦU -->
    <div class="ltn__login-area pb-110">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area text-center">
                        <h1 class="section-title">Tạo Mật Khẩu Mới</h1>
                        <p>Nhập mật khẩu mới của bạn bên dưới để đặt lại tài khoản.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="account-login-inner">
                        <form action="{{ route('password.update') }}" class="ltn__form-box contact-form-box" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <input type="hidden" name="email" value="{{ request()->email }}">
                            
                            <input type="password" name="password" placeholder="Mật Khẩu Mới*" >
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                            <input type="password" name="password_confirmation" placeholder="Xác Nhận Mật Khẩu Mới*" >
                            @error('password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                            <div class="btn-wrapper">
                                <button class="theme-btn-1 btn reverse-color btn-block" type="submit">ĐẶT LẠI MẬT KHẨU</button>
                            </div>
                        </form>

                        <div class="go-to-btn mt-50 text-center">
                            <a href="{{ route('login') }}">Quay Lại Đăng Nhập</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- KHU VỰC ĐẶT LẠI MẬT KHẨU KẾT THÚC -->
@endsection
