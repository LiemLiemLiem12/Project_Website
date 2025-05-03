document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle for mobile
    const toggleSidebar = document.getElementById('toggleSidebar');
    const closeSidebar = document.getElementById('closeSidebar');
    const sidebar = document.getElementById('sidebar');
    
    if (toggleSidebar) {
        toggleSidebar.addEventListener('click', function() {
            sidebar.classList.add('show');
        });
    }
    
    if (closeSidebar) {
        closeSidebar.addEventListener('click', function() {
            sidebar.classList.remove('show');
        });
    }
    
    // Close sidebar when clicking outside
    document.addEventListener('click', function(event) {
        const isClickInside = sidebar.contains(event.target) || 
                            (toggleSidebar && toggleSidebar.contains(event.target));
        
        if (!isClickInside && sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
        }
    });
    
    // Handle sidebar navigation
    const navLinks = document.querySelectorAll('.nav-links li');
    const contentViews = document.querySelectorAll('.page-content');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Remove active class from all links
            navLinks.forEach(l => l.classList.remove('active'));
            // Add active class to clicked link
            this.classList.add('active');
            
            // Hide all content views
            contentViews.forEach(view => view.style.display = 'none');
            
            // Show selected content view
            const page = this.getAttribute('data-page');
            document.getElementById(`${page}-content`).style.display = 'block';
        });
    });

    // Handle logout
    const logoutBtn = document.querySelector('.logout');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Bạn có chắc chắn muốn đăng xuất?')) {
                window.location.href = 'login.html';
            }
        });
    }
    
    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Handle "Select All" checkbox
    const selectAll = document.getElementById('select-all');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.order-checkbox, .product-checkbox, .category-checkbox, .customer-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        });
    }

    // Initialize charts if elements exist
    if (document.getElementById('yearlySalesChart')) {
        const yearlySalesCtx = document.getElementById('yearlySalesChart').getContext('2d');
        new Chart(yearlySalesCtx, {
            type: 'line',
            data: {
                labels: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'],
                datasets: [{
                    label: 'Đơn hàng',
                    data: [65, 78, 90, 85, 92, 110, 120, 130, 125, 132, 145, 150],
                    borderColor: '#6344dd',
                    backgroundColor: 'rgba(99, 68, 221, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    if (document.getElementById('monthlyRevenueChart')) {
        const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
        new Chart(monthlyRevenueCtx, {
            type: 'bar',
            data: {
                labels: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'],
                datasets: [{
                    label: 'Doanh thu (triệu đồng)',
                    data: [25, 29, 32, 28, 30, 35, 38, 40, 42, 45, 48, 52],
                    backgroundColor: 'rgba(99, 68, 221, 0.7)',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Doanh thu (triệu đồng)'
                        }
                    }
                }
            }
        });
    }
    
    if (document.getElementById('categoryChart')) {
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Áo thun', 'Quần jean', 'Áo khoác', 'Áo sơ mi', 'Phụ kiện'],
                datasets: [{
                    data: [35, 25, 15, 15, 10],
                    backgroundColor: [
                        '#6344dd',
                        '#4ca3ff',
                        '#ff6b6b',
                        '#feca57',
                        '#1dd1a1'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '65%'
            }
        });
    }

    // Check if we're on the AdminOrder page
    if (document.querySelector('.order-checkbox')) {
        initializeAdminOrder();
    }
});

// Handle Status Changes
function updateOrderStatus(orderId, status) {
    console.log(`Updating order ${orderId} to status: ${status}`);
}

// Handle Delete Confirmations
function confirmDelete(type, id) {
    if (confirm(`Bạn có chắc chắn muốn xóa ${type} này?`)) {
        console.log(`Deleting ${type} with ID: ${id}`);
    }
}

// Handle Form Submissions
function handleFormSubmit(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    console.log('Form submitted:', Object.fromEntries(formData));
}

// Handle Image Preview
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            if (preview) {
                preview.src = e.target.result;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Handle Search
function handleSearch(searchTerm) {
    console.log('Searching for:', searchTerm);
}

// Handle Export Functions
function exportToExcel() {
    console.log('Exporting to Excel...');
}

function exportToPDF() {
    console.log('Exporting to PDF...');
}

// --- Product Form Submit ---
if (document.getElementById('addProductForm')) {
    document.getElementById('addProductForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // Lấy dữ liệu từ form
        const formData = new FormData(this);
        // Demo: In ra console, thực tế sẽ gửi về server xử lý
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        // Đóng modal sau khi thêm (giả lập)
        var addProductModal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
        addProductModal.hide();
        // Reset form
        this.reset();
        // Thông báo (demo)
        alert('Đã thêm sản phẩm (demo, cần kết nối backend để lưu vào database)');
    });
}

// --- Product Checkbox Delete ---
if (document.querySelectorAll('.product-checkbox').length && document.getElementById('deleteSelectedBtn')) {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const deleteBtn = document.getElementById('deleteSelectedBtn');
    function updateDeleteBtn() {
        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
        deleteBtn.disabled = !anyChecked;
    }
    checkboxes.forEach(cb => cb.addEventListener('change', updateDeleteBtn));
    deleteBtn.addEventListener('click', function() {
        if (confirm('Bạn có chắc muốn xóa các sản phẩm đã chọn?')) {
            const selected = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.closest('tr').querySelector('td:nth-child(2)').textContent.trim());
            console.log('Xóa các sản phẩm:', selected);
            alert('Đã xóa các sản phẩm (demo, cần kết nối backend để thực hiện xóa thực tế)');
            checkboxes.forEach(cb => cb.checked = false);
            updateDeleteBtn();
        }
    });
}

// --- Product Image Preview ---
if (document.getElementById('productImage') && document.getElementById('imagePreview')) {
    document.getElementById('productImage').addEventListener('change', function(event) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        const files = event.target.files;
        if (files) {
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'rounded border';
                        img.style.height = '60px';
                        img.style.width = '60px';
                        img.style.objectFit = 'cover';
                        img.style.marginRight = '8px';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
}

// --- Product Tag Input ---
if (document.getElementById('tagInput') && document.getElementById('tagInputContainer') && document.getElementById('productTags')) {
    const tagInput = document.getElementById('tagInput');
    const tagInputContainer = document.getElementById('tagInputContainer');
    const productTags = document.getElementById('productTags');
    let tags = [];
    function renderTags() {
        tagInputContainer.querySelectorAll('.tag-item').forEach(e => e.remove());
        tags.forEach((tag, idx) => {
            const tagEl = document.createElement('span');
            tagEl.className = 'tag-item';
            tagEl.textContent = tag;
            const removeBtn = document.createElement('span');
            removeBtn.className = 'remove-tag';
            removeBtn.textContent = '×';
            removeBtn.onclick = () => {
                tags.splice(idx, 1);
                renderTags();
            };
            tagEl.appendChild(removeBtn);
            tagInputContainer.insertBefore(tagEl, tagInput);
        });
        productTags.value = tags.join(',');
    }
    tagInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && this.value.trim() !== '') {
            e.preventDefault();
            const newTag = this.value.trim();
            if (!tags.includes(newTag)) {
                tags.push(newTag);
                renderTags();
            }
            this.value = '';
        } else if (e.key === 'Backspace' && this.value === '' && tags.length > 0) {
            tags.pop();
            renderTags();
        }
    });
}

// --- Product Double Click Row ---
if (document.querySelectorAll('.product-table tbody tr.product-row').length) {
    document.querySelectorAll('.product-table tbody tr.product-row').forEach(function(row) {
        row.addEventListener('dblclick', function() {
            // Lấy dữ liệu từ data-attribute
            document.getElementById('detailCode').textContent = row.getAttribute('data-id_product') || '';
            document.getElementById('detailName').textContent = row.getAttribute('data-name') || '';
            document.getElementById('detailCategory').textContent = row.getAttribute('data-id_category') || '';
            document.getElementById('detailOriginalPrice').textContent = row.getAttribute('data-original_price') || '';
            document.getElementById('detailCurrentPrice').textContent = row.getAttribute('data-current_price') || '';
            document.getElementById('detailDiscount').textContent = row.getAttribute('data-discount_percent') ? '-' + row.getAttribute('data-discount_percent') + '%' : '';
            document.getElementById('detailStock').textContent = row.getAttribute('data-stock') || '';
            document.getElementById('detailStatus').textContent = row.getAttribute('data-hide') === "0" ? "Còn hàng" : "Hết hàng";
            document.getElementById('detailClick').textContent = row.getAttribute('data-click_count') || '';
            document.getElementById('detailCreated').textContent = row.getAttribute('data-created_at') || '';
            document.getElementById('detailUpdated').textContent = row.getAttribute('data-updated_at') || '';
            document.getElementById('detailLink').textContent = row.getAttribute('data-link') || '';
            document.getElementById('detailMeta').textContent = row.getAttribute('data-meta') || '';
            document.getElementById('detailOrder').textContent = row.getAttribute('data-order') || '';
            document.getElementById('detailTags').textContent = row.getAttribute('data-tags') || '';
            document.getElementById('detailPolicyReturn').src = row.getAttribute('data-policy_return') || '';
            document.getElementById('detailPolicyWarranty').src = row.getAttribute('data-policy_warranty') || '';
            document.getElementById('detailDesc').innerHTML = row.getAttribute('data-description') || '';
            document.getElementById('detailImage').src = row.getAttribute('data-main_image') || '';
            // Gallery ảnh phụ
            const gallery = document.getElementById('detailGallery');
            gallery.innerHTML = '';
            const imgs = (row.getAttribute('data-img') || '').split(',');
            imgs.forEach(src => {
                if (src.trim()) {
                    const img = document.createElement('img');
                    img.src = src.trim();
                    img.className = 'rounded border';
                    img.style.height = '50px';
                    img.style.width = '50px';
                    img.style.objectFit = 'cover';
                    img.style.marginRight = '6px';
                    gallery.appendChild(img);
                }
            });
            // Hiện modal
            var modal = new bootstrap.Modal(document.getElementById('productDetailModal'));
            modal.show();
        });
    });
}

// AdminOrder specific functions
function initializeAdminOrder() {
    // Initialize multiple delete functionality
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const deleteOrderBtn = document.getElementById('deleteSelectedOrderBtn');
    
    if (orderCheckboxes.length > 0 && deleteOrderBtn) {
        // Update delete button state based on checkbox selection
        function updateDeleteOrderBtn() {
            const anyChecked = Array.from(orderCheckboxes).some(cb => cb.checked);
            deleteOrderBtn.disabled = !anyChecked;
        }

        // Add event listeners to checkboxes
        orderCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateDeleteOrderBtn);
        });

        // Handle delete button click
        deleteOrderBtn.addEventListener('click', function() {
            // Lấy tất cả checkbox đơn hàng được chọn
            const checkedOrderCheckboxes = document.querySelectorAll('.order-checkbox:checked');
            const selectedOrders = Array.from(checkedOrderCheckboxes).map(cb => cb.getAttribute('data-id'));
            if (selectedOrders.length === 0) {
                alert('Vui lòng chọn ít nhất một đơn hàng để xóa!');
                return;
            }
            if (confirm('Bạn có chắc chắn muốn xóa các đơn hàng đã chọn?')) {
                // Demo: Xóa trên giao diện
                selectedOrders.forEach(id => {
                    const row = document.querySelector(`tr[data-order-id="${id}"]`);
                    if (row) row.remove();
                });
                // TODO: Gửi request xóa về backend nếu cần
                alert('Đã xóa các đơn hàng đã chọn (demo, cần kết nối backend để thực hiện xóa thực tế)');
            }
        });
    }

    // Initialize view order details functionality
    const viewButtons = document.querySelectorAll('.btn-view-order');
    if (viewButtons.length > 0) {
        viewButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                // Đảo trạng thái hide
                let hide = btn.getAttribute('data-hide');
                hide = hide === '1' ? '0' : '1';
                btn.setAttribute('data-hide', hide);
                // Đổi icon và style
                const icon = btn.querySelector('i');
                if (hide === '1') {
                    btn.classList.add('hide');
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    btn.classList.remove('hide');
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
            // Khởi tạo đúng trạng thái khi load trang
            const icon = btn.querySelector('i');
            if (btn.getAttribute('data-hide') === '1') {
                btn.classList.add('hide');
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                btn.classList.remove('hide');
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }
}

// Function to populate order details modal
function populateOrderDetailsModal(order) {
    // Update modal content with order details
    document.getElementById('orderId').textContent = order.id;
    document.getElementById('orderDate').textContent = order.created_at;
    document.getElementById('customerName').textContent = order.customer_name;
    document.getElementById('customerEmail').textContent = order.customer_email;
    document.getElementById('orderStatus').textContent = order.status;
    document.getElementById('orderTotal').textContent = order.total_amount;
    
    // Populate order items table
    const itemsTable = document.getElementById('orderItemsTable');
    itemsTable.innerHTML = ''; // Clear existing content
    
    order.items.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.product_name}</td>
            <td>${item.quantity}</td>
            <td>${item.price}</td>
            <td>${item.subtotal}</td>
        `;
        itemsTable.appendChild(row);
    });
}

// --- AdminOrder Double Click Row ---
if (document.querySelector('tr[data-order-id]')) {
    document.querySelectorAll('tr[data-order-id]').forEach(function(row) {
        row.addEventListener('dblclick', function() {
            // Lấy thông tin từ các ô trong dòng
            const orderId = row.getAttribute('data-order-id');
            const tds = row.querySelectorAll('td');
            // Demo dữ liệu chi tiết
            document.getElementById('orderId').textContent = orderId;
            document.getElementById('orderDate').textContent = tds[3]?.textContent.trim() || '';
            document.getElementById('orderStatus').textContent = tds[6]?.textContent.trim() || '';
            document.getElementById('orderTotal').textContent = tds[4]?.textContent.trim() || '';
            // Thông tin khách hàng (demo)
            document.getElementById('customerName').textContent = tds[2]?.textContent.trim() || '';
            document.getElementById('customerEmail').textContent = 'khachhang@email.com';
            document.getElementById('customerPhone').textContent = '0901234567';
            document.getElementById('customerAddress').textContent = '123 Đường ABC, Quận 1, TP.HCM';
            // Thông tin thanh toán (demo)
            document.getElementById('paymentMethod').textContent = tds[5]?.textContent.trim() || '';
            document.getElementById('orderNote').textContent = 'Giao giờ hành chính';
            // Sản phẩm đặt (demo)
            const itemsTable = document.getElementById('orderItemsTable');
            itemsTable.innerHTML = `
                <tr>
                    <td>Áo thun nam</td>
                    <td>2</td>
                    <td>250,000đ</td>
                    <td>500,000đ</td>
                </tr>
                <tr>
                    <td>Quần jeans nữ</td>
                    <td>1</td>
                    <td>499,000đ</td>
                    <td>499,000đ</td>
                </tr>
            `;
            // Hiện modal
            var modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
            modal.show();
        });
    });
}
