@extends('layout.app_admin', ['homeLinkAdmin' => route('trang-chu-admin')])

@section('content')
<div>
    <div style="display: flex; align-items: center;">  
        <h2 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Danh Thu Theo Ngày</h2>
        <form id="formThongKeNgay" action="{{ route('thong-ke-ngay-chon') }}" method="POST" style="margin-left: auto;">    
            @csrf
            <div class="form-group" style="margin-left: auto;">
                <input type="date" class="form-control" id="ngaythongke" name="ngaythongke" value="{{ $ngayThongKe }}" required>
            </div>
        </form>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var inputNgayThongKe = document.getElementById('ngaythongke');

                inputNgayThongKe.addEventListener('change', function() {
                    var ngayThongKe = new Date(inputNgayThongKe.value);
                    var ngayHienTai = new Date();

                    // Đặt giờ, phút, giây của ngày hiện tại thành 0 để chỉ so sánh ngày
                    ngayHienTai.setHours(0, 0, 0, 0);

                    // Đặt giờ, phút, giây của ngày thống kê thành 0 để chỉ so sánh ngày
                    ngayThongKe.setHours(0, 0, 0, 0);

                    // Kiểm tra nếu ngày thống kê không hợp lệ (lớn hơn ngày hiện tại)
                    if (ngayThongKe > ngayHienTai) {
                        alert('Ngày thống kê không được lớn hơn ngày hiện tại.');
                        inputNgayThongKe.value = ''; // Xóa giá trị nhập vào
                        return; // Dừng lại và không gửi form đi
                    }

                    // Nếu ngày thống kê hợp lệ, gửi form đi để thực hiện thống kê
                    document.getElementById('formThongKeNgay').submit();
                });
            });
        </script>   
    </div>
    <div style="display: flex; align-items: center;">
        <h3 style="margin-left: auto;">Tổng doanh thu: {{ number_format($tongDoanhThu) }}</h3>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="container" style="margin-top: 20px;">
        <canvas id="myChart" width="400" height="400"></canvas>
    </div>

    <script>
        // Dữ liệu từ Controller (ví dụ)
        var doanhThuTheoNgay = {!! json_encode($doanhThuTheoNgay) !!};

        // Chuẩn bị dữ liệu cho biểu đồ
        var labels = [];
        var data = [];

        doanhThuTheoNgay.forEach(function(item) {
            labels.push(item.TenKho);
            data.push(item.DoanhThu);
        });

        // Vẽ biểu đồ bằng Chart.js
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar', // Loại biểu đồ (ví dụ: cột)
            data: {
                labels: labels, // Nhãn trục x (Tên kho)
                datasets: [{
                    label: 'Doanh thu theo kho', // Nhãn cho dataset
                    data: data, // Dữ liệu doanh thu
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', // Màu nền cho cột
                    borderColor: 'rgba(54, 162, 235, 1)', // Màu đường viền cột
                    borderWidth: 1 // Độ rộng đường viền
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true // Bắt đầu trục y từ 0
                    }
                }
            }
        });
    </script>
</div>
@endsection