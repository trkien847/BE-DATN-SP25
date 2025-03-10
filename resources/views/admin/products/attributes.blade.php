@extends('admin.layouts.layout')

@section('content')
<div class="container">
    <h2>Danh sách Thuộc tính</h2>
    <a href="javascript:void(0)" class="btn btn-primary" id="add-attribute-btn">Thêm Thuộc tính</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Loại biến thể</th>
                <th>Thông số</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @php
                $groupedAttributes = $attributes->groupBy('name');
            @endphp
            @foreach ($groupedAttributes as $name => $group)
                <tr class="group-header" data-name="{{ $name }}">
                    <td>{{ $group->first()->id }}</td>
                    <td>{{ $name }}</td>
                    <td>{{ $group->first()->slug }} 
                        @foreach ($group->first()->values as $value)
                            <span class="badge bg-primary">{{ $value->value }}</span>
                        @endforeach
                    </td>
                    <td>{{ $group->first()->is_active ? 'Hiển thị' : 'Ẩn' }}</td>
                    <td>
                        <a href="javascript:void(0)" class="btn btn-primary edit-attribute-btn" 
                           data-id="{{ $group->first()->id }}" 
                           data-name="{{ $group->first()->name }}" 
                           data-slug="{{ $group->first()->slug }}"
                           data-value="{{ optional($group->first()->values->first())->value }}"
                           data-is-active="{{ $group->first()->is_active }}">Sửa Thuộc tính</a>
                    </td>
                </tr>
                <tr class="group-details" style="display: none;">
                    <td colspan="5">
                        <ul class="list-group">
                            @foreach ($group as $attribute)
                                <li class="list-group-item">
                                    {{ $attribute->name }} 
                                    @foreach ($attribute->values as $value)
                                        <span class="badge bg-primary"> {{ $attribute->slug }} {{ $value->value }}</span>
                                    @endforeach
                                     Trạng thái: {{ $attribute->is_active ? 'Hiển thị' : 'Ẩn' }}
                                    <a href="javascript:void(0)" class="btn btn-primary btn-sm edit-attribute-btn ms-2" 
                                       data-id="{{ $attribute->id }}" 
                                       data-name="{{ $attribute->name }}" 
                                       data-slug="{{ $attribute->slug }}"
                                       data-value="{{ optional($attribute->values->first())->value }}"
                                       data-is-active="{{ $attribute->is_active }}">Sửa Thuộc tính</a>
                                </li>
                            @endforeach
                        </ul>
                        <p class="text-muted mt-2">Tổng số biến thể: {{ $group->count() }}</p>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


<div id="attribute-overlay" class="attribute-overlay">
    <div class="overlay-content">
        <h4 id="overlay-title"></h4>
        <button type="button" id="close-overlay" class="btn-close" style="position: absolute; top: 10px; right: 10px;"></button>
        <form id="attribute-form" method="POST">
            @csrf
            <input type="hidden" name="attribute_id" id="attribute-id">
            <div class="mb-3">
                <label class="form-label">Tên Thuộc Tính</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Chọn loại biến thể</label>
                <select name="value" id="value" class="form-control">
                    <option value="viên" {{ old('value') == 'viên' ? 'selected' : '' }}>Viên</option>
                    <option value="ml" {{ old('value') == 'ml' ? 'selected' : '' }}>ml</option>
                    <option value="g" {{ old('value') == 'g' ? 'selected' : '' }}>g</option>
                </select>
                @error('value')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Số lượng/thuộc tính (30 viên/hộp hoặc 150ml/lọ)</label>
                <input type="number" name="slug" id="slug" class="form-control" value="{{ old('slug') }}" required>
                @error('slug')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="is_active" class="form-label">Trạng Thái</label>
                <select name="is_active" id="is_active" class="form-control">
                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ old('is_active', '1') == '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success" id="submit-btn">Thêm</button>
        </form>
    </div>
</div>


<style>
    .attribute-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .overlay-content {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        max-width: 500px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        transform: scale(0.8);
        opacity: 0;
        transition: transform 0.3s ease, opacity 0.3s ease;
    }
    .overlay-content.active {
        transform: scale(1);
        opacity: 1;
    }
    body.overlay-active {
        overflow: hidden;
    }
    .btn-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
    }
    .group-header {
        cursor: pointer;
        background-color: #f8f9fa;
    }
    .group-header:hover {
        background-color: #e9ecef;
    }
    .group-details {
        background-color: #fff;
    }
    .list-group-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>

<!-- jQuery và SweetAlert2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Toggle nhóm chi tiết khi nhấp vào header
    $('.group-header').on('click', function() {
        const $details = $(this).next('.group-details');
        $details.toggle();
    });

    // Hiển thị thông báo từ session
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK'
        });
    @endif
    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: '{{ session('error') }}',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                let isUpdate = $('#attribute-id').val() !== '';
                $('#overlay-title').text(isUpdate ? 'Sửa Thuộc Tính' : 'Thêm Thuộc Tính');
                $('#attribute-form').attr('action', isUpdate ? '{{ route('attributes.update', '') }}/' + $('#attribute-id').val() : '{{ route('attributes.store') }}');
                $('#name').val('{{ old('name') }}');
                $('#value').val('{{ old('value', 'viên') }}');
                $('#slug').val('{{ old('slug') }}');
                $('#is_active').val('{{ old('is_active', '1') }}');
                $('#submit-btn').text(isUpdate ? 'Cập nhật' : 'Thêm');
                if (isUpdate) {
                    $('#attribute-form').append('<input type="hidden" name="_method" value="PUT">');
                } else {
                    $('#attribute-form input[name="_method"]').remove();
                }
                $('#attribute-overlay').fadeIn(300).find('.overlay-content').addClass('active');
                $('body').addClass('overlay-active');
            }
        });
    @endif

    // Hiển thị overlay khi nhấp "Thêm Thuộc tính"
    $('#add-attribute-btn').on('click', function() {
        $('#overlay-title').text('Thêm Thuộc Tính');
        $('#attribute-form').attr('action', '{{ route('attributes.store') }}');
        $('#attribute-id').val('');
        $('#name').val('');
        $('#value').val('viên');
        $('#slug').val('');
        $('#is_active').val('1');
        $('#submit-btn').text('Thêm');
        $('#attribute-form input[name="_method"]').remove();
        $('#attribute-overlay').fadeIn(300).find('.overlay-content').addClass('active');
        $('body').addClass('overlay-active');
    });

    // Hiển thị overlay khi nhấp "Sửa Thuộc tính"
    $('.edit-attribute-btn').on('click', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let slug = $(this).data('slug');
        let value = $(this).data('value');
        let isActive = $(this).data('is-active');

        $('#overlay-title').text('Sửa Thuộc Tính');
        $('#attribute-form').attr('action', '{{ route('attributes.update', '') }}/' + id);
        $('#attribute-form').append('<input type="hidden" name="_method" value="PUT">');
        $('#attribute-id').val(id);
        $('#name').val(name);
        $('#value').val(value || 'viên');
        $('#slug').val(slug);
        $('#is_active').val(isActive ? '1' : '0');
        $('#submit-btn').text('Cập nhật');
        $('#attribute-overlay').fadeIn(300).find('.overlay-content').addClass('active');
        $('body').addClass('overlay-active');
    });

    // Ẩn overlay khi nhấp "Đóng"
    $('#close-overlay').on('click', function() {
        $('#attribute-overlay').find('.overlay-content').removeClass('active');
        setTimeout(function() {
            $('#attribute-overlay').fadeOut(300);
            $('#attribute-form input[name="_method"]').remove();
            $('body').removeClass('overlay-active');
        }, 300);
    });

    // Ẩn overlay khi nhấp ra ngoài nội dung
    $('#attribute-overlay').on('click', function(e) {
        if (e.target === this) {
            $('#attribute-overlay').find('.overlay-content').removeClass('active');
            setTimeout(function() {
                $('#attribute-overlay').fadeOut(300);
                $('#attribute-form input[name="_method"]').remove();
                $('body').removeClass('overlay-active');
            }, 300);
        }
    });
});
</script>
@endsection