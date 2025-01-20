@extends('admin.layouts.layout')

@section('content')

<!-- Include Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
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
    color: #fff;
    background-color: rgb(59, 72, 84);
    text-align: center;
    vertical-align: middle;
  }

  .table tbody tr:hover {
    background-color: #f8f9fa;
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
    border: 2px solid #6c757d;
    border-radius: 5px;
    padding: 0.5rem;
  }

  .search-bar button {
    background-color: rgb(59, 72, 84);
    border-color: rgb(59, 72, 84);
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
    alert('{{ session(' success ') }}');});
</script>
@endif

@if(session('error'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    alert('{{ session(' error ') }}');});
</script>
@endif

@if($errors->any())
<script>
  document.addEventListener('DOMContentLoaded', function() {
    let errorMessage = 'Vui lòng sửa các lỗi sau:\n';
    @foreach($errors -> all() as $error)
    errorMessage += '- {{ $error }}\n';
    @endforeach
    alert(errorMessage);
  });
</script>
@endif

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-secondary">DANH SÁCH SẢN PHẨM</h4>
    <button style="background-color: rgb(59, 72, 84); color: white;" class="btn btn-l" data-bs-toggle="modal" data-bs-target="#addProductModal">
      <i class="bi bi-plus-circle"></i> Thêm Sản Phẩm
    </button>
  </div>

  <div class="table-responsive rounded shadow">

    <form action="{{ route('products.list') }}" method="GET" class="search-bar">
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
            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil-square"></i> Sửa
            </a>
            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" 
                  onclick="return confirm('Bạn có chắc chắn muốn xóa thương hiệu này không?')">
                    Xóa
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



<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addProductModalLabel">Thêm Sản Phẩm Mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-md-6">
              <label for="categorySelect" class="form-label">Chọn Danh Mục Cha</label>
              <select id="categorySelect" class="form-select" name="category_id">
                <option value="">Chọn danh mục cha</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label for="categoryTypeSelect" class="form-label">Chọn Danh Mục Con</label>
              <select id="categoryTypeSelect" class="form-select" name="category_type_id" disabled>
                <option value="">Chọn danh mục con</option>
              </select>
            </div>

            <script>
              document.addEventListener('DOMContentLoaded', () => {
                const categorySelect = document.getElementById('categorySelect');
                const categoryTypeSelect = document.getElementById('categoryTypeSelect');

                categorySelect.addEventListener('change', function() {
                  const categoryId = this.value;
                  categoryTypeSelect.innerHTML = '<option value="">Chọn danh mục con</option>';

                  if (categoryId) {
                    const categories = @json($categories);
                    const selectedCategory = categories.find(category => category.id == categoryId);
                    if (selectedCategory && selectedCategory.category_types.length > 0) {
                      selectedCategory.category_types.forEach(type => {
                        const option = document.createElement('option');
                        option.value = type.id;
                        option.textContent = type.name;
                        categoryTypeSelect.appendChild(option);
                      });
                      categoryTypeSelect.disabled = false;
                    } else {
                      categoryTypeSelect.disabled = true;
                    }
                  } else {
                    categoryTypeSelect.disabled = true;
                  }
                });
              });
            </script>

            <div class="col-md-6 mb-3">
              <label for="productName" class="form-label">Tên Sản Phẩm</label>
              <input type="text" class="form-control" id="productName" name="name" placeholder="Nhập tên sản phẩm">
            </div>
            <div class="col-md-6 mb-3">
              <label for="productPrice" class="form-label">Mã sản phẩm</label>
              <input type="text" class="form-control" id="productPrice" name="sku" placeholder="Nhập giá sản phẩm">
            </div>
            <div class="col-md-12 mb-3">
              <label for="productImage" class="form-label">Ảnh</label>
              <input type="file" class="form-control" id="productImage" name="thumbnail" accept="image/*">
            </div>
            <div id="imagePreview" style="margin-top: 10px;">
              <img id="previewImg" src="#" alt="Preview Image" style="max-width: 50%; display: none; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
            </div>
            <div class="col-md-6 mb-3">
              <label for="brandSelect" class="form-label">Chọn tên thương hiệu</label>
              <select id="brandSelect" class="form-control" name="brand_id">
                <option value="">Chọn tên thương hiệu</option>
                @foreach($brands as $br)
                <option value="{{ $br->id }}">{{ $br->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label for="productCostPrice" class="form-label">Giá Bán</label>
              <input type="number" class="form-control" id="productCostPrice" name="sell_price" placeholder="Nhập giá bán">
            </div>
            <div class="col-md-6 mb-3">
              <label for="productSalePrice" class="form-label">Giá Nhập</label>
              <input type="number" class="form-control" id="productSalePrice" name="price" placeholder="Nhập giá nhập">
            </div>
            <div class="col-md-6 mb-3">
              <label for="productSalePrice" class="form-label">Giá Khuyến Mãi (Mãi bên nhau em nhe)</label>
              <input type="number" class="form-control" id="productSalePrice" name="sale_price" placeholder="Giá Khuyến Mãi">
            </div>

            <div class="col-md-6 mb-3">
              <label for="timestampInput" class="form-label">Ngày Giờ Bắt Đầu Giảm Giá</label>
              <input type="datetime-local" id="timestampInput" name="sale_price_start_at" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
              <label for="timestampInput" class="form-label">Ngày Giờ Kết Thúc Giảm Giá</label>
              <input type="datetime-local" id="timestampInput" name="sale_price_end_at" class="form-control">
            </div>

            <div class="col-md-12 mb-3">
              <label class="form-label">Mô tả sản phẩm</label>
              <textarea class="form-control @error('bio') is-invalid @enderror"
                id="doctorBio"
                style="height: 100px"
                name="content">
              </textarea>
              <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
              <script>
                CKEDITOR.replace('doctorBio');
              </script>
            </div>
          </div>
          <button type="submit" class="btn btn-primary w-100">Lưu Sản Phẩm</button>
        </form>
      </div>
    </div>
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