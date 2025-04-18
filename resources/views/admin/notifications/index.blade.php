@extends('admin.layouts.layout')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .notification-table th,
        .notification-table td {
            vertical-align: middle;
        }

        .action-buttons .btn {
            margin-right: 5px;
        }

        .card-header {
            background-color: #007bff;
            color: white;
        }

        .notification-section {
            margin-bottom: 30px;
        }
    </style>

    <div class="container mt-5">
        <h1 class="mb-4">Danh sách thông báo nhớ ngày 30/4 nhé ae</h1>
        <p>Chọn checkbox đang bị khóa</p>

        @php
            $notificationTypes = [
                'order_cancel' => 'Yêu cầu hủy đơn hàng',
                'refund_request' => 'Yêu cầu hoàn tiền',
                'order_status_request' => 'Yêu cầu thay đổi trạng thái đơn hàng',
                'return_request' => 'Yêu cầu hoàn hàng',
                'product_pending_create' => 'Yêu cầu thêm sản phẩm mới',
                'product_pending_update' => 'Yêu cầu sửa sản phẩm',
                'import_pending' => 'Yêu cầu nhập hàng',
                'category_pending_create' => 'Yêu cầu tạo danh mục mới',
                'category_pending_update' => 'Yêu cầu cập nhật danh mục',
                'category_approval_response' => 'Phản hồi yêu cầu danh mục',
            ];
        @endphp

        @foreach ($notificationTypes as $type => $title)
            @php
                $filteredNotifications = $notifications->where('type', $type);
            @endphp
            @if ($filteredNotifications->isNotEmpty())
                <div class="notification-section">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="mb-0">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover notification-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tiêu đề</th>
                                            <th>Nội dung</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($filteredNotifications as $notification)
                                            <tr>
                                                <td>{{ $notification->title }}</td>
                                                <td>{{ $notification->content }}</td>
                                                <td class="action-buttons">
                                                    @if ($notification->type === 'order_cancel')
                                                        <form
                                                            action="{{ $notification->data['actions']['cancel_request'] }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="notification_id"
                                                                value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-danger">Hủy yêu
                                                                cầu</button>
                                                        </form>
                                                        <form
                                                            action="{{ $notification->data['actions']['accept_request'] }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="notification_id"
                                                                value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-success">Chấp
                                                                nhận</button>
                                                        </form>
                                                        <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}"
                                                            class="btn btn-sm btn-info">Xem chi tiết</a>
                                                    @elseif($notification->type === 'refund_request')
                                                        <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}"
                                                            class="btn btn-sm btn-info">Xem trực tiếp</a>
                                                    @elseif($notification->type === 'order_status_request')
                                                        <form
                                                            action="{{ $notification->data['actions']['cancel_request'] }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="notification_id"
                                                                value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-danger">Hủy yêu
                                                                cầu</button>
                                                        </form>
                                                        <form
                                                            action="{{ $notification->data['actions']['accept_request'] }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="notification_id"
                                                                value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-success">Chấp
                                                                nhận</button>
                                                        </form>
                                                        <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}"
                                                            class="btn btn-sm btn-info">Xem chi tiết</a>
                                                    @elseif($notification->type === 'return_request')
                                                        <form
                                                            action="{{ $notification->data['actions']['accept_request'] }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="notification_id"
                                                                value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-success">Chấp
                                                                nhận</button>
                                                        </form>
                                                        <form
                                                            action="{{ $notification->data['actions']['cancel_request'] }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="notification_id"
                                                                value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-danger">Hủy yêu
                                                                cầu</button>
                                                        </form>
                                                        <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}"
                                                            class="btn btn-sm btn-info">Xem chi tiết</a>
                                                    @elseif($notification->type === 'product_pending_create')
                                                        <form
                                                            action="{{ $notification->data['actions']['approve_request'] }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="notification_id"
                                                                value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-success">Chấp
                                                                nhận</button>
                                                        </form>
                                                        <form
                                                            action="{{ $notification->data['actions']['reject_request'] }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="notification_id"
                                                                value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-danger">Hủy yêu
                                                                cầu</button>
                                                        </form>
                                                        <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}"
                                                            class="btn btn-sm btn-info">Xem chi tiết</a>
                                                    @elseif($notification->type === 'product_pending_update')
                                                        <form
                                                            action="{{ $notification->data['actions']['approve_request'] }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="notification_id"
                                                                value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-success">Chấp
                                                                nhận</button>
                                                        </form>
                                                        <form
                                                            action="{{ $notification->data['actions']['reject_request'] }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="notification_id"
                                                                value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-danger">Hủy yêu
                                                                cầu</button>
                                                        </form>
                                                        <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}"
                                                            class="btn btn-sm btn-info">Xem chi tiết</a>
                                                    @elseif($notification->type === 'import_pending')
                                                        <form
                                                            action="{{ $notification->data['actions']['confirm_request'] }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="notification_id"
                                                                value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-success">Chấp
                                                                nhận</button>
                                                        </form>
                                                        <form
                                                            action="{{ $notification->data['actions']['reject_request'] }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="notification_id"
                                                                value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-danger">Hủy yêu
                                                                cầu</button>
                                                        </form>
                                                    @elseif($notification->type === 'category_pending_create' || $notification->type === 'category_pending_update')
                                                        <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}"
                                                            class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye me-1"></i>Xem chi tiết
                                                        </a>
                                                    @elseif($notification->type === 'category_approval_response')
                                                        <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}"
                                                            class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye me-1"></i>Xem chi tiết
                                                        </a>
                                                        <form action="{{ $notification->data['actions']['acknowledge'] }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="notification_id"
                                                                value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                <i class="fas fa-check me-1"></i>Đã xem
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        @if ($notifications->isEmpty())
            <div class="alert alert-info text-center">
                Không có thông báo nào
            </div>
        @endif
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lấy tất cả các form có action chứa "category"
            const categoryForms = document.querySelectorAll('form[action*="category"]');

            categoryForms.forEach(form => {
                form.addEventListener('submit', async function(e) {
                    // Ngăn form submit mặc định
                    e.preventDefault();

                    try {
                        const formData = new FormData(this);
                        const url = this.getAttribute('action');
                        const button = this.querySelector('button');
                        const originalText = button.innerHTML;

                        // Disable button và hiển thị trạng thái loading
                        button.disabled = true;
                        button.innerHTML =
                            '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';

                        // Gửi request
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const result = await response.json();

                        if (result.success) {
                            await Swal.fire({
                                title: 'Thành công!',
                                text: result.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // Xóa row khỏi bảng
                            const row = this.closest('tr');
                            const tbody = row.closest('tbody');
                            const section = tbody.closest('.notification-section');

                            row.remove();

                            // Nếu không còn thông báo nào, xóa section
                            if (!tbody.querySelector('tr')) {
                                section.remove();
                            }

                            // Nếu không còn section nào, hiển thị "Không có thông báo"
                            if (!document.querySelector('.notification-section')) {
                                const container = document.querySelector('.container');
                                container.innerHTML = `
                            <div class="alert alert-info text-center">
                                Không có thông báo nào
                            </div>
                        `;
                            }
                        } else {
                            throw new Error(result.message || 'Có lỗi xảy ra');
                        }
                    } catch (error) {
                        Swal.fire({
                            title: 'Lỗi!',
                            text: error.message || 'Đã có lỗi xảy ra, vui lòng thử lại',
                            icon: 'error'
                        });
                    } finally {
                        const button = this.querySelector('button');
                        if (button) {
                            button.disabled = false;
                            button.innerHTML = button.getAttribute('data-original-text') ||
                                'Xác nhận';
                        }
                    }
                });
            });
        });
    </script>
@endsection
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
@endpush
