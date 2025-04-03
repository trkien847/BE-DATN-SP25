<style>
    /* Định dạng cơ bản cho dropdown */
    .dropdown {
        position: relative;
    }

    .dropdown-menu {
        display: none;
        /* Ẩn menu con mặc định */
        position: absolute;
        left: 100%;
        /* Hiển thị menu con bên phải */
        top: 0;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        min-width: 200px;
        padding: 10px 0;
        z-index: 1000;
        opacity: 0;
        transform: translateY(-10px);
        /* Hiệu ứng trượt xuống */
        transition: all 0.3s ease;
        /* Thời gian chuyển động */
    }

    /* Hiển thị menu con khi hover */
    .dropdown:hover .dropdown-menu {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    /* Định dạng các item trong dropdown */
    .dropdown-item {
        display: flex;
        align-items: center;
        padding: 8px 15px;
        color: #333;
        text-decoration: none;
        transition: background-color 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #f5f5f5;
        color: #007bff;
        /* Màu khi hover */
    }

    /* Điều chỉnh icon và text */
    .nav-icon {
        margin-right: 10px;
    }

    .nav-text {
        font-size: 14px;
    }

    /* Định dạng nút cha (Sản Phẩm) */
    .dropdown-toggle {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    /* Thêm mũi tên nhỏ cho dropdown */
    .dropdown-toggle::after {
        content: '\25B6';
        /* Mũi tên sang phải */
        margin-left: 5px;
        font-size: 10px;
        transition: transform 0.3s ease;
    }

    .dropdown:hover .dropdown-toggle::after {
        transform: rotate(90deg);
        /* Xoay mũi tên khi hover */
    }

    .dropdown-menu.active {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }
</style>
<div class="main-nav">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        {{-- <a href="index.html" class="logo-dark">
            <img src="{{ asset('admin/images/logo-sm.png') }}" class="logo-sm" alt="logo sm">
        <img src="{{ asset('admin/images/logo-dark.png') }}" class="logo-lg" alt="logo dark">
        </a>

        <a href="index.html" class="logo-light">
            <img src="{{ asset('admin/images/logo-sm.png') }}" class="logo-sm" alt="logo sm">
            <img src="{{ asset('admin/images/logo-light.png') }}" class="logo-lg" alt="logo light">
        </a> --}}
        <div class="col-12 text-start">
            <iconify-icon icon="solar:hearts-bold-duotone" class="fs-18 align-middle text-danger">
            </iconify-icon>
            <a href="" class="fw-bold footer-text" target="_blank">BeePharmacy</a>
        </div>
    </div>

    <!-- Menu Toggle Button (sm-hover) -->
    <button type="button" class="button-sm-hover" aria-label="Show Full Sidebar">
        <iconify-icon icon="solar:hamburger-menu-broken" class="button-sm-hover-icon"></iconify-icon>
    </button>

    <div class="scrollbar" data-simplebar>

        <ul class="navbar-nav" id="navbar-nav">

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard.index') }}">
                    <span class="nav-icon">
                        <iconify-icon icon="line-md:home-md" width="24" height="24"></iconify-icon>
                    </span>
                    <span class="nav-text"> Thống Kê </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('categories.list') }}">
                    <span class="nav-icon">
                        <iconify-icon icon="line-md:folder-settings-filled" width="24"
                            height="24"></iconify-icon>
                    </span>
                    <span class="nav-text"> Danh Mục </span>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="" role="button" aria-expanded="false">
                    <span class="nav-icon">
                        <iconify-icon icon="simple-icons:buffer" width="24" height="24"></iconify-icon>
                    </span>
                    <span class="nav-text"> Danh mục sản phẩm </span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="{{ route('attributes.list') }}">
                            <span class="nav-icon">
                                <iconify-icon icon="line-md:menu" width="24" height="24"></iconify-icon>
                            </span>
                            <span class="nav-text"> Biến thể </span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('products.list') }}">
                            <span class="nav-icon">
                                <iconify-icon icon="simple-icons:buffer" width="24" height="24"></iconify-icon>
                            </span>
                            <span class="nav-text"> Sản Phẩm </span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('products.import') }}">
                            <span class="nav-icon">
                                <iconify-icon icon="mdi:application-edit-outline" width="24"
                                    height="24"></iconify-icon>
                            </span>
                            <span class="nav-text"> Nhập </span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('order.list') }}">
                    <span class="nav-icon">
                        <iconify-icon icon="cuida:document-text-outline" width="24" height="24"></iconify-icon>
                    </span>
                    <span class="nav-text"> Quản Lý Đơn Hàng </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('brands.list') }}">
                    <span class="nav-icon">
                        <iconify-icon icon="gridicons:multiple-users" width="24" height="24"></iconify-icon>
                    </span>
                    <span class="nav-text"> Thương Hiệu </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('coupons.list') }}">
                    <span class="nav-icon">
                        <iconify-icon icon="mynaui:ticket-solid" width="24" height="24"></iconify-icon>
                    </span>
                    <span class="nav-text"> Mã Giảm Giá </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.users.list') }}">
                    <span class="nav-icon">
                        <iconify-icon icon="mdi:account-group-outline" width="24" height="24"></iconify-icon>
                    </span>
                    <span class="nav-text"> Quản Lý Người Dùng </span>
                </a>
            </li>
            @if (Auth::user()->role_id == 3)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('notifications.index') }}">
                        <span class="nav-icon">
                            <iconify-icon icon="mdi:bell-notification" width="24" height="24"></iconify-icon>
                        </span>
                        <span class="nav-text">
                            Thông báo
                            <span
                                class="badge bg-danger rounded-pill">{{ Auth::user()->unreadNotifications->count() }}</span>
                        </span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>

<script>
    document.querySelectorAll('.dropdown-toggle').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const menu = this.nextElementSibling;
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        });
    });
</script>
