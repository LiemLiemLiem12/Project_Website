<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý khách hàng - SR Store</title>
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
    pointer-events: none; /* Đảm bảo click vào icon vẫn focus được input */
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
                <div class="header-right" style="display: flex; align-items: center; gap: 1rem; margin-left: auto; position: relative;">
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
                        <img src="/Project_Website/ProjectWeb/upload/img/avatar.jpg" alt="Admin Avatar" class="profile-image">
                    </div>
                </div>
        </header>
        <div class="content" id="content-container">
            <div class="page-header d-flex justify-content-between align-items-center">
                <h1 class="mb-0">Quản lý Khách hàng</h1>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" id="addCustomerBtn">
                        <i class="fas fa-plus"></i> Thêm khách hàng
                    </button>
                    <button class="btn btn-danger" id="deleteSelectedBtn" disabled>
                        <i class="fas fa-trash"></i> Xóa đã chọn
                    </button>
                </div>
            </div>
    <div class="customer-filters mb-3">
    <div class="filter-row d-flex flex-row flex-wrap gap-2 align-items-center">
        <div class="filter-item flex-grow-1" style="min-width:220px;">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Tìm kiếm khách hàng..." id="searchCustomerInput" style="width:100%;padding-left:36px;" autocomplete="off">
            </div>
        </div>
        <div class="filter-item">
            <select class="filter-dropdown" id="filterStatus">
                <option value="" <?= empty($status) ? 'selected' : '' ?>>Trạng thái</option>
                <option value="active" <?= $status == 'active' ? 'selected' : '' ?>>Đang hoạt động</option>
                <option value="inactive" <?= $status == 'inactive' ? 'selected' : '' ?>>Ẩn</option>
            </select>
        </div>
        <div class="filter-item">
            <select class="filter-dropdown" id="sortCustomer">
                <option value="" <?= empty($sort) ? 'selected' : '' ?>>Sắp xếp theo</option>
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
                            <th>Tên khách hàng</th>
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
                                <input type="checkbox" class="form-check-input customer-checkbox" data-id="<?= $customer['id_User'] ?>">
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
                                    <button class="btn-view-customer" data-id="<?= $customer['id_User'] ?>" data-hide="<?= $customer['hide'] ?? 0 ?>" title="Ẩn/Hiện">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary btn-edit-customer" title="Sửa" data-id="<?= $customer['id_User'] ?>">
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
                    <a class="page-link" href="index.php?controller=admincustomer&status=<?= urlencode($status) ?>&sort=<?= urlencode($sort) ?>&page=<?= $i ?>"><?= $i ?></a>
                  </li>
                <?php endfor; ?>
              </ul>
            </nav>
            <?php endif; ?>

            <!-- Modal Thêm/Sửa Khách Hàng -->
            <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="customerForm">
                            <div class="modal-header">
                                <h5 class="modal-title" id="customerModalLabel">Thêm khách hàng</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" id="customerId">
                                <div class="mb-3">
                                    <label>Họ tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="customerNameInput" required>
                                </div>
                                <div class="mb-3">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" id="customerEmailInput" required>
                                </div>
                                <div class="mb-3">
                                    <label>Mật khẩu <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password" id="customerPasswordInput" required autocomplete="new-password">
                                </div>
                                <div class="mb-3">
                                    <label>Số điện thoại</label>
                                    <input type="text" class="form-control" name="phone" id="customerPhoneInput">
                                </div>
                                <div class="mb-3">
                                    <label>Địa chỉ</label>
                                    <textarea class="form-control" name="address" id="customerAddressInput"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Nhóm khách hàng</label>
                                    <select class="form-select" name="role" id="customerRoleInput">
                                        <option value="user">Thường</option>
                                        <option value="admin">Admin</option>
                                    </select>
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
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
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
    fetch('index.php?controller=admincustomer&action=getRecentCustomerNotifications')
        .then(res => res.json())
        .then(renderNotifications);
}

document.addEventListener('DOMContentLoaded', function() {
    loadRecentNotifications();

    const bell = document.getElementById('notificationBell');
    const dropdown = document.getElementById('notificationDropdown');
    bell.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });
    document.addEventListener('click', function(e) {
        if (!dropdown.contains(e.target) && e.target !== bell) {
            dropdown.style.display = 'none';
        }
    });

    // Gắn sự kiện cho nút "Xem tất cả" nếu có
    const viewAllBtn = document.querySelector('#notificationDropdown a');
    if (viewAllBtn) {
        viewAllBtn.onclick = function(e) {
            e.preventDefault();
            showAllNotifications(); // Hàm này bạn đã có ở trên
        };
    }
});
</script>

<script>
document.getElementById('filterStatus').addEventListener('change', function() {
    const status = this.value;
    const sort = document.getElementById('sortCustomer').value;
    window.location = `index.php?controller=admincustomer&status=${status}&sort=${sort}&page=1`;
});
document.getElementById('sortCustomer').addEventListener('change', function() {
    const sort = this.value;
    const status = document.getElementById('filterStatus').value;
    window.location = `index.php?controller=admincustomer&status=${status}&sort=${sort}&page=1`;
});
document.getElementById('searchCustomerInput').addEventListener('input', function() {
    const keyword = this.value;
    if (keyword.length >= 2) {
        fetch(`index.php?controller=admincustomer&action=search&keyword=${encodeURIComponent(keyword)}`)
            .then(res => res.json())
            .then(customers => {
                const tbody = document.getElementById('customerTableBody');
                tbody.innerHTML = customers.map(customer => `
                    <tr data-customer-id="${customer.id_User}">
                        <td><input type="checkbox" class="form-check-input customer-checkbox" data-id="${customer.id_User}"></td>
                        <td>${customer.name}</td>
                        <td>${customer.phone}</td>
                        <td>${customer.email}</td>
                        <td>
                            <span class="status ${customer.hide == 1 ? 'inactive' : 'completed'}">
                                ${customer.hide == 1 ? 'Ẩn' : 'Đang hoạt động'}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-view-customer" data-id="${customer.id_User}" data-hide="${customer.hide ?? 0}" title="Ẩn/Hiện">
                                    <i class="fas fa-eye${customer.hide == 1 ? '-slash' : ''}"></i>
                                </button>
                                <button class="btn btn-sm btn-primary btn-edit-customer" title="Sửa" data-id="${customer.id_User}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete-customer" title="Xóa" data-id="${customer.id_User}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `).join('');
                document.querySelector('nav.pagination')?.classList.add('d-none');
                rebindAll();
            });
    } else if (keyword.length === 0) {
        // Nếu xóa hết ký tự, chỉ clear bảng và hiện lại phân trang
        const tbody = document.getElementById('customerTableBody');
        tbody.innerHTML = '';
        document.querySelector('nav.pagination')?.classList.remove('d-none');
    }
});

// Gắn sự kiện cho nút con mắt (ẩn/hiện khách hàng)
function bindEyeButtons() {
    document.querySelectorAll('.btn-view-customer').forEach(btn => {
        btn.onclick = function() {
            const id = this.dataset.id;
            const currentHide = this.dataset.hide == "1" ? 1 : 0;
            const newHide = currentHide ? 0 : 1;
            fetch('index.php?controller=admincustomer&action=toggleHide', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({id: id, hide: newHide})
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
addBtn.onclick = function() {
    document.getElementById('customerForm').reset();
    document.getElementById('customerId').value = '';
    document.getElementById('customerModalLabel').textContent = 'Thêm khách hàng';
    document.getElementById('customerPasswordInput').required = true;
    var modal = new bootstrap.Modal(document.getElementById('customerModal'));
    modal.show();
};

// Hiện modal sửa khách hàng
function bindEditButtons() {
    document.querySelectorAll('.btn-edit-customer').forEach(btn => {
        btn.onclick = function() {
            console.log('Edit button clicked');
            const id = this.dataset.id;
            console.log('Customer ID:', id);
            fetch(`index.php?controller=admincustomer&action=getCustomer&id=${id}`)
                .then(res => res.json())
                .then(customer => {
                    console.log('Customer data received:', customer);
                    document.getElementById('customerId').value = customer.id_User;
                    document.getElementById('customerNameInput').value = customer.name;
                    document.getElementById('customerEmailInput').value = customer.email;
                    document.getElementById('customerPhoneInput').value = customer.phone;
                    document.getElementById('customerAddressInput').value = customer.address;
                    document.getElementById('customerRoleInput').value = customer.role;
                    document.getElementById('customerHideInput').value = customer.hide;
                    document.getElementById('customerPasswordInput').required = false;
                    document.getElementById('customerModalLabel').textContent = 'Sửa khách hàng';
                    console.log('Opening modal...');
                    var modal = new bootstrap.Modal(document.getElementById('customerModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error fetching customer data:', error);
                });
        };
    });
}
bindEditButtons();

// Submit form thêm/sửa
const customerForm = document.getElementById('customerForm');
customerForm.onsubmit = function(e) {
    e.preventDefault();
    const id = document.getElementById('customerId').value;
    const data = {
        id: id,
        name: document.getElementById('customerNameInput').value,
        email: document.getElementById('customerEmailInput').value,
        password: document.getElementById('customerPasswordInput').value,
        phone: document.getElementById('customerPhoneInput').value,
        address: document.getElementById('customerAddressInput').value,
        role: document.getElementById('customerRoleInput').value,
        hide: document.getElementById('customerHideInput').value
    };
    let url = 'index.php?controller=admincustomer&action=addCustomer';
    if (id) url = 'index.php?controller=admincustomer&action=updateCustomer';
    fetch(url, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(resp => {
        if (resp.success) {
            loadRecentNotifications();
            var modal = bootstrap.Modal.getInstance(document.getElementById('customerModal'));
            if (modal) modal.hide();
        // Reload lại bảng (hoặc reload trang nếu muốn)
            location.reload();
        } else {
            alert('Có lỗi xảy ra!');
        }
    });
};

// Xóa khách hàng (thêm nút xóa vào mỗi dòng nếu muốn)
function bindDeleteButtons() {
    document.querySelectorAll('.btn-delete-customer').forEach(btn => {
        btn.onclick = function() {
            if (!confirm('Bạn có chắc muốn xóa khách hàng này?')) return;
            const id = this.dataset.id;
            fetch('index.php?controller=admincustomer&action=deleteCustomer', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({id: id})
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    loadRecentNotifications(); 
                    // Đếm số dòng còn lại trên trang
                    const tbody = document.getElementById('customerTableBody');
                    const rows = tbody.querySelectorAll('tr').length;
                    // Lấy số trang hiện tại từ URL
                    const urlParams = new URLSearchParams(window.location.search);
                    let page = parseInt(urlParams.get('page') || '1');
                    if (rows <= 1 && page > 1) {
                        // Nếu chỉ còn 1 dòng (dòng vừa xóa) và không phải trang 1, chuyển về trang trước
                        urlParams.set('page', page - 1);
                        window.location.search = urlParams.toString();
                    } else {
                        window.location.reload();
                    }
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi xóa!');
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
    selectAll.addEventListener('change', function() {
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
    customerTable.addEventListener('change', function(e) {
        if (e.target.classList.contains('customer-checkbox')) {
            updateDeleteBtnState();
        }
    });
}
// Xử lý xóa đã chọn
const deleteBtn = document.getElementById('deleteSelectedBtn');
if (deleteBtn) {
    deleteBtn.addEventListener('click', function() {
        const ids = Array.from(document.querySelectorAll('.customer-checkbox:checked')).map(cb => cb.dataset.id);
        if (ids.length === 0) return;
        if (!confirm('Bạn có chắc muốn xóa các khách hàng đã chọn?')) return;
        fetch('index.php?controller=admincustomer&action=deleteSelected', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ids: ids})
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                loadRecentNotifications();
                // Đếm số dòng còn lại trên trang
                const tbody = document.getElementById('customerTableBody');
                const rows = tbody.querySelectorAll('tr').length;
                // Lấy số trang hiện tại từ URL
                const urlParams = new URLSearchParams(window.location.search);
                let page = parseInt(urlParams.get('page') || '1');
                if (rows <= ids.length && page > 1) {
                    // Nếu số dòng còn lại nhỏ hơn hoặc bằng số dòng vừa xóa và không phải trang 1, chuyển về trang trước
                    urlParams.set('page', page - 1);
                    window.location.search = urlParams.toString();
                } else {
                    window.location.reload();
                }
            } else {
                alert(data.message || 'Có lỗi xảy ra khi xóa!');
            }
        });
    });
}

// Gắn lại các nút sau khi render lại bảng (tìm kiếm)
function rebindAll() {
    bindEyeButtons();
    bindEditButtons();
    bindDeleteButtons();
    // Gắn lại sự kiện xóa đã chọn và checkbox
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const checked = this.checked;
            document.querySelectorAll('.customer-checkbox').forEach(cb => {
                cb.checked = checked;
            });
            updateDeleteBtnState();
        });
    }
    if (customerTable) {
        customerTable.addEventListener('change', function(e) {
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
document.getElementById('customerTableBody').addEventListener('dblclick', function(e) {
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
<div class="modal fade" id="customerDetailModal" tabindex="-1" aria-labelledby="customerDetailModalLabel" aria-hidden="true">
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
              <img src="/Project_Website/ProjectWeb/upload/img/avatar.jpg" alt="Avatar" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
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
  box-shadow: 0 0 20px rgba(0,0,0,0.1);
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

</body>
</html>