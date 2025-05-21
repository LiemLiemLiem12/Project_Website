<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: ?controller=Adminlogin');
    exit;
}
?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý tài khoản Admin - SR Store</title>
    <link href="/Project_Website/ProjectWeb/layout/cssBootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Admin.css">
    <style>
        .status.completed {
            background: #d4f5e9;
            color: #2e7d32;
            border-radius: 16px;
            padding: 2px 12px;
            display: inline-block;
            font-size: 14px;
            font-weight: 500;
        }

        .status.inactive {
            background: #e0e0e0;
            color: #757575;
            border-radius: 16px;
            padding: 2px 12px;
            display: inline-block;
            font-size: 14px;
            font-weight: 500;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding-left: 36px;
        }

        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            pointer-events: none;
            /* Đảm bảo click vào icon vẫn focus được input */
        }

        .filter-row {
            flex-wrap: wrap;
            gap: 12px;
        }

        .filter-item {
            min-width: 160px;
        }

        .search-box input {
            width: 100%;
            min-width: 120px;
            padding-left: 36px;
            box-sizing: border-box;
        }

        .notification-list-modal {
            padding: 0;
            margin: 0;
        }

        .notification-item-modal {
            transition: background 0.2s;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            display: flex;
            align-items: start;
        }

        .notification-item-modal:hover {
            background: #f5f7fa;
        }

        .notification-icon-modal {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #1976d2;
            margin-right: 12px;
        }

        .notification-title-modal {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 2px;
        }

        .notification-desc-modal {
            font-size: 15px;
            margin-bottom: 2px;
            color: #444;
        }

        .notification-time-modal {
            font-size: 13px;
            color: #aaa;
        }
    </style>
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
                        <!-- <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                        <div class="notification-dropdown" id="notificationDropdown" style="display: none; position: absolute; top: 120%; right: 0; width: 340px; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.15); border-radius: 8px; z-index: 9999;">
                            <div style="padding: 12px 16px; border-bottom: 1px solid #eee; font-weight: 600;">Thông báo khách hàng</div>
                            <ul id="notificationList" style="list-style: none; margin: 0; padding: 0; max-height: 320px; overflow-y: auto;"></ul>
                            <div style="padding: 10px 0; text-align: center; border-top: 1px solid #eee;">
                                <a href="#" style="color: #007bff; font-size: 14px; text-decoration: none;">Xem tất cả</a>
                            </div>
                        </div> -->
                    </div>
                    <div class="profile">
                        <img src="/Project_Website/ProjectWeb/upload/img/avatar.jpg" alt="Admin Avatar"
                            class="profile-image">
                    </div>
                </div>
            </header>
            <div class="content" id="content-container">
                <div class="page-header d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">Quản lý tài khoản Admin</h1>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" id="addCustomerBtn">
                            <i class="fas fa-plus"></i> Thêm tài khoản admin
                        </button>
                        <button class="btn btn-danger" id="deleteSelectedBtn" disabled>
                            <i class="fas fa-trash"></i> Xóa đã chọn
                        </button>
                        <button class="btn btn-secondary" id="showTrashBtn">
                            <i class="fas fa-trash-restore"></i> Thùng rác
                        </button>
                        <button class="btn btn-info" id="viewUsersBtn">
                            <i class="fas fa-users"></i> Xem khách hàng
                        </button>
                    </div>
                </div>
                <div class="customer-filters mb-3">
                    <div class="filter-row d-flex flex-row flex-wrap gap-2 align-items-center">
                        <div class="filter-item flex-grow-1" style="min-width:220px;">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Tìm kiếm tài khoản admin..." id="searchCustomerInput"
                                    style="width:100%;padding-left:36px;" autocomplete="off">
                            </div>
                        </div>
                        <div class="filter-item">
                            <select class="filter-dropdown" id="filterStatus">
                                <option value="active" <?= ($status == 'active' && empty($sort)) || empty($status) ? 'selected' : '' ?>>Đang hoạt động</option>
                                <option value="newest" <?= $sort == 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                                <option value="oldest" <?= $sort == 'oldest' ? 'selected' : '' ?>>Cũ nhất</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <select class="filter-dropdown" id="sortCustomer">
                                <option value="" <?= empty($sort) || $sort == 'newest' || $sort == 'oldest' ? 'selected' : '' ?>>Sắp xếp theo</option>
                                <option value="name-asc" <?= $sort == 'name-asc' ? 'selected' : '' ?>>Tên (A-Z)</option>
                                <option value="name-desc" <?= $sort == 'name-desc' ? 'selected' : '' ?>>Tên (Z-A)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all-customer" class="form-check-input"></th>
                                <th>Tên tài khoản</th>
                                <th>Số điện thoại</th>
                                <th>Email</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="customerTableBody">
                            <?php foreach ($customers as $customer): ?>
                                <tr data-customer-id="<?= $customer['id_User'] ?>">
                                    <td>
                                        <input type="checkbox" class="form-check-input customer-checkbox"
                                            data-id="<?= $customer['id_User'] ?>">
                                    </td>
                                    <td><?= htmlspecialchars($customer['name']) ?></td>
                                    <td><?= htmlspecialchars($customer['phone']) ?></td>
                                    <td><?= htmlspecialchars($customer['email']) ?></td>
                                    <td>
                                        <span class="status <?= ($customer['hide'] ?? 0) ? 'inactive' : 'completed' ?>">
                                            <?= ($customer['hide'] ?? 0) ? 'Ẩn' : 'Đang hoạt động' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-primary btn-edit-customer" title="Sửa"
                                                data-id="<?= $customer['id_User'] ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php
                $totalPages = ceil($total / $limit);
                if ($totalPages > 1): ?>
                    <nav>
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link"
                                        href="index.php?controller=admincustomer&status=<?= urlencode($status) ?>&sort=<?= urlencode($sort) ?>&page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

                <!-- Modal Thêm/Sửa Khách Hàng -->
                <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="customerForm">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="customerModalLabel">Thêm tài khoản admin</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Đóng"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id" id="customerId">
                                    <div class="mb-3">
                                        <label>Họ tên <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" id="customerNameInput"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" id="customerEmailInput"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Mật khẩu <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password"
                                            id="customerPasswordInput" required autocomplete="new-password">
                                    </div>
                                    <div class="mb-3">
                                        <label>Số điện thoại</label>
                                        <input type="text" class="form-control" name="phone" id="customerPhoneInput">
                                    </div>
                                    <div class="mb-3">
                                        <label>Địa chỉ</label>
                                        <textarea class="form-control" name="address"
                                            id="customerAddressInput"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <input type="hidden" name="role" id="customerRoleInput" value="admin">
                                    </div>
                                    <div class="mb-3">
                                        <label>Trạng thái</label>
                                        <select class="form-select" name="hide" id="customerHideInput">
                                            <option value="0">Hoạt động</option>
                                            <option value="1">Ẩn</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Đóng</button>
                                    <button type="submit" class="btn btn-success">Lưu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/Project_Website/ProjectWeb/layout/jsBootstrap/bootstrap.bundle.min.js"></script>
    <script src="/Project_Website/ProjectWeb/layout/js/Admin.js"></script>


    <script>
        // Thêm hàm htmlspecialchars để thay thế hàm PHP
        function htmlspecialchars(str) {
            if (typeof str !== 'string') return '';
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function formatTime(datetimeStr) {
            const d = new Date(datetimeStr);
            return d.toLocaleString('vi-VN');
        }

        function renderNotifications(notifications) {
            const notificationList = document.getElementById('notificationList');
            notificationList.innerHTML = notifications.map(n => `
        <li class="notification-item-modal" style="display:flex;align-items:start;padding:16px 20px;border-bottom:1px solid #f0f0f0;cursor:pointer;">
            <span class="notification-icon-modal" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;font-size:22px;color:#1976d2;margin-right:12px;">
                <i class="fas ${getNotificationIcon(n)}"></i>
            </span>
            <div class="notification-content-modal flex-grow-1">
                <div class="notification-title-modal" style="font-weight:600;font-size:16px;margin-bottom:2px;">${n.title}</div>
                <div class="notification-desc-modal" style="font-size:15px;margin-bottom:2px;color:#444;">${n.content}</div>
                <div class="notification-time-modal" style="font-size:13px;color:#aaa;">${formatTime(n.created_at)}</div>
            </div>
        </li>
    `).join('');
            document.querySelector('.notification .badge').textContent = notifications.length;
        }

        function loadRecentNotifications() {
            // Kiểm tra xem các phần tử có tồn tại hay không
            const badgeElement = document.querySelector('.notification .badge');
            const notificationList = document.getElementById('notificationList');

            if (!notificationList || !badgeElement) {
                console.log('Các phần tử thông báo không tồn tại trên trang');
                return;
            }

            fetch('index.php?controller=admincustomer&action=getRecentCustomerNotifications')
                .then(res => {
                    if (!res.ok) {
                        throw new Error(`Lỗi HTTP: ${res.status}`);
                    }
                    const contentType = res.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error(`Định dạng không đúng: ${contentType}, cần application/json`);
                    }
                    return res.json();
                })
                .then(notifications => {
                    if (Array.isArray(notifications) && notifications.length > 0) {
                        renderNotifications(notifications);
                    } else {
                        badgeElement.textContent = '0';
                        if (notificationList) {
                            notificationList.innerHTML = '<li class="text-center p-3">Không có thông báo mới</li>';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    badgeElement.textContent = '!';
                    if (notificationList) {
                        notificationList.innerHTML = `<li class="text-center p-3 text-danger">Lỗi tải thông báo: ${error.message}</li>`;
                    }
                });
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Tải dữ liệu admin mặc định (đang hoạt động)
            const status = document.getElementById('filterStatus').value || 'active';
            const sort = document.getElementById('sortCustomer').value || '';

            // Chỉ tải lại nếu bảng trống (tránh load lại khi đã có dữ liệu từ server)
            const tbody = document.getElementById('customerTableBody');
            if (tbody && tbody.children.length === 0) {
                loadCustomerTable(status, sort);
            }

            // Các đoạn code khởi tạo khác...
            loadRecentNotifications();

            const bell = document.getElementById('notificationBell');
            const dropdown = document.getElementById('notificationDropdown');
            if (bell && dropdown) {
                bell.addEventListener('click', function (e) {
                    e.stopPropagation();
                    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
                });
                document.addEventListener('click', function (e) {
                    if (!dropdown.contains(e.target) && e.target !== bell) {
                        dropdown.style.display = 'none';
                    }
                });
            }

            // Gắn sự kiện cho nút "Xem tất cả" nếu có
            const viewAllBtn = document.querySelector('#notificationDropdown a');
            if (viewAllBtn) {
                viewAllBtn.onclick = function (e) {
                    e.preventDefault();
                    showAllNotifications(); // Hàm này bạn đã có ở trên
                };
            }
        });
    </script>

    <script>
        document.getElementById('filterStatus').addEventListener('change', function () {
            const value = this.value;
            let status = 'active';
            let sort = '';

            // Check if the value is a sort option
            if (value === 'newest' || value === 'oldest') {
                sort = value;
            } else {
                status = value;
            }

            // Clear sort dropdown selection if newest/oldest is selected
            if (sort === 'newest' || sort === 'oldest') {
                document.getElementById('sortCustomer').value = '';
            }

            // Tải lại bảng thay vì chuyển hướng
            loadCustomerTable(status, sort);

            // Cập nhật URL nhưng không reload trang
            const url = new URL(window.location);
            url.searchParams.set('status', status);
            if (sort) {
                url.searchParams.set('sort', sort);
            } else {
                url.searchParams.delete('sort');
            }
            window.history.pushState({}, '', url);
        });

        document.getElementById('sortCustomer').addEventListener('change', function () {
            const sort = this.value;
            const status = document.getElementById('filterStatus').value;

            // Reset filterStatus to 'active' if it was a sort option and a different sort is selected
            let actualStatus = status;
            if (status === 'newest' || status === 'oldest') {
                actualStatus = 'active';
                document.getElementById('filterStatus').value = 'active';
            }

            // Tải lại bảng thay vì chuyển hướng
            loadCustomerTable(actualStatus, sort);

            // Cập nhật URL nhưng không reload trang
            const url = new URL(window.location);
            url.searchParams.set('status', actualStatus);
            if (sort) {
                url.searchParams.set('sort', sort);
            } else {
                url.searchParams.delete('sort');
            }
            window.history.pushState({}, '', url);
        });

        document.getElementById('searchCustomerInput').addEventListener('input', function () {
            const keyword = this.value;
            if (keyword.length >= 2) {
                fetch(`index.php?controller=admincustomer&action=search&keyword=${encodeURIComponent(keyword)}`)
                    .then(res => {
                        if (!res.ok) {
                            throw new Error(`Lỗi HTTP: ${res.status}`);
                        }
                        const contentType = res.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            throw new Error(`Định dạng không đúng: ${contentType}, cần application/json`);
                        }
                        return res.json();
                    })
                    .then(customers => {
                        const tbody = document.getElementById('customerTableBody');
                        tbody.innerHTML = customers.map(customer => `
                    <tr data-customer-id="${customer.id_User}">
                        <td><input type="checkbox" class="form-check-input customer-checkbox" data-id="${customer.id_User}"></td>
                        <td>${htmlspecialchars(customer.name)}</td>
                        <td>${htmlspecialchars(customer.phone || '')}</td>
                        <td>${htmlspecialchars(customer.email)}</td>
                        <td>
                            <span class="status ${customer.hide == 1 ? 'inactive' : 'completed'}">
                                ${customer.hide == 1 ? 'Ẩn' : 'Đang hoạt động'}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-primary btn-edit-customer" title="Sửa" data-id="${customer.id_User}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `).join('');
                        document.querySelector('nav.pagination')?.classList.add('d-none');
                        rebindAll();
                    })
                    .catch(error => {
                        console.error('Error searching:', error);
                        const tbody = document.getElementById('customerTableBody');
                        tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Lỗi tìm kiếm: ${error.message}</td></tr>`;
                    });
            } else if (keyword.length === 0) {
                // Nếu xóa hết ký tự, chỉ clear bảng và hiện lại phân trang
                const tbody = document.getElementById('customerTableBody');
                tbody.innerHTML = '';
                document.querySelector('nav.pagination')?.classList.remove('d-none');

                // Nên tải lại dữ liệu
                const status = document.getElementById('filterStatus').value;
                const sort = document.getElementById('sortCustomer').value;
                loadCustomerTable(status, sort);
            }
        });

        // Gắn sự kiện cho nút con mắt (ẩn/hiện khách hàng)
        function bindEyeButtons() {
            document.querySelectorAll('.btn-view-customer').forEach(btn => {
                btn.onclick = function () {
                    const id = this.dataset.id;
                    const currentHide = this.dataset.hide == "1" ? 1 : 0;
                    const newHide = currentHide ? 0 : 1;
                    fetch('index.php?controller=admincustomer&action=toggleHide', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: id, hide: newHide })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.dataset.hide = newHide;
                                const row = this.closest('tr');
                                const statusSpan = row.querySelector('.status');
                                if (newHide == 1) {
                                    statusSpan.classList.remove('completed');
                                    statusSpan.classList.add('inactive');
                                    statusSpan.textContent = 'Ẩn';
                                    this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                                } else {
                                    statusSpan.classList.remove('inactive');
                                    statusSpan.classList.add('completed');
                                    statusSpan.textContent = 'Đang hoạt động';
                                    this.innerHTML = '<i class="fas fa-eye"></i>';
                                }
                            } else {
                                alert('Có lỗi xảy ra!');
                            }
                        });
                };
            });
        }
        bindEyeButtons();

        // Hiện modal thêm khách hàng
        const addBtn = document.getElementById('addCustomerBtn');
        addBtn.onclick = function () {
            document.getElementById('customerForm').reset();
            document.getElementById('customerId').value = '';
            document.getElementById('customerModalLabel').textContent = 'Thêm tài khoản admin';
            document.getElementById('customerPasswordInput').required = true;
            var modal = new bootstrap.Modal(document.getElementById('customerModal'));
            modal.show();
        };

        // Hiện modal sửa khách hàng
        function bindEditButtons() {
            document.querySelectorAll('.btn-edit-customer').forEach(btn => {
                btn.onclick = function () {
                    console.log('Edit button clicked');
                    const id = this.dataset.id;
                    console.log('Customer ID:', id);
                    fetch(`index.php?controller=admincustomer&action=getCustomer&id=${id}`)
                        .then(res => {
                            if (!res.ok) {
                                throw new Error(`Lỗi HTTP: ${res.status}`);
                            }
                            const contentType = res.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                throw new Error(`Định dạng không đúng: ${contentType}, cần application/json`);
                            }
                            return res.json();
                        })
                        .then(customer => {
                            console.log('Customer data received:', customer);

                            // Kiểm tra nếu có lỗi từ server
                            if (customer.error) {
                                throw new Error(customer.error);
                            }

                            document.getElementById('customerId').value = customer.id_User;
                            document.getElementById('customerNameInput').value = customer.name;
                            document.getElementById('customerEmailInput').value = customer.email;
                            document.getElementById('customerPhoneInput').value = customer.phone || '';
                            document.getElementById('customerAddressInput').value = customer.address || '';
                            // Always admin role
                            document.getElementById('customerRoleInput').value = 'admin';
                            document.getElementById('customerHideInput').value = customer.hide;
                            document.getElementById('customerPasswordInput').required = false;
                            document.getElementById('customerModalLabel').textContent = 'Sửa tài khoản admin';
                            console.log('Opening modal...');
                            var modal = new bootstrap.Modal(document.getElementById('customerModal'));
                            modal.show();
                        })
                        .catch(error => {
                            console.error('Error fetching customer data:', error);
                            alert(`Lỗi khi tải dữ liệu admin: ${error.message}`);
                        });
                };
            });
        }
        bindEditButtons();

        // Submit form thêm/sửa
        const customerForm = document.getElementById('customerForm');
        customerForm.onsubmit = function (e) {
            e.preventDefault();
            const id = document.getElementById('customerId').value;
            const data = {
                id: id,
                name: document.getElementById('customerNameInput').value,
                email: document.getElementById('customerEmailInput').value,
                password: document.getElementById('customerPasswordInput').value,
                phone: document.getElementById('customerPhoneInput').value,
                address: document.getElementById('customerAddressInput').value,
                // role is handled by the backend
                hide: document.getElementById('customerHideInput').value
            };
            let url = 'index.php?controller=admincustomer&action=addCustomer';
            if (id) url = 'index.php?controller=admincustomer&action=updateCustomer';

            // Hiển thị thông báo đang xử lý
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';

            fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
                .then(res => {
                    if (!res.ok) {
                        throw new Error(`Lỗi HTTP: ${res.status}`);
                    }
                    const contentType = res.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error(`Định dạng không đúng: ${contentType}, cần application/json`);
                    }
                    return res.json();
                })
                .then(resp => {
                    if (resp.success) {
                        loadRecentNotifications();
                        var modal = bootstrap.Modal.getInstance(document.getElementById('customerModal'));
                        if (modal) modal.hide();

                        // Hiển thị thông báo thành công
                        alert(`Đã ${id ? 'cập nhật' : 'thêm'} tài khoản admin thành công!`);

                        // Kiểm tra xem có cần tải trang 2 không
                        // Nếu là thêm mới và có đúng 5 admin trên trang hiện tại
                        const status = document.getElementById('filterStatus').value || 'active';
                        const sort = document.getElementById('sortCustomer').value || '';
                        const urlParams = new URLSearchParams(window.location.search);
                        const currentPage = parseInt(urlParams.get('page')) || 1;

                        // Get the total count to determine pagination
                        fetch(`index.php?controller=admincustomer&action=getCustomersData&status=${encodeURIComponent(status)}&sort=${encodeURIComponent(sort)}`)
                            .then(response => response.json())
                            .then(response => {
                                const totalItems = response.total || 0;
                                const limit = 5; // Số lượng item trên mỗi trang
                                const totalPages = Math.ceil(totalItems / limit);

                                if (!id) {
                                    // For new items
                                    // If we're on the last page and it's full, go to the new page
                                    const lastPageItems = totalItems - (currentPage - 1) * limit;

                                    if (currentPage === totalPages && lastPageItems > limit) {
                                        // We need a new page
                                        const newPage = currentPage + 1;
                                        const url = new URL(window.location);
                                        url.searchParams.set('page', newPage);
                                        window.history.pushState({}, '', url);
                                        loadCustomerTable(status, sort, newPage, limit);
                                    } else {
                                        // Stay on current page and reload
                                        loadCustomerTable(status, sort, currentPage, limit);
                                    }
                                } else {
                                    // For updates, just reload the current page
                                    loadCustomerTable(status, sort, currentPage, limit);
                                }
                            });
                    } else {
                        alert(resp.message || 'Có lỗi xảy ra khi lưu dữ liệu!');
                    }
                })
                .catch(error => {
                    console.error('Error submitting form:', error);
                    alert(`Lỗi khi lưu dữ liệu: ${error.message}`);
                })
                .finally(() => {
                    // Phục hồi trạng thái nút submit
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                });
        };

        // Thêm hàm load dữ liệu khách hàng
        function loadCustomerTable(status, sort, page = 1, limit = 5) {
            const tbody = document.getElementById('customerTableBody');
            tbody.innerHTML = '<tr><td colspan="6" class="text-center"><i class="fas fa-spinner fa-spin"></i> Đang tải dữ liệu...</td></tr>';

            fetch(`index.php?controller=admincustomer&action=getCustomersData&status=${encodeURIComponent(status)}&sort=${encodeURIComponent(sort)}&page=${page}&limit=${limit}`)
                .then(response => {
                    // Kiểm tra nếu response không ok, throw lỗi
                    if (!response.ok) {
                        throw new Error(`Lỗi HTTP: ${response.status}`);
                    }

                    // Kiểm tra content-type để đảm bảo server trả về JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error(`Định dạng không đúng: ${contentType}, cần application/json`);
                    }

                    return response.json();
                })
                .then(response => {
                    const data = response.data || [];
                    const total = response.total || 0; // Thêm thông tin tổng số
                    const totalPages = Math.ceil(total / limit);

                    tbody.innerHTML = '';

                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="6" class="text-center">Không có tài khoản admin nào.</td></tr>';
                        // Ẩn phân trang nếu không có dữ liệu
                        updatePagination(page, totalPages, status, sort);
                        return;
                    }

                    data.forEach(customer => {
                        const row = document.createElement('tr');
                        row.setAttribute('data-customer-id', customer.id_User);

                        row.innerHTML = `
                    <td>
                        <input type="checkbox" class="form-check-input customer-checkbox" data-id="${customer.id_User}">
                    </td>
                    <td>${htmlspecialchars(customer.name)}</td>
                    <td>${htmlspecialchars(customer.phone || '')}</td>
                    <td>${htmlspecialchars(customer.email)}</td>
                    <td>
                        <span class="status ${customer.hide == 1 ? 'inactive' : 'completed'}">
                            ${customer.hide == 1 ? 'Ẩn' : 'Đang hoạt động'}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-primary btn-edit-customer" title="Sửa" data-id="${customer.id_User}">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </td>
                `;
                        tbody.appendChild(row);
                    });

                    // Cập nhật phân trang dựa trên dữ liệu mới
                    updatePagination(page, totalPages, status, sort);

                    // Đính kèm lại sự kiện
                    rebindAll();
                })
                .catch(error => {
                    console.error('Error loading customers:', error);
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Có lỗi xảy ra khi tải dữ liệu: ${error.message}</td></tr>`;
                });
        }

        // Thêm hàm cập nhật phân trang
        function updatePagination(currentPage, totalPages, status = '', sort = '') {
            let paginationNav = document.querySelector('nav.pagination');

            // Xử lý khi không cần phân trang
            if (totalPages <= 1) {
                if (paginationNav) {
                    paginationNav.innerHTML = '';
                    paginationNav.style.display = 'none';
                }
                return;
            }

            // Nếu không có nav phân trang, tạo mới
            if (!paginationNav) {
                paginationNav = document.createElement('nav');
                paginationNav.className = 'pagination';
                const contentSection = document.querySelector('.content') || document.querySelector('.main-content');
                if (contentSection) {
                    // Chèn vào trước phần modal
                    const firstModal = contentSection.querySelector('.modal');
                    if (firstModal) {
                        contentSection.insertBefore(paginationNav, firstModal);
                    } else {
                        contentSection.appendChild(paginationNav);
                    }
                }
            }

            // Hiển thị phân trang
            paginationNav.style.display = 'block';

            // Xây dựng thanh phân trang
            let paginationHTML = '<ul class="pagination">';

            for (let i = 1; i <= totalPages; i++) {
                const isActive = i === currentPage ? 'active' : '';
                paginationHTML += `<li class="page-item ${isActive}">
            <a class="page-link" href="javascript:void(0)" data-page="${i}">${i}</a>
        </li>`;
            }

            paginationHTML += '</ul>';
            paginationNav.innerHTML = paginationHTML;

            // Gắn sự kiện cho các nút phân trang
            paginationNav.querySelectorAll('.page-link').forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const page = parseInt(this.getAttribute('data-page'));

                    // Cập nhật URL
                    const url = new URL(window.location);
                    url.searchParams.set('page', page);
                    window.history.pushState({}, '', url);

                    // Tải dữ liệu trang mới
                    loadCustomerTable(status, sort, page);
                });
            });
        }

        // Xóa khách hàng (thêm nút xóa vào mỗi dòng nếu muốn)
        function bindDeleteButtons() {
            document.querySelectorAll('.btn-delete-customer').forEach(btn => {
                btn.onclick = function () {
                    if (!confirm('Bạn có chắc muốn ẩn tài khoản admin này?')) return;
                    const id = this.dataset.id;
                    fetch('index.php?controller=admincustomer&action=deleteCustomer', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: id })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                loadRecentNotifications();
                                alert('Đã ẩn tài khoản admin thành công!');

                                // Get current page and status
                                const urlParams = new URLSearchParams(window.location.search);
                                const currentPage = parseInt(urlParams.get('page')) || 1;
                                const status = document.getElementById('filterStatus').value;
                                const sort = document.getElementById('sortCustomer').value;

                                // Check if we need to go to the previous page (if this is the last item on a page beyond page 1)
                                const currentRows = document.querySelectorAll('#customerTableBody tr').length;
                                if (currentRows <= 1 && currentPage > 1) {
                                    // Load the previous page
                                    const newPage = currentPage - 1;
                                    const url = new URL(window.location);
                                    url.searchParams.set('page', newPage);
                                    window.history.pushState({}, '', url);
                                    loadCustomerTable(status, sort, newPage);
                                } else {
                                    // Reload current page
                                    loadCustomerTable(status, sort, currentPage);
                                }
                            } else {
                                alert(data.message || 'Có lỗi xảy ra khi ẩn tài khoản admin!');
                            }
                        });
                };
            });
        }
        bindDeleteButtons();

        // Bổ sung logic bật/tắt nút xóa đã chọn và xóa nhiều khách hàng
        function updateDeleteBtnState() {
            const checked = document.querySelectorAll('.customer-checkbox:checked').length;
            document.getElementById('deleteSelectedBtn').disabled = checked === 0;
        }

        // Chọn tất cả
        const selectAll = document.getElementById('select-all-customer');
        if (selectAll) {
            selectAll.addEventListener('change', function () {
                const checked = this.checked;
                document.querySelectorAll('.customer-checkbox').forEach(cb => {
                    cb.checked = checked;
                });
                updateDeleteBtnState();
            });
        }
        // Khi chọn từng dòng
        const customerTable = document.getElementById('customerTableBody');
        if (customerTable) {
            customerTable.addEventListener('change', function (e) {
                if (e.target.classList.contains('customer-checkbox')) {
                    updateDeleteBtnState();
                }
            });
        }
        // Xử lý xóa đã chọn
        const deleteBtn = document.getElementById('deleteSelectedBtn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function () {
                const ids = Array.from(document.querySelectorAll('.customer-checkbox:checked')).map(cb => cb.dataset.id);
                if (ids.length === 0) return;
                if (!confirm('Bạn có chắc muốn ẩn các tài khoản admin đã chọn?')) return;
                fetch('index.php?controller=admincustomer&action=deleteSelected', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ids: ids })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            loadRecentNotifications();
                            alert(data.message || 'Đã ẩn tài khoản admin thành công!');

                            // Get current page and status
                            const urlParams = new URLSearchParams(window.location.search);
                            const currentPage = parseInt(urlParams.get('page')) || 1;
                            const status = document.getElementById('filterStatus').value;
                            const sort = document.getElementById('sortCustomer').value;

                            // Check if we need to go to the previous page (when deleting all items on current page except page 1)
                            const remainingRows = document.querySelectorAll('#customerTableBody tr').length - ids.length;

                            if (remainingRows <= 0 && currentPage > 1) {
                                // Load the previous page
                                const newPage = currentPage - 1;
                                const url = new URL(window.location);
                                url.searchParams.set('page', newPage);
                                window.history.pushState({}, '', url);
                                loadCustomerTable(status, sort, newPage);
                            } else {
                                // Reload current page
                                loadCustomerTable(status, sort, currentPage);
                            }
                        } else {
                            alert(data.message || 'Có lỗi xảy ra khi ẩn tài khoản admin!');
                        }
                    });
            });
        }

        // Gắn lại các nút sau khi render lại bảng (tìm kiếm)
        function rebindAll() {
            bindEditButtons();
            bindDeleteButtons();
            // Gắn lại sự kiện xóa đã chọn và checkbox
            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    const checked = this.checked;
                    document.querySelectorAll('.customer-checkbox').forEach(cb => {
                        cb.checked = checked;
                    });
                    updateDeleteBtnState();
                });
            }
            if (customerTable) {
                customerTable.addEventListener('change', function (e) {
                    if (e.target.classList.contains('customer-checkbox')) {
                        updateDeleteBtnState();
                    }
                });
            }
        }
        // Xem thông báo
        function getNotificationIcon(n) {
            if (n.title && n.title.includes('VIP')) return 'fa-star';
            if (n.title && n.title.includes('Khóa')) return 'fa-user-lock';
            if (n.title && n.title.includes('mới')) return 'fa-user-plus';
            return 'fa-user';
        }
        function showAllNotifications() {
            fetch('index.php?controller=admincustomer&action=getRecentCustomerNotifications&limit=100')
                .then(res => res.json())
                .then(allNoti => {
                    document.getElementById('allNotificationsList').innerHTML = allNoti.map(n => `
                <li class="notification-item-modal">
                    <span class="notification-icon-modal"><i class="fas ${getNotificationIcon(n)}"></i></span>
                    <div class="notification-content-modal flex-grow-1">
                        <div class="notification-title-modal">${n.title}</div>
                        <div class="notification-desc-modal">${n.content}</div>
                        <div class="notification-time-modal">${formatTime(n.created_at)}</div>
                    </div>
                </li>
            `).join('');
                    var modal = new bootstrap.Modal(document.getElementById('allNotificationsModal'));
                    modal.show();
                });
        }

        // Xử lý double click vào dòng khách hàng để xem chi tiết
        document.getElementById('customerTableBody').addEventListener('dblclick', function (e) {
            let tr = e.target.closest('tr[data-customer-id]');
            if (!tr) return;
            let id = tr.getAttribute('data-customer-id');
            fetch(`index.php?controller=admincustomer&action=getCustomer&id=${id}`)
                .then(res => res.json())
                .then(customer => {
                    // Cập nhật thông tin
                    document.getElementById('detailCustomerName').textContent = customer.name;
                    document.getElementById('detailCustomerEmail').textContent = customer.email;
                    document.getElementById('detailCustomerPhone').textContent = customer.phone || 'Chưa cập nhật';
                    document.getElementById('detailCustomerAddress').textContent = customer.address || 'Chưa cập nhật';

                    // Cập nhật role với badge
                    const roleBadge = document.getElementById('detailCustomerRole');
                    roleBadge.textContent = customer.role === 'admin' ? 'Admin' : 'Khách hàng';
                    roleBadge.className = 'badge ' + (customer.role === 'admin' ? 'bg-danger' : 'bg-primary');

                    // Cập nhật trạng thái với badge
                    const statusBadge = document.getElementById('detailCustomerHide');
                    statusBadge.textContent = customer.hide == 1 ? 'Đã ẩn' : 'Đang hoạt động';
                    statusBadge.className = 'badge ' + (customer.hide == 1 ? 'bg-danger' : 'bg-success');

                    // Hiển thị modal
                    var modal = new bootstrap.Modal(document.getElementById('customerDetailModal'));
                    modal.show();
                });
        });
    </script>

    <!-- Modal thông báo -->
    <div class="modal fade" id="allNotificationsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thông báo khách hàng</h5>
                </div>
                <div class="modal-body" style="padding:0;">
                    <ul id="allNotificationsList" class="notification-list-modal"></ul>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Chi Tiết Khách Hàng -->
    <div class="modal fade" id="customerDetailModal" tabindex="-1" aria-labelledby="customerDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="customerDetailModalLabel">
                        <i class="fas fa-user-circle me-2"></i>Chi tiết khách hàng
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Cột trái: Avatar và thông tin cơ bản -->
                        <div class="col-md-4 text-center border-end">
                            <div class="mb-4">
                                <img src="/Project_Website/ProjectWeb/upload/img/avatar.jpg" alt="Avatar"
                                    class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                            </div>
                            <h4 id="detailCustomerName" class="mb-3"></h4>
                            <div id="detailCustomerRole" class="badge bg-primary mb-3"></div>
                            <div id="detailCustomerHide" class="badge mb-3"></div>
                        </div>

                        <!-- Cột phải: Thông tin chi tiết -->
                        <div class="col-md-8">
                            <div class="info-group mb-3">
                                <label class="text-muted mb-1"><i class="fas fa-envelope me-2"></i>Email</label>
                                <p id="detailCustomerEmail" class="mb-3"></p>
                            </div>
                            <div class="info-group mb-3">
                                <label class="text-muted mb-1"><i class="fas fa-phone me-2"></i>Số điện thoại</label>
                                <p id="detailCustomerPhone" class="mb-3"></p>
                            </div>
                            <div class="info-group mb-3">
                                <label class="text-muted mb-1"><i class="fas fa-map-marker-alt me-2"></i>Địa chỉ</label>
                                <p id="detailCustomerAddress" class="mb-3"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Style cho modal chi tiết khách hàng */
        #customerDetailModal .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        #customerDetailModal .modal-header {
            border-radius: 15px 15px 0 0;
            padding: 1.5rem;
        }

        #customerDetailModal .modal-body {
            padding: 2rem;
        }

        #customerDetailModal .modal-footer {
            border-radius: 0 0 15px 15px;
            padding: 1rem 1.5rem;
        }

        #customerDetailModal .info-group label {
            font-size: 0.9rem;
            font-weight: 500;
        }

        #customerDetailModal .info-group p {
            font-size: 1.1rem;
            margin: 0;
            color: #2c3e50;
        }

        #customerDetailModal .badge {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            font-weight: 500;
        }

        #customerDetailModal .badge.bg-primary {
            background-color: #3498db !important;
        }

        #customerDetailModal .badge.bg-success {
            background-color: #2ecc71 !important;
        }

        #customerDetailModal .badge.bg-danger {
            background-color: #e74c3c !important;
        }
    </style>

    <!-- Modal Thùng rác -->
    <div class="modal fade" id="trashModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thùng rác - Tài khoản admin đã ẩn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all-trash" class="form-check-input"></th>
                                    <th>Tên tài khoản</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="trashTableBody">
                                <!-- Dữ liệu sẽ được load bằng AJAX -->
                                <tr>
                                    <td colspan="5" class="text-center">Đang tải dữ liệu...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-success" id="restoreSelectedBtn" disabled>
                        <i class="fas fa-trash-restore"></i> Khôi phục đã chọn
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Thêm mã JavaScript cho chức năng thùng rác
        document.addEventListener('DOMContentLoaded', function () {
            // Xử lý thùng rác
            const showTrashBtn = document.getElementById('showTrashBtn');
            const trashModalEl = document.getElementById('trashModal');
            const trashModal = trashModalEl ? new bootstrap.Modal(trashModalEl) : null;
            const trashTableBody = document.getElementById('trashTableBody');
            const restoreSelectedBtn = document.getElementById('restoreSelectedBtn');
            const selectAllTrashCheckbox = document.getElementById('select-all-trash');

            // Hiển thị modal thùng rác
            if (showTrashBtn && trashModal) {
                showTrashBtn.addEventListener('click', function () {
                    loadTrashCustomers();
                    trashModal.show();
                });
            }

            // Tải khách hàng trong thùng rác
            function loadTrashCustomers() {
                if (!trashTableBody) return;

                trashTableBody.innerHTML = '<tr><td colspan="5" class="text-center"><i class="fas fa-spinner fa-spin"></i> Đang tải dữ liệu...</td></tr>';

                // Gửi request AJAX để lấy danh sách khách hàng đã ẩn
                fetch('index.php?controller=admincustomer&action=getTrashCustomers')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        trashTableBody.innerHTML = '';

                        if (data.error) {
                            trashTableBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">${data.error}</td></tr>`;
                            return;
                        }

                        if (!data || data.length === 0) {
                            trashTableBody.innerHTML = '<tr><td colspan="5" class="text-center">Không có khách hàng nào trong thùng rác.</td></tr>';
                            return;
                        }

                        // Hiển thị khách hàng trong thùng rác
                        data.forEach(customer => {
                            const row = document.createElement('tr');
                            row.setAttribute('data-customer-id', customer.id_User);

                            row.innerHTML = `
                        <td>
                            <input type="checkbox" class="form-check-input trash-checkbox" data-id="${customer.id_User}">
                        </td>
                        <td>${htmlspecialchars(customer.name)}</td>
                        <td>${htmlspecialchars(customer.email)}</td>
                        <td>${htmlspecialchars(customer.phone || '')}</td>
                        <td>
                            <button class="btn btn-sm btn-success btn-restore-customer" title="Khôi phục" data-id="${customer.id_User}">
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
                        console.error('Error loading trash customers:', error);
                        trashTableBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Có lỗi xảy ra khi tải dữ liệu thùng rác.</td></tr>';
                    });
            }

            // Đính kèm sự kiện cho các phần tử trong thùng rác
            function attachTrashEventListeners() {
                // Chọn tất cả trong thùng rác
                if (selectAllTrashCheckbox) {
                    selectAllTrashCheckbox.checked = false;
                    selectAllTrashCheckbox.addEventListener('change', function () {
                        const checkboxes = document.querySelectorAll('.trash-checkbox');
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = this.checked;
                        });
                        updateRestoreSelectedButton();
                    });
                }

                // Cập nhật trạng thái nút khôi phục khi chọn/bỏ chọn
                document.querySelectorAll('.trash-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', updateRestoreSelectedButton);
                });

                // Nút khôi phục từng khách hàng
                document.querySelectorAll('.btn-restore-customer').forEach(button => {
                    button.addEventListener('click', function () {
                        const id = this.getAttribute('data-id');
                        restoreCustomers([id]);
                    });
                });
            }

            // Cập nhật trạng thái nút khôi phục đã chọn
            function updateRestoreSelectedButton() {
                if (!restoreSelectedBtn) return;

                const selectedCheckboxes = document.querySelectorAll('.trash-checkbox:checked');
                restoreSelectedBtn.disabled = selectedCheckboxes.length === 0;
            }

            // Xử lý nút khôi phục đã chọn
            if (restoreSelectedBtn) {
                restoreSelectedBtn.addEventListener('click', function () {
                    const selectedIds = Array.from(document.querySelectorAll('.trash-checkbox:checked'))
                        .map(checkbox => checkbox.getAttribute('data-id'));

                    if (selectedIds.length === 0) return;

                    restoreCustomers(selectedIds);
                });
            }

            // Khôi phục khách hàng
            function restoreCustomers(ids) {
                fetch('index.php?controller=admincustomer&action=restoreCustomers', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ids })
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert(data.message || 'Đã khôi phục thành công!');

                            // Tải lại danh sách thùng rác
                            loadTrashCustomers();

                            // Tải lại danh sách khách hàng chính mà không làm mới trang
                            const status = document.getElementById('filterStatus').value;
                            const sort = document.getElementById('sortCustomer').value;
                            const urlParams = new URLSearchParams(window.location.search);
                            const currentPage = parseInt(urlParams.get('page')) || 1;

                            // Reload the current page to show the restored items
                            loadCustomerTable(status, sort, currentPage);
                        } else {
                            alert(data.message || 'Có lỗi xảy ra khi khôi phục khách hàng.');
                        }
                    })
                    .catch(error => {
                        console.error('Error restoring customers:', error);
                        alert('Lỗi kết nối khi khôi phục khách hàng. Chi tiết: ' + error.message);
                    });
            }
        });
    </script>

    <!-- Modal Xem Khách Hàng -->
    <div class="modal fade" id="usersModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Danh sách khách hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="customer-filters mb-3">
                        <div class="filter-row d-flex flex-row flex-wrap gap-2 align-items-center">
                            <div class="filter-item flex-grow-1" style="min-width:220px;">
                                <div class="search-box">
                                    <i class="fas fa-search"></i>
                                    <input type="text" placeholder="Tìm kiếm khách hàng..." id="searchUserInput"
                                        style="width:100%;padding-left:36px;" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên khách hàng</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Địa chỉ</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                <!-- Dữ liệu sẽ được load bằng AJAX -->
                                <tr>
                                    <td colspan="6" class="text-center">Đang tải dữ liệu...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // JavaScript để xử lý modal danh sách khách hàng
        document.addEventListener('DOMContentLoaded', function () {
            const viewUsersBtn = document.getElementById('viewUsersBtn');
            const usersModalEl = document.getElementById('usersModal');
            const usersModal = usersModalEl ? new bootstrap.Modal(usersModalEl) : null;
            const usersTableBody = document.getElementById('usersTableBody');
            const searchUserInput = document.getElementById('searchUserInput');

            let allUsers = []; // Lưu toàn bộ danh sách khách hàng để tìm kiếm nhanh

            // Hiển thị modal danh sách khách hàng
            if (viewUsersBtn && usersModal) {
                viewUsersBtn.addEventListener('click', function () {
                    loadUsers();
                    usersModal.show();
                });
            }

            // Tải danh sách khách hàng
            function loadUsers() {
                if (!usersTableBody) return;

                usersTableBody.innerHTML = '<tr><td colspan="6" class="text-center"><i class="fas fa-spinner fa-spin"></i> Đang tải dữ liệu...</td></tr>';

                fetch('index.php?controller=admincustomer&action=getUsers')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            throw new Error(`Định dạng không đúng: ${contentType}, cần application/json`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        allUsers = data; // Lưu lại danh sách đầy đủ
                        renderUsers(data);
                    })
                    .catch(error => {
                        console.error('Error loading users:', error);
                        usersTableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Có lỗi xảy ra khi tải dữ liệu: ${error.message}</td></tr>`;
                    });
            }

            // Hiển thị danh sách khách hàng
            function renderUsers(users) {
                usersTableBody.innerHTML = '';

                if (!users || users.length === 0) {
                    usersTableBody.innerHTML = '<tr><td colspan="6" class="text-center">Không có khách hàng nào.</td></tr>';
                    return;
                }

                users.forEach(user => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                <td>${user.id_User}</td>
                <td>${htmlspecialchars(user.name || '')}</td>
                <td>${htmlspecialchars(user.email || '')}</td>
                <td>${htmlspecialchars(user.phone || '')}</td>
                <td>${htmlspecialchars(user.address || '')}</td>
                <td>
                    <span class="status ${user.hide == 1 ? 'inactive' : 'completed'}">
                        ${user.hide == 1 ? 'Ẩn' : 'Đang hoạt động'}
                    </span>
                </td>
            `;
                    usersTableBody.appendChild(row);
                });
            }

            // Tìm kiếm khách hàng
            if (searchUserInput) {
                searchUserInput.addEventListener('input', function () {
                    const keyword = this.value.toLowerCase().trim();

                    if (keyword.length === 0) {
                        renderUsers(allUsers);
                        return;
                    }

                    // Lọc khách hàng từ danh sách đã load
                    const filteredUsers = allUsers.filter(user =>
                        (user.name && user.name.toLowerCase().includes(keyword)) ||
                        (user.email && user.email.toLowerCase().includes(keyword)) ||
                        (user.phone && user.phone.toLowerCase().includes(keyword)) ||
                        (user.address && user.address.toLowerCase().includes(keyword))
                    );

                    renderUsers(filteredUsers);
                });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Lấy phần tử sidebar và nút toggle
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebarToggleBtn');

            console.log('Toggle button:', toggleBtn);
            console.log('Sidebar:', sidebar);

            // Gắn sự kiện click cho nút toggle
            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function () {
                    console.log('Toggle button clicked');
                    sidebar.classList.toggle('show');
                });
            }
        });
    </script>
</body>

</html>