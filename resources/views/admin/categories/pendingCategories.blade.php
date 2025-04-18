@extends('admin.layouts.layout')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between gap-3">
                        <div class="search-bar">
                            <span><i class="bx bx-search-alt"></i></span>
                            <input type="search" class="form-control" id="search" placeholder="Search categories...">
                        </div>
                        <div>
                            <a href="{{ route('categories.list') }}" class="btn btn-success">
                                <i class="bx bx-plus me-1"></i>Danh mục
                            </a>
                        </div>
                    </div> <!-- end row -->
                </div>
                <div>
                    <div class="table-responsive table-centered">
                        <table class="table text-nowrap mb-0" id="categoryTable">
                            <thead class="bg-light bg-opacity-50">
                                <tr>
                                    <th class="border-0 py-2 sortable" data-sort="id">ID</th>
                                    <th class="border-0 py-2 sortable" data-sort="name">Tên danh mục</th>
                                    <th class="border-0 py-2 sortable" data-sort="created_at">Ngày tạo</th>
                                    <th class="border-0 py-2">Hành Động</th>
                                </tr>
                            </thead> <!-- end thead-->
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr class="category-row" data-category-id="{{ $category->id }}">
                                        <td>
                                            @if ($category->categoryTypes->isNotEmpty())
                                                <button class="toggle-subcategories btn btn-sm btn-outline-primary"
                                                    data-category-id="{{ $category->id }}">
                                                    +
                                                </button>
                                            @endif
                                            <a href="javascript:void(0);" class="fw-medium">{{ $category->id }}</a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h5 class="fs-14 m-0 fw-normal">{{ $category->name }}</h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $category->created_at }}</td>

                                        <td>
                                            @if ($category->status == 'pending')
                                                <button class="btn btn-sm btn-soft-success approve-category"
                                                    data-id="{{ $category->id }}">
                                                    <i class="bx bx-check fs-16"></i> Duyệt
                                                </button>

                                                <button class="btn btn-sm btn-soft-danger reject-category"
                                                    data-id="{{ $category->id }}">
                                                    <i class="bx bx-x fs-16"></i> Từ chối
                                                </button>
                                            @endif
                                        </td>

                                    </tr>
                                    @if ($category->categoryTypes->isNotEmpty())
                                        @foreach ($category->categoryTypes as $subcategory)
                                            <tr class="subcategory-row subcategory-{{ $category->id }}"
                                                data-parent-id="{{ $category->id }}" style="display: none;">
                                                <td></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="ms-4">
                                                            <h5 class="fs-14 m-0 fw-normal">{{ $subcategory->name }}</h5>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $subcategory->created_at }}</td>
                                                <td>{{ $subcategory->updated_at }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            </tbody>
                            <!-- end tbody -->
                        </table>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".toggle-subcategories").forEach(button => {
                button.addEventListener("click", function() {
                    let categoryId = this.getAttribute("data-category-id");
                    let subcategories = document.querySelectorAll(`.subcategory-${categoryId}`);

                    subcategories.forEach(sub => {
                        sub.style.display = sub.style.display === "none" ? "table-row" :
                            "none";
                    });

                    // Đổi dấu "+" thành "-" khi mở danh mục con
                    if (this.textContent === "+") {
                        this.textContent = "−";
                        this.classList.add("btn-primary");
                        this.classList.remove("btn-outline-primary");
                    } else {
                        this.textContent = "+";
                        this.classList.add("btn-outline-primary");
                        this.classList.remove("btn-primary");
                    }
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

            document.querySelectorAll(".toggle-active").forEach(checkbox => {
                checkbox.addEventListener("change", function() {
                    let categoryId = this.getAttribute("data-id");
                    let isActive = this.checked ? 1 : 0;
                    let isParent = this.getAttribute("data-parent-id") ===
                        null; // Kiểm tra danh mục cha/con

                    let url = isParent ?
                        `/categories/${categoryId}/toggle-active` :
                        `/categories/${categoryId}/toggle-subcategory-active`;

                    fetch(url, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrfToken
                            },
                            body: JSON.stringify({
                                is_active: isActive
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log("Cập nhật trạng thái thành công!");

                                if (isParent) {
                                    let subcategories = document.querySelectorAll(
                                        `[data-parent-id="${categoryId}"]`);
                                    subcategories.forEach(subcategory => {
                                        let subCheckbox = subcategory.querySelector(
                                            ".toggle-active");
                                        if (subCheckbox) {
                                            subCheckbox.disabled = isActive ===
                                                0; // Nếu cha tắt, vô hiệu hóa danh mục con
                                            if (isActive === 0) {
                                                subCheckbox.checked =
                                                    false;
                                            }
                                        }
                                        subcategory.style.display = isActive === 1 ?
                                            "table-row" :
                                            "none";
                                    });
                                }
                            } else {
                                console.error(
                                    "Không thể bật danh mục con nếu danh mục cha đang tắt.");
                                this.checked = !
                                    isActive;
                            }
                        })
                        .catch(error => console.error("Lỗi:", error));
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

            // Xử lý khi bấm nút "Duyệt"
            document.querySelectorAll(".approve-category").forEach(button => {
                button.addEventListener("click", function() {
                    let categoryId = this.getAttribute("data-id");
                    let row = this.closest('tr'); // Lấy dòng chứa nút

                    fetch(`/category/${categoryId}/accept`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrfToken
                            }
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Xóa các nút action
                                row.querySelector('td:last-child').innerHTML =
                                    '<span class="badge bg-success">Đã duyệt</span>';

                                // Hiển thị thông báo thành công
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Thành công',
                                    text: data.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi',
                                    text: data.message || 'Có lỗi xảy ra'
                                });
                            }
                        })
                        .catch(error => {
                            console.error("Lỗi:", error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: 'Có lỗi xảy ra khi xử lý yêu cầu'
                            });
                        });
                });
            });

            // Xử lý khi bấm nút "Từ chối"
            document.querySelectorAll(".reject-category").forEach(button => {
                button.addEventListener("click", function() {
                    let categoryId = this.getAttribute("data-id");
                    let row = this.closest('tr');

                    Swal.fire({
                        title: 'Xác nhận',
                        text: 'Bạn có chắc chắn muốn từ chối danh mục này?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/category/${categoryId}/reject`, {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": csrfToken
                                    }
                                })
                                .then(response => {
                                    if (!response.ok) throw new Error(
                                        'Network response was not ok');
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        // Xóa các nút action
                                        row.querySelector('td:last-child').innerHTML =
                                            '<span class="badge bg-danger">Đã từ chối</span>';

                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Thành công',
                                            text: data.message,
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Lỗi',
                                            text: data.message ||
                                                'Có lỗi xảy ra'
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error("Lỗi:", error);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Lỗi',
                                        text: 'Có lỗi xảy ra khi xử lý yêu cầu'
                                    });
                                });
                        }
                    });
                });
            });
        });
    </script>
@endpush
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css" rel="stylesheet">
    <style>
        .category-row.active {
            background-color: #f0f0f0;
            /* Change this to your desired active color */
        }

        .subcategory-row.highlight {
            background-color: #e0e0e0;
            /* Change this to your desired highlight color */
        }

        .subcategory-row td {
            padding-left: 20px;
            /* Adjust the padding to indent subcategory rows */
        }
    </style>
@endpush
