@extends('admin.layouts.layout')

@section('content')
<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
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
                    <h2 class="text-lg font-semibold">sửa sản phẩm</h2>
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
                <form action="{{ route('products.update', $product->id) }}" id="myForm" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form1">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="categorySelect" class="form-label">Chọn Danh Mục Cha</label>
                                <select id="categorySelect" class="form-select" name="category_id">
                                    <option value="">Chọn danh mục cha</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}"  {{ $product->categories->contains('id', $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="categoryTypeSelect" class="form-label">Chọn Danh Mục Con</label>
                                <select id="categoryTypeSelect" class="form-select" name="category_type_id">
                                    <option value="">Chọn danh mục con</option>
                                    @foreach($categoryTypes as $categoryType)
                                        <option value="{{ $categoryType->id }}" 
                                            {{ $product->categoryTypes->contains('id', $categoryType->id) ? 'selected' : '' }}>
                                            {{ $categoryType->name }}
                                        </option>
                                    @endforeach
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
                                <input type="text" class="form-control" id="productName" name="name" value="{{$product->name}}">
                            </div>
                            <div>
                                <label for="productPrice" class="form-label">Mã sản phẩm</label>
                                <input type="text" class="form-control" id="productPrice" name="sku" value="{{$product->sku}}">
                            </div>
                            <div>
                                <label for="productImage" class="form-label">Ảnh</label>
                                <input type="file" class="form-control" id="productImage" name="thumbnail" accept="image/*">
                            </div>
                            <div id="imagePreview" style="margin-top: 10px;">
                                <img id="previewImg" src="{{ asset('upload/'.$product->thumbnail) }}" alt="Preview Image" style="max-width: 50%; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
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
                                name="content">{!! $product->content !!}
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
                                    <option value="{{ $br->id }}" {{ $product->brand_id == $br->id ? 'selected' : '' }}>{{ $br->name }}</option>
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
                        <div class="form3 p-4 border rounded" id="uploadContainer">
                            <div class="form-group">
                                <label for="images">Chọn ảnh (có thể chọn nhiều):</label>
                                <input type="file" id="images" name="image[]" multiple accept="image/*" class="form-control">   
                            </div>
                            <div id="previewContainer" class="mt-4 d-flex flex-wrap"></div>

                            <h3>ảnh cũ</h3>
                            <div class="mt-4 d-flex flex-wrap"> 
                                @foreach($productGallery as $productGallery)
                                    <img src="{{ asset('upload/'.$productGallery->image) }}" style="width: 150px; height: 150px;" alt="">
                                @endforeach
                            </div>
                        </div>
                        <a href="#" class="btn-next3 text-white bg-teal-500 w-100 block text-center p-2 rounded-md mt-4">
                            Tiếp theo
                        </a>
                    </div>

                    <div class="form4" style="display: none;">
                        <div class="space-y-6">
                            <div class="w-full">
                                <h4 class="text-lg font-semibold mb-4">Chọn Biến Thể</h4>
                                <div id="variant-container" class="grid grid-cols-3 gap-4">
                                    @php
                                        $groupedAttributes = $attributes->groupBy('name');
                                    @endphp
                                    @foreach ($groupedAttributes as $name => $group)
                                        <div class="variant-group border p-4 rounded hover:shadow-lg transition-all">
                                            <strong class="variant-name block mb-2" data-name="{{ $name }}" style="cursor: pointer;">
                                                <i class="fas fa-chevron-right mr-2 transform transition-transform"></i>
                                                {{ $name }}
                                            </strong>
                                            <div class="variant-options hidden mt-3 pl-2 border-l-2 border-teal-500">
                                                @foreach ($group as $attribute)
                                                    @foreach ($attribute->values as $value)
                                                        <div class="variant-item mb-3 hover:bg-gray-50 p-2 rounded">
                                                            <div class="variant-checkbox flex items-center">
                                                            <input type="checkbox" 
                                                                name="variants[{{ $attribute->id }}][]" 
                                                                value="{{ $value->id }}" 
                                                                data-variant-name="{{ $attribute->slug }} {{ $value->value }}"
                                                                id="variant-{{ $attribute->id }}-{{ $value->id }}"
                                                                class="variant-checkbox-input w-4 h-4"
                                                                @if(isset($selectedVariantIds) && in_array($value->id, $selectedVariantIds)) 
                                                                    checked 
                                                                    data-has-data="true"
                                                                    data-price="{{ $variantData[$value->id]['price'] ?? '' }}"
                                                                    data-sale-price="{{ $variantData[$value->id]['sale_price'] ?? '' }}"
                                                                    data-sale-start="{{ $variantData[$value->id]['sale_price_start_at'] ? date('Y-m-d\TH:i', strtotime($variantData[$value->id]['sale_price_start_at'])) : '' }}"
                                                                    data-sale-end="{{ $variantData[$value->id]['sale_price_end_at'] ? date('Y-m-d\TH:i', strtotime($variantData[$value->id]['sale_price_end_at'])) : '' }}"
                                                                @endif>
                                                                <label for="variant-{{ $attribute->id }}-{{ $value->id }}"
                                                                    class="ml-2 text-sm cursor-pointer">
                                                                    {{ $attribute->slug }} {{ $value->value }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Bottom panel - Selected variants details -->
                            <div class="w-full mt-8 border-t pt-8">
                                <h4 class="text-lg font-semibold mb-4">Biến Thể Đã Chọn</h4>
                                <div id="selected-variants-container" class="grid grid-cols-2 gap-6">
                                    <!-- Selected variants will be dynamically added here -->
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
    const variantData = new Map();

    // Toggle variant options when clicking on variant name
    document.querySelectorAll('.variant-name').forEach(item => {
        item.addEventListener('click', function() {
            this.classList.toggle('active');
            const icon = this.querySelector('i');
            icon.style.transform = this.classList.contains('active') ? 'rotate(90deg)' : '';
            const options = this.nextElementSibling;
            options.classList.toggle('hidden');
        });
    });

    // Handle checkbox changes
    document.querySelectorAll('.variant-checkbox-input').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const variantId = this.value;
            const variantName = this.dataset.variantName;

            if (this.checked) {
                const card = createVariantCard(variantId, variantName);
                document.getElementById('selected-variants-container').appendChild(card);
                addInputValidation(card, variantId);
            } else {
                removeVariantCard(variantId);
                variantData.delete(variantId);
            }
        });
    });

    // Handle pre-selected variants for edit mode
    document.querySelectorAll('.variant-checkbox-input[data-has-data="true"]').forEach(checkbox => {
        if (checkbox.checked) {
            const variantId = checkbox.value;
            const variantName = checkbox.dataset.variantName;
            const price = checkbox.dataset.price;
            const salePrice = checkbox.dataset.salePrice;
            const saleStart = checkbox.dataset.saleStart;
            const saleEnd = checkbox.dataset.saleEnd;

            const card = createVariantCard(variantId, variantName);
            document.getElementById('selected-variants-container').appendChild(card);

            // Set existing values
            const cardElement = document.getElementById(`variant-card-${variantId}`);
            if (cardElement) {
                cardElement.querySelector('input[name$="[price]"]').value = price;
                cardElement.querySelector('input[name$="[sale_price]"]').value = salePrice;
                cardElement.querySelector('input[name$="[sale_price_start_at]"]').value = saleStart;
                cardElement.querySelector('input[name$="[sale_price_end_at]"]').value = saleEnd;
            }
        }
    });

    function createVariantCard(variantId, variantName) {
        const card = document.createElement('div');
        card.className = 'variant-card bg-white p-4 rounded-lg shadow-sm mb-4';
        card.id = `variant-card-${variantId}`;
        
        card.innerHTML = `
            <div class="flex justify-between items-center mb-3">
                <h5 class="font-medium text-teal-600">${variantName}</h5>
                <button type="button" 
                        onclick="removeVariant('${variantId}')"
                        class="text-gray-400 hover:text-red-500 transition-colors duration-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Giá bán:</label>
                        <input type="number" 
                            name="variant_prices[${variantId}][price]" 
                            class="form-control w-full price-input" 
                            placeholder="Nhập giá bán"
                            required>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Giá khuyến mãi:</label>
                        <input type="number" 
                            name="variant_prices[${variantId}][sale_price]" 
                            class="form-control w-full sale-price-input" 
                            placeholder="Nhập giá khuyến mãi">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Bắt đầu khuyến mãi:</label>
                        <input type="datetime-local" 
                            name="variant_prices[${variantId}][sale_price_start_at]" 
                            class="form-control w-full sale-start-input">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Kết thúc khuyến mãi:</label>
                        <input type="datetime-local" 
                            name="variant_prices[${variantId}][sale_price_end_at]" 
                            class="form-control w-full sale-end-input">
                    </div>
                </div>
            </div>
        `;
        
        return card;
    }

    function removeVariantCard(variantId) {
        const card = document.getElementById(`variant-card-${variantId}`);
        if (card) {
            card.remove();
        }
        const checkbox = document.querySelector(`input[value="${variantId}"]`);
        if (checkbox) {
            checkbox.checked = false;
        }
    }

    // Add to global scope for onclick access
    window.removeVariant = function(variantId) {
        Swal.fire({
            title: 'Xác nhận xóa',
            text: 'Bạn có chắc muốn xóa biến thể này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                removeVariantCard(variantId);
                variantData.delete(variantId);
            }
        });
    };

    // Form validation before submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const selectedVariants = document.querySelectorAll('.variant-card');
        let hasError = false;

        selectedVariants.forEach(card => {
            const price = card.querySelector('.price-input').value;
            const salePrice = card.querySelector('.sale-price-input').value;
            const saleStart = card.querySelector('.sale-start-input').value;
            const saleEnd = card.querySelector('.sale-end-input').value;

            if (!price) {
                hasError = true;
                alert('Vui lòng nhập giá bán cho tất cả biến thể');
                e.preventDefault();
                return;
            }

            if (salePrice) {
                if (parseFloat(salePrice) >= parseFloat(price)) {
                    hasError = true;
                    alert('Giá khuyến mãi phải nhỏ hơn giá bán');
                    e.preventDefault();
                    return;
                }

                if (!saleStart || !saleEnd) {
                    hasError = true;
                    alert('Vui lòng nhập đầy đủ thời gian khuyến mãi');
                    e.preventDefault();
                    return;
                }

                if (new Date(saleEnd) <= new Date(saleStart)) {
                    hasError = true;
                    alert('Thời gian kết thúc phải sau thời gian bắt đầu khuyến mãi');
                    e.preventDefault();
                    return;
                }
            }
        });
    });
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
                const productCostPrice = document.getElementById('productCostPrice');
                const productSalePrice = document.getElementById('productSalePrice');
                const sale_price = document.getElementById('sale_price');
                const timestampInput = document.getElementById('timestampInput');
                const sale_price_end_at = document.getElementById('sale_price_end_at');
                let errorMessage = "";
                if (brandSelect.value.trim() === "") {
                    errorMessage += "<li>Vui lòng chọn Danh Mục Cha.</li>";
                }
                if (productCostPrice.value.trim() === "") {
                    errorMessage += "<li>Vui lòng nhập Tên Sản Phẩm.</li>";
                }
                if (productSalePrice.value.trim() === "") {
                    errorMessage += "<li>Vui lòng nhập Mã sản phẩm.</li>";
                }
                if (timestampInput.value.trim() === "") {
                    errorMessage += "<li>Vui lòng nhập Mã sản phẩm.</li>";
                }
                if (sale_price_end_at.value.trim() === "") {
                    errorMessage += "<li>Vui lòng nhập Mã sản phẩm.</li>";
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