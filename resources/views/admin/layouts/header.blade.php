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
</style>
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
                        id="page-header-notifications-dropdown" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <iconify-icon icon="solar:bell-bing-broken" class="fs-24 align-middle"></iconify-icon>
                        <span id="notification-count"
                            class="position-absolute topbar-badge fs-10 translate-middle badge bg-danger rounded-pill">
                            <span class="count">{{ \App\Models\Notification::userOrSystem(Auth::id())->where('is_read', false)->count() }}</span>
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    </button>

                    <div class="dropdown-menu py-0 dropdown-lg dropdown-menu-end"
                        aria-labelledby="page-header-notifications-dropdown">
                        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 fs-16 fw-semibold">Thông báo</h6>
                                </div>
                            </div>
                        </div>
                        <div data-simplebar style="max-height: 280px;" id="notification-list">
                            @foreach(\App\Models\Notification::userOrSystem(Auth::id())->where('is_read', 0)->latest()->limit(10)->get() as $notification)
                            <div class="notification-item p-3 border-bottom {{ $notification->is_read ? 'bg-light' : '' }}">
                                <h6 class="mb-1">{{ $notification->title }}</h6>
                                <p class="mb-2 fs-13">{{ $notification->content }}</p>
                                @if($notification->type === 'order_cancel')
                                <div class="d-flex gap-2">
                                    <form action="{{ $notification->data['actions']['cancel_request'] }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                        <button type="submit" class="btn btn-sm btn-danger">Hủy yêu cầu</button>
                                    </form>
                                    <form action="{{ $notification->data['actions']['accept_request'] }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                        <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                                    </form>
                                    <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}" class="btn btn-sm btn-info">Xem chi tiết</a>
                                </div>
                                @elseif($notification->type === 'refund_request')
                                <div class="d-flex gap-2">
                                    <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}" class="btn btn-sm btn-info">Xem trực tiếp</a>
                                </div>
                                @elseif($notification->type === 'order_status_request')
                                <div class="d-flex gap-2">
                                    <form action="{{ $notification->data['actions']['cancel_request'] }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                        <button type="submit" class="btn btn-sm btn-danger">Hủy yêu cầu</button>
                                    </form>
                                    <form action="{{ $notification->data['actions']['accept_request'] }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                        <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                                    </form>
                                    <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}" class="btn btn-sm btn-info">Xem chi tiết</a>
                                </div>
                                @elseif($notification->type === 'return_request')
                                <div class="d-flex gap-2">
                                    <form action="{{ $notification->data['actions']['accept_request'] }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                        <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                                    </form>
                                    <form action="{{ $notification->data['actions']['cancel_request'] }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                        <button type="submit" class="btn btn-sm btn-danger">Không chấp nhận</button>
                                    </form>
                                    <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}" class="btn btn-sm btn-info">Xem chi tiết</a>
                                </div>
                                @elseif($notification->type === 'product_pending_create')
                                <div class="d-flex gap-2">
                                    <form action="{{ $notification->data['actions']['approve_request'] }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                        <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                                    </form>
                                    <form action="{{ $notification->data['actions']['reject_request'] }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                        <button type="submit" class="btn btn-sm btn-danger">Không chấp nhận</button>
                                    </form>
                                    <a href="{{ $notification->data['actions']['view_details'] }}" class="btn btn-sm btn-info">Xem chi tiết</a>
                                </div>
                                @endif
                            </div>
                            @endforeach
                            @if(\App\Models\Notification::userOrSystem(Auth::id())->where('is_read', false)->count() === 0)
                            <div class="text-center p-3">Không có thông báo nào</div>
                            @endif
                        </div>
                        <div class="text-center py-3">
                            <a href="javascript:void(0);" class="btn btn-primary btn-sm">
                                Được thiết kế bởi TG XX
                            </a>
                        </div>
                    </div>
                </div>




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
                            @php
                            $currentUser = Auth::user(); // Lấy thông tin người dùng hiện tại
                            @endphp
                            <img class="rounded-circle" width="42" height="42"
                                src="{{ $currentUser->avatar ? asset('storage/' . $currentUser->avatar) : asset('storage/avatars/default.jpg') }}"
                                alt="{{ $currentUser->avatar ? 'Ảnh đại diện' : 'Ảnh mặc định' }}"
                                style="object-fit: cover;"
                                onerror="this.onerror=null; this.src='{{ asset('storage/avatars/default.jpg') }}';">

                            <span class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white"
                                style="width: 12px; height: 12px;"></span>
                        </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-end">

                        <h6 class="dropdown-header">Xin chào <span class="text-black fw-bold">{{ $currentUser->fullname }}</span> !</h6>

                        <a class="dropdown-item" href="apps-chat.html">
                            <i class="bx bx-message-dots text-muted fs-18 align-middle me-1"></i><span
                                class="align-middle">Messages</span>
                        </a>

                        <a class="dropdown-item" href="pages-pricing.html">
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

                        <div class="dropdown-divider my-1"></div>

                        <a class="dropdown-item text-danger" href="auth-signin.html">
                            <i class="bx bx-log-out fs-18 align-middle me-1"></i><span
                                class="align-middle">Đăng xuất</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>