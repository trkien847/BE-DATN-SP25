@extends('admin.layouts.layout')

@section('content')

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-1 anchor" id="basic">
                        Thêm mã giảm giá<a class="anchor-link" href="#basic">#</a>
                    </h5>
                   
                    <div class="">
                        <form action="{{ route('coupons.store') }}" method="POST">
                            @csrf
                            <!-- Mã giảm giá -->
                            <div class="mb-3">
                                <label for="code" class="form-label">Mã giảm giá</label>
                                <input type="text" id="code" name="code" class="form-control @error('code') is-invalid @enderror"
                                    placeholder="Nhập mã giảm giá" value="{{ old('code') }}">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tiêu đề -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Tiêu đề</label>
                                <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror"
                                    placeholder="Nhập tiêu đề" value="{{ old('title') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Mô tả -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Nhập mô tả">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kiểu giảm giá -->
                            <div class="mb-3">
                                <label for="discount_type" class="form-label">Kiểu giảm giá</label>
                                <select id="discount_type" name="discount_type" class="form-control @error('discount_type') is-invalid @enderror">
                                    <option value="phan_tram" {{ old('discount_type') == 'phan_tram' ? 'selected' : '' }}>Phần trăm (%)</option>
                                    <option value="co_dinh" {{ old('discount_type') == 'co_dinh' ? 'selected' : '' }}>Cố định</option>
                                </select>
                                @error('discount_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Giá trị giảm giá -->
                            <div class="mb-3">
                                <label for="discount_value" class="form-label">Giá trị giảm giá</label>
                                <input type="number" id="discount_value" name="discount_value" class="form-control @error('discount_value') is-invalid @enderror"
                                       placeholder="Nhập giá trị giảm" value="{{ old('discount_value') }}" 
                                      >
                                @error('discount_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Số lần sử dụng tối đa -->
                            <div class="mb-3">
                                <label for="usage_limit" class="form-label">Số lần sử dụng tối đa</label>
                                <input type="number" id="usage_limit" name="usage_limit" class="form-control @error('usage_limit') is-invalid @enderror"
                                    placeholder="Nhập số lần sử dụng tối đa" value="{{ old('usage_limit') }}">
                                @error('usage_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Mã có hạn -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="is_expired" class="form-label">Mã có hạn</label>
                                        <select id="is_expired" name="is_expired" class="form-control @error('is_expired') is-invalid @enderror">
                                            <option value="1" {{ old('is_expired') == '1' ? 'selected' : '' }}>Có</option>
                                            <option value="0" {{ old('is_expired') == '0' ? 'selected' : '' }}>Không</option>
                                        </select>
                                        @error('is_expired')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="is_active" class="form-label">Kích hoạt</label>
                                        <select id="is_active" name="is_active" class="form-control @error('is_active') is-invalid @enderror">
                                            <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Có</option>
                                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Không</option>
                                        </select>
                                        @error('is_active')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Ngày bắt đầu -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">Ngày bắt đầu</label>
                                        <input type="datetime-local" id="start_date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                                            value="{{ old('start_date') }}"
                                            min="{{ date('Y-m-d\TH:i') }}">
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Ngày kết thúc -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">Ngày kết thúc</label>
                                        <input type="datetime-local" id="end_date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                                            value="{{ old('end_date') }}"
                                            min="{{ date('Y-m-d\TH:i') }}">
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
                                        <input type="text" id="min_order_value" name="min_order_value"
                                            class="form-control @error('min_order_value') is-invalid @enderror" step="0.01" placeholder="Nhập số tiền tối thiểu" value="{{ old('min_order_value') }}">
                                        @error('min_order_value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Giới hạn sử dụng tối đa -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_discount_value" class="form-label">Số tiền tối đa giảm</label>
                                        <input type="text" id="max_discount_value" name="max_discount_value"
                                            class="form-control @error('max_discount_value') is-invalid @enderror" step="0.01" placeholder="Nhập số tiền tối đa" value="{{ old('max_discount_value') }}">
                                        @error('max_discount_value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                             <!-- Chỉ áp dụng cho danh mục -->
                             <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="valid_categories" class="form-label">Áp dụng cho danh mục</label>
                                    <select class="form-control @error('valid_categories') is-invalid @enderror" id="choices-multiple-remove-button" data-choices
                                        data-choices-removeItem name="valid_categories[]" multiple>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ in_array($category->id, old('valid_categories', [])) ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('valid_categories')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Chỉ áp dụng cho sản phẩm -->
                           
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="valid_products" class="form-label">Áp dụng cho sản phẩm</label>
                                        <select class="form-control @error('valid_products') is-invalid @enderror" id="choices-multiple-remove-buttonsss" data-choices
                                            data-choices-removeItem name="valid_products[]" multiple>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" {{ in_array($product->id, old('valid_products', [])) ? 'selected' : '' }}>{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('valid_products')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                               

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
