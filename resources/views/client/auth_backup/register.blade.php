@extends('layout')
@section('titlepage','Instinct - Instinct Pharmacy System')
@section('title','Welcome')

@section('content')

<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
<style>
  label.error {
    color: red;
  }

  .tbao {
    color: red;
  }
</style>

<body>
  <main class="main d-flex w-100 mt-5">
    <div class="container d-flex flex-column mt-5">
      <div class="row h-100">
        <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
          <div class="d-table-cell align-middle">
            <div class="text-center mt-4">
              <h1 class="h2">Get started</h1>
              <p class="lead">
                Start creating the best possible user experience for you
                customers.
              </p>
            </div>
            <div class="card">
              <div class="card-body">
                <div class="m-sm-4">
                  <form method="post" action="{{ route('register') }}" enctype="multipart/form-data" id="demoForm">
                    @csrf
                    <div class="form-group">
                        <label>FullName</label>
                        <input class="form-control form-control-lg" type="text" name="name" placeholder="Enter your FullName" value="{{ old('name') }}" />
                        @error('name')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                      </div>
                    <div class="form-group">
                      <label>Password</label>
                      <input class="form-control form-control-lg @error('password') is-invalid @enderror" type="password"
                       name="password" placeholder="Enter password" value="{{ old('password') }}" autocomplete="new-password" />
                      @error('password')
                      <p class="text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" type="password"
                        name="password_confirmation" placeholder="Confirm password" value="{{ old('password_confirmation') }}" autocomplete="new-password" />
                        @error('password_confirmation')
                        <p class="text-danger">{{ $message }}</p>
                        @enderror
                      </div>
                    <div class="form-group">
                      <label>Email</label>
                      <input class="form-control form-control-lg" type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" />
                      @error('email')
                      <p class="text-danger">{{ $message }}</p>
                  @enderror
                    </div>
                    <div class="form-group">
                      <label>Address</label>
                      <input class="form-control form-control-lg" type="text" name="address" placeholder="Enter your address" value="{{ old('address') }}" />
                      @error('address')
                      <p class="text-danger">{{ $message }}</p>
                  @enderror
                    </div>
                    <div class="form-group">
                      <label>Phone</label>
                      <input class="form-control form-control-lg" type="text" name="phone" placeholder="Enter your phone" value="{{ old('phone') }}" />
                      @error('phone')
                      <p class="text-danger">{{ $message }}</p>
                  @enderror
                    </div>
                    <div class="form-group">
                      <label>Image</label>
                      <input class="form-control form-control-lg" type="file" name="image" placeholder="Enter your image" value="{{ old('image') }}" />
                      @error('image')
                      <p class="text-danger">{{ $message }}</p>
                  @enderror
                    </div>
                    <div class="text-center mt-3">
                      <input type="submit" href="#" class="btn btn-lg btn-primary" name="signUp" value="Sign up">
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <h2 class="tbao">
              {{-- <?php
              if (isset($tbao) && ($tbao) != "") {
                echo $tbao;
              }
              ?> --}}
            </h2>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection
