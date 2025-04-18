<style>
    .notification-item {
        transition: background-color 0.3s;
    }

    .notification-item:hover {
        background-color: #f8f9fa;
    }

    .notification-item h6 {
        font-size: 14px;
        color: #333;
    }

    .notification-item p {
        color: #666;
    }

    .footer-bg {
        background-image: url('https://cdn.pixabay.com/animation/2022/09/18/18/39/18-39-26-615_512.gif');
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .custom-btn {
        background-color: rgba(0, 123, 255, 0.7);
        border-color: #007bff;
        color: white;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        position: relative;
        z-index: 1;
    }

    #notification-list {
        max-height: 400px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #10B981 #F3F4F6;
    }

    #notification-list::-webkit-scrollbar {
        width: 6px;
    }

    #notification-list::-webkit-scrollbar-track {
        background: #F3F4F6;
        border-radius: 10px;
    }

    #notification-list::-webkit-scrollbar-thumb {
        background-color: #10B981;
        border-radius: 10px;
        border: 2px solid transparent;
    }

    /* Notification Item Animations */
    .notification-item {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
        animation: slideIn 0.3s ease-out;
    }

    .notification-item:hover {
        background-color: #F8FAFC;
        border-left-color: #10B981;
        transform: translateX(4px);
    }

    /* Button Animations */
    .notification-item .btn {
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .notification-item .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .notification-item .btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(255, 255, 255, 0.5);
        opacity: 0;
        border-radius: 100%;
        transform: scale(1, 1) translate(-50%);
        transform-origin: 50% 50%;
    }

    .notification-item .btn:active::after {
        animation: ripple 1s ease-out;
    }

    /* Unread Notification Indicator */
    .notification-item:not(.bg-light)::before {
        content: '';
        position: absolute;
        right: 10px;
        top: 10px;
        width: 8px;
        height: 8px;
        background: #10B981;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    /* Animations */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }

        70% {
            transform: scale(1);
            box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
        }

        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }

    @keyframes ripple {
        0% {
            transform: scale(0, 0);
            opacity: 1;
        }

        20% {
            transform: scale(25, 25);
            opacity: 1;
        }

        100% {
            opacity: 0;
            transform: scale(40, 40);
        }
    }

    /* Empty State Animation */
    .notification-empty {
        animation: fadeIn 0.5s ease-out;
        padding: 2rem;
        text-align: center;
        color: #6B7280;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .custom-swal-popup {
        border-radius: 15px;
        padding: 20px;
    }

    .custom-swal-title {
        color: #2d3748;
        font-size: 1.5rem;
    }

    .custom-swal-confirm {
        background-color: #48bb78 !important;
        border-radius: 8px !important;
    }

    .custom-swal-cancel {
        background-color: #f56565 !important;
        border-radius: 8px !important;
    }

    .custom-swal-content {
        color: #4a5568;
    }

    .custom-swal-icon {
        border-color: #48bb78;
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<header class="topbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <div class="d-flex align-items-center gap-2">
                <!-- Menu Toggle Button -->
                <div class="topbar-item">
                    <button type="button" class="button-toggle-menu topbar-button">
                        <iconify-icon icon="solar:hamburger-menu-broken" class="fs-24 align-middle"></iconify-icon>
                    </button>
                </div>

                <!-- App Search-->
                <form class="app-search d-none d-md-block me-auto">
                    <div class="position-relative">
                        <input type="search" class="form-control" placeholder="Search..." autocomplete="off"
                            value="">
                        <iconify-icon icon="solar:magnifer-broken" class="search-widget-icon"></iconify-icon>
                    </div>
                </form>
            </div>

            <div class="d-flex align-items-center gap-1">
                <!-- Theme Color (Light/Dark) -->
                <div class="topbar-item">
                    <button type="button" class="topbar-button" id="light-dark-mode">
                        <iconify-icon icon="solar:moon-broken" class="fs-24 align-middle light-mode"></iconify-icon>
                        <iconify-icon icon="solar:sun-broken" class="fs-24 align-middle dark-mode"></iconify-icon>
                    </button>
                </div>

                <!-- Category -->
                <div class="dropdown topbar-item d-none d-lg-flex">
                    <button type="button" class="topbar-button" data-toggle="fullscreen">
                        <iconify-icon icon="solar:full-screen-broken"
                            class="fs-24 align-middle fullscreen"></iconify-icon>
                        <iconify-icon icon="solar:quit-full-screen-broken"
                            class="fs-24 align-middle quit-fullscreen"></iconify-icon>
                    </button>
                </div>

                <!-- Notification -->
                <div class="dropdown topbar-item">
                    <button type="button" class="topbar-button position-relative"
                        id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <iconify-icon icon="solar:bell-bing-broken" class="fs-24 align-middle"></iconify-icon>
                        <span id="notification-count"
                            class="position-absolute topbar-badge fs-10 translate-middle badge bg-danger rounded-pill">
                            <span
                                class="count">{{ \App\Models\Notification::userOrSystem(Auth::id())->where('is_read', false)->count() }}</span>
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    </button>

                    <div class="dropdown-menu py-0 dropdown-lg dropdown-menu-end"
                        aria-labelledby="page-header-notifications-dropdown">
                        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                            <div class="row align-items-center">
                                <div class="col d-flex align-items-center">
                                    <img src="https://scr.vn/wp-content/uploads/2020/07/%E1%BA%A3nh-ch%C3%A2n-dung-B%C3%A1c-H%E1%BB%93-7-771x1024.jpg"
                                        alt="Ảnh"
                                        style="width: 40px; height: 40px; margin-right: 10px; object-fit: cover;">
                                    <h6 class="m-0 fs-16 fw-semibold">Thông báo</h6>
                                </div>
                            </div>
                        </div>
                        <div data-simplebar id="notification-list" class="notification-container">
                            @foreach (\App\Models\Notification::userOrSystem(Auth::id())->where('is_read', 0)->latest()->limit(10)->get() as $notification)
                                <div
                                    class="notification-item p-3 border-bottom {{ $notification->is_read ? 'bg-light' : '' }}">
                                    <h6 class="mb-1">{{ $notification->title }}</h6>
                                    <p class="mb-2 fs-13">{{ $notification->content }}</p>
                                    @if ($notification->type === 'order_cancel')
                                        <div class="d-flex gap-2">
                                            <form action="{{ $notification->data['actions']['cancel_request'] }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="notification_id"
                                                    value="{{ $notification->id }}">
                                                <button type="submit" class="btn btn-sm btn-danger">Hủy yêu
                                                    cầu</button>
                                            </form>
                                            <form action="{{ $notification->data['actions']['accept_request'] }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="notification_id"
                                                    value="{{ $notification->id }}">
                                                <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                                            </form>
                                            <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}"
                                                class="btn btn-sm btn-info">Xem chi tiết</a>
                                        </div>
                                    @elseif($notification->type === 'refund_request')
                                        <div class="d-flex gap-2">
                                            <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}"
                                                class="btn btn-sm btn-info">Xem trực tiếp</a>
                                        </div>
                                    @elseif($notification->type === 'order_status_request')
                                        <div class="d-flex gap-2">
                                            <form action="{{ $notification->data['actions']['cancel_request'] }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="notification_id"
                                                    value="{{ $notification->id }}">
                                                <button type="submit" class="btn btn-sm btn-danger">Hủy yêu
                                                    cầu</button>
                                            </form>
                                            <form action="{{ $notification->data['actions']['accept_request'] }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="notification_id"
                                                    value="{{ $notification->id }}">
                                                <button type="submit" class="btn btn-sm btn-success">Chấp
                                                    nhận</button>
                                            </form>
                                            <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}"
                                                class="btn btn-sm btn-info">Xem chi tiết</a>
                                        </div>
                                    @elseif($notification->type === 'return_request')
                                        <div class="d-flex gap-2">
                                            <form action="{{ $notification->data['actions']['accept_request'] }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="notification_id"
                                                    value="{{ $notification->id }}">
                                                <button type="submit" class="btn btn-sm btn-success">Chấp
                                                    nhận</button>
                                            </form>
                                            <form action="{{ $notification->data['actions']['cancel_request'] }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="notification_id"
                                                    value="{{ $notification->id }}">
                                                <button type="submit" class="btn btn-sm btn-danger">Hủy yêu
                                                    cầu</button>
                                            </form>
                                            <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}"
                                                class="btn btn-sm btn-info">Xem chi tiết</a>
                                        </div>
                                    @elseif($notification->type === 'product_pending_create')
                                        <div class="d-flex gap-2">
                                            <form action="{{ $notification->data['actions']['approve_request'] }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="notification_id"
                                                    value="{{ $notification->id }}">
                                                <button type="submit" class="btn btn-sm btn-success">Chấp
                                                    nhận</button>
                                            </form>
                                            <form action="{{ $notification->data['actions']['reject_request'] }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="notification_id"
                                                    value="{{ $notification->id }}">
                                                <button type="submit" class="btn btn-sm btn-danger">Hủy yêu
                                                    cầu</button>
                                            </form>
                                            <a href="{{ $notification->data['actions']['view_details'] }}"
                                                class="btn btn-sm btn-info">Xem chi tiết</a>
                                        </div>
                                    @elseif($notification->type === 'product_pending_update')
                                        <div class="d-flex gap-2">
                                            <form action="{{ $notification->data['actions']['approve_request'] }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="notification_id"
                                                    value="{{ $notification->id }}">
                                                <button type="submit" class="btn btn-sm btn-success">Chấp
                                                    nhận</button>
                                            </form>
                                            <form action="{{ $notification->data['actions']['reject_request'] }}"
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
                                        </div>
                                    @elseif($notification->type === 'import_pending')
                                        <div class="d-flex gap-2">
                                            <form action="{{ $notification->data['actions']['confirm_request'] }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="notification_id"
                                                    value="{{ $notification->id }}">
                                                <button type="submit" class="btn btn-sm btn-success">Chấp
                                                    nhận</button>
                                            </form>
                                            <form action="{{ $notification->data['actions']['reject_request'] }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="notification_id"
                                                    value="{{ $notification->id }}">
                                                <button type="submit" class="btn btn-sm btn-danger">Hủy yêu
                                                    cầu</button>
                                            </form>
                                        </div>
                                    @elseif($notification->type === 'import_confirmation')
                                        <div class="d-flex gap-2">
                                            <a href="{{ $notification->data['actions']['view_details'] }}"
                                                class="btn btn-sm btn-info">
                                                <i class="fas fa-eye me-1"></i>Xem chi tiết
                                            </a>
                                            <form action="{{ $notification->data['actions']['confirm'] }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('POST')
                                                <input type="hidden" name="notification_id"
                                                    value="{{ $notification->id }}">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check me-1"></i>Xác nhận
                                                </button>
                                            </form>
                                            <form action="{{ $notification->data['actions']['cancel'] }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('POST')
                                                <input type="hidden" name="notification_id"
                                                    value="{{ $notification->id }}">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times me-1"></i>Từ chối
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($notification->type === 'import_response')
                                        <div class="d-flex gap-2">
                                            <a href="{{ $notification->data['actions']['view_details'] }}"
                                                class="btn btn-sm btn-info">
                                                <i class="fas fa-eye me-1"></i>Xem chi tiết
                                            </a>
                                            <form action="{{ $notification->data['actions']['acknowledge'] }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('POST')
                                                <input type="hidden" name="notification_id"
                                                    value="{{ $notification->id }}">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check me-1"></i>Đã xem
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($notification->type === 'category_pending_create' || $notification->type === 'category_pending_update')
                                        <div class="d-flex gap-2">
                                            <form action="{{ $notification->data['actions']['approve_request'] }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="notification_id"
                                                    value="{{ $notification->id }}">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i
                                                        class="fas fa-check me-1"></i>{{ $notification->type === 'category_pending_create' ? 'Phê duyệt' : 'Chấp nhận thay đổi' }}
                                                </button>
                                            </form>
                                            <form action="{{ $notification->data['actions']['reject_request'] }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="notification_id"
                                                    value="{{ $notification->id }}">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i
                                                        class="fas fa-times me-1"></i>{{ $notification->type === 'category_pending_create' ? 'Từ chối' : 'Từ chối thay đổi' }}
                                                </button>
                                            </form>
                                            <form action="${notification.data.actions.view_details}" method="GET"
                                                class="view-details-form" style="display:inline;">
                                                <input type="hidden" name="notification_id"
                                                    value="${notification.id}">
                                                <button type="submit" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($notification->type === 'category_approval_response')
                                        <div class="d-flex gap-2">
                                            <a href="{{ $notification->data['actions']['view_details'] }}"
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
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                            @if (\App\Models\Notification::userOrSystem(Auth::id())->where('is_read', false)->count() === 0)
                                <div class="notification-empty">
                                    <i class="fas fa-bell-slash fa-2x mb-2 text-gray-400"></i>
                                    <p>Không có thông báo nào</p>
                                </div>
                            @endif
                        </div>
                        <div class="text-center py-3 footer-bg">
                            <div class="overlay"></div>
                            <a class="btn btn-sm custom-btn" style="color: white;">
                                Được thiết kế bởi TG VN
                            </a>
                        </div>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const notificationList = document.getElementById('notification-list');
                        const notificationCount = document.getElementById('notification-count');
                        const countSpan = notificationCount.querySelector('.count');

                        function fetchNotifications() {
                            fetch('/api/notifications', {
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Accept': 'application/json',
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    countSpan.textContent = data.count;
                                    notificationCount.style.display = data.count > 0 ? 'block' : 'none';
                                    notificationList.innerHTML = '';

                                    if (data.count === 0) {
                                        notificationList.innerHTML = `
                                            <div class="text-center p-3">Không có thông báo nào</div>
                                        `;
                                    } else {
                                        data.notifications.forEach(notification => {
                                            const itemHtml = `
                                                <div class="notification-item p-3 border-bottom ${notification.is_read ? 'bg-light' : ''}">
                                                    <h6 class="mb-1">${notification.title}</h6>
                                                    <p class="mb-2 fs-13">${notification.content}</p>
                                                    <div class="d-flex gap-2">
                                                        ${getActionButtons(notification)}
                                                    </div>
                                                </div>
                                            `;
                                            notificationList.innerHTML += itemHtml;
                                        });
                                    }
                                })
                                .catch(error => console.error('Error fetching notifications:', error));
                        }

                        function getActionButtons(notification) {
                            switch (notification.type) {
                                case 'order_cancel':
                                    return `
                                            <form action="${notification.data.actions.cancel_request}" method="POST" style="display:inline;">
                                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                <input type="hidden" name="notification_id" value="${notification.id}">
                                                <button type="submit" class="btn btn-sm btn-danger">Hủy yêu cầu</button>
                                            </form>
                                            <form action="${notification.data.actions.accept_request}" method="POST" style="display:inline;">
                                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                <input type="hidden" name="notification_id" value="${notification.id}">
                                                <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                                            </form>
                                            <a href="${notification.data.actions.view_details}?notification_id=${notification.id}" class="btn btn-sm btn-info">Xem chi tiết</a>
                                        `;
                                case 'refund_request':
                                    return `
                                            <a href="${notification.data.actions.view_details}?notification_id=${notification.id}" class="btn btn-sm btn-info">Xem trực tiếp</a>
                                        `;
                                case 'order_status_request':
                                    return `
                                            <form action="${notification.data.actions.cancel_request}" method="POST" style="display:inline;">
                                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                <input type="hidden" name="notification_id" value="${notification.id}">
                                                <button type="submit" class="btn btn-sm btn-danger">Hủy yêu cầu</button>
                                            </form>
                                            <form action="${notification.data.actions.accept_request}" method="POST" style="display:inline;">
                                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                <input type="hidden" name="notification_id" value="${notification.id}">
                                                <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                                            </form>
                                            <a href="${notification.data.actions.view_details}?notification_id=${notification.id}" class="btn btn-sm btn-info">Xem chi tiết</a>
                                        `;
                                case 'return_request':
                                    return `
                                            <form action="${notification.data.actions.accept_request}" method="POST" style="display:inline;">
                                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                <input type="hidden" name="notification_id" value="${notification.id}">
                                                <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                                            </form>
                                            <form action="${notification.data.actions.cancel_request}" method="POST" style="display:inline;">
                                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                <input type="hidden" name="notification_id" value="${notification.id}">
                                                <button type="submit" class="btn btn-sm btn-danger">Hủy yêu cầu</button>
                                            </form>
                                            <a href="${notification.data.actions.view_details}?notification_id=${notification.id}" class="btn btn-sm btn-info">Xem chi tiết</a>
                                        `;
                                case 'import_response':
                                    return `
                                            <div class="d-flex gap-2">
                                                <a href="${notification.data.actions.view_details}" 
                                                class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                                </a>
                                                <form action="${notification.data.actions.acknowledge}" method="POST" style="display:inline;">
                                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                    <input type="hidden" name="_method" value="POST">
                                                    <input type="hidden" name="notification_id" value="${notification.id}">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check me-1"></i>Đã xem
                                                    </button>
                                                </form>
                                            </div>
                                        `;
                                case 'product_pending_create':
                                    return `
                                            <form action="${notification.data.actions.approve_request}" method="POST" style="display:inline;">
                                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                <input type="hidden" name="_method" value="PUT">
                                                <input type="hidden" name="notification_id" value="${notification.id}">
                                                <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                                            </form>
                                            <form action="${notification.data.actions.reject_request}" method="POST" style="display:inline;">
                                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="notification_id" value="${notification.id}">
                                                <button type="submit" class="btn btn-sm btn-danger">Hủy yêu cầu</button>
                                            </form>
                                            <a href="${notification.data.actions.view_details}?notification_id=${notification.id}" class="btn btn-sm btn-info">Xem chi tiết</a>
                                        `;
                                case 'product_pending_update':
                                    return `
                                            <form action="${notification.data.actions.approve_request}" method="POST" style="display:inline;">
                                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                <input type="hidden" name="_method" value="PUT">
                                                <input type="hidden" name="notification_id" value="${notification.id}">
                                                <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                                            </form>
                                            <form action="${notification.data.actions.reject_request}" method="POST" style="display:inline;">
                                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="notification_id" value="${notification.id}">
                                                <button type="submit" class="btn btn-sm btn-danger">Hủy yêu cầu</button>
                                            </form>
                                            <a href="${notification.data.actions.view_details}?notification_id=${notification.id}" class="btn btn-sm btn-info">Xem chi tiết</a>
                                        `;
                                case 'import_confirmation':
                                    return `
                                            <div class="d-flex gap-2">
                                                <a href="${notification.data.actions.view_details}" 
                                                class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                                </a>
                                                <form action="${notification.data.actions.confirm}" method="POST" style="display:inline;">
                                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                    <input type="hidden" name="_method" value="POST">
                                                    <input type="hidden" name="notification_id" value="${notification.id}">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check me-1"></i>Xác nhận
                                                    </button>
                                                </form>
                                                <form action="${notification.data.actions.cancel}" method="POST" style="display:inline;">
                                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                    <input type="hidden" name="_method" value="POST">
                                                    <input type="hidden" name="notification_id" value="${notification.id}">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-times me-1"></i>Từ chối
                                                    </button>
                                                </form>
                                            </div>
                                        `;

                                case 'product_approval_response':
                                    return `
                                            <div class="d-flex gap-2">
                                                <a href="${notification.data.actions.view_details}" 
                                                class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                                </a>
                                                <form action="${notification.data.actions.acknowledge}" method="POST" style="display:inline;">
                                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                    <input type="hidden" name="_method" value="POST">
                                                    <input type="hidden" name="notification_id" value="${notification.id}">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check me-1"></i>Đã xem
                                                    </button>
                                                </form>
                                            </div>
                                        `;
                                case 'import_pending':
                                    return `
                                            <form action="${notification.data.actions.confirm_request}" method="POST" style="display:inline;">
                                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                <input type="hidden" name="_method" value="PATCH">
                                                <input type="hidden" name="notification_id" value="${notification.id}">
                                                <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                                            </form>
                                            <form action="${notification.data.actions.reject_request}" method="POST" style="display:inline;">
                                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                <input type="hidden" name="_method" value="PATCH">
                                                <input type="hidden" name="notification_id" value="${notification.id}">
                                                <button type="submit" class="btn btn-sm btn-danger">Hủy yêu cầu</button>
                                            </form>
                                        `;
                                case 'category_pending_create':
                                    return `
                                            <div class="d-flex gap-2">
                                                <form action="${notification.data.actions.approve_request}" method="POST" class="notification-form" style="display:inline;">
                                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                    <input type="hidden" name="notification_id" value="${notification.id}">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check me-1"></i>Phê duyệt
                                                    </button>
                                                </form>
                                                <form action="${notification.data.actions.reject_request}" method="POST" class="notification-form" style="display:inline;">
                                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                    <input type="hidden" name="notification_id" value="${notification.id}">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-times me-1"></i>Từ chối
                                                    </button>
                                                </form>
                                                <a href="${notification.data.actions.view_details}" class="btn btn-sm btn-info" 
                                                data-notification-id="${notification.id}">
                                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                                </a>
                                            </div>
                                        `;

                                case 'category_pending_update':
                                    return `
                                            <div class="d-flex gap-2">
                                                <form action="${notification.data.actions.approve_request}" method="POST" class="notification-form" style="display:inline;">
                                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                    <input type="hidden name="notification_id" value="${notification.id}">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check me-1"></i>Chấp nhận thay đổi
                                                    </button>
                                                </form>
                                                <form action="${notification.data.actions.reject_request}" method="POST" class="notification-form" style="display:inline;">
                                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                    <input type="hidden" name="notification_id" value="${notification.id}">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-times me-1"></i>Từ chối thay đổi
                                                    </button>
                                                </form>
                                                <a href="${notification.data.actions.view_details}" class="btn btn-sm btn-info" 
                                                data-notification-id="${notification.id}">
                                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                                </a>
                                            </div>
                                        `;

                                case 'category_approval_response':
                                    return `
                                            <div class="d-flex gap-2">
                                                <a href="${notification.data.actions.view_details}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                                </a>
                                                <form action="${notification.data.actions.acknowledge}" method="POST" style="display:inline;">
                                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                    <input type="hidden" name="_method" value="POST">
                                                    <input type="hidden" name="notification_id" value="${notification.id}">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check me-1"></i>Đã xem
                                                    </button>
                                                </form>
                                            </div>
                                        `;

                                default:
                                    return '';
                            }
                        }
                        fetchNotifications();
                        setInterval(fetchNotifications, 3000);
                    });
                </script>

                <div class="topbar-item d-none d-md-flex">
                    <button type="button" class="topbar-button" id="theme-settings-btn" data-bs-toggle="offcanvas"
                        data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas">
                        <iconify-icon icon="solar:settings-broken" class="fs-24 align-middle"></iconify-icon>
                    </button>
                </div>


                <div class="dropdown topbar-item">
                    <a type="button" class="topbar-button" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center position-relative">
                            <img class="rounded-circle" width="42" height="42"
                                src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : asset('admin/images/users/dummy-avatar.png') }}"
                                style="object-fit: cover;"
                                onerror="this.onerror=null; this.src='{{ asset('storage/avatars/default.jpg') }}';">

                            <span
                                class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white"
                                style="width: 12px; height: 12px;"></span>
                        </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-end">

                        <h6 class="dropdown-header">Xin chào <span
                                class="text-black fw-bold">{{ auth()->user()->fullname }}</span> !</h6>

                        {{-- <a class="dropdown-item" href="apps-chat.html">
                            <i class="bx bx-message-dots text-muted fs-18 align-middle me-1"></i><span
                                class="align-middle">Tin nhắn</span>
                        </a> --}}

                        {{-- <a class="dropdown-item" href="pages-pricing.html">
                            <i class="bx bx-wallet text-muted fs-18 align-middle me-1"></i><span
                                class="align-middle">Pricing</span>
                        </a>
                        <a class="dropdown-item" href="pages-faqs.html">
                            <i class="bx bx-help-circle text-muted fs-18 align-middle me-1"></i><span
                                class="align-middle">Help</span>
                        </a>
                        <a class="dropdown-item" href="auth-lock-screen.html">
                            <i class="bx bx-lock text-muted fs-18 align-middle me-1"></i><span
                                class="align-middle">Lock screen</span>
                        </a>

                        <div class="dropdown-divider my-1"></div> --}}

                        <a class="dropdown-item text-danger" href="auth-signin.html">
                            <i class="bx bx-log-out fs-18 align-middle me-1"></i><span class="align-middle">Đăng
                                xuất</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
