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
                            <div>
                                <label for="timestampInput" class="form-label">Ngày Giờ Bắt Đầu Giảm Giá</label>
                                <input type="datetime-local" id="timestampInput" name="sale_price_start_at" class="form-control" value="{{$product->sale_price_start_at}}">
                            </div>
                            <div>
                                <label for="timestampInput" class="form-label">Ngày Giờ Kết Thúc Giảm Giá</label>
                                <input type="datetime-local" id="sale_price_end_at" name="sale_price_end_at" class="form-control" value="{{$product->sale_price_end_at}}">
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
                        <h4>Chọn Biến Thể</h4>
                        <div id="variant-container" class="grid grid-cols-4 gap-4">
                            @foreach($attributes as $attribute)
                                <div class="variant-group border p-4 rounded hover:shadow-lg transition-transform transform hover:scale-105">
                                    <strong class="variant-name" style="cursor: pointer;">{{ $attribute->name }}</strong>
                                    <div class="variant-options" style="display: none; margin-top: 10px;">
                                        @foreach($attribute->values as $value)
                                            <div>
                                                <input type="checkbox" name="variants[{{ $attribute->id }}][]" value="{{ $value->id }}" data-display="{{ $attribute->name }}: {{ $attribute->slug }}{{ $value->value }}" class="variant-checkbox" 
                                                @if(in_array($value->id, $selectedVariantIds)) checked @endif>
                                                <label>{{ $attribute->name }}: {{ $attribute->slug }}{{ $value->value }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div id="selected-variants" style="margin-top: 20px;">
                            <h4>Biến Thể Đã Chọn</h4>
                            <ul id="selected-variants-list">
                                @foreach($product->variants as $variant)
                                    @foreach($variant->attributeValues as $attributeValue)
                                        <li>{{ $attributeValue->attribute->name }}: {{ $attributeValue->attribute->slug }}{{ $attributeValue->value }}</li>
                                    @endforeach
                                @endforeach
                            </ul>
                        </div>
                        <button type="submit" class="btn text-white bg-teal-500 w-100" style="margin-top: 10px;">Lưu Sản Phẩm</button>
                    </div>


                </form>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
        // Toggle display of variant options
        $('.variant-name').on('click', function() {
            $(this).next('.variant-options').slideToggle();
        });

        // Update selected variants list
        $('.variant-checkbox').on('change', function() {
            let selectedVariantsList = $('#selected-variants-list');
            selectedVariantsList.empty();

            $('.variant-checkbox:checked').each(function() {
                let displayText = $(this).data('display');
                selectedVariantsList.append('<li>' + displayText + '</li>');
            });

            // Add zoom effect to selected checkboxes
            $('.variant-checkbox').closest('.variant-group').removeClass('selected');
            $('.variant-checkbox:checked').closest('.variant-group').addClass('selected');
        });

        // Initialize selected variants list on page load
        $('.variant-checkbox:checked').each(function() {
            let displayText = $(this).data('display');
            $('#selected-variants-list').append('<li>' + displayText + '</li>');
            $(this).closest('.variant-group').addClass('selected');
        });

        // Form submission validation
        $('form').on('submit', function(e) {
            let hasError = false;
            if ($('.variant-checkbox:checked').length === 0) {
                hasError = true;
                Swal.fire({
                    icon: 'warning',
                    title: 'Cảnh báo!',
                    text: 'Vui lòng chọn ít nhất một biến thể!',
                    confirmButtonText: 'OK'
                });
            }

            if (hasError) {
                e.preventDefault();
            }
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