@extends('layout.app', ['homeLink' => route('trang-chu-dien-may')])

@section('content')
<div>
    <h2 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Giỏ Hàng Của Bạn</h2>
    @if(session('success'))
    <div id="successAlert" style="margin-top: 10px; margin-bottom: 0px;" class="alert alert-success">
        {{ session('success') }}
    </div>

    <script>
        // Sau 3 giây, ẩn thông báo thành công
        setTimeout(function() {
            document.getElementById('successAlert').style.display = 'none';
        }, 3000); // Thời gian tính bằng mili giây (ở đây là 3 giây)
    </script>
    @endif

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

    @if ($chiTietGioHangs->isEmpty())
    <h1 style="text-align: center; margin-top: 100px;">Giỏ hàng của bạn đang trống</h1>
    @else
    <table class="table" style="margin-top: 20px;">
        <thead class="table" style="background-color: RGBA( 135, 206, 235, 1 ); color:white;">
            <tr class="text-center">
                <th>STT</th>
                <th>Hình ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($chiTietGioHangs as $index => $chiTietGioHang)
            <tr class="text-center" style="vertical-align: middle;">
                <td>{{ $index + 1 }}</td>
                <td>
                    @php
                    $firstImage = $chiTietGioHang->SanPham->HinhAnhSP->first();
                    @endphp
                    <!-- <img src="https://firebasestorage.googleapis.com/v0/b/qlsieuthi-54c8e.appspot.com/o/images%2F{{ $firstImage->AnhSanPham }}?alt=media&token=966d606c-9997-467b-8f1f-bcd49b535f5a" class=" card-img-top " style="width: 150px; height: 150px;" /> -->
                    <img src="https://firebasestorage.googleapis.com/v0/b/sieuthidienmay-6e8af.appspot.com/o/images%2F{{ $firstImage->AnhSanPham  }}?alt=media&token=4b53c182-5825-47c2-a647-01213a54a87b" class=" card-img-top " style="width: 150px; height: 150px;" />
                </td>
                <td>{{ $chiTietGioHang->SanPham->TenSP }}</td>
                <td>
                    <?= number_format($chiTietGioHang->SanPham->GiaGiam, 0, ',', '.') ?> VNĐ</p>
                </td>
                <td>
                    <form id="updateForm_{{ $index }}" action="{{ route('cap-nhat-so-luong-gio-hang') }}" method="POST">
                        @csrf
                        <input type="hidden" name="MaGH" value="{{ $chiTietGioHang->MaGH }}">
                        <input type="hidden" name="MaSP" value="{{ $chiTietGioHang->MaSP }}">
                        <input id="quantityInput_{{ $index }}" type="number" name="SoLuong" style="width: 50px; text-align: center;" value="{{ $chiTietGioHang->SoLuong }}" min="1">
                    </form>
                    <script>
                        // Bắt sự kiện khi giá trị của trường số lượng thay đổi
                        document.getElementById('quantityInput_{{ $index }}').addEventListener('change', function() {
                            document.getElementById('updateForm_{{ $index }}').submit();
                        });
                    </script>
                </td>
                <td>
                    <!-- Thêm nút hoặc link để xóa sản phẩm khỏi giỏ hàng -->
                    <a href="{{ route('xoa-khoi-gio-hang', ['MaGH' => $chiTietGioHang->MaGH, 'MaSP' => $chiTietGioHang->MaSP]) }}" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng không?')">Xóa</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tính tổng tiền -->
    @php
    $tongTien = 0;
    foreach($chiTietGioHangs as $chiTietGioHang) {
    $tongTien += $chiTietGioHang->SanPham->GiaGiam * $chiTietGioHang->SoLuong;
    }
    @endphp
    <div style="display: flex; justify-content: center; align-items: center; margin-top: 80px;">
        <p style="margin-right: 10px; font-size: 40px; ">Tổng tiền: <a style="color:RGBA( 135, 206, 235, 1 ); "><?= number_format($tongTien, 0, ',', '.') ?> </a>VNĐ</p>
        <form action="{{ route('lap-hoa-don') }}" method="POST" style="margin-top: -15px;">
            @csrf
            <input type="hidden" name="tongtien" value="{{ $tongTien }}">
            <input type="hidden" name="chitietgiohangs" value="{{ json_encode($chiTietGioHangs) }}">
            <button type="submit" class="btn btn-success">Thanh Toán</button>
        </form>
        <!-- <form action="{{ route('thanh-toan-vnpay') }}" method="POST" style="margin-top: -15px;">
            @csrf
            <input type="hidden" name="tongtien" value="{{ $tongTien }}" >
            <button type="submit" class="btn btn-success">Thanh Toán</button>
        </form> -->
    </div>
</div>
@endif
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
@endsection