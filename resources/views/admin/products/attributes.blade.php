@extends('admin.layouts.layout')
@section('content')
<div class="container">
    <h2>Danh sách Thuộc tính</h2>
    <a href="javascript:void(0)" class="btn btn-primary" id="add-attribute-btn2">Thêm Thuộc tính</a>
    <table class="table">
        <thead>
            <tr>
                <th>Loại biến thể</th>
                <th>Số lượng biến thể</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @php
                $groupedAttributes = $attributes->groupBy('name');
            @endphp
            @foreach ($groupedAttributes as $name => $group)
                <tr class="group-header" data-name="{{ $name }}">
                    <td>{{ $name }}</td>
                    <td>{{ $group->count() }}</td>
                    <td>
                        <a href="javascript:void(0)" 
                        class="btn btn-primary btn-sm ms-2 add-variant-btn" 
                        data-name="{{ $name }}">
                            <i class="fas fa-plus"></i> Thêm dữ liệu vào biến thể
                        </a>
                    </td>
                </tr>
                <tr class="group-details" style="display: none;">
                    <td colspan="5">
                        <ul class="list-group">
                            @foreach ($group as $attribute)
                                <li class="list-group-item">
                                    {{ $attribute->name }} 
                                    @foreach ($attribute->values as $value)
                                        <span class="badge bg-primary">{{ $attribute->slug }} {{ $value->value }}</span>
                                    @endforeach
                                    
                                    <div class="form-check form-switch d-inline-block ms-2">
                                        <input type="checkbox" 
                                            class="form-check-input status-toggle" 
                                            id="status-{{ $attribute->id }}"
                                            data-id="{{ $attribute->id }}"
                                            {{ $attribute->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status-{{ $attribute->id }}">
                                            <span class="status-text">{{ $attribute->is_active ? 'Hiển thị' : 'Ẩn' }}</span>
                                        </label>
                                    </div>
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

    .add-variant-btn {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
        white-space: nowrap;
    }

    .add-variant-btn i {
        margin-right: 0.25rem;
    }

    .group-header {
        user-select: none;
    }

    .form-switch {
        padding-left: 2.5em;
    }

    .form-check-input {
        cursor: pointer;
    }

    .status-text {
        font-size: 0.9em;
        color: #666;
    }

    .form-switch .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }

    .form-switch .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
    }

    input[readonly] {
        background-color: #e9ecef !important;
        cursor: not-allowed !important;
        color: #495057 !important;
    }

    .swal2-popup {
        font-size: 0.9rem !important;
        border-radius: 1rem !important;
    }

    .swal2-title {
        font-size: 1.3rem !important;
        font-weight: 600 !important;
    }

    .swal2-confirm {
        padding: 0.5rem 1.5rem !important;
        font-weight: 500 !important;
        letter-spacing: 0.5px !important;
    }

    .swal2-cancel {
        padding: 0.5rem 1.5rem !important;
        font-weight: 500 !important;
        letter-spacing: 0.5px !important;
    }

    .swal2-icon {
        width: 4em !important;
        height: 4em !important;
        margin: 1em auto 0.5em !important;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('.group-header').on('click', function() {
        const $details = $(this).next('.group-details');
        $details.toggle();
    });

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
                $('#overlay-title').text(isUpdate ? 'Sửa Thuộc Tính ( Designed by TG )' : 'Thêm Thuộc Tính ( Designed by TG )');
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

    $('#add-attribute-btn2').on('click', function() {
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


    $('.add-variant-btn').on('click', function(e) {
        e.stopPropagation();
        const attributeName = $(this).data('name');
        $('#overlay-title').text('Thêm Biến Thể Mới');
        $('#attribute-form').attr('action', '{{ route('attributes.store') }}');
        $('#attribute-id').val('');
        $('#name').val(attributeName).prop('readonly', true);
        $('#value').val('viên');
        $('#slug').val('');
        $('#is_active').val('1');
        $('#submit-btn').text('Thêm');
        $('#attribute-form input[name="_method"]').remove();
        $('#attribute-overlay').fadeIn(300).find('.overlay-content').addClass('active');
        $('body').addClass('overlay-active');
    });

   
    $('#close-overlay').on('click', function() {
        $('#attribute-overlay').find('.overlay-content').removeClass('active');
        setTimeout(function() {
            $('#attribute-overlay').fadeOut(300);
            $('#attribute-form input[name="_method"]').remove();
            $('body').removeClass('overlay-active');
        }, 300);
    });

    
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

document.querySelectorAll('.status-toggle').forEach(toggle => {
    toggle.addEventListener('change', async function() {
        const attributeId = this.dataset.id;
        const newStatus = this.checked;
        const statusText = this.closest('.form-check').querySelector('.status-text');
        const result = await Swal.fire({
            title: 'Xác nhận thay đổi?',
            text: newStatus ? 
                'Bạn có chắc chắn muốn hiển thị thuộc tính này?' : 
                'Bạn có chắc chắn muốn ẩn thuộc tính này?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy',
            reverseButtons: true
        });

        if (result.isConfirmed) {
            try {
                Swal.fire({
                    title: 'Đang xử lý...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const response = await fetch(`/admin/attributes/${attributeId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ is_active: newStatus })
                });

                const data = await response.json();

                if (data.success) {
                    statusText.textContent = newStatus ? 'Hiển thị' : 'Ẩn';
                    await Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                await Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: error.message || 'Có lỗi xảy ra khi cập nhật trạng thái',
                    confirmButtonText: 'Đóng'
                });
                
                this.checked = !newStatus;
            }
        } else {
            this.checked = !newStatus;
        }
    });
});
</script>
@endsection