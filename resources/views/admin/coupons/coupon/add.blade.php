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
                            <input type="text" id="code" name="code" class="form-control" placeholder="Nhập mã giảm giá" required>
                        </div>

                        <!-- Tiêu đề -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề</label>
                            <input type="text" id="title" name="title" class="form-control" placeholder="Nhập tiêu đề">
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
                            <input type="number" id="discount_value" name="discount_value" class="form-control" step="0.01" placeholder="Nhập giá trị giảm">
                        </div>

                        <!-- Số lần sử dụng tối đa -->
                        <div class="mb-3">
                            <label for="usage_limit" class="form-label">Số lần sử dụng tối đa</label>
                            <input type="number" id="usage_limit" name="usage_limit" class="form-control" placeholder="Nhập số lần sử dụng tối đa">
                        </div>

                        <!-- Mã có hạn -->
                        <div class="mb-3">
                            <label for="is_expired" class="form-label">Mã có hạn</label>
                            <select id="is_expired" name="is_expired" class="form-control">
                                <option value="1">Có</option>
                                <option value="0">Không</option>
                            </select>
                        </div>

                        <!-- Kích hoạt -->
                        <div class="mb-3">
                            <label for="is_active" class="form-label">Kích hoạt</label>
                            <select id="is_active" name="is_active" class="form-control">
                                <option value="1">Có</option>
                                <option value="0">Không</option>
                            </select>
                        </div>

                        <!-- Ngày bắt đầu -->
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Ngày bắt đầu</label>
                            <input type="datetime-local" id="start_date" name="start_date" class="form-control">
                        </div>

                        <!-- Ngày kết thúc -->
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Ngày kết thúc</label>
                            <input type="datetime-local" id="end_date" name="end_date" class="form-control">
                        </div>

                        <!-- Coupon Restriction -->
                        <h5 class="card-title mt-4 mb-2">Hạn chế mã giảm giá</h5>

                        <!-- Giới hạn sử dụng tối thiểu -->
                        <div class="mb-3">
                            <label for="min_usage_amount" class="form-label">Số tiền tối thiểu để áp dụng</label>
                            <input type="number" id="min_usage_amount" name="min_usage_amount" class="form-control" step="0.01" placeholder="Nhập số tiền tối thiểu">
                        </div>

                        <!-- Giới hạn sử dụng tối đa -->
                        <div class="mb-3">
                            <label for="max_usage_amount" class="form-label">Số tiền tối đa giảm</label>
                            <input type="number" id="max_usage_amount" name="max_usage_amount" class="form-control" step="0.01" placeholder="Nhập số tiền tối đa">
                        </div>

                        <!-- Chỉ áp dụng cho sản phẩm -->
                        <div class="mb-3">
                            <label for="applicable_products" class="form-label">Áp dụng cho sản phẩm</label>
                            <select class="form-control" id="choices-multiple-remove-button" data-choices data-choices-removeItem name="choices-multiple-remove-button" multiple>
                                <option value=""></option>
                                <option value="Choice 2">Choice 2</option>
                                <option value="Choice 3">Choice 3</option>
                                <option value="Choice 4">Choice 4</option>
                           </select>
                        </div>

                        <!-- Chỉ áp dụng cho danh mục -->
                        <div class="mb-3">
                            <label for="applicable_categories" class="form-label">Áp dụng cho danh mục</label>
                            <select class="form-control" id="choices-multiple-remove-button" data-choices data-choices-removeItem name="choices-multiple-remove-button" multiple>
                                <option value="Choice 1" selected>Choice 1</option>
                                <option value="Choice 2">Choice 2</option>
                                <option value="Choice 3">Choice 3</option>
                                <option value="Choice 4">Choice 4</option>
                           </select>
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
