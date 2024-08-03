@extends('layout.app', ['homeLink' => route('trang-chu-dien-may')])

@section('content')
<div>
    <h2 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Hóa Đơn Của Bạn</h2>
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
            @foreach($chiTietGioHangs as $index => $chiTietGioHang)
            <tr class="text-center">
                <td>{{ $index + 1 }}</td>
                <td>{{ $chiTietGioHang->san_pham->TenSP }}</td>
                <td>{{ number_format($chiTietGioHang->san_pham->GiaGiam, 0, ',', '.') }} VNĐ</td>
                <td>{{ $chiTietGioHang->SoLuong }}</td>
                <td>{{ number_format($chiTietGioHang->san_pham->GiaGiam * $chiTietGioHang->SoLuong, 0, ',', '.') }} VNĐ</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="text-align: right; margin-top: 20px;">
        <p style="font-size: 30px;">Tổng tiền: <a id="tongTienHienThi" style="color:RGBA(135, 206, 235, 1);">{{ number_format($tongTien, 0, ',', '.') }} VNĐ</a></p>
        @if($cauHinhDangHoatDong)
            @php
                $diemTichLuy = floor($tongTien / $cauHinhDangHoatDong->SoTienTich) * $cauHinhDangHoatDong->SoDiemTich;
            @endphp
            <p style="font-size: 30px;">Điểm tích được: <a style="color:RGBA(135, 206, 235, 1);">{{ $diemTichLuy }}</a></p>
        @else
            <p style="font-size: 30px;">Không có cấu hình tích điểm nào đang hoạt động</p>
        @endif
    </div>


    <!-- Form nhập địa chỉ giao hàng và ghi chú -->
    <form action="{{ route('thanh-toan') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="diaChiGiaoHang" style="font-weight: bold;">Địa chỉ giao hàng <span style="color:red;">*</span></label>
                    <input type="text" class="form-control" id="diaChiGiaoHang" name="diaChiGiaoHang" placeholder="VD: 140 Lê Trọng Tấn" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" style="margin-top: 24px;">
                    <div>
                        <select name="city" class="form-select form-select-sm mb-3" id="city" aria-label=".form-select-sm" style="height: 40px;">
                            <option value="" selected>Chọn tỉnh thành</option>
                        </select>
                        @if ($errors->has('city'))
                        <span class="text-danger">{{ $errors->first('city') }}</span>
                        @endif

                        <select name="district" class="form-select form-select-sm mb-3" id="district" aria-label=".form-select-sm" style="height: 40px;">
                            <option value="" selected>Chọn quận huyện</option>
                        </select>
                        @if ($errors->has('district'))
                        <span class="text-danger">{{ $errors->first('district') }}</span>
                        @endif

                        <select name="ward" class="form-select form-select-sm" id="ward" aria-label=".form-select-sm" style="height: 40px;">
                            <option value="" selected>Chọn phường xã</option>
                        </select>
                        @if ($errors->has('ward'))
                        <span class="text-danger">{{ $errors->first('ward') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="maKhuyenMai" style="font-weight: bold;">Mã khuyến mãi (nếu có):</label>
                    <input type="text" class="form-control" id="maKhuyenMai" name="maKhuyenMai" placeholder="VD: KM02">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="maVoucher" style="font-weight: bold;">Voucher của bạn</label>
                    <select id="maVoucher" class="form-control" name="maVoucher">
                        <option value="">Chọn Voucher của bạn</option>
                        @foreach($vouchers as $vc)
                        <option value="{{ $vc->KhuyenMai->MaKM }}">{{ $vc->KhuyenMai->Mota}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="text-align: center;">
                <span id="khuyenMaiMessage" class="text-danger"></span>
            </div>
        </div>
        <div class="form-group">
            <label for="ghiChu" style="font-weight: bold;">Ghi chú</label>
            <textarea class="form-control" id="ghiChu" name="ghiChu" rows="3"></textarea>
        </div>
        <input type="hidden" name="tongTien" id="tongTien" value="{{ $tongTien }}">
        @if($cauHinhDangHoatDong)
            @php
                $diemTichLuy = floor($tongTien / $cauHinhDangHoatDong->SoTienTich) * $cauHinhDangHoatDong->SoDiemTich;
            @endphp
            <input type="hidden" name="diemtichluy" id="diemtichluy" value="{{ $diemTichLuy }}">
        @else
            <input type="hidden" name="diemtichluy" id="diemtichluy" value="0">
        @endif
        <input type="hidden" name="chiTietGioHangs" value="{{ json_encode($chiTietGioHangs) }}">
        <div class="d-flex justify-content-center mt-3">
            <button type="submit" name="payment_method" value="cod" class="btn btn-success me-2 flex-grow-1" onclick="return confirm('Bạn có chắc chắn muốn chọn hình thức thanh toán khi nhận hàng không?')">
                <i class="fas fa-dollar-sign" style="font-size: 30px; margin-right: 10px;"></i>
                <span style="font-size: 20px;">Thanh toán khi nhận hàng</span>
            </button>

            <button type="submit" name="payment_method" value="momo" class="btn me-2 flex-grow-1" style="background-color:#D82D8B; display: flex; align-items: center;">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRGds0dVYCpsArM9iAbJ8GNMQIHWR_M7vECi27mUxg1cQ&s" alt="Momo" style="height: 55px; width: auto; margin-right: 10px;">
                <div style="display: flex; flex-direction: column; align-items: flex-start;">
                    <span style="font-size: 20px; color: white;">Thanh toán Momo</span>
                    <span style="font-size: 14px; color: white;">( Tối đa 30 triệu )</span>
                </div>
            </button>
            <button type="submit" name="payment_method" value="vnpay" class="btn flex-grow-1" style="height: 80px; background-color: rgb(0, 102, 204);">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQULr3Ust3Yw-IS1KvGuHQFys81W1ava9Ohd8gduuRPXA&s" alt="VN Pay" style="height: 55px; width: auto; margin-right: 10px;">
                <span style="font-size: 20px; color: white;">Thanh toán VN Pay</span>
            </button>
        </div>
    </form>
    </br>
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

<script>
    // Lưu vị trí cuộn trang trước khi load lại
    window.addEventListener('beforeunload', function() {
        sessionStorage.setItem('scrollPosition', window.scrollY);
    });

    // Khôi phục lại vị trí cuộn sau khi trang được load lại
    window.addEventListener('load', function() {
        var scrollPosition = sessionStorage.getItem('scrollPosition');
        if (scrollPosition) {
            window.scrollTo(0, scrollPosition);
            sessionStorage.removeItem('scrollPosition'); // Xóa dữ liệu đã lưu sau khi khôi phục
        }
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
<script>
    var citis = document.getElementById("city");
    var districts = document.getElementById("district");
    var wards = document.getElementById("ward");

    var Parameter = {
        url: "https://raw.githubusercontent.com/kenzouno1/DiaGioiHanhChinhVN/master/data.json",
        method: "GET",
        responseType: "application/json",
    };

    var promise = axios(Parameter);
    promise.then(function(result) {
        renderCity(result.data);
    });

    function renderCity(data) {
        for (const x of data) {
            citis.options[citis.options.length] = new Option(x.Name, x.Name);
        }
        citis.onchange = function() {
            district.length = 1;
            ward.length = 1;
            if (this.value !== "") {
                const result = data.filter(n => n.Name === this.value);

                for (const k of result[0].Districts) {
                    district.options[district.options.length] = new Option(k.Name, k.Name);
                }
            }
        };
        district.onchange = function() {
            ward.length = 1;
            const dataCity = data.filter((n) => n.Name === citis.value);
            if (this.value !== "") {
                const dataWards = dataCity[0].Districts.filter(n => n.Name === this.value)[0].Wards;

                for (const w of dataWards) {
                    wards.options[wards.options.length] = new Option(w.Name, w.Name);
                }
            }
        };
    }
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        var tongTienGoc = parseFloat($('#tongTien').val());

        function applyDiscounts(maKhuyenMai, maVoucher) {
            var tongTien = tongTienGoc;

            $.ajax({
                url: '{{ route("check-discounts") }}',
                method: 'POST',
                data: {
                    maKhuyenMai: maKhuyenMai,
                    maVoucher: maVoucher,
                    _token: '{{ csrf_token() }}',
                    tongTien: tongTien
                },
                success: function(response) {
                    var tongTienMoi = tongTien;

                    response.forEach(function(discount) {
                        var GiaTriGiam = discount.GiaTriGiam;
                        var MaLKM = discount.MaLKM;

                        if (MaLKM == 3 || MaLKM == 5) { // Giảm giá phần trăm
                            tongTienMoi = tongTienMoi - (tongTienMoi * GiaTriGiam / 100);
                        } else if (MaLKM == 4 || MaLKM == 6) { // Giảm giá số tiền cố định
                            tongTienMoi = tongTienMoi - GiaTriGiam;
                        }
                    });

                    $('#tongTien').val(tongTienMoi);
                    $('#tongTienHienThi').text(new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(tongTienMoi));
                    $('#khuyenMaiMessage').text('');
                },
                error: function(response) {
                    $('#khuyenMaiMessage').text(response.responseJSON.error);
                    $('#tongTien').val(tongTienGoc);
                    $('#tongTienHienThi').text(new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(tongTienGoc));
                }
            });
        }

        function updateDiscounts() {
            var maKhuyenMai = $('#maKhuyenMai').val();
            var maVoucher = $('#maVoucher').val();
            applyDiscounts(maKhuyenMai, maVoucher);
        }

        $('#maKhuyenMai').on('keyup', function() {
            updateDiscounts();
        });

        $('#maVoucher').on('change', function() {
            updateDiscounts();
        });
    });
</script>
@endsection