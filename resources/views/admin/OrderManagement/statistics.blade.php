@extends('admin.layouts.layout')
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .scrollable-container {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
        }
    </style>
    <div class="container mt-5">
        <h1>Thống kê đơn hàng - {{ $dateLabel }}</h1>

        <form method="GET" action="{{ route('orders.statistics') }}" class="mb-4">
            <div class="row">
                <div class="col-md-2">
                    <select name="filter_type" class="form-control" id="filterType" onchange="toggleFilterFields()">
                        <option value="day" {{ $filterType === 'day' ? 'selected' : '' }}>Ngày hiện tại</option>
                        <option value="range" {{ $filterType === 'range' ? 'selected' : '' }}>Khoảng ngày</option>
                        <option value="month" {{ $filterType === 'month' ? 'selected' : '' }}>Theo tháng</option>
                        <option value="year" {{ $filterType === 'year' ? 'selected' : '' }}>Theo năm</option>
                    </select>
                </div>

                <div class="col-md-4 filter-field" id="rangeFields" style="display: {{ $filterType === 'range' ? 'block' : 'none' }}">
                    <div class="row">
                        <div class="col">
                            <input type="date" name="start_date" value="{{ $startDate }}" class="form-control" placeholder="Từ ngày">
                        </div>
                        <div class="col">
                            <input type="date" name="end_date" value="{{ $endDate }}" class="form-control" placeholder="Đến ngày">
                        </div>
                    </div>
                </div>

                <div class="col-md-3 filter-field" id="monthFields" style="display: {{ $filterType === 'month' ? 'block' : 'none' }}">
                    <div class="row">
                        <div class="col">
                            <select name="month" class="form-control">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ $m }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 filter-field" id="yearFields" style="display: {{ $filterType === 'year' ? 'block' : 'none' }}">
                    <select name="year" class="form-control">
                        @for ($y = $currentYear; $y >= 2000; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Xem</button>
                    <button type="submit" name="export" value="excel" class="btn btn-success">Xuất Excel</button>
                </div>
            </div>
        </form>

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

        <h3>Biểu đồ doanh số {{ $filterType === 'day' ? 'theo giờ' : ($filterType === 'range' || $filterType === 'month' ? 'theo ngày' : 'theo tháng') }}</h3>
        <div class="card mb-4">
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <h3>Phân bố trạng thái đơn hàng</h3>
                <div class="card">
                    <div class="card-body">
                        <canvas id="orderStatusChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <h3>Top 10 người dùng đặt nhiều nhất</h3>
                <div class="scrollable-container">
                    @forelse ($topUsers as $user)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/avatars/default.jpg') }}" alt="Avatar" width="50" height="50" class="rounded-circle me-2">
                            <div>
                                <strong>{{ $user->fullname }}</strong><br>
                                <small>Tổng tiền: {{ number_format($user->total_spent, 0, ',', '.') }} VNĐ</small>
                            </div>
                        </div>
                    @empty
                        <p>Không có dữ liệu</p>
                    @endforelse
                </div>
            </div>

            <div class="col-md-4">
                <h3>Top 10 sản phẩm được đặt nhiều nhất</h3>
                <div class="scrollable-container">
                    @forelse ($topProducts as $product)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $product->thumbnail ? asset('upload/' . $product->thumbnail) : asset('default-product.png') }}" alt="Product" width="50" height="50" class="me-2">
                            <div>
                                <strong>{{ $product->name }}</strong><br>
                                <small>Số lượng: {{ $product->items_sold }}</small>
                            </div>
                        </div>
                    @empty
                        <p>Không có dữ liệu</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFilterFields() {
            const filterType = document.getElementById('filterType').value;
            document.getElementById('rangeFields').style.display = filterType === 'range' ? 'block' : 'none';
            document.getElementById('monthFields').style.display = filterType === 'month' ? 'block' : 'none';
            document.getElementById('yearFields').style.display = filterType === 'year' ? 'block' : 'none';
        }

        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [
                    { label: 'Doanh số dự tính', data: @json($expectedRevenueData), borderColor: 'rgba(54, 162, 235, 1)', backgroundColor: 'rgba(54, 162, 235, 0.2)', fill: true },
                    { label: 'Doanh số thực tế', data: @json($actualRevenueData), borderColor: 'rgba(75, 192, 192, 1)', backgroundColor: 'rgba(75, 192, 192, 0.2)', fill: true },
                    { label: 'Doanh số bị hủy', data: @json($canceledRevenueData), borderColor: 'rgba(255, 99, 132, 1)', backgroundColor: 'rgba(255, 99, 132, 0.2)', fill: true }
                ]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Doanh số (VNĐ)' } },
                    x: { title: { display: true, text: '{{ $filterType === "day" ? "Giờ trong ngày" : ($filterType === "range" || $filterType === "month" ? "Ngày" : "Tháng") }}' } }
                },
                plugins: { legend: { display: true, position: 'top' } }
            }
        });

        const ctxStatus = document.getElementById('orderStatusChart').getContext('2d');
        const orderStatusChart = new Chart(ctxStatus, {
            type: 'pie',
            data: {
                labels: ['Đơn chờ xác nhận', 'Đơn hoàn thành', 'Đơn bị hủy'],
                datasets: [{
                    data: [@json($pendingOrdersCount), @json($completedOrdersCount), @json($canceledOrdersCount)],
                    backgroundColor: ['rgba(255, 206, 86, 0.8)', 'rgba(75, 192, 192, 0.8)', 'rgba(255, 99, 132, 0.8)'],
                    borderColor: ['rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' }, title: { display: true, text: 'Phân bố trạng thái đơn hàng' } }
            }
        });
    </script>
    @endsection