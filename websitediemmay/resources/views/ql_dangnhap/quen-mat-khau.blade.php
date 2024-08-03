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

        .password-toggle-icon:hover i {
            color: red;
            /* Màu sắc mong muốn khi hover */
        }
    </style>
</head>

<body>
    <div class="container text-center d-flex align-items-center min-vh-100">
        <div class="card mx-auto py-5" style="width: 25rem; position: relative;">
            <i class="fas fa-times close-icon" onclick="goBack()"></i>
            <h1 style="margin-bottom: 20px;"><i class="fas fa-unlock" style="color: black; font-size: 3vw; margin-right: 10px;"></i>Quên Mật Khẩu</h1>
            <div class="card-body">
                <form action="/ql_dangnhap/quen-mat-khau" method="post">
                    <div class="mb-3">
                        <label class="form-label" style="text-align:left; display: block;font-family: Verdana, Geneva, Tahoma, sans-serif;"><i class="fas fa-envelope" style="color: black; font-size: 1.5vw;"></i><strong> Email đăng kí</strong></label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email. . ." style="font-style: italic; font-size: 14px;" required>

                        @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="text-align:left; display: block;font-family: Verdana, Geneva, Tahoma, sans-serif;"><i class="fas fa-phone-square-alt" style="color: black; font-size: 1.5vw;"></i><strong> Số điện thoại đăng kí</strong></label>
                        <input type="text" class="form-control" id="sdt" name="sdt" pattern="[0-9]{10,12}" title="Số điện thoại phải có từ 10 đến 12 chữ số" style="font-style: italic; font-size: 14px;" placeholder="Nhập số điện thoại. . ." required>
                        @if ($errors->has('sdt'))
                        <span class="text-danger">{{ $errors->first('sdt') }}</span>
                        @endif
                    </div>
                    <br>
                    <button type="submit" class="btn" name="login" style="width: 360px; background-color: RGBA(135, 206, 235, 1); color:white; font-size: 18px;">Submit</button><br><br>
                    @csrf
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
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
<script>
    function goBack() {
        window.history.back();
    }
</script>

</html>