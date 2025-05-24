<?php
// Thêm vào đầu file để lấy thông tin cài đặt
require_once 'Controllers/FooterController.php';
$footerController = new FooterController();
$storeSettings = $footerController->getStoreSettings();
$siteName = $storeSettings['site_name'] ?? 'RSStore';
$faviconPath = !empty($storeSettings['favicon_path']) ? $storeSettings['favicon_path'] : '/Project_Website/ProjectWeb/upload/img/Header/favicon.ico';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài khoản - 160STORE</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <!-- Favicon -->
    <link rel="icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
      <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Footer.css">
    <style>
        :root {
            --primary-color: #000000;
            --secondary-color: #ffffff;
            --accent-color: #212529;
            --text-color: #212121;
            --text-light: #757575;
            --border-color: #e0e0e0;
            --bg-color: #f0f0f0;
        }
        /* Modal */
.modal-content {
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.modal-title {
    font-size: 1.4rem;
    font-weight: 500;
    color: #333;
}

.modal-body {
    padding: 20px;
    max-height: 70vh;
    overflow-y: auto;
}

/* Card trong modal */
.order-detail-card {
    border: 1px solid #e9ecef;
    border-radius: 6px;
    margin-bottom: 15px;
}

.order-detail-card h6 {
    font-size: 1.1rem;
    font-weight: 500;
    color: #333;
    margin: 0;
    padding: 10px;
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.order-detail-card .card-body {
    padding: 15px;
}

/* Bảng sản phẩm */
.order-detail-table {
    margin-bottom: 0;
    font-size: 0.95rem;
}

.order-detail-table th {
    font-weight: 500;
    color: #555;
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.order-detail-table td {
    vertical-align: middle;
    color: #333;
}

.order-detail-table img {
    border-radius: 4px;
    width: 40px;
    height: 40px;
    object-fit: cover;
}

/* Badge */
.badge {
    font-size: 0.8rem;
    padding: 5px 10px;
    border-radius: 10px;
}

.badge.bg-warning {
    background-color: #ffc107;
    color: #333;
}

.badge.bg-success {
    background-color: #28a745;
    color: #fff;
}

.badge.bg-danger {
    background-color: #dc3545;
    color: #fff;
}

/* Nút */
.btn {
    border-radius: 4px;
    padding: 6px 12px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-outline-dark {
    border-color: #333;
    color: #333;
}

.btn-outline-dark:hover {
    background-color: #333;
    color: #fff;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
}

/* Responsive */
@media (max-width: 576px) {
    .modal-dialog {
        margin: 10px;
    }

    .modal-title {
        font-size: 1.2rem;
    }

    .order-detail-table {
        font-size: 0.85rem;
    }

    .order-detail-table img {
        width: 30px;
        height: 30px;
    }

    .btn {
        padding: 5px 10px;
        font-size: 0.85rem;
    }
}
        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            padding: 0;
            margin: 0;
        }

        .container {
            max-width: 1440px;
            padding: 0 20px;
        }

        main.container {
            background-color: var(--bg-color);
            padding: 20px;
            min-height: 100vh;
        }

        .row {
            margin: 0;
        }

        .account-sidebar {
            background-color: var(--secondary-color);
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 20px;
            height: fit-content;
            padding: 20px;
            width: 100%;
        }

        .account-content {
            background-color: var(--secondary-color);
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 25px;
            min-height: calc(100% - 20px); /* Trừ đi margin-bottom */
        }

        /* Đảm bảo bên phải có chiều cao tương đương với sidebar */
        .account-content-wrapper {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .user-avatar {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border: 3px solid var(--border-color);
        }

        .avatar-wrapper {
            position: relative;
            display: inline-block;
            margin-top: 15px;
        }

        .avatar-edit {
            position: absolute;
            right: 5px;
            bottom: 5px;
            width: 32px;
            height: 32px;
            background-color: var(--primary-color);
            color: var(--secondary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
        }

        .user-info {
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 20px;
        }

        .user-name {
            font-size: 22px;
            margin-top: 10px;
        }

        .user-email {
            font-size: 15px;
        }

        .account-menu {
            padding-top: 15px;
        }

        .account-menu .nav-link {
            color: var(--text-color);
            padding: 12px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 16px;
        }

        .account-menu .nav-link:hover, 
        .account-menu .nav-link.active {
            background-color: var(--primary-color);
            color: var(--secondary-color);
        }

        .account-menu .nav-link i {
            width: 24px;
            text-align: center;
            font-size: 18px;
        }

        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 25px;
            font-weight: 600;
            font-size: 24px;
            color: var(--accent-color);
            border-bottom: 2px solid var(--border-color);
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 80px;
            height: 2px;
            background-color: var(--primary-color);
        }

        .btn {
            padding: 10px 24px;
            font-size: 16px;
            border-radius: 5px;
        }

        .btn-sm {
            padding: 6px 14px;
            font-size: 14px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        /* Hide all content sections by default */
        .content-section {
            display: none;
            height: 100%;
        }

        /* Show active section */
        .content-section.active {
            display: block;
        }

        /* Card styles */
        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid var(--border-color);
            margin-bottom: 20px;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
        }

        .table {
            margin-bottom: 0;
            font-size: 15px;
        }

        .table th {
            font-weight: 600;
            border-top: none;
            padding: 15px 12px;
            font-size: 16px;
        }

        .table td {
            padding: 15px 12px;
            vertical-align: middle;
        }

        /* Form elements */
        .form-control {
            padding: 12px 15px;
            font-size: 15px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(33, 37, 41, 0.15);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .form-check-label {
            font-size: 15px;
        }

        /* Responsive adjustments */
        @media (max-width: 767px) {
            .account-sidebar {
                margin-bottom: 20px;
                position: static;
            }
            
            .container {
                padding: 10px;
            }
        }

        /* Product cards in viewed/wishlist */
        .product-card-img {
            height: 220px;
            object-fit: cover;
        }

        /* Order status badges */
        .badge {
            font-weight: normal;
            padding: 7px 12px;
            font-size: 14px;
        }

        /* Links and buttons */
        a {
            color: var(--accent-color);
            text-decoration: none;
        }

        a:hover {
            color: var(--primary-color);
        }

        .btn-outline-dark {
            border-color: var(--accent-color);
            color: var(--accent-color);
        }

        .btn-outline-dark:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--secondary-color);
        }

        /* Fix cho chiều cao các phần */
        .col-md-9, .col-md-3 {
            display: flex;
            flex-direction: column;
        }

        .account-sidebar {
            height: 100%;
        }
    </style>
</head>
<body>
     <?php
    view('frontend.partitions.frontend.header');
    ?>
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Header.css">
    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="container mt-3">
            <div class="alert alert-<?= $_SESSION['flash_message']['type'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['flash_message']['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="account-sidebar">
                    <div class="user-info text-center">
                        <div class="avatar-wrapper mb-3">
                           
                            <img src="/Project_Website/ProjectWeb/upload/img/avatars/image.png" alt="Avatar" class="user-avatar rounded-circle">
                            <!-- <label for="avatar-upload" class="avatar-edit" title="Thay đổi ảnh đại diện">
                                <i class="fas fa-camera"></i>
                            </label>
                            <form id="avatar-form" action="index.php?controller=account&action=uploadAvatar" method="POST" enctype="multipart/form-data" style="display: none;">
                                <input type="file" id="avatar-upload" name="avatar" accept="image/*" onchange="document.getElementById('avatar-form').submit();">
                            </form> -->
                        </div>
                        <h5 class="user-name mb-2"><?= isset($user['name']) ? htmlspecialchars($user['name']) : 'Người dùng' ?></h5>
                        <p class="user-email text-muted mb-0"><?= isset($user['email']) ? htmlspecialchars($user['email']) : '' ?></p>
                    </div>
                    <ul class="nav flex-column account-menu">
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentTab == 'profile') ? 'active' : '' ?>" href="#profile-info" data-section="profile-section">
                                <i class="fas fa-user me-2"></i> Thông tin cá nhân
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentTab == 'orders') ? 'active' : '' ?>" href="#order-history" data-section="orders-section">
                                <i class="fas fa-shopping-bag me-2"></i> Đơn hàng của tôi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentTab == 'address') ? 'active' : '' ?>" href="#address-book" data-section="address-section">
                                <i class="fas fa-map-marker-alt me-2"></i> Sổ địa chỉ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentTab == 'password') ? 'active' : '' ?>" href="#change-password" data-section="password-section">
                                <i class="fas fa-lock me-2"></i> Đổi mật khẩu
                            </a>
                        </li>
                        <li class="nav-item mt-3">
                            <a class="nav-link text-danger" href="index.php?controller=account&action=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?')">
                                <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <div class="account-content-wrapper">
                    <!-- Profile Section -->
                    <div id="profile-section" class="account-content content-section <?= ($currentTab == 'profile') ? 'active' : '' ?>">
                        <h4 class="section-title">Thông tin cá nhân</h4>
                        <form id="profile-form" action="index.php?controller=account&action=updateProfile" method="POST">
                           <div class="row g-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= isset($user['name']) ? htmlspecialchars($user['name']) : '' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="<?= isset($user['phone']) ? htmlspecialchars($user['phone']) : '' ?>" required>
                    </div>
                    <div class="col-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" value="<?= isset($user['email']) ? htmlspecialchars($user['email']) : '' ?>" readonly>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-dark">Lưu thay đổi</button>
                    </div>
                </div>
                        </form>
                    </div>
                    
                    <!-- Orders Section -->
                    <div id="orders-section" class="account-content content-section <?= ($currentTab == 'orders') ? 'active' : '' ?>">
                        <h4 class="section-title">Đơn hàng của tôi</h4>
                        <?php if (empty($orders)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-shopping-bag fa-4x mb-3 text-muted"></i>
                                <h5>Bạn chưa có đơn hàng nào</h5>
                                <p class="text-muted">Hãy mua sắm và quay lại đây để xem lịch sử đơn hàng của bạn.</p>
                                <a href="index.php?controller=home&action=index" class="btn btn-primary mt-3">Mua sắm ngay</a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Mã đơn hàng</th>
                                            <th>Ngày đặt</th>
                                            <th>Tổng tiền</th>
                                            <th>Trạng thái</th>
                                            <th class="text-center">Chi tiết</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td><strong><?= $order['order_number'] ?? 'ORD-' . $order['id_Order'] ?></strong></td>
                                                <td><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                                                <td class="text-danger fw-bold"><?= number_format($order['total_amount'], 0, ',', '.') ?>đ</td>
                                                <td>
                                                    <?php 
                                                    $statusClass = '';
                                                    $statusText = '';
                                                    
                                                    switch ($order['status']) {
                                                        case 'pending':
                                                            $statusClass = 'bg-warning text-dark';
                                                            $statusText = 'Đang xử lý';
                                                            break;
                                                        case 'waitConfirm':
                                                            $statusClass = 'bg-info text-white';
                                                            $statusText = 'Chờ xác nhận';
                                                            break;
                                                        case 'shipping':
                                                            $statusClass = 'bg-primary';
                                                            $statusText = 'Đang giao';
                                                            break;
                                                        case 'completed':
                                                            $statusClass = 'bg-success';
                                                            $statusText = 'Đã giao';
                                                            break;
                                                        case 'cancelled':
                                                            $statusClass = 'bg-danger';
                                                            $statusText = 'Đã hủy';
                                                            break;
                                                        default:
                                                            $statusClass = 'bg-secondary';
                                                            $statusText = 'Không xác định';
                                                        }
                                                        ?>
                                                    <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-outline-dark view-order-btn" 
                                                                     data-order-id="<?= htmlspecialchars($order['id_Order']) ?>" 
                                                                     data-bs-toggle="modal" 
                                                                     data-bs-target="#orderDetailModal">
                                                                 Xem
                                                             </button>

                                                             <!-- <a href="index.php?controller=account&action=viewOrder&id=<?= $order['id_Order'] ?>" class="btn btn-sm btn-outline-dark">Xem</a> -->
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                         <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="orderDetailModalLabel">Chi tiết đơn hàng</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="order-detail-content">
                                        <p class="text-center">Đang tải dữ liệu...</p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    </div>
                    
                          <div id="address-section" class="account-content content-section <?= ($currentTab == 'address') ? 'active' : '' ?>">
                        <h4 class="section-title">Sổ địa chỉ</h4>
                        <div class="mb-4">
                            <button class="btn btn-dark mb-4" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="fas fa-plus me-2"></i>Thêm địa chỉ mới
                            </button>
                            
                            <?php if (empty($addresses)): ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-map-marker-alt fa-4x mb-3 text-muted"></i>
                                    <h5>Bạn chưa có địa chỉ nào</h5>
                                    <p class="text-muted">Hãy thêm địa chỉ để dễ dàng đặt hàng trong tương lai.</p>
                                </div>
                            <?php else: ?>
                                <div class="row g-4">
                                    <?php foreach ($addresses as $address): ?>
                                        <div class="col-md-6">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <h5 class="card-title"><?= htmlspecialchars($address['address_name'] ?? 'Địa chỉ') ?></h5>
                                                        <?php if ($address['is_default']): ?>
                                                            <span class="badge bg-dark">Mặc định</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <p class="card-text mb-1"><strong><?= htmlspecialchars($address['receiver_name']) ?></strong></p>
                                                    <p class="card-text mb-1"><?= htmlspecialchars($address['phone']) ?></p>
                                                    <p class="card-text mb-1"><?= htmlspecialchars($address['street_address']) ?></p>
                                                    <p class="card-text"><?= htmlspecialchars($address['ward'] . ', ' . $address['district'] . ', ' . $address['province']) ?></p>
                                                    <div class="d-flex mt-3">
                                                        <button class="btn btn-sm btn-outline-dark me-2 edit-address-btn" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#editAddressModal"
                                                                data-address-id="<?= $address['id'] ?>"
                                                                data-address-name="<?= htmlspecialchars($address['address_name'] ?? '') ?>"
                                                                data-receiver-name="<?= htmlspecialchars($address['receiver_name']) ?>"
                                                                data-phone="<?= htmlspecialchars($address['phone']) ?>"
                                                                data-street="<?= htmlspecialchars($address['street_address']) ?>"
                                                                data-province="<?= htmlspecialchars($address['province']) ?>"
                                                                data-district="<?= htmlspecialchars($address['district']) ?>"
                                                                data-ward="<?= htmlspecialchars($address['ward']) ?>"
                                                                data-is-default="<?= $address['is_default'] ?>">
                                                            Sửa
                                                        </button>
                                                        <?php if (!$address['is_default']): ?>
                                                            <form action="index.php?controller=account&action=deleteAddress" method="POST" class="d-inline me-2" onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?');">
                                                                <input type="hidden" name="address_id" value="<?= $address['id'] ?>">
                                                                <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                                                            </form>
                                                            
                                                            <form action="index.php?controller=account&action=setDefaultAddress" method="POST" class="d-inline">
                                                                <input type="hidden" name="address_id" value="<?= $address['id'] ?>">
                                                                <button type="submit" class="btn btn-sm btn-outline-primary">Đặt làm mặc định</button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Change Password Section -->
                    <div id="password-section" class="account-content content-section <?= ($currentTab == 'password') ? 'active' : '' ?>">
                        <h4 class="section-title">Đổi mật khẩu</h4>
                        
                        <form id="change-password-form" action="index.php?controller=account&action=changePassword" method="POST">
                            <div class="mb-3">
                                <label for="currentPassword" class="form-label">Mật khẩu hiện tại</label>
                                <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                            </div>
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">Mật khẩu mới</label>
                                <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                                <div class="form-text">Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ và số.</div>
                            </div>
                            <div class="mb-4">
                                <label for="confirmPassword" class="form-label">Xác nhận mật khẩu mới</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                            </div>
                            <button type="submit" class="btn btn-dark">Cập nhật mật khẩu</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Add Address Modal -->
    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAddressModalLabel">Thêm địa chỉ mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-address-form" action="index.php?controller=account&action=addAddress" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="address_name" class="form-label">Tên địa chỉ</label>
                                <input type="text" class="form-control" id="address_name" name="address_name" placeholder="Nhà, Công ty, ...">
                            </div>
                            <div class="col-md-6">
                                <label for="receiver_name" class="form-label">Họ và tên người nhận</label>
                                <input type="text" class="form-control" id="receiver_name" name="receiver_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" id="phonenumber" name="phone" required>
                            </div>
                            <div class="col-12">
                                <label for="street_address" class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" id="street_address" name="street_address" placeholder="Số nhà, tên đường" required>
                            </div>
                            <div class="col-md-4">
                                <label for="province" class="form-label">Tỉnh/TP</label>
                                <select class="form-select" id="province" name="province" required>
                                    <option value="">Chọn tỉnh/thành phố</option>
                                    <option value="TP. Hồ Chí Minh">TP. Hồ Chí Minh</option>
                                    <option value="Hà Nội">Hà Nội</option>
                                    <option value="Đà Nẵng">Đà Nẵng</option>
                                    <option value="Cần Thơ">Cần Thơ</option>
                                    <option value="Hải Phòng">Hải Phòng</option>
                                    <!-- Thêm các tỉnh/thành phố khác -->
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="district" class="form-label">Quận/Huyện</label>
                                <select class="form-select" id="district" name="district" required>
                                    <option value="">Chọn quận/huyện</option>
                                    <!-- Các quận/huyện sẽ được thêm bằng JavaScript -->
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="ward" class="form-label">Phường/Xã</label>
                                <select class="form-select" id="ward" name="ward" required>
                                    <option value="">Chọn phường/xã</option>
                                    <!-- Các phường/xã sẽ được thêm bằng JavaScript -->
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1">
                                    <label class="form-check-label" for="is_default">
                                        Đặt làm địa chỉ mặc định
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-dark">Lưu địa chỉ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Address Modal -->
    <div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAddressModalLabel">Chỉnh sửa địa chỉ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-address-form" action="index.php?controller=account&action=updateAddress" method="POST">
                        <input type="hidden" id="edit_address_id" name="address_id">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_address_name" class="form-label">Tên địa chỉ</label>
                                <input type="text" class="form-control" id="edit_address_name" name="address_name" placeholder="Nhà, Công ty, ...">
                            </div>
                            <div class="col-md-6">
                                <label for="edit_receiver_name" class="form-label">Họ và tên người nhận</label>
                                <input type="text" class="form-control" id="edit_receiver_name" name="receiver_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" id="edit_phone" name="phone" required>
                            </div>
                            <div class="col-12">
                                <label for="edit_street_address" class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" id="edit_street_address" name="street_address" placeholder="Số nhà, tên đường" required>
                            </div>
                            <div class="col-md-4">
                                <label for="edit_province" class="form-label">Tỉnh/TP</label>
                                <select class="form-select" id="edit_province" name="province" required>
                                    <option value="">Chọn tỉnh/thành phố</option>
                                    <option value="TP. Hồ Chí Minh">TP. Hồ Chí Minh</option>
                                    <option value="Hà Nội">Hà Nội</option>
                                    <option value="Đà Nẵng">Đà Nẵng</option>
                                    <option value="Cần Thơ">Cần Thơ</option>
                                    <option value="Hải Phòng">Hải Phòng</option>
                                    <!-- Thêm các tỉnh/thành phố khác -->
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="edit_district" class="form-label">Quận/Huyện</label>
                                <select class="form-select" id="edit_district" name="district" required>
                                    <option value="">Chọn quận/huyện</option>
                                    <!-- Các quận/huyện sẽ được thêm bằng JavaScript -->
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="edit_ward" class="form-label">Phường/Xã</label>
                                <select class="form-select" id="edit_ward" name="ward" required>
                                    <option value="">Chọn phường/xã</option>
                                    <!-- Các phường/xã sẽ được thêm bằng JavaScript -->
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="edit_is_default" name="is_default" value="1">
                                    <label class="form-check-label" for="edit_is_default">
                                        Đặt làm địa chỉ mặc định
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu địa chỉ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab navigation
            const navLinks = document.querySelectorAll('.account-menu .nav-link');
            
            navLinks.forEach(link => {
                if (!link.classList.contains('text-danger')) { // Exclude logout link
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        // Remove active class from all links
                        navLinks.forEach(item => item.classList.remove('active'));
                        
                        // Add active class to clicked link
                        this.classList.add('active');
                        
                        // Get the section to show
                        const sectionId = this.getAttribute('data-section');
                        
                        // Hide all content sections
                        document.querySelectorAll('.content-section').forEach(section => {
                            section.classList.remove('active');
                        });
                        
                        // Show the target section
                        document.getElementById(sectionId).classList.add('active');
                        
                        // Update URL with tab parameter
                        const tabName = sectionId.replace('-section', '');
                        history.replaceState(null, null, `index.php?controller=account&tab=${tabName}`);
                    });
                }
            });
            
            // Edit address modal
            const editAddressModal = document.getElementById('editAddressModal');
            if (editAddressModal) {
                editAddressModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    
                    // Extract data from button attributes
                    const addressId = button.getAttribute('data-address-id');
                    const addressName = button.getAttribute('data-address-name');
                    const receiverName = button.getAttribute('data-receiver-name');
                    const phone = button.getAttribute('data-phone');
                    const street = button.getAttribute('data-street');
                    const province = button.getAttribute('data-province');
                    const district = button.getAttribute('data-district');
                    const ward = button.getAttribute('data-ward');
                    const isDefault = button.getAttribute('data-is-default') === '1';
                    
                    // Set values in form fields
                    document.getElementById('edit_address_id').value = addressId;
                    document.getElementById('edit_address_name').value = addressName;
                    document.getElementById('edit_receiver_name').value = receiverName;
                    document.getElementById('edit_phone').value = phone;
                    document.getElementById('edit_street_address').value = street;
                    
                    // Set province, and trigger change events to load district and ward
                    const provinceSelect = document.getElementById('edit_province');
                    provinceSelect.value = province;
                    
                    // Simulate a change event to load districts
                    const changeEvent = new Event('change');
                    provinceSelect.dispatchEvent(changeEvent);
                    
                    // Set district and ward (this would need to be delayed or done after districts are loaded)
                    setTimeout(() => {
                        const districtSelect = document.getElementById('edit_district');
                        districtSelect.value = district;
                        districtSelect.dispatchEvent(changeEvent);
                        
                        setTimeout(() => {
                            document.getElementById('edit_ward').value = ward;
                        }, 100);
                    }, 100);
                    
                    // Set default checkbox
                    document.getElementById('edit_is_default').checked = isDefault;
                });
            }
            
            // Password validation
            const passwordForm = document.getElementById('change-password-form');
            if (passwordForm) {
                passwordForm.addEventListener('submit', function(e) {
                    const newPassword = document.getElementById('newPassword').value;
                    const confirmPassword = document.getElementById('confirmPassword').value;
                    
                    if (newPassword !== confirmPassword) {
                        e.preventDefault();
                        alert('Mật khẩu mới và xác nhận mật khẩu không khớp!');
                    }
                });
            }
            
            // Location data
            // This is a simplified example - in a real implementation, you would fetch this data from an API
            const locations = {
                'TP. Hồ Chí Minh': {
                    'Quận 1': ['Phường Bến Nghé', 'Phường Bến Thành', 'Phường Cầu Ông Lãnh'],
                    'Quận 2': ['Phường An Phú', 'Phường Thảo Điền', 'Phường Bình Trưng Đông'],
                    'Quận 3': ['Phường 1', 'Phường 2', 'Phường 3']
                },
                'Hà Nội': {
                    'Quận Ba Đình': ['Phường Phúc Xá', 'Phường Trúc Bạch', 'Phường Vĩnh Phúc'],
                    'Quận Hoàn Kiếm': ['Phường Hàng Bạc', 'Phường Hàng Bồ', 'Phường Hàng Đào'],
                    'Quận Tây Hồ': ['Phường Bưởi', 'Phường Nhật Tân', 'Phường Quảng An']
                }
                
            };
            
            // Dynamic location selects
            function setupLocationSelects(provinceId, districtId, wardId) {
                const provinceSelect = document.getElementById(provinceId);
                const districtSelect = document.getElementById(districtId);
                const wardSelect = document.getElementById(wardId);
                
                if (!provinceSelect || !districtSelect || !wardSelect) return;
                
                provinceSelect.addEventListener('change', function() {
                    const province = this.value;
                    
                    // Clear district and ward selects
                    districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                    
                    // Disable ward select
                    wardSelect.disabled = true;
                    
                    if (province && locations[province]) {
                        // Enable district select
                        districtSelect.disabled = false;
                        
                        // Add district options
                        for (const district in locations[province]) {
                            const option = document.createElement('option');
                            option.value = district;
                            option.textContent = district;
                            districtSelect.appendChild(option);
                        }
                    } else {
                        // Disable district select
                        districtSelect.disabled = true;
                    }
                });
                
                districtSelect.addEventListener('change', function() {
                    const province = provinceSelect.value;
                    const district = this.value;
                    
                    // Clear ward select
                    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                    
                    if (province && district && locations[province] && locations[province][district]) {
                        // Enable ward select
                        wardSelect.disabled = false;
                        
                        // Add ward options
                        for (const ward of locations[province][district]) {
                            const option = document.createElement('option');
                            option.value = ward;
                            option.textContent = ward;
                            wardSelect.appendChild(option);
                        }
                    } else {
                        // Disable ward select
                        wardSelect.disabled = true;
                    }
                });
            }
            
            // Setup location selects for add address form
            setupLocationSelects('province', 'district', 'ward');
            
            // Setup location selects for edit address form
            setupLocationSelects('edit_province', 'edit_district', 'edit_ward');
        });
    </script>
    <?php
    view('frontend.partitions.frontend.footer');
    ?>
        <script src="/Project_Website/ProjectWeb/layout/js/Header.js"></script>
   
    <script src="/Project_Website/ProjectWeb/layout/js/Footer.js"></script>
</body>
</html>


<?php
// Add this JavaScript to the bottom of the file, before the closing </body> tag
?>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const viewOrderButtons = document.querySelectorAll(".view-order-btn");
    const modal = document.getElementById("orderDetailModal");

    if (!modal) {
        console.error("Modal #orderDetailModal not found!");
        return;
    }

    console.log("Found buttons:", viewOrderButtons.length);
    viewOrderButtons.forEach(button => {
        button.addEventListener("click", function () {
            const orderId = this.dataset.orderId;
            const modalContent = document.getElementById("order-detail-content");

            if (!modalContent) {
                console.error("Modal content #order-detail-content not found!");
                return;
            }

            console.log("Fetching order:", orderId);
            modalContent.innerHTML = `
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                    </div>
                    <p>Đang tải dữ liệu...</p>
                </div>
            `;

            fetch("index.php?controller=account&action=getOrderDetails", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: `order_id=${orderId}`
            })
            .then(response => {
                console.log("Response status:", response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.text(); // Dùng text() trước để debug
            })
            .then(text => {
                console.log("Raw response:", text);
                try {
                    const data = JSON.parse(text); // Thử parse JSON
                    if (data.success) {
                        modalContent.innerHTML = generateOrderDetailHTML(data.order, data.orderDetails);
                    } else {
                        modalContent.innerHTML = `<p class="text-danger text-center">${data.message || "Không thể tải chi tiết đơn hàng!"}</p>`;
                    }
                } catch (error) {
                    console.error("Parse error:", error);
                    modalContent.innerHTML = `<p class="text-danger text-center">Lỗi phân tích dữ liệu: Phản hồi không phải JSON!</p>`;
                }
            })
            .catch(error => {
                console.error("Fetch error:", error.message);
                modalContent.innerHTML = `<p class="text-danger text-center">Có lỗi xảy ra, vui lòng thử lại! (${error.message})</p>`;
            });

            modal.addEventListener('hidden.bs.modal', function () {
                button.focus();
            }, { once: true });
        });
    });

    // Hàm generateOrderDetailHTML (giữ nguyên)
    function generateOrderDetailHTML(order, orderDetails) {
        const formatCurrency = (amount) => {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
        };

        const noteParts = order.note ? order.note.split(' - ') : [];
        const receiverName = noteParts[0] || '';
        const phone = noteParts[1] || '';
        const email = noteParts[2] || '';
        const address = noteParts[3] || '';
        const note = noteParts[4] || '';

        let html = `
            <div class="order-detail-card">
                <h6>Thông tin đơn hàng</h6>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Mã đơn hàng:</strong> ${order.order_number}</p>
                            <p><strong>Ngày đặt hàng:</strong> ${order.created_at}</p>
                            <p><strong>Trạng thái:</strong> 
                                <span class="badge ${order.status === 'pending' ? 'bg-warning' : (order.status === 'waitConfirm' ? 'bg-info text-white' : (order.status === 'shipping' ? 'bg-primary' : (order.status === 'completed' ? 'bg-success' : 'bg-danger')))}">
                                    ${order.status === 'pending' ? 'Đang xử lý' : (order.status === 'waitConfirm' ? 'Chờ xác nhận' : (order.status === 'shipping' ? 'Đang giao' : (order.status === 'completed' ? 'Đã giao' : 'Đã hủy')))}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tổng tiền:</strong> ${formatCurrency(order.total_amount)}</p>
                            <p><strong>Phí vận chuyển:</strong> ${formatCurrency(order.shipping_fee)}</p>
                            <p><strong>Phương thức thanh toán:</strong> 
                                ${order.payment_by === 'cod' ? 'Thanh toán khi nhận hàng' : order.payment_by.charAt(0).toUpperCase() + order.payment_by.slice(1)}
                            </p>
                            <p><strong>Phương thức vận chuyển:</strong> 
                                ${order.shipping_method === 'ghn' ? 'Giao hàng nhanh' : order.shipping_method.charAt(0).toUpperCase() + order.shipping_method.slice(1)}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="order-detail-card">
                <h6>Thông tin giao hàng</h6>
                <div class="card-body">
                    <p><strong>Họ và tên:</strong> ${receiverName}</p>
                    <p><strong>Số điện thoại:</strong> ${phone}</p>
                    ${email ? `<p><strong>Email:</strong> ${email}</p>` : ''}
                    <p><strong>Địa chỉ giao hàng:</strong> ${address}</p>
                </div>
            </div>
            <div class="order-detail-card">
                <h6>Sản phẩm trong đơn hàng</h6>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered order-detail-table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Kích thước</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
        `;

        orderDetails.forEach(detail => {
            html += `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            ${detail.product.main_image ? `<img src="/Project_Website/ProjectWeb/upload/img/All-Product/${detail.product.main_image}" alt="${detail.product.name}" class="me-2">` : ''}
                            <span>${detail.product.name}</span>
                        </div>
                    </td>
                    <td>${detail.size}</td>
                    <td>${detail.quantity}</td>
                    <td>${formatCurrency(detail.price)}</td>
                    <td>${formatCurrency(detail.sub_total)}</td>
                </tr>
            `;
        });

        html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;

        if (note) {
            html += `
                <div class="order-detail-card">
                    <h6>Ghi chú</h6>
                    <div class="card-body">
                        <p>${note}</p>
                    </div>
                </div>
            `;
        }

        return html;
    }
});
// Add this to your existing JavaScript in order.js or at the bottom of the page
document.addEventListener('DOMContentLoaded', function() {
    // Address selection functionality
    const addressSelectors = document.querySelectorAll('.address-selector');
    const addressCards = document.querySelectorAll('.address-card');
    const addressForm = document.querySelector('#checkout-form');
    
    if (addressSelectors.length > 0) {
        // Highlight selected address card
        addressSelectors.forEach(selector => {
            selector.addEventListener('change', function() {
                // Remove highlight from all cards
                addressCards.forEach(card => {
                    card.classList.remove('border-primary');
                });
                
                // Add highlight to selected card
                const selectedCard = this.closest('.address-card');
                if (selectedCard) {
                    selectedCard.classList.add('border-primary');
                }
                
                // If "new address" is selected, enable the form fields
                // Otherwise, fill the form with the selected address data
                if (this.value === 'new') {
                    // Clear form fields
                    document.getElementById('fullname').value = '';
                    document.getElementById('phone').value = '';
                    document.getElementById('email').value = '';
                    document.getElementById('address').value = '';
                    document.getElementById('province').value = '';
                    document.getElementById('district').value = '';
                    document.getElementById('ward').value = '';
                    document.getElementById('note').value = '';
                    document.getElementById('address_id').value = '';
                    
                    // Enable form fields
                    enableFormFields(true);
                } else {
                    // Set the address ID for the form submission
                    document.getElementById('address_id').value = this.value;
                    
                    // Load address data
                    loadAddressData(this.value);
                }
            });
        });
        
        // Load first selected address (usually default) if any
        const selectedAddress = document.querySelector('.address-selector:checked');
        if (selectedAddress && selectedAddress.value !== 'new') {
            document.getElementById('address_id').value = selectedAddress.value;
            loadAddressData(selectedAddress.value);
        }
    }
    
    // Function to enable/disable form fields
    function enableFormFields(enable) {
        const formFields = addressForm.querySelectorAll('input:not([type="hidden"]), select, textarea');
        formFields.forEach(field => {
            field.disabled = !enable;
        });
    }
    
    // Function to load address data into form
    function loadAddressData(addressId) {
        // Show loading state
        const submitButton = document.querySelector('#complete-order');
        if (submitButton) {
            submitButton.disabled = true;
        }
        
        // Make AJAX request to get address data
        fetch('index.php?controller=account&action=selectAddress', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'address_id=' + addressId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const address = data.address;
                
                // Fill form fields with address data
                document.getElementById('fullname').value = address.receiver_name;
                document.getElementById('phone').value = address.phone;
                
                // If email field exists and email in address data
                const emailField = document.getElementById('email');
                if (emailField && address.email) {
                    emailField.value = address.email;
                }
                
                document.getElementById('address').value = address.street_address;
                
                // Handle province/district/ward selection
                const provinceSelect = document.getElementById('province');
                if (provinceSelect) {
                    provinceSelect.value = address.province;
                    
                    // Trigger change event to load districts
                    const event = new Event('change');
                    provinceSelect.dispatchEvent(event);
                    
                    // Set district and ward with a delay to ensure options are loaded
                    setTimeout(() => {
                        const districtSelect = document.getElementById('district');
                        if (districtSelect) {
                            districtSelect.value = address.district;
                            districtSelect.dispatchEvent(event);
                            
                            setTimeout(() => {
                                const wardSelect = document.getElementById('ward');
                                if (wardSelect) {
                                    wardSelect.value = address.ward;
                                }
                            }, 100);
                        }
                    }, 100);
                }
                
                // Disable form fields since we're using a saved address
                enableFormFields(false);
            } else {
                console.error('Failed to load address data:', data.message);
                // If failed, enable form fields so user can enter data manually
                enableFormFields(true);
            }
            
            // Re-enable submit button
            if (submitButton) {
                submitButton.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error fetching address data:', error);
            enableFormFields(true);
            
            // Re-enable submit button
            if (submitButton) {
                submitButton.disabled = false;
            }
        });
    }
    
    // Add functionality to save new address when submitting order
    const orderForm = document.getElementById('checkout-form');
    if (orderForm) {
        // Add a checkbox to save new address
        const saveAddressDiv = document.createElement('div');
        saveAddressDiv.className = 'col-12 mt-3';
        saveAddressDiv.innerHTML = `
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="save_address" name="save_address" value="1">
                <label class="form-check-label" for="save_address">
                    Lưu địa chỉ này vào sổ địa chỉ
                </label>
            </div>
        `;
        
        // Find where to insert the checkbox
        const noteField = orderForm.querySelector('.col-12:last-of-type');
        if (noteField) {
            noteField.after(saveAddressDiv);
        }
    }
});
</script>

<style>
/* Add this CSS to your existing stylesheet */
.address-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.address-card:hover {
    border-color: #ddd;
}

.address-card.border-primary {
    border-color: #0d6efd;
    background-color: rgba(13, 110, 253, 0.03);
}

.shipping-divider {
    position: relative;
    text-align: center;
}

.shipping-divider hr {
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    margin: 0;
    z-index: 1;
}

.shipping-divider p {
    display: inline-block;
    position: relative;
    padding: 0 15px;
    background-color: #f0f0f0;
    z-index: 2;
    margin: 0;
}
</style>