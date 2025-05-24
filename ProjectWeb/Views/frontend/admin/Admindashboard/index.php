<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: ?controller=Adminlogin');
    exit;
}
// Lấy đường dẫn favicon từ cài đặt hoặc mặc định
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
    <title>Dashboard - SR Store</title>
    <!-- Favicon -->
    <link rel="icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="/Project_Website/ProjectWeb/layout/cssBootstrap/bootstrap.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Admin.css">
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/Views/frontend/admin/Admindashboard/responsive.css">
</head>

<body>
    <div class="admin-container">
       

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
                    <div class="notification" id="notificationBell" style="position: relative; cursor: pointer;">
                    </div>
                    <div class="profile">
                        <img src="/Project_Website/ProjectWeb/upload/img/avatar.jpg" alt="Admin Avatar"
                            class="profile-image">
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="content">
                <div class="page-header">
                    <h1>Dashboard</h1>
                </div>

                <!-- Stats Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-details">
                                        <h3><?= $orderNumByMonth ?></h3>
                                        <p>Đơn hàng mới</p>
                                    </div>
                                    <div class="stat-progress">
                                        <?php
                                        echo $orderPercentage >= 100 ?
                                            '<span class="text-success"><i class="fas fa-arrow-up"></i> ' . ($orderPercentage - 100) . '%' . '</span>' :
                                            '<span class="text-danger"><i class="fas fa-arrow-down"></i> ' . $orderPercentage . '%' . '</span>'
                                            ?>
                                        <small>So với tháng trước</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="stat-icon bg-success">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-details">
                                        <h3><?= $revenueByMonth ?></h3>
                                        <p>Doanh thu</p>
                                    </div>
                                    <div class="stat-progress">
                                        <?php
                                        echo $revenuePercentage >= 100 ?
                                            '<span class="text-success"><i class="fas fa-arrow-up"></i> ' . ($revenuePercentage - 100) . '%' . '</span>' :
                                            '<span class="text-danger"><i class="fas fa-arrow-down"></i> ' . $revenuePercentage . '%' . '</span>'
                                            ?>
                                        <small>So với tháng trước</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="stat-icon bg-warning">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-details">
                                        <h3><?= $userByMonthDisplay ?></h3>
                                        <p>Khách hàng mới</p>
                                    </div>
                                    <div class="stat-progress">
                                        <?php
                                        echo $userPercentage >= 100 ?
                                            '<span class="text-success"><i class="fas fa-arrow-up"></i> ' . ($userPercentage - 100) . '%' . '</span>' :
                                            '<span class="text-danger"><i class="fas fa-arrow-down"></i> ' . $userPercentage . '%' . '</span>'
                                            ?>
                                        <small>So với tháng trước</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="stat-icon bg-info">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-details">
                                        <h3><?= $visitByMonthDisplay ?></h3>
                                        <p>Lượt truy cập</p>
                                    </div>
                                    <div class="stat-progress">
                                        <?php
                                        echo $visitPercentage >= 100 ?
                                            '<span class="text-success"><i class="fas fa-arrow-up"></i> ' . ($visitPercentage - 100) . '%' . '</span>' :
                                            '<span class="text-danger"><i class="fas fa-arrow-down"></i> ' . $visitPercentage . '%' . '</span>'
                                            ?>
                                        <small>So với tháng trước</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Đơn hàng gần đây</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive-mobile">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Mã đơn hàng</th>
                                        <th>Khách hàng</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày đặt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // print_r($orderList);
                                    foreach ($orderList as $data) {
                                        echo '
                                        <tr>
                                            <td data-label="Mã đơn hàng"> #ORD0' . $data['id_Order'] . '</td>
                                            <td data-label="Khách hàng">' . $data['name'] . '</td>
                                            <td data-label="Tổng tiền">' . $data['total_amount'] . '</td>';

                                        if ($data['status'] === 'completed') {
                                            echo '<td data-label="Trạng thái"><span class="status completed">Hoàn thành</span></td>';
                                        } elseif ($data['status'] === 'pending') {
                                            echo '<td data-label="Trạng thái"><span class="status pending">Đang xử lý</span></td>';
                                        } elseif ($data['status'] === 'cancelled') {
                                            echo '<td data-label="Trạng thái"><span class="status cancelled">Đã hủy</span></td>';
                                        } elseif ($data['status'] === 'shipping') {
                                            echo '<td data-label="Trạng thái"><span class="status shipping">Đang giao</span></td>';
                                        } else {
                                            echo '<td data-label="Trạng thái"><span class="status unknown">Không xác định</span></td>';
                                        }

                                        echo '
                                            <td data-label="Ngày đặt">' . $data['created_at'] . '</td>
                                        </tr>';

                                    }

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Top Products -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Sản phẩm bán chạy</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive-mobile">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Danh mục</th>
                                        <th>Giá</th>
                                        <th>Đã bán</th>
                                        <th>Doanh thu</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    foreach ($productTopSaleList as $data) {
                                        echo '
                                                <tr>
                                                    <td data-label="Sản phẩm">
                                                        <div style="display: flex; align-items: center; gap: 8px;">
                                                        <div
                                                        style="width: 25px; height: 25px; flex-shrink: 0; border-radius: 4px; overflow: hidden;">
                                                        <img src="/Project_Website/ProjectWeb/upload/img/All-Product/' . $data["main_image"] . '" alt="Product"
                                                            style="width: 100%; height: 100%; object-fit: cover;">
                                                        </div>
                                                        <span style="font-size: 13px;">' . $data["name"] . '</span>
                                                        </div>
                                                    </td>
                                                    <td data-label="Danh mục">' . $data['category_name'] . '</td>
                                                    <td data-label="Giá">' . $data['current_price'] . '</td>
                                                    <td data-label="Đã bán">' . $data['sumQuantity'] . '</td>
                                                    <td data-label="Doanh thu">' . $data['sumRevenue'] . '</td>
                                                </tr>
                                            ';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS with Popper -->
    <script src="/Project_Website/ProjectWeb/layout/jsBootstrap/bootstrap.js"></script>
    <!-- Custom JavaScript -->

</body>

</html>