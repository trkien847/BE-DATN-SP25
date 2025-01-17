@extends('admin.layouts.layout')

@section('content')

<!-- Include Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

<!-- Title Section -->
{{-- <div class="container my-1">
  <div class="text-center">
    <h1 class="text-uppercase text-primary my-1">Quản Lý Sản Phẩm</h1>
    <p class="text-muted my-1">Dễ dàng quản lý danh sách sản phẩm và cập nhật thông tin.</p>
  </div>
</div> --}}

<!-- Product Table Section -->
<div class="container" >
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-secondary">DANH SÁCH SẢN PHẨM</h4>
    <button class="btn btn-success btn-l" data-bs-toggle="modal" data-bs-target="#addProductModal">
      <i class="bi bi-plus-circle"></i> Thêm Sản Phẩm
    </button>
  </div>

  <div class="table-responsive rounded shadow">
    <table class="table table-hover table-bordered align-middle">
      <thead class="table-dark">
        <tr class="text-center">
          <th scope="col">Mã SP</th>
          <th scope="col">Tên Sản Phẩm</th>
          <th scope="col">Giá</th>
          <th scope="col">Ảnh</th>
          <th scope="col">Số Lượng</th>
          <th scope="col">Mô Tả</th>
          <th scope="col">Giá Nhập</th>
          <th scope="col">Giá Bán</th>
          <th scope="col">Trạng Thái</th>
          <th scope="col">Nhà Cung Cấp</th>
          <th scope="col">Hành Động</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>SP001</td>
          <td>Sản Phẩm A</td>
          <td>500 VND</td>
          <td><img src="https://locatelvenezuela.vtexassets.com/arquivos/ids/177850/2014458.jpg?v=638222833141170000" class="img-thumbnail" alt="Product Image" width="100px" height="100px"></td>
          <td>10</td>
          <td>Mô tả ngắn gọn...</td>
          <td>500 VND</td>
          <td>1,000 VND</td>
          <td><span class="badge bg-success">Còn Hàng</span></td>
          <td>Nhà Cung Cấp A</td>
          <td>
            <button class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i>Sửa</button>
            <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i>Xóa</button>
          </td>
        </tr>
        <tr>
          <td>SP001</td>
          <td>Sản Phẩm A</td>
          <td>500 VND</td>
          <td><img src="https://cdn.medigoapp.com/product/Habroxol600_83644bd3ae.jpg" class="img-thumbnail" alt="Product Image" width="100px" height="100px"></td>
          <td>0</td>
          <td>Mô tả ngắn gọn...</td>
          <td>500 VND</td>
          <td>1,000 VND</td>
          <td><span class="badge bg-danger">Hết Hàng</span></td>
          <td>Nhà Cung Cấp A</td>
          <td>
            <button class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i>Sửa</button>
            <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i>Xóa</button>
          </td>
        </tr>
        <tr>
          <td>SP001</td>
          <td>Sản Phẩm A</td>
          <td>500 VND</td>
          <td><img src="https://th.bing.com/th/id/OIP.7Qmb42bfeqlSgDd4jinPFQHaHa?pid=ImgDet&w=179&h=179&c=7&dpr=1.3" class="img-thumbnail" alt="Product Image" width="100px" height="100px"></td>
          <td>10</td>
          <td>Mô tả ngắn gọn...</td>
          <td>500 VND</td>
          <td>1,000 VND</td>
          <td><span class="badge bg-success">Còn Hàng</span></td>
          <td>Nhà Cung Cấp A</td>
          <td>
            <button class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i>Sửa</button>
            <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i>Xóa</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
    <!-- Pagination -->
    <nav aria-label="Page navigation">
      <ul class="pagination justify-content-start mt-4">
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



<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addProductModalLabel">Thêm Sản Phẩm Mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="productName" class="form-label">Tên Sản Phẩm</label>
              <input type="text" class="form-control" id="productName" placeholder="Nhập tên sản phẩm">
            </div>
            <div class="col-md-6 mb-3">
              <label for="productPrice" class="form-label">Giá</label>
              <input type="number" class="form-control" id="productPrice" placeholder="Nhập giá sản phẩm">
            </div>
            <div class="col-md-6 mb-3">
              <label for="productImage" class="form-label">Ảnh</label>
              <input type="file" class="form-control" id="productImage">
            </div>
            <div class="col-md-6 mb-3">
              <label for="productQuantity" class="form-label">Số Lượng</label>
              <input type="number" class="form-control" id="productQuantity" placeholder="Nhập số lượng">
            </div>
            <div class="col-md-6 mb-3">
              <label for="productCostPrice" class="form-label">Giá Nhập</label>
              <input type="number" class="form-control" id="productCostPrice" placeholder="Nhập giá nhập">
            </div>
            <div class="col-md-6 mb-3">
              <label for="productSalePrice" class="form-label">Giá Bán</label>
              <input type="number" class="form-control" id="productSalePrice" placeholder="Nhập giá bán">
            </div>
            <div class="col-md-12 mb-3">
              <label for="productSupplier" class="form-label">Nhà Cung Cấp</label>
              <input type="text" class="form-control" id="productSupplier" placeholder="Nhập tên nhà cung cấp">
            </div>
          </div>
          <button type="submit" class="btn btn-primary w-100">Lưu Sản Phẩm</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection
