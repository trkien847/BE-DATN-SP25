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
                <a class="nav-link" href="index.html">
                    <span class="nav-icon">
                    <iconify-icon icon="line-md:home-md" width="24" height="24"></iconify-icon>
                    </span>
                    <span class="nav-text"> Thống Kê </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('categories.list') }}">
                    <span class="nav-icon">
                    <iconify-icon icon="line-md:folder-settings-filled" width="24" height="24"></iconify-icon>
                    </span>
                    <span class="nav-text"> Danh Mục </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('products.list')}}">
                    <span class="nav-icon">
                    <iconify-icon icon="simple-icons:buffer" width="24" height="24"></iconify-icon>
                    </span>
                    <span class="nav-text"> Sản Phẩm  </span>
                </a>
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
                <a class="nav-link" href="{{route('attributes.list')}}">
                    <span class="nav-icon">
                    <iconify-icon icon="line-md:menu" width="24" height="24"></iconify-icon>
                    </span>                  
                    <span class="nav-text"> Biến thể </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('products.import')}}">
                    <span class="nav-icon">
                    <iconify-icon icon="mdi:application-edit-outline" width="24" height="24">
                    </span>                  
                    <span class="nav-text">Nhập </span>
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
        </ul>
    </div>
</div>
