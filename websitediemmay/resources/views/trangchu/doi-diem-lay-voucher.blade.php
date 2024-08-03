@extends('layout.app', ['homeLink' => route('trang-chu-dien-may')])

@section('content')
<div>
    <a href="{{ route('voucher-ca-nhan') }}" class="btn btn-primary">Voucher đã đổi</a>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
        <h2 style="margin: 0; color: RGBA(135, 206, 235, 1);">Đổi Điểm Lấy Voucher</h2>
        <div style="font-size: 20px; color: RGBA(135, 206, 235, 1);">
            <p>Điểm tích lũy của bạn: <a id="diemtichluycuaban" style="color:RGBA(135, 206, 235, 1);">{{ $khachhang->DiemTichLuy }}</a></p>
        </div>
    </div>
    @if (session('success'))
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

    @if (session('error'))
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

    <table class="table" style="margin-top: 20px;">
        <thead class="table" style="background-color: RGBA(135, 206, 235, 1); color:white;">
            <tr class="text-center">
                <th>STT</th>
                <th>Mô Tả</th>
                <th>Số điểm đổi được</th>
                <th>Số Bắt Đầu</th>
                <th>Ngày Kết Thúc</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($danhsachkhuyenmais as $index => $khuyenmai)
            <tr class="text-center">
                <td>{{ $index + 1 }}</td>
                <td>{{ $khuyenmai->Mota}}</td>
                <td>{{ $khuyenmai->SoDiemDoiDuoc}}</td>
                <td>{{ \Carbon\Carbon::parse($khuyenmai->NgayBatDau)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($khuyenmai->NgayKetThuc)->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('doi-voucher', $khuyenmai->MaKM) }}" class="btn btn-primary">Đổi</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection