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

        input[type="checkbox"]:checked {
            background-color: black;
        }
    </style>
</head>

<body>
    <div class="container text-center d-flex align-items-center min-vh-100">
        <div class="card mx-auto py-5" style="width: 25rem; position: relative;">
            <i class="fas fa-times close-icon" onclick="goBack()"></i>
            <h1><i class="fas fa-store" style="color: black; font-size: 3.5vw; margin-right: 10px;"></i>Đăng Nhập</h1>
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
            <div class="card-body" style="margin-top: 30px;">
                <form action="/ql_dangnhap/dangnhap" method="post">
                    <div class="mb-3">
                        <label class="form-label" style="text-align:left; display: block; font-family: Verdana, Geneva, Tahoma, sans-serif;"> <i class="fas fa-user" style="color: black; font-size: 1.5vw;"></i><strong> Email/Số điện thoại</strong></label>
                        <input type="text" class="form-control" id="email_sdt" name="email_sdt" placeholder="Nhập email/Sdt . . ." style="font-style: italic; font-size: 14px;" required>

                        @if ($errors->has('email_sdt'))
                        <span class="text-danger">{{ $errors->first('email_sdt') }}</span>
                        @endif
                    </div>
                    <div class="mb-3" style="position: relative;">
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
                    <div class="mb-3 form-check" style="display: flex; align-items: center;">
                        <input type="checkbox" class="form-check-input" id="nhanvien" name="nhanvien">
                        <label class="form-check-label" for="nhanvien" style="text-align:left; margin-left: 5px; font-family: Verdana, Geneva, Tahoma, sans-serif;">Bạn là nhân viên</label>
                    </div>
                    <div class="d-flex flex-column align-items-center">
                        <button style="width: 370px; background-color: RGBA( 135, 206, 235, 1 );" type="submit" class="btn mb-3" name="dangnhap"><a style="color:white; font-size: 20px;">Đăng Nhập</a></button>
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('quen-mat-khau') }}" style="max-width: 200px; color:rgb(59, 176, 222);">Quên Mật Khẩu</a>
                            <a href="{{ route('tao-tai-khoan-khach-hang') }}" style="max-width: 200px; color:rgb(59, 176, 222);; margin-left: 10px;">Đăng Kí Tài Khoản</a>
                        </div>
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