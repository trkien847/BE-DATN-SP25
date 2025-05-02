@extends('client.layouts.layout')
@section('content')

<style>
    .custom-alert {
        position: relative;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px 20px;
        border-radius: 8px;
        background-color: #f8d7da;
        border: 1px solid #f5c2c7;
        color: #842029;
        font-size: 16px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        animation: fadeIn 0.5s ease-in-out;
    }

    .custom-alert .icon {
        font-size: 24px;
        color: #dc3545;
    }

    .custom-alert .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        background: none;
        border: none;
        font-size: 20px;
        color: #842029;
        cursor: pointer;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@if ($errors->has('error'))
<div class="custom-alert alert alert-danger alert-dismissible" role="alert">
    <span class="icon">⚠️</span>
    <span>{{ $errors->first('error') }}</span>
    <button type="button" class="close-btn" data-bs-dismiss="alert" aria-label="Close">&times;</button>
</div>
@endif
<div class="ltn__utilize-overlay"></div>

<!-- BREADCRUMB AREA START -->
<div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image " data-bs-bg="img/bg/14.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="ltn__breadcrumb-inner">
                    <h1 class="page-title">Tài khoản</h1>
                    <div class="ltn__breadcrumb-list">
                        <ul>
                            <li><a href="{{ route('index') }}"><span class="ltn__secondary-color"><i
                                            class="fas fa-home"></i></span> Trang chủ</a></li>
                            <li>Đăng nhập</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- BREADCRUMB AREA END -->

<!-- LOGIN AREA START -->
<div class="ltn__login-area pb-65">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area text-center">
                    <h1 class="section-title">Đăng Nhập<br>Tài Khoản</h1>
                    <p>Đăng nhập để theo dõi đơn hàng,<br> nhận nhiều ưu đãi hấp dẫn.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="account-login-inner">
                    <form action="{{ route('login.submit') }}" method="POST" class="ltn__form-box contact-form-box">
                        @csrf
                        <input type="text" name="email" placeholder="Email*" value="{{ old('email') }}">
                        @error('email')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror

                        <input type="password" name="password" placeholder="Mật khẩu*">
                        @error('password')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror

                        <div class="btn-wrapper mt-0">
                            <button class="theme-btn-1 btn btn-block" type="submit">ĐĂNG NHẬP</button>
                        </div>
                        <div class="go-to-btn mt-20">
                            <a href="{{ route('password.request') }}"><small>QUÊN MẬT KHẨU?</small></a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="account-create text-center pt-50">
                    <h4>CHƯA CÓ TÀI KHOẢN?</h4>
                    <p>Tạo tài khoản để theo dõi đơn hàng, lưu<br>danh sách yêu thích và nhận ưu đãi hấp dẫn</p>
                    <div class="btn-wrapper">
                        <a href="{{ route('register') }}" class="theme-btn-1 btn black-btn">TẠO TÀI KHOẢN</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- LOGIN AREA END -->

<!-- CALL TO ACTION START (call-to-action-6) -->
<div class="ltn__call-to-action-area call-to-action-6 before-bg-bottom" data-bs-bg="img/1.jpg--">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="call-to-action-inner call-to-action-inner-6 ltn__secondary-bg position-relative text-center---">
                    <div class="coll-to-info text-color-white">
                        <h1>Mua khẩu trang y tế dùng một lần <br> để bảo vệ những người thân yêu của bạn</h1>
                    </div>
                    <div class="btn-wrapper">
                        <a class="btn btn-effect-3 btn-white" href="{{ route('category.show') }}">
                            Khám phá sản phẩm <i class="icon-next"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- CALL TO ACTION END -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection