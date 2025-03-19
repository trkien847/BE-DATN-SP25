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
                        <span
                            class="position-absolute topbar-badge fs-10 translate-middle badge bg-danger rounded-pill">3<span
                                class="visually-hidden">unread messages</span></span>
                    </button>

                    <div class="dropdown-menu py-0 dropdown-lg dropdown-menu-end"
                        aria-labelledby="page-header-notifications-dropdown">
                        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 fs-16 fw-semibold"> Thông báo</h6>
                                </div>
                                <div class="col-auto">
                                    <a href="javascript: void(0);" class="text-dark text-decoration-underline">
                                        <small>Clear All</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div data-simplebar style="max-height: 280px;">
                            <!-- Item -->
                            <a href="javascript:void(0);" class="dropdown-item py-3 border-bottom text-wrap">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('admin/images/users/avatar-1.jpg') }}"
                                            class="img-fluid me-2 avatar-sm rounded-circle" alt="avatar-1" />
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0"><span class="fw-medium">Josephine Thompson
                                            </span>commented on admin panel <span>" Wow 😍! this admin looks
                                                good and awesome design"</span></p>
                                    </div>
                                </div>
                            </a>
                            
                        </div>
                        <div class="text-center py-3">
                            <a href="javascript:void(0);" class="btn btn-primary btn-sm">View All Notification
                                <i class="bx bx-right-arrow-alt ms-1"></i></a>
                        </div>
                    </div>
                </div>
<!-- Nếu bạn load file app.js -->

@if(auth()->check() && auth()->user()->role_id == 3)
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let lastChecked = new Date();

            function checkNotifications() {
                fetch("{{ route('notifications.check') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ last_checked: lastChecked.toISOString() }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.imports && data.imports.length > 0) {
                        data.imports.forEach(importItem => {
                            Swal.fire({
                                icon: 'info',
                                title: 'Thông báo',
                                html: `${importItem.message} (Ngày nhập: ${importItem.imported_at} bởi ${importItem.imported_by})<br>
                                       <form action="{{ url('products/import/confirm') }}/${importItem.import_id}" method="POST" style="display: inline;">
                                           @csrf
                                           @method('PATCH')
                                           <button type="submit" class="btn btn-sm btn-success mt-2">Xác nhận</button>
                                       </form>
                                       <form action="{{ url('products/import/reject') }}/${importItem.import_id}" method="POST" style="display: inline;">
                                           @csrf
                                           @method('PATCH')
                                           <button type="submit" class="btn btn-sm btn-danger mt-2">Không xác nhận</button>
                                       </form>`,
                                showConfirmButton: false,
                            });
                        });
                        lastChecked = new Date();
                    }
                })
                .catch(error => console.log('Error:', error))
                .finally(() => {
                    setTimeout(checkNotifications, 3000); // Kiểm tra lại sau 5 giây
                });
            }

            checkNotifications();
        });
    </script>
@endif    <!-- Theme Setting -->
                <div class="topbar-item d-none d-md-flex">
                    <button type="button" class="topbar-button" id="theme-settings-btn" data-bs-toggle="offcanvas"
                        data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas">
                        <iconify-icon icon="solar:settings-broken" class="fs-24 align-middle"></iconify-icon>
                    </button>
                </div>

                <!-- User -->
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
                            <!-- Chấm xanh hoạt động -->
                            <span class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white"
                                  style="width: 12px; height: 12px;"></span>
                        </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
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