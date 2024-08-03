@extends('layout.app_admin', ['homeLinkAdmin' => route('trang-chu-admin')])

@section('content')
@extends('layout.app', ['homeLink' => route('trang-chu-dien-may')])

@section('content')
<div>
    <h2 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Thông Tin Nhân Viên</h2>
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
    <form action="{{ route('cap-nhat-tai-khoan-nhan-vien') }}" method="post">

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label" style="text-align:left; display: block; font-family: Verdana, Geneva, Tahoma, sans-serif;"> <i class="fas fa-user" style="color: black; font-size: 1.5vw;"></i><strong> Họ tên</strong></label>
                <input type="text" class="form-control text-left" id="hoten" name="hoten" value="{{ $NhanVien->TenNV}}" style="font-style: italic; font-size: 14px;" required>

                @if ($errors->has('hoten'))
                <span class="text-danger">{{ $errors->first('hoten') }}</span>
                @endif
            </div>

            <div class="col-md-6">
                <label class="form-label" style="text-align:left; display: block; font-family: Verdana, Geneva, Tahoma, sans-serif;"> <i class="fas fa-map-marker-alt" style="color: black; font-size: 1.5vw;"></i><strong> Địa chỉ</strong></label>
                <input type="text" class="form-control text-left" id="diachi" name="diachi" value="{{ $NhanVien->DiaChi}}" style="font-style: italic; font-size: 14px;" required>

                @if ($errors->has('diachi'))
                <span class="text-danger">{{ $errors->first('diachi') }}</span>
                @endif
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" style="text-align:left; display: block;font-family: Verdana, Geneva, Tahoma, sans-serif;"><i class="fas fa-user-friends" style="color: black; font-size: 1.5vw;"></i><strong> Giới tính</strong></label>
                    <select name="phai" class="form-select form-select-sm">
                        <option value="" style="font-style: italic;">Chọn giới tính</option>
                        <option value="0" {{ $NhanVien->GioiTinh == 'Nam' ? 'selected' : '' }}>Nam</option>
                        <option value="1" {{ $NhanVien->GioiTinh == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                    </select>
                </div>
                @if ($errors->has('phai'))
                <span class="text-danger">{{ $errors->first('phai') }}</span>
                @endif
            </div>

            <div class="col-md-6">
                <label class="form-label" style="text-align:left; display: block;font-family: Verdana, Geneva, Tahoma, sans-serif;"><i class="fas fa-bullseye" style="color: black; font-size: 1.5vw;"></i><strong> Số CCCD</strong></label>
                <input type="text" class="form-control text-left" id="cccd" name="cccd" value="{{ $NhanVien->CCCD}}" style="font-style: italic; font-size: 14px;" maxlength="12" pattern="\d{12}" title="CCCD phải chứa đúng 12 số">

                @if ($errors->has('sdt'))
                <span class="text-danger">{{ $errors->first('sdt') }}</span>
                @endif
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label" style="text-align:left; display: block;font-family: Verdana, Geneva, Tahoma, sans-serif;"><i class="fas fa-envelope" style="color: black; font-size: 1.5vw;"></i><strong> Email</strong></label>
                <input type="email" class="form-control text-left" id="email" name="email" value="{{ $NhanVien->Email}}" style="font-style: italic; font-size: 14px;" required>

                @if ($errors->has('email'))
                <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <div class="col-md-4">
                <label class="form-label" style="text-align:left; display: block;font-family: Verdana, Geneva, Tahoma, sans-serif;"><i class="fas fa-phone-square-alt" style="color: black; font-size: 1.5vw;"></i><strong> Số điện thoại</strong></label>
                <input type="text" class="form-control text-left" id="sdt" name="sdt" value="{{ $NhanVien->SDT}}" style="font-style: italic; font-size: 14px;" required>

                @if ($errors->has('sdt'))
                <span class="text-danger">{{ $errors->first('sdt') }}</span>
                @endif
            </div>

            <div class="col-md-4">
                <div style="position: relative;">
                    <div class="mb-3" style="position: relative;">
                        <label class="form-label" style="text-align:left; display: block;font-family: Verdana, Geneva, Tahoma, sans-serif;"><i class="fas fa-key" style="color: black; font-size: 1.5vw;"></i><strong> Mật Khẩu</strong></label>
                        <input type="password" class="form-control" id="password" name="password" value="{{ $NhanVien->MatKhau}}" style="font-style: italic; font-size: 14px;" required>
                        <span class="password-toggle-icon" style="position: absolute; top: 75%; transform: translateY(-50%); right: 10px; cursor: pointer;"><i class="fas fa-eye" onclick="togglePasswordVisibility()"></i></span>
                    </div>

                    <script>
                        function togglePasswordVisibility() {
                            var passwordInput = document.getElementById('password');
                            var passwordToggleIcon = document.querySelector('.password-toggle-icon i');

                            if (passwordInput.type === 'password') {
                                passwordInput.type = 'text';
                                passwordToggleIcon.classList.remove('fa-eye');
                                passwordToggleIcon.classList.add('fa-eye-slash');
                            } else {
                                passwordInput.type = 'password';
                                passwordToggleIcon.classList.remove('fa-eye-slash');
                                passwordToggleIcon.classList.add('fa-eye');
                            }
                        }
                    </script>

                    @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
                </div>
            </div>
        </div>

</div>
<div style="text-align: center;" class="mb-3">
    <button style="width: 370px; background-color: RGBA( 135, 206, 235, 1 );" type="submit" class="btn mb-3" name="capnhat"><a style="color:white; font-size: 20px;">Cập nhật</a></button>
</div>
@csrf
</form>
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
</div>
@endsection
@endsection