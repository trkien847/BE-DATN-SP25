<!DOCTYPE html>
<html>
<head>
    <title>Thống kê đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Thống kê đơn hàng ngày {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h1>

        <!-- Form chọn ngày -->
        <form method="GET" action="{{ route('orders.statistics') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="date" name="date" value="{{ $date }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Xem</button>
                </div>
            </div>
        </form>

        <!-- Thống kê doanh số -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Doanh số dự tính</h5>
                        <p class="card-text">{{ number_format($expectedRevenue, 0, ',', '.') }} VNĐ</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Doanh số thực tế</h5>
                        <p class="card-text">{{ number_format($actualRevenue, 0, ',', '.') }} VNĐ</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">Doanh số bị hủy</h5>
                        <p class="card-text">{{ number_format($canceledRevenue, 0, ',', '.') }} VNĐ</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ sóng -->
        <h3>Biểu đồ doanh số theo giờ</h3>
        <div class="card">
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['0h', '1h', '2h', '3h', '4h', '5h', '6h', '7h', '8h', '9h', '10h', '11h', '12h', 
                         '13h', '14h', '15h', '16h', '17h', '18h', '19h', '20h', '21h', '22h', '23h'],
                datasets: [
                    {
                        label: 'Doanh số dự tính',
                        data: @json($expectedRevenueData),
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        fill: true,
                    },
                    {
                        label: 'Doanh số thực tế',
                        data: @json($actualRevenueData),
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true,
                    },
                    {
                        label: 'Doanh số bị hủy',
                        data: @json($canceledRevenueData),
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        fill: true,
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Doanh số (VNĐ)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Giờ trong ngày'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    </script>
</body>
</html>