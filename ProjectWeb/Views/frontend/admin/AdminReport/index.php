<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo thống kê - SR Store</title>
    <!-- Bootstrap CSS -->
    <link href="/Project_Website/ProjectWeb/layout/cssBootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Admin.css">
    <style>
    .notification-dropdown::-webkit-scrollbar {
        width: 6px;
    }
    .notification-dropdown::-webkit-scrollbar-thumb {
        background: #eee;
        border-radius: 4px;
    }
    .notification-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 16px;
        border-bottom: 1px solid #f3f3f3;
        font-size: 15px;
        transition: background 0.2s;
    }
    .notification-item:last-child {
        border-bottom: none;
    }
    .notification-item:hover {
        background: #f7f7f7;
    }
    .notification-icon {
        color: #4ca3ff;
        font-size: 18px;
        margin-top: 2px;
    }
    .notification-content {
        flex: 1;
    }
    .notification-title {
        font-weight: 600;
        font-size: 15px;
    }
    .notification-time {
        color: #888;
        font-size: 12px;
        margin-top: 2px;
    }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/Project_Website/ProjectWeb/Views/frontend/partitions/frontend/sidebar.php'; ?>
        
        <div class="main-content">
            <header class="header">
                <button class="sidebar-toggle" id="sidebarToggleBtn" aria-label="Mở menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <div class="header-right" style="display: flex; align-items: center; gap: 1rem; margin-left: auto; position: relative;">
                    <div class="profile">
                        <img src="/Project_Website/ProjectWeb/upload/img/avatar.jpg" alt="Admin Avatar" class="profile-image">
                    </div>
                </div>
            </header>
            <div class="content">
                <div class="page-header">
                    <h1>Báo cáo thống kê</h1>
                </div>

                <!-- Date Range Filter -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" action="index.php">
                            <input type="hidden" name="controller" value="adminreport">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Từ ngày</label>
                                    <input type="date" name="start_date" class="form-control" value="<?= $startDate ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Đến ngày</label>
                                    <input type="date" name="end_date" class="form-control" value="<?= $endDate ?>">
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Xem báo cáo</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Stats Summary -->
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Tổng doanh thu</h6>
                                <h3 class="card-title mb-0"><?= number_format($summaryStats['revenue'] / 1000000, 1) ?>M</h3>
                                <small class="<?= $changes['revenue'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                    <i class="fas fa-arrow-<?= $changes['revenue'] >= 0 ? 'up' : 'down' ?>"></i> 
                                    <?= abs($changes['revenue']) ?>% so với kỳ trước
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Số đơn hàng</h6>
                                <h3 class="card-title mb-0"><?= $summaryStats['orders'] ?></h3>
                                <small class="<?= $changes['orders'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                    <i class="fas fa-arrow-<?= $changes['orders'] >= 0 ? 'up' : 'down' ?>"></i> 
                                    <?= abs($changes['orders']) ?>% so với kỳ trước
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Khách hàng mới</h6>
                                <h3 class="card-title mb-0"><?= $summaryStats['customers'] ?></h3>
                                <small class="<?= $changes['customers'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                    <i class="fas fa-arrow-<?= $changes['customers'] >= 0 ? 'up' : 'down' ?>"></i> 
                                    <?= abs($changes['customers']) ?>% so với kỳ trước
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Tỷ lệ chuyển đổi</h6>
                                <h3 class="card-title mb-0"><?= number_format($summaryStats['conversion'], 1) ?>%</h3>
                                <small class="<?= $changes['conversion'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                    <i class="fas fa-arrow-<?= $changes['conversion'] >= 0 ? 'up' : 'down' ?>"></i> 
                                    <?= abs($changes['conversion']) ?>% so với kỳ trước
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Report -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Báo cáo doanh số</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Ngày</th>
                                        <th>Số đơn hàng</th>
                                        <th>Doanh thu</th>
                                        <th>Chi phí</th>
                                        <th>Lợi nhuận</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($salesReport as $report): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($report['date'])) ?></td>
                                        <td><?= $report['order_count'] ?></td>
                                        <td><?= number_format($report['revenue'] / 1000000, 1) ?>M</td>
                                        <td><?= number_format($report['expense'] / 1000000, 1) ?>M</td>
                                        <td><?= number_format($report['profit'] / 1000000, 1) ?>M</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <td><strong>Tổng</strong></td>
                                        <td><strong><?= $salesTotal['total_orders'] ?></strong></td>
                                        <td><strong><?= number_format($salesTotal['total_revenue'] / 1000000, 1) ?>M</strong></td>
                                        <td><strong><?= number_format($salesTotal['total_expense'] / 1000000, 1) ?>M</strong></td>
                                        <td><strong><?= number_format($salesTotal['total_profit'] / 1000000, 1) ?>M</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Top Products -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Top sản phẩm bán chạy</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Số lượng bán</th>
                                        <th>Doanh thu</th>
                                        <th>Tỷ lệ hoàn trả</th>
                                        <th>Đánh giá TB</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($topProducts as $product): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($product['product_name']) ?></td>
                                        <td><?= $product['total_sold'] ?></td>
                                        <td><?= number_format($product['total_revenue'] / 1000000, 1) ?>M</td>
                                        <td><?= $product['return_rate'] ?>%</td>
                                        <td><?= number_format($product['avg_rating'], 1) ?>/5</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS with Popper -->
    <script src="/Project_Website/ProjectWeb/layout/jsBootstrap/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Chart.js plugin for chart labels -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <!-- Custom JavaScript -->
    <script src="/Project_Website/ProjectWeb/layout/js/Admin.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Khai báo biến toàn cục cho biểu đồ
        let revenueChart = null;
        let orderPieChart = null;
        let isModalShown = false;

        // Khởi tạo modal
        const reportDetailModal = new bootstrap.Modal(document.getElementById('reportDetailModal'));

        // Xử lý sự kiện click nút "Xem báo cáo"
        const btnReport = document.querySelector('button.btn.btn-primary.w-100, button.btn.btn-primary');
        if (btnReport) {
            btnReport.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Cập nhật số liệu thống kê
                updateStatistics();
                
                // Hiển thị modal chi tiết báo cáo
                reportDetailModal.show();
            });
        }

        // Hàm cập nhật số liệu thống kê (demo)
        function updateStatistics() {
            const stats = [
                {
                    revenue: '52.3M',
                    revenueChange: '+12% so với tháng trước',
                    revenueClass: 'text-success',
                    orders: '180',
                    ordersChange: '+20% so với tháng trước',
                    ordersClass: 'text-success',
                    customers: '320',
                    customersChange: '+18% so với tháng trước',
                    customersClass: 'text-success',
                    conversion: '3.8%',
                    conversionChange: '+0.6% so với tháng trước',
                    conversionClass: 'text-success'
                },
                {
                    revenue: '45.6M',
                    revenueChange: '+8% so với tháng trước',
                    revenueClass: 'text-success',
                    orders: '150',
                    ordersChange: '+12% so với tháng trước',
                    ordersClass: 'text-success',
                    customers: '280',
                    customersChange: '+15% so với tháng trước',
                    customersClass: 'text-success',
                    conversion: '3.2%',
                    conversionChange: '-2% so với tháng trước',
                    conversionClass: 'text-danger'
                }
            ];

            const stat = stats[Math.floor(Math.random() * stats.length)];
            
            // Cập nhật số liệu
            document.querySelectorAll('.card-title.mb-0')[0].textContent = stat.revenue;
            document.querySelectorAll('.text-success, .text-danger')[0].textContent = stat.revenueChange;
            document.querySelectorAll('.card-title.mb-0')[1].textContent = stat.orders;
            document.querySelectorAll('.text-success, .text-danger')[1].textContent = stat.ordersChange;
            document.querySelectorAll('.card-title.mb-0')[2].textContent = stat.customers;
            document.querySelectorAll('.text-success, .text-danger')[2].textContent = stat.customersChange;
            document.querySelectorAll('.card-title.mb-0')[3].textContent = stat.conversion;
            document.querySelectorAll('.text-success, .text-danger')[3].textContent = stat.conversionChange;
            
            const conversionChangeEl = document.querySelectorAll('.text-success, .text-danger')[3];
            conversionChangeEl.className = stat.conversionClass;
        }

        // Xử lý sự kiện khi modal được hiển thị
        document.getElementById('reportDetailModal').addEventListener('shown.bs.modal', function () {
            if (isModalShown) return;
            isModalShown = true;

            // Hủy biểu đồ cũ nếu có
            if (revenueChart) revenueChart.destroy();
            if (orderPieChart) orderPieChart.destroy();

            // Dữ liệu cho biểu đồ từ PHP controller
            const chartData = <?= $chartData ?>;
            const pieChartData = <?= $pieChartData ?>;

            // Dữ liệu biểu đồ đường
            const revenueData = {
                labels: chartData.labels,
                datasets: [{
                    label: 'Doanh thu (triệu)',
                    data: chartData.values,
                    borderColor: '#4ca3ff',
                    backgroundColor: 'rgba(76,163,255,0.1)',
                    tension: 0.3,
                    fill: true
                }]
            };

            // Dữ liệu biểu đồ trạng thái đơn hàng
            const orderPieData = {
                labels: pieChartData.labels,
                datasets: [{
                    data: pieChartData.values,
                    backgroundColor: pieChartData.colors
                }]
            };

            // Vẽ biểu đồ đường
            const ctxLine = document.getElementById('revenueLineChart').getContext('2d');
            revenueChart = new Chart(ctxLine, {
                type: 'line',
                data: revenueData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        datalabels: {
                            align: 'top',
                            anchor: 'end',
                            color: '#1976d2',
                            font: { weight: 'bold', size: 14 },
                            formatter: (value) => value
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            // Vẽ biểu đồ tròn
            const ctxPie = document.getElementById('orderPieChart').getContext('2d');
            orderPieChart = new Chart(ctxPie, {
                type: 'pie',
                data: orderPieData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { 
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 15
                            }
                        },
                        datalabels: {
                            color: '#222',
                            font: { weight: 'bold', size: 16 },
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percent = (value / total * 100).toFixed(1) + '%';
                                return percent;
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        });

        // Xử lý sự kiện khi modal bị đóng
        document.getElementById('reportDetailModal').addEventListener('hidden.bs.modal', function () {
            isModalShown = false;
            // Hủy biểu đồ khi đóng modal
            if (revenueChart) revenueChart.destroy();
            if (orderPieChart) orderPieChart.destroy();
        });

        // Xử lý menu toggle và các sự kiện khác
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        
        if (sidebarToggleBtn && sidebar) {
            sidebarToggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
        }
        
        if (sidebarCloseBtn && sidebar) {
            sidebarCloseBtn.addEventListener('click', function() {
                sidebar.classList.remove('active');
            });
        }

    });
    </script>
    
    <!-- Modal Chi Tiết Báo Cáo -->
    <div class="modal fade" id="reportDetailModal" tabindex="-1" aria-labelledby="reportDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportDetailModalLabel">Chi tiết báo cáo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <h6>Doanh thu theo ngày</h6>
                            <div style="height: 300px;">
                                <canvas id="revenueLineChart"></canvas>
                            </div>
                        </div>
                        <div class="col-12">
                            <h6>Tỷ lệ đơn hàng theo trạng thái</h6>
                            <div style="height: 300px;">
                                <canvas id="orderPieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>