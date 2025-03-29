@extends('admin.layouts.layout')

@section('content')
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
    color: #5d7186;
    background-color: rgb(243, 243, 243);
    text-align: center;
    vertical-align: middle;
  }
  .table tbody tr:hover {
    background-color: rgb(15, 15, 15);
  }
  .table img {
    object-fit: cover;
    border-radius: 5px;
  }
  .content-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .overlay-content {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    max-width: 600px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    transform: scale(0.8);
    opacity: 0;
    transition: transform 0.3s ease, opacity 0.3s ease;
  }
  .overlay-content.active {
    transform: scale(1);
    opacity: 1;
  }
  .show-full-content {
    color: #007bff;
    cursor: pointer;
    text-decoration: underline;
  }
  .show-full-content:hover {
    color: #0056b3;
  }
  .search-bar {
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  .search-bar input {
    flex: 1;
    border: 2px solid rgb(255, 255, 255);
    border-radius: 5px;
    padding: 0.5rem;
  }
  .search-bar button {
    background-color: #1e84c4;
    border-color: #1e84c4;
    color: #fff;
  }
  .variant-container {
    position: relative;
  }
  .variant-item {
    padding: 5px;
    transition: background-color 0.3s ease;
  }
  .variant-item:hover {
    background-color: #f5f5f5;
  }

  .variant-list {
    margin-top: 10px;
  }

    .variant-count .badge {
    font-size: 12px;
    padding: 5px 8px;
  }
  .variant-count {
    margin-top: 5px;
  }
  .search-bar button:hover {
    background-color: rgb(179, 0, 9);
    border-color: rgb(179, 0, 9);
  }

  .ripple {
    position: relative;
    overflow: hidden;
  }
  .ripple-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    pointer-events: none;
    z-index: 9999;
  }
  .ripple-effect {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 193, 7, 0.5);
    transform: scale(0);
    animation: rippleFull 0.8s ease-out;
    opacity: 1;
  }
  @keyframes rippleFull {
    to {
      transform: scale(20);
      opacity: 0;
    }
  }

  .shake-effect {
    display: inline-block;
    animation: shakeAndScale 0.6s ease-in-out forwards;
  }
  @keyframes shakeAndScale {
    0% {
      transform: scale(1);
    }
    20% {
      transform: scale(1.2) rotate(5deg);
    }
    40% {
      transform: scale(1.2) rotate(-5deg);
    }
    60% {
      transform: scale(1.2) rotate(5deg);
    }
    80% {
      transform: scale(1.2) rotate(-5deg);
    }
    100% {
      transform: scale(1.5);
      opacity: 0;
    }
  }

  .rainbow-text {
      background: linear-gradient(to right, red, orange, yellow, green, blue, indigo, violet);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      background-size: 200% 100%;
      animation: rainbowMove 5s linear infinite;
  }

  @keyframes rainbowMove {
      0% {
          background-position: 200% 0;
      }
      100% {
          background-position: 0 0;
      }
  }
</style>

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
    <h4 class="rainbow-text">DANH SÁCH SẢN PHẨM</h4>
    <div class="d-flex flex-wrap justify-content-between gap-3">
      <a href="{{ route('products.add') }}" class="btn btn-success shake">
        <i class="bi bi-plus-circle"></i><i class="bx bx-plus me-1"></i>
        Thêm Sản Phẩm
      </a>
      <a href="{{ route('products.hidden') }}" class="btn btn-secondary">
        <i class="bx bx-hide me-1"></i>
        Xem Sản Phẩm Đã Ẩn
      </a>
    </div>
  </div>

  <div class="d-flex flex-wrap justify-content-between gap-3">
    <form action="{{ route('products.list') }}" method="GET" class="search-bar">
      <span><i class="bx bx-search-alt"></i></span>
      <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
      <button type="submit" class="btn btn-primary">
        <i class="bi bi-search"></i> Tìm Kiếm
      </button>
    </form>

    <table class="table table-hover table-bordered align-middle">
  <thead>
    <tr>
      <th scope="col">Mã SP</th>
      <th scope="col">Tên Sản Phẩm</th>
      <th scope="col">Ngày nhập</th>
      <th scope="col">Ảnh</th>
      <th scope="col">Danh mục</th>
      <th scope="col">Trạng Thái</th>
      <th scope="col">Hành Động</th>
    </tr>
  </thead>
  <tbody>
    @foreach($products as $product)
    <tr class="product-row" data-id="{{ $product->id }}">
      <td>{{ $product->sku }}</td>
      <td>{{ $product->name }}</td>
      <td>
        @if(is_null($product->import_at))
          <a href="{{ route('products.import') }}" class="btn btn-primary btn-sm" title="Tạo ngày nhập">
            <i class="bx bx-calendar-plus fs-16"></i>
          </a>
        @else
          {{ $product->import_at }}
        @endif
      </td>
      <td>
        <img src="{{ asset('upload/'.$product->thumbnail) }}" class="img-thumbnail" alt="Product Image" width="100px" height="100px">
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
      <td>
        <span class="badge {{ $product->variants_sum_stock > 0 ? 'bg-success' : 'bg-danger' }}">
          {{ $product->variants_sum_stock > 0 ? 'Còn Hàng' : 'Hết Hàng' }}
          {{ $product->variants_sum_stock }}
        </span>
      </td>
      <td>
        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm ripple">
          <i class="bx bx-edit fs-16"></i>
        </a>
        <a href="{{ route('products.productct', $product->id) }}" class="btn btn-info btn-sm" title="Chi tiết sản phẩm">
          <i class="bx bx-detail fs-16"></i>
        </a>
        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline delete-form">
          @csrf
          @method('PATCH')
          <button type="submit" class="btn btn-secondary btn-sm ">
            <i class="bx bx-hide fs-16 "></i>
          </button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
function toggleSubcategories(categoryId) {
  const subcategoriesDiv = document.getElementById(`subcategories-${categoryId}`);
  if (subcategoriesDiv.style.display === 'none') {
    subcategoriesDiv.style.display = 'block';
  } else {
    subcategoriesDiv.style.display = 'none';
  }
}

function toggleSubcategories(categoryId) {
  const subcategoriesDiv = document.getElementById(`subcategories-${categoryId}`);
  if (subcategoriesDiv.style.display === 'none') {
    subcategoriesDiv.style.display = 'block';
  } else {
    subcategoriesDiv.style.display = 'none';
  }
}

$(document).ready(function() {
  $('.variant-container').hover(
    function() { 
      const productId = $(this).data('product-id');
      $(`#variant-list-${productId}`).slideDown(300);
    },
    function() { 
      const productId = $(this).data('product-id');
      $(`#variant-list-${productId}`).slideUp(300);
    }
  );
});

</script>
  </div>

  

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    
    const rippleContainer = document.createElement('div');
    rippleContainer.classList.add('ripple-container');
    document.body.appendChild(rippleContainer);

    
    document.querySelectorAll('.ripple').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        const x = e.clientX;
        const y = e.clientY;

        const ripple = document.createElement('span');
        ripple.classList.add('ripple-effect');
        ripple.style.width = ripple.style.height = `${Math.max(window.innerWidth, window.innerHeight)}px`;
        ripple.style.left = `${x - Math.max(window.innerWidth, window.innerHeight) / 2}px`;
        ripple.style.top = `${y - Math.max(window.innerWidth, window.innerHeight) / 2}px`;
        rippleContainer.appendChild(ripple);

        setTimeout(() => {
          window.location.href = this.getAttribute('href');
        }, 800);
        setTimeout(() => {
          ripple.remove();
        }, 800);
      });
    });

    
    document.querySelectorAll('.shake').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        this.classList.add('shake-effect');

        setTimeout(() => {
          window.location.href = this.getAttribute('href');
        }, 600);
        setTimeout(() => {
          this.classList.remove('shake-effect');
        }, 600);
      });
    });

   //zzzz
    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        const productRow = this.closest('.product-row');
        const productId = productRow.getAttribute('data-id');

        Swal.fire({
          title: 'Bạn có chắc chắn?',
          text: 'Bạn có muốn ẩn sản phẩm này không?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Có, ẩn nó!',
          cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            productRow.style.transition = 'opacity 0.5s ease';
            productRow.style.opacity = '0';
            setTimeout(() => {
              form.submit();
            }, 500);
          }
        });
      });
    });
  });
  </script>

  <nav aria-label="Page navigation">
    {{ $products->links('pagination::bootstrap-5') }}
  </nav>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
<script>
  document.getElementById('productImage')?.addEventListener('change', function(event) {
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