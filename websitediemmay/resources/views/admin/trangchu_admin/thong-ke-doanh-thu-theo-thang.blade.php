@extends('layout.app_admin', ['homeLinkAdmin' => route('trang-chu-admin')])

@section('content')
<div>
    <div style="display: flex; align-items: center;">
        <h2 style="margin: 0; color: RGBA(135, 206, 235, 1);">Doanh Thu Theo Tháng</h2>
        <form id="formThongKeThang" action="{{ route('thong-ke-thang-chon') }}" method="POST" style="margin-left: auto;">
            @csrf
            <div class="form-group" style="margin-left: auto;">
                <input type="month" class="form-control" id="thangthongke" name="thangthongke" value="{{ $ngayThongKe }}" required>
            </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var inputThangThongKe = document.getElementById('thangthongke');

                inputThangThongKe.addEventListener('change', function() {
                    var thangThongKe = new Date(inputThangThongKe.value);
                    var ngayHienTai = new Date();

                    // Đặt giờ, phút, giây của ngày hiện tại thành 0 để chỉ so sánh ngày
                    ngayHienTai.setHours(0, 0, 0, 0);

                    // Đặt giờ, phút, giây của ngày thống kê thành 0 để chỉ so sánh ngày
                    thangThongKe.setHours(0, 0, 0, 0);

                    // Kiểm tra nếu tháng thống kê không hợp lệ (lớn hơn tháng hiện tại)
                    if (thangThongKe > ngayHienTai) {
                        alert('Tháng thống kê không được lớn hơn tháng hiện tại.');
                        inputThangThongKe.value = ''; // Xóa giá trị nhập vào
                        return; // Dừng lại và không gửi form đi
                    }

                    // Nếu tháng thống kê hợp lệ, gửi form đi để thực hiện thống kê
                    document.getElementById('formThongKeThang').submit();
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
        var doanhThuTheoThang = {!! json_encode($doanhThuTheoThang) !!};

        // Chuẩn bị dữ liệu cho biểu đồ
        var labels = [];
        var data = [];

        doanhThuTheoThang.forEach(function(item) {
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