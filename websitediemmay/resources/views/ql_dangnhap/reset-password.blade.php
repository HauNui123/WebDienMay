<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">   
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="icon" type="image/x-icon" href="../img/logo.ico">
    <title>Reset Password</title>
    <style>
    body {
        background-color: RGBA(135, 206, 235, 1);
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }
</style>
</head>
<body>
    <div class="container text-center d-flex align-items-center min-vh-100">
        <div class="card mx-auto  py-5" style="width: 25rem;">
            <h1 style="margin-bottom: 20px;"><i class="fas fa-sync" style="color: black; font-size: 3vw; margin-right: 10px;"></i>Đặt Lại Mật Khẩu</h1>
            <div class="card-body">
            <form method="post">
                <div class="mb-3" style="position: relative;">
                    <label style="text-align: left; display: block;font-family: Verdana, Geneva, Tahoma, sans-serif;" for="matkhau" class="form-label"><i class="fas fa-key" style="color: black; font-size: 1.5vw;"></i><strong> Mật khẩu mới</strong></label>
                    <input type="password" class="form-control" id="matkhau" name="matkhau" placeholder="Nhập mật khẩu mới. . ." style="font-style: italic; font-size: 14px;" required>
                    <span class="password-toggle-icon" style="position: absolute; top: 75%; transform: translateY(-50%); right: 10px; cursor: pointer;"><i class="fas fa-eye" onclick="togglePasswordVisibilitya()"></i></span>
                    
                    <script>
                            function togglePasswordVisibilitya() {
                                var passwordInput = document.getElementById('matkhau');
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

                    @if ($errors->has('matkhau'))
                        <span class="text-danger">{{ $errors->first('matkhau') }}</span>
                    @endif
                </div>
                <div class="mb-3"  style="position: relative;">
                    <label style="text-align: left; display: block;font-family: Verdana, Geneva, Tahoma, sans-serif;" for="nhaplai"  class="form-label"><i class="fas fa-key" style="color: black; font-size: 1.5vw;"></i><strong>Nhập lại mật khẩu</strong></label>
                    <input type="password" class="form-control" id="nhaplai" name="nhaplai" placeholder="Nhập lại mật khẩu. . ." style="font-style: italic; font-size: 14px;" required>
                    <span class="password-toggle-icon" style="position: absolute; top: 75%; transform: translateY(-50%); right: 10px; cursor: pointer;"><i class="fas fa-eye" onclick="togglePasswordVisibilityb()"></i></span>
                    
                    <script>
                            function togglePasswordVisibilityb() {
                                var passwordInput = document.getElementById('nhaplai');
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

                    @if ($errors->has('nhaplai'))
                        <span class="text-danger">{{ $errors->first('nhaplai') }}</span>
                    @endif
                </div>
                <button style="width: 370px; background-color: RGBA( 135, 206, 235, 1 );" type="submit" class="btn mb-3" name="dangnhap"><a style="color:white; font-size: 20px;">Submit</a></button>
                @csrf
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </form>
            </div>
        </div>
    </div>
</body>
</html>