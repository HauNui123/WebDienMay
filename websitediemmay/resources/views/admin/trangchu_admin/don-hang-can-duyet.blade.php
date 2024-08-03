@extends('layout.app_admin', ['homeLinkAdmin' => route('trang-chu-admin')])

@section('content')
<div>
    @if ($danhsachhoadon->isEmpty())
        <h1 style="text-align: center; margin-top: 100px;">Không có đơn hàng nào cần duyệt!</h1>
    @else
        <h2 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Đơn Hàng Cần Duyệt</h2>
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
        <table class="table" style="margin-top: 20px;">
            <thead class="table" style="background-color: RGBA(135, 206, 235, 1); color:white;">
                <tr class="text-center">
                    <th>STT</th>
                    <th>Ngày Lập</th>
                    <th>Khách Hàng</th>
                    <th>Số Điện Thoại</th>
                    <th>Tổng Tiền</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($danhsachhoadon as $index => $hoadon)
                <tr class="text-center">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($hoadon->NgayLap)->format('d/m/Y') }}</td>
                    <td>{{ $hoadon->KhachHang->TenKH}}</td>
                    <td>{{ $hoadon->KhachHang->SDT}}</td>
                    <td>{{ number_format($hoadon->TongTien, 0, ',', '.') }} VNĐ</td>
                    <td>
                        <a href="{{ route('chi-tiet-hoa-don', $hoadon->MaHD) }}" class="btn btn-primary">Xem</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection