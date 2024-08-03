@extends('layout.app_admin', ['homeLinkAdmin' => route('trang-chu-admin')])

@section('content')
<div>
    @if ($chiTietApDung == null)
    <h2 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Chi Tiết Khuyến Mãi</h2>
    <form action="{{ route('chinh-sua-thong-tin-khuyen-mai') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="donhangtoithieu" style="font-weight: bold;">Đơn hàng tối thiểu</label>
                    <input type="value" class="form-control" id="donhangtoithieu" name="donhangtoithieu" value="{{ $khuyenmai->GiaTriDonHangToiThieu }}" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="ngaybatdau" style="font-weight: bold;">Ngày Bắt Đầu</label>
                    <input type="date" class="form-control" id="ngaybatdau" name="ngaybatdau" value="{{ \Carbon\Carbon::parse($khuyenmai->NgayBatDau)->format('Y-m-d') }}" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="ngayketthuc" style="font-weight: bold;">Ngày Kết Thúc</label>
                    <input type="date" class="form-control" id="ngayketthuc" name="ngayketthuc" value="{{ \Carbon\Carbon::parse($khuyenmai->NgayKetThuc)->format('Y-m-d') }}" required>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var ngayBatDauInput = document.getElementById('ngaybatdau');
                    var ngayKetThucInput = document.getElementById('ngayketthuc');

                    // Lắng nghe sự kiện onchange của ngày kết thúc (nếu cần)
                    ngayKetThucInput.addEventListener('change', function() {
                        var ngayBatDau = new Date(ngayBatDauInput.value);
                        var ngayKetThuc = new Date(ngayKetThucInput.value);

                        // Đặt giờ, phút, giây của ngày bắt đầu và ngày kết thúc thành 0 để chỉ so sánh ngày
                        ngayBatDau.setHours(0, 0, 0, 0);
                        ngayKetThuc.setHours(0, 0, 0, 0);

                        // Kiểm tra nếu ngày kết thúc nhỏ hơn ngày bắt đầu
                        if (ngayKetThuc < ngayBatDau) {
                            alert('Ngày kết thúc không được nhỏ hơn ngày bắt đầu.');
                            ngayKetThucInput.value = ''; // Xóa giá trị nhập vào
                        }
                    });
                });
            </script>
        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="giatrigiam" style="font-weight: bold;">Giá trị giảm</label>
                    <input type="value" class="form-control" id="giatrigiam" name="giatrigiam" value="{{ number_format($khuyenmai->GiaTriGiam, 0, ',', '.') }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="form-group">
                        <label for="motakhuyenmai" style="font-weight: bold;">Mô tả khuyến mãi</label>
                        <input type="text" class="form-control" id="motakhuyenmai" name="motakhuyenmai" value="{{ $khuyenmai->Mota}}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="sodiemdoiduoc" style="font-weight: bold;">Số điểm đổi được</label>
                    <input type="value" class="form-control" id="sodiemdoiduoc" name="sodiemdoiduoc" value="{{ $khuyenmai->SoDiemDoiDuoc}}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="form-group">
                        <label for="soluong" style="font-weight: bold;">Số lượng</label>
                        <input type="value" class="form-control" id="soluong" name="soluong" value="{{ $khuyenmai->SoLuong}}" required>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="khuyenmai" value="{{ $khuyenmai->MaKM}}">
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
    @else
    <h2 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Chi Tiết Khuyến Mãi Ưu Đãi</h2>
    <form action="{{ route('chinh-sua-thong-tin-khuyen-mai') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="giatrigiam" style="font-weight: bold;">Giá trị giảm</label>
                    <input type="value" class="form-control" id="giatrigiam" name="giatrigiam" value="{{ number_format($khuyenmai->GiaTriGiam, 0, ',', '.') }}" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="ngaybatdau" style="font-weight: bold;">Ngày Bắt Đầu</label>
                    <input type="date" class="form-control" id="ngaybatdau" name="ngaybatdau" value="{{ \Carbon\Carbon::parse($khuyenmai->NgayBatDau)->format('Y-m-d') }}" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="ngayketthuc" style="font-weight: bold;">Ngày Kết Thúc</label>
                    <input type="date" class="form-control" id="ngayketthuc" name="ngayketthuc" value="{{ \Carbon\Carbon::parse($khuyenmai->NgayKetThuc)->format('Y-m-d') }}" required>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var ngayBatDauInput = document.getElementById('ngaybatdau');
                    var ngayKetThucInput = document.getElementById('ngayketthuc');

                    // Lắng nghe sự kiện onchange của ngày kết thúc (nếu cần)
                    ngayKetThucInput.addEventListener('change', function() {
                        var ngayBatDau = new Date(ngayBatDauInput.value);
                        var ngayKetThuc = new Date(ngayKetThucInput.value);

                        // Đặt giờ, phút, giây của ngày bắt đầu và ngày kết thúc thành 0 để chỉ so sánh ngày
                        ngayBatDau.setHours(0, 0, 0, 0);
                        ngayKetThuc.setHours(0, 0, 0, 0);

                        // Kiểm tra nếu ngày kết thúc nhỏ hơn ngày bắt đầu
                        if (ngayKetThuc < ngayBatDau) {
                            alert('Ngày kết thúc không được nhỏ hơn ngày bắt đầu.');
                            ngayKetThucInput.value = ''; // Xóa giá trị nhập vào
                        }
                    });
                });
            </script>
        </div>

        <div class="form-group">
            <div class="form-group">
                <label for="motakhuyenmai" style="font-weight: bold;">Mô tả khuyến mãi</label>
                <input type="text" class="form-control" id="motakhuyenmai" name="motakhuyenmai" value="{{ $khuyenmai->Mota}}" required>
            </div>
        </div>

        <input type="hidden" name="khuyenmai" value="{{ $khuyenmai->MaKM}}">

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div id="danhSachSanPham">
            <label style="font-weight: bold; font-size: 20px;">Danh sách sản phẩm áp dụng khuyến mãi</label><br>
            <input type="text" id="searchInput" class="form-control" style="margin-top: 10px;" placeholder="Tìm kiếm sản phẩm...">
            <div class="form-group" style="overflow-y: scroll; max-height: 500px; margin-top: 10px; border: 1px solid #ced4da;">
                <div class="form-check" style="margin-left: 10px;">
                    <input class="form-check-input" type="checkbox" id="selectAll">
                    <label class="form-check-label" for="selectAll">
                        Chọn tất cả
                    </label>
                </div>
                @foreach($danhsachsanpham as $sanpham)
                <div class="form-check" style="margin-left: 10px;">
                    <input class="form-check-input checkbox-item" type="checkbox" value="{{ $sanpham->MaSP }}" id="sanpham_{{  $sanpham->MaSP }}" name="sanpham_ids[]" {{ in_array($sanpham->MaSP, $sanphamDaChon) ? 'checked' : '' }}>
                    <label class="form-check-label" for="sanpham_{{ $sanpham->MaSP }}">
                        {{ $sanpham->TenSP }}
                    </label>
                    <input type="number" class="form-control quantity-input" id="quantity_{{ $sanpham->MaSP }}" name="quantities[{{ $sanpham->MaSP }}]" placeholder="Số lượng" style="{{ in_array($sanpham->MaSP, $sanphamDaChon) ? '' : 'display: none;' }}; margin-top: 5px;" value="{{ in_array($sanpham->MaSP, $sanphamDaChon) ? $chiTietApDung->firstWhere('MaSP', $sanpham->MaSP)->SoLuong : '' }}">
                </div>
                @endforeach
            </div>
        </div>
        <script>
            // JavaScript để xử lý tìm kiếm sản phẩm
            document.getElementById('searchInput').addEventListener('input', function() {
                var searchQuery = this.value.toLowerCase(); // Lấy giá trị tìm kiếm và chuyển về chữ thường
                var checkboxes = document.querySelectorAll('.form-check-label'); // Lấy tất cả các label trong form-check

                checkboxes.forEach(function(label) {
                    var productName = label.textContent.toLowerCase(); // Lấy tên sản phẩm và chuyển về chữ thường
                    var checkbox = label.previousElementSibling; // Lấy checkbox tương ứng

                    if (productName.includes(searchQuery)) {
                        label.parentElement.style.display = 'block'; // Hiển thị sản phẩm nếu tên sản phẩm chứa từ khóa tìm kiếm
                    } else {
                        label.parentElement.style.display = 'none'; // Ẩn sản phẩm nếu tên sản phẩm không chứa từ khóa tìm kiếm
                    }
                });
            });
            // JavaScript để xử lý chọn tất cả
            document.getElementById('selectAll').addEventListener('change', function() {
                var checkboxes = document.getElementsByClassName('checkbox-item');

                for (var i = 0; i < checkboxes.length; i++) {
                    checkboxes[i].checked = this.checked;
                }
            });

            // JavaScript để kiểm tra và thay đổi trạng thái của nút "Chọn tất cả"
            var checkboxes = document.getElementsByClassName('checkbox-item');
            var selectAllCheckbox = document.getElementById('selectAll');

            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].addEventListener('change', function() {
                    var allChecked = true;
                    for (var j = 0; j < checkboxes.length; j++) {
                        if (!checkboxes[j].checked) {
                            allChecked = false;
                            break;
                        }
                    }
                    selectAllCheckbox.checked = allChecked;
                });
            }
            document.addEventListener('DOMContentLoaded', function() {
                var checkboxes = document.querySelectorAll('.checkbox-item');
                checkboxes.forEach(function(checkbox) {
                    checkbox.addEventListener('change', function() {
                        var quantityInput = document.getElementById('quantity_' + this.value);
                        if (this.checked) {
                            quantityInput.style.display = 'block';
                        } else {
                            quantityInput.style.display = 'none';
                            quantityInput.value = ''; // Reset quantity input
                        }
                    });
                });

                var selectAll = document.getElementById('selectAll');
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = selectAll.checked;
                        var quantityInput = document.getElementById('quantity_' + checkbox.value);
                        if (selectAll.checked) {
                            quantityInput.style.display = 'block';
                        } else {
                            quantityInput.style.display = 'none';
                            quantityInput.value = ''; // Reset quantity input
                        }
                    });
                });
            });
        </script>
        <input type="hidden" name="khuyenmai" value="{{ $khuyenmai->MaKM}}">
        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px; margin-bottom: 20px;">Cập nhật</button>
    </form>
    @endif
</div>

@endsection