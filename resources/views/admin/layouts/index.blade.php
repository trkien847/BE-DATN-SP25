@extends('admin.layouts.layout')

@section('content')

<!-- Include Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

<div class="container mt-5">
    <h2 class="text-center mb-4">Quản Lý Danh Mục</h2>
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tên Danh Mục</th>
                    <th scope="col">Danh Mục Con</th>
                    <th scope="col">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <!-- Danh mục cha -->
                <tr>
                    <td>1</td>
                    <td>Thuốc Giảm Đau</td>
                    <td>
                        <ul class="list-unstyled mb-0">
                            <li>- Paracetamol</li>
                            <li>- Aspirin</li>
                        </ul>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm">Sửa</button>
                        <button class="btn btn-danger btn-sm">Xóa</button>
                    </td>
                </tr>
                <!-- Danh mục cha khác -->
                <tr>
                    <td>2</td>
                    <td>Vitamin</td>
                    <td>
                        <ul class="list-unstyled mb-0">
                            <li>- Vitamin C</li>
                            <li>- Vitamin D</li>
                        </ul>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm">Sửa</button>
                        <button class="btn btn-danger btn-sm">Xóa</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection
