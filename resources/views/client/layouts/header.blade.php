<header class="ltn__header-area ltn__header-3">
    <!-- ltn__header-top-area start -->
    <!-- ltn__header-top-area end -->
    <!-- ltn__header-middle-area start -->
    <div class="ltn__header-middle-area">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="site-logo">
                        <a href="{{ route('index') }}"><img src="{{ asset('client/img/logo.png') }}" alt="Logo"></a>
                    </div>
                </div>
                <div class="col header-contact-serarch-column d-none d-lg-block">
                    <div class="header-contact-search">
                        <!-- header-feature-item -->
                        <div class="header-feature-item">
                            <div class="header-feature-icon">
                                <i class="icon-call"></i>
                            </div>
                            <div class="header-feature-info">
                                <h6>Tư vấn:</h6>
                                <p><a href="tel:0123456789">+0123-456-789</a></p>
                            </div>
                        </div>
                        <!-- header-search-2 -->
                        <div class="header-search-2">
                            <form id="#123" method="get" action="#">
                                <input type="text" name="search" value="" placeholder="Tìm kiếm ở đây..." />
                                <button type="submit">
                                    <span><i class="icon-search"></i></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <!-- header-options -->
                    <div class="ltn__header-options">
                        <ul>
                            {{-- <li class="d-none">
                                <!-- ltn__currency-menu -->
                                <div class="ltn__drop-menu ltn__currency-menu">
                                    <ul>
                                        <li><a href="#" class="dropdown-toggle"><span
                                                    class="active-currency">USD</span></a>
                                            <ul>
                                                <li><a href="login.html">USD - US Dollar</a></li>
                                                <li><a href="wishlist.html">CAD - Canada Dollar</a></li>
                                                <li><a href="register.html">EUR - Euro</a></li>
                                                <li><a href="account.html">GBP - British Pound</a></li>
                                                <li><a href="wishlist.html">INR - Indian Rupee</a></li>
                                                <li><a href="wishlist.html">BDT - Bangladesh Taka</a></li>
                                                <li><a href="wishlist.html">JPY - Japan Yen</a></li>
                                                <li><a href="wishlist.html">AUD - Australian Dollar</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </li> --}}
                            <li class="d-lg-none">
                                <!-- header-search-1 -->
                                <div class="header-search-wrap">
                                    <div class="header-search-1">
                                        <div class="search-icon">
                                            <i class="icon-search  for-search-show"></i>
                                            <i class="icon-cancel  for-search-close"></i>
                                        </div>
                                    </div>
                                    <div class="header-search-1-form">
                                        <form id="#" method="get" action="#">
                                            <input type="text" name="search" value=""
                                                placeholder="Tìm kiếm ở đây..." />
                                            <button type="submit">
                                                <span><i class="icon-search"></i></span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                            <li class="d-none---">
                                <!-- user-menu -->
                                <div class="ltn__drop-menu user-menu">
                                    <ul>
                                        <li>
                                            <a href="#"><i class="icon-user"></i></a>
                                            <ul>
                                                @if (Auth::guest())
                                                    <li><a href="{{ route('login') }}">Đăng nhập</a></li>
                                                    <li><a href="{{ route('register') }}">Đăng ký</a></li>
                                                @endif
                                            
                                                @if (Auth::check())
                                                
                                                    <li><a href="{{ route('profile') }}">Tài khoản</a></li>
                                                    <li><a href="{{ route('logout') }}">Đăng xuất</a></li>
                                                @endif
                                            </ul>                                            
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            @if (Auth::check())
                                <li>
                                    <div class="mini-cart-icon mini-cart-icon-2">
                                        <a href="#ltn__utilize-cart-menu" class="ltn__utilize-toggle">
                                            <span class="mini-cart-icon">
                                                <i class="icon-shopping-cart"></i>
                                                <sup>{{ $carts->count() }}</sup>
                                            </span>
                                            <h6><span>Giỏ hàng</span> <span
                                                class="ltn__secondary-color" id="cart-subtotal">{{ number_format($subtotal, 0) }}đ</span>
                                            </h6>
                                        </a>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ltn__header-middle-area end -->
    <!-- header-bottom-area start -->
    <div
        class="header-bottom-area ltn__border-top ltn__header-sticky  ltn__sticky-bg-white--- ltn__sticky-bg-secondary ltn__secondary-bg section-bg-1 menu-color-white d-none d-lg-block">
        <div class="container">
            <div class="row">
                <div class="col header-menu-column justify-content-center">
                    <div class="sticky-logo">
                        <div class="site-logo">
                            <a href="{{ route('index') }}"><img src="{{ asset('client/img/logo-3.p') }}ng" alt="Logo"></a>
                        </div>
                    </div>
                    <div class="header-menu header-menu-2">
                        <nav>
                            <div class="ltn__main-menu">
                                <ul>
                                    <li><a href="{{ route('index') }}">Trang chủ</a>
                                    </li>
                                    {{-- <li class="menu-icon"><a href="#">About</a>
                                        <ul>
                                            <li><a href="about.html">About</a></li>
                                            <li><a href="service.html">Services</a></li>
                                            <li><a href="service-details.html">Service Details</a></li>
                                            <li><a href="portfolio.html">Gallery</a></li>
                                            <li><a href="portfolio-2.html">Gallery - 02</a></li>
                                            <li><a href="portfolio-details.html">Gallery Details</a></li>
                                            <li><a href="team.html">Team</a></li>
                                            <li><a href="team-details.html">Team Details</a></li>
                                            <li><a href="faq.html">FAQ</a></li>
                                            <li><a href="locations.html">Google Map Locations</a></li>
                                        </ul>
                                    </li> --}}
                                    <li><a href="{{ route('category.show') }}">Sản phẩm</a>
                                    <li><a href="{{ route('orderHistory') }}">Lịch sử mua hàng</a></li>
                                    {{-- <li class="menu-icon"><a href="#">Pages</a>
                                        <ul class="mega-menu">
                                            <li><a href="#">Inner Pages</a>
                                                <ul>
                                                    <li><a href="portfolio.html">Gallery</a></li>
                                                    <li><a href="portfolio-2.html">Gallery - 02</a></li>
                                                    <li><a href="portfolio-details.html">Gallery Details</a></li>
                                                    <li><a href="team.html">Team</a></li>
                                                    <li><a href="team-details.html">Team Details</a></li>
                                                    <li><a href="faq.html">FAQ</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="#">Inner Pages</a>
                                                <ul>
                                                    <li><a href="history.html">History</a></li>
                                                    <li><a href="contact.html">Appointment</a></li>
                                                    <li><a href="locations.html">Google Map Locations</a></li>
                                                    <li><a href="404.html">404</a></li>
                                                    <li><a href="contact.html">Contact</a></li>
                                                    <li><a href="coming-soon.html">Coming Soon</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="#">Shop Pages</a>
                                                <ul>
                                                    <li><a href="shop.html">Shop</a></li>
                                                    <li><a href="shop-left-sidebar.html">Shop Left sidebar</a></li>
                                                    <li><a href="shop-right-sidebar.html">Shop right sidebar</a></li>
                                                    <li><a href="shop-grid.html">Shop Grid</a></li>
                                                    <li><a href="product-details.html">Shop details </a></li>
                                                    <li><a href="cart.html">Cart</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="shop.html"><img
                                                        src="{{ asset('client/img/banner/m') }}enu-banner-1.png"
                                                        alt="#"></a>
                                            </li>
                                        </ul> --}}
                                    </li>
                                    <li><a href="contact.html">Liên hệ</a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- header-bottom-area end -->
</header>
