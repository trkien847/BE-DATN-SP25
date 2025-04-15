@extends('admin.layouts.layout')
@section('content')
<div> 
            <audio id="backgroundMusic" autoplay>
                <source src="{{ asset('audio/w.mp3') }}" type="audio/mpeg">
            </audio>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const audio = document.getElementById('backgroundMusic');
                audio.volume = 1;
                let playPromise = audio.play();
                
                if (playPromise !== undefined) {
                    playPromise.catch(error => {
                        console.log("Autoplay was prevented");
                    });
                }
                document.addEventListener('visibilitychange', function() {
                    if (!document.hidden && !audio.ended) {
                        audio.play();
                    }
                });
            });
            </script>
    </div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container">
    <h2>Danh sách Thuộc tính</h2>
    <a href="javascript:void(0)" class="btn btn-Success" id="add-attribute-btn2" style="background: #1bb394; color: white;">Thêm Thuộc tính</a>
    <table class="table">
        <thead>
            <tr>
                <th>Loại biến thể</th>
                <th>Số lượng biến thể</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($groupedAttributes as $name => $group)
            <tr class="group-header" data-name="{{ $name }}">
                <td>{{ $name }}</td>
                <td>{{ $group['count'] }}</td>
                <td>
                <a href="javascript:void(0)" 
                class="btn btn-sm ms-2 add-variant-btn" 
                data-name="{{ $name }}"
                data-id="{{ $group['firstAttribute']->id }}" style="background: #1bb394; color: white;">
                    <i class="fas fa-plus"></i> Thêm dữ liệu vào biến thể
                </a>
                </td>
            </tr>
            
            <tr class="group-details" style="display: none;">
                <td colspan="5">
                    <ul class="list-group">
                        @foreach ($group['firstAttribute']->values as $value)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-primary">{{ $value->value }} {{ $value->id }}</span>
                                </div>
                                <div class="form-check form-switch d-inline-block">
                                    <input type="checkbox" 
                                        class="form-check-input status-toggle" 
                                        id="status-value-{{ $value->id }}"
                                        data-id="{{ $value->id }}"
                                        {{ $value->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status-value-{{ $value->id }}">
                                        <span class="status-text">{{ $value->is_active ? 'Hiển thị' : 'Ẩn' }}</span>
                                    </label>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <p class="text-muted mt-2">Tổng số giá trị: {{ $group['count'] }}</p>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>


<div id="attribute-overlay" class="attribute-overlay">
    <div class="overlay-content">
        <h4 id="overlay-title"></h4>
        <button type="button" id="close-overlay" class="btn-close-custom">
            <i class="fas fa-times"></i>
        </button>
        <form id="attribute-form" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Tên loại biến thể</label>
                <select name="name" id="name" class="form-control" required>
                    <option value="">-- Chọn loại biến thể --</option>
                    <option value="Hình thù">Hình thù</option>
                    <option value="Khối lượng">Khối lượng</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Mã biến thể</label>
                <input type="text" name="slug" id="slug" class="form-control" readonly>
                <small class="text-muted">Mã sẽ tự động được tạo</small>
            </div>
            <div class="mb-3">
                <label for="is_active" class="form-label">Trạng thái</label>
                <select name="is_active" id="is_active" class="form-control">
                    <option value="1">Hiển thị</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>
            <input type="hidden" name="is_variant" value="1">
            <button type="submit" class="btn btn-success" id="submit-btn">Thêm loại biến thể</button>
        </form>
    </div>
</div>

<!-- Form thêm giá trị biến thể  group-header-->
<div id="variant-value-overlay" class="attribute-overlay">
    <div class="overlay-content">
        <h4 id="variant-overlay-title"></h4>
        <button type="button" id="close-variant-overlay" class="btn-close-custom">
            <i class="fas fa-times"></i>
        </button>
        <form id="variant-value-form" method="POST">
            @csrf
            <input type="hidden" name="attribute_id" id="variant-attribute-id">
            <input type="hidden" name="variant_type" id="variant-type">
            <div class="mb-3">
                <label class="form-label">Giá trị</label>
                <div class="input-group">
                    <input type="number" name="amount" id="amount" class="form-control" required>
                    <select name="unit" id="unit" class="form-control">
                        <!-- Options will be dynamically populated -->
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="value_is_active" class="form-label">Trạng thái</label>
                <select name="is_active" id="value_is_active" class="form-control">
                    <option value="1">Hiển thị</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Thêm giá trị</button>
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
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1050;
    }

    .overlay-content {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        max-width: 500px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        transform: scale(0.8);
        opacity: 0;
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .overlay-content.active {
        transform: scale(1);
        opacity: 1;
    }

    .btn-close {
        position: absolute;
        top: 10px;
        right: 10px;
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        z-index: 1;
    }

    body.overlay-active {
        overflow: hidden;
    }
    .btn-close-custom {
        position: absolute;
        top: 10px;
        right: 10px;
        background: none;
        border: none;
        padding: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }

    .btn-close-custom:hover {
        background-color: rgba(0, 0, 0, 0.1);
    }

    .btn-close-custom i {
        font-size: 20px;
        color: #666;
    }

    .btn-close-custom:hover i {
        color: #333;
    }
    .input-group input[type="number"] {
        transition: all 0.3s ease;
    }

    select.form-control.rounded {
        border-top-left-radius: 0.375rem !important;
        border-bottom-left-radius: 0.375rem !important;
    }
    .group-header {
        cursor: pointer;
        background-color: #f8f9fa;
        position: relative;
        transition: all 0.3s ease;
    }

    .group-header:hover {
        background-color: #e9ecef;
        transform: translateX(5px);
    }

    /* Add expand/collapse indicator */
    .group-header td:first-child {
        position: relative;
        padding-left: 2rem;
    }

    .group-header td:first-child:before {
        content: '\f0da'; /* FontAwesome right arrow */
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        transition: transform 0.3s ease;
        color: #6c757d;
    }

    .group-header.expanded td:first-child:before {
        transform: translateY(-50%) rotate(90deg);
    }

    /* Add subtle border and shadow */
    .group-header {
        border-left: 3px solid transparent;
    }

    .group-header:hover {
        border-left-color: #0d6efd;
    }

    /* Animation for details panel */
    .group-details {
        background-color: #fff;
        transition: all 0.3s ease;
        display: none; /* Thêm display: none mặc định */
    }

    .group-details.show {
        display: table-row;
        opacity: 1;
    }

    /* Bỏ max-height và overflow để tránh conflict */
    .group-details {
        background-color: #fff;
        transition: opacity 0.3s ease;
        opacity: 0;
    }

    /* Add helper text */
    .click-helper {
        position: absolute;
        right: 100%;
        top: 50%;
        transform: translateY(-50%);
        background-color: #6c757d;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        opacity: 0;
        transition: all 0.3s ease;
        pointer-events: none;
        white-space: nowrap;
    }

    .group-header:hover .click-helper {
        opacity: 1;
        right: calc(100% + 10px);
    }

    /* Add pulse effect to new items */
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(13, 110, 253, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(13, 110, 253, 0);
        }
    }

    .list-group-item {
        animation: pulse 2s infinite;
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
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

    const shapeUnits = [
        { value: 'hộp', text: 'Hộp' },
        { value: 'vỉ', text: 'Vỉ' },
        { value: 'lọ', text: 'Lọ' },
        { value: 'tuýp', text: 'Tuýp' },
        { value: 'gói', text: 'Gói' }
    ];

    const weightUnits = [
        { value: 'viên', text: 'Viên' },
        { value: 'ml', text: 'ml' },
        { value: 'g', text: 'g' }
    ];

    function showOverlay(overlayId) {
        $(overlayId)
            .css('display', 'flex')
            .find('.overlay-content')
            .addClass('active');
        $('body').addClass('overlay-active');
    }

    function hideOverlay(overlay) {
        $(overlay)
            .find('.overlay-content')
            .removeClass('active');
        setTimeout(() => {
            $(overlay).hide();
            $('body').removeClass('overlay-active');
        }, 300);
    }

    function canAddMoreAttributes() {
        return $('#name option:not(:disabled):not(:first)').length > 0;
    }

    function showMaxAttributesWarning() {
        Swal.fire({
            icon: 'warning',
            title: 'Không thể thêm',
            text: 'Đã tạo đủ các loại biến thể cho phép (Hình thù và Khối lượng)',
            confirmButtonText: 'Đóng'
        });
    }

    function populateUnitOptions(type) {
        const unitSelect = $('#unit');
        const amountInput = $('#amount');
        
        unitSelect.empty();
        
        if (type === 'Hình thù') {
            amountInput.hide().prop('required', false);
            unitSelect.removeClass('rounded-0 rounded-end').addClass('rounded');
            shapeUnits.forEach(unit => unitSelect.append(new Option(unit.text, unit.value)));
        } else {
            amountInput.show().prop('required', true);
            unitSelect.removeClass('rounded').addClass('rounded-0 rounded-end');
            weightUnits.forEach(unit => unitSelect.append(new Option(unit.text, unit.value)));
        }
    }

    $('.group-header').off('click').on('click', function(e) {
        if (!$(e.target).closest('.add-variant-btn').length) {
            const $header = $(this);
            const $details = $header.next('.group-details');
            
            if (!$details.hasClass('show')) {
                $('.group-details.show').removeClass('show').hide();
                $('.group-header.expanded').removeClass('expanded');
                
                $header.addClass('expanded');
                $details.show().addClass('show');
            } else {
                $header.removeClass('expanded');
                $details.removeClass('show').hide();
            }
        }
    });

    $(document).on('click', '#add-attribute-btn2', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (!canAddMoreAttributes()) {
            showMaxAttributesWarning();
            return;
        }

        $('#overlay-title').text('Thêm Loại Biến Thể Mới');
        $('#attribute-form')
            .attr('action', '{{ route('attributes.store') }}')
            .trigger('reset');
        $('#is_active').val('1');
        showOverlay('#attribute-overlay');
    });

    $(document).on('click', '.add-variant-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const attributeName = $(this).data('name');
        const attributeId = $(this).data('id');
        
        $('#variant-overlay-title').text(`Thêm giá trị cho ${attributeName} ( Designed by TG )`);
        $('#variant-value-form')
            .attr('action', '{{ route('attribute-values.store') }}')
            .trigger('reset');
        $('#variant-attribute-id').val(attributeId);
        $('#variant-type').val(attributeName);
        $('#value_is_active').val('1');
        
        populateUnitOptions(attributeName);
        showOverlay('#variant-value-overlay');
    });

    $(document).on('click', '.btn-close-custom, .btn-close, .attribute-overlay', function(e) {
        if (e.target === this) {
            hideOverlay($(this).closest('.attribute-overlay'));
        }
    });

    $('.overlay-content').on('click', function(e) {
        e.stopPropagation();
    });

    function init() {
        setupAlertMessages();
        setupHelperText();
        checkExistingAttributes();
        setupEventHandlers();
    }

    function setupAlertMessages() {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
        @endif
    }

    function checkExistingAttributes() {
        const existingAttributes = $('.group-header').map(function() {
            return $(this).data('name');
        }).get();

        $('#name option').each(function() {
            if (existingAttributes.includes($(this).val()) && $(this).val()) {
                $(this).prop('disabled', true);
            }
        });
    }

    function setupHelperText() {
        $('.group-header').each(function() {
            $(this).find('td:first').append('<span class="click-helper">Click để xem chi tiết</span>');
        });
    }

    function setupEventHandlers() {
        $('#name').on('change', function() {
            const name = $(this).val();
            const slug = name.toLowerCase()
                .replace(/đ/g, 'd')
                .replace(/[^a-z0-9]/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
            $('#slug').val(slug);
        });
    }

     $('#close-overlay, #close-variant-overlay').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const $overlay = $(this).closest('.attribute-overlay');
        
        $overlay.find('.overlay-content').removeClass('active');
        setTimeout(() => {
            $overlay.hide();
            $('body').removeClass('overlay-active');
        }, 300);
    });

    $('.attribute-overlay').on('click', function(e) {
        if (e.target === this) {
            const $overlay = $(this);
            $overlay.find('.overlay-content').removeClass('active');
            setTimeout(() => {
                $overlay.hide();
                $('body').removeClass('overlay-active');
            }, 300);
        }
    });

    $('.overlay-content').on('click', function(e) {
        e.stopPropagation();
    });

    init();
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
                        'Accept': 'application/json',
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