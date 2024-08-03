@extends('layout.app_admin', ['homeLinkAdmin' => route('trang-chu-admin')])

@section('content')
<div>
    <div style="display: flex; align-items: center;">  
        <h2 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Danh Sách Khuyến Mãi</h2>
        <a href="{{ route('tao-moi-khuyen-mai') }}" class="btn btn-primary" style="margin-left: auto;">Tạo mới khuyến mãi</a>
    </div>
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
                <th>Mô Tả</th>
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
                <td>{{ \Carbon\Carbon::parse($khuyenmai->NgayBatDau)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($khuyenmai->NgayKetThuc)->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('chi-tiet-khuyen-mai', $khuyenmai->MaKM) }}" class="btn btn-primary">Xem</a>
                    <a href="{{ route('xoa-khuyen-mai', $khuyenmai->MaKM) }}" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa khuyến mãi này không?')">Xóa</a>
                </td>
                </tr>
                @endforeach
        </tbody>
    </table>
</div>
@endsection