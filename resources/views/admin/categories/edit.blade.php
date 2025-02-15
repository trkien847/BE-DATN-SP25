@extends('admin.layouts.layout')
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Category</div>
                    <div class="card-body">
                        <form id="categoryForm" action="{{ route('categories.update', $category->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $category->name) }}">
                                @error('name')
                                    <div class="text-danger text-sm mt-2 mb-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="subcategoriesContainer">
                                <div class="mb-3">
                                    <label class="form-label">Subcategories</label>
                                    <div id="subcategoryFields">
                                        @if ($category->categoryTypes->isNotEmpty())
                                            @foreach ($category->categoryTypes as $index => $subcategory)
                                                <div class="subcategory-field mb-2">
                                                    <div class="row g-2 align-items-center">
                                                        <div class="col">
                                                            <input type="text" class="form-control"
                                                                name="subcategories[]"
                                                                value="{{ old('subcategories.' . $index, $subcategory->name) }}"
                                                                placeholder="Enter subcategory name">
                                                            @error('subcategories.' . $index)
                                                                <div class="text-danger text-sm mt-2 mb-2">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-auto">
                                                            <button type="button"
                                                                class="btn btn-outline-secondary icon-select-btn"
                                                                data-bs-toggle="modal" data-bs-target="#iconModal">
                                                                <i class="fas fa-icons"></i> Select Icon
                                                            </button>
                                                            <input type="hidden" name="subcategory_icons[]"
                                                                value="{{ old('subcategory_icons.' . $index, $subcategory->icon) }}"
                                                                class="icon-input">
                                                            @error('subcategory_icons.' . $index)
                                                                <div class="text-danger text-sm mt-2 mb-2">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-auto selected-icon">
                                                            @if ($subcategory->icon)
                                                                <i class="{{ $subcategory->icon }}" aria-hidden="true"></i>
                                                            @endif
                                                        </div>
                                                        <div class="col-auto">
                                                            <button type="button"
                                                                class="btn btn-danger remove-subcategory">X</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="subcategory-field mb-2">
                                                <div class="row g-2 align-items-center">
                                                    <div class="col">
                                                        <input type="text" class="form-control" name="subcategories[]"
                                                            placeholder="Enter subcategory name">
                                                    </div>
                                                    <div class="col-auto">
                                                        <button type="button"
                                                            class="btn btn-outline-secondary icon-select-btn"
                                                            data-bs-toggle="modal" data-bs-target="#iconModal">
                                                            <i class="fas fa-icons"></i> Select Icon
                                                        </button>
                                                        <input type="hidden" name="subcategory_icons[]" class="icon-input">
                                                    </div>
                                                    <div class="col-auto selected-icon"></div>
                                                    <div class="col-auto">
                                                        <button type="button"
                                                            class="btn btn-danger remove-subcategory">X</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-secondary mt-2" id="addSubcategory">
                                        Add Subcategory
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Category</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Icon Selection Modal -->
    <div class="modal fade" id="iconModal" tabindex="-1" aria-labelledby="iconModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="iconModalLabel">Select Icon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="iconSearch" class="form-control mb-3" placeholder="Search icons...">
                    <div class="row row-cols-6 g-3" id="iconGrid">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addSubcategoryBtn = document.getElementById('addSubcategory');
            const iconModalElement = document.getElementById('iconModal');
            const iconModal = new bootstrap.Modal(iconModalElement);
            const iconSearch = document.getElementById('iconSearch');
            let currentIconButton = null;

            // Add new subcategory field
            addSubcategoryBtn.addEventListener('click', function() {
                const subcategoryFields = document.getElementById('subcategoryFields');
                const newField = document.createElement('div');
                newField.className = 'subcategory-field mb-2';
                newField.innerHTML = `
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <input type="text" class="form-control" name="subcategories[]" placeholder="Enter subcategory name">
                        @error('subcategories.*')
                            <div class="text-danger text-sm mt-2 mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-outline-secondary icon-select-btn" data-bs-toggle="modal" data-bs-target="#iconModal">
                            <i class="fas fa-icons"></i> Select Icon
                        </button>
                        <input type="hidden" name="subcategory_icons[]" class="icon-input">
                        @error('subcategory_icons.*')
                            <div class="text-danger text-sm mt-2 mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-auto selected-icon"></div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-danger remove-subcategory">X</button>
                    </div>
                </div>`;
                subcategoryFields.appendChild(newField);
            });

            // Attach listeners to icon selection and subcategory removal
            document.addEventListener('click', function(e) {
                if (e.target.closest('.icon-select-btn')) {
                    currentIconButton = e.target.closest('.icon-select-btn');
                }
                if (e.target.classList.contains('remove-subcategory')) {
                    e.target.closest('.subcategory-field').remove();
                }
            });

            // Populate icons in the modal
            async function populateIcons(searchTerm = '') {
                const iconGrid = document.getElementById('iconGrid');
                iconGrid.innerHTML = '';

                // Lấy danh sách biểu tượng từ API của FontAwesome
                const response = await fetch(
                'https://api.fontawesome.com/icons?access_token=YOUR_ACCESS_TOKEN');
                const data = await response.json();
                const icons = data.icons;

                // Lọc và hiển thị các biểu tượng
                icons.filter(icon => icon.includes(searchTerm)).forEach(icon => {
                    const div = document.createElement('div');
                    div.className = 'col text-center';
                    div.innerHTML = `<div class="icon-option p-2 border rounded" data-icon="fa fa-${icon}">
                        <i class="fa fa-${icon}" aria-hidden="true"></i>
                    </div>`;
                    iconGrid.appendChild(div);
                });
            }
            populateIcons();

            // Search and select icon logic
            iconSearch.addEventListener('input', (e) => {
                populateIcons(e.target.value);
            });

            document.addEventListener('click', (e) => {
                if (e.target.closest('.icon-option')) {
                    const selectedIcon = e.target.closest('.icon-option').dataset.icon;
                    const parentField = currentIconButton.closest('.subcategory-field');
                    parentField.querySelector('.icon-input').value = selectedIcon;
                    parentField.querySelector('.selected-icon').innerHTML = `
                    <i class="${selectedIcon}" aria-hidden="true"></i>`;

                    // Manually hide the modal
                    iconModal.hide();

                    // Forcefully remove the backdrop (this is key)
                    const modalBackdrop = document.querySelector('.modal-backdrop');
                    if (modalBackdrop) {
                        modalBackdrop.remove(); // Completely remove the backdrop element
                    }

                    // Reset the body class and style manually to avoid locked state
                    document.body.classList.remove('modal-open');
                    document.body.style.paddingRight = ''; // Reset any added padding
                }
            });
        });
    </script>
@endpush
@push('styles')
    <style>
        .iconify-icon {
            opacity: 1 !important;
            filter: none !important;
        }
    </style>
@endpush
