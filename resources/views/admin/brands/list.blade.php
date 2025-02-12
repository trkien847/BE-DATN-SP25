@extends('admin.layouts.layout')
@section('content')
    <style>
        /* CSS cho nút chuyển đổi */
        .switch {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 20px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked+.slider:before {
            transform: translateX(14px);
        }

        .slider.active {
            background-color: #4CAF50;
        }

        .slider.inactive {
            background-color: #ccc;
        }
    </style>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between gap-3">
                        <div class="search-bar">
                            <span><i class="bx bx-search-alt"></i></span>
                            <form action="{{ route('brands.list') }}" method="GET" class="d-flex justify-content-center">
                                @csrf
                                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm thương hiệu ..."
                                    value="{{ request('search') }}">
                                <button style="width: 20vh;" type="submit" class="btn btn-primary ms-2">Tìm kiếm</button>
                            </form>

                        </div>
                        <div>
                            <a href="{{ route('brands.create') }}" class="btn btn-success">
                                <i class="bx bx-plus me-1"></i>Thêm Thương Hiệu
                            </a>
                        </div>
                    </div> <!-- end row -->
                </div>
                <div>
                    <div class="table-responsive table-centered">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tên Thương Hiệu</th>
                                    <th scope="col">Mô tả thương hiệu</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col">Logo</th>

                                    <th scope="col">Kích Hoạt</th>
                                    <th scope="col">Sửa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($brands as $brand)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $brand->name }}</td>
                                        <td>{{ $brand->description }}</td>
                                        <td>{{ $brand->slug }}</td>
                                        <!-- <td>{{ $brand->slug }}</td> -->

                                        <td>
                                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="Logo" width="50">
                                        </td>

                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" class="toggle-active" data-id="{{ $brand->id }}"
                                                    {{ $brand->is_active ? '' : 'checked' }}>
                                                <span
                                                    class="slider round {{ $brand->is_active ? 'inactive' : 'active' }}"></span>
                                            </label>
                                        </td>
                                        <td>
                                            
                                            <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-primary btn-sm">Sửa</a>
                                            
                                            
                                            
                                            
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- <a style="margin-left: 10px" class="btn btn-success mb-3 " href="{{ route('brands.listActive') }}">Thương hiệu chưa hoạt động   </a> --}}
                        <div class="d-flex justify-content-center">
                            {{ $brands->links('pagination::bootstrap-4') }}
                        </div>
                    </div> <!-- table responsive -->
                </div>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleSwitches = document.querySelectorAll('.toggle-active');
            toggleSwitches.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const brandId = this.getAttribute('data-id');
                    const isActive = this.checked ? 0 :
                        1; // 0 = Active (Green), 1 = Inactive (Gray)

                    fetch(`/api/brands/${brandId}/toggle-active`, {
                            method: 'POST',
                            headers: {
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify({
                                is_active: isActive
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.nextElementSibling.classList.toggle('active', isActive ===
                                    0);
                                this.nextElementSibling.classList.toggle('inactive',
                                    isActive === 1);
                                Toastify({
                                    text: "Trạng thái đã được cập nhập!",
                                    gravity: "top", // `top` or `bottom`
                                    position: "center", // `left`, `center` or `right`
                                    backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                                    duration: 3000
                                }).showToast();

                            } else {
                                alert('Failed to update status');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        });
    </script>
@endsection
