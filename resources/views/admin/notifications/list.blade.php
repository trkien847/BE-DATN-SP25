@extends('admin.layouts.layout')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Thông Báo</h4>
                        <div class="btn-group">
                            <button id="mark-all-read" class="btn btn-primary me-2">
                                <i class="bx bx-check-double me-1"></i>Đánh dấu tất cả đã đọc
                            </button>
                            <button id="clear-all-read" class="btn btn-danger">
                                <i class="bx bx-trash me-1"></i>Xóa thông báo đã đọc
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="notification-list">
                        @forelse ($notifications as $notification)
                            <a href="{{ $notification->data['url'] ?? '#' }}"
                                class="notification-item p-3 border-bottom d-block text-decoration-none"
                                data-id="{{ $notification->id }}">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar avatar-sm">
                                            <i class="bx bx-bell fs-3 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">{{ $notification->data['title'] ?? 'Thông báo mới' }}</h6>
                                        <p class="mb-1">{{ $notification->data['message'] }}</p>
                                        <p class="mb-0">Người tạo: {{ $notification->data['created_by'] }}</p>
                                        <small class="text-muted">
                                            {{ $notification->created_at->diffForHumans() }}
                                            @if (!$notification->read_at)
                                                <span class="badge bg-danger ms-2">Mới</span>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-5">
                                <i class="bx bx-bell-off fs-1 text-muted"></i>
                                <p class="mt-2">Không có thông báo nào</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                @if ($notifications->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.getElementById('mark-all-read')?.addEventListener('click', function() {
            fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cập nhật giao diện
                        document.querySelectorAll('.notification-item').forEach(item => {
                            item.classList.add('bg-light');
                            item.querySelector('.badge')?.remove();
                        });

                        // Cập nhật số thông báo trên header và sidebar
                        window.updateAllNotificationBadges();

                        // Thông báo thành công
                        alert('Đã đọc tất cả thông báo');
                    }
                })
                .catch(error => console.error('Lỗi:', error));
        });
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function() {
                let notificationId = this.getAttribute('data-id');

                fetch(`/notifications/${notificationId}/mark-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                        'Content-Type': 'application/json',
                    },
                });
            });
        });
        document.getElementById('clear-all-read')?.addEventListener('click', function() {
            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc chắn muốn xóa tất cả thông báo đã đọc?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/notifications/clear-all-read', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Xóa các thông báo đã đọc khỏi giao diện
                                const readNotifications = document.querySelectorAll(
                                    '.notification-item');
                                readNotifications.forEach(item => {
                                    if (!item.querySelector('.badge')) {
                                        item.remove();
                                    }
                                });

                                // Kiểm tra và cập nhật giao diện nếu không còn thông báo
                                const notificationList = document.querySelector('.notification-list');
                                const remainingNotifications = notificationList.querySelectorAll(
                                    '.notification-item');

                                if (remainingNotifications.length === 0) {
                                    notificationList.innerHTML = `
                                <div class="text-center py-5">
                                    <i class="bx bx-bell-off fs-1 text-muted"></i>
                                    <p class="mt-2">Không có thông báo nào</p>
                                </div>
                            `;
                                }

                                // Cập nhật số thông báo trên header và sidebar
                                window.updateAllNotificationBadges?.();

                                // Hiển thị thông báo thành công
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Thành công',
                                    text: 'Đã xóa tất cả thông báo đã đọc',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Lỗi:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: 'Có lỗi xảy ra khi xóa thông báo'
                            });
                        });
                }
            });
        });
    </script>
@endpush
