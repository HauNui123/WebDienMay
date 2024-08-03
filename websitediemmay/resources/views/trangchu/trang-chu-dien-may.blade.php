@extends('layout.app', ['homeLink' => route('trang-chu-dien-may')])

@section('content')
<div>

    @if(session('error'))
    <div id="errorAlert" style="margin-top: 10px; margin-bottom: 0px;" class="alert alert-danger">
        {{ session('error') }}
    </div>

    <script>
        // Sau 3 giây, ẩn thông báo lỗi
        setTimeout(function() {
            document.getElementById('errorAlert').style.display = 'none';
        }, 3000); // Thời gian tính bằng mili giây (ở đây là 3 giây)
    </script>
    @endif

    <!-- <div style="display: flex; justify-content: space-between; align-items: center;"> //thể hiện icon bên phải sát lề-->
    <div style="display: flex; align-items: center;">
        @if(!empty($moTa))
        <h3 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">{{ $moTa }}</h3>
        @else
        <h3 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Danh Sách Sản Phẩm</h3>
        @endif
        <div><i style="margin-left: 10px; color: RGBA( 135, 206, 235, 1 ); font-size: 24px;" id="sortIcon" class="fas fa-filter"></i></div>
        <form id="sortForm" action="{{ isset($loaiSanPham) ? route('trang-chu-dien-may-theo-loai', ['loaiSanPham' => $loaiSanPham]) : route('trang-chu-dien-may') }}" method="GET" style="margin-left: auto;display: none; ">
            <select id="sortSelect" name="sapXep" style="height: 35px;">
                <option value="">Mặc định</option>
                <option value="gia_tien_tang" {{ request()->sapXep == 'gia_tien_tang' ? 'selected' : '' }}>Sắp xếp theo giá tiền tăng</option>
                <option value="gia_tien_giam" {{ request()->sapXep == 'gia_tien_giam' ? 'selected' : '' }}>Sắp xếp theo giá tiền giảm</option>
                <option value="moi_nhat" {{ request()->sapXep == 'moi_nhat' ? 'selected' : '' }}>Sắp xếp theo mới nhất</option>
                <option value="cu_nhat" {{ request()->sapXep == 'cu_nhat' ? 'selected' : '' }}>Sắp xếp theo cũ nhất</option>
            </select>
        </form>
    </div>
    <!-- Script để xử lý sự kiện nhấn vào icon và hiển thị/ẩn form -->
    <script>
        const sortIcon = document.getElementById('sortIcon');
        const sortForm = document.getElementById('sortForm');

        // Bắt sự kiện nhấn vào icon
        sortIcon.addEventListener('click', function() {
            // Kiểm tra trạng thái hiện tại của form
            if (sortForm.style.display === 'none') {
                // Nếu form đang ẩn, hiển thị form
                sortForm.style.display = 'block';
            } else {
                // Nếu form đang hiển thị, ẩn form
                sortForm.style.display = 'none';
            }
        });
    </script>
    <div class="row" style="margin-top: 20px;">
        @foreach($danhsachsanphams as $sanpham)
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card" style="height: auto">
                    <div id="carouselExampleControls_{{ $sanpham->MaSP }}" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($sanpham->HinhAnhSP as $index => $hinhanhsp)
                            <div class="carousel-item  @if($index == 0) active @endif">
                                <!-- <img src="https://firebasestorage.googleapis.com/v0/b/qlsieuthi-54c8e.appspot.com/o/images%2F{{ $hinhanhsp->AnhSanPham }}?alt=media&token=966d606c-9997-467b-8f1f-bcd49b535f5a" class=" d-block w-100 img" style="height: 300px; width: auto; object-fit: cover;" alt="..." /> -->
                                <img src="https://firebasestorage.googleapis.com/v0/b/sieuthidienmay-6e8af.appspot.com/o/images%2F{{ $hinhanhsp->AnhSanPham }}?alt=media&token=4b53c182-5825-47c2-a647-01213a54a87b" class=" d-block w-100 img" style="height: 300px; width: auto; object-fit: cover;" alt="..." />
                            </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls_{{ $sanpham->MaSP }}" data-bs-slide="prev">
                            <span style="color: darkslategray; font-size: 40px;" aria-hidden="true"><i class='fas fa-angle-left'></i></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls_{{ $sanpham->MaSP }}" data-bs-slide="next">
                            <span style="color: darkslategray; font-size: 40px;" aria-hidden="true"><i class='fas fa-angle-right'></i></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var carouselId = 'carouselExampleControls_{{ $sanpham->MaSP }}';
                            var myCarousel = new bootstrap.Carousel(document.getElementById(carouselId), {
                                interval: 3000 // Thay đổi interval nếu cần
                            });

                            document.querySelector('#' + carouselId + ' .carousel-control-prev').addEventListener('click', function() {
                                myCarousel.prev();
                            });

                            document.querySelector('#' + carouselId + ' .carousel-control-next').addEventListener('click', function() {
                                myCarousel.next();
                            });
                        });
                    </script>
                    <div class="card-body">
                        <h5 class="card-title" style="height: 60px;"><a href="{{ route('sanpham.chitiet', $sanpham->MaSP) }}"><?= $sanpham->TenSP ?></a></h5>
                        @if($sanpham->SoLuong > 0)
                        @if($sanpham->GiaGiam)
                        <p class="card-text">
                            <span style="text-decoration: line-through; color: red;">
                                {{ number_format($sanpham->GiaSP, 0, ',', '.') }} VNĐ
                            </span>
                            <br>
                            Giảm {{ $sanpham->LGG }}
                            <br>
                            <span style="font-weight: bold; color: green;">
                                {{ number_format($sanpham->GiaGiam, 0, ',', '.') }} VNĐ
                            </span>
                        </p>
                        @else
                        <p class="card-text">
                            Giá: {{ number_format($sanpham->GiaSP, 0, ',', '.') }} VNĐ
                        </p>
                        @endif

                        <form action="{{ route('them-vao-gio-hang')}}" method="POST">
                            @csrf
                            <input type="hidden" name="productId" value="{{ $sanpham->MaSP }}">
                            <button type="submit" class="btn btn-add-to-cart" style="max-width: 100px; background-color:RGBA( 135, 206, 235, 1 )">Chọn mua</button>
                        </form>
                        @else
                        <p class="card-text">
                            <span style="font-weight: bold; color: red;">
                                Sắp về hàng
                            </span>
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="pagination">
        {{ $danhsachsanphams->links() }}
    </div>
    <script>
        // Lưu vị trí cuộn trang trước khi load lại
        window.addEventListener('beforeunload', function() {
            sessionStorage.setItem('scrollPosition', window.scrollY);
        });

        // Khôi phục lại vị trí cuộn sau khi trang được load lại
        window.addEventListener('load', function() {
            var scrollPosition = sessionStorage.getItem('scrollPosition');
            if (scrollPosition) {
                window.scrollTo(0, scrollPosition);
                sessionStorage.removeItem('scrollPosition'); // Xóa dữ liệu đã lưu sau khi khôi phục
            }
        });
    </script>
    <script>
        // Bắt sự kiện khi giá trị của dropdown list thay đổi
        document.getElementById('sortSelect').addEventListener('change', function() {
            // Gửi form khi có sự thay đổi
            document.getElementById('sortForm').submit();
        });
    </script>
    <script>
        window.onload = function() {
            // Lấy các vùng chứa biểu tượng
            const previousIconContainer = document.querySelector('.pagination .page-item:first-child .page-link');
            const nextIconContainer = document.querySelector('.pagination .page-item:last-child .page-link');

            // Sửa đổi các vùng chứa biểu tượng (ví dụ: thêm biểu tượng Font Awesome)
            previousIconContainer.innerHTML = '<i class="fas fa-angle-left"></i>';
            nextIconContainer.innerHTML = '<i class="fas fa-angle-right"></i>';

            // Hoặc thay thế, bạn có thể sửa đổi kiểu (ví dụ: tăng kích thước phông chữ)
            previousIconContainer.style.fontSize = '16px';
            nextIconContainer.style.fontSize = '16px';
        }
    </script>
</div>
@endsection