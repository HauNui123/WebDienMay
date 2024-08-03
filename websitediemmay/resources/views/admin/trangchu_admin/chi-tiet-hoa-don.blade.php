@extends('layout.app_admin', ['homeLinkAdmin' => route('trang-chu-admin')])

@section('content')
<div>
    <h2 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Chi Tiết Hóa Đơn</h2>
    <form action="{{ route('chinh-sua-thong-tin-don-hang') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="tenkhachhang" style="font-weight: bold;">Tên khách hàng</label>
                    <input type="text" class="form-control" id="tenkhachhang" name="tenkhachhang" value="{{ $hoadon->khachHang->TenKH}}" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="sodienthoai" style="font-weight: bold;">Số điện thoại</label>
                    <input type="text" class="form-control" id="sodienthoai" name="sodienthoai" value="{{ $hoadon->khachHang->SDT}}" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="ngaymuahang" style="font-weight: bold;">Ngày mua hàng</label>
                    <input type="text" class="form-control" id="ngaymuahang" name="ngaymuahang" value="{{ \Carbon\Carbon::parse($hoadon->NgayLap)->format('d/m/Y')  }}" readonly>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="diaChiGiaoHang" style="font-weight: bold;">Địa chỉ giao hàng</label>
            <input type="text" class="form-control" id="diaChiGiaoHang" name="diaChiGiaoHang" value="{{ $hoadon->DiaChiGiaoHang}}" required autocomplete="street-address">
        </div>
        <div class="form-group">
            <label for="ghiChu" style="font-weight: bold;">Ghi chú</label>
            <textarea class="form-control" id="ghiChu" name="ghiChu" rows="3">{{ $hoadon->GhiChu }}</textarea>
        </div>
        <input type="hidden" name="hoadon" value="{{ $hoadon->MaHD}}">
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
    <h2 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Danh sách sản phẩm của hóa đơn</h2>
    <table class="table" style="margin-top: 20px;">
        <thead class="table" style="background-color: RGBA(135, 206, 235, 1); color:white;">
            <tr class="text-center">
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Tổng</th>
            </tr>
        </thead>
        <tbody>
            @foreach($chitiethoadon as $index => $chitiet)
            <tr class="text-center">
                <td>{{ $index + 1 }}</td>
                <td>{{ $chitiet->SanPham->TenSP }}</td>
                <td>{{ number_format($chitiet->DonGia, 0, ',', '.') }} VNĐ</td>
                <td>{{ $chitiet->SoLuong }}</td>
                <td>{{ number_format($chitiet->ThanhTien, 0, ',', '.') }} VNĐ</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="display: flex; justify-content: center; align-items: center; margin-top: 80px;">
        <p style="margin-right: 10px; font-size: 40px; ">Tổng tiền: <a style="color:RGBA( 135, 206, 235, 1 ); "><?= number_format($hoadon->TongTien, 0, ',', '.') ?> </a>VNĐ</p>
        <form action="{{ route('duyet-don-hang') }}" method="POST" style="margin-top: -15px;">
            @csrf
            <input type="hidden" name="mahoadon" value="{{ $hoadon->MaHD }}">
            <input type="hidden" name="chitiethoadon" value="{{ json_encode($chitiethoadon) }}">
            <button type="submit" class="btn btn-success">Duyệt đơn hàng</button>
        </form>
    </div>
</div>
@endsection