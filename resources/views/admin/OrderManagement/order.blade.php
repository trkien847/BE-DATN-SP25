@extends('admin.layouts.layout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<style>
    /* .nav-tabs .nav-link.active {
        background-color: #f8f9fa;
        border-color: #dee2e6 #dee2e6 #fff;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .status-cancelled {
        color: red;
    }

    .status-pending {
        color: orange;
    }

    .status-delivered {
        color: green;
    } */
</style>
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="text-secondary">DANH SÁCH ĐƠN HÀNG</h4>
        {{-- <button class="btn btn-success btn-l" data-bs-toggle="modal" data-bs-target="#addProductModal">
          <i class="bi bi-plus-circle"></i> Thêm Sản Phẩm
        </button> --}}
      </div>
    <ul class="nav" >
        <li class="nav-item">
            <a class="nav-link active" href="#">Tất cả đơn giao hàng</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Chờ xác nhận</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Chờ giao hàng</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Đang giao hàng</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Đã giao hàng</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Đã hủy</a>
        </li>
    </ul>
    <div class="mt-3">
        <div class="d-flex justify-content-between mb-3">

            <input type="text" class="form-control w-25" placeholder="Tìm kiếm đơn giao hàng">
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col"><input type="checkbox"></th>
                        <th scope="col">Mã đơn hàng</th>
                        <th scope="col">Tên khách hàng</th>
                        <th scope="col">Mã giảm giá</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Tổng hóa đơn</th>
                        <th scope="col">Người sử lý</th>
                        <th scope="col">Trạng thái đối soát</th>
                    </tr>
                </thead>
               
                <tbody>
                @foreach($orders as $od)
                <tr>
                    <td><input type="checkbox"></td>
                    <td>{{$od->code}}</td>
                    <td>{{$od->fullname}}</td>
                    <td>{{$od->coupon_code}}</td>
                    <td class="status-cancelled">
                        <select name="status" class="form-select form-select-xs text-xs" data-order-id="{{ $od->id }}">
                            <option value="1" {{ $od->orderStatusDetails->name == 'Chờ xác nhân' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="2" {{ $od->orderStatusDetails->name == 'Chờ giao hàng' ? 'selected' : '' }}>Chờ giao hàng</option>
                            <option value="3" {{ $od->orderStatusDetails->name == 'Đang giao hàng' ? 'selected' : '' }}>Đang giao hàng</option>
                            <option value="4" {{ $od->orderStatusDetails->name == 'Đã giao hàng' ? 'selected' : '' }}>Đã giao hàng</option>
                            <option value="5" {{ $od->orderStatusDetails->name == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </td>
                    <td>{{ number_format($od->total_amount, 0, ',', '.') }} VND</td>
                    <td>test</td>
                    <td><span class="badge bg-success">{{ $od->orderStatusDetails->name }}</span></td>
                </tr>
                @endforeach

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                   $(document).ready(function() {
                    $('select[name="status"]').change(function() {
                        var status = $(this).val();  
                        var orderId = $(this).data('order-id'); 

                        $.ajax({
                            url: '{{ route('updateOrderStatus') }}',  
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}', 
                                order_id: orderId,
                                status: status
                            },
                            success: function(response) {
                                alert(response.message); 
                                location.reload(); 
                            },
                            error: function(xhr, status, error) {
                                if (xhr.responseJSON && xhr.responseJSON.error) {
                                    alert(xhr.responseJSON.error);
                                    location.reload(); 
                                } else {
                                    alert('Cập nhật trạng thái thất bại. Vui lòng thử lại!');
                                }
                            }
                        });
                    });
                });

                </script>
                    
                </tbody>
            </table>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-start">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Trước</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">...</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Tiếp</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

@endsection