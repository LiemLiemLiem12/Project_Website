// ProjectWeb/layout/js/category-filter-ajax.js
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo các thành phần lọc
    initFilters();
    
    // Khởi tạo chức năng sắp xếp
    initSorting();
    
    // Khởi tạo các nút thêm vào giỏ hàng và xem nhanh
    initProductActions();
});

// Khởi tạo các bộ lọc
function initFilters() {
    // Lọc theo giá
    const priceRange = document.getElementById('price-range');
    const priceValue = document.getElementById('price-value');
    
    if (priceRange && priceValue) {
        // Cập nhật hiển thị giá khi người dùng kéo thanh trượt
        priceRange.addEventListener('input', function() {
            const formattedPrice = Number(this.value).toLocaleString('vi-VN') + '₫';
            priceValue.textContent = formattedPrice;
        });
        
        // Áp dụng bộ lọc khi người dùng thả thanh trượt
        priceRange.addEventListener('change', function() {
            applyFilters();
        });
    }
    
    // Lọc theo kích cỡ
    const sizeCheckboxes = document.querySelectorAll('.filter-option input[type="checkbox"]');
    sizeCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            applyFilters();
        });
    });
}

// Khởi tạo chức năng sắp xếp
function initSorting() {
    const sortSelect = document.getElementById('sort-by');
    
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            applyFilters();
        });
    }
}

// Lấy tất cả tham số lọc hiện tại
function getFilterParams() {
    const params = new URLSearchParams();
    
    // Thêm ID danh mục
    const categoryId = getCurrentCategoryId();
    if (categoryId) {
        params.append('id', categoryId);
    }
    
    // Thêm tham số sắp xếp
    const sortSelect = document.getElementById('sort-by');
    if (sortSelect) {
        params.append('sort', sortSelect.value);
    }
    
    // Thêm khoảng giá
    const priceRange = document.getElementById('price-range');
    if (priceRange) {
        params.append('price', priceRange.value);
    }
    
    // Thêm kích cỡ đã chọn
    const sizeCheckboxes = document.querySelectorAll('.filter-option input[type="checkbox"]:checked');
    sizeCheckboxes.forEach(checkbox => {
        const size = checkbox.id.replace('size-', '').toUpperCase();
        params.append('size[]', size);
    });
    
    return params;
}

// Áp dụng bộ lọc và sắp xếp sử dụng AJAX
function applyFilters() {
    // Hiển thị loading indicator
    const loadingIndicator = document.getElementById('loading-indicator');
    const productGridContainer = document.getElementById('product-grid-container');
    
    if (loadingIndicator && productGridContainer) {
        loadingIndicator.style.display = 'block';
        productGridContainer.style.opacity = '0.5';
    }
    
    // Lấy tham số lọc
    const params = getFilterParams();
    
    // Cập nhật URL hiện tại mà không tải lại trang
    const newUrl = `${window.location.pathname}?${params.toString()}`;
    window.history.pushState({ path: newUrl }, '', newUrl);
    
    // Gọi API để lấy dữ liệu đã lọc
    fetch(`index.php?controller=category&action=filterProducts&${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Ẩn loading indicator
        if (loadingIndicator && productGridContainer) {
            loadingIndicator.style.display = 'none';
            productGridContainer.style.opacity = '1';
        }
        
        if (data.success) {
            // Cập nhật nội dung danh sách sản phẩm
            productGridContainer.innerHTML = data.html;
            
            // Cập nhật số lượng sản phẩm hiển thị
            const productCountDisplay = document.getElementById('product-count-display');
            if (productCountDisplay) {
                productCountDisplay.innerHTML = `<span>Hiển thị ${data.count} sản phẩm</span>`;
            }
            
            // Khởi tạo lại các sự kiện cho sản phẩm mới
            initProductActions();
            
            // Hiển thị thông báo nếu cần
            if (data.count === 0) {
                showNotification('Không tìm thấy sản phẩm phù hợp với bộ lọc.', 'info');
            }
        } else {
            // Hiển thị thông báo lỗi
            showNotification(data.error || 'Có lỗi xảy ra khi lọc sản phẩm.', 'error');
        }
    })
    .catch(error => {
        // Ẩn loading indicator
        if (loadingIndicator && productGridContainer) {
            loadingIndicator.style.display = 'none';
            productGridContainer.style.opacity = '1';
        }
        
        console.error('Error:', error);
        showNotification('Đã xảy ra lỗi khi kết nối với máy chủ!', 'error');
    });
}

// Khởi tạo các sự kiện cho sản phẩm
function initProductActions() {
    // Xử lý thêm vào giỏ hàng
    initAddToCart();
    
    // Xử lý xem nhanh sản phẩm
    initQuickView();
    
    // Xử lý đặt lại bộ lọc
    initResetFilters();
}

// Xử lý nút đặt lại bộ lọc
function initResetFilters() {
    const resetButtons = document.querySelectorAll('.reset-filters');
    
    resetButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Đặt lại giá trị thanh trượt giá
            const priceRange = document.getElementById('price-range');
            const priceValue = document.getElementById('price-value');
            
            if (priceRange) {
                priceRange.value = 1000000; // Giá trị mặc định
                
                if (priceValue) {
                    priceValue.textContent = '1,000,000₫';
                }
            }
            
            // Bỏ chọn tất cả checkbox kích cỡ
            const sizeCheckboxes = document.querySelectorAll('.filter-option input[type="checkbox"]');
            sizeCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            
            // Đặt lại về tùy chọn sắp xếp mặc định
            const sortSelect = document.getElementById('sort-by');
            if (sortSelect) {
                sortSelect.value = 'newest';
            }
            
            // Áp dụng bộ lọc đã đặt lại
            applyFilters();
        });
    });
}

// Khởi tạo chức năng thêm vào giỏ hàng
function initAddToCart() {
    const addToCartButtons = document.querySelectorAll('.btn-add-cart');
    
    addToCartButtons.forEach(button => {
        // Xóa sự kiện cũ để tránh trùng lặp
        button.removeEventListener('click', handleAddToCart);
        
        // Thêm sự kiện mới
        button.addEventListener('click', handleAddToCart);
    });
}

// Xử lý sự kiện thêm vào giỏ hàng
function handleAddToCart(e) {
    e.preventDefault();
    const productId = this.getAttribute('data-product-id');
    
    // Thêm hiệu ứng đang tải
    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';
    this.disabled = true;
    
    // Gọi API thêm vào giỏ hàng
    fetch('index.php?controller=cart&action=add&id=' + productId + '&qty=1&size=M')
        .then(response => {
            // Khôi phục trạng thái nút
            this.innerHTML = 'Thêm vào giỏ hàng';
            this.disabled = false;
            
            // Kiểm tra phản hồi
            if (response.ok) {
                // Hiển thị thông báo thành công
                showNotification('Đã thêm sản phẩm vào giỏ hàng!', 'success');
                
                // Kích hoạt sự kiện cập nhật giỏ hàng để Header.js có thể cập nhật số lượng
                document.dispatchEvent(new CustomEvent('cartUpdated'));
            } else {
                showNotification('Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
            }
            
            // Lấy số lượng sản phẩm trong giỏ hàng
            return fetch('index.php?controller=cart&action=getCount', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
        })
        .then(response => response.json())
        .then(data => {
            // Cập nhật số lượng sản phẩm trong giỏ hàng
            const cartCount = document.getElementById('item-count');
            if (cartCount && data.count) {
                cartCount.textContent = data.count;
                
                // Lưu vào sessionStorage để sử dụng trên các trang khác
                sessionStorage.setItem('cartCount', data.count);
            }
        })
        .catch(error => {
            // Khôi phục trạng thái nút
            this.innerHTML = 'Thêm vào giỏ hàng';
            this.disabled = false;
            
            console.error('Error:', error);
            showNotification('Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
        });
}

// Khởi tạo chức năng xem nhanh sản phẩm
function initQuickView() {
    const quickViewButtons = document.querySelectorAll('.btn-quickview');
    
    quickViewButtons.forEach(button => {
        // Xóa sự kiện cũ để tránh trùng lặp
        button.removeEventListener('click', handleQuickView);
        
        // Thêm sự kiện mới
        button.addEventListener('click', handleQuickView);
    });
}

// Xử lý sự kiện xem nhanh sản phẩm
function handleQuickView(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const productId = this.getAttribute('data-product-id');
    
    // Hiển thị thông báo đang tải
    showNotification('Đang tải thông tin sản phẩm...', 'info');
    
    // Chuyển hướng đến trang chi tiết sản phẩm
    window.location.href = 'index.php?controller=product&action=show&id=' + productId;
}

// Lấy ID danh mục hiện tại từ URL
function getCurrentCategoryId() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('id');
}

// Hiển thị thông báo
function showNotification(message, type = 'success') {
    // Tạo phần tử thông báo
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-icon">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'info' ? 'info-circle' : 'exclamation-circle'}"></i>
        </div>
        <div class="notification-content">${message}</div>
    `;
    
    // Tìm hoặc tạo container thông báo
    let container = document.querySelector('.notification-container');
    
    if (!container) {
        container = document.createElement('div');
        container.className = 'notification-container';
        document.body.appendChild(container);
    }
    
    // Thêm thông báo vào container
    container.appendChild(notification);
    
    // Thêm class hiển thị sau khoảng thời gian ngắn để có hiệu ứng
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    // Tự động ẩn thông báo sau 3 giây
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}