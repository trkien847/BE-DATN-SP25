@extends('admin.layout')
@section('titlepage','')

@section('content')

<main>
    <div class="container-fluid px-4">
      <h1 class="mt-4">List categories</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>

      <!-- Data -->
      <div class="card mb-4">

        {{-- hien thi tb success --}}

        <div class="card-header">
          <i class="fas fa-table me-1"></i>
          List categories
        </div>
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
                <th>Image</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
                @foreach($categories as $item)
            <tr>
              <td> {{ $item->id }}</td>
              <td>{{ $item->name }}</td>
              <td><img src="{{ asset('upload/'.$item->img)  }}" height="150" width="300" alt=""></td>
              <td class="{{ $item->status == true ? 'text-success' : 'text-danger' }}">
                {{ $item->status == true ? 'Display' : 'Hidden' }}
              </td>
              <td class="text-center">
                   <!-- Thêm nút update -->
                   <a href="" class="btn btn-warning">
                    <form action="{{ route('admin.categories.cateUpdateForm', $item->id) }}" method="GET">
                        <button type="submit">
                                  Edit
                       </button>
                        </form>
                   </a>
                 <!-- Thêm nút delete -->
                 <a href="" class="btn btn-danger">
                    <form action="{{ route('admin.categories.cateDestroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        {{-- Sử dụng @method('DELETE') trong đoạn mã nhằm mục đích gửi một yêu cầu HTTP DELETE từ form HTML.  --}}
                        <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">
                            Delete
                        </button>
                    </form>
                 </a>
              </td>
            </tr>
            @endforeach
            </tbody>
          </table>
          <a href="{{ route('admin.categories.viewCateAdd') }}">
            <input type="submit" class="btn btn-primary" name="them" value="ADD">
          </a>
        </div>
      </div>
    </div>
  </main>

@endsection
