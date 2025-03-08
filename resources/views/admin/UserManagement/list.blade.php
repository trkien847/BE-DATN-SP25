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
.table td, .table th {
    text-align: center;
    vertical-align: middle;
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
    <h4 class="text-secondary">DANH SÁCH NGƯỜI DÙNG</h4>
    <div class="d-flex flex-wrap justify-content-between gap-3">
      <a href="{{ route('products.add') }}" class="btn btn-success shake">
        <i class="bi bi-plus-circle"></i><i class="bx bx-plus me-1"></i>
        Thêm Người Dùng
      </a>
      
    </div>
  </div>

  <div class="d-flex flex-wrap justify-content-between gap-3">
    <form action="{{ route('admin.users.list') }}" method="GET" class="search-bar">
      <span><i class="bx bx-search-alt"></i></span>
      <input type="text" name="search" class="form-control" placeholder="Tìm kiếm người dùng..." value="{{ request('search') }}">
      <button type="submit" class="btn btn-primary">
        <i class="bi bi-search"></i> Tìm Kiếm
      </button>
    </form>

    <table class="table table-hover table-bordered text-center align-middle">

      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Tên người dùng</th>
          <th scope="col">Email</th>
          <th scope="col">Số điện thoại</th>
          <th scope="col">Vai trò</th>
          <th scope="col">Trạng Thái</th>
          <th scope="col">Hành Động</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $user->fullname }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone_number }}</td>
            <td>
                {{ ['Admin', 'Staff', 'Customer'][$user->role_id - 1] ?? 'Người dùng' }}
            </td>
            
            <td>
                <span class="badge {{ $user->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                    {{ $user->status == 'Online' ? 'Online' : 'Offline' }}
                </span>
            </td>
            <td>
              <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm ripple">
                <i class="bx bx-edit fs-16"></i>
            </a>            
                <a href="{{ route('admin.users.create', $user->id) }}" class="btn btn-info btn-sm" title="Chi tiết người dùng">
                    <i class="bx bx-detail fs-16"></i>
                </a>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bx bx-hide fs-16"></i>
                    </button>
                </form>
            </td>
            
            
        @endforeach
    </tbody>
    </table>
  </div>

  <script>
  </script>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>

@endsection