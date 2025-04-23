<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Quarter - Real Estate HTML Template</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Place favicon.png in the root directory -->
    <link rel="shortcut icon" href="{{ asset('client/img/favicon.png') }}" type="image/x-icon" />
    <!-- Font Icons css -->
    <link rel="stylesheet" href="{{ asset('client/css/font-icons.css') }}">
    <!-- plugins css -->
    <link rel="stylesheet" href="{{ asset('client/css/plugins.css') }}">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ asset('client/css/style.css') }}">
    <!-- Responsive css -->
    <link rel="stylesheet" href="{{ asset('client/css/responsive.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    @stack('css')
    <style>
         .video-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: black;
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            overflow: hidden;
            opacity: 1; 
            transition: opacity 1s ease; 
        }

        .video-overlay video {
            width: 100vw;
            height: 100vh;
            object-fit: cover;
            display: block;
            position: absolute;
            top: 0;
            left: 0;
        }

        .product-image {
            transition: transform 0.2s;
        }

        .product-image:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

    <!-- Add your site or application content here -->

    <!-- Body main wrapper start -->
    <div class="body-wrapper" id="main-content">

        <!-- HEADER AREA START (header-3) -->
        @include('client.layouts.header')
        <!-- HEADER AREA END -->

        @yield('content')

        <!-- FOOTER AREA START -->
        @include('client.layouts.footer')
        <!-- FOOTER AREA END -->
    </div>
    <!-- Body main wrapper end -->
    <div class="video-overlay" id="video-overlay">
        <video id="transition-video" preload="auto" muted>
            <source src="{{ asset('videos/ashe.mp4') }}" type="video/mp4">
        </video>
    </div>
    <!-- preloader area start -->
    <div class="preloader d-none" id="preloader">
        <div class="preloader-inner">
            <div class="spinner">
                <div class="dot1"></div>
                <div class="dot2"></div>
            </div>
        </div>
    </div>
    <!-- preloader area end -->

    <!-- All JS Plugins -->
    <script>
         document.addEventListener('DOMContentLoaded', function () {
            const productLinks = document.querySelectorAll('.product-link');
            const videoOverlay = document.getElementById('video-overlay');
            const transitionVideo = document.getElementById('transition-video');
            const mainContent = document.getElementById('main-content');

            productLinks.forEach(link => {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    const targetUrl = this.getAttribute('href');
                    console.log('Product link clicked, target URL:', targetUrl);

                    // Ẩn toàn bộ body
                    mainContent.classList.add('hidden-content');

                    // Hiển thị video overlay
                    videoOverlay.style.display = 'flex';
                    console.log('Video overlay set to display: flex');

                    // Phát video
                    transitionVideo.play().then(() => {
                        console.log('Video started playing');
                    }).catch(error => {
                        console.error('Video playback failed:', error);
                    });

                   // Hiệu ứng mờ dần và chuyển hướng khi video kết thúc
                   transitionVideo.onended = function () {
                        console.log('Video ended, starting fade-out');
                        videoOverlay.style.opacity = '0'; // Kích hoạt mờ dần
                        setTimeout(() => {
                            console.log('Fade-out complete, redirecting to:', targetUrl);
                            window.location.href = targetUrl;
                        }, 1000); // Chờ 1 giây (khớp với transition)
                    };

                    // Dự phòng: Chuyển hướng sau 10 giây nếu video không phát
                    setTimeout(() => {
                        if (videoOverlay.style.display === 'flex') {
                            console.warn('Video timeout, forcing redirect');
                            window.location.href = targetUrl;
                        }
                    }, 10000);

                    // Xử lý lỗi video
                    transitionVideo.onerror = function () {
                        console.error('Video failed to load');
                    };

                    // Kiểm tra xem video có hiển thị không
                    transitionVideo.onloadeddata = function () {
                        console.log('Video data loaded, should be visible');
                    };
                });
            });

            // Reset trạng thái khi trang được hiển thị lại
            window.addEventListener('pageshow', function () {
                videoOverlay.style.display = 'none';
                mainContent.classList.remove('hidden-content');
                transitionVideo.pause();
                transitionVideo.currentTime = 0;
                console.log('Page reset');
            });
        });
    </script>
    <script src="{{ asset('client/js/plugins.js') }}"></script>
    <!-- Main JS -->
    <script src="{{ asset('client/js/main.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    
    @stack('js')
</body>

</html>
