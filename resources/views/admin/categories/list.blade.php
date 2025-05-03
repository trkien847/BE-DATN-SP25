@extends('admin.layouts.layout')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between gap-3">
                        <div class="search-bar">
                            <span><i class="bx bx-search-alt"></i></span>
                            <input type="text" id="searchCategory" class="form-control" placeholder="Tìm kiếm danh mục...">
                        </div>
                        <div>
                            @if (auth()->user()->role_id == 3)
                                <a href="{{ route('categories.pending') }}" class="btn btn-success">
                                    Danh mục đợi duyệt
                                </a>
                            @endif
                            <a href="{{ route('categories.create') }}" class="btn btn-success">
                                <i class="bx bx-plus me-1"></i>Thêm Danh Mục
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
                                    <th class="border-0 py-2 sortable" data-sort="updated_at">Ngày cập nhật</th>
                                    <th class="border-0 py-2">Trạng Thái</th>
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
                                        <td>{{ $category->updated_at }}</td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input toggle-active" type="checkbox" role="switch"
                                                    data-id="{{ $category->id }}"
                                                    {{ $category->is_active ? 'checked' : '' }}>
                                            </div>
                                        </td>

                                        <td>
                                            <a href="{{ route('categories.edit', $category->id) }}"
                                                class="btn btn-sm btn-soft-secondary me-1">
                                                <i class="bx bx-edit fs-16"></i>
                                            </a>
                                            {{-- <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-soft-danger"
                                                    onclick="return confirm('Are you sure you want to delete this category?');">
                                                    <i class="bx bx-trash fs-16"></i>
                                                </button>
                                            </form> --}}
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
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input toggle-active" type="checkbox"
                                                            role="switch" data-id="{{ $subcategory->id }}"
                                                            data-parent-id="{{ $category->id }}"
                                                            {{ $subcategory->is_active ? 'checked' : '' }}
                                                            {{ !$category->is_active ? 'disabled' : '' }}>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            </tbody>
                            <!-- end tbody -->
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Hiển thị {{ $categories->firstItem() ?? 0 }} đến {{ $categories->lastItem() ?? 0 }}
                            của {{ $categories->total() ?? 0 }} sản phẩm
                        </div>
                        {{ $categories->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all features
            initializeSearch();
            initializeToggleSubcategories();
            initializeToggleActive();

            // Search functionality
            function initializeSearch() {
                const searchInput = document.getElementById('searchCategory');
                let timeoutId;

                searchInput.addEventListener('keyup', function() {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => {
                        const searchText = this.value;
                        const url = new URL(window.location.href);
                        url.searchParams.set('search', searchText);

                        fetch(url)
                            .then(response => response.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                document.querySelector('.table-responsive').innerHTML =
                                    doc.querySelector('.table-responsive').innerHTML;

                                // Reinitialize toggle features after search
                                initializeToggleSubcategories();
                                initializeToggleActive();

                                window.history.pushState({}, '', url);
                            })
                            .catch(error => console.error('Error:', error));
                    }, 300);
                });
            }

            // Toggle subcategories functionality
            function initializeToggleSubcategories() {
                document.querySelectorAll(".toggle-subcategories").forEach(button => {
                    button.addEventListener("click", function() {
                        const categoryId = this.getAttribute("data-category-id");
                        const subcategories = document.querySelectorAll(
                            `.subcategory-${categoryId}`);
                        const isExpanding = this.textContent === "+";

                        subcategories.forEach(sub => {
                            sub.style.display = isExpanding ? "table-row" : "none";
                        });

                        this.textContent = isExpanding ? "−" : "+";
                        this.classList.toggle("btn-primary", isExpanding);
                        this.classList.toggle("btn-outline-primary", !isExpanding);
                    });
                });
            }

            // Toggle active status functionality
            function initializeToggleActive() {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

                document.querySelectorAll(".toggle-active").forEach(checkbox => {
                    checkbox.addEventListener("change", function() {
                        const categoryId = this.getAttribute("data-id");
                        const isActive = this.checked;
                        const isParent = !this.hasAttribute("data-parent-id");
                        const checkboxElement = this;

                        Swal.fire({
                            title: 'Xác nhận thay đổi',
                            text: `Bạn có chắc chắn muốn ${isActive ? 'kích hoạt' : 'vô hiệu hóa'} danh mục này?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Đồng ý',
                            cancelButtonText: 'Hủy'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                handleStatusChange(categoryId, isActive, isParent,
                                    checkboxElement, csrfToken);
                            } else {
                                checkboxElement.checked = !isActive;
                            }
                        });
                    });
                });
            }

            // Handle category status change
            function handleStatusChange(categoryId, isActive, isParent, checkboxElement, csrfToken) {
                const url = isParent ?
                    `/categories/${categoryId}/toggle-active` :
                    `/categories/${categoryId}/toggle-subcategory-active`;

                fetch(url, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken
                        },
                        body: JSON.stringify({
                            is_active: isActive ? 1 : 0
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            handleSuccessResponse(categoryId, isActive, isParent);
                        } else {
                            handleErrorResponse(checkboxElement, data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        handleErrorResponse(checkboxElement);
                    });
            }

            // Handle success response
            function handleSuccessResponse(categoryId, isActive, isParent) {
                if (isParent) {
                    updateSubcategories(categoryId, isActive);
                }

                Swal.fire({
                    title: 'Thành công!',
                    text: 'Trạng thái danh mục đã được cập nhật',
                    icon: 'success',
                    timer: 1500
                });
            }

            // Update subcategories status
            function updateSubcategories(categoryId, isActive) {
                const subcategories = document.querySelectorAll(`[data-parent-id="${categoryId}"]`);
                subcategories.forEach(subcategory => {
                    const subCheckbox = subcategory.querySelector(".toggle-active");
                    if (subCheckbox) {
                        subCheckbox.disabled = !isActive;
                        if (!isActive) {
                            subCheckbox.checked = false;
                        }
                    }
                });
            }

            // Handle error response
            function handleErrorResponse(checkboxElement, message = 'Đã xảy ra lỗi khi cập nhật trạng thái!') {
                checkboxElement.checked = !checkboxElement.checked;
                Swal.fire({
                    title: 'Lỗi!',
                    text: message,
                    icon: 'error'
                });
            }
        });
    </script>
@endpush
@push('styles')
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
