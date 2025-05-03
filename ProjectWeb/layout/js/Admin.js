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



  // Kích hoạt nút xóa nhiều khi có checkbox được chọn
  const checkboxes = document.querySelectorAll('.product-checkbox');
  const deleteBtn = document.getElementById('deleteSelectedBtn');

  function updateDeleteBtn() {
    const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
    deleteBtn.disabled = !anyChecked;
  }

  checkboxes.forEach(cb => cb.addEventListener('change', updateDeleteBtn));

  // Xử lý khi nhấn nút xóa nhiều
  deleteBtn.addEventListener('click', function() {
    if (confirm('Bạn có chắc muốn xóa các sản phẩm đã chọn?')) {
      // Lấy danh sách mã SP đã chọn
      const selected = Array.from(checkboxes)
        .filter(cb => cb.checked)
        .map(cb => cb.closest('tr').querySelector('td:nth-child(2)').textContent.trim());
      // Demo: In ra console, thực tế sẽ gửi request xóa về backend
      console.log('Xóa các sản phẩm:', selected);
      alert('Đã xóa các sản phẩm (demo, cần kết nối backend để thực hiện xóa thực tế)');
      // Reset checkbox và nút
      checkboxes.forEach(cb => cb.checked = false);
      updateDeleteBtn();
    }
  });





// preview ảnh 

document.getElementById('productImage').addEventListener('change', function(event) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = ''; // Xóa preview cũ
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


// Tagename
const tagInput = document.getElementById('tagInput');
const tagInputContainer = document.getElementById('tagInputContainer');
const productTags = document.getElementById('productTags');
let tags = [];

function renderTags() {
  // Xóa các tag cũ (trừ input)
  tagInputContainer.querySelectorAll('.tag-item').forEach(e => e.remove());
  tags.forEach((tag, idx) => {
    const tagEl = document.createElement('span');
    tagEl.className = 'tag-item';
    tagEl.textContent = tag;
    // Nút xóa
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
  // Cập nhật input ẩn để submit form
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
    // Xóa tag cuối khi backspace ở đầu input
    tags.pop();
    renderTags();
  }
});


 // Hide
document.querySelectorAll('.btn-view').forEach(function(btn) {
    btn.addEventListener('click', function() {
        // Lấy trạng thái hiện tại
        let hide = btn.getAttribute('data-hide');
        // Đảo trạng thái
        hide = hide === "0" ? "1" : "0";
        btn.setAttribute('data-hide', hide);

        // Đổi icon
        const icon = btn.querySelector('i');
        if (hide === "1") {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
            icon.style.opacity = "0.5";
        } else {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
            icon.style.opacity = "1";
        }

        // Tooltip cập nhật lại
        btn.title = hide === "0" ? "Đang hiện (bấm để ẩn)" : "Đang ẩn (bấm để hiện)";

        // TODO: Gửi AJAX về backend để cập nhật trường hide trong database
    });

    // Khởi tạo icon đúng trạng thái khi load trang
    const icon = btn.querySelector('i');
    if (btn.getAttribute('data-hide') === "1") {
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
        icon.style.opacity = "0.5";
    } else {
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
        icon.style.opacity = "1";
    }
});


// Chỉnh sửa btn
document.querySelectorAll('.btn-edit').forEach(function(btn) {
    btn.addEventListener('click', function() {
        // Lấy dòng sản phẩm (tr)
        const row = btn.closest('tr');
        // Lấy dữ liệu từ data-attribute
        document.getElementById('productName').value = row.getAttribute('data-name') || '';
        // Nếu có các trường khác, điền tương tự:
        // Hình ảnh: không thể fill file input, có thể show preview hoặc để trống
        CKEDITOR.instances['productDesc'].setData(row.getAttribute('data-description') || '');
        document.getElementById('productCategory').value = row.getAttribute('data-id_category') || '';
        document.getElementById('productPrice').value = row.getAttribute('data-current_price') || '';
        document.getElementById('productStatus').value = row.getAttribute('data-hide') || '0';
        // ... điền các trường khác nếu có ...

        // Mở modal thêm/sửa sản phẩm
        var modal = new bootstrap.Modal(document.getElementById('addProductModal'));
        modal.show();

        // (Tùy chọn) Đổi tiêu đề modal thành "Chỉnh sửa sản phẩm"
        document.getElementById('addProductModalLabel').textContent = "Chỉnh sửa Sản Phẩm";
        // (Tùy chọn) Đổi nút submit thành "Cập nhật"
        document.querySelector('#addProductForm button[type="submit"]').textContent = "Cập nhật sản phẩm";
    });
});

// Khi mở modal thêm mới, reset lại tiêu đề/nút
document.querySelector('[data-bs-target="#addProductModal"]').addEventListener('click', function() {
    document.getElementById('addProductModalLabel').textContent = "Thêm Sản Phẩm Mới";
    document.querySelector('#addProductForm button[type="submit"]').textContent = "Thêm sản phẩm";
    // Reset form nếu cần
    document.getElementById('addProductForm').reset();
    CKEDITOR.instances['productDesc'].setData('');
});

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
