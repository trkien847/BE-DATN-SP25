@extends('admin.layouts.layout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
</nav>

<!-- Tiêu đề -->
<div class="container my-4">
  <h1 class="text-center">Quản Lý Sản Phẩm</h1>
</div>

<!-- Bảng sản phẩm -->
<div class="container">
  <div class="mb-3 text-end">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal" style="background-color:rgb(0, 125, 249);">Thêm Sản Phẩm</button>
  </div>
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th scope="col">Mã Sản Phẩm</th>
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
        <td><img src="https://tse1.mm.bing.net/th?id=OIP.UkhN7j6jyENoOuKmZ_0TDgHaHa&pid=ImgDet&w=200&h=200&c=7" alt=""></td>
        <td>10</td>
        <td>Ảnh Sản Phẩm</td>
        <td>500 VND</td>
        <td>100,000 VND</td>
        <td>Còn Hàng</td>
        <td>Bà Tân vlog</td>
        <td>
          <button class="btn btn-primary btn-sm">Chỉnh Sửa</button>
          <button class="btn btn-danger btn-sm">Xóa</button>
        </td>
      </tr>

    </tbody>
  </table>
</div>

<!-- Modal Thêm Sản Phẩm -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addProductModalLabel">Thêm Sản Phẩm Mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="productName" class="form-label">Tên Sản Phẩm</label>
            <input type="text" class="form-control" id="productName">
          </div>
          <div class="mb-3">
            <label for="productPrice" class="form-label">Giá</label>
            <input type="number" class="form-control" id="productPrice">
          </div>
          <div class="mb-3">
            <label for="productQuantity" class="form-label">Ảnh</label>
            <input type="file" class="form-control" id="productQuantity">
          </div>
          <div class="mb-3">
            <label for="productName" class="form-label">Số Lượng</label>
            <input type="text" class="form-control" id="productName">
          </div>
          <div class="mb-3">
            <label for="productPrice" class="form-label">Giá Nhập</label>
            <input type="text" class="form-control" id="productPrice">
          </div>
          <div class="mb-3">
            <label for="productQuantity" class="form-label">Giá Bán</label>
            <input type="text" class="form-control" id="productQuantity">
          </div>
          <div class="mb-3">
            <label for="productQuantity" class="form-label">Nhà Cung Cấp</label>
            <input type="number" class="form-control" id="productQuantity">
          </div>

          <button type="submit" class="btn btn-primary">Lưu</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection