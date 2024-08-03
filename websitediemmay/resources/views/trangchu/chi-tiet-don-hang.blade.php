@extends('layout.app', ['homeLink' => route('trang-chu-dien-may')])

@section('content')
<div>
    <h2 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Chi Tiết Đơn Hàng</h2>
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                @php
                $formattedTongTien = number_format($hoadon->TongTien, 0, ',', '.');
                @endphp
                <label for="tongtien" style="font-weight: bold;">Tổng Tiền</label>
                <input type="text" class="form-control" id="tongtien" name="tongtien" value="{{ $formattedTongTien}} VND" readonly>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="ngaymuahang" style="font-weight: bold;">Ngày mua hàng</label>
                <input type="text" class="form-control" id="ngaymuahang" name="ngaymuahang" value="{{ \Carbon\Carbon::parse($hoadon->NgayLap)->format('d/m/Y')  }}" readonly>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="diaChiGiaoHang" style="font-weight: bold;">Địa chỉ giao hàng</label>
        <input type="text" class="form-control" id="diaChiGiaoHang" name="diaChiGiaoHang" value="{{ $hoadon->DiaChiGiaoHang}}" readonly>
    </div>
    <div class="form-group">
        <label for="ghiChu" style="font-weight: bold;">Ghi chú</label>
        <textarea class="form-control" id="ghiChu" name="ghiChu" rows="3" readonly>{{ $hoadon->GhiChu }}</textarea>
    </div>
    @if($hoadon->MaTrangThaiHD == 4)
        <form method="POST" action="{{ route('them-binh-luan') }}">
            @csrf
            <label style="font-weight: bold; font-size: 20px;">Danh sách sản phẩm</label><br>
            <input type="text" id="searchInput" class="form-control" style="margin-top: 10px;" placeholder="Tìm kiếm sản phẩm...">
            <div class="form-group" style="overflow-y: scroll; max-height: 500px; margin-top: 10px; border: 1px solid #ced4da;">
                <div class="form-check" style="margin-left: 10px;">
                    <input class="form-check-input" type="checkbox" id="selectAll">
                    <label class="form-check-label" for="selectAll">
                        Chọn tất cả
                    </label>
                </div>
                @foreach($chitiethoadon as $chitiet)
                <div class="form-check" style="margin-left: 10px;">
                    <input class="form-check-input checkbox-item" type="checkbox" value="{{ $chitiet->SanPham->MaSP }}" id="sanpham_{{  $chitiet->SanPham->MaSP }}" name="sanpham_ids[]">
                    <label class="form-check-label" for="sanpham_{{ $chitiet->SanPham->MaSP }}">
                        {{ $chitiet->SanPham->TenSP }}
                    </label>
                    <input type="text" class="form-control quantity-input" id="quantity_{{ $chitiet->SanPham->MaSP }}" name="quantities[{{ $chitiet->SanPham->MaSP }}]" placeholder="Bình luận" style="display: none; margin-top: 5px;">
                </div>
                @endforeach
            </div>
            <div style="text-align: center; margin-top: 20px;" class="mb-3">
                <button style="width: 370px; background-color: RGBA( 135, 206, 235, 1 );" type="submit" class="btn mb-3"><a style="color:white; font-size: 20px;">Thêm bình luận</a></button>
            </div>
        </form>
    @else
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
    @endif

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