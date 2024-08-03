@extends('layout.app_admin', ['homeLinkAdmin' => route('trang-chu-admin')])

@section('content')
<div>
    <h2 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Cập Nhật Sản Phẩm</h2>
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
    <form action="{{ route('cap-nhat-sp') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="tensanpham" style="font-weight: bold;">Tên Sản Phẩm</label>
                    <input type="text" class="form-control" id="tensanpham" name="tensanpham" value="{{ $sanpham->TenSP}}" require>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="soluong" style="font-weight: bold;">Số Lượng</label>
                    <input type="value" class="form-control" id="soluong" name="soluong" value="{{ $sanpham->SoLuong}}" require>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="ngaysanxuat" style="font-weight: bold;">Ngày Sản Xuất</label>
                    <input type="date" class="form-control" id="ngaysanxuat" name="ngaysanxuat" value="{{ \Carbon\Carbon::parse($sanpham->NgaySX)->format('Y-m-d') }}" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="mota" style="font-weight: bold;">Mô Tả</label>
            <textarea class="form-control" id="mota" name="mota" rows="3">{{ $sanpham->MoTa }}</textarea>
        </div>
        <input type="hidden" name="sanpham" value="{{ $sanpham->MaSP}}">
        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px; margin-bottom: 20px;">Cập nhật</button>
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