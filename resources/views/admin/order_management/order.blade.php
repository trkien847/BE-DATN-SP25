@extends('admin.layouts.layout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<style>
    .nav-tabs .nav-link.active {
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
    }
</style>
<div class="container mt-4">
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
                        <th scope="col">Phí trả đối tác vận chuyển</th>
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
                            <select name="" id="" class="custom-select">
                                <option value="">Đã hủy</option>
                            </select>
                        </td>
                        <td>0</td>
                        <td>18,000</td>
                        <td>Chưa đối soát</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>FUN02294</td>
                        <td>SON03068</td>
                        <td>FUN02294</td>
                        <td class="status-pending">Đang giao hàng</td>
                        <td>8,680,000</td>
                        <td>63,000</td>
                        <td>Chưa đối soát</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>FUN02293</td>
                        <td>SON03065</td>
                        <td>S6445630.HN1.8CVPhuc.356688118</td>
                        <td class="status-cancelled">Hủy đóng gói</td>
                        <td>9,180,000</td>
                        <td>63,000</td>
                        <td>Chưa đối soát</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>FUN02290</td>
                        <td>SON03050</td>
                        <td>S6445630.HN1.C12H.409211786</td>
                        <td class="status-pending">Đang giao hàng</td>
                        <td>9,180,000</td>
                        <td>77,500</td>
                        <td>Chưa đối soát</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>FUN02289</td>
                        <td>SON03047</td>
                        <td>S6445630.HN1.A86T.405315314</td>
                        <td class="status-pending">Chờ giao hàng</td>
                        <td>60,858</td>
                        <td>22,000</td>
                        <td>Chưa đối soát</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>FUN02282</td>
                        <td>SON03052</td>
                        <td>FUN02282</td>
                        <td class="status-delivered">Đã giao hàng</td>
                        <td>9,180,000</td>
                        <td>0</td>
                        <td>Chưa đối soát</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>FUN02275</td>
                        <td>SON03038</td>
                        <td>FUN02275</td>
                        <td class="status-delivered">Đã giao hàng</td>
                        <td>216,000</td>
                        <td>0</td>
                        <td>Đang đối soát</td>
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