<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="icon" type="image/x-icon" href="../img/logo.ico">
    <title>{{ $title }}</title>
    <!-- Bổ sung các tài liệu CSS và JavaScript ở đây -->
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
    <style>
        body {
            position: relative;
            min-height: 100vh;
            padding-top: 100px;
            padding-bottom: 150px;
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

        .menu-quanly:hover {
            color: #2681e2 !important;
        }

        .dropdown:hover .menu-quanly {
            color: #2681e2 !important;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
            <div class="container-fluid" style="height: 80px; max-width: auto;background-color: RGBA( 135, 206, 235, 1 );padding: 0 7vw;">
                <a class="navbar-brand" style="color: #fff; font-size: 3vw; font-family: Arial, Helvetica, sans-serif;   font-style: italic; font-weight: bold;" href="{{ $homeLinkAdmin }}">Admin HLP</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav" style="display: flex; align-items: center;">
                    @if (session('user') && session('user')->MaCV == 4)
                        <a class="nav-link menu-quanly" style="color: #fff; font-size: 1.6vw; margin-right: 1.8vw; margin-left: auto; font-weight: bold;" href="{{ route('duyet-don')}}">Duyệt Đơn</a>
                        <a class="nav-link menu-quanly" style="color: #fff; font-size: 1.6vw; margin-right: 1.8vw; margin-left: auto; font-weight: bold;" href="{{ route('xuat-kho-don-hang')}}">Xuất Kho</a>
                        <a class="nav-link menu-quanly" style="color: #fff; font-size: 1.6vw; margin-right: 1.8vw; margin-left: auto; font-weight: bold;" href="{{ route('trang-thai-don-hang')}}">Cập Nhật Đơn Hàng</a>
                    @else
                        <!-- thống kê -->
                        <!-- <a class="nav-link menu-quanly" style="color: #fff; font-size: 1.6vw; margin-right: 1.8vw; margin-left: auto; font-weight: bold;" href="{{ route('khuyen-mai')}}">Thống Kê</a> -->
                        <div class="dropdown" style="margin-left: auto;">
                            <a class="btn btn-link position-relative menu-quanly" style="color: #fff; font-size: 1.5vw; margin-right: 1.0vw; font-weight: bold; text-decoration: none;" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Thống Kê
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown" style="left: -25px;">
                                <a class="dropdown-item luachonDN" href="{{ route('thong-ke-doanh-thu-theo-ngay')}}">Theo Ngày</a>
                                <a class="dropdown-item luachonDN" href="{{ route('thong-ke-doanh-thu-theo-tuan')}}">Theo Tuần</a>
                                <a class="dropdown-item luachonDN" href="{{ route('thong-ke-doanh-thu-theo-thang')}}">Theo Tháng</a>
                                <a class="dropdown-item luachonDN" href="{{ route('thong-ke-doanh-thu-theo-quy')}}">Theo Quý</a>
                            </div>
                        </div>
                        <!-- <a class="nav-link menu-quanly" style="color: #fff; font-size: 1.7vw; margin-right: 3vw; margin-left: auto; font-weight: bold;" href="{{ route('khuyen-mai')}}">Khuyến mãi</a> -->
                        <div class="dropdown">
                            <a class="btn btn-link position-relative menu-quanly" style="color: #fff; font-size: 1.5vw; margin-right: 1vw; font-weight: bold; text-decoration: none;" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Người Dùng
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <a class="dropdown-item luachonDN" href="{{ route('danh-sach-khach-hang')}}">Khách hàng</a>
                                <a class="dropdown-item luachonDN" href="{{ route('danh-sach-nhan-vien')}}">Nhân viên</a>
                                <!-- <a class="dropdown-item luachonDN" href="{{ route('danh-sach-khach-hang-vo-hieu-hoa')}}">Khách hàng vô hiệu hóa</a> -->
                                <a class="dropdown-item luachonDN" href="{{ route('danh-sach-nhan-vien-vo-hieu-hoa')}}">Nhân viên vô hiệu hóa</a>
                            </div>
                        </div>
                        <!-- Quản lý đơn hàng -->
                        <div class="dropdown">
                            <a class="btn btn-link position-relative menu-quanly" style="color: #fff; font-size: 1.5vw; margin-right: 1.0vw; font-weight: bold; text-decoration: none;" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Đơn Hàng
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown" style="left: -25px;">
                                <a class="dropdown-item luachonDN" href="{{ route('duyet-don')}}">Duyệt đơn thanh toán khi nhận hàng</a>
                                <a class="dropdown-item luachonDN" href="{{ route('xuat-kho-don-hang')}}">Xuất kho đơn hàng</a>
                                <a class="dropdown-item luachonDN" href="{{ route('trang-thai-don-hang')}}">Trạng thái đơn hàng</a>
                            </div>
                        </div>
                        <!-- Quản lý ưu đãi -->
                        <div class="dropdown">
                            <a class="btn btn-link position-relative menu-quanly" style="color: #fff; font-size: 1.5vw; margin-right: 2.2vw; font-weight: bold; text-decoration: none;" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Ưu Đãi
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown" style="left: -25px;">
                                <a class="dropdown-item luachonDN" href="{{ route('khuyen-mai') }}">Khuyến mãi</a>
                                <a class="dropdown-item luachonDN" href="{{ route('tich-diem') }}">Tích điểm</a>
                            </div>
                        </div>
                    @endif
                    <form action="{{ route('admin-tim-kiem')}}" method="GET" style="margin-right: 1vw;">
                        <div class="search-container">
                            <input type="text" style="border-radius: 0.5vw 0 0 0.5vw; border:none; height: 3vw; width: 20vw; padding-left: 2vw;" name="timkiem" placeholder="Nhập tên sản phẩm muốn tìm!">
                            <button type="submit" style="border-radius: 0 0.5vw 0.5vw 0; border:none; height: 3vw;"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                    <div class="dropdown">
                        <a class="btn btn-link position-relative" style="padding-right: 0;" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user" style="color: #fff; font-size: 1.5vw;"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown" style="left: -25px;">
                            @if (!session('user'))
                            <a class="dropdown-item luachonDN" href="{{ route('dangnhap')}}">Đăng nhập</a>
                            @else
                            <a class="dropdown-item luachonDN" href="{{ route('dangxuat')}}">Đăng xuất</a>
                            <a class="dropdown-item luachonDN" href="{{ route('thong-tin-nhan-vien')}}">Thông tin cá nhân</a>
                            @endif
                        </div>
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
                                    <a class="dropdown-item" href="{{ route('trang-chu-admin-theo-loai', ['loaiSanPham' => $loaisanpham->MaLoaiSP]) }}" style="color: white;">{{$loaisanpham->MoTa}}</a>
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
                <div class="container mt-3" style="min-height: 1000px;--bs-gutter-x: 0rem;">
                    @yield('content') <!-- Đây là nơi nội dung cụ thể của từng trang sẽ được hiển thị -->
                </div>
            </main>
        </div>
    </div>
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>

    <footer class="text-center text-lg-start text-dark" style="background-color:RGBA( 135, 206, 235, 1 );z-index: 600;">
        <div class="container" style="color: #fff;">

            <!-- Copyright -->
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
                © 2024 Copyright:
                <a class="text-white" href="{{ route('trang-chu-admin')}}">HLPQuiz</a>
            </div>
            <!-- Copyright -->
        </div>
    </footer>
    <!-- Footer -->
</body>

</html>