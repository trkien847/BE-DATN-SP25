@extends('admin.layout')
@section('titlepage','')

@section('content')
<style>
.pagination {
    margin-top: 20px;
    display: flex;
    justify-content: center;
}

.pagination a {
    margin: 0 5px;
    padding: 5px 10px;
    text-decoration: none;
    background-color: #316b7d;
    color: #fff;
    border-radius: 3px;
}
.pagination li {
    list-style-type: none;
}
</style>
<main>
    <div class="container-fluid px-4">
      <h1 class="mt-4">List products</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>

      <!-- Data -->
      <div class="card mb-4">
        <div class="card-header">
          <i class="fas fa-table me-1"></i>
          List categories
        </div>
        {{-- <form action="index.php?act=list_pro" method="post">
            <select class="form-select" name="category_id" id="">
                <option value="0">Chon danh muc</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
          <input class="btn btn-primary" type="submit" name="listok" value="GO">
        </form> --}}
        <div class="card-body">
                        {{-- Hiển thị thông báo --}}
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
          <table id="datatablesSimple">
            <thead>
              <tr>
                <th>ID</th>
                <th>Category name</th>
                <th>Product name</th>
                <th>Image</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
                @foreach($products as $item)
                 <tr>
                    <td>{{ $item->idProduct }}</td>
                    <td>{{ $item->category->name }}</td>
                    <td>{{ $item->name }}</td>
                    <td><img src="{{ asset('upload/'.$item->img)  }}" width="200" height="150" alt=""></td>
                    <td>{{ number_format($item->price,0,'.',',')}} $</td>
                    <td>{{ number_format($item->discount,0,'.',',') }} %</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="{{ $item->is_type == true ? 'text-success' : 'text-danger' }}">
                        {{ $item->is_type == true ? 'Display' : 'Hidden' }}
                      </td>
                    <td>
                    <a href="" class="btn btn-warning">
                    <!-- Thêm nút update -->
                      <form action="{{ route('admin.products.productUpdateForm', $item->id) }}" method="GET">
                          <button type="submit">
                                    Edit
                         </button>
                     </form>
                    </a>
                     <!-- Thêm nút delete -->
                     <a href="" class="btn btn-danger">
                        <form action="{{ route('admin.products.productDestroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                                    {{-- Sử dụng @method('DELETE') trong đoạn mã nhằm mục đích gửi một yêu cầu HTTP DELETE từ form HTML.  --}}
                                    <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')">
                                        Delete
                                    </button>
                         </form>
                     </a>
                    </td>
                  </tr>
                  @endforeach
            </tbody>
          </table>
          <div class="d-flex justify-content-center">
            {{ $products->links('pagination::default') }}
         </div>
          <a href="{{ route('admin.products.viewProAdd') }}">
            <input type="submit" class="btn btn-primary" name="them" value="ADD">
          </a>
        </div>
      </div>
    </div>
  </main>

@endsection
