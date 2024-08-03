@extends('layout.app', ['homeLink' => route('trang-chu-dien-may')])

@section('content')
<div>
    <h2 style="margin: 0; color: RGBA( 135, 206, 235, 1 );">Thống kê Hóa Đơn Của Bạn</h2>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <canvas id="myChart" width="400" height="200"></canvas>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var labels = {!! $hoaDons->pluck('NgayLap') !!};
            var data = {!! $hoaDons->pluck('TongTien') !!};

            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Số Tiền Đã Chi',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('en-US', { style: 'currency', currency: 'VND' });
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</div>
@endsection