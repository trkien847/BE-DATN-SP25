@extends('admin.layouts.layout')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('error'))
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

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let errorMessage = '<ul>';
                @foreach ($errors->all() as $error)
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
    <h3>Sửa Chữa sai lầm</h3>
    <form action="{{ route('products.update', $product->id) }}" method="post" enctype="multipart/form-data"
        style="margin-top: 10px;">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <label for="categorySelect" class="form-label">Chọn Danh Mục Cha</label>
                <select id="categorySelect" class="form-select" name="category_id">
                    <option value="">Chọn danh mục cha</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $product->categories->contains('id', $category->id) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="categoryTypeSelect" class="form-label">Chọn Danh Mục Con</label>
                <select id="categoryTypeSelect" class="form-select" name="category_type_id">
                    <option value="">Chọn danh mục con</option>
                    @foreach ($categoryTypes as $categoryType)
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

            <div class="col-md-6 mb-3">
                <label for="productName" class="form-label">Tên Sản Phẩm</label>
                <input type="text" class="form-control" id="productName" name="name" value="{{ $product->name }}">
            </div>
            <div class="col-md-6 mb-3">
                <label for="productPrice" class="form-label">Mã sản phẩm</label>
                <input type="text" class="form-control" id="productPrice" name="sku" value="{{ $product->sku }}">
            </div>
            <div class="col-md-12 mb-3">
                <label for="productImage" class="form-label">Ảnh</label>
                <input type="file" class="form-control" id="productImage" name="thumbnail" accept="image/*">
            </div>
            <div id="imagePreview" style="margin-top: 10px;">
                <img id="previewImg" src="{{ asset('upload/' . $product->thumbnail) }}" alt="Preview Image"
                    style="max-width: 50%; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
            </div>
            <div class="col-md-6 mb-3">
                <label for="brandSelect" class="form-label">Chọn tên thương hiệu</label>
                <select id="brandSelect" class="form-control" name="brand_id">
                    <option value="">Chọn tên thương hiệu</option>
                    @foreach ($brands as $br)
                        <option value="{{ $br->id }}" {{ $product->brand_id == $br->id ? 'selected' : '' }}>
                            {{ $br->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="productCostPrice" class="form-label">Giá Bán</label>
                <input type="number" class="form-control" id="productCostPrice" name="sell_price"
                    value="{{ $product->sell_price }}">
            </div>
            <div class="col-md-6 mb-3">
                <label for="productSalePrice" class="form-label">Giá Nhập</label>
                <input type="number" class="form-control" id="productSalePrice" name="price"
                    value="{{ $product->price }}">
            </div>
            <div class="col-md-6 mb-3">
                <label for="productSalePrice" class="form-label">Giá Khuyến Mãi (Mãi bên nhau em nhe)</label>
                <input type="number" class="form-control" id="productSalePrice" name="sale_price"
                    value="{{ $product->sale_price }}">
            </div>

            <div class="col-md-6 mb-3">
                <label for="timestampInput" class="form-label">Ngày Giờ Bắt Đầu Giảm Giá</label>
                <input type="datetime-local" id="timestampInput" name="sale_price_start_at" class="form-control"
                    value="{{ $product->sale_price_start_at }}">
            </div>

            <div class="col-md-6 mb-3">
                <label for="timestampInput" class="form-label">Ngày Giờ Kết Thúc Giảm Giá</label>
                <input type="datetime-local" id="timestampInput" name="sale_price_end_at" class="form-control"
                    value="{{ $product->sale_price_end_at }}">
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Mô tả sản phẩm</label>
                <textarea class="form-control @error('bio') is-invalid @enderror" id="doctorBio" style="height: 100px"
                    name="content">{!! Str::limit($product->content) !!}
              </textarea>
                <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
                <script>
                    CKEDITOR.replace('doctorBio');
                </script>
            </div>

        </div>
        <button type="submit" class="btn btn-primary w-100">Cập nhật sản phẩm</button>
    </form>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
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


        $(document).ready(function() {
            $('#brandSelect').select2({
                placeholder: "Chọn tên thương hiệu",
                allowClear: true,
                width: '100%'
            });
        });
        ClassicEditor
            .create(document.querySelector('#doctorBio'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
