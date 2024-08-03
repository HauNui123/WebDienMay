<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image/x-icon" href="../img/logo.ico">
    <title>{{ $title }}</title>
    <!-- Bổ sung các tài liệu CSS và JavaScript ở đây -->
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
    <style>
        body {
            position: relative;
            min-height: 100vh;
            padding-top: 100px;
            padding-bottom: 400px;
        }

        header nav.navbar {
            box-shadow: 0 3px 5px rgba(57, 63, 72, 0.3);
        }

        footer {
            color: #fff;
            font-family: Arial, sans-serif;
            font-size: 14px;
            padding: 20px;
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        ul.navbar-nav {
            width: 100%;
            display: flex;
            justify-content: center;
            margin: 0;
            padding: 0;
        }

        ul.navbar-nav li.nav-item {
            margin-right: 20px;
        }

        ul.navbar-nav li.nav-item a {
            color: white;
        }

        ul.navbar-nav li.nav-item a.nav-link.active {
            color: white;
        }

        .dropdown-menu li:hover a {
            color: RGBA(135, 206, 235, 1) !important;
        }

        .dropdown-item:hover {
            background-color: white;
            /* Màu sắc mong muốn khi hover */
        }

        .luachonDN:hover {
            background-color: RGBA(135, 206, 235, 1);
            /* Màu sắc mong muốn khi hover */
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pagination .page-link {
            color: black;
            /* Thay đổi màu chữ của các liên kết phân trang */
        }

        .pagination .page-item.active .page-link {
            background-color: RGBA(135, 206, 235, 1);
            /* Thay đổi màu nền của trang hiện tại */
            border-color: RGBA(135, 206, 235, 1);
            /* Thay đổi màu viền của trang hiện tại */
        }

        .dropdown:hover .dropdown-menu {
            display: block;
            border-radius: 1px !important;
            border: none !important;
            margin-left: 0px;
            padding-left: 10px;
        }

        body {
            background-image: url('/images/hinh1.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        /* Định dạng sidebar */
        #sidebar {
            min-height: 100vh;
            /* Chiều cao tối thiểu bằng chiều cao của viewport */
            position: sticky;
            /* Giữ sidebar cố định khi cuộn trang */
            top: 0;
            /* Bắt đầu từ đầu trang */
        }

        /* Kiểu dáng của sidebar */
        .sidebar {
            padding-top: 1.5rem;
            /* Khoảng cách từ trên xuống */
        }

        /* Kiểu dáng của nội dung trang */
        .main {
            padding-top: 3.5rem;
            /* Khoảng cách từ trên xuống */
        }

        #carouselExample {
            max-width: max;
            /* Adjust as needed */
            margin: 0px 0px;
        }

        /* Adjust image size */
        .carousel-item img {
            height: 350px;
            /* Adjust as needed */
            width: 100%;
            object-fit: cover;
            /* Ensure images maintain aspect ratio */
        }

        /* Chèn hình ảnh vào hai bên khoảng trống */
        /* .container-fluid::before ,.container-fluid::after{
            content: "";
            display: block;
            position: absolute;
            top: 100px;
            bottom: 0;
            width: 7vw;
            /* Kích thước của hình ảnh sẽ chèn vào cạnh trái và phải */
        /*background-image: url('https://img.tgdd.vn/imgt/f_webp,fit_outside,quality_100/https://cdn.tgdd.vn/2024/04/banner/phai-79x271-1.png');
            /*background-size: auto;
            /* Hiển thị hình ảnh theo tỉ lệ khung hình */
        /*background-repeat: repeat-y;
            /* Lặp lại hình ảnh theo chiều dọc */
        /* }*/

        /*.container-fluid::before {
            left: 10px;
            /* Chèn hình ảnh vào bên trái */

        /*}

        /* .container-fluid::after {
            right: -15px;
            /* Chèn hình ảnh vào bên phải */


        /*} */
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
            <div class="container-fluid" style="height: 80px; max-width: auto;background-color: RGBA( 135, 206, 235, 1 );padding: 0 7vw;">
                <a class="navbar-brand" style="color: #fff; font-size: 3vw; font-family: Arial, Helvetica, sans-serif;   font-style: italic; font-weight: bold;" href="{{ $homeLink }}">HAULONGPHAT</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav" style="display: flex; align-items: center;">
                    <form action="{{ route('tim-kiem') }}" method="GET" style="margin-left: 21.7vw; margin-right: auto;">
                        <div class="search-container">
                            <input type="text" style="border-radius: 0.5vw 0 0 0.5vw; border:none; height: 3vw; width: 30vw; padding-left: 2vw;" name="timkiem" placeholder="Bạn cần tìm gì hôm nay?">
                            <button type="submit" style="border-radius: 0 0.5vw 0.5vw 0; border:none; height: 3vw;"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                    <!-- Giỏ hàng -->
                    <div>
                        @if (session('user'))
                        <?php
                        // Lấy thông tin người dùng từ session
                        $user = session('user');

                        // Kiểm tra xem user là loại KhachHang hay không
                        if ($user instanceof App\Models\KhachHang) {
                            $user_id = $user->MaKH;

                            // Lấy tổng số mặt hàng trong giỏ hàng của khách hàng đó
                            // $tongSoMatHang = App\Models\GioHang::where('MaKH', $user_id)->first()->ChiTietGioHang()->sum('SoLuong');
                            $gioHang = App\Models\GioHang::where('MaKH', $user_id)->first();

                            if ($gioHang) {
                                // Nếu giỏ hàng tồn tại, lấy tổng số mặt hàng trong giỏ hàng của khách hàng đó
                                $tongSoMatHang = $gioHang->ChiTietGioHang()->sum('SoLuong');
                            } else {
                                // Nếu giỏ hàng chưa được tạo, set tổng số mặt hàng là 0
                                $gioHangMoi = new App\Models\GioHang();
                                $gioHangMoi->MaKH = $user_id;
                                $gioHangMoi->save();
                                $tongSoMatHang = 0;
                            }
                        } else {
                            // Nếu không phải khách hàng, set tổng số mặt hàng là 0
                            $tongSoMatHang = 0;
                        }
                        ?>
                        <a style="position: relative; margin: auto 1vw auto 2vw; display: inline-block;" href="{{ route('gio-hang-cua-ban') }}">
                            <i class="fa fa-shopping-cart" style="color: #fff; font-size: 1.5vw;"></i>
                            <span style="position: absolute; top: -10px; right: -10px; background-color: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px;">{{ $tongSoMatHang }}</span>
                        </a>
                        @else
                        <a style="margin: auto 1vw auto 2vw;" href="{{ route('dangnhap') }}">
                            <i class="fa fa-shopping-cart" style="color: #fff; font-size: 1.5vw;"></i>
                        </a>
                        @endif
                    </div>
                    <div class="dropdown">
                        <a class="btn btn-link position-relative" style=" padding-right: 0;" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user" style="color: #fff; font-size: 1.5vw;"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown" style="left: -25px;">
                            @if (!session('user'))
                            <a class="dropdown-item luachonDN" href="{{ route('dangnhap')}}">Đăng nhập</a>
                            @else
                            <a class="dropdown-item luachonDN" href="{{ route('dangxuat')}}">Đăng xuất</a>
                            <a class="dropdown-item luachonDN" href="{{ route('thong-tin-can-nhan')}}">Thông tin cá nhân</a>
                            <a class="dropdown-item luachonDN" href="{{ route('don-hang-ca-nhan')}}">Đơn hàng</a>
                            <a class="dropdown-item luachonDN" href="{{ route('voucher-ca-nhan')}}">Voucher</a>
                            <a class="dropdown-item luachonDN" href="{{ route('thong-ke')}}">Thống kê</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="container-fluid" style="padding: 0 7vw;">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar" style="background-color: RGBA(135, 206, 235, 1); z-index: 100;"> <!--position: fixed;top: 100px; bottom: 80px; width: 228px;-->
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        @foreach($danhmucsanphams as $danhmucsanpham)
                        <li class="nav-item dropdown" style="margin-bottom: 2px; margin-top: 2px; position: relative;"> <!-- Điều chỉnh margin top và bottom -->
                            <a class="nav-link dropdown" href="#" id="navbarDropdown{{ $danhmucsanpham->MaDM }}" role="button" aria-haspopup="true" aria-expanded="false" style="color: white;font-size: 20px;">
                                {{ $danhmucsanpham->TenDM }}
                            </a>
                            <!-- Menu con -->
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown{{ $danhmucsanpham->MaDM }}" style="background-color: RGBA(135, 206, 235, 1); position: absolute; top: 0; left: 100%;">
                                @foreach($danhmucsanpham->loaisanpham as $loaisanpham)
                                <li>
                                    <a class="dropdown-item" href="{{ route('trang-chu-dien-may-theo-loai', ['loaiSanPham' => $loaisanpham->MaLoaiSP]) }}" style="color: white;">{{$loaisanpham->MoTa}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        <hr style="border-color: white; margin-top: 5px; margin-bottom: 5px;">
                        @endforeach
                    </ul>
                </div>
            </nav>


            <!-- Content -->
            <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-md-2">
                <!-- The slideshow/carousel -->
                <div class="row">
                    <div class="col-md-12">
                        <!-- The slideshow/carousel -->
                        <div id="carouselExample" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="2000">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="https://noithatqg.com/wp-content/uploads/2021/01/gia-ke-cua-hang-dien-may.gif" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://martmedia.vn/wp-content/uploads/2018/11/quang-cao-sieu-thi-dien-may-2.jpg" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://cdn.thegioididong.com/Files/2019/12/16/1226918/Gallery/IMG_6252.JPG" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://img.websosanh.vn/v10/users/review/images/rq60c1cjll6jm/dien-may.jpg?compress=85" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://hc.com.vn/i/ecommerce/media/2845254_BANNER_DESKTOP_IMAGE_1_143856.jpg" class="d-block w-100" alt="...">
                                </div>
                            </div>
                        </div>
                        <!-- Add Bootstrap JS and jQuery -->
                        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
                        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

                        <script>
                            // Activate carousel auto play
                            $('.carousel').carousel({
                                interval: 2000 // Change interval as needed (in milliseconds)
                            });
                        </script>
                    </div>
                </div>
                <div class="container mt-3" style="min-height: 1000px;--bs-gutter-x: 0rem;">
                    @yield('content') <!-- Đây là nơi nội dung cụ thể của từng trang sẽ được hiển thị -->
                </div>
            </main>
        </div>
    </div>
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <div style="position: relative; z-index: 10000;">
        <df-messenger intent="WELCOME" chat-title="ChatAI_" agent-id="046182cd-32ab-4b5a-b36a-ea2dd9869681" language-code="vi" image="https://static.vecteezy.com/system/resources/thumbnails/007/225/199/small_2x/robot-chat-bot-concept-illustration-vector.jpg"></df-messenger>


    </div>

    <footer class="text-center text-lg-start text-dark" style="background-color:RGBA( 135, 206, 235, 1 );z-index: 600;">
        <div class="container" style="color: #fff;">
            <section class="">
                <div class="container text-center text-md-start mt-5">
                    <div class="row mt-3">
                        <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                            <h6 class="text-uppercase fw-bold">HLPQuiz</h6>
                            <hr class="mb-4 mt-0 d-inline-block mx-auto" style="width: 60px; background-color: #7c4dff; height: 2px" />
                            <p>
                                Hệ thống điện máy hiện đại nhất Việt Nam
                            </p>
                        </div>
                        <!-- Grid column -->

                        <!-- Grid column -->
                        <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                            <!-- Links -->
                            <h6 class="text-uppercase fw-bold">Điều khoản</h6>
                            <hr class="mb-4 mt-0 d-inline-block mx-auto" style="width: 60px; background-color: #7c4dff; height: 2px" />
                            <p>
                                <a href="#!" class="text-white">Điều khoản sử dụng</a>
                            </p>
                            <p>
                                <a href="#!" class="text-white">Điều khoản bảo mật thông tin</a>
                            </p>
                        </div>
                        <!-- Grid column -->

                        <!-- Grid column -->
                        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                            <!-- Links -->
                            <h6 class="text-uppercase fw-bold">Liên kết</h6>
                            <hr class="mb-4 mt-0 d-inline-block mx-auto" style="width: 60px; background-color: #7c4dff; height: 2px" />
                            <p>
                                <a href="{{ $homeLink }}" class="text-white">Trang chủ</a>
                            </p>
                            <p>
                                <a href="#!" class="text-white">Hướng dẫn</a>
                            </p>
                            <p>
                                <a href="#!" class="text-white">Liên hệ</a>
                            </p>
                        </div>
                        <!-- Grid column -->

                        <!-- Grid column -->
                        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                            <!-- Links -->
                            <h6 class="text-uppercase fw-bold">Thông tin liên hệ</h6>
                            <hr class="mb-4 mt-0 d-inline-block mx-auto" style="width: 60px; background-color: #7c4dff; height: 2px" />
                            <p><i class="fas fa-home mr-3"></i> 140, Lê Trọng Tấn, Thành Phố Hồ Chí Minh</p>
                            <p><i class="fas fa-envelope mr-3"></i> vanhau98.nhd@gmail.com</p>
                            <p><i class="fas fa-phone mr-3"></i> (+84) 764 670 179</p>
                            <p><i class="fas fa-print mr-3"></i> (+84) 764 670 179</p>
                        </div>
                        <!-- Grid column -->
                    </div>
                    <!-- Grid row -->
                </div>
            </section>
            <!-- Section: Links  -->

            <!-- Copyright -->
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
                © 2024 Copyright:
                <a class="text-white" href="{{ route('trang-chu-dien-may')}}">HLPQuiz</a>
            </div>
            <!-- Copyright -->
        </div>

    </footer>
    <!-- Footer -->
</body>

</html>