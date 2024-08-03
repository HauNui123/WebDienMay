@extends('layout.app', ['homeLink' => route('trang-chu-dien-may')])

@section('content')
<div>
    <a href="{{ route('doi-diem') }}" class="btn btn-primary">Đổi điểm lấy Voucher</a>
    <h2 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Voucher Của Bạn</h2>
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
                <th>Voucher</th>
                <th>Từ Ngày</th>
                <th>Đến Ngày</th>
                <th>Điểm Đổi</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($vouchers as $index => $vouchers)
            <tr class="text-center">
                <td>{{ $index + 1 }}</td>
                <td>{{ $vouchers->KhuyenMai->Mota}}</td>
                <td>{{ \Carbon\Carbon::parse($vouchers->KhuyenMai->NgayBatDau)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($vouchers->KhuyenMai->NgayKetThuc)->format('d/m/Y') }}</td>
                <td>{{ $vouchers->KhuyenMai->SoDiemDoiDuoc}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection