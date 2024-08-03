<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="icon" type="image/x-icon" href="../img/logo.ico">
    <title>{{ $title }}</title>
    <style>
        body {
            background-color: RGBA(135, 206, 235, 1);
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .close-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 24px;
            color: black;
        }

        .close-icon:hover {
            color: red;
            /* Màu sắc mong muốn khi hover */
        }

    </style>
</head>

<body>
    <div class="container d-flex align-items-center min-vh-100">
        <div class="card mx-auto py-5" style="width: 38rem;">
            <i class="fas fa-times close-icon" onclick="goBack()"></i>
            <h1 style="text-align: center;"><i class="fas fa-store" style="color: black; font-size: 3.5vw; margin-right: 10px;"></i>Đăng kí tài khoản</h1>
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            <div class="card-body">
                <form action="/ql_dangnhap/tao-tai-khoan-khach-hang" method="post">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" style="text-align:left; display: block; font-family: Verdana, Geneva, Tahoma, sans-serif;"> <i class="fas fa-user" style="color: black; font-size: 1.5vw;"></i><strong> Họ tên</strong></label>
                            <input type="text" class="form-control text-left" id="hoten" name="hoten" placeholder="Nhập họ và tên . . ." style="font-style: italic; font-size: 14px;" required>

                            @if ($errors->has('hoten'))
                            <span class="text-danger">{{ $errors->first('hoten') }}</span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="text-align:left; display: block; font-family: Verdana, Geneva, Tahoma, sans-serif;"> <i class="fas fa-map-marker-alt" style="color: black; font-size: 1.5vw;"></i><strong> Địa chỉ</strong></label>
                            <input type="text" class="form-control text-left" id="diachi" name="diachi" placeholder="Nhập địa chỉ . . ." style="font-style: italic; font-size: 14px;" required>

                            @if ($errors->has('diachi'))
                            <span class="text-danger">{{ $errors->first('diachi') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" style="text-align:left; display: block;font-family: Verdana, Geneva, Tahoma, sans-serif;"><i class="fas fa-envelope" style="color: black; font-size: 1.5vw;"></i><strong> Email</strong></label>
                            <input type="email" class="form-control text-left" id="email" name="email" placeholder="Nhập email . . ." style="font-style: italic; font-size: 14px;" required>

                            @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="text-align:left; display: block;font-family: Verdana, Geneva, Tahoma, sans-serif;"><i class="fas fa-phone-square-alt" style="color: black; font-size: 1.5vw;"></i><strong> Số điện thoại</strong></label>
                            <input type="text" class="form-control text-left" id="sdt" name="sdt" placeholder="Nhập số điện thoại. . ." style="font-style: italic; font-size: 14px;"required>

                            @if ($errors->has('sdt'))
                            <span class="text-danger">{{ $errors->first('sdt') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" style="text-align:left; display: block;font-family: Verdana, Geneva, Tahoma, sans-serif;"><i class="fas fa-user-friends" style="color: black; font-size: 1.5vw;"></i><strong> Giới tính</strong></label>
                                <select name="phai" class="form-select form-select-sm" >
                                    <option value="" style="font-style: italic;">Chọn giới tính</option>
                                    <option value="0">Nam</option>
                                    <option value="1">Nữ</option>
                                </select>
                            </div>
                            @if ($errors->has('phai'))
                            <span class="text-danger">{{ $errors->first('phai') }}</span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <div style="position: relative;">
                                <div class="mb-3" style="position: relative;">
                                    <label class="form-label" style="text-align:left; display: block;font-family: Verdana, Geneva, Tahoma, sans-serif;"><i class="fas fa-key" style="color: black; font-size: 1.5vw;"></i><strong> Mật Khẩu</strong></label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu . . ." style="font-style: italic; font-size: 14px;" required>
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

                    <div style="text-align: center;" class="mb-3">
                        <button style="width: 370px; background-color: RGBA( 135, 206, 235, 1 );" type="submit" class="btn mb-3" name="dangki"><a style="color:white; font-size: 20px;">Đăng kí</a></button>
                    </div>
                    @csrf
                </form>
            </div>
        </div>
    </div>
</body>
<script>
    function goBack() {
        window.history.back();
    }
</script>
</html>