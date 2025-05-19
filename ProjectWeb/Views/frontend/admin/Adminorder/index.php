<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng - SR Store</title>
    <!-- Bootstrap CSS -->
    <link href="/Project_Website/ProjectWeb/layout/cssBootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Admin.css">
    <!-- Responsive CSS -->

</head>

<body>
    <div class="admin-container">
        <!-- Sidebar -->

        <!-- Sidebar -->
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/Project_Website/ProjectWeb/Views/frontend/partitions/frontend/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Header -->
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
            <!-- Orders Content -->
            <div class="content" id="content-container">
                <div class="page-content" id="orders-content">
                    <div
                        class="page-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
                        <h1 class="mb-0 w-50">Quản lý đơn hàng</h1>
                        <div class="d-flex gap-2 w-100 w-md-auto">
                            <button id="deleteSelectedOrderBtn" class="btn btn-danger" disabled>
                                <i class="fas fa-trash"></i> Xóa đã chọn
                            </button>
                            <button class="btn btn-success flex-fill" id="exportExcel"><i
                                    class="fas fa-file-excel me-1"></i> Xuất
                                Excel</button>
                            <button class="btn btn-danger flex-fill" id="exportPDF"><i class="fas fa-file-pdf me-1"></i>
                                Xuất
                                PDF</button>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0"
                                            placeholder="Tìm đơn hàng..." id="searchingOrder">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="filter-options d-flex flex-column flex-md-row gap-2">
                                        <select class="form-select" id="status">
                                            <option value="">Trạng thái</option>
                                            <option value="cho-xac-nhan">Chờ xác nhận</option>
                                            <option value="dang-xu-ly">Đang xử lý</option>
                                            <option value="dang-giao">Đang giao</option>
                                            <option value="hoan-thanh">Đã hoàn thành</option>
                                            <option value="da-huy">Đã hủy</option>
                                        </select>
                                        <select class="form-select" id="payment-by">
                                            <option value="">Phương thức thanh toán</option>
                                            <option value="cod">COD</option>
                                            <option value="chuyen-khoanr">Chuyển khoản</option>
                                            <option value="vi-momo">Ví MoMo</option>
                                        </select>
                                        <select class="form-select" id="sort-by">
                                            <option value="">Sắp xếp theo</option>
                                            <option value="newest">Mới nhất</option>
                                            <option value="oldest">Cũ nhất</option>
                                            <option value="total-high">Tổng tiền (Cao-Thấp)</option>
                                            <option value="total-low">Tổng tiền (Thấp-Cao)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive" id="order-table">
                                <table class="table align-middle" id="table-order">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 40px;"><input type="checkbox"
                                                    id="select-all" class="form-check-input"></th>
                                            <th>Mã đơn hàng</th>
                                            <th>Khách hàng</th>
                                            <th>Ngày đặt</th>
                                            <th>Tổng tiền</th>
                                            <th>Thanh toán</th>
                                            <th>Trạng thái</th>
                                            <th style="width: 120px;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($orderList as $data) {
                                            echo '
                                                <tr data-order-id="' . $data['id_Order'] . '" data-status="' . $data['status'] . '" data-order-note = "' . $data['note'] . '">
                                                    <td><input type="checkbox" class="order-checkbox form-check-input"
                                                            data-id="' . $data['id_Order'] . '"></td>
                                                    <td>#0' . $data['id_Order'] . '</td>
                                                    <td>' . $data['name'] . '</td>
                                                    <td>' . $data['created_at'] . '</td>
                                                    <td>' . $data['total_amount'] . '</td>
                                                    <td>' . $data['payment_by'] . '</td>

                                                ';
                                            if ($data['status'] === 'completed') {
                                                echo '<td data-label="Trạng thái"><span class="status completed">Hoàn thành</span></td>';
                                            } elseif ($data['status'] === 'pending') {
                                                echo '<td data-label="Trạng thái"><span class="status pending">Đang xử lý</span></td>';
                                            } elseif ($data['status'] === 'cancelled') {
                                                echo '<td data-label="Trạng thái"><span class="status cancelled">Đã hủy</span></td>';
                                            } elseif ($data['status'] === 'shipping') {
                                                echo '<td data-label="Trạng thái"><span class="status shipping">Đang giao</span></td>';
                                            } elseif ($data['status'] === 'waitConfirm') {
                                                echo '<td data-label="Trạng thái"><span class="status waitConfirm">Chờ xác nhận</span></td>';
                                            } else {
                                                echo '<td data-label="Trạng thái"><span class="status unknown">Không xác định</span></td>';
                                            }
                                            echo '
                                                    <td>
                                                        <div class="action-buttons">
                                                ';

                                            if ($data['status'] === 'completed') {

                                            } elseif ($data['status'] === 'pending') {
                                                echo '<button class="btn btn-sm btn-primary d-flex justify-content-center align-items-center status-btn status-pending" data-bs-toggle="tooltip"
                                                                title="Giao hàng">
                                                                <i class="fa-solid fa-truck"></i>
                                                            </button>
                                                        <button class="btn btn-sm btn-danger d-flex justify-content-center align-items-center status-cancel" data-bs-toggle="tooltip"
                                                                title="Hủy bỏ">
                                                                <i class="fa-solid fa-x"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>';
                                            } elseif ($data['status'] === 'cancelled') {

                                            } elseif ($data['status'] === 'shipping') {
                                                echo '<button class="btn btn-sm btn-warning d-flex justify-content-center align-items-center status-btn status-shipping" data-bs-toggle="tooltip"
                                                                title="Nhận hàng">
                                                                <i class="fa-solid fa-inbox"></i>
                                                            </button>
                                                        <button class="btn btn-sm btn-danger d-flex justify-content-center align-items-center status-cancel" data-bs-toggle="tooltip"
                                                                title="Hủy bỏ">
                                                                <i class="fa-solid fa-x"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>';
                                            } elseif ($data['status'] === 'waitConfirm') {
                                                echo '<button class="btn btn-sm btn-success d-flex justify-content-center align-items-center status-btn status-wait" data-bs-toggle="tooltip"
                                                                title="Xác nhận">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        <button class="btn btn-sm btn-danger d-flex justify-content-center align-items-center status-cancel" data-bs-toggle="tooltip"
                                                                title="Hủy bỏ">
                                                                <i class="fa-solid fa-x"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>';
                                            } else {
                                                echo '<td data-label="Trạng thái"><span class="status unknown">Không xác định</span></td>';
                                            }

                                            echo
                                                '</tr>';
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
    </div>


    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">Chi tiết đơn hàng</h5>
                    <button id="closeModalBtn" type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="order-info">
                        <p><strong>Mã đơn hàng:</strong> <span id="orderId"></span></p>
                        <p><strong>Ngày đặt:</strong> <span id="orderDate"></span></p>
                        <p><strong>Trạng thái:</strong> <span id="orderStatus"></span></p>
                        <p><strong>Tổng tiền:</strong> <span id="orderTotal"></span></p>
                    </div>
                    <div class="customer-info mb-3">
                        <h6>Thông tin khách hàng</h6>
                        <p><strong>Họ tên:</strong> <span id="customerName"></span></p>
                        <p><strong>Email:</strong> <span id="customerEmail"></span></p>
                        <p><strong>Số điện thoại:</strong> <span id="customerPhone"></span></p>
                        <p><strong>Địa chỉ:</strong> <span id="customerAddress"></span></p>
                    </div>
                    <div class="payment-info mb-3">
                        <h6>Thông tin thanh toán</h6>
                        <p><strong>Phương thức:</strong> <span id="paymentMethod"></span></p>
                        <p><strong>Ghi chú:</strong> <span id="orderNote"></span></p>
                    </div>
                    <h6 class="mt-3">Sản phẩm đã đặt</h6>
                    <table id="orderItemsTable" class="table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Order items will be populated here -->
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <!-- Bootstrap JS with Popper -->
    <script src="/Project_Website/ProjectWeb/layout/jsBootstrap/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript -->
    <script src="/Project_Website/ProjectWeb/layout/js/AdminOrder.js"></script>
    <!-- Thêm thư viện jsPDF và jspdf-autotable -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.72/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.72/vfs_fonts.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Thêm style cho dropdown thông báo -->
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
</body>

</html>