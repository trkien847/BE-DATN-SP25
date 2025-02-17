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
                                    <th class="border-0 py-2 sortable" data-sort="name">Tên danh mục69</th>
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
                                                        <input class="form-check-input toggle-active" type="checkbox" role="switch"
                                                            data-id="{{ $subcategory->id }}" data-parent-id="{{ $category->id }}" 
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
                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of
                            {{ $categories->total() }} entries
                        </div>
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
    <script>
        // document.addEventListener('DOMContentLoaded', function() {


        //     document.querySelectorAll('.category-row').forEach(function(row) {
        //         row.addEventListener('click', function() {
        //             const categoryId = this.getAttribute('data-category-id');
        //             const subRows = document.querySelectorAll(
        //                 `.subcategory-row[data-parent-id="${categoryId}"]`);

        //             // Toggle display of subcategory rows
        //             subRows.forEach(function(subRow) {
        //                 subRow.style.display = subRow.style.display === 'none' ?
        //                     'table-row' : 'none';
        //             });

        //             // Toggle active class on category row
        //             this.classList.toggle('active');

        //             // Change background color of subcategory rows
        //             subRows.forEach(function(subRow) {
        //                 if (subRow.style.display === 'table-row') {
        //                     subRow.classList.add('highlight');
        //                 } else {
        //                     subRow.classList.remove('highlight');
        //                 }
        //             });
        //         });
        //     });
        // });
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
                                                false; // Tắt danh mục con khi cha tắt
                                            }
                                        }
                                        subcategory.style.display = isActive === 1 ?
                                            "table-row" :
                                            "none"; // Hiện/tắt danh mục con
                                    });
                                }
                            } else {
                                console.error(
                                    "Không thể bật danh mục con nếu danh mục cha đang tắt.");
                                this.checked = !
                                isActive; // Hoàn tác checkbox nếu không thành công
                            }
                        })
                        .catch(error => console.error("Lỗi:", error));
                });
            });
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
