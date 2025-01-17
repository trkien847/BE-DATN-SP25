@extends('admin.layouts.layout')
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add Category</div>
                    <div class="card-body">
                        <form id="categoryForm" action="{{ route('categories.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name">
                                @error('name')
                                    <div class="text-danger text-sm mt-2 mb-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="isSubcategory" name="is_subcategory">
                                <label class="form-check-label" for="isSubcategory">Is Subcategory</label>
                            </div>

                            <div id="subcategoriesContainer">
                                <div class="mb-3">
                                    <label class="form-label">Subcategories</label>
                                    <div id="subcategoryFields">
                                        <div class="subcategory-field mb-2">
                                            <div class="row g-2 align-items-center">
                                                <div class="col">
                                                    <input type="text" class="form-control" name="subcategories[]"
                                                        placeholder="Enter subcategory name">
                                                    @error('subcategories.0')
                                                        <div class="text-danger text-sm mt-2 mb-2">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-auto">
                                                    <button type="button" class="btn btn-outline-secondary icon-select-btn"
                                                        data-bs-toggle="modal" data-bs-target="#iconModal">
                                                        <i class="fas fa-icons"></i> Select Icon
                                                    </button>
                                                    <input type="hidden" name="subcategory_icons[]" class="icon-input">
                                                    @error('subcategory_icons.0')
                                                        <div class="text-danger text-sm mt-2 mb-2">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-auto">
                                                    <button type="button"
                                                        class="btn btn-danger remove-subcategory">X</button>
                                                </div>
                                                <div class="col-auto selected-icon">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-secondary mt-2" id="addSubcategory">
                                        Add Subcategory
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Add Category</button>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const subcategoriesContainer = document.getElementById('subcategoriesContainer');
            const addSubcategoryBtn = document.getElementById('addSubcategory');
            const isSubcategoryCheckbox = document.getElementById('isSubcategory');
            const iconModal = new bootstrap.Modal(document.getElementById('iconModal'));
            const iconSearch = document.getElementById('iconSearch');
            let currentIconButton = null;

            // Initially hide the subcategories container
            subcategoriesContainer.style.display = 'none';

            // List of Iconify icons (Solar icons set as example)
            const icons = [
                'solar:home-2-broken',
                'solar:shop-broken',
                'solar:cart-large-broken',
                'solar:user-broken',
                'solar:heart-broken',
                'solar:star-broken',
                'solar:settings-broken',
                'solar:mail-broken',
                'solar:phone-broken',
                'solar:camera-broken',
                'solar:music-notes-broken',
                'solar:videocamera-broken',
                'solar:book-broken',
                'solar:calendar-broken',
                'solar:map-point-broken',
                'solar:gift-broken',
                'solar:cup-star-broken',
                'solar:bell-broken',
                'solar:flag-broken',
                'solar:smile-broken',
                'solar:sun-broken',
                'solar:moon-broken',
                'solar:cloud-broken',
                'solar:umbrella-broken',
                'solar:car-broken',
                'solar:airplane-broken',
                'solar:ship-broken',
                'solar:train-broken',
                'solar:bicycle-broken',
                'solar:coffee-cup-broken'
            ];

            // Populate icon grid
            function populateIcons(searchTerm = '') {
                const iconGrid = document.getElementById('iconGrid');
                iconGrid.innerHTML = '';

                const filteredIcons = icons.filter(icon =>
                    icon.toLowerCase().includes(searchTerm.toLowerCase())
                );

                filteredIcons.forEach(icon => {
                    const div = document.createElement('div');
                    div.className = 'col text-center';
                    div.innerHTML = `
                <div class="icon-option p-2 border rounded cursor-pointer" style="cursor: pointer;" data-icon="${icon}">
                    <iconify-icon icon="${icon}" width="24" height="24"></iconify-icon>
                </div>
            `;
                    iconGrid.appendChild(div);
                });

                // Reattach icon selection event listeners
                attachIconListeners();
            }

            // Search functionality
            iconSearch.addEventListener('input', (e) => {
                populateIcons(e.target.value);
            });

            // Initial population of icons
            populateIcons();

            // Toggle subcategories container based on checkbox
            isSubcategoryCheckbox.addEventListener('change', function() {
                subcategoriesContainer.style.display = this.checked ? 'block' : 'none';
            });

            // Add new subcategory field
            addSubcategoryBtn.addEventListener('click', function() {
                const subcategoryFields = document.getElementById('subcategoryFields');
                const newField = document.createElement('div');
                newField.className = 'subcategory-field mb-2';
                newField.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col">
                    <input type="text" class="form-control" name="subcategories[]" placeholder="Enter subcategory name">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-outline-secondary icon-select-btn" data-bs-toggle="modal" data-bs-target="#iconModal">
                        <iconify-icon icon="solar:gallery-add-broken"></iconify-icon>
                        Select Icon
                    </button>
                    <input type="hidden" name="subcategory_icons[]" class="icon-input">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-danger remove-subcategory">X</button>
                </div>
                <div class="col-auto selected-icon">
                    <!-- Selected icon will appear here -->
                </div>
            </div>
        `;
                subcategoryFields.appendChild(newField);
            });

            // Function to attach icon selection listeners
            function attachIconListeners() {
                document.querySelectorAll('.icon-option').forEach(option => {
                    option.addEventListener('click', function() {
                        const icon = this.dataset.icon;
                        const parentField = currentIconButton.closest('.subcategory-field');
                        const iconInput = parentField.querySelector('.icon-input');
                        const selectedIconDiv = parentField.querySelector('.selected-icon');

                        iconInput.value = icon;
                        selectedIconDiv.innerHTML =
                            `<iconify-icon icon="${icon}" width="24" height="24"></iconify-icon>`;

                        // Hide the modal
                        iconModal.hide();

                        // Remove the modal backdrop and allow scrolling
                        document.body.classList.remove('modal-open');
                        const backdrop = document.querySelector('.modal-backdrop');
                        if (backdrop) {
                            backdrop.remove();
                        }
                    });
                });
            }

            // Track current icon button being clicked
            document.addEventListener('click', function(e) {
                if (e.target.closest('.icon-select-btn')) {
                    currentIconButton = e.target.closest('.icon-select-btn');
                }
                if (e.target.classList.contains('remove-subcategory')) {
                    e.target.closest('.subcategory-field').remove();
                }
            });
        });
    </script>
@endpush
