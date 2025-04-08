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
                        <form action="{{route('register.submit')}}" class="ltn__form-box contact-form-box" method="POST">
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
@endsection
