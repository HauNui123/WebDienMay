@extends('layout.app_admin', ['homeLinkAdmin' => route('trang-chu-admin')])

@section('content')
<div>
    <h2 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Tạo Mới Khuyến Mãi</h2>
    @if ($errors->any())
    <div id="errorAlert" class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <script>
        // Sau 3 giây, ẩn thông báo lỗi
        setTimeout(function() {
            document.getElementById('errorAlert').style.display = 'none';
        }, 3000); // Thời gian tính bằng mili giây (ở đây là 3 giây)
    </script>
    @endif
    <form action="{{ route('them-khuyen-mai') }}" method="POST">    
        @csrf
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="khuyenmai_id" style="font-weight: bold;">Chọn Loại Khuyến Mãi</label>
                    <select id="khuyenmai_id" class="form-control" name="khuyenmai_id" required>
                        <option value="">Chọn Khuyến Mãi</option>
                        @foreach($loaikhuyenmai as $lkm)
                        <option value="{{ $lkm->MaLKM }}">{{ $lkm->Mota}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="makhuyenmai" style="font-weight: bold;">Mã Khuyến Mãi</label>
                    <input type="text" class="form-control" id="makhuyenmai" name="makhuyenmai" placeholder="VD:KM001" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="ngaybatdau" style="font-weight: bold;">Ngày Bắt Đầu</label>
                    <input type="date" class="form-control" id="ngaybatdau" name="ngaybatdau" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="ngayketthuc" style="font-weight: bold;">Ngày Kết Thúc</label>
                    <input type="date" class="form-control" id="ngayketthuc" name="ngayketthuc" required>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var ngayBatDauInput = document.getElementById('ngaybatdau');
                    var ngayKetThucInput = document.getElementById('ngayketthuc');

                    // Lắng nghe sự kiện onchange của ngày bắt đầu
                    ngayBatDauInput.addEventListener('change', function() {
                        var ngayBatDau = new Date(ngayBatDauInput.value);
                        var ngayHienTai = new Date();

                        // Đặt giờ, phút, giây của ngày hiện tại thành 0 để chỉ so sánh ngày
                        ngayHienTai.setHours(0, 0, 0, 0);

                        // Đặt giờ, phút, giây của ngày bắt đầu thành 0 để chỉ so sánh ngày
                        ngayBatDau.setHours(0, 0, 0, 0);

                        // Kiểm tra nếu ngày bắt đầu nhỏ hơn ngày hiện tại
                        if (ngayBatDau < ngayHienTai) {
                            alert('Ngày bắt đầu không được nhỏ hơn ngày hiện tại.');
                            ngayBatDauInput.value = ''; // Xóa giá trị nhập vào
                            ngayKetThucInput.value = ''; // Xóa giá trị ngày kết thúc nếu cần thiết
                        }
                    });

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
            <div class="col-md-3">
                <div class="form-group">
                    <label for="giatritoithieu" style="font-weight: bold;">Giá Trị Đơn Hàng Tối Thiểu</label>
                    <input type="number" class="form-control" id="giatritoithieu" name="giatritoithieu" min="0" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="giatrigiam" style="font-weight: bold;">Giá Trị Giảm</label>
                    <input type="number" class="form-control" id="giatrigiam" name="giatrigiam" min="0" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="soluongapdung" style="font-weight: bold;">Số Lượng Áp Dụng</label>
                    <input type="number" class="form-control" id="soluongapdung" name="soluongapdung" min="0" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="diemdoiduoc" style="font-weight: bold;">Số Điểm Đổi Được</label>
                    <input type="number" class="form-control" id="diemdoiduoc" name="diemdoiduoc" min="0" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="mota" style="font-weight: bold;">Mô tả</label>
            <textarea class="form-control" id="mota" name="mota" rows="3" required></textarea>
        </div>
        <div id="danhSachSanPham" style="display: none;">
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
                    <input class="form-check-input checkbox-item" type="checkbox" value="{{ $sanpham->MaSP }}" id="sanpham_{{  $sanpham->MaSP }}" name="sanpham_ids[]">
                    <label class="form-check-label" for="sanpham_{{ $sanpham->MaSP }}">
                        {{ $sanpham->TenSP }}
                    </label>
                    <input type="number" class="form-control quantity-input" id="quantity_{{ $sanpham->MaSP }}" name="quantities[{{ $sanpham->MaSP }}]" placeholder="Số lượng" style="display: none; margin-top: 5px;">
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
        <button type="submit" class="btn btn-success" style="width: 100%; margin-top: 10px; margin-bottom: 20px;">Tạo Khuyến Mãi</button>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var selectKhuyenMai = document.getElementById('khuyenmai_id');
        var inputDiemDoiDuoc = document.getElementById('diemdoiduoc');
        var inputGiaTriToiThieu = document.getElementById('giatritoithieu');
        var inputSoLuongApDung = document.getElementById('soluongapdung');

        selectKhuyenMai.addEventListener('change', function() {
            var selectedValue = selectKhuyenMai.value;
            // Hiển thị hoặc ẩn danh sách sản phẩm áp dụng khuyến mãi
            if (selectedValue == '1' || selectedValue == '2') {
                danhSachSanPham.style.display = 'block';
                inputSoLuongApDung.disabled = true;
            } else {
                danhSachSanPham.style.display = 'none';
                inputSoLuongApDung.disabled = false;
            }
            if (selectedValue == '1' || selectedValue == '2') {
                inputDiemDoiDuoc.disabled = true;
                inputGiaTriToiThieu.disabled = true;
            } else {
                inputDiemDoiDuoc.disabled = false;
                inputGiaTriToiThieu.disabled = false;
            }
            if (selectedValue == '3' || selectedValue == '4' || selectedValue == '1' || selectedValue == '2') {
                inputDiemDoiDuoc.disabled = true;
            } else {
                inputDiemDoiDuoc.disabled = false;
            }
        });
    });
</script>
@endsection