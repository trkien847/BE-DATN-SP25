@extends('admin.layouts.layout')

@section('content')

<!-- Include Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
  .select2-container .select2-selection--single {
    height: 38px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    padding: 5px;
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100%;
  }

  .select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #6c757d;
  }

  select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    background-color: #fff;
    appearance: none;
    background-image: url("data:image/svg+xml;base64,...");
    background-repeat: no-repeat;
    background-position: right 10px center;
  }

  .table thead th {
    color:#5d7186;
    background-color:rgb(243, 243, 243);
    text-align: center;
    vertical-align: middle;
  }

  .table tbody tr:hover {
    background-color:rgb(15, 15, 15);
  }

  .table img {
    object-fit: cover;
    border-radius: 5px;
  }

  .search-bar {
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .search-bar input {
    flex: 1;
    border: 2px solidrgb(255, 255, 255);
    border-radius: 5px;
    padding: 0.5rem;
  }

  .search-bar button {
    background-color: #1e84c4;
    border-color:  #1e84c4;
    color: #fff;
  }

  .search-bar button:hover {
    background-color: rgb(179, 0, 9);
    border-color: rgb(179, 0, 9);
  }
</style>

{{-- <div class="container my-1">
  <div class="text-center">
    <h1 class="text-uppercase text-primary my-1">Quản Lý Sản Phẩm</h1>
    <p class="text-muted my-1">Dễ dàng quản lý danh sách sản phẩm và cập nhật thông tin.</p>
  </div>
</div> --}}

@if(session('success'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
      icon: 'success',
      title: 'Thành công!',
      text: '{{ session('success') }}',
      confirmButtonText: 'OK'
    });
  });
</script>
@endif


<div class="container">
  <div class="d-flex flex-wrap justify-content-between gap-3">
    <h4 class="text-secondary">DANH SÁCH SẢN PHẨM</h4>
    <div class="d-flex flex-wrap justify-content-between gap-3">
    <a href="{{ route('products.add') }}" class="btn btn-success">
      <i class="bi bi-plus-circle"></i><i class="bx bx-plus me-1"></i>
      Thêm Sản Phẩm
    </a>
    </div>
    
  
  </div>

  <div class="d-flex flex-wrap justify-content-between gap-3">

    <form action="{{ route('products.list') }}" method="GET" class="search-bar">
    <span><i class="bx bx-search-alt"></i></span>
      <input
        type="text"
        name="search"
        class="form-control"
        placeholder="Tìm kiếm sản phẩm..."
        value="{{ request('search') }}">
        
      <button type="submit" class="btn btn-primary">
        <i class="bi bi-search"></i> Tìm Kiếm
      </button>
      </form>

    <table class="table table-hover table-bordered align-middle">
      <thead>
        <tr>
          <th scope="col">Mã SP</th>
          <th scope="col">Tên Sản Phẩm</th>
          <th scope="col">Giá</th>
          <th scope="col">Ảnh</th>
          <th scope="col">Danh mục</th>
          <th scope="col">Mô Tả</th>
          <th scope="col">Giá Nhập</th>
          <th scope="col">Giá Bán</th>
          <th scope="col">Trạng Thái</th>
          <th scope="col">Nhà Cung Cấp</th>
          <th scope="col">Hành Động</th>
        </tr>
      </thead>
      <tbody>
        @foreach($products as $product)
        <tr>
          <td>{{ $product->sku }}</td>
          <td>{{ $product->name }}</td>
          <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
          <td>
            <img src="{{ asset('upload/'.$product->thumbnail)  }}" class="img-thumbnail" alt="Product Image" width="100px" height="100px">
          </td>
          <td>
              @foreach($product->categories as $category)
                  <div>
                      <span class="category-name" style="cursor: pointer;" onclick="toggleSubcategories({{ $category->id }})">
                          {{ $category->name }}
                      </span>
                      @if($category->categoryTypes->isNotEmpty())
                          <div id="subcategories-{{ $category->id }}" style="display: none; margin-left: 20px;">
                              @foreach($category->categoryTypes as $categoryType)
                                  <div>{{ $categoryType->name }}</div>
                              @endforeach
                          </div>
                      @endif
                  </div>
              @endforeach
          </td>
          <script>
            function toggleSubcategories(categoryId) {
                const subcategoriesDiv = document.getElementById(`subcategories-${categoryId}`);
                const toggleIcon = document.getElementById(`icon-${categoryId}`);
                if (subcategoriesDiv.style.display === 'none') {
                    subcategoriesDiv.style.display = 'block';
                    toggleIcon.textContent = '-';
                } else {
                    subcategoriesDiv.style.display = 'none';
                    toggleIcon.textContent = '+';
                }
            }
          </script>
          <td>{!! Str::limit($product->content, 100, '...') !!}</td>
          <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
          <td>{{ number_format($product->sale_price, 0, ',', '.') }} VND</td>
          <td>
            <span class="badge {{ $product->quantity > 0 ? 'bg-success' : 'bg-danger' }}">
              {{ $product->quantity > 0 ? 'Còn Hàng' : 'Hết Hàng' }}
            </span>
          </td>
          <td>{{ $product->brand->name ?? 'Không có thương hiệu' }}</td>
          <td>
          <a href="{{ route('products.edit', $product->id) }}"
           class="btn btn-warning btn-sm">
           <i class="bx bx-edit fs-16"></i>
          </a>
            <a href="{{ route('products.productct', $product->id) }}" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil-square"></i> Chi tiết sp
            </a>
            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" 
                  onclick="return confirm('Bạn có chắc chắn muốn xóa thương hiệu này không?')">
                  <i class="bx bx-trash fs-16"></i>
                </button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <!-- Phân trang -->
  <nav aria-label="Page navigation">
    {{ $products->links('pagination::bootstrap-5') }}
  </nav>
</div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
<script>
  document.getElementById('productImage').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const previewImg = document.getElementById('previewImg');

    if (file) {
      const reader = new FileReader();


      reader.onload = function(e) {
        previewImg.src = e.target.result;
        previewImg.style.display = 'block';
      };

      reader.readAsDataURL(file);
    } else {

      previewImg.src = '#';
      previewImg.style.display = 'none';
    }
  });


  $(document).ready(function() {
    $('#brandSelect').select2({
      placeholder: "Chọn tên thương hiệu",
      allowClear: true,
      width: '100%'
    });
  });
</script>
@endsection