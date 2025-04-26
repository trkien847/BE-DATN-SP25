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
    {{-- <div class="d-flex flex-wrap justify-content-between gap-3">
      <a href="{{ route('admin.users.create') }}" class="btn btn-success shake">
        <i class="bx bx-plus me-1"></i>Thêm người dùng
      </a>
      
    </div> --}}
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
                {{ $user->role->name }}
                {{-- Debug giá trị role --}}
            </td>
            
              <td>
                <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                    <div class="form-check form-switch">
                        <input class="form-check-input toggle-status" type="checkbox" role="switch"
                            data-id="{{ $user->id }}"
                            {{ $user->status === 'Online' ? 'checked' : '' }}
                            {{ $user->role_id == 3 ? 'disabled style=opacity:0.5;' : '' }}>
                    </div>
                </div>
            </td>
          
          
          
            <td>
              <a href="{{ $user->role_id != 3 ? route('admin.users.edit', $user->id) : '#' }}" 
                class="btn btn-success btn-sm ripple {{ $user->role_id == 3 ? 'disabled' : '' }}" 
                {{ $user->role_id == 3 ? 'style=opacity:0.5;pointer-events:none;' : '' }}>
                 <i class="bx bx-edit fs-16"></i>
             </a>          
                <a href="{{ route('admin.users.detail', $user->id) }}" class="btn btn-primary btn-sm" title="Chi tiết người dùng">
                    <i class="bx bx-detail fs-16"></i>
                </a>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline delete-form">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="btn btn-danger btn-sm delete-button" data-id="{{ $user->id }}">
                      <i class="bx bx-hide fs-16"></i>
                  </button>
              </form>              
            </td>
           
            
        @endforeach
    </tbody>
    </table>
  </div>
  

  {{-- Phân trang --}}
<div class="pagination-simple mt-3 text-center">
  <!-- Previous -->
  @if ($users->currentPage() > 1)
      <a href="{{ $users->previousPageUrl() . ($search ? '&search=' . $search : '') }}" class="btn btn-sm btn-outline-primary mx-1">Trước</a>
  @else
      <span class="btn btn-sm btn-outline-secondary mx-1 disabled">Trước</span>
  @endif

  <!-- Số trang -->
  @for ($i = 1; $i <= $users->lastPage(); $i++)
      @if ($i == $users->currentPage())
          <span class="btn btn-sm btn-primary mx-1">{{ $i }}</span>
      @else
          <a href="{{ $users->url($i) . ($search ? '&search=' . $search : '') }}" class="btn btn-sm btn-outline-primary mx-1">{{ $i }}</a>
      @endif
  @endfor

  <!-- Next -->
  @if ($users->hasMorePages())
      <a href="{{ $users->nextPageUrl() . ($search ? '&search=' . $search : '') }}" class="btn btn-sm btn-outline-primary mx-1">Tiếp</a>
  @else
      <span class="btn btn-sm btn-outline-secondary mx-1 disabled">Tiếp</span>
  @endif
</div>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-button');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('form');
            Swal.fire({
                title: 'Bạn có chắc chắn muốn xóa?',
                text: "Hành động này không thể hoàn tác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});

  </script>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>

@endsection