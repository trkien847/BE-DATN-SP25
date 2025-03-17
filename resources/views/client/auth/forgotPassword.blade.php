@extends('client.layouts.layout')

@section('content')
    <div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image" data-bs-bg="img/bg/14.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">Forgot Password</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="{{ route('index') }}"><span class="ltn__secondary-color"><i class="fas fa-home"></i></span> Home</a></li>
                                <li>Forgot Password</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <!-- FORGOT PASSWORD AREA START -->
    <div class="ltn__login-area pb-110">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area text-center">
                        <h1 class="section-title">Reset Your Password</h1>
                        <p>Enter your registered email address and we will send you a link to reset your password.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="account-login-inner">
                        <form action="{{ route('password.email') }}" class="ltn__form-box contact-form-box" method="POST">
                            @csrf
                            <input type="email" name="email" placeholder="Enter your email*">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            
                            <div class="btn-wrapper">
                                <button class="theme-btn-1 btn reverse-color btn-block" type="submit">SEND RESET LINK</button>
                            </div>
                        </form>
                        <div class="go-to-btn mt-50 text-center">
                            <a href="{{ route('login') }}">Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FORGOT PASSWORD AREA END -->
@endsection
