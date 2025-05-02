@extends('client.layouts.layout')
@section('content')
    <div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image " data-bs-bg="img/bg/14.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">Tài Khoản</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="index.html"><span class="ltn__secondary-color"><i
                                                class="fas fa-home"></i></span> Trang chủ</a></li>
                                <li>Đăng ký</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <!-- LOGIN AREA START (Register) -->
    <div class="ltn__login-area pb-110">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area text-center">
                        <h1 class="section-title">Đăng Ký<br>Tài Khoản</h1>
                        <p>Hãy tạo tài khoản để có trải nghiệm mua sắm tốt nhất<br>
                            cùng với nhiều ưu đãi hấp dẫn.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="account-login-inner">
                        <form action="{{ route('register.submit') }}" class="ltn__form-box contact-form-box" method="POST">
                            @csrf
                            <input type="text" name="firstname" placeholder="Tên">
                            @error('firstname')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <input type="text" name="lastname" placeholder="Họ">
                            @error('lastname')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <input type="text" name="email" placeholder="Email*">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <input type="password" name="password" placeholder="Mật khẩu*">
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu*">
                            @error('password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="terms-conditions mb-4">
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="agreeTerms" name="agree_terms"
                                        >
                                    <label class="form-check-label" for="agreeTerms">
                                        Tôi đã đọc và đồng ý với điều khoản sử dụng và chính sách bảo mật
                                    </label>
                                    @error('agree_terms')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="agreeMedical" name="agree_medical"
                                        >
                                    <label class="form-check-label" for="agreeMedical">
                                        Tôi xác nhận đã hiểu rõ các quy định về mua bán thuốc kê đơn và cam kết tuân thủ
                                    </label>
                                    @error('agree_medical')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="agreeAge" name="agree_age"
                                        >
                                    <label class="form-check-label" for="agreeAge">
                                        Tôi xác nhận đủ 18 tuổi và có đầy đủ năng lực hành vi dân sự
                                    </label>
                                    @error('agree_age')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="agreeInfo" name="agree_info"
                                        >
                                    <label class="form-check-label" for="agreeInfo">
                                        Tôi đồng ý cung cấp thông tin cá nhân chính xác để mua thuốc kê đơn
                                    </label>
                                    @error('agree_info')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="btn-wrapper">
                                <button class="theme-btn-1 btn reverse-color btn-block" type="submit">
                                    ĐĂNG KÝ TÀI KHOẢN
                                </button>
                            </div>
                        </form>
                        <div class="by-agree text-center">
                            <p>Bằng việc đăng ký, bạn đồng ý với:</p>
                            <p><a href="#">ĐIỀU KHOẢN DỊCH VỤ &nbsp; &nbsp; | &nbsp; &nbsp; CHÍNH SÁCH BẢO MẬT</a></p>
                            <div class="go-to-btn mt-50">
                                <a href="{{ route('login') }}">ĐÃ CÓ TÀI KHOẢN?</a>
                            </div>
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
                    <div
                        class="call-to-action-inner call-to-action-inner-6 ltn__secondary-bg position-relative text-center---">
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
@endsection
@push('css')
    <style>
        .terms-conditions {
            text-align: left;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
        }

        .form-check {
            padding-left: 30px;
        }

        .form-check-input {
            margin-left: -25px;
        }

        .form-check-label {
            font-size: 14px;
            color: #666;
            line-height: 1.4;
        }

        .text-danger {
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
@endpush
