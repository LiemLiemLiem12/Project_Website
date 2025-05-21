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
                            <button class="btn btn-secondary" id="showTrashBtn">
                                <i class="fas fa-trash-restore"></i> Thùng rác
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thùng rác -->
    <div class="modal fade" id="trashModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thùng rác - Đơn hàng đã ẩn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="40"><input type="checkbox" id="select-all-trash" class="form-check-input"></th>
                                    <th>Mã đơn hàng</th>
                                    <th>Khách hàng</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Thanh toán</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="trashTableBody">
                                <!-- Dữ liệu sẽ được load bằng AJAX -->
                                <tr>
                                    <td colspan="8" class="text-center">Đang tải dữ liệu...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="restoreSelectedBtn" disabled>
                        <i class="fas fa-trash-restore"></i> Khôi phục đã chọn
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
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
    <script>
        // Đảm bảo chỉ toggle class 'show' cho sidebar khi bấm hamburger
        // Xóa mọi logic toggle class 'active' hoặc nút đóng sidebar riêng
        document.addEventListener('DOMContentLoaded', function () {
            // Thông báo cứng về đơn hàng
            const notifications = [
                {
                    icon: 'fa-shopping-cart',
                    title: 'Đơn hàng mới',
                    content: 'Đơn hàng #ORD123 vừa được tạo.',
                    time: '3 phút trước'
                },
                {
                    icon: 'fa-times-circle',
                    title: 'Đơn hàng bị hủy',
                    content: 'Đơn hàng #ORD120 đã bị hủy bởi khách.',
                    time: '20 phút trước'
                },
                {
                    icon: 'fa-truck',
                    title: 'Đơn hàng đang giao',
                    content: 'Đơn hàng #ORD119 đang được giao cho khách.',
                    time: '1 giờ trước'
                }
            ];
            const notificationList = document.getElementById('notificationList');
            if (notificationList) {
                notificationList.innerHTML = notifications.map(n => `
                <li class="notification-item">
                    <span class="notification-icon"><i class="fas ${n.icon}"></i></span>
                    <div class="notification-content">
                        <div class="notification-title">${n.title}</div>
                        <div>${n.content}</div>
                        <div class="notification-time">${n.time}</div>
                    </div>
                </li>
            `).join('');
            }
            // Toggle dropdown
            const bell = document.getElementById('notificationBell');
            const dropdown = document.getElementById('notificationDropdown');
            if (bell && dropdown) {
                bell.addEventListener('click', function (e) {
                    e.stopPropagation();
                    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
                });
                // Click ngoài dropdown sẽ ẩn
                document.addEventListener('click', function (e) {
                    if (!dropdown.contains(e.target) && e.target !== bell) {
                        dropdown.style.display = 'none';
                    }
                });
            }

            // Xử lý thùng rác
            const showTrashBtn = document.getElementById('showTrashBtn');
            const trashModal = new bootstrap.Modal(document.getElementById('trashModal'));
            const restoreSelectedBtn = document.getElementById('restoreSelectedBtn');
            
            if (showTrashBtn) {
                showTrashBtn.addEventListener('click', function() {
                    loadTrashOrders();
                    trashModal.show();
                });
            }
            
            // Hàm tải danh sách đơn hàng trong thùng rác
            function loadTrashOrders() {
                const trashTableBody = document.getElementById('trashTableBody');
                if (!trashTableBody) return;
                
                trashTableBody.innerHTML = '<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Đang tải dữ liệu...</td></tr>';
                
                fetch('index.php?controller=adminorder&action=trash')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        trashTableBody.innerHTML = '';
                        
                        if (data.error) {
                            trashTableBody.innerHTML = `<tr><td colspan="8" class="text-center text-danger">${data.error}</td></tr>`;
                            return;
                        }
                        
                        if (!data || data.length === 0) {
                            trashTableBody.innerHTML = '<tr><td colspan="8" class="text-center">Không có đơn hàng nào trong thùng rác.</td></tr>';
                            updateRestoreSelectedButton();
                            return;
                        }
                        
                        // Hiển thị đơn hàng trong thùng rác
                        data.forEach(order => {
                            let statusClass = '';
                            let statusText = '';
                            
                            if (order.status === 'completed') {
                                statusClass = 'completed';
                                statusText = 'Hoàn thành';
                            } else if (order.status === 'pending') {
                                statusClass = 'pending';
                                statusText = 'Đang xử lý';
                            } else if (order.status === 'cancelled') {
                                statusClass = 'cancelled';
                                statusText = 'Đã hủy';
                            } else if (order.status === 'shipping') {
                                statusClass = 'shipping';
                                statusText = 'Đang giao';
                            } else if (order.status === 'waitConfirm') {
                                statusClass = 'waitConfirm';
                                statusText = 'Chờ xác nhận';
                            } else {
                                statusClass = 'unknown';
                                statusText = 'Không xác định';
                            }
                            
                            const row = document.createElement('tr');
                            row.setAttribute('data-order-id', order.id_Order);
                            
                            row.innerHTML = `
                                <td><input type="checkbox" class="form-check-input trash-order-checkbox" data-id="${order.id_Order}"></td>
                                <td>#0${order.id_Order}</td>
                                <td>${order.name || ''}</td>
                                <td>${order.created_at || ''}</td>
                                <td>${order.total_amount || ''}</td>
                                <td>${order.payment_by || ''}</td>
                                <td><span class="status ${statusClass}">${statusText}</span></td>
                                <td>
                                    <button class="btn btn-sm btn-success btn-restore-order" title="Khôi phục" data-id="${order.id_Order}">
                                        <i class="fas fa-trash-restore"></i>
                                    </button>
                                </td>
                            `;
                            trashTableBody.appendChild(row);
                        });
                        
                        // Đính kèm sự kiện
                        attachTrashEventListeners();
                    })
                    .catch(error => {
                        console.error('Error loading trash orders:', error);
                        trashTableBody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Có lỗi xảy ra khi tải dữ liệu thùng rác.</td></tr>';
                    });
            }
            
            // Đính kèm sự kiện cho các phần tử trong thùng rác
            function attachTrashEventListeners() {
                // Đính kèm sự kiện cho các nút khôi phục đơn lẻ
                document.querySelectorAll('.btn-restore-order').forEach(button => {
                    button.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        showConfirmDialog('Bạn có chắc chắn muốn khôi phục đơn hàng này?', function() {
                            restoreOrder(id);
                        });
                    });
                });
                
                // Đính kèm sự kiện cho các checkbox
                document.querySelectorAll('.trash-order-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', updateRestoreSelectedButton);
                });
                
                // Đính kèm sự kiện cho checkbox chọn tất cả
                const selectAllTrashCheckbox = document.getElementById('select-all-trash');
                if (selectAllTrashCheckbox) {
                    selectAllTrashCheckbox.addEventListener('change', function() {
                        const isChecked = this.checked;
                        document.querySelectorAll('.trash-order-checkbox').forEach(checkbox => {
                            checkbox.checked = isChecked;
                        });
                        updateRestoreSelectedButton();
                    });
                }
                
                updateRestoreSelectedButton();
            }
            
            // Cập nhật trạng thái nút khôi phục đã chọn
            function updateRestoreSelectedButton() {
                if (restoreSelectedBtn) {
                    const selectedCheckboxes = document.querySelectorAll('.trash-order-checkbox:checked');
                    restoreSelectedBtn.disabled = selectedCheckboxes.length === 0;
                }
            }
            
            // Xử lý khôi phục hàng loạt
            if (restoreSelectedBtn) {
                restoreSelectedBtn.addEventListener('click', function() {
                    const selectedIds = Array.from(document.querySelectorAll('.trash-order-checkbox:checked'))
                        .map(checkbox => checkbox.getAttribute('data-id'));
                    
                    if (selectedIds.length === 0) return;
                    
                    showConfirmDialog(`Bạn có chắc chắn muốn khôi phục ${selectedIds.length} đơn hàng đã chọn?`, function() {
                        restoreMultipleOrders(selectedIds);
                    });
                });
            }
            
            // Khôi phục một đơn hàng
            function restoreOrder(id) {
                fetch(`index.php?controller=adminorder&action=restore&id=${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccessAlert('Đã khôi phục đơn hàng thành công!');
                            loadTrashOrders(); // Tải lại danh sách thùng rác
                            
                            // Tải lại danh sách đơn hàng chính nếu cần
                            location.reload(); // Hoặc sử dụng AJAX để tải lại bảng chính
                        } else {
                            showErrorAlert(data.error || 'Lỗi khi khôi phục đơn hàng.');
                        }
                    })
                    .catch(error => {
                        console.error('Error restoring order:', error);
                        showErrorAlert('Lỗi kết nối khi khôi phục đơn hàng.');
                    });
            }
            
            // Khôi phục nhiều đơn hàng
            function restoreMultipleOrders(ids) {
                if (!ids || ids.length === 0) return;
                
                // Đếm số thành công
                let successCount = 0;
                let failureCount = 0;
                let totalToProcess = ids.length;
                let processed = 0;
                
                // Hiển thị thông báo đang xử lý
                const trashTableBody = document.getElementById('trashTableBody');
                if (trashTableBody) {
                    const loadingRow = document.createElement('tr');
                    loadingRow.id = 'restore-loading-indicator';
                    loadingRow.innerHTML = `<td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Đang khôi phục ${ids.length} đơn hàng...</td>`;
                    trashTableBody.appendChild(loadingRow);
                }
                
                // Khôi phục từng đơn hàng
                ids.forEach(id => {
                    fetch(`index.php?controller=adminorder&action=restore&id=${id}`)
                        .then(response => response.json())
                        .then(data => {
                            processed++;
                            if (data.success) {
                                successCount++;
                            } else {
                                failureCount++;
                                console.error(`Lỗi khôi phục đơn hàng #${id}:`, data.error);
                            }
                            
                            // Khi tất cả đã được xử lý
                            if (processed === totalToProcess) {
                                const message = `Đã khôi phục thành công ${successCount}/${totalToProcess} đơn hàng.`;
                                showSuccessAlert(message);
                                
                                // Tải lại danh sách
                                loadTrashOrders();
                                
                                // Tải lại danh sách đơn hàng chính
                                if (successCount > 0) {
                                    location.reload();
                                }
                            }
                        })
                        .catch(error => {
                            processed++;
                            failureCount++;
                            console.error(`Lỗi kết nối khi khôi phục đơn hàng #${id}:`, error);
                            
                            // Khi tất cả đã được xử lý
                            if (processed === totalToProcess) {
                                const message = `Đã khôi phục thành công ${successCount}/${totalToProcess} đơn hàng.`;
                                showSuccessAlert(message);
                                
                                // Tải lại danh sách
                                loadTrashOrders();
                                
                                // Tải lại danh sách đơn hàng chính
                                if (successCount > 0) {
                                    location.reload();
                                }
                            }
                        });
                });
            }
        });
    </script>
    
    <!-- Sweet Alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom SweetAlert Config -->
    <script src="/Project_Website/ProjectWeb/layout/js/sweetalert-config.js"></script>
    
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