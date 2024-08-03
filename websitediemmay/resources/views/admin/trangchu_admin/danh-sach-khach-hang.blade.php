@extends('layout.app_admin', ['homeLinkAdmin' => route('trang-chu-admin')])

@section('content')
<div>
    @if ($danhsachkhachhangs->isEmpty())
    <form action="{{ route('tim-kiem-khach-hang') }}" method="GET" style="margin-bottom: 20px;">
        <div class="input-group">
            <input type="text" name="query" class="form-control" placeholder="Tìm kiếm khách hàng theo SĐT hoặc Email">
            <span class="input-group-btn" style="margin-left: 10px;">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </span>
        </div>
    </form>
    <h1 style="text-align: center; margin-top: 100px;">Chưa có khách hàng!</h1>
    @else
    <h2 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Danh Sách Khách Hàng</h2>
    <form action="{{ route('tim-kiem-khach-hang') }}" method="GET" style="margin-bottom: 20px;">
        <div class="input-group">
            <input type="text" name="query" class="form-control" placeholder="Tìm kiếm khách hàng theo SĐT hoặc Email">
            <span class="input-group-btn" style="margin-left: 10px;">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </span>
        </div>
    </form>
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

    <table class="table" style="margin-top: 20px;">
        <thead class="table" style="background-color: RGBA(135, 206, 235, 1); color:white;">
            <tr class="text-center">
                <th>STT</th>
                <th>Tên Khách Hàng</th>
                <th>Giới Tính</th>
                <th>Địa Chỉ</th>
                <th>SĐT</th>
                <th>Email</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($danhsachkhachhangs as $index => $khachhang)
            <tr class="text-center">
                <td>{{ $index + 1 }}</td>
                <td>{{ $khachhang->TenKH}}</td>
                <td>{{ $khachhang->GioiTinh}}</td>
                <td>{{ $khachhang->DiaChi}}</td>
                <td>{{ $khachhang->SDT}}</td>
                <td>{{ $khachhang->Email}}</td>
                <td>
                    <a href="{{ route('vo-hieu-khach-hang', $khachhang->MaKH) }}" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn vô hiệu hóa khách hàng này không?')">Vô hiệu hóa</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection