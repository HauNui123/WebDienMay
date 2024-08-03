@extends('layout.app_admin', ['homeLinkAdmin' => route('trang-chu-admin')])

@section('content')
<div>
    @if ($danhsachnhanviens->isEmpty())
    <div style="display: flex; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Danh Sách Nhân Viên</h2>
        <a href="{{ route('tao-moi-khuyen-mai') }}" class="btn btn-primary" style="margin-left: auto;">Thêm nhân viên mới</a>
    </div>
    <h1 style="text-align: center; margin-top: 100px;">Chưa có nhân viên!</h1>
    @else
    <form action="{{ route('them-nhan-vien') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="loainhanvien" style="font-weight: bold;">Chọn Loại Nhân Viên</label>
                    <select id="loainhanvien" class="form-control" name="loainhanvien" required>
                        <option value="">Chọn Loại Nhân Viên</option>
                        <option value="4">Nhân Viên Duyệt Đơn</option>
                        <option value="5">Admin_website</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="tennhanvien" style="font-weight: bold;">Tên Nhân Viên</label>
                    <input type="text" class="form-control" id="tennhanvien" name="tennhanvien" placeholder="Nhập tên nhân viên" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="phai" style="font-weight: bold;">Phái Nhân Viên</label>
                    <select name="phai" class="form-control">
                        <option value="">Chọn giới tính</option>
                        <option value="0">Nam</option>
                        <option value="1">Nữ</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="cccd" style="font-weight: bold;">Số CCCD</label>
                    <input type="value" class="form-control" id="cccd" name="cccd" placeholder="Nhập số CCCD" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="diachi" style="font-weight: bold;">Địa chỉ</label>
                    <input type="text" class="form-control" id="diachi" name="diachi" placeholder="Nhập địa chỉ nhân viên" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="sdt" style="font-weight: bold;">Số Điện Thoại</label>
                    <input type="text" class="form-control" id="sdt" name="sdt" placeholder="Nhập số điện thoại" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="email" style="font-weight: bold;">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email nhân viên" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="matkhau" style="font-weight: bold;">Mật Khẩu</label>
                    <input type="number" class="form-control" id="matkhau" name="matkhau"placeholder="Nhập mật khẩu" required>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success" style="width: 100%; margin-top: 10px; margin-bottom: 20px;">Thêm nhân viên</button>
    </form>

    <div style="display: flex; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Danh Sách Nhân Viên</h2>
    </div>

    <form action="{{ route('tim-kiem-nhan-vien') }}" method="GET" style="margin-bottom: 20px;">
        <div class="input-group">
            <input type="text" name="query" class="form-control" placeholder="Tìm kiếm nhân viên theo SĐT hoặc Email">
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
                <th>Tên Nhân Viên</th>
                <th>Giới Tính</th>
                <th>SĐT</th>
                <th>Email</th>
                <th>Chức Vụ</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($danhsachnhanviens as $index => $Nhanvien)
            <tr class="text-center">
                <td>{{ $index + 1 }}</td>
                <td>{{ $Nhanvien->TenNV}}</td>
                <td>{{ $Nhanvien->GioiTinh}}</td>
                <td>{{ $Nhanvien->SDT}}</td>
                <td>{{ $Nhanvien->Email}}</td>
                <td>{{ $Nhanvien->ChucVu->MoTa}}</td>
                <td>
                    <a href="{{ route('vo-hieu-nhan-vien', $Nhanvien->MaNV) }}" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn vô hiệu hóa nhân viên này không?')">Vô hiệu hóa</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection