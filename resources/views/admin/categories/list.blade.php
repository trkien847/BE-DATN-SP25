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
                                    <th class="border-0 py-2 sortable" data-sort="name">Tên danh mục</th>
                                    <th class="border-0 py-2 sortable" data-sort="created_at">Ngày tạo</th>
                                    <th class="border-0 py-2 sortable" data-sort="updated_at">Ngày cập nhật</th>
                                    <th class="border-0 py-2">Hành Động</th>
                                </tr>
                            </thead> <!-- end thead-->
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr class="category-row" data-category-id="{{ $category->id }}">
                                        <td>
                                            <a href="javascript:void(0);" class="fw-medium">{{ $category->id }}</a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h5 class="fs-14 m-0 fw-normal">{{ $category->name }}</h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h5 class="fs-14 m-0 fw-normal">{{ $category->created_at }}</h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h5 class="fs-14 m-0 fw-normal">{{ $category->updated_at }}</h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('categories.edit', $category->id) }}"
                                                class="btn btn-sm btn-soft-secondary me-1">
                                                <i class="bx bx-edit fs-16"></i>
                                            </a>
                                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-soft-danger"
                                                    onclick="return confirm('Are you sure you want to delete this category?');">
                                                    <i class="bx bx-trash fs-16"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @if ($category->categoryTypes->isNotEmpty())
                                        @foreach ($category->categoryTypes as $subcategory)
                                            <tr class="subcategory-row" data-parent-id="{{ $category->id }}"
                                                style="display: none;">
                                                <td>
                                                    <a href="javascript:void(0);"
                                                        class="fw-medium">{{ $subcategory->id }}</a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="ms-4">
                                                            <h5 class="fs-14 m-0 fw-normal">{{ $subcategory->name }}</h5>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="ms-4">
                                                            <h5 class="fs-14 m-0 fw-normal">{{ $subcategory->created_at }}
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="ms-4">
                                                            <h5 class="fs-14 m-0 fw-normal">{{ $subcategory->updated_at }}
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            </tbody> <!-- end tbody -->
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
        document.addEventListener('DOMContentLoaded', function() {
            const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

            const comparer = (idx, asc) => (a, b) => ((v1, v2) =>
                v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
            )(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

            document.querySelectorAll('.sortable').forEach(th => th.addEventListener('click', (() => {
                const table = th.closest('table');
                Array.from(table.querySelectorAll('tbody > tr'))
                    .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this
                        .asc))
                    .forEach(tr => table.querySelector('tbody').appendChild(tr));
            })));

            document.querySelectorAll('.category-row').forEach(function(row) {
                row.addEventListener('click', function() {
                    const categoryId = this.getAttribute('data-category-id');
                    const subRows = document.querySelectorAll(
                        `.subcategory-row[data-parent-id="${categoryId}"]`);

                    // Toggle display of subcategory rows
                    subRows.forEach(function(subRow) {
                        subRow.style.display = subRow.style.display === 'none' ?
                            'table-row' : 'none';
                    });

                    // Toggle active class on category row
                    this.classList.toggle('active');

                    // Change background color of subcategory rows
                    subRows.forEach(function(subRow) {
                        if (subRow.style.display === 'table-row') {
                            subRow.classList.add('highlight');
                        } else {
                            subRow.classList.remove('highlight');
                        }
                    });
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
