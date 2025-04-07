@extends('client.layouts.layout')
@section('content')
<style>
    .form-container {
        max-width: 500px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .form-group {
        margin-bottom: 15px;
    }
    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    textarea, input[type="file"] {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .submit-btn {
        padding: 10px 20px;
        background-color: #ff9800;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .submit-btn:hover {
        background-color: #e68a00;
    }
</style>

<div class="form-container">
    <h2>Yêu cầu hoàn hàng {{ $order->code }}</h2>
    <form action="{{ route('order.return', $order->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="return_reason">Lý do hoàn hàng:</label>
            <textarea name="return_reason" id="return_reason" rows="4" required></textarea>
            @error('return_reason')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="return_images">Ảnh minh chứng (tối đa 5 ảnh):</label>
            <input type="file" name="return_images[]" id="return_images" multiple accept="image/*" onchange="previewImages(event)">
            @error('return_images.*')
                <span style="color: red;">{{ $message }}</span>
            @enderror
            <!-- Khu vực hiển thị ảnh xem trước -->
            <div id="image-preview" style="margin-top: 10px; display: flex; flex-wrap: wrap; gap: 10px;"></div>
        </div>
        <button type="submit" class="submit-btn">Gửi yêu cầu hoàn hàng</button>
    </form>
</div>


<script>
    function previewImages(event) {
        const input = event.target;
        const previewContainer = document.getElementById('image-preview');
        previewContainer.innerHTML = ''; // Xóa các ảnh cũ

        const files = input.files;
        const maxFiles = 5;

        if (files.length > maxFiles) {
            alert(`Bạn chỉ có thể tải lên tối đa ${maxFiles} ảnh!`);
            input.value = ''; // Xóa các file đã chọn
            return;
        }

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (!file.type.startsWith('image/')) {
                alert('Vui lòng chỉ chọn file ảnh!');
                input.value = '';
                previewContainer.innerHTML = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100px'; // Kích thước xem trước
                img.style.maxHeight = '100px';
                img.style.objectFit = 'cover'; // Giữ tỉ lệ ảnh
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection