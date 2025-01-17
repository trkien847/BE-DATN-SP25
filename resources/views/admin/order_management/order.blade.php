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
    <ul class="nav nav-tabs">
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
                        <th scope="col">Mã đóng gói</th>
                        <th scope="col">Mã đơn hàng</th>
                        <th scope="col">Mã vận đơn</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Tiền COD</th>
                        <th scope="col">Phí ship</th>
                        <th scope="col">Trạng thái đối soát</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>FUN02297</td>
                        <td>SON03068</td>
                        <td>S6445630.HN1.A89.291516849</td>
                        <td class="status-cancelled">
                            <select name="" id="" class="form-select form-select-xs text-xs">
                                <option value="">Đã hủy</option>
                                <option value="">Đang giao hàng</option>
                                <option value="">Đã giao hàng</option>
                            </select>
                        </td>
                        <td>0</td>
                        <td>18,000</td>
                        <td><span class="badge bg-success">Đã đối soát</span></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>FUN02297</td>
                        <td>SON03068</td>
                        <td>S6445630.HN1.A89.291516849</td>
                        <td class="status-cancelled">
                            <select name="" id="" class="custom-select">
                                <option value="">Đã hủy</option>
                            </select>
                        </td>
                        <td>0</td>
                        <td>18,000</td>
                        <td><span class="badge bg-success">Đã đối soát</span></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>FUN02297</td>
                        <td>SON03068</td>
                        <td>S6445630.HN1.A89.291516849</td>
                        <td class="status-cancelled">
                            <select name="" id="" class="custom-select">
                                <option value="">Đã hủy</option>
                            </select>
                        </td>
                        <td>0</td>
                        <td>18,000</td>
                        <td><span class="badge bg-success">Đã đối soát</span></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>FUN02297</td>
                        <td>SON03068</td>
                        <td>S6445630.HN1.A89.291516849</td>
                        <td class="status-cancelled">
                            <select name="" id="" class="custom-select">
                                <option value="">Đã hủy</option>
                            </select>
                        </td>
                        <td>0</td>
                        <td>18,000</td>
                        <td><span class="badge bg-success">Đã đối soát</span></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>FUN02297</td>
                        <td>SON03068</td>
                        <td>S6445630.HN1.A89.291516849</td>
                        <td class="status-cancelled">
                            <select name="" id="" class="custom-select">
                                <option value="">Đã hủy</option>
                            </select>
                        </td>
                        <td>0</td>
                        <td>18,000</td>
                        <td><span class="badge bg-warning">Chưa đối soát</span></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>FUN02297</td>
                        <td>SON03068</td>
                        <td>S6445630.HN1.A89.291516849</td>
                        <td class="status-cancelled">
                            <select name="" id="" class="custom-select">
                                <option value="">Đã hủy</option>
                            </select>
                        </td>
                        <td>0</td>
                        <td>18,000</td>
                        <td><span class="badge bg-success">Đã đối soát</span></td>
                    </tr>
                    
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