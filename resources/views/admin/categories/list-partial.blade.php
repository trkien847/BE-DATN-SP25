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