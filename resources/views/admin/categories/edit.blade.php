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
                                                                class="btn btn-danger remove-subcategory">X</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
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
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addSubcategoryBtn = document.getElementById('addSubcategory');

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
                        <button type="button" class="btn btn-danger remove-subcategory">X</button>
                    </div>
                </div>`;
                subcategoryFields.appendChild(newField);
            });

            // Remove subcategory field
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-subcategory')) {
                    e.target.closest('.subcategory-field').remove();
                }
            });
        });
    </script>
@endpush
