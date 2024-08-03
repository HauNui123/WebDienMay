@extends('layout.app_admin', ['homeLinkAdmin' => route('trang-chu-admin')])

@section('content')
<div>
    <h2 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Cấu hình tích điểm</h2>
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

    <form action="{{ route('them-cau-hinh-tich-diem') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="sotien" style="font-weight: bold;">Số tiền</label>
                    <input type="number" class="form-control" id="sotien" name="sotien" placeholder="VD:100" require>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="sodiem" style="font-weight: bold;">Số điểm đổi được</label>
                    <input type="number" class="form-control" id="sodiem" name="sodiem" placeholder="VD:10" require>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" style="margin-top: 24px;">Thêm</button>
                </div>
            </div>
        </div>
    </form>
    <h2 style="margin: 0; color: RGBA( 135, 206, 235, 1 ); margin-top: 20px;">Danh sách cấu hình tích điểm</h2>
    <table class="table" style="margin-top: 20px;">
        <thead class="table" style="background-color: RGBA(135, 206, 235, 1); color:white;">
            <tr class="text-center">
                <th>STT</th>
                <th>Số tiền</th>
                <th>Điểm tích được</th>
                <th>Áp Dụng</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($cauhinhtichdiems as $index => $cauhinh)
            <tr class="text-center">
                <td>{{ $index + 1 }}</td>
                <td>{{ number_format($cauhinh->SoTienTich, 0, ',', '.') }} VNĐ</td> 
                <td>{{ $cauhinh->SoDiemTich}}</td>
                <td>
                    <form action="{{route('cap-nhat-cau-hinh-tich-diem')}}" method="POST">
                        @csrf
                        <input type="hidden" name="matichdiem" value="{{ $cauhinh->MaTichDiem }}">
                        <input style="transform: scale(2.0); margin: 10px;" type="checkbox" name="TrangThaiApDung" onchange="this.form.submit()" {{ $cauhinh->TrangThaiApDung ? 'checked' : '' }}>
                    </form>
                </td>
                <td>
                <a href="{{ route('xoa-cau-hinh-tich-diem', $cauhinh->MaTichDiem) }}" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa cấu hình này không?')">Xóa</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection