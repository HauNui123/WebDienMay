@extends('layout.app', ['homeLink' => route('trang-chu-dien-may')])

@section('content')
<div>
    <div style="display: flex; align-items: center;">
        @if(!empty($moTa))
        <h3 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">{{ $moTa }}</h3>
        @else
        <h3 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Đơn Hàng Của Bạn</h3>
        @endif
        <div><i style="margin-left: 10px; color: RGBA( 135, 206, 235, 1 ); font-size: 24px;" id="sortIcon" class="fas fa-filter"></i></div>
        <form id="sortForm" action="{{route('don-hang-ca-nhan')}}" method="GET" style="margin-left: auto;display: none; ">
            <select id="sortSelect" name="sapXep" style="height: 35px;">
                <option value="">Mặc định</option>
                <option value="da_hoan_thanh" {{ request()->sapXep == 'da_hoan_thanh' ? 'selected' : '' }}>Hoàn Thành</option>
                <option value="chua_duyet" {{ request()->sapXep == 'chua_duyet' ? 'selected' : '' }}>Chưa Duyệt</option>
                <option value="da_duyet" {{ request()->sapXep == 'da_duyet' ? 'selected' : '' }}>Đã Duyệt</option>
                <option value="dang_van_chuyen" {{ request()->sapXep == 'dang_van_chuyen' ? 'selected' : '' }}>Đang Vận Chuyển</option>
                <option value="giao_hang_that_bai" {{ request()->sapXep == 'giao_hang_that_bai' ? 'selected' : '' }}>Giao Hàng Thất Bại</option>
            </select>
        </form>
    </div>
    <!-- Script để xử lý sự kiện nhấn vào icon và hiển thị/ẩn form -->
    <script>
        const sortIcon = document.getElementById('sortIcon');
        const sortForm = document.getElementById('sortForm');

        // Bắt sự kiện nhấn vào icon
        sortIcon.addEventListener('click', function() {
            // Kiểm tra trạng thái hiện tại của form
            if (sortForm.style.display === 'none') {
                // Nếu form đang ẩn, hiển thị form
                sortForm.style.display = 'block';
            } else {
                // Nếu form đang hiển thị, ẩn form
                sortForm.style.display = 'none';
            }
        });
        // Bắt sự kiện thay đổi của select để tự động submit form
        sortSelect.addEventListener('change', function() {
            sortForm.submit();
        });
    </script>

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
    <table class="table" style="margin-top: 20px;">
        <thead class="table" style="background-color: RGBA(135, 206, 235, 1); color:white;">
            <tr class="text-center">
                <th>STT</th>
                <th>Ngày Lập</th>
                <th>Tổng Tiền</th>
                <th>Điểm Tích Được</th>
                <th>Trạng Thái</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($hoaDons as $index => $hoadon)
            <tr class="text-center">
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($hoadon->NgayLap)->format('d/m/Y') }}</td>
                <td>{{ number_format($hoadon->TongTien, 0, ',', '.') }} VNĐ</td>
                <td>{{ $hoadon->DiemTichDuoc }}</td>
                <td>
                    @if($hoadon->MaTrangThaiHD == 4)
                    <span style="color: green;">{{ $hoadon->TrangThaiHoaDon->MoTaTrangThai }}</span>
                    @elseif($hoadon->MaTrangThaiHD == 5)
                    <span class="text-danger">{{ $hoadon->TrangThaiHoaDon->MoTaTrangThai }}</span>
                    @else
                    {{ $hoadon->TrangThaiHoaDon->MoTaTrangThai }}
                    @endif
                </td>
                <td>
                    <a href="{{ route('chi-tiet-don-hang', $hoadon->MaHD) }}" class="btn btn-primary">Xem</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection