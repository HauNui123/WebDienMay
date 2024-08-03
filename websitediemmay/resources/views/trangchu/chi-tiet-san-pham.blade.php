@extends('layout.app', ['homeLink' => route('trang-chu-dien-may')])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>{{ $sanpham->TenSP }}</h2>
                </div>
                <div class="card-body">
                    <div style="height: 500px;" id="carouselExampleControls_{{ $sanpham->MaSP }}" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($sanpham->HinhAnhSP as $index => $hinhanhsp)
                            <div class="carousel-item  @if($index == 0) active @endif">
                                <!-- <img src="https://firebasestorage.googleapis.com/v0/b/qlsieuthi-54c8e.appspot.com/o/images%2F{{ $hinhanhsp->AnhSanPham }}?alt=media&token=966d606c-9997-467b-8f1f-bcd49b535f5a" style="width: 500px; height:500px; margin-left: 300px;" alt="..." /> -->
                                <img src="https://firebasestorage.googleapis.com/v0/b/sieuthidienmay-6e8af.appspot.com/o/images%2F{{ $hinhanhsp->AnhSanPham }}?alt=media&token=4b53c182-5825-47c2-a647-01213a54a87b" style="width: 500px; height:500px; margin-left: 300px;" alt="..." />
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
                    @if($sanpham->SoLuong > 0)
                    @if($sanpham->GiaGiam)
                    <p class="card-text">
                        <span style="text-decoration: line-through; color: red;">
                            Giá ban đầu: {{ number_format($sanpham->GiaSP, 0, ',', '.') }} VNĐ
                        </span>
                        <br>
                        Giảm {{ $sanpham->LGG }}
                        <br>
                        <span style="font-weight: bold; color: green;">
                            Giá đã giảm: {{ number_format($sanpham->GiaGiam, 0, ',', '.') }} VNĐ
                        </span>
                    </p>
                    @else
                    <p class="card-text">
                        Giá: {{ number_format($sanpham->GiaSP, 0, ',', '.') }} VNĐ
                    </p>
                    @endif
                    <p class="card-text">Mô tả: {{ $sanpham->MoTa }}</p>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <form action="{{ route('them-vao-gio-hang')}}" method="POST">
                            @csrf
                            <input type="hidden" name="productId" value="{{ $sanpham->MaSP }}">
                            <button type="submit" class="btn btn-add-to-cart" style="max-width: 100px; background-color:RGBA(135, 206, 235, 1);">Chọn mua</button>
                        </form>
                        <a href="{{ route('trang-chu-dien-may') }}" class="btn btn-primary">Quay lại</a>
                    </div>
                    @else
                    <p class="card-text">
                        <span style="font-size: 20px; font-weight: bold; color: red;">
                            Sắp về hàng
                        </span>
                    </p>
                    <a href="{{ route('trang-chu-dien-may') }}" class="btn btn-primary">Quay lại</a>
                    @endif
                </div>
            </div>
            <h2>Bình luận</h2>
            <div>
                @foreach($binhluans as $binhluan)
                <div class="comment" style="display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid #ddd;">
                    <div>
                        <a><strong>{{ $binhluan->KhachHang->TenKH }}: </strong> {{ $binhluan->BinhLuan }}</a><!-- Hiển thị tên khách hàng -->
                    </div>
                    <div>
                        <span>Ngày bình luận: {{ date('d/m/Y', strtotime($binhluan->NgayBinhLuan)) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection