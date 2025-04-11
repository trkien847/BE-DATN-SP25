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
                                    <i class="fas fa-tags mr-2"></i>Chọn Biến Thể Sản Phẩm
                                </h4>

                                <div id="variant-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @php
                                        $groupedAttributes = $attributes->groupBy('name');
                                    @endphp
                                    
                                    @foreach ($groupedAttributes as $name => $group)
                                        <div class="variant-group bg-white border rounded-lg overflow-hidden hover:shadow-lg transition-all duration-300">
                                            <!-- Variant Group Header -->
                                            <div class="variant-header bg-gray-50 p-4 cursor-pointer select-none"
                                                data-name="{{ $name }}">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-2">
                                                        <i class="fas fa-chevron-right transform transition-transform duration-300"></i>
                                                        <span class="font-medium text-gray-700">{{ $name }}</span>
                                                    </div>
                                                    <span class="text-sm text-gray-500 variant-counter">0 đã chọn</span>
                                                </div>
                                            </div>

                                            <!-- Variant Options -->
                                            <div class="variant-options hidden">
                                                <div class="p-4 space-y-3 border-t">
                                                    @foreach ($group as $attribute)
                                                        <div class="variant-group-items">
                                                            @foreach ($attribute->values as $value)
                                                                <div class="variant-item hover:bg-gray-50 rounded-md transition-colors duration-200">
                                                                    <label class="flex items-center p-2 cursor-pointer">
                                                                        <input type="checkbox" 
                                                                            name="variants[{{ $attribute->id }}][]" 
                                                                            value="{{ $value->id }}" 
                                                                            data-variant-name="{{ $attribute->name }} {{ $value->value }}"
                                                                            data-group="{{ $name }}"
                                                                            id="variant-{{ $attribute->id }}-{{ $value->id }}"
                                                                            class="variant-checkbox-input w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                                                                        <span class="ml-3 text-sm text-gray-700">
                                                                            {{ $value->value }}
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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
        // Variant group toggle
        document.querySelectorAll('.variant-header').forEach(header => {
            header.addEventListener('click', function() {
                const options = this.parentElement.querySelector('.variant-options');
                const icon = this.querySelector('.fas.fa-chevron-right');
                
                // Toggle options visibility
                options.classList.toggle('hidden');
                
                // Rotate icon
                if (options.classList.contains('hidden')) {
                    icon.style.transform = 'rotate(0deg)';
                } else {
                    icon.style.transform = 'rotate(90deg)';
                }
            });
        });

        // Update counter when checkbox changes
        document.querySelectorAll('.variant-checkbox-input').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateVariantCounter(this);
                handleVariantSelection(this);
            });
        });

        function updateVariantCounter(checkbox) {
            const group = checkbox.closest('.variant-group');
            const counter = group.querySelector('.variant-counter');
            const checkedCount = group.querySelectorAll('.variant-checkbox-input:checked').length;
            counter.textContent = `${checkedCount} đã chọn`;
        }

        function handleVariantSelection(checkbox) {
            const variantId = checkbox.value;
            const variantName = checkbox.dataset.variantName;
            const selectedContainer = document.getElementById('selected-variants-container');

            if (checkbox.checked) {
                // Add variant card
                const card = createVariantCard(variantId, variantName);
                selectedContainer.appendChild(card);
            } else {
                // Remove variant card
                const existingCard = document.getElementById(`variant-card-${variantId}`);
                if (existingCard) {
                    existingCard.classList.add('fade-out');
                    setTimeout(() => existingCard.remove(), 300);
                }
            }
        }

        function createVariantCard(variantId, variantName) {
            const card = document.createElement('div');
            card.id = `variant-card-${variantId}`;
            card.className = 'variant-card bg-white p-4 rounded-lg shadow-sm border fade-in';
            
            card.innerHTML = `
                <div class="flex justify-between items-center mb-3">
                    <h6 class="font-medium text-gray-800">${variantName}</h6>
                    <button type="button" onclick="removeVariant('${variantId}')"
                            class="text-gray-400 hover:text-red-500 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm text-gray-600">Giá bán</label>
                            <input type="number" name="variant_prices[${variantId}][price]" 
                                class="form-control w-full mt-1" required>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Giá khuyến mãi</label>
                            <input type="number" name="variant_prices[${variantId}][sale_price]" 
                                class="form-control w-full mt-1">
                        </div>
                    </div>
                </div>
            `;
            
            return card;
        }

        // Global function to remove variant
        window.removeVariant = function(variantId) {
            const checkbox = document.querySelector(`input[value="${variantId}"]`);
            if (checkbox) {
                checkbox.checked = false;
                updateVariantCounter(checkbox);
            }
            
            const card = document.getElementById(`variant-card-${variantId}`);
            if (card) {
                card.classList.add('fade-out');
                setTimeout(() => card.remove(), 300);
            }
        }
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