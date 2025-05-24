<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: ?controller=Adminlogin');
    exit;
}
require_once 'Controllers/FooterController.php';
$footerController = new FooterController();
$storeSettings = $footerController->getStoreSettings();
$faviconPath = !empty($storeSettings['favicon_path']) ? $storeSettings['favicon_path'] : '/Project_Website/ProjectWeb/upload/img/Header/favicon.ico';

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo thống kê</title>
    <!-- Favicon -->
    <link rel="icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">
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

        /* Thêm style mới cho table */
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: middle;
        }

        .table-primary {
            background-color: #cfe2ff;
        }

        .table-primary th {
            background-color: #0d6efd;
            color: white;
        }

        .table-light {
            background-color: #f8f9fa;
        }

        /* Style cho card */
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            padding: 1rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        /* Style cho chart container */
        .chart-container {
            background-color: #fff;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        /* Style cho table compact */
        .table-compact th,
        .table-compact td {
            padding: 0.3rem 0.5rem;
            font-size: 0.9rem;
        }

        /* Style cho các nút xuất dữ liệu */
        .btn-export {
            margin-left: 0.5rem;
            border-radius: 0.25rem;
            transition: all 0.2s;
        }

        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        /* Style responsive cho mobile */
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }

            .table-responsive .table {
                min-width: 650px;
            }
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
                <div class="header-right"
                    style="display: flex; align-items: center; gap: 1rem; margin-left: auto; position: relative;">
                    <div class="profile">
                        <img src="/Project_Website/ProjectWeb/upload/img/avatar.jpg" alt="Admin Avatar"
                            class="profile-image">
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
                                    <label class="form-label">Tháng</label>
                                    <select name="month" class="form-select" id="month">
                                        <?php for ($i = 1; $i <= 12; $i++): ?>
                                            <option value="<?= $i ?>" <?= isset($_GET['month']) && $_GET['month'] == $i ? 'selected' : (date('n') == $i && !isset($_GET['month']) ? 'selected' : '') ?>>
                                                Tháng <?= $i ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Năm</label>
                                    <select name="year" class="form-select" id="year">
                                        <?php for ($i = date('Y') - 20; $i <= date('Y'); $i++): ?>
                                            <option value="<?= $i ?>" <?= isset($_GET['year']) && $_GET['year'] == $i ? 'selected' : (date('Y') == $i && !isset($_GET['year']) ? 'selected' : '') ?>>
                                                <?= $i ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Xem báo cáo</button>
                                </div>
                            </div>
                            <!-- Dùng hidden fields để lưu ngày tháng tính toán bên trong controller -->
                            <input type="hidden" name="start_date" id="start_date" value="<?= $startDate ?>">
                            <input type="hidden" name="end_date" id="end_date" value="<?= $endDate ?>">
                        </form>
                    </div>
                </div>

                <!-- Stats Summary -->
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Tổng doanh thu</h6>
                                <h3 class="card-title mb-0"><?= number_format($summaryStats['revenue'] / 1000000, 1) ?>M
                                </h3>
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

                <!-- Action Buttons -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary w-100" id="btnViewChart">
                            <i class="fas fa-chart-line me-2"></i>Xem biểu đồ
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-danger w-100" id="btnExportPDFWithCharts">
                            <i class="fas fa-file-pdf me-2"></i>Xuất PDF với biểu đồ
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-success w-100" id="btnExportExcel">
                            <i class="fas fa-file-excel me-2"></i>Xuất Excel
                        </button>
                    </div>
                </div>

                <!-- Sales Report -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Báo cáo doanh số</h5>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="toggleTableView">
                                <i class="fas fa-arrows-alt-h"></i> Chuyển đổi hiển thị
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered" id="salesReportTable">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-center">Ngày</th>
                                        <th class="text-center">Số đơn hàng</th>
                                        <th class="text-center">Doanh thu</th>
                                        <th class="text-center">Chi phí</th>
                                        <th class="text-center">Lợi nhuận</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($salesReport as $report): ?>
                                        <tr>
                                            <td class="text-center"><?= date('d/m/Y', strtotime($report['date'])) ?></td>
                                            <td class="text-center"><?= $report['order_count'] ?></td>
                                            <td class="text-center"><?= number_format($report['revenue'] / 1000000, 1) ?>M
                                            </td>
                                            <td class="text-center"><?= number_format($report['expense'] / 1000000, 1) ?>M
                                            </td>
                                            <td class="text-center"><?= number_format($report['profit'] / 1000000, 1) ?>M
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td class="text-center"><strong>Tổng</strong></td>
                                        <td class="text-center"><strong><?= $salesTotal['total_orders'] ?></strong></td>
                                        <td class="text-center">
                                            <strong><?= number_format($salesTotal['total_revenue'] / 1000000, 1) ?>M</strong>
                                        </td>
                                        <td class="text-center">
                                            <strong><?= number_format($salesTotal['total_expense'] / 1000000, 1) ?>M</strong>
                                        </td>
                                        <td class="text-center">
                                            <strong><?= number_format($salesTotal['total_profit'] / 1000000, 1) ?>M</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <!-- Top Products Card -->
                    <div class="col-12 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Top sản phẩm bán chạy</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead class="table-primary">
                                            <tr>
                                                <th class="text-center">Sản phẩm</th>
                                                <th class="text-center">Số lượng</th>
                                                <th class="text-center">Doanh thu</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($topProducts as $product): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($product['product_name']) ?></td>
                                                    <td class="text-center"><?= $product['total_sold'] ?></td>
                                                    <td class="text-center">
                                                        <?= number_format($product['total_revenue'] / 1000000, 1) ?>M
                                                    </td>
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
        </div>
    </div>

    <!-- Bootstrap JS with Popper -->
    <script src="/Project_Website/ProjectWeb/layout/jsBootstrap/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Chart.js plugin for chart labels -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="/Project_Website/ProjectWeb/layout/js/Admin.js"></script>
    <!-- Chart.js plugin for image export -->
    <script>
        // Cấu hình Chart.js để hỗ trợ xuất hình ảnh tốt hơn
        Chart.defaults.font.family = "'Arial', sans-serif";
        Chart.defaults.animation.duration = 1000;
        Chart.defaults.plugins.legend.labels.usePointStyle = true;
        Chart.defaults.plugins.legend.labels.padding = 15;

        // Đảm bảo rằng canvas có thể xuất thành hình ảnh
        Chart.register({
            id: 'customCanvasBackgroundColor',
            beforeDraw: (chart) => {
                const ctx = chart.canvas.getContext('2d');
                ctx.save();
                ctx.globalCompositeOperation = 'destination-over';
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, chart.width, chart.height);
                ctx.restore();
            }
        });
    </script>
    <!-- Custom JavaScript -->
    <script src="/Project_Website/ProjectWeb/layout/js/Admin.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Khai báo biến toàn cục cho biểu đồ
            let revenueChart = null;
            let orderPieChart = null;

            // Khởi tạo modal
            const chartModal = new bootstrap.Modal(document.getElementById('chartModal'));

            // Xử lý nút xem biểu đồ
            document.getElementById('btnViewChart').addEventListener('click', function () {
                chartModal.show();
                renderCharts();
            });

            // Xử lý nút xuất PDF với biểu đồ
            document.getElementById('btnExportPDFWithCharts').addEventListener('click', function () {
                exportToPDF();
            });

            // Xử lý nút xuất Excel
            document.getElementById('btnExportExcel').addEventListener('click', function () {
                exportTableToExcel('salesReportTable', 'BaoCaoDoanhSo_<?= date("dmY", strtotime($startDate)) ?>_<?= date("dmY", strtotime($endDate)) ?>');
            });

            // Xử lý nút xuất PDF trong modal
            document.getElementById('btnExportPDF').addEventListener('click', function () {
                exportToPDF();
            });

            // Hàm vẽ biểu đồ
            function renderCharts() {
                // Dữ liệu cho biểu đồ từ PHP controller
                const chartData = <?= $chartData ?>;
                const pieChartData = <?= $pieChartData ?>;

                console.log('Bắt đầu render biểu đồ...');

                // Vẽ biểu đồ đường - doanh thu
                renderRevenueChart(chartData);

                // Vẽ biểu đồ trạng thái đơn hàng
                renderOrderStatusChart(pieChartData);

                // Thêm sự kiện lắng nghe khi các biểu đồ đã hiển thị
                const checkChartsReady = () => {
                    if (revenueChart && orderPieChart) {
                        console.log('Tất cả biểu đồ đã được khởi tạo thành công');
                    }
                };

                // Gọi kiểm tra sau một khoảng thời gian
                setTimeout(checkChartsReady, 300);
            }

            // Vẽ biểu đồ doanh thu
            function renderRevenueChart(chartData) {
                // Hủy biểu đồ cũ nếu có
                if (revenueChart) revenueChart.destroy();

                const ctx = document.getElementById('revenueChart').getContext('2d');

                // Dữ liệu biểu đồ đường
                const revenueData = {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Doanh thu (triệu)',
                        data: chartData.values,
                        borderColor: '#4ca3ff',
                        backgroundColor: 'rgba(76,163,255,0.1)',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 3,
                        pointRadius: 5,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4ca3ff',
                        pointBorderWidth: 2,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#ffffff',
                        pointHoverBorderColor: '#4ca3ff',
                        pointHoverBorderWidth: 3
                    }]
                };

                // Tạo biểu đồ mới
                revenueChart = new Chart(ctx, {
                    type: 'line',
                    data: revenueData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            datalabels: {
                                align: 'top',
                                anchor: 'end',
                                color: '#1976d2',
                                font: {
                                    weight: 'bold',
                                    size: 14
                                },
                                formatter: (value) => value,
                                padding: 6
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                                titleFont: {
                                    size: 14
                                },
                                bodyFont: {
                                    size: 13
                                },
                                padding: 10,
                                cornerRadius: 5,
                                displayColors: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0,0,0,0.05)'
                                },
                                ticks: {
                                    font: {
                                        size: 12
                                    },
                                    padding: 8
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 12
                                    },
                                    padding: 8
                                }
                            }
                        },
                        animation: {
                            duration: 500
                        },
                        layout: {
                            padding: {
                                top: 20,
                                right: 20,
                                bottom: 20,
                                left: 20
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });

                // Khi chart vẽ xong, ghi log để debug
                revenueChart.options.animation.onComplete = function () {
                    console.log('Biểu đồ doanh thu đã render xong');
                };
            }

            // Vẽ biểu đồ trạng thái đơn hàng
            function renderOrderStatusChart(pieChartData) {
                // Hủy biểu đồ cũ nếu có
                if (orderPieChart) orderPieChart.destroy();

                const ctx = document.getElementById('orderStatusChart').getContext('2d');

                // Tạo biểu đồ mới với cấu hình cải tiến
                orderPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: pieChartData.labels,
                        datasets: [{
                            data: pieChartData.values,
                            backgroundColor: pieChartData.colors,
                            borderWidth: 2,
                            borderColor: '#fff',
                            hoverBorderWidth: 3,
                            hoverBorderColor: '#fff',
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    padding: 20,
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    },
                                    color: '#333',
                                    generateLabels: function(chart) {
                                        const data = chart.data;
                                        if (data.labels.length && data.datasets.length) {
                                            return data.labels.map((label, i) => ({
                                                text: label,
                                                fillStyle: data.datasets[0].backgroundColor[i],
                                                strokeStyle: '#fff',
                                                lineWidth: 2,
                                                hidden: false,
                                                index: i
                                            }));
                                        }
                                        return [];
                                    }
                                }
                            },
                            datalabels: {
                                color: '#fff',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                },
                                formatter: (value, context) => {
                                    const percentage = ((value / context.dataset.data.reduce((a, b) => a + b, 0)) * 100).toFixed(1) + '%';
                                    return percentage;
                                },
                                textAlign: 'center',
                                textStrokeColor: 'rgba(0, 0, 0, 0.5)',
                                textStrokeWidth: 2,
                                padding: {
                                    top: 5,
                                    bottom: 5
                                },
                                display: true
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleFont: {
                                    size: 16
                                },
                                bodyFont: {
                                    size: 14
                                },
                                padding: 15,
                                cornerRadius: 5,
                                displayColors: true,
                                callbacks: {
                                    label: function(context) {
                                        const value = context.raw;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return ` ${context.label}: ${value} đơn (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        layout: {
                            padding: 20
                        },
                        animation: {
                            animateRotate: true,
                            animateScale: true,
                            duration: 1000
                        }
                    },
                    plugins: [ChartDataLabels]
                });
            }
            // Trong index.php, thêm hàm applyStyle
            function applyStyle(worksheet, row, col, style) {
                const cellRef = XLSX.utils.encode_cell({ r: row, c: col });
                if (!worksheet[cellRef]) {
                    worksheet[cellRef] = { v: "", t: "s" };
                }
                worksheet[cellRef].s = style;
            }
            // Hàm xuất bảng sang Excel với định dạng chuyên nghiệp
            function exportTableToExcel(tableID, filename = '') {
                // Tạo một workbook mới
                const wb = XLSX.utils.book_new();

                // Lấy dữ liệu từ bảng
                const table = document.getElementById(tableID);

                // Tạo thông tin công ty và tiêu đề
                const companyInfo = [
                    ["RS STORE - HỆ THỐNG THỜI TRANG"],
                    ["BÁO CÁO DOANH SỐ CHI TIẾT"],
                    [`Thời gian: ${formatDate(new Date("<?= $startDate ?>"))} - ${formatDate(new Date("<?= $endDate ?>"))}`],
                    [""]
                ];

                // Tạo worksheet từ dữ liệu công ty
                const ws = XLSX.utils.aoa_to_sheet(companyInfo);

                // Lấy dữ liệu từ bảng HTML
                const tableRows = Array.from(table.querySelectorAll('tr'));
                const tableData = tableRows.map(row => {
                    return Array.from(row.cells).map(cell => cell.innerText);
                });

                // Copy dữ liệu từ bảng vào worksheet (sau header)
                tableData.forEach((row, rowIndex) => {
                    row.forEach((cellValue, colIndex) => {
                        const cellAddress = XLSX.utils.encode_cell({ 
                            r: rowIndex + companyInfo.length, 
                            c: colIndex 
                        });
                        ws[cellAddress] = { v: cellValue, t: 's' };
                    });
                });

                // Thiết lập phạm vi dữ liệu
                const range = {
                    s: { r: 0, c: 0 },
                    e: { 
                        r: tableData.length + companyInfo.length - 1,
                        c: tableData[0].length - 1
                    }
                };
                ws['!ref'] = XLSX.utils.encode_range(range);

                // Thiết lập merge cells cho header
                ws['!merges'] = [
                    { s: { r: 0, c: 0 }, e: { r: 0, c: 4 } },
                    { s: { r: 1, c: 0 }, e: { r: 1, c: 4 } },
                    { s: { r: 2, c: 0 }, e: { r: 2, c: 4 } }
                ];

                // Thiết lập độ rộng cột
                ws['!cols'] = [
                    { width: 20 }, // Ngày
                    { width: 15 }, // Số đơn hàng
                    { width: 18 }, // Doanh thu
                    { width: 18 }, // Chi phí
                    { width: 18 }  // Lợi nhuận
                ];

                // Định nghĩa styles
                const styles = {
                    header: {
                        font: { bold: true, color: { rgb: "FFFFFF" } },
                        fill: { fgColor: { rgb: "4472C4" }, patternType: "solid" },
                        alignment: { horizontal: "center", vertical: "center" },
                        border: {
                            top: { style: "medium", color: { rgb: "000000" } },
                            right: { style: "medium", color: { rgb: "000000" } },
                            bottom: { style: "medium", color: { rgb: "000000" } },
                            left: { style: "medium", color: { rgb: "000000" } }
                        }
                    },
                    title: {
                        font: { bold: true, sz: 16, color: { rgb: "1F497D" } },
                        alignment: { horizontal: "center", vertical: "center" },
                        fill: { fgColor: { rgb: "DCE6F1" }, patternType: "solid" }
                    },
                    cell: {
                        alignment: { horizontal: "center", vertical: "center" },
                        border: {
                            top: { style: "thin", color: { rgb: "000000" } },
                            right: { style: "thin", color: { rgb: "000000" } },
                            bottom: { style: "thin", color: { rgb: "000000" } },
                            left: { style: "thin", color: { rgb: "000000" } }
                        }
                    },
                    total: {
                        font: { bold: true },
                        fill: { fgColor: { rgb: "F2F2F2" }, patternType: "solid" },
                        alignment: { horizontal: "center", vertical: "center" },
                        border: {
                            top: { style: "medium", color: { rgb: "000000" } },
                            right: { style: "medium", color: { rgb: "000000" } },
                            bottom: { style: "medium", color: { rgb: "000000" } },
                            left: { style: "medium", color: { rgb: "000000" } }
                        }
                    }
                };

                // Áp dụng style cho tiêu đề
                for (let i = 0; i < 3; i++) {
                    for (let j = 0; j <= 4; j++) {
                        const cell = XLSX.utils.encode_cell({ r: i, c: j });
                        if (!ws[cell]) ws[cell] = { v: "", t: "s" };
                        ws[cell].s = styles.title;
                    }
                }

                // Áp dụng style cho header bảng
                const headerRow = companyInfo.length;
                for (let C = 0; C < tableData[0].length; C++) {
                    const cell = XLSX.utils.encode_cell({ r: headerRow, c: C });
                    if (ws[cell]) ws[cell].s = styles.header;
                }

                // Áp dụng style cho nội dung bảng
                for (let R = headerRow + 1; R < tableData.length + companyInfo.length - 1; R++) {
                    for (let C = 0; C < tableData[0].length; C++) {
                        const cell = XLSX.utils.encode_cell({ r: R, c: C });
                        if (ws[cell]) ws[cell].s = styles.cell;
                    }
                }

                // Áp dụng style cho hàng tổng cộng
                const lastRow = tableData.length + companyInfo.length - 1;
                for (let C = 0; C < tableData[0].length; C++) {
                    const cell = XLSX.utils.encode_cell({ r: lastRow, c: C });
                    if (ws[cell]) ws[cell].s = styles.total;
                }

                // Thêm sheet vào workbook và xuất file
                XLSX.utils.book_append_sheet(wb, ws, "Báo cáo doanh số");
                XLSX.writeFile(wb, filename + '.xlsx');
            }

            // Hàm định dạng ngày
            function formatDate(date) {
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                return `${day}/${month}/${year}`;
            }

            // Hàm xuất PDF
            function exportToPDF() {
                try {
                    // Hiển thị thông báo đang xử lý
                    Swal.fire({
                        title: 'Đang xử lý...',
                        text: 'Vui lòng đợi trong khi chúng tôi tạo file PDF',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Đảm bảo các biểu đồ đã được tạo
                    renderCharts();

                    // Mở modal để biểu đồ có thể render
                    const chartModal = new bootstrap.Modal(document.getElementById('chartModal'));
                    chartModal.show();

                    // Đợi lâu hơn để biểu đồ render hoàn chỉnh - 2 giây
                    setTimeout(() => {
                        try {
                            const chartImages = [];

                            // Chụp biểu đồ doanh thu trực tiếp từ canvas
                            const revenueCanvas = document.getElementById('revenueChart');
                            if (revenueCanvas) {
                                try {
                                    const revenueImage = revenueCanvas.toDataURL('image/png');
                                    chartImages.push({
                                        name: 'revenue',
                                        src: revenueImage
                                    });
                                    console.log('Đã chụp biểu đồ doanh thu thành công');
                                } catch (err) {
                                    console.error('Lỗi khi chụp biểu đồ doanh thu:', err);
                                }
                            } else {
                                console.warn('Không tìm thấy canvas biểu đồ doanh thu');
                            }

                            // Chụp biểu đồ tỷ lệ đơn hàng trực tiếp từ canvas
                            const orderStatusCanvas = document.getElementById('orderStatusChart');
                            if (orderStatusCanvas) {
                                try {
                                    const orderStatusImage = orderStatusCanvas.toDataURL('image/png');
                                    chartImages.push({
                                        name: 'orderStatus',
                                        src: orderStatusImage
                                    });
                                    console.log('Đã chụp biểu đồ trạng thái đơn hàng thành công');
                                } catch (err) {
                                    console.error('Lỗi khi chụp biểu đồ trạng thái đơn hàng:', err);
                                }
                            } else {
                                console.warn('Không tìm thấy canvas biểu đồ trạng thái đơn hàng');
                            }

                            console.log('Đã chụp tất cả biểu đồ, số lượng:', chartImages.length);

                            // Đóng modal sau khi đã có ảnh
                            chartModal.hide();

                            // Tiếp tục tạo PDF với ảnh biểu đồ
                            createPDFWithChartImages(chartImages);
                        } catch (error) {
                            console.error('Lỗi khi xử lý biểu đồ:', error);
                            chartModal.hide();
                            createPDFWithChartImages([]);
                        }
                    }, 2000); // Tăng thời gian chờ lên 2 giây để đảm bảo biểu đồ render hoàn chỉnh
                } catch (error) {
                    console.error('General error in PDF export:', error);
                    // Hiển thị thông báo lỗi nếu có lỗi xảy ra
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Có lỗi xảy ra khi tạo PDF. Vui lòng thử lại sau.'
                    });
                }
            }

            // Hàm tạo PDF với ảnh biểu đồ
            function createPDFWithChartImages(chartImages) {
                console.log('Bắt đầu tạo PDF với', chartImages.length, 'biểu đồ');
                const filename = 'BaoCaoThongKe_<?= date("dmY", strtotime($startDate)) ?>_<?= date("dmY", strtotime($endDate)) ?>.pdf';

                // Tạo container tạm thời để chứa nội dung PDF
                const pdfContainer = document.createElement('div');
                pdfContainer.style.width = '100%';
                pdfContainer.style.padding = '15px';
                pdfContainer.style.backgroundColor = '#fff';

                // Thêm CSS cho container
                pdfContainer.innerHTML = `
                <style>
                    * {
                        font-family: 'Arial', sans-serif;
                    }
                    .pdf-header {
                        text-align: center;
                        margin-bottom: 20px;
                        color: #333;
                        border-bottom: 2px solid #4472C4;
                        padding-bottom: 10px;
                    }
                    .pdf-company-name {
                        font-size: 24px;
                        font-weight: bold;
                        margin: 0;
                        color: #1F497D;
                    }
                    .pdf-report-name {
                        font-size: 22px;
                        font-weight: bold;
                        margin: 10px 0;
                        color: #1F497D;
                    }
                    .pdf-date-range {
                        font-size: 16px;
                        margin: 5px 0 15px;
                        font-weight: bold;
                    }
                    .pdf-section {
                        margin-bottom: 25px;
                        page-break-inside: avoid;
                    }
                    .pdf-section-title {
                        font-size: 18px;
                        font-weight: bold;
                        margin-bottom: 10px;
                        color: #1976d2;
                        border-bottom: 1px solid #4472C4;
                        padding-bottom: 5px;
                    }
                    .pdf-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 15px;
                    }
                    .pdf-table th, .pdf-table td {
                        border: 1px solid #4472C4;
                        padding: 8px;
                        text-align: center;
                    }
                    .pdf-table th {
                        background-color: #4472C4;
                        color: white;
                        font-weight: bold;
                    }
                    .pdf-table tr:nth-child(even) {
                        background-color: #f2f6fc;
                    }
                    .pdf-chart-container {
                        width: 100%;
                        margin: 15px 0 25px;
                        text-align: center;
                        padding: 10px;
                        background-color: #f9f9f9;
                        border-radius: 8px;
                        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                    }
                    .pdf-chart-img {
                        max-width: 100%;
                        height: auto;
                        border: 1px solid #ddd;
                        border-radius: 5px;
                        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                    }
                    .pdf-no-chart {
                        padding: 20px;
                        text-align: center;
                        color: #777;
                        font-style: italic;
                        background-color: #f9f9f9;
                        border: 1px dashed #ddd;
                        border-radius: 5px;
                        margin: 15px 0;
                    }
                    .pdf-footer {
                        margin-top: 30px;
                        text-align: center;
                        font-size: 12px;
                        color: #666;
                        border-top: 1px solid #4472C4;
                        padding-top: 10px;
                    }
                    .stats-container {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 15px;
                        margin-bottom: 20px;
                    }
                    .stat-box {
                        flex: 1;
                        min-width: 200px;
                        padding: 15px;
                        border: 1px solid #4472C4;
                        border-radius: 5px;
                        text-align: center;
                        background-color: #f2f6fc;
                    }
                    .stat-title {
                        font-size: 14px;
                        color: #666;
                        margin-bottom: 5px;
                    }
                    .stat-value {
                        font-size: 20px;
                        font-weight: bold;
                        margin-bottom: 5px;
                        color: #1F497D;
                    }
                    .stat-change {
                        font-size: 12px;
                    }
                    .text-success {
                        color: #28a745;
                    }
                    .text-danger {
                        color: #dc3545;
                    }
                    .analysis-content {
                        background-color: #f9f9f9;
                        padding: 15px;
                        border-radius: 5px;
                        border: 1px solid #ddd;
                    }
                    .analysis-content p {
                        margin-bottom: 15px;
                    }
                    .analysis-content b {
                        color: #1F497D;
                    }
                    @page {
                        margin: 15mm;
                    }
                </style>
            `;

                // Tạo header
                const header = document.createElement('div');
                header.className = 'pdf-header';
                header.innerHTML = `
                <h1 class="pdf-company-name">RS STORE - HỆ THỐNG THỜI TRANG</h1>
                <h2 class="pdf-report-name">BÁO CÁO THỐNG KÊ</h2>
                <p class="pdf-date-range">Thời gian: ${formatDate(new Date("<?= $startDate ?>"))} - ${formatDate(new Date("<?= $endDate ?>"))}</p>
            `;
                pdfContainer.appendChild(header);

                // Thêm thông tin tổng quan
                const summarySection = document.createElement('div');
                summarySection.className = 'pdf-section';
                summarySection.innerHTML = `
                <h3 class="pdf-section-title">Thống kê tổng quan</h3>
                <div class="stats-container">
                    <div class="stat-box">
                        <div class="stat-title">Tổng doanh thu</div>
                        <div class="stat-value"><?= number_format($summaryStats['revenue'] / 1000000, 1) ?>M</div>
                        <div class="stat-change <?= $changes['revenue'] >= 0 ? 'text-success' : 'text-danger' ?>">
                            <i class="fas fa-arrow-<?= $changes['revenue'] >= 0 ? 'up' : 'down' ?>"></i> 
                            <?= abs($changes['revenue']) ?>% so với kỳ trước
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-title">Số đơn hàng</div>
                        <div class="stat-value"><?= $summaryStats['orders'] ?></div>
                        <div class="stat-change <?= $changes['orders'] >= 0 ? 'text-success' : 'text-danger' ?>">
                            <i class="fas fa-arrow-<?= $changes['orders'] >= 0 ? 'up' : 'down' ?>"></i> 
                            <?= abs($changes['orders']) ?>% so với kỳ trước
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-title">Khách hàng mới</div>
                        <div class="stat-value"><?= $summaryStats['customers'] ?></div>
                        <div class="stat-change <?= $changes['customers'] >= 0 ? 'text-success' : 'text-danger' ?>">
                            <i class="fas fa-arrow-<?= $changes['customers'] >= 0 ? 'up' : 'down' ?>"></i> 
                            <?= abs($changes['customers']) ?>% so với kỳ trước
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-title">Tỷ lệ chuyển đổi</div>
                        <div class="stat-value"><?= number_format($summaryStats['conversion'], 1) ?>%</div>
                        <div class="stat-change <?= $changes['conversion'] >= 0 ? 'text-success' : 'text-danger' ?>">
                            <i class="fas fa-arrow-<?= $changes['conversion'] >= 0 ? 'up' : 'down' ?>"></i> 
                            <?= abs($changes['conversion']) ?>% so với kỳ trước
                        </div>
                    </div>
                </div>
            `;
                pdfContainer.appendChild(summarySection);

                // Phần biểu đồ
                const chartsSection = document.createElement('div');
                chartsSection.className = 'pdf-section';
                chartsSection.innerHTML = `<h3 class="pdf-section-title">Biểu đồ thống kê</h3>`;

                // Kiểm tra và thêm biểu đồ doanh thu 
                const revenueImage = chartImages.find(img => img.name === 'revenue');
                if (revenueImage && revenueImage.src && revenueImage.src.length > 100) {
                    console.log('Thêm biểu đồ doanh thu vào PDF');
                    const revenueChartContainer = document.createElement('div');
                    revenueChartContainer.innerHTML = `
                    <h4 style="font-size:16px; margin-top:20px; color:#1976d2;">Doanh thu theo ngày</h4>
                    <div class="pdf-chart-container">
                        <img src="${revenueImage.src}" alt="Biểu đồ doanh thu" class="pdf-chart-img" style="width:100%; max-width:600px;">
                    </div>
                `;
                    chartsSection.appendChild(revenueChartContainer);
                } else {
                    console.warn('Không có biểu đồ doanh thu để thêm vào PDF');
                    const noRevenueChart = document.createElement('div');
                    noRevenueChart.innerHTML = `
                    <h4 style="font-size:16px; margin-top:20px; color:#1976d2;">Doanh thu theo ngày</h4>
                    <div class="pdf-no-chart">
                        <p>Không thể tạo biểu đồ doanh thu</p>
                    </div>
                `;
                    chartsSection.appendChild(noRevenueChart);
                }

                // Kiểm tra và thêm biểu đồ trạng thái đơn hàng
                const orderStatusImage = chartImages.find(img => img.name === 'orderStatus');
                if (orderStatusImage && orderStatusImage.src && orderStatusImage.src.length > 100) {
                    console.log('Thêm biểu đồ trạng thái đơn hàng vào PDF');
                    const orderStatusChartContainer = document.createElement('div');
                    orderStatusChartContainer.innerHTML = `
                    <h4 style="font-size:16px; margin-top:20px; color:#1976d2;">Tỷ lệ đơn hàng theo trạng thái</h4>
                    <div class="pdf-chart-container">
                        <img src="${orderStatusImage.src}" alt="Biểu đồ trạng thái đơn hàng" class="pdf-chart-img" style="width:100%; max-width:600px;">
                    </div>
                `;
                    chartsSection.appendChild(orderStatusChartContainer);
                } else {
                    console.warn('Không có biểu đồ trạng thái đơn hàng để thêm vào PDF');
                    const noOrderStatusChart = document.createElement('div');
                    noOrderStatusChart.innerHTML = `
                    <h4 style="font-size:16px; margin-top:20px; color:#1976d2;">Tỷ lệ đơn hàng theo trạng thái</h4>
                    <div class="pdf-no-chart">
                        <p>Không thể tạo biểu đồ trạng thái đơn hàng</p>
                    </div>
                `;
                    chartsSection.appendChild(noOrderStatusChart);
                }

                pdfContainer.appendChild(chartsSection);

                // Thêm bảng báo cáo doanh thu
                const revenueSection = document.createElement('div');
                revenueSection.className = 'pdf-section';
                revenueSection.innerHTML = `
                <h3 class="pdf-section-title">Báo cáo doanh số</h3>
                <table class="pdf-table" id="pdfRevenueTable">
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
                        <tr>
                            <td><strong>Tổng</strong></td>
                            <td><strong><?= $salesTotal['total_orders'] ?></strong></td>
                            <td><strong><?= number_format($salesTotal['total_revenue'] / 1000000, 1) ?>M</strong></td>
                            <td><strong><?= number_format($salesTotal['total_expense'] / 1000000, 1) ?>M</strong></td>
                            <td><strong><?= number_format($salesTotal['total_profit'] / 1000000, 1) ?>M</strong></td>
                        </tr>
                    </tfoot>
                </table>
            `;
                pdfContainer.appendChild(revenueSection);

                // Thêm bảng sản phẩm bán chạy
                const productsSection = document.createElement('div');
                productsSection.className = 'pdf-section';
                productsSection.innerHTML = `
                <h3 class="pdf-section-title">Top sản phẩm bán chạy</h3>
                <table class="pdf-table">
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
            `;
                pdfContainer.appendChild(productsSection);

                // Thêm phân tích báo cáo
                const analysisSection = document.createElement('div');
                analysisSection.className = 'pdf-section';
                analysisSection.innerHTML = `
                <h3 class="pdf-section-title">Phân tích báo cáo</h3>
                <div class="analysis-content">
                    <p>
                        <b>Tóm tắt tình hình kinh doanh:</b><br>
                        Trong kỳ báo cáo từ <?= date('d/m/Y', strtotime($startDate)) ?> đến <?= date('d/m/Y', strtotime($endDate)) ?>, 
                        doanh thu đạt <?= number_format($summaryStats['revenue'] / 1000000, 1) ?> triệu đồng 
                        (<?= $changes['revenue'] >= 0 ? 'tăng' : 'giảm' ?> <?= abs($changes['revenue']) ?>% so với kỳ trước).
                    </p>
                    
                    <p>
                        <b>Xu hướng phát triển:</b><br>
                        <?php if ($changes['revenue'] > 0 && $changes['orders'] > 0): ?>
                        Cửa hàng đang có xu hướng tăng trưởng tích cực với doanh thu và số lượng đơn hàng đều tăng.
                        <?php elseif ($changes['revenue'] > 0 && $changes['orders'] <= 0): ?>
                        Doanh thu tăng trong khi số đơn hàng <?= $changes['orders'] == 0 ? 'giữ nguyên' : 'giảm' ?>, 
                        cho thấy giá trị trung bình mỗi đơn hàng đã tăng.
                        <?php elseif ($changes['revenue'] <= 0 && $changes['orders'] > 0): ?>
                        Số lượng đơn hàng tăng nhưng doanh thu <?= $changes['revenue'] == 0 ? 'giữ nguyên' : 'giảm' ?>, 
                        cho thấy giá trị trung bình mỗi đơn hàng đã giảm.
                        <?php else: ?>
                        Cửa hàng đang gặp thách thức khi cả doanh thu và số đơn hàng đều giảm so với kỳ trước.
                        <?php endif; ?>
                    </p>
                    
                    <p>
                        <b>Đề xuất:</b><br>
                        <?php if ($changes['revenue'] < 0 || $changes['orders'] < 0): ?>
                        Cần xem xét các chiến lược marketing và chương trình khuyến mãi để cải thiện tình hình kinh doanh.
                        <?php else: ?>
                        Duy trì và tối ưu hóa các chiến lược marketing hiện tại để tiếp tục tăng trưởng.
                        <?php endif; ?>
                        
                        <?php if ($changes['customers'] < 0): ?>
                        Tập trung vào việc thu hút khách hàng mới thông qua các chiến dịch quảng cáo và chương trình giới thiệu.
                        <?php else: ?>
                        Tiếp tục xây dựng chiến lược giữ chân khách hàng hiện tại, đồng thời mở rộng cơ sở khách hàng mới.
                        <?php endif; ?>
                    </p>
                </div>
            `;
                pdfContainer.appendChild(analysisSection);

                // Thêm footer
                const footer = document.createElement('div');
                footer.className = 'pdf-footer';
                footer.innerHTML = `
                <p>Ngày tạo: ${formatDate(new Date())}</p>
                <p>Người tạo: Admin</p>
                <p>© RS Store - Hệ thống thời trang</p>
            `;
            pdfContainer.appendChild(footer);
            
            // Thêm container tạm thời vào trang
            document.body.appendChild(pdfContainer);
            
            // Cấu hình tùy chọn cho PDF với tải xuống tự động
            const pdfOptions = {
                margin: 10,
                filename: filename,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { 
                    scale: 2, 
                    useCORS: true, 
                    logging: false,
                    allowTaint: true,
                    imageTimeout: 3000 // Tăng thời gian chờ cho việc tải hình ảnh
                },
                jsPDF: { 
                    unit: 'mm', 
                    format: 'a4', 
                    orientation: 'portrait',
                    compress: true
                }
            };
            
            // Tạo PDF từ container và tải xuống tự động
            console.log('Bắt đầu tạo file PDF...');
            html2pdf()
                .from(pdfContainer)
                .set(pdfOptions)
                .toPdf()
                .get('pdf')
                .then((pdf) => {
                    try {
                        console.log('Đã tạo xong PDF, chuẩn bị tải xuống');
                        // Tạo blob từ PDF
                        const blob = pdf.output('blob');
                        
                        // Tạo URL cho blob
                        const url = URL.createObjectURL(blob);
                        
                        // Tạo link tải xuống
                        const downloadLink = document.createElement('a');
                        downloadLink.href = url;
                        downloadLink.download = filename;
                        
                        // Thêm link vào trang và click
                        document.body.appendChild(downloadLink);
                        downloadLink.click();
                        
                        // Dọn dẹp
                        setTimeout(() => {
                            document.body.removeChild(downloadLink);
                            URL.revokeObjectURL(url);
                            document.body.removeChild(pdfContainer);
                            
                            // Đóng thông báo đang xử lý
                            Swal.close();
                            
                            // Xóa backdrop và reset modal
                            const modalBackdrop = document.querySelector('.modal-backdrop');
                            if (modalBackdrop) {
                                modalBackdrop.remove();
                            }
                            document.body.classList.remove('modal-open');
                            document.body.style.overflow = '';
                            document.body.style.paddingRight = '';
                            
                            // Đóng modal chart nếu đang mở
                            const chartModal = bootstrap.Modal.getInstance(document.getElementById('chartModal'));
                            if (chartModal) {
                                chartModal.hide();
                            }
                            
                            // Hiển thị thông báo thành công
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: 'Báo cáo PDF đã được tải xuống',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }, 100);
                    } catch (error) {
                        console.error('Error in PDF download process:', error);
                        handlePdfError(pdfContainer);
                    }
                })
                .catch(err => {
                    console.error('Error exporting PDF', err);
                    handlePdfError(pdfContainer);
                });
        }
        
        // Hàm xử lý lỗi PDF
        function handlePdfError(container) {
            if (document.body.contains(container)) {
                document.body.removeChild(container);
            }
            
            // Xóa backdrop và reset modal
            const modalBackdrop = document.querySelector('.modal-backdrop');
            if (modalBackdrop) {
                modalBackdrop.remove();
            }
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            
            // Đóng modal chart nếu đang mở
            const chartModal = bootstrap.Modal.getInstance(document.getElementById('chartModal'));
            if (chartModal) {
                chartModal.hide();
            }
            
            // Đóng thông báo đang xử lý nếu đang hiển thị
            try {
                Swal.close();
            } catch (e) {
                console.error('Error closing Swal:', e);
            }
            
            // Hiển thị thông báo lỗi
            try {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Không thể tạo file PDF. Vui lòng thử lại sau.'
                });
            } catch (e) {
                console.error('Error displaying Swal error message:', e);
                alert('Không thể tạo file PDF. Vui lòng thử lại sau.');
            }
        }

            // Thêm script để chuyển đổi chế độ xem bảng
            document.getElementById('toggleTableView').addEventListener('click', function () {
                const table = document.getElementById('salesReportTable');
                table.classList.toggle('table-compact');

                if (table.classList.contains('table-compact')) {
                    // Nếu đang ở chế độ compact, áp dụng style compact
                    table.style.fontSize = '0.9rem';
                    const cells = table.querySelectorAll('th, td');
                    cells.forEach(cell => {
                        cell.style.padding = '0.3rem 0.5rem';
                    });
                } else {
                    // Khôi phục lại style mặc định
                    table.style.fontSize = '';
                    const cells = table.querySelectorAll('th, td');
                    cells.forEach(cell => {
                        cell.style.padding = '';
                    });
                }
            });

            // Xử lý nút kiểm tra chụp biểu đồ
            document.getElementById('btnTestChartCapture').addEventListener('click', function () {
                // Chụp biểu đồ
                captureCharts();
            });

            // Hàm chụp và hiển thị biểu đồ để kiểm tra
            function captureCharts() {
                try {
                    // Chụp biểu đồ doanh thu trực tiếp từ canvas
                    const revenueCanvas = document.getElementById('revenueChart');
                    if (revenueCanvas) {
                        try {
                            const revenueImage = revenueCanvas.toDataURL('image/png');
                            showCapturedChart('Biểu đồ doanh thu', revenueImage);
                        } catch (err) {
                            console.error('Lỗi khi chụp biểu đồ doanh thu:', err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: 'Không thể chụp biểu đồ doanh thu: ' + err.message
                            });
                        }
                    } else {
                        console.warn('Không tìm thấy canvas biểu đồ doanh thu');
                        Swal.fire({
                            icon: 'warning',
                            title: 'Cảnh báo',
                            text: 'Không tìm thấy biểu đồ doanh thu'
                        });
                    }

                    // Chụp biểu đồ tỷ lệ đơn hàng trực tiếp từ canvas
                    const orderStatusCanvas = document.getElementById('orderStatusChart');
                    if (orderStatusCanvas) {
                        try {
                            const orderStatusImage = orderStatusCanvas.toDataURL('image/png');
                            showCapturedChart('Biểu đồ trạng thái đơn hàng', orderStatusImage);
                        } catch (err) {
                            console.error('Lỗi khi chụp biểu đồ trạng thái đơn hàng:', err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: 'Không thể chụp biểu đồ trạng thái đơn hàng: ' + err.message
                            });
                        }
                    } else {
                        console.warn('Không tìm thấy canvas biểu đồ trạng thái đơn hàng');
                        Swal.fire({
                            icon: 'warning',
                            title: 'Cảnh báo',
                            text: 'Không tìm thấy biểu đồ trạng thái đơn hàng'
                        });
                    }
                } catch (error) {
                    console.error('Lỗi khi kiểm tra biểu đồ:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Có lỗi xảy ra khi kiểm tra biểu đồ: ' + error.message
                    });
                }
            }

            // Hiển thị biểu đồ đã chụp
            function showCapturedChart(title, imageData) {
                Swal.fire({
                    title: title,
                    html: `<img src="${imageData}" style="max-width:100%; border:1px solid #ddd; border-radius:5px;">`,
                    width: 800,
                    padding: '3em',
                    confirmButtonText: 'Đóng',
                    showClass: {
                        popup: 'animate__animated animate__fadeIn faster'
                    }
                });
            }
        });
    </script>

    <!-- Thư viện xuất Excel with Styling Support -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx-js-style@1.2.0/dist/xlsx.bundle.js"></script>
    <!-- Thư viện xuất PDF với hỗ trợ đầy đủ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <!-- Thư viện SweetAlert2 cho thông báo -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom SweetAlert Config -->
    <script src="/Project_Website/ProjectWeb/layout/js/sweetalert-config.js"></script>
    
    <!-- Modal Chi Tiết Biểu Đồ -->
    <div class="modal fade" id="chartModal" tabindex="-1" aria-labelledby="chartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chartModalLabel">Biểu đồ thống kê -
                        <?= date('m/Y', strtotime($startDate)) ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button id="btnTestChartCapture" class="btn btn-info me-2">
                                <i class="fas fa-camera me-2"></i>Kiểm tra chụp biểu đồ
                            </button>
                            <button id="btnExportPDF" class="btn btn-danger">
                                <i class="fas fa-file-pdf me-2"></i>Xuất PDF
                            </button>
                        </div>
                    </div>

                    <div class="chart-container" id="chartContainer">
                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Doanh thu theo ngày</h6>
                                    </div>
                                    <div class="card-body" style="background-color: white; padding: 15px;">
                                        <div style="height: 300px; position: relative;">
                                            <canvas id="revenueChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Tỷ lệ đơn hàng theo trạng thái</h6>
                                    </div>
                                    <div class="card-body" style="background-color: white; padding: 15px;">
                                        <div style="height: 300px; position: relative;">
                                            <canvas id="orderStatusChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Thống kê tổng quan</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Chỉ số</th>
                                                <th>Giá trị</th>
                                                <th>So với kỳ trước</th>
                                            </tr>
                                            <tr>
                                                <td>Doanh thu</td>
                                                <td><?= number_format($summaryStats['revenue'] / 1000000, 1) ?>M</td>
                                                <td
                                                    class="<?= $changes['revenue'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                    <i
                                                        class="fas fa-arrow-<?= $changes['revenue'] >= 0 ? 'up' : 'down' ?>"></i>
                                                    <?= abs($changes['revenue']) ?>%
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Số đơn hàng</td>
                                                <td><?= $summaryStats['orders'] ?></td>
                                                <td
                                                    class="<?= $changes['orders'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                    <i
                                                        class="fas fa-arrow-<?= $changes['orders'] >= 0 ? 'up' : 'down' ?>"></i>
                                                    <?= abs($changes['orders']) ?>%
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Khách hàng mới</td>
                                                <td><?= $summaryStats['customers'] ?></td>
                                                <td
                                                    class="<?= $changes['customers'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                    <i
                                                        class="fas fa-arrow-<?= $changes['customers'] >= 0 ? 'up' : 'down' ?>"></i>
                                                    <?= abs($changes['customers']) ?>%
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tỷ lệ chuyển đổi</td>
                                                <td><?= number_format($summaryStats['conversion'], 1) ?>%</td>
                                                <td
                                                    class="<?= $changes['conversion'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                    <i
                                                        class="fas fa-arrow-<?= $changes['conversion'] >= 0 ? 'up' : 'down' ?>"></i>
                                                    <?= abs($changes['conversion']) ?>%
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>