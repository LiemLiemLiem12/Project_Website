<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý danh mục - SR Store</title>
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
                            <div style="padding: 12px 16px; border-bottom: 1px solid #eee; font-weight: 600;">Thông báo danh mục</div>
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
                <h1 class="mb-0">Quản lý Danh mục</h1>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" id="addCategoryBtn">
                        <i class="fas fa-plus"></i> Thêm danh mục
                    </button>
                    <button class="btn btn-danger" id="deleteSelectedBtn" disabled>
                        <i class="fas fa-trash"></i> Xóa đã chọn
                    </button>
                    <button class="btn btn-secondary" id="showTrashBtn">
                        <i class="fas fa-trash-restore"></i> Thùng rác
                    </button>
                </div>
            </div>
    <div class="category-filters mb-3">
    <div class="filter-row d-flex flex-row flex-wrap gap-2 align-items-center">
        <div class="filter-item flex-grow-1" style="min-width:220px;">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Tìm kiếm danh mục..." id="searchCategoryInput" class="form-control" style="width:100%;padding-left:36px;" autocomplete="off">
            </div>
        </div>
        <div class="filter-item">
            <select class="filter-dropdown form-select" id="filterStatus">
                <option value="" <?= empty($status) ? 'selected' : '' ?>>Trạng thái</option>
                <option value="active" <?= ($status ?? '') == 'active' ? 'selected' : '' ?>>Đang hoạt động</option>
                <option value="newest" <?= ($status ?? '') == 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                <option value="oldest" <?= ($status ?? '') == 'oldest' ? 'selected' : '' ?>>Cũ nhất</option>
            </select>
        </div>
        <div class="filter-item">
            <select class="filter-dropdown form-select" id="sortCategory">
                <option value="" <?= empty($sort) ? 'selected' : '' ?>>Sắp xếp theo</option>
                <option value="name-asc" <?= ($sort ?? '') == 'name-asc' ? 'selected' : '' ?>>Tên (A-Z)</option>
                <option value="name-desc" <?= ($sort ?? '') == 'name-desc' ? 'selected' : '' ?>>Tên (Z-A)</option>
            </select>
        </div>
    </div>
</div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all-category" class="form-check-input"></th>
                            <th>Tên danh mục</th>
                            <th>Ảnh</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="categoryTableBody">
                        <?php foreach ($categories as $category): ?>
                        <tr data-category-id="<?= $category['ID'] ?>">
                            <td>
                                <input type="checkbox" class="form-check-input category-checkbox" data-id="<?= $category['ID'] ?>">
                            </td>
                            <td><?= htmlspecialchars($category['name']) ?></td>
                            <td>
                                <?php if (!empty($category['image'])): ?>
                                    <img src="<?= htmlspecialchars($category['image']) ?>" alt="Ảnh" style="width:40px;height:40px;object-fit:cover;">
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status <?= ($category['hide'] ?? 0) ? 'inactive' : 'completed' ?>">
                                    <?= ($category['hide'] ?? 0) ? 'Ẩn' : 'Đang hoạt động' ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-primary btn-edit-category" title="Sửa" data-id="<?= $category['ID'] ?>">
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
            // Ensure $limit, $total, and $page are set before using them for pagination
            $currentPage = $page ?? 1;
            $currentLimit = $limit ?? 5; // Default limit if not set
            $totalItems = $total ?? 0;
            $totalPages = ($currentLimit > 0 && $totalItems > 0) ? ceil($totalItems / $currentLimit) : 0;
            
            if ($totalPages > 1): ?>
            <nav>
              <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                  <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                    <a class="page-link" href="index.php?controller=admincategory&status=<?= urlencode($status ?? '') ?>&sort=<?= urlencode($sort ?? '') ?>&page=<?= $i ?>"><?= $i ?></a>
                  </li>
                <?php endfor; ?>
              </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Thêm/Sửa Danh mục -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalTitle">Thêm danh mục mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="categoryForm">
                    <input type="hidden" id="categoryId">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Tên danh mục</label>
                        <input type="text" class="form-control" id="categoryName" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoryImage" class="form-label">Ảnh danh mục</label>
                        <input type="file" class="form-control" id="categoryImage" name="image" accept="image/*">
                        <div id="categoryImagePreview" class="mt-2"></div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="categoryHide">
                        <label class="form-check-label" for="categoryHide">Ẩn danh mục</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="saveCategory">Lưu</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="deleteConfirmMessage">Bạn có chắc chắn muốn xóa danh mục này không?</p>
                <p class="text-muted small">Lưu ý: Danh mục sẽ được ẩn chứ không bị xóa hoàn toàn khỏi hệ thống.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Xóa</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thùng rác -->
<div class="modal fade" id="trashModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thùng rác - Danh mục đã ẩn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all-trash" class="form-check-input"></th>
                                <th>Tên danh mục</th>
                                <th>Ảnh</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="trashTableBody">
                            <!-- Dữ liệu sẽ được load bằng AJAX -->
                            <tr>
                                <td colspan="3" class="text-center">Đang tải dữ liệu...</td>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Helper function to escape HTML special characters
function htmlspecialchars(str) {
    if (typeof str !== 'string') {
        return '';
    }
    return str.replace(/[&<>"']/g, function (match) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[match];
    });
}

// Hàm hỗ trợ gửi request JSON
function sendJsonRequest(url, method, data) {
    return fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: data ? JSON.stringify(data) : null
    })
    .then(response => {
        // Kiểm tra response
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        
        // Kiểm tra content-type
        const contentType = response.headers.get('content-type');
        if (!contentType || contentType.indexOf('application/json') === -1) {
            return response.text().then(text => {
                console.error('Phản hồi không phải JSON:', text);
                throw new Error('Phản hồi không phải JSON, nhận được: ' + text.substring(0, 100));
            });
        }
        
        return response.json();
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
    const sidebar = document.getElementById('sidebar');
    
    if (sidebarToggleBtn) {
        sidebarToggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
    
    if (sidebarCloseBtn) {
        sidebarCloseBtn.addEventListener('click', function() {
            sidebar.classList.remove('active');
        });
    }

    // Notification dropdown
    const notificationBell = document.getElementById('notificationBell');
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    if (notificationBell && notificationDropdown) {
        notificationBell.addEventListener('click', function(event) {
            event.stopPropagation();
            const isCurrentlyDisplayed = notificationDropdown.style.display === 'block';
            notificationDropdown.style.display = isCurrentlyDisplayed ? 'none' : 'block';
            
            if (!isCurrentlyDisplayed) { // Only load if opening
                loadNotifications();
            }
        });
        
        document.addEventListener('click', function(event) {
            if (notificationDropdown && !notificationBell.contains(event.target) && !notificationDropdown.contains(event.target)) {
                notificationDropdown.style.display = 'none';
            }
        });
    }

    // Load notifications
    function loadNotifications() {
        sendJsonRequest('index.php?controller=admincategory&action=getRecentCategoryNotifications', 'GET')
            .then(data => {
                const notificationList = document.getElementById('notificationList');
                notificationList.innerHTML = ''; // Clear previous notifications
                
                if (data.length === 0) {
                    notificationList.innerHTML = '<li style="padding: 16px; text-align: center;">Không có thông báo mới</li>';
                    return;
                }
                
                data.forEach(notification => {
                    const item = document.createElement('li');
                    item.className = 'notification-item-modal';
                    
                    let formattedDate = 'Không rõ thời gian';
                    if (notification.created_at) {
                        try {
                            const date = new Date(notification.created_at);
                            // Check if date is valid
                            if (!isNaN(date.getTime())) {
                               formattedDate = date.toLocaleDateString('vi-VN') + ' ' + date.toLocaleTimeString('vi-VN');
                            }
                        } catch (e) {
                            console.error("Error formatting date:", e, "for notification:", notification.created_at);
                        }
                    }
                    
                    item.innerHTML = `
                        <div class="notification-icon-modal">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div>
                            <div class="notification-title-modal">${htmlspecialchars(notification.title || '')}</div>
                            <div class="notification-desc-modal">${htmlspecialchars(notification.content || '')}</div>
                            <div class="notification-time-modal">${formattedDate}</div>
                        </div>
                    `;
                     if (notification.link) {
                         item.addEventListener('click', () => { window.location.href = notification.link; });
                    }
                    notificationList.appendChild(item);
                });
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                const notificationList = document.getElementById('notificationList');
                if(notificationList) notificationList.innerHTML = '<li style="padding: 16px; text-align: center; color: red;">Lỗi tải thông báo</li>';
            });
    }

    // Filter categories
    const filterStatusEl = document.getElementById('filterStatus'); // Renamed for clarity
    const sortCategoryEl = document.getElementById('sortCategory');
    
    if (filterStatusEl) {
        filterStatusEl.addEventListener('change', function() {
            const selectedStatus = this.value;
            // Nếu chọn Mới nhất hoặc Cũ nhất, vô hiệu hóa dropdown sắp xếp
            if (selectedStatus === 'newest' || selectedStatus === 'oldest') {
                if (sortCategoryEl) {
                    sortCategoryEl.value = '';
                    sortCategoryEl.disabled = true;
                }
            } else {
                if (sortCategoryEl) {
                    sortCategoryEl.disabled = false;
                }
            }
            loadCategoriesWithAjax();
        });
    }
    
    if (sortCategoryEl) {
        sortCategoryEl.addEventListener('change', function() {
            loadCategoriesWithAjax();
        });
    }
    
    // Kiểm tra trạng thái ban đầu để vô hiệu hóa dropdown sắp xếp nếu cần
    if (filterStatusEl && sortCategoryEl) {
        const currentStatus = filterStatusEl.value;
        if (currentStatus === 'newest' || currentStatus === 'oldest') {
            sortCategoryEl.disabled = true;
        }
    }
    
    // Hàm tải danh mục bằng AJAX
    function loadCategoriesWithAjax(page = 1) {
        const statusVal = filterStatusEl ? filterStatusEl.value : '';
        const sortVal = sortCategoryEl && !sortCategoryEl.disabled ? sortCategoryEl.value : '';
        const keyword = document.getElementById('searchCategoryInput') ? document.getElementById('searchCategoryInput').value.trim() : '';
        
        // Hiển thị loading
        const tableBody = document.getElementById('categoryTableBody');
        tableBody.innerHTML = '<tr><td colspan="4" class="text-center"><i class="fas fa-spinner fa-spin"></i> Đang tải dữ liệu...</td></tr>';
        
        // Cập nhật URL với trang hiện tại mà không reload trang
        if (page !== 1) {
            const url = new URL(window.location);
            url.searchParams.set('page', page);
            window.history.pushState({}, '', url);
        } else {
            const url = new URL(window.location);
            url.searchParams.delete('page');
            window.history.pushState({}, '', url);
        }
        
        // Gửi request AJAX
        fetch(`index.php?controller=admincategory&action=ajaxSearch&status=${encodeURIComponent(statusVal)}&sort=${encodeURIComponent(sortVal)}&keyword=${encodeURIComponent(keyword)}&page=${page}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                tableBody.innerHTML = '';
                
                if (!data.categories || data.categories.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Không tìm thấy danh mục nào.</td></tr>';
                    // Xóa phân trang nếu không có dữ liệu
                    updatePagination({totalPages: 0, currentPage: 1});
                    return;
                }
                
                // Hiển thị danh mục
                data.categories.forEach(category => {
                    const row = document.createElement('tr');
                    row.setAttribute('data-category-id', category.ID);
                    
                    const isHidden = category.hide == 1;
                    row.innerHTML = `
                        <td>
                            <input type="checkbox" class="form-check-input category-checkbox" data-id="${category.ID}">
                        </td>
                        <td>${htmlspecialchars(category.name)}</td>
                        <td>
                            ${category.image ? `<img src="${htmlspecialchars(category.image)}" style="width:40px;height:40px;object-fit:cover;">` : ''}
                        </td>
                        <td>
                            <span class="status ${isHidden ? 'inactive' : 'completed'}">
                                ${isHidden ? 'Ẩn' : 'Đang hoạt động'}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-primary btn-edit-category" title="Sửa" data-id="${category.ID}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
                
                // Cập nhật phân trang
                updatePagination(data.pagination);
                
                // Đính kèm lại sự kiện
                attachEventListeners();
            })
            .catch(error => {
                console.error('Error loading categories:', error);
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Có lỗi xảy ra khi tải dữ liệu.</td></tr>';
            });
    }
    
    // Cập nhật phân trang
    function updatePagination(pagination) {
        const paginationContainer = document.querySelector('nav .pagination');
        if (!paginationContainer) return;
        
        if (!pagination || pagination.totalPages <= 1) {
            // Ẩn phân trang nếu chỉ có 1 trang hoặc không có dữ liệu
            const parentNav = paginationContainer.closest('nav');
            if (parentNav) {
                parentNav.style.display = 'none';
            }
            paginationContainer.innerHTML = '';
            return;
        }
        
        // Hiển thị phân trang nếu nó bị ẩn trước đó
        const parentNav = paginationContainer.closest('nav');
        if (parentNav) {
            parentNav.style.display = 'block';
        }
        
        let paginationHTML = '';
        for (let i = 1; i <= pagination.totalPages; i++) {
            paginationHTML += `
                <li class="page-item ${i == pagination.currentPage ? 'active' : ''}">
                    <a class="page-link" href="javascript:void(0)" data-page="${i}">${i}</a>
                </li>
            `;
        }
        
        paginationContainer.innerHTML = paginationHTML;
        
        // Đính kèm sự kiện cho các nút phân trang
        document.querySelectorAll('.pagination .page-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = this.getAttribute('data-page');
                loadCategoriesWithAjax(page);
            });
        });
    }
    
    // Search categories
    const searchInput = document.getElementById('searchCategoryInput');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadCategoriesWithAjax();
            }, 500);
        });
    }

    // Select all categories
    const selectAllCheckbox = document.getElementById('select-all-category');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.category-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateDeleteSelectedButton();
        });
    }
    
    function updateDeleteSelectedButton() {
        const selectedCheckboxes = document.querySelectorAll('.category-checkbox:checked');
        const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
        
        if (deleteSelectedBtn) {
            deleteSelectedBtn.disabled = selectedCheckboxes.length === 0;
        }
    }
    
    document.addEventListener('change', function(event) { // Listen on document for dynamically added checkboxes
        if (event.target.classList.contains('category-checkbox')) {
            updateDeleteSelectedButton();
        }
    });

    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
    const deleteConfirmModalEl = document.getElementById('deleteConfirmModal');
    const deleteConfirmModal = deleteConfirmModalEl ? new bootstrap.Modal(deleteConfirmModalEl) : null;
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    const deleteConfirmMessageEl = document.getElementById('deleteConfirmMessage');


    // Thay đổi đoạn mã xử lý nút deleteSelectedBtn
if (deleteSelectedBtn && deleteConfirmModal && confirmDeleteBtn && deleteConfirmMessageEl) {
    deleteSelectedBtn.addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.category-checkbox:checked'))
            .map(checkbox => checkbox.getAttribute('data-id'));
        
        if (selectedIds.length === 0) return;
        
        // Kiểm tra xem có đang xóa tất cả mục trên trang không
        const totalItemsOnPage = document.querySelectorAll('.category-checkbox').length;
        const isHidingAllItems = totalItemsOnPage === selectedIds.length;
        const currentPage = parseInt(new URLSearchParams(window.location.search).get('page') || 1);
        
        let confirmMessage = `Bạn có chắc chắn muốn xóa ${selectedIds.length} danh mục đã chọn không?`;
        if (isHidingAllItems && currentPage > 1) {
            confirmMessage += ` Bạn sẽ được chuyển về trang trước đó.`;
        }
        
        deleteConfirmMessageEl.textContent = confirmMessage;
        deleteConfirmModal.show();
        
        const confirmHandler = function() {
            hideCategories(selectedIds);
            deleteConfirmModal.hide();
            confirmDeleteBtn.removeEventListener('click', confirmHandler);
        };
        confirmDeleteBtn.addEventListener('click', confirmHandler);
    });
}
    
    function hideCategories(ids) {
    // Lấy thông tin trang hiện tại và số lượng item trên trang
    const currentPage = parseInt(new URLSearchParams(window.location.search).get('page') || 1);
    const status = new URLSearchParams(window.location.search).get('status') || '';
    const sort = new URLSearchParams(window.location.search).get('sort') || '';
    
    // Đếm số item trên trang và số item được chọn để xóa
    const totalItemsOnPage = document.querySelectorAll('.category-checkbox').length;
    const isHidingAllItems = totalItemsOnPage === ids.length;
    
    sendJsonRequest('index.php?controller=admincategory&action=deleteSelected', 'POST', { ids })
        .then(data => {
            if (data.success) {
                alert('Đã xóa thành công!');
                loadCategoriesWithAjax(currentPage);
                loadTrashCategories();
            } else {
                alert(data.message || 'Có lỗi xảy ra khi xóa danh mục.');
            }
        })
        .catch(error => {
            console.error('Error hiding categories:', error);
            alert('Lỗi kết nối khi xóa danh mục. Chi tiết: ' + error.message);
        });
}

    const addCategoryBtn = document.getElementById('addCategoryBtn');
    const categoryModalEl = document.getElementById('categoryModal');
    const categoryModal = categoryModalEl ? new bootstrap.Modal(categoryModalEl) : null;
    const categoryForm = document.getElementById('categoryForm');
    const categoryModalTitle = document.getElementById('categoryModalTitle');
    const categoryIdInput = document.getElementById('categoryId');
    const categoryNameInput = document.getElementById('categoryName');
    const categoryHideCheckbox = document.getElementById('categoryHide');
    const categoryImageInput = document.getElementById('categoryImage');
    const categoryImagePreview = document.getElementById('categoryImagePreview');
    
    if (addCategoryBtn && categoryModal && categoryForm && categoryModalTitle && categoryIdInput && categoryNameInput && categoryHideCheckbox && categoryImageInput && categoryImagePreview) {
        addCategoryBtn.addEventListener('click', function() {
            categoryModalTitle.textContent = 'Thêm danh mục mới';
            categoryForm.reset();
            categoryIdInput.value = '';
            categoryModal.show();
        });
    }

    const saveBtn = document.getElementById('saveCategory');
    if (saveBtn && categoryModal && categoryIdInput && categoryNameInput && categoryHideCheckbox && categoryImageInput && categoryImagePreview) {
        saveBtn.addEventListener('click', function() {
            const id = categoryIdInput.value;
            const name = categoryNameInput.value.trim();
            const hide = categoryHideCheckbox.checked ? 1 : 0;
            
            if (!name) {
                alert('Vui lòng nhập tên danh mục.');
                categoryNameInput.focus();
                return;
            }
            
            let url = 'index.php?controller=admincategory&action=addCategory';
            if (id) {
                url = 'index.php?controller=admincategory&action=updateCategory';
            }
            
            const formData = new FormData();
            formData.append('name', name);
            formData.append('hide', hide);
            
            if (id) {
                formData.append('id', id);
            }
            
            if (categoryImageInput.files.length > 0) {
                formData.append('image', categoryImageInput.files[0]);
            }
            
            // Gửi FormData trực tiếp không dùng JSON.stringify
            fetch(url, {
                method: 'POST',
                body: formData // Không cần Content-Type, trình duyệt tự xử lý
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                
                const contentType = response.headers.get('content-type');
                if (!contentType || contentType.indexOf('application/json') === -1) {
                    return response.text().then(text => {
                        console.error('Phản hồi không phải JSON:', text);
                        throw new Error('Phản hồi không phải JSON, nhận được: ' + text.substring(0, 100));
                    });
                }
                
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    categoryModal.hide();
                    alert(id ? 'Đã cập nhật danh mục!' : 'Đã thêm danh mục mới!');
                    
                    // Lấy trang hiện tại từ URL
                    const urlParams = new URLSearchParams(window.location.search);
                    let currentPage = parseInt(urlParams.get('page')) || 1;
                    
                    // Nếu thêm mới, hãy kiểm tra xem có cần chuyển sang trang mới không
                    if (!id) {
                        // Tải tổng số danh mục để quyết định có tạo trang mới không
                        sendJsonRequest('index.php?controller=admincategory&action=getCategoriesCount', 'GET')
                            .then(countData => {
                                const totalItems = countData.count || 0;
                                const itemsPerPage = 5; // Số item trên mỗi trang
                                const totalPages = Math.ceil(totalItems / itemsPerPage);
                                
                                // Kiểm tra nếu thêm item tạo thêm trang mới
                                if (totalPages > Math.ceil((totalItems - 1) / itemsPerPage)) {
                                    // Chuyển đến trang mới
                                    loadCategoriesWithAjax(totalPages);
                                } else {
                                    // Tải lại trang hiện tại
                                    loadCategoriesWithAjax(currentPage);
                                }
                            })
                            .catch(error => {
                                console.error('Error getting categories count:', error);
                                // Mặc định tải lại trang hiện tại nếu có lỗi
                                loadCategoriesWithAjax(currentPage);
                            });
                    } else {
                        // Nếu cập nhật, chỉ cần tải lại trang hiện tại
                        loadCategoriesWithAjax(currentPage);
                    }
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi lưu danh mục.');
                }
            })
            .catch(error => {
                console.error('Error saving category:', error);
                alert('Lỗi kết nối khi lưu danh mục. Chi tiết: ' + error.message);
            });
        });
    }

    function attachEventListeners() {
        document.querySelectorAll('.btn-edit-category').forEach(button => {
            // Remove existing listener to prevent duplicates if attachEventListeners is called multiple times on the same elements
            // This is a simple way; a more robust solution might involve checking if a listener already exists or using a flag.
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);

            newButton.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                
                sendJsonRequest(`index.php?controller=admincategory&action=getCategory&id=${id}`, 'GET')
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }
                        if (categoryModal && categoryForm && categoryModalTitle && categoryIdInput && categoryNameInput && categoryHideCheckbox && categoryImageInput && categoryImagePreview) {
                            categoryModalTitle.textContent = 'Sửa danh mục';
                            categoryIdInput.value = data.ID;
                            categoryNameInput.value = data.name;
                            categoryHideCheckbox.checked = data.hide == 1;
                            categoryModal.show();
                        }
                    })
                    .catch(error => {
                        console.error('Error getting category details:', error);
                        alert('Lỗi tải thông tin danh mục: ' + error.message);
                    });
            });
        });
        
        updateDeleteSelectedButton(); // Ensure delete button state is correct
        const selectAllCategoryCheckbox = document.getElementById('select-all-category');
        if (selectAllCategoryCheckbox) { // Reset select-all checkbox
             selectAllCategoryCheckbox.checked = document.querySelectorAll('.category-checkbox:checked').length === document.querySelectorAll('.category-checkbox').length && document.querySelectorAll('.category-checkbox').length > 0;
        }
    }

    // Initial call to attach event listeners
    attachEventListeners();

    // Xử lý thùng rác
    const showTrashBtn = document.getElementById('showTrashBtn');
    const trashModalEl = document.getElementById('trashModal');
    const trashModal = trashModalEl ? new bootstrap.Modal(trashModalEl) : null;
    const trashTableBody = document.getElementById('trashTableBody');
    const restoreSelectedBtn = document.getElementById('restoreSelectedBtn');
    const selectAllTrashCheckbox = document.getElementById('select-all-trash');
    
    // Hiển thị modal thùng rác
    if (showTrashBtn && trashModal) {
        showTrashBtn.addEventListener('click', function() {
            loadTrashCategories();
            trashModal.show();
        });
    }
    
    // Tải danh mục trong thùng rác
    function loadTrashCategories() {
        if (!trashTableBody) return;
        
        trashTableBody.innerHTML = '<tr><td colspan="3" class="text-center"><i class="fas fa-spinner fa-spin"></i> Đang tải dữ liệu...</td></tr>';
        
        sendJsonRequest('index.php?controller=admincategory&action=getTrashCategories', 'GET')
            .then(data => {
                trashTableBody.innerHTML = '';
                
                if (data.error) {
                    trashTableBody.innerHTML = `<tr><td colspan="3" class="text-center text-danger">${data.error}</td></tr>`;
                    return;
                }
                
                if (!data || data.length === 0) {
                    trashTableBody.innerHTML = '<tr class="table-active"><td colspan="4" class="text-center">Không có danh mục nào trong thùng rác.</td></tr>';
                    return;
                }
                
                // Hiển thị danh mục trong thùng rác
                data.forEach(category => {
                    const row = document.createElement('tr');
                    row.setAttribute('data-category-id', category.ID);
                    
                    row.innerHTML = `
                        <td>
                            <input type="checkbox" class="form-check-input trash-checkbox" data-id="${category.ID}">
                        </td>
                        <td>${htmlspecialchars(category.name)}</td>
                        <td>
                            ${category.image ? `<img src="${htmlspecialchars(category.image)}" style="width:40px;height:40px;object-fit:cover;">` : ''}
                        </td>
                        <td>
                            <button class="btn btn-sm btn-success btn-restore-category" title="Khôi phục" data-id="${category.ID}">
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
                console.error('Error loading trash categories:', error);
                trashTableBody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Có lỗi xảy ra khi tải dữ liệu thùng rác.</td></tr>';
            });
    }
    
    // Đính kèm sự kiện cho các phần tử trong thùng rác
    function attachTrashEventListeners() {
        // Chọn tất cả trong thùng rác
        if (selectAllTrashCheckbox) {
            selectAllTrashCheckbox.addEventListener('change', function() {
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
        
        // Nút khôi phục từng danh mục
        document.querySelectorAll('.btn-restore-category').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                restoreCategories([id]);
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
        restoreSelectedBtn.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.trash-checkbox:checked'))
                .map(checkbox => checkbox.getAttribute('data-id'));
            
            if (selectedIds.length === 0) return;
            
            restoreCategories(selectedIds);
        });
    }
    
    // Khôi phục danh mục
    function restoreCategories(ids) {
        sendJsonRequest('index.php?controller=admincategory&action=restoreCategories', 'POST', { ids })
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Đã khôi phục thành công!');
                    
                    // Tải lại danh sách thùng rác
                    loadTrashCategories();
                    
                    // Lấy trang hiện tại từ URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const currentPage = parseInt(urlParams.get('page')) || 1;
                    
                    // Tải lại danh sách danh mục chính với trang hiện tại
                    loadCategoriesWithAjax(currentPage);
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi khôi phục danh mục.');
                }
            })
            .catch(error => {
                console.error('Error restoring categories:', error);
                alert('Lỗi kết nối khi khôi phục danh mục. Chi tiết: ' + error.message);
            });
    }
});
</script>
</body>
</html>