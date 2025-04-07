<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from techzaa.in/rasket/admin/apps-invoices.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 14 Jan 2025 16:51:56 GMT -->

<head>
    <!-- Title Meta -->
    <meta charset="utf-8" />
    <title>BeePharmacy | Nhà thuốc cho bạn</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully responsive premium admin dashboard template" />
    <meta name="author" content="Techzaa" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('admin/images/favicon.ico') }}">

    <!-- Vendor css (Require in all Page) -->
    <link href="{{ asset('admin/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Icons css (Require in all Page) -->
    <link href="{{ asset('admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css (Require in all Page) -->
    <link href="{{ asset('admin/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('admin/js/components/table-gridjs.js') }}"></script>

    <!-- Theme Config js (Require in all Page) -->
    <script src="{{ asset('admin/js/config.min.js') }}"></script>
    @stack('styles')
    <style>
        #notification-container {
            max-height: 280px;
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>
</head>


<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <!-- ========== Topbar Start ========== -->
        @include('admin.layouts.header')

        <!-- Right Sidebar (Theme Settings) -->
        {{-- <div>
            <div class="offcanvas offcanvas-end border-0" tabindex="-1" id="theme-settings-offcanvas">
                <div class="d-flex align-items-center bg-primary p-3 offcanvas-header">
                    <h5 class="text-white m-0">Theme Settings</h5>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>

                <div class="offcanvas-body p-0">
                    <div data-simplebar class="h-100">
                        <div class="p-3 settings-bar">

                            <div>
                                <h5 class="mb-3 font-16 fw-semibold">Color Scheme</h5>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-bs-theme"
                                        id="layout-color-light" value="light">
                                    <label class="form-check-label" for="layout-color-light">Light</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-bs-theme"
                                        id="layout-color-dark" value="dark">
                                    <label class="form-check-label" for="layout-color-dark">Dark</label>
                                </div>
                            </div>

                            <div>
                                <h5 class="my-3 font-16 fw-semibold">Topbar Color</h5>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-topbar-color"
                                        id="topbar-color-light" value="light">
                                    <label class="form-check-label" for="topbar-color-light">Light</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-topbar-color"
                                        id="topbar-color-dark" value="dark">
                                    <label class="form-check-label" for="topbar-color-dark">Dark</label>
                                </div>
                            </div>


                            <div>
                                <h5 class="my-3 font-16 fw-semibold">Menu Color</h5>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-color"
                                        id="leftbar-color-light" value="light">
                                    <label class="form-check-label" for="leftbar-color-light">
                                        Light
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-color"
                                        id="leftbar-color-dark" value="dark">
                                    <label class="form-check-label" for="leftbar-color-dark">
                                        Dark
                                    </label>
                                </div>
                            </div>

                            <div>
                                <h5 class="my-3 font-16 fw-semibold">Sidebar Size</h5>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-size"
                                        id="leftbar-size-default" value="default">
                                    <label class="form-check-label" for="leftbar-size-default">
                                        Default
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-size"
                                        id="leftbar-size-small" value="condensed">
                                    <label class="form-check-label" for="leftbar-size-small">
                                        Condensed
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-size"
                                        id="leftbar-hidden" value="hidden">
                                    <label class="form-check-label" for="leftbar-hidden">
                                        Hidden
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-size"
                                        id="leftbar-size-small-hover-active" value="sm-hover-active">
                                    <label class="form-check-label" for="leftbar-size-small-hover-active">
                                        Small Hover Active
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-size"
                                        id="leftbar-size-small-hover" value="sm-hover">
                                    <label class="form-check-label" for="leftbar-size-small-hover">
                                        Small Hover
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="offcanvas-footer border-top p-3 text-center">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-danger w-100" id="reset-layout">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- ========== Topbar End ========== -->

        <!-- ========== App Menu Start ========== -->
        @include('admin.layouts.side-bar')
        <!-- ========== App Menu End ========== -->

        <!-- ==================================================== -->
        <!-- Start right Content here -->
        <!-- ==================================================== -->
        <div class="page-content">

            <!-- Start Container Fluid -->
            <div class="container-fluid">

                <!-- Start here.... -->
                @yield('content')
                <!-- end row -->

            </div>
            <!-- End Container Fluid -->

            <!-- ========== Footer Start ========== -->
            @include('admin.layouts.footer')
            <!-- ========== Footer End ========== -->

        </div>
        <!-- ==================================================== -->
        <!-- End Page Content -->
        <!-- ==================================================== -->

    </div>
    <!-- END Wrapper -->
    <!-- Vendor Javascript (Require in all Page) -->
    <script src="{{ asset('admin/js/vendor.js') }}"></script>

    <!-- App Javascript (Require in all Page) -->
    <script src="{{ asset('admin/js/app.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="https://unpkg.com/simplebar@latest/dist/simplebar.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.pusherInitialized) return;
            window.pusherInitialized = true;

            if (window.pusherInstance) {
                window.pusherInstance.disconnect();
                window.pusherInstance = null;
            }

            Pusher.logToConsole = true;

            var pusher = new Pusher("c59e8a8c8980e404fb73", {
                cluster: "ap1",
                forceTLS: true,
                authEndpoint: "/broadcasting/auth",
                auth: {
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                }
            });
            window.pusherInstance = pusher;

            var channel = pusher.subscribe("private-notifications");

            channel.bind('pusher:subscription_succeeded', function() {
                console.log('Đã subscribe thành công vào private-notifications');
            });

            channel.bind('pusher:subscription_error', function(error) {
                console.error('Lỗi subscribe:', error);
            });

            function handleNotification(event) {
                console.log("Nhận được thông báo real-time:", event);

                // Fetch lại dữ liệu thông báo mới sau khi nhận được tín hiệu từ Pusher
                fetchNotifications();
            }

            // Bind sự kiện từ Pusher
            channel.bind("Illuminate\\Notifications\\Events\\BroadcastNotificationCreated", handleNotification);

            // Hàm AJAX để fetch dữ liệu thông báo
            function fetchNotifications() {
                fetch('/notifications/fetch')
                    .then(response => response.json())
                    .then(data => {
                        console.log('Dữ liệu thông báo mới:', data);

                        var notificationContainer = document.getElementById("notification-container");
                        if (notificationContainer) {
                            notificationContainer.innerHTML = ''; // Xóa thông báo cũ

                            if (data.notifications && data.notifications.length > 0) {
                                data.notifications.forEach(notification => {
                                    var notificationElement = document.createElement("a");
                                    // Sử dụng URL từ notification nếu có
                                    notificationElement.href = notification.url ||
                                    'javascript:void(0);';
                                    notificationElement.classList.add("dropdown-item", "py-3",
                                        "border-bottom", "text-wrap");

                                    // Thêm sự kiện click để xử lý chuyển hướng
                                    notificationElement.addEventListener('click', function(e) {
                                        if (notification.url) {
                                            e.preventDefault();
                                            // Đánh dấu thông báo là đã đọc trước khi chuyển hướng
                                            fetch(`/notifications/${notification.id}/mark-as-read`, {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': document
                                                            .querySelector(
                                                                'meta[name="csrf-token"]')
                                                            .content,
                                                        'Content-Type': 'application/json',
                                                    }
                                                })
                                                .then(() => {
                                                    // Sau khi đánh dấu đã đọc, chuyển hướng đến URL
                                                    window.location.href = notification.url;
                                                });
                                        }
                                    });

                                    notificationElement.innerHTML = `
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <img src="${notification.avatar || '{{ asset('admin/images/users/dummy-avatar.jpg') }}'}" 
                                         class="img-fluid me-2 avatar-sm rounded-circle" alt="user-avatar" />
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0">
                                        <span class="fw-medium">${notification.created_by}</span>: 
                                        ${notification.message}
                                    </p>
                                    <small class="text-muted">
                                        ${notification.created_at}
                                        ${!notification.read_at ? '<span class="badge bg-danger ms-1">Mới</span>' : ''}
                                    </small>
                                </div>
                            </div>
                        `;
                                    notificationContainer.prepend(notificationElement);
                                });
                            } else {
                                notificationContainer.innerHTML =
                                    '<p class="text-center py-3">Không có thông báo mới</p>';
                            }

                            // Cuộn lên đầu
                            notificationContainer.scrollTop = 0;

                            updateBadgeCount();
                        }
                    })
                    .catch(error => console.error('Lỗi khi fetch dữ liệu:', error));
            }

            function updateBadgeCount() {
                fetch('/notifications/count')
                    .then(response => response.json())
                    .then(data => {
                        const count = data.count;

                        // Update header badge
                        const headerBadge = document.getElementById("notification-badge");
                        if (headerBadge) {
                            headerBadge.textContent = count;
                            headerBadge.style.display = count > 0 ? "inline-block" : "none";
                        }

                        // Update sidebar badge
                        const sidebarBadge = document.querySelector('.nav-text .badge');
                        if (sidebarBadge) {
                            sidebarBadge.textContent = count;
                            sidebarBadge.style.display = count > 0 ? "inline-block" : "none";
                        }
                    })
                    .catch(error => console.error('Lỗi khi cập nhật số lượng thông báo:', error));
            }


            // Cập nhật số lượng thông báo ban đầu
            updateBadgeCount();
        });
        document.getElementById('clear-all-notifications').addEventListener('click', function() {
            fetch('{{ route('notifications.markAllAsRead') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Hide notification badge
                        document.getElementById('notification-badge').style.display = 'none';
                        // Clear notifications container
                        document.getElementById('notification-container').innerHTML =
                            '<p class="text-center py-3">No new notifications</p>';
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
    @stack('scripts')

</body>



<!-- Mirrored from techzaa.in/rasket/admin/apps-invoices.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 14 Jan 2025 16:51:56 GMT -->

</html>
