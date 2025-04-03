@extends('admin.layouts.layout')

@section('content')

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-1 anchor" id="basic">
                        Thêm mã giảm giá<a class="anchor-link" href="#basic">#</a>
                    </h5>
                    {{-- @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif --}}
                    <div class="">
                        <form action="{{ route('coupons.store') }}" method="POST">
                            @csrf
                            <!-- Mã giảm giá -->
                            <div class="mb-3">
                                <label for="code" class="form-label">Mã giảm giá</label>
                                <input type="text" id="code" name="code" class="form-control"
                                    placeholder="Nhập mã giảm giá" >
                            </div>

                            <!-- Tiêu đề -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Tiêu đề</label>
                                <input type="text" id="title" name="title" class="form-control"
                                    placeholder="Nhập tiêu đề">
                            </div>

                            <!-- Mô tả -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea id="description" name="description" class="form-control" rows="3" placeholder="Nhập mô tả"></textarea>
                            </div>

                            <!-- Kiểu giảm giá -->
                            <div class="mb-3">
                                <label for="discount_type" class="form-label">Kiểu giảm giá</label>
                                <select id="discount_type" name="discount_type" class="form-control">
                                    <option value="phan_tram">Phần trăm (%)</option>
                                    <option value="co_dinh">Cố định</option>
                                    
                                </select>
                            </div>

                            <!-- Giá trị giảm giá -->
                            <div class="mb-3">
                                <label for="discount_value" class="form-label">Giá trị giảm giá</label>
                                <input type="number" id="discount_value" name="discount_value" class="form-control"
                                    step="0.01" placeholder="Nhập giá trị giảm">
                            </div>

                            <!-- Số lần sử dụng tối đa -->
                            <div class="mb-3">
                                <label for="usage_limit" class="form-label">Số lần sử dụng tối đa</label>
                                <input type="number" id="usage_limit" name="usage_limit" class="form-control"
                                    placeholder="Nhập số lần sử dụng tối đa">
                            </div>

                            <!-- Mã có hạn -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="is_expired" class="form-label">Mã có hạn</label>
                                        <select id="is_expired" name="is_expired" class="form-control">
                                            <option value="1">Có</option>
                                            <option value="0">Không</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="is_active" class="form-label">Kích hoạt</label>
                                        <select id="is_active" name="is_active" class="form-control">
                                            <option value="1">Có</option>
                                            <option value="0">Không</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Ngày bắt đầu -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">Ngày bắt đầu</label>
                                        <input type="datetime-local" id="start_date" name="start_date" class="form-control">
                                    </div>
                                </div>
                                <!-- Ngày kết thúc -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">Ngày kết thúc</label>
                                        <input type="datetime-local" id="end_date" name="end_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <!-- Coupon Restriction -->
                            <h5 class="card-title mt-4 mb-2">Hạn chế mã giảm giá</h5>

                            <!-- Giới hạn sử dụng tối thiểu -->
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <label for="min_order_value" class="form-label">Số tiền tối thiểu để áp
                                            dụng</label>
                                        <input type="number" id="min_order_value" name="min_order_value"
                                            class="form-control" step="0.01" placeholder="Nhập số tiền tối thiểu">
                                    </div>
                                </div>

                                <!-- Giới hạn sử dụng tối đa -->
                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <label for="max_discount_value" class="form-label">Số tiền tối đa giảm</label>
                                        <input type="number" id="max_discount_value" name="max_discount_value"
                                            class="form-control" step="0.01" placeholder="Nhập số tiền tối đa">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                             <!-- Chỉ áp dụng cho danh mục -->
                             <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="valid_categories" class="form-label">Áp dụng cho danh mục</label>
                                    <select class="form-control" id="choices-multiple-remove-button" data-choices
                                        data-choices-removeItem name="valid_categories[]" multiple>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Chỉ áp dụng cho sản phẩm -->
                           
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="valid_products" class="form-label">Áp dụng cho sản phẩm</label>
                                        <select class="form-control" id="choices-multiple-remove-button" data-choices
                                            data-choices-removeItem name="valid_products[]" multiple>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                               

                                {{-- <!-- Áp dụng cho khách hàng -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="user_id" class="form-label">Áp dụng cho khách hàng</label>
                                        <select class="form-control" id="choices-multiple-remove-button" data-choices
                                            data-choices-removeItem name="user_id[]" multiple>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->fullname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                            </div>

                            <!-- Nút lưu -->
                            <button type="submit" class="btn btn-primary">Thêm mã giảm giá</button>
                        </form>
                    </div>
                </div>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
@endsection
