@extends('admin.layouts.layout')

@section('content')
<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .variant-row {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #fff;
            transition: all 0.3s ease;
        }
        .variant-row label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        .variant-row input, .variant-row select {
            width: 100%;
            margin-top: 5px;
        }
        .remove-variant {
            margin-top: 10px;
        }
        .error-input {
            border-color: #dc3545 !important;
            animation: shake 0.3s;
        }
        .variant-group {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .variant-group:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .variant-group.selected {
            transform: scale(1.1);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
            100% { transform: translateX(0); }
        }

        .variant-card {
            @apply bg-white p-4 rounded-lg shadow-sm border border-gray-200;
            animation: slideIn 0.3s ease-out;
        }

        .variant-card:hover {
            @apply shadow-md;
            transform: translateY(-2px);
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        .fade-out {
            animation: fadeOut 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(10px); }
        }
        #selected-variants-container {
            max-height: 600px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #0d9488 #e5e7eb;
        }

        #selected-variants-container::-webkit-scrollbar {
            width: 6px;
        }

        #selected-variants-container::-webkit-scrollbar-track {
            background: #e5e7eb;
        }

        #selected-variants-container::-webkit-scrollbar-thumb {
            background-color: #0d9488;
            border-radius: 3px;
        }

        .variant-group {
            height: fit-content;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100px);
            }
        }
    </style>
</head>
@if(session('error'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
      icon: 'error',
      title: 'Lỗi!',
      text: '{{ session('error') }}',
      confirmButtonText: 'Đóng'
    });
  });
</script>
@endif

@if($errors->any())
<script>
  document.addEventListener('DOMContentLoaded', function() {
    let errorMessage = '<ul>';
    @foreach($errors->all() as $error)
      errorMessage += '<li>{{ $error }}</li>';
    @endforeach
    errorMessage += '</ul>';

    Swal.fire({
      icon: 'error',
      title: 'Có lỗi xảy ra!',
      html: errorMessage,
      confirmButtonText: 'Đã hiểu'
    });
  });
</script>
@endif
<body class="bg-gray-100">
    <div class="flex">
        <div class="w-1/4 bg-white shadow-md">
            <div class="p-4">
                <div class="flex justify-between items-center mb-4">

                    <button class="lg:hidden text-gray-600" onclick="toggleMenu()">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <ul id="menu" class="hidden lg:block">
                    <h2 class="text-lg font-semibold">Thêm mới sản phẩm</h2>
                    <li class="mb-2">
                        <a href="#" class="flex items-center text-white bg-teal-500 p-2 rounded-md" onclick="selectMenuItem(event)">
                            <i class="fas fa-cog mr-2"></i> Tổng quát
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="flex items-center text-gray-600 hover:text-gray-800 p-2 rounded-md" onclick="selectMenuItem(event)">
                            <i class="fas fa-box mr-2"></i> Giá thương hiệu
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="flex items-center text-gray-600 hover:text-gray-800 p-2 rounded-md" onclick="selectMenuItem(event)">
                            <i class="fas fa-image mr-2"></i> Thêm ảnh
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="flex items-center text-gray-600 hover:text-gray-800 p-2 rounded-md" onclick="selectMenuItem(event)">
                            <i class="fas fa-wrench mr-2"></i> Thêm biến thể
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('products.list') }}" class="flex items-center text-gray-600 hover:text-gray-800 p-2 rounded-md" onclick="selectMenuItem(event)">
                            <i class="fas fa-arrow-left mr-2"></i> Quay lại
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="w-3/4 p-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <form action="{{ route('products.store') }}" id="myForm" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form1">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="categorySelect" class="form-label">Chọn Danh Mục Cha</label>
                                <select id="categorySelect" class="form-select" name="category_id">
                                    <option value="">Chọn danh mục cha</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="categoryTypeSelect" class="form-label">Chọn Danh Mục Con</label>
                                <select id="categoryTypeSelect" class="form-select" name="category_type_id" disabled>
                                    <option value="">Chọn danh mục con</option>
                                </select>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', () => {
                                    const categorySelect = document.getElementById('categorySelect');
                                    const categoryTypeSelect = document.getElementById('categoryTypeSelect');

                                    categorySelect.addEventListener('change', function() {
                                        const categoryId = this.value;
                                        categoryTypeSelect.innerHTML = '<option value="">Chọn danh mục con</option>';

                                        if (categoryId) {
                                            const categories = @json($categories);
                                            const selectedCategory = categories.find(category => category.id == categoryId);
                                            if (selectedCategory && selectedCategory.category_types.length > 0) {
                                                selectedCategory.category_types.forEach(type => {
                                                    const option = document.createElement('option');
                                                    option.value = type.id;
                                                    option.textContent = type.name;
                                                    categoryTypeSelect.appendChild(option);
                                                });
                                                categoryTypeSelect.disabled = false;
                                            } else {
                                                categoryTypeSelect.disabled = true;
                                            }
                                        } else {
                                            categoryTypeSelect.disabled = true;
                                        }
                                    });
                                });
                            </script>
                            <div>
                                <label for="productName" class="form-label">Tên Sản Phẩm</label>
                                <input type="text" class="form-control" id="productName" name="name" placeholder="Nhập tên sản phẩm">
                            </div>
                            <div>
                                <label for="productPrice" class="form-label">Mã sản phẩm</label>
                                <input type="text" class="form-control" id="productPrice" name="sku" placeholder="Nhập giá sản phẩm">
                            </div>
                            <div>
                                <label for="productImage" class="form-label">Ảnh</label>
                                <input type="file" class="form-control" id="productImage" name="thumbnail" accept="image/*">
                            </div>
                            <div id="imagePreview" style="margin-top: 10px;">
                                <img id="previewImg" src="#" alt="Preview Image" style="max-width: 50%; display: none; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                            </div>
                            <script>
                                document.getElementById('productImage').addEventListener('change', function(event) {
                                const file = event.target.files[0];
                                const previewImg = document.getElementById('previewImg');

                                if (file) {
                                const reader = new FileReader();


                                reader.onload = function(e) {
                                    previewImg.src = e.target.result;
                                    previewImg.style.display = 'block';
                                };

                                reader.readAsDataURL(file);
                                } else {

                                previewImg.src = '#';
                                previewImg.style.display = 'none';
                                }
                            });
                            </script>
                        </div>
                        <div>
                            <label class="form-label">Mô tả sản phẩm</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror"
                                id="doctorBio"
                                style="height: 100px"
                                name="content">
                        </textarea>
                            <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
                            <script>
                                CKEDITOR.replace('doctorBio');
                            </script>
                        </div>

                        <a href="#" class="btn-next text-white bg-teal-500 w-100 block text-center p-2 rounded-md mt-4">
                            Tiếp theo
                        </a>

                    </div>

                    <div class="form2" style="display: none;">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="brandSelect" class="form-label">Chọn tên thương hiệu</label>
                                <select id="brandSelect" class="form-control" name="brand_id">
                                    <option value="">Chọn tên thương hiệu</option>
                                    @foreach($brands as $br)
                                    <option value="{{ $br->id }}">{{ $br->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>  
                        <a href="#" class="btn-next2 text-white bg-teal-500 w-100 block text-center p-2 rounded-md mt-4">
                            Tiếp theo
                        </a>
                    </div>

                    <div class="form3" style="display: none;">
                        <h2>Thêm Ảnh</h2>
                        <div class="form p-4 border rounded" id="uploadContainer">
                            <div class="form-group">
                                <label for="images">Chọn ảnh (có thể chọn nhiều):</label>
                                <input type="file" id="images" name="image[]" multiple accept="image/*" class="form-control">   
                            </div>
                            <div id="previewContainer" class="mt-4 d-flex flex-wrap"></div>
                        </div>
                        <a href="#" class="btn-next3 text-white bg-teal-500 w-100 block text-center p-2 rounded-md mt-4">
                            Tiếp theo
                        </a>
                    </div>

                    <div class="form4" style="display: none;">
                        <div class="space-y-6">


                                <div class="w-full bg-white p-6 rounded-lg shadow-sm">
                                    <h4 class="text-xl font-semibold mb-6 text-gray-800 border-b pb-3">
                                        <i class="fas fa-tags mr-2"></i>Chọn Hình Thù Sản Phẩm
                                    </h4>

                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @php
                                            $shapeGroup = $attributes->where('name', 'Hình thù')->first();
                                            $weightGroup = $attributes->where('name', 'Khối lượng')->first();
                                            $weightValues = $weightGroup ? $weightGroup->values->map(function ($value) {
                                                $parts = explode(' ', $value->value);
                                                $unit = end($parts);
                                                $number = implode(' ', array_slice($parts, 0, -1));
                                                return [
                                                    'id' => $value->id,
                                                    'value' => $value->value,
                                                    'unit' => $unit,
                                                    'number' => $number,
                                                ];
                                            })->toArray() : [];
                                        @endphp
                                        
                                        @if($shapeGroup)
                                        @foreach ($shapeGroup->values as $value)
                                            <div class="variant-item bg-white border rounded-lg p-4 hover:shadow-lg transition-all duration-300">
                                                <label class="flex items-center cursor-pointer w-full">
                                                <input type="checkbox" 
                                                name="variants[shape][]" 
                                                value="{{ $value->id }}" 
                                                data-variant-name="{{ $value->value }}"
                                                data-group="Hình thù"
                                                id="variant-{{ $value->id }}"
                                                class="variant-checkbox-input w-5 h-5 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                                                    <span class="ml-3 text-gray-700 text-lg font-medium">
                                                        {{ $value->value }}
                                                    </span>
                                                </label>
                                            </div>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-6 border-t pt-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h5 class="text-lg font-medium text-gray-800">
                                            <i class="fas fa-weight mr-2"></i>Chọn khối lượng
                                        </h5>
                                        <div class="shape-filters flex gap-2">
                                            <!-- Filter buttons will be added here dynamically -->
                                        </div>
                                    </div>
                                    <div id="weight-selections-container" class="bg-white p-4 rounded-lg shadow-sm border">
                                        <div class="weight-options flex flex-wrap gap-4">
                                            <!-- Weight options will be added here dynamically -->
                                        </div>
                                    </div>
                                </div>

                            <div class="mt-8 border-t pt-6">
                                <h5 class="text-lg font-medium mb-4 text-gray-800">
                                    <i class="fas fa-list-check mr-2"></i>Biến Thể Đã Chọn
                                </h5>
                                <div id="selected-variants-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Selected variants will be added here dynamically -->
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn text-white bg-teal-500 w-full mt-6 py-3 rounded-lg hover:bg-teal-600 transition-colors">
                            Lưu Sản Phẩm
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.variant-name').forEach(item => {
            item.addEventListener('click', function() {
                const options = this.nextElementSibling;
                if (options.style.display === 'none') {
                    options.style.display = 'block';
                } else {
                    options.style.display = 'none';
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            let selectedShapes = new Map(); // Store selected shapes with their names
            let selectedWeights = new Set(); // Store selected weights across all shapes
            let currentShapeFilter = 'all'; // Track current shape filter

            // Handle shape selection updateShapeFilterButtons
            document.querySelectorAll('.variant-checkbox-input').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const shapeId = this.value;
                    const shapeName = this.dataset.variantName;

                    if (this.checked) {
                        selectedShapes.set(shapeId, shapeName);
                    } else {
                        selectedShapes.delete(shapeId);
                        // Remove variants for this shape updateShapeFilterButtons
                        removeVariantsByShape(shapeId);
                        // Remove corresponding weight selections
                        document.querySelectorAll(`.weight-checkbox[data-shape-id="${shapeId}"]`).forEach(cb => {
                            cb.checked = false;
                        });
                    }
                    
                    updateShapeFilterButtons();
                    updateWeightSelectionArea();
                });
            });

            function updateShapeFilterButtons() {
                const filterContainer = document.querySelector('.shape-filters');
                if (!filterContainer) return;

                filterContainer.innerHTML = `
                    <button type="button" 
                        class="filter-btn active px-4 py-2 rounded-md text-sm font-medium bg-teal-500 text-white hover:bg-teal-600 transition-colors" 
                        data-filter="all">
                        Tất cả
                    </button>
                `;

                selectedShapes.forEach((shapeName, shapeId) => {
                    filterContainer.innerHTML += `
                        <button type="button"
                            class="filter-btn px-4 py-2 rounded-md text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors"
                            data-shape-id="${shapeId}"
                            data-shape-name="${shapeName}">
                            ${shapeName}
                        </button>
                    `;
                });

                addFilterButtonHandlers();
            }

            function updateWeightSelectionArea() {
                const container = document.querySelector('.weight-options');
                if (!container) return;

                // Clear container if no shapes are selected
                if (selectedShapes.size === 0) {
                    container.innerHTML = '';
                    return;
                }

                const weightValues = @json($weightValues);
                container.innerHTML = '';

                weightValues.forEach(value => {
                    const optionDiv = document.createElement('div');
                    optionDiv.className = 'weight-option flex items-center bg-gray-50 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors';
                    optionDiv.dataset.unit = value.unit;

                    const isChecked = Array.from(document.querySelectorAll('.variant-card')).some(card => {
                        const [_, weightId] = card.id.split('-').pop().split('-');
                        return weightId === value.id.toString();
                    });

                    optionDiv.innerHTML = `
                        <input type="checkbox" 
                            id="weight-${value.id}"
                            value="${value.id}"
                            data-weight-name="${value.value}"
                            class="weight-checkbox w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500"
                            ${isChecked ? 'checked' : ''}>
                        <label class="ml-2 text-sm text-gray-700 cursor-pointer select-none">
                            ${value.value}
                        </label>
                    `;

                    container.appendChild(optionDiv);

                    const checkbox = optionDiv.querySelector('.weight-checkbox');
                    checkbox.addEventListener('change', handleWeightSelection);
                });
            }

            function handleWeightSelection(e) {
                const checkbox = e.target;
                const weightId = checkbox.value;
                const weightName = checkbox.dataset.weightName;

                if (checkbox.checked) {
                    if (currentShapeFilter === 'all') {
                        selectedShapes.forEach((shapeName, shapeId) => {
                            createOrUpdateVariantCard(`${shapeId}-${weightId}`, `${shapeName} ${weightName}`);
                        });
                    } else {
                        const selectedShape = Array.from(selectedShapes.entries())
                            .find(([id, _]) => id === currentShapeFilter);
                        if (selectedShape) {
                            createOrUpdateVariantCard(
                                `${selectedShape[0]}-${weightId}`, 
                                `${selectedShape[1]} ${weightName}`
                            );
                        }
                    }
                } else {
                    if (currentShapeFilter === 'all') {
                        selectedShapes.forEach((_, shapeId) => {
                            removeVariantCard(`${shapeId}-${weightId}`);
                        });
                    } else {
                        removeVariantCard(`${currentShapeFilter}-${weightId}`);
                    }
                }
            }

            function removeVariantsByShape(shapeId) {
                document.querySelectorAll(`.variant-card`).forEach(card => {
                    if (card.id.includes(`variant-card-${shapeId}-`)) {
                        card.classList.add('fade-out');
                        setTimeout(() => card.remove(), 300);
                    }
                });
            }

            function updateAllVariants() {
                // Recreate all variants based on current removeVariantCard
                selectedWeights.forEach(weightId => {
                    selectedShapes.forEach((shapeName, shapeId) => {
                        const weightName = document.querySelector(`#weight-${weightId}`).dataset.weightName;
                        createOrUpdateVariantCard(`${shapeId}-${weightId}`, `${shapeName} ${weightName}`);
                    });
                });
            }

            function addFilterButtonHandlers() {
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.filter-btn').forEach(b => {
                            b.classList.remove('bg-teal-500', 'text-white');
                            b.classList.add('bg-gray-100', 'text-gray-700');
                        });
                        this.classList.remove('bg-gray-100', 'text-gray-700');
                        this.classList.add('bg-teal-500', 'text-white');

                        const shapeId = this.dataset.shapeId || 'all';
                        filterWeightOptions(shapeId);
                    });
                });
            }

            function filterWeightOptions(shapeId) {
                currentShapeFilter = shapeId;
                const options = document.querySelectorAll('.weight-option');
                
                if (shapeId === 'all') {
                    options.forEach(option => {
                        option.style.display = 'flex';
                    });
                } else {
                    // Show only weights that aren't used by other shapes
                    const usedWeights = new Set();
                    document.querySelectorAll('.variant-card').forEach(card => {
                        if (!card.id.startsWith(`variant-card-${shapeId}`)) {
                            const [_, weightId] = card.id.split('-').pop().split('-');
                            usedWeights.add(weightId);
                        }
                    });

                    options.forEach(option => {
                        const weightId = option.querySelector('input').value;
                        option.style.display = !usedWeights.has(weightId) ? 'flex' : 'none';
                    });
                }
            }

            // Handle checkbox changes for variant selection removeVariantsByShape
            document.querySelectorAll('.shape-filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    // Update button styles
                    document.querySelectorAll('.shape-filter-btn, #all-shapes-btn').forEach(b => {
                        b.classList.remove('bg-teal-500', 'text-white');
                        b.classList.add('bg-gray-100', 'text-gray-700');
                    });
                    this.classList.remove('bg-gray-100', 'text-gray-700');
                    this.classList.add('bg-teal-500', 'text-white');

                    currentShapeFilter = this.dataset.shapeId;
                    updateWeightCheckboxes();
                });
            });

            // Handle "All" button 
            document.getElementById('all-shapes-btn').addEventListener('click', function() {
                document.querySelectorAll('.shape-filter-btn, #all-shapes-btn').forEach(b => {
                    b.classList.remove('bg-teal-500', 'text-white');
                    b.classList.add('bg-gray-100', 'text-gray-700');
                });
                this.classList.remove('bg-gray-100', 'text-gray-700');
                this.classList.add('bg-teal-500', 'text-white');

                currentShapeFilter = 'all';
                updateWeightCheckboxes();
            });

            function updateWeightOptions() {
                const container = document.querySelector('.weight-options');
                container.innerHTML = '';

                if (selectedShapes.size === 0) return;

                const weightValues = @json($weightValues);
                
                weightValues.forEach(value => {
                    const optionDiv = document.createElement('div');
                    optionDiv.className = 'weight-option flex items-center bg-gray-50 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors';
                    optionDiv.dataset.unit = value.unit;

                    optionDiv.innerHTML = `
                        <input type="checkbox" 
                            id="weight-${value.id}"
                            value="${value.id}"
                            data-weight-name="${value.value}"
                            class="weight-checkbox w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                        <label class="ml-2 text-sm text-gray-700 cursor-pointer select-none">
                            ${value.value}
                        </label>
                    `;

                    container.appendChild(optionDiv);

                    // Add change event listener
                    const checkbox = optionDiv.querySelector('.weight-checkbox');
                    checkbox.addEventListener('change', function() {
                        if (this.checked) {
                            if (currentShapeFilter === 'all') {
                                // Create variants for all selected shapes
                                selectedShapes.forEach(shape => {
                                    createOrUpdateVariantCard(
                                        `${shape.id}-${value.id}`,
                                        `${shape.name} ${value.value}`
                                    );
                                });
                            } else {
                                // Create variant only for selected shape
                                const shape = Array.from(selectedShapes)
                                    .find(s => s.id === currentShapeFilter);
                                if (shape) {
                                    createOrUpdateVariantCard(
                                        `${shape.id}-${value.id}`,
                                        `${shape.name} ${value.value}`
                                    );
                                }
                            }
                        } else {
                            if (currentShapeFilter === 'all') {
                                selectedShapes.forEach(shape => {
                                    removeVariantCard(`${shape.id}-${value.id}`);
                                });
                            } else {
                                removeVariantCard(`${currentShapeFilter}-${value.id}`);
                            }
                        }
                    });
                });
            }

            function createWeightSelectionArea(shapeId, shapeName) {
                const container = document.getElementById('weight-selections-container');
                const weightArea = document.createElement('div');
                weightArea.className = 'weight-selection-area bg-white p-4 rounded-lg shadow-sm border mb-4 fade-in';
                weightArea.dataset.shapeId = shapeId;

                weightArea.innerHTML = `
                    <h6 class="font-medium text-gray-800 mb-3">
                        Chọn khối lượng cho <span class="text-teal-600 font-bold">${shapeName}</span>
                    </h6>
                    
                    <!-- Filter buttons -->
                    <div class="flex gap-2 mb-4">
                        <button type="button" class="filter-btn active px-4 py-2 rounded-md text-sm font-medium bg-teal-500 text-white hover:bg-teal-600 transition-colors" data-filter="all">
                            Tất cả
                        </button>
                        <button type="button" class="filter-btn px-4 py-2 rounded-md text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors" data-filter="viên">
                            Viên
                        </button>
                        <button type="button" class="filter-btn px-4 py-2 rounded-md text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors" data-filter="ml">
                            ml
                        </button>
                        <button type="button" class="filter-btn px-4 py-2 rounded-md text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors" data-filter="g">
                            g
                        </button>
                    </div>

                    <!-- Weight options container -->
                    <div class="weight-options flex flex-wrap gap-4">
                    </div>
                `;

                const weightOptions = weightArea.querySelector('.weight-options');
                
                // Get weight values from PHP
                const weightValues = @json($weightValues);
                console.log('Weight Values:', weightValues);

                // Create weight options
                weightValues.forEach(value => {
                    const optionDiv = document.createElement('div');
                    optionDiv.className = 'weight-option flex items-center bg-gray-50 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors';
                    
                    console.log('Creating option with ID:', value.id);
                    optionDiv.dataset.unit = value.unit; // Sử dụng unit từ dữ liệu

                    optionDiv.innerHTML = `
                        <input type="checkbox" 
                            id="weight-${shapeId}-${value.id}"
                            value="${value.id}"
                            data-weight-name="${value.value}"
                            class="weight-checkbox w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                        <label class="ml-2 text-sm text-gray-700 cursor-pointer select-none">
                            ${value.value}
                        </label>
                    `;
                    
                    weightOptions.appendChild(optionDiv);
                });

                // Add filter functionality
                const filterButtons = weightArea.querySelectorAll('.filter-btn');
                filterButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        filterButtons.forEach(b => {
                            b.classList.remove('bg-teal-500', 'text-white');
                            b.classList.add('bg-gray-100', 'text-gray-700');
                        });
                        this.classList.remove('bg-gray-100', 'text-gray-700');
                        this.classList.add('bg-teal-500', 'text-white');

                        const filter = this.dataset.filter;
                        const options = weightOptions.querySelectorAll('.weight-option');

                        options.forEach(option => {
                            if (filter === 'all' || option.dataset.unit === filter) {
                                option.style.display = 'flex';
                            } else {
                                option.style.display = 'none';
                            }
                        });
                    });
                });

                container.appendChild(weightArea);

                weightArea.querySelectorAll('.weight-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const weightId = this.value;
                        const weightName = this.dataset.weightName;
                        const combinationId = `${shapeId}-${weightId}`;
                        const combinationName = `${shapeName} ${weightName}`;

                        if (this.checked) {
                            createOrUpdateVariantCard(combinationId, combinationName);
                        } else {
                            removeVariantCard(combinationId);
                        }
                    });
                });
            }

            function createOrUpdateVariantCard(combinationId, combinationName) {
                const container = document.getElementById('selected-variants-container');
                let card = document.getElementById(`variant-card-${combinationId}`);
                const [shapeId, weightId] = combinationId.split('-');

                if (!card) {
                    card = document.createElement('div');
                    card.id = `variant-card-${combinationId}`;
                    card.className = 'variant-card bg-white p-4 rounded-lg shadow-sm border fade-in';
                    
                    card.innerHTML = `
                        <div class="flex justify-between items-center mb-3">
                            <h6 class="font-medium text-gray-800">${combinationName}</h6>
                            <button type="button" onclick="removeVariant('${combinationId}')"
                                    class="text-gray-400 hover:text-red-500 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="space-y-4">
                            <input type="hidden" name="variants[${shapeId}][]" value="${weightId}">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-sm text-gray-600">Giá bán <span class="text-red-500">*</span></label>
                                    <input type="number" 
                                        name="variant_prices[${shapeId}-${weightId}][price]" 
                                        class="form-control w-full mt-1" 
                                        required 
                                        min="0">
                                </div>
                                <div>
                                    <label class="text-sm text-gray-600">Giá khuyến mãi</label>
                                    <input type="number" 
                                        name="variant_prices[${shapeId}-${weightId}][sale_price]" 
                                        class="form-control w-full mt-1" 
                                        min="0">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-sm text-gray-600">Bắt đầu khuyến mãi</label>
                                    <input type="datetime-local" 
                                        name="variant_prices[${shapeId}-${weightId}][sale_start_at]" 
                                        class="form-control w-full mt-1">
                                </div>
                                <div>
                                    <label class="text-sm text-gray-600">Kết thúc khuyến mãi</label>
                                    <input type="datetime-local" 
                                        name="variant_prices[${shapeId}-${weightId}][sale_end_at]" 
                                        class="form-control w-full mt-1">
                                </div>
                            </div>
                        </div>
                    `;
                    
                    container.appendChild(card);

                    // Add validation event listeners
                    const priceInput = card.querySelector('input[name$="[price]"]');
                    const salePriceInput = card.querySelector('input[name$="[sale_price]"]');
                    const startDateInput = card.querySelector('input[name$="[sale_start_at]"]');
                    const endDateInput = card.querySelector('input[name$="[sale_end_at]"]');

                    priceInput.addEventListener('change', validatePrice);
                    salePriceInput.addEventListener('change', validateSalePrice);
                    startDateInput.addEventListener('change', validateDates);
                    endDateInput.addEventListener('change', validateDates);
                }
            }

            function removeWeightSelectionArea(shapeId) {
                const area = document.querySelector(`.weight-selection-area[data-shape-id="${shapeId}"]`);
                if (area) {
                    area.classList.add('fade-out');
                    setTimeout(() => area.remove(), 300);
                }
            }

            function removeVariantCard(combinationId) {
                const card = document.getElementById(`variant-card-${combinationId}`);
                if (card) {
                    card.classList.add('fade-out');
                    setTimeout(() => card.remove(), 300);
                }
            }
//addFilterButtonHandlers
            function removeVariantCards(shapeId) {
                document.querySelectorAll(`[id^="variant-card-${shapeId}-"]`).forEach(card => {
                    card.classList.add('fade-out');
                    setTimeout(() => card.remove(), 300);
                });
            }

            // Thêm các hàm validate
            function validatePrice(e) {
                const input = e.target;
                if (input.value <= 0) {
                    input.setCustomValidity('Giá bán phải lớn hơn 0');
                } else {
                    input.setCustomValidity('');
                }
            }

            function validateSalePrice(e) {
                const input = e.target;
                const card = input.closest('.variant-card');
                const priceInput = card.querySelector('input[name$="[price]"]');
                
                if (input.value && parseFloat(input.value) >= parseFloat(priceInput.value)) {
                    input.setCustomValidity('Giá khuyến mãi phải nhỏ hơn giá bán');
                } else {
                    input.setCustomValidity('');
                }
            }

            function validateDates(e) {
                const input = e.target;
                const card = input.closest('.variant-card');
                const startInput = card.querySelector('input[name$="[sale_start_at]"]');
                const endInput = card.querySelector('input[name$="[sale_end_at]"]');
                
                if (startInput.value && endInput.value) {
                    if (new Date(endInput.value) <= new Date(startInput.value)) {
                        endInput.setCustomValidity('Thời gian kết thúc phải sau thời gian bắt đầu');
                    } else {
                        endInput.setCustomValidity('');
                    }
                }
            }

            // Thêm validation khi submit form
            document.getElementById('myForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const variants = document.querySelectorAll('.variant-card');
                let hasError = false;

                // Kiểm tra xem có biến thể nào không
                if (variants.length === 0) {
                    alert('Vui lòng chọn ít nhất một biến thể (Hình thù và Khối lượng).');
                    return;
                }

                // Kiểm tra các trường bắt buộc trong từng biến thể
                variants.forEach(variant => {
                    const price = variant.querySelector('input[name$="[price]"]');
                    const salePrice = variant.querySelector('input[name$="[sale_price]"]');
                    const startDate = variant.querySelector('input[name$="[sale_start_at]"]');
                    const endDate = variant.querySelector('input[name$="[sale_end_at]"]');

                    if (!price.value) {
                        price.classList.add('error-input');
                        hasError = true;
                    }

                    if (salePrice.value) {
                        if (!startDate.value || !endDate.value) {
                            startDate.classList.add('error-input');
                            endDate.classList.add('error-input');
                            hasError = true;
                        }
                    }
                });

                if (hasError) {
                    alert('Vui lòng kiểm tra lại các trường bắt buộc');
                    return;
                }

                this.submit();
            });

            // Global function for removing variants
            window.removeVariant = function(combinationId) {
                const [shapeId, weightId] = combinationId.split('-');
                const weightCheckbox = document.getElementById(`weight-${shapeId}-${weightId}`);
                if (weightCheckbox) {
                    weightCheckbox.checked = false;
                }
                removeVariantCard(combinationId);
            };
        });

      
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
    <script>
       document.addEventListener('DOMContentLoaded', function() {
            let filesArray = [];
            
            const input = document.getElementById('images');
            const previewContainer = document.getElementById('previewContainer');
            const uploadContainer = document.getElementById('uploadContainer');
            
            input.addEventListener('change', function(event) {
                const selectedFiles = Array.from(event.target.files);
                selectedFiles.forEach(file => {
                    filesArray.push(file);
                });
                updatePreviews();
                input.value = "";
            });
            
            function updatePreviews() {
                previewContainer.innerHTML = "";
                filesArray.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'position-relative m-2';
                        previewDiv.style.width = "150px";
                        previewDiv.style.height = "150px";
                        
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = file.name;
                        img.className = "img-thumbnail";
                        img.style.width = "100%";
                        img.style.height = "100%";
                        img.style.objectFit = "cover";
                        
                        const deleteBtn = document.createElement('button');
                        deleteBtn.type = "button";
                        deleteBtn.innerText = "×";
                        deleteBtn.className = "btn btn-danger btn-sm position-absolute";
                        deleteBtn.style.top = "0";
                        deleteBtn.style.right = "0";
                        deleteBtn.addEventListener('click', function() {
                            filesArray.splice(index, 1);
                            updatePreviews();
                        });
                        
                        previewDiv.appendChild(img);
                        previewDiv.appendChild(deleteBtn);
                        previewContainer.appendChild(previewDiv);
                    }
                    reader.readAsDataURL(file);
                });
            }

            const form = document.getElementById('myForm');
            form.addEventListener('submit', function(e) {
                const dataTransfer = new DataTransfer();
                filesArray.forEach(file => {
                    dataTransfer.items.add(file);
                });
                input.files = dataTransfer.files;
            });
        });


        // form 2
        document.addEventListener('DOMContentLoaded', function() {
        const btnNext = document.querySelector('.btn-next');
        if (btnNext) {
            btnNext.addEventListener('click', function(event) {
                event.preventDefault();
                const previousErrorBox = document.querySelector('.error-box');
                if (previousErrorBox) {
                    previousErrorBox.remove();
                }
                const categorySelect = document.getElementById('categorySelect');
                const productName = document.getElementById('productName');
                const productPrice = document.getElementById('productPrice');
                const doctorBioData = CKEDITOR.instances.doctorBio.getData().trim();
                const productImage = document.getElementById('productImage');
                const categoryTypeSelect = document.getElementById('categoryTypeSelect');
                let errorMessage = "";
                if (categorySelect.value.trim() === "") {
                    errorMessage += "<li>Vui lòng chọn Danh Mục Cha.</li>";
                }
                if (productName.value.trim() === "") {
                    errorMessage += "<li>Vui lòng nhập Tên Sản Phẩm.</li>";
                }
                if (productPrice.value.trim() === "") {
                    errorMessage += "<li>Vui lòng nhập Mã sản phẩm.</li>";
                }
                if (doctorBioData === "") {
                    errorMessage += "<li>Vui lòng nhập Mô tả sản phẩm.</li>";
                }
                if (productImage.files.length === 0) {
                    errorMessage += "<li>Vui lòng chọn Ảnh.</li>";
                }
                
                if (!categoryTypeSelect.disabled && categoryTypeSelect.value.trim() === "") {
                    errorMessage += "<li>Vui lòng chọn Danh mục con.</li>";
                }
                
                if (errorMessage !== "") {
                    const errorBox = document.createElement('div');
                    errorBox.className = "error-box bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4";
                    errorBox.innerHTML = `
                        <strong class="font-bold">Má nó nhập còn thiếu!</strong>
                        <ul class="mt-2">${errorMessage}</ul>
                    `;
                    const form1 = document.querySelector('.form1');
                    form1.insertBefore(errorBox, form1.firstChild);
                    
                    setTimeout(() => {
                        errorBox.remove();
                    }, 5000);
                    return; 
                }
                
                console.log("btn-next clicked");
                switchToForm2();
                console.log("switchToForm2() has been called");
                
                document.querySelectorAll('#menu a').forEach(link => {
                    if (link.textContent.trim() === 'Tổng quát') {
                        link.classList.remove('bg-teal-500', 'text-white');
                        link.classList.add('text-gray-600');
                    }
                    if (link.textContent.trim() === 'Giá thương hiệu') {
                        link.classList.remove('text-gray-600');
                        link.classList.add('bg-teal-500', 'text-white');
                    }
                });
            });
        } else {
            console.log("Không tìm thấy phần tử .btn-next");
        }
        switchToForm1();
    });

    document.addEventListener('DOMContentLoaded', function() {
        const btnNext2 = document.querySelector('.btn-next2');
        if (btnNext2) {
            btnNext2.addEventListener('click', function(event) {
                event.preventDefault();
                const previousErrorBox = document.querySelector('.error-box');
                if (previousErrorBox) {
                    previousErrorBox.remove();
                }
                const brandSelect = document.getElementById('brandSelect');
                let errorMessage = "";
                if (brandSelect.value.trim() === "") {
                    errorMessage += "<li>Vui lòng chọn nhan hang.</li>";
                }
               
                
                if (errorMessage !== "") {
                    const errorBox = document.createElement('div');
                    errorBox.className = "error-box bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4";
                    errorBox.innerHTML = `
                        <strong class="font-bold">Má nó nhập còn thiếu!</strong>
                        <ul class="mt-2">${errorMessage}</ul>
                    `;
                    const form2 = document.querySelector('.form2');
                    form2.insertBefore(errorBox, form2.firstChild);
                    
                    setTimeout(() => {
                        errorBox.remove();
                    }, 5000);
                    return; 
                }
                
                console.log("btn-next2 clicked");
                switchToForm3();
                console.log("switchToForm3() has been called");
                
                document.querySelectorAll('#menu a').forEach(link => {
                    if (link.textContent.trim() === 'Giá thương hiệu') {
                        link.classList.remove('bg-teal-500', 'text-white');
                        link.classList.add('text-gray-600');
                    }
                    if (link.textContent.trim() === 'Thêm ảnh') {
                        link.classList.remove('text-gray-600');
                        link.classList.add('bg-teal-500', 'text-white');
                    }
                });
            });
        } else {
            console.log("Không tìm thấy phần tử .btn-next2");
        }
        switchToForm1();
    });


    document.addEventListener('DOMContentLoaded', function() {
        const btnNext3 = document.querySelector('.btn-next3');
        if (btnNext3) {
            btnNext3.addEventListener('click', function(event) {
                event.preventDefault();
                const previousErrorBox = document.querySelector('.error-box');
                if (previousErrorBox) {
                    previousErrorBox.remove();
                }  
                console.log("btn-next3 clicked");
                switchToForm4();
                console.log("switchToForm3() has been called");
                
                document.querySelectorAll('#menu a').forEach(link => {
                    if (link.textContent.trim() === 'Thêm ảnh') {
                        link.classList.remove('bg-teal-500', 'text-white');
                        link.classList.add('text-gray-600');
                    }
                    if (link.textContent.trim() === 'Thêm biến thể') {
                        link.classList.remove('text-gray-600');
                        link.classList.add('bg-teal-500', 'text-white');
                    }
                });
            });
        } else {
            console.log("Không tìm thấy phần tử .btn-next3");
        }
        switchToForm1();
    });



        $(document).ready(function() {
            $('#brandSelect').select2({
                placeholder: "Chọn tên thương hiệu",
                allowClear: true,
                width: '100%'
            });
        });


        function switchToForm1() {
            document.querySelector('.form1').style.display = 'block';
            document.querySelector('.form4').style.display = 'none';
            document.querySelector('.form3').style.display = 'none';
            document.querySelector('.form2').style.display = 'none';
        }

        function switchToForm2() {
            document.querySelector('.form1').style.display = 'none';
            document.querySelector('.form4').style.display = 'none';
            document.querySelector('.form3').style.display = 'none';
            document.querySelector('.form2').style.display = 'block';
        }

        function switchToForm3() {
            document.querySelector('.form1').style.display = 'none';
            document.querySelector('.form4').style.display = 'none';
            document.querySelector('.form2').style.display = 'none';
            document.querySelector('.form3').style.display = 'block';
        }

        function switchToForm4() {
            document.querySelector('.form1').style.display = 'none';
            document.querySelector('.form2').style.display = 'none';
            document.querySelector('.form3').style.display = 'none';
            document.querySelector('.form4').style.display = 'block';
        }

        function selectMenuItem(event) {
            document.querySelectorAll('#menu a').forEach(item => {
                item.classList.remove('bg-teal-500', 'text-white');
                item.classList.add('text-gray-600');
            });

            event.target.closest('a').classList.add('bg-teal-500', 'text-white');

            const menuText = event.target.closest('a').innerText.trim();
            if (menuText === 'Giá thương hiệu') {
                switchToForm2();
            } else if (menuText === 'Tổng quát') {
                switchToForm1();
            }else if (menuText === 'Thêm ảnh') {
                switchToForm3();
            }else if (menuText === 'Thêm biến thể') {
                switchToForm4();
            }
        }
        
    </script>
</body>

</html>

@endsection