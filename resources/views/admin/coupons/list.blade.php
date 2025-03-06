@extends('admin.layouts.layout')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between gap-3">

                        <div class="search-bar">
                            <span><i class="bx bx-search-alt"></i></span>
                            <form action="{{ route('coupons.list') }}" method="GET" class="d-flex justify-content-center">
                                @csrf
                                <input type="text" name="search" class="form-control"
                                    placeholder="Tìm kiếm mã giảm giá ..." value="{{ request('search') }}">
                                <button style="width: 20vh;" type="submit" class="btn btn-primary ms-2">Tìm kiếm</button>
                            </form>
                        </div>

                        <div>
                            <a href="{{ route('coupons.create') }}" class="btn btn-success">
                                <i class="bx bx-plus me-1"></i>Thêm Mã Giảm
                            </a>
                        </div>
                    </div> <!-- end row -->
                </div>

                <div>
                    <div class="table-responsive table-centered">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">STT</th>
                                    <th scope="col">Mã giảm giá</th>
                                    <th scope="col">Loại giảm giá</th>
                                    <th scope="col">Giá trị</th>
                                    <th scope="col">Hạn sử dụng</th>
                                    <th scope="col">Sản phẩm áp dụng</th>
                                    <th scope="col">Danh mục áp dụng</th>
                                    <th scope="col">Người dùng áp dụng</th>
                                    <th scope="col">Hành động</th>
                                </tr>
                            </thead> <!-- end thead -->
                            <tbody>
                                @foreach ($coupons as $key => $coupon)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <span class="fw-medium">{{ $coupon->code }}</span>
                                        </td>
                                        <td>
                                            {{ $coupon->discount_type == 'phan_tram' ? 'Giảm %' : 'Giảm cố định' }}
                                        </td>
                                        <td>
                                            {{ number_format($coupon->discount_value) }}
                                            {{ $coupon->discount_type == 'phan_tram' ? '%' : ' VNĐ' }}
                                        </td>

                                        <!-- Hạn sử dụng -->
                                        <td>
                                            {{ $coupon->end_date ? date('d/m/Y', strtotime($coupon->end_date)) : 'Không giới hạn' }}
                                        </td>

                                        <!-- Sản phẩm áp dụng -->
                                        <td>
                                            @if (!empty($coupon->productNames))
                                                <strong>{{ implode(', ', $coupon->productNames) }}</strong>
                                            @else
                                                <strong>Không có</strong>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @if (!empty($coupon->categoryNames))
                                                <strong>{{ implode(', ', $coupon->categoryNames) }}</strong>
                                            @else
                                                <strong>Không có</strong>
                                            @endif
                                        </td>
                                        
                                        <!-- Người dùng áp dụng -->
                                        <td>
                                            @if ($coupon->users->isNotEmpty())
                                                <strong>{{ implode(', ', $coupon->users->pluck('fullname')->toArray()) }}</strong>
                                            @else
                                                <strong>Không áp dụng</strong>
                                            @endif
                                        </td>

                                        <!-- Hành động -->
                                        <td class="text-center">
                                            
                                            <a href=" {{ route('coupons.edit', $coupon->id) }}" class="btn btn-sm btn-soft-secondary me-1">
                                                <i class="bx bx-edit fs-16"></i>
                                            </a>

                                            <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-soft-danger"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                    <i class="bx bx-trash fs-16"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody> <!-- end tbody -->
                        </table> <!-- end table -->
                        <div class="d-flex justify-content-center">
                            {{ $coupons->links('pagination::bootstrap-4') }}
                        </div>
                    </div> <!-- table responsive -->
                </div>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
@endsection
