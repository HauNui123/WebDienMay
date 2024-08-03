@extends('layout.app_admin', ['homeLinkAdmin' => route('trang-chu-admin')])

@section('content')
<div>
    <div style="display: flex; align-items: center;">  
        <h2 style="margin: 0; color: RGBA(135, 206, 235, 1);">Doanh Thu Theo Quý</h2>
        <form id="formThongKeQuy" action="{{ route('thong-ke-quy-chon') }}" method="POST" style="margin-left: auto;"> 
            @csrf   
            <div class="form-group" style="display: flex; align-items: center;">
            <label for="quythongke" style="margin-right: 10px;">Quý:</label>
            <select class="form-control" id="quythongke" name="quythongke" required style="margin-right: 10px;">
                <option value="1" {{ $quyThongKe == 1 ? 'selected' : '' }}>Quý 1</option>
                <option value="2" {{ $quyThongKe == 2 ? 'selected' : '' }}>Quý 2</option>
                <option value="3" {{ $quyThongKe == 3 ? 'selected' : '' }}>Quý 3</option>
                <option value="4" {{ $quyThongKe == 4 ? 'selected' : '' }}>Quý 4</option>
            </select>
            <label for="namthongke" style="margin-right: 10px;">Năm:</label>
            <input type="number" class="form-control" id="namthongke" name="namthongke" value="{{ $namThongKe }}" required>
        </div>

        </form>
        <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var selectQuyThongKe = document.getElementById('quythongke');
                    var inputNamThongKe = document.getElementById('namthongke');

                    selectQuyThongKe.addEventListener('change', function() {
                        document.getElementById('formThongKeQuy').submit();
                    });

                    inputNamThongKe.addEventListener('change', function() {
                        document.getElementById('formThongKeQuy').submit();
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
        var doanhThuTheoTuan = {!! json_encode($doanhThuTheoQuy) !!};

        // Chuẩn bị dữ liệu cho biểu đồ
        var labels = [];
        var data = [];

        doanhThuTheoTuan.forEach(function(item) {
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
