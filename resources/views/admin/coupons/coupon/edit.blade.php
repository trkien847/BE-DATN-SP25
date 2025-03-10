@extends('admin.layouts.layout')

@section('content')

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-1 anchor">Chỉnh sửa mã giảm giá</h5>

                    <div class="">
                        <form action="{{ route('coupons.update', $coupon->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Mã giảm giá -->
                            <div class="mb-3">
                                <label for="code" class="form-label">Mã giảm giá</label>
                                <input type="text" id="code" name="code" class="form-control"
                                    value="{{ old('code', $coupon->code) }}">
                            </div>

                            <!-- Tiêu đề -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Tiêu đề</label>
                                <input type="text" id="title" name="title" class="form-control"
                                    value="{{ old('title', $coupon->title) }}">
                            </div>

                            <!-- Mô tả -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $coupon->description) }}</textarea>
                            </div>

                            <!-- Kiểu giảm giá -->
                            <div class="mb-3">
                                <label for="discount_type" class="form-label">Kiểu giảm giá</label>
                                <select id="discount_type" name="discount_type" class="form-control">
                                    <option value="phan_tram" {{ $coupon->discount_type == 'phan_tram' ? 'selected' : '' }}>Phần trăm (%)</option>
                                    <option value="co_dinh" {{ $coupon->discount_type == 'co_dinh' ? 'selected' : '' }}>Cố định</option>
                                </select>
                            </div>

                            <!-- Giá trị giảm giá -->
                            <div class="mb-3">
                                <label for="discount_value" class="form-label">Giá trị giảm giá</label>
                                <input type="number" id="discount_value" name="discount_value" class="form-control"
                                    value="{{ old('discount_value', $coupon->discount_value) }}">
                            </div>

                             <!-- Số lần sử dụng tối đa -->
                            
                            <div class="mb-3">
                                <label for="usage_limit" class="form-label">Số lần sử dụng tối đa</label>
                                <input type="number" id="usage_limit" name="usage_limit" class="form-control"
                                    value="{{ old('usage_limit', $coupon->usage_limit) }}">
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="min_order_value" class="form-label">Số tiền tối thiểu để áp dụng</label>
                                        <input type="number" id="min_order_value" name="min_order_value"
                                            class="form-control" step="0.01" placeholder="Nhập số tiền tối thiểu" 
                                            value="{{ old('min_order_value', $minOrderValue) }}">
                                    </div>
                                </div>
                            
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_discount_value" class="form-label">Số tiền tối đa giảm</label>
                                        <input type="number" id="max_discount_value" name="max_discount_value"
                                            class="form-control" step="0.01" placeholder="Nhập số tiền tối đa"
                                            value="{{ old('max_discount_value', $maxDiscountValue) }}">
                                    </div>
                                </div>
                            </div>
                            

                            <!-- Ngày bắt đầu & Ngày kết thúc -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">Ngày bắt đầu</label>
                                        <input type="datetime-local" id="start_date" name="start_date" class="form-control"
                                            value="{{ old('start_date', $coupon->start_date) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">Ngày kết thúc</label>
                                        <input type="datetime-local" id="end_date" name="end_date" class="form-control"
                                            value="{{ old('end_date', $coupon->end_date) }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Chỉ áp dụng cho sản phẩm -->
                            <div class="mb-3">
                                <label for="valid_products" class="form-label">Áp dụng cho sản phẩm</label>
                                <select class="form-control" name="valid_products[]" multiple>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ in_array($product->id, $validProducts) ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Chỉ áp dụng cho danh mục -->
                            <div class="mb-3">
                                <label for="valid_categories" class="form-label">Áp dụng cho danh mục</label>
                                <select class="form-control" name="valid_categories[]" multiple>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ in_array($category->id, $validCategories) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Áp dụng cho khách hàng -->
                            <div class="mb-3">
                                <label for="user_id" class="form-label">Áp dụng cho khách hàng</label>
                                <select class="form-control" name="user_id[]" multiple>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ in_array($user->id, $appliedUsers) ? 'selected' : '' }}>
                                            {{ $user->fullname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Nút cập nhật -->
                            <button type="submit" class="btn btn-primary">Cập nhật mã giảm giá</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
