<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $category['name'] ?? 'Danh mục sản phẩm' ?> | Fashion Store</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/AllProduct.css">
</head>

<body>
    <!-- Main Container -->
    <div class="container mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="index.php?controller=category&action=index">Danh mục</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $category['name'] ?? 'Tất cả sản phẩm' ?>
                </li>
            </ol>
        </nav>

        <!-- Hero Banner -->
        <div class="hero-banner mb-4">
            <img src="/Project_Website/ProjectWeb/upload/img/home/Banner3.webp"
                alt="<?= $category['name'] ?? 'Danh mục sản phẩm' ?>" class="img-fluid">
            <div class="banner-content">
                <h1><?= $category['name'] ?? 'Danh mục sản phẩm' ?></h1>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3">
                <div class="side-filter">
                    <h4>Bộ lọc sản phẩm</h4>
                    <div class="filter-category">
                        <div class="category-title">Khoảng giá</div>
                        <div class="price-slider">
                            <input type="range" class="form-range" min="100000" max="2000000" step="50000"
                                value="<?= $filters['price_max'] ?? 1000000 ?>" id="price-range">
                            <div class="price-range mt-2">
                                <span>100,000₫</span>
                                <span
                                    id="price-value"><?= number_format($filters['price_max'] ?? 1000000, 0, ',', '.') ?>₫</span>
                                <span>2,000,000₫</span>
                            </div>
                        </div>
                    </div>
                    <div class="filter-category">
                        <div class="category-title">Kích cỡ</div>
                        <div class="filter-option">
                            <input type="checkbox" id="size-m" class="filter-checkbox" <?= in_array('M', $filters['sizes'] ?? []) ? 'checked' : '' ?>>
                            <label for="size-m">M</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="size-l" class="filter-checkbox" <?= in_array('L', $filters['sizes'] ?? []) ? 'checked' : '' ?>>
                            <label for="size-l">L</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="size-xl" class="filter-checkbox" <?= in_array('XL', $filters['sizes'] ?? []) ? 'checked' : '' ?>>
                            <label for="size-xl">XL</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Listing -->
            <div class="col-lg-9">
                <!-- Filter Bar -->
                <div class="filter-section d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="filter-label">Sắp xếp theo:</span>
                        <select class="filter-dropdown" id="sort-by">
                            <option value="newest" <?= ($filters['sort'] ?? 'newest') == 'newest' ? 'selected' : '' ?>>Mới
                                nhất</option>
                            <option value="price-asc" <?= ($filters['sort'] ?? '') == 'price-asc' ? 'selected' : '' ?>>Giá:
                                Thấp đến cao</option>
                            <option value="price-desc" <?= ($filters['sort'] ?? '') == 'price-desc' ? 'selected' : '' ?>>
                                Giá: Cao đến thấp</option>
                            <option value="bestseller" <?= ($filters['sort'] ?? '') == 'bestseller' ? 'selected' : '' ?>>
                                Bán chạy nhất</option>
                        </select>
                    </div>
                    <!-- <div>
                    <span>Hiển thị <?= count($products) ?> sản phẩm</span>
                </div> -->
                    <div id="product-count-display">
                        <span>Hiển thị <?= count($products) ?> sản phẩm</span>
                    </div>
                    <div id="loading-indicator" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Đang tải...</span>
                        </div>
                        <p class="mt-2">Đang tải sản phẩm...</p>
                    </div>
                </div>
                <div class="notification-container"></div>
                <!-- Product Grid -->
                <?php
                // Tách ra phần hiển thị danh sách sản phẩm
                require('Views/frontend/categories/product_grid.php');
                ?>
            </div>
        </div>
    </div>

    <!-- Quick View Modal -->
    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quickViewModalLabel">Xem nhanh sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="" id="quickViewImage" class="img-fluid" alt="Sản phẩm">
                        </div>
                        <div class="col-md-6">
                            <h3 id="quickViewName"></h3>
                            <div class="product-price mb-3">
                                <span class="original-price" id="quickViewOriginalPrice"></span>
                                <span class="current-price" id="quickViewCurrentPrice"></span>
                            </div>
                            <p id="quickViewDescription"></p>

                            <div class="product-sizes mb-3">
                                <label class="me-2">Kích cỡ:</label>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary size-btn"
                                        data-size="M">M</button>
                                    <button type="button" class="btn btn-outline-secondary size-btn"
                                        data-size="L">L</button>
                                    <button type="button" class="btn btn-outline-secondary size-btn"
                                        data-size="XL">XL</button>
                                </div>
                            </div>

                            <div class="quantity-selector mb-3">
                                <label class="me-2">Số lượng:</label>
                                <div class="input-group" style="width: 130px;">
                                    <button class="btn btn-outline-secondary" id="decreaseQuantity"
                                        type="button">-</button>
                                    <input type="text" class="form-control text-center" id="quantityInput" value="1">
                                    <button class="btn btn-outline-secondary" id="increaseQuantity"
                                        type="button">+</button>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" id="quickViewAddToCart">Thêm vào giỏ hàng</button>
                                <a href="#" class="btn btn-outline-primary" id="quickViewViewDetail">Xem chi tiết sản
                                    phẩm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/Project_Website/ProjectWeb/layout/js/category-filter-ajax.js"></script>
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/category-filter.css">
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- <script>
        // Phần JavaScript xử lý lọc và sắp xếp sản phẩm
        document.addEventListener('DOMContentLoaded', function () {
            // Khởi tạo các thành phần lọc
            initFilters();

            // Khởi tạo chức năng sắp xếp
            initSorting();

            // Khởi tạo các nút thêm vào giỏ hàng
            initAddToCart();

            // Khởi tạo xem nhanh sản phẩm
            initQuickView();
        });

        // Khởi tạo các bộ lọc
        function initFilters() {
            // Lọc theo giá
            const priceRange = document.getElementById('price-range');
            const priceValue = document.getElementById('price-value');

            if (priceRange && priceValue) {
                // Cập nhật hiển thị giá khi người dùng kéo thanh trượt
                priceRange.addEventListener('input', function () {
                    const formattedPrice = Number(this.value).toLocaleString('vi-VN') + '₫';
                    priceValue.textContent = formattedPrice;
                });

                // Chỉ áp dụng bộ lọc khi người dùng thả thanh trượt
                priceRange.addEventListener('change', function () {
                    applyFilters();
                });
            }

            // Lọc theo kích cỡ
            const sizeCheckboxes = document.querySelectorAll('.filter-option input[type="checkbox"]');
            sizeCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    applyFilters();
                });
            });

            // Thiết lập các giá trị bộ lọc từ URL hiện tại (cho trường hợp tải lại trang)
            setFiltersFromUrl();
        }

        // Thiết lập các bộ lọc từ tham số URL
        function setFiltersFromUrl() {
            const urlParams = new URLSearchParams(window.location.search);

            // Thiết lập giá trị thanh trượt giá
            const priceRange = document.getElementById('price-range');
            const priceValue = document.getElementById('price-value');
            const price = urlParams.get('price');

            if (price && priceRange && priceValue) {
                priceRange.value = price;
                priceValue.textContent = Number(price).toLocaleString('vi-VN') + '₫';
            }

            // Thiết lập các checkbox kích cỡ
            const sizes = urlParams.getAll('size[]');
            if (sizes.length > 0) {
                sizes.forEach(size => {
                    const checkbox = document.getElementById(`size-${size.toLowerCase()}`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            }

            // Thiết lập giá trị sắp xếp
            const sortSelect = document.getElementById('sort-by');
            const sort = urlParams.get('sort');

            if (sort && sortSelect) {
                sortSelect.value = sort;
            }
        }

        // Khởi tạo chức năng sắp xếp
        function initSorting() {
            const sortSelect = document.getElementById('sort-by');

            if (sortSelect) {
                sortSelect.addEventListener('change', function () {
                    applyFilters();
                });
            }
        }

        // Áp dụng bộ lọc và sắp xếp
        function applyFilters() {
            // Xây dựng URL với các tham số lọc và sắp xếp
            let url = 'index.php?controller=category&action=show';

            // Thêm ID danh mục nếu có
            const categoryId = getCurrentCategoryId();
            if (categoryId) {
                url += '&id=' + categoryId;
            }

            // Thêm tham số sắp xếp
            const sortSelect = document.getElementById('sort-by');
            if (sortSelect) {
                url += '&sort=' + sortSelect.value;
            }

            // Thêm khoảng giá
            const priceRange = document.getElementById('price-range');
            if (priceRange) {
                url += '&price=' + priceRange.value;
            }

            // Thêm kích cỡ đã chọn
            const sizeCheckboxes = document.querySelectorAll('.filter-option input[type="checkbox"]:checked');
            sizeCheckboxes.forEach(checkbox => {
                const size = checkbox.id.replace('size-', '').toUpperCase();
                url += '&size[]=' + size;
            });

            // Chuyển hướng đến URL mới với các bộ lọc
            window.location.href = url;
        }

        // Lấy ID danh mục hiện tại từ URL
        function getCurrentCategoryId() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('id');
        }

        // Khởi tạo chức năng thêm vào giỏ hàng
        function initAddToCart() {
            const addToCartButtons = document.querySelectorAll('.btn-add-cart');

            addToCartButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const productId = this.getAttribute('data-product-id');

                    // Gọi API thêm vào giỏ hàng
                    fetch('index.php?controller=cart&action=add&id=' + productId + '&qty=1&size=M')
                        .then(response => response.text())
                        .then(data => {
                            // Hiển thị thông báo thành công
                            showNotification('Đã thêm sản phẩm vào giỏ hàng!', 'success');

                            // Kích hoạt sự kiện cập nhật giỏ hàng để Header.js có thể cập nhật số lượng
                            document.dispatchEvent(new CustomEvent('cartUpdated'));

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
                            console.error('Error:', error);
                            showNotification('Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
                        });
                });
            });
        }

        // Khởi tạo chức năng xem nhanh sản phẩm
        function initQuickView() {
            const quickViewButtons = document.querySelectorAll('.btn-quickview');

            quickViewButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const productCard = this.closest('.product-card');
                    const productId = this.getAttribute('data-product-id');

                    // Hiển thị modal xem nhanh sản phẩm
                    // (Phần này cần có API hoặc dữ liệu sản phẩm chi tiết)
                    // Ở đây tôi sẽ giả định rằng có một bootstrap modal với id "quickViewModal"

                    // Hiển thị loading khi đợi dữ liệu
                    showNotification('Đang tải thông tin sản phẩm...', 'info');

                    // Gọi API lấy thông tin sản phẩm
                    fetch('index.php?controller=product&action=show&id=' + productId)
                        .then(response => {
                            // Chuyển hướng đến trang chi tiết sản phẩm
                            window.location.href = 'index.php?controller=product&action=show&id=' + productId;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification('Có lỗi xảy ra khi tải thông tin sản phẩm!', 'error');
                        });
                });
            });
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
        document.addEventListener('DOMContentLoaded', function () {
            // Price range slider
            const priceRange = document.getElementById('price-range');
            const priceValue = document.getElementById('price-value');

            if (priceRange && priceValue) {
                priceRange.addEventListener('input', function () {
                    priceValue.textContent = Number(this.value).toLocaleString('vi-VN') + '₫';
                });
            }

            // Color options
            const colorOptions = document.querySelectorAll('.color-option');
            colorOptions.forEach(option => {
                option.addEventListener('click', function () {
                    colorOptions.forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Add to cart functionality
            const addToCartButtons = document.querySelectorAll('.btn-add-cart');
            addToCartButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const productId = this.getAttribute('data-product-id');
                    // Add to cart AJAX call
                    fetch('index.php?controller=cart&action=add&id=' + productId + '&qty=1&size=M')
                        .then(response => response.text())
                        .then(data => {
                            alert('Đã thêm sản phẩm vào giỏ hàng!');
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            });

            // Quick view functionality
            const quickViewButtons = document.querySelectorAll('.btn-quickview');
            const quickViewModal = new bootstrap.Modal(document.getElementById('quickViewModal'));

            quickViewButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const productId = this.getAttribute('data-product-id');

                    // Get product details AJAX call
                    fetch('index.php?controller=product&action=getQuickView&id=' + productId)
                        .then(response => response.json())
                        .then(data => {
                            // Populate modal with product data
                            document.getElementById('quickViewImage').src = data.main_image;
                            document.getElementById('quickViewName').textContent = data.name;

                            if (data.original_price > data.current_price) {
                                document.getElementById('quickViewOriginalPrice').textContent = Number(data.original_price).toLocaleString('vi-VN') + '₫';
                                document.getElementById('quickViewOriginalPrice').style.display = 'inline-block';
                            } else {
                                document.getElementById('quickViewOriginalPrice').style.display = 'none';
                            }

                            document.getElementById('quickViewCurrentPrice').textContent = Number(data.current_price).toLocaleString('vi-VN') + '₫';
                            document.getElementById('quickViewDescription').textContent = data.description;
                            document.getElementById('quickViewViewDetail').href = 'index.php?controller=product&action=show&id=' + productId;

                            // Set up add to cart button
                            document.getElementById('quickViewAddToCart').setAttribute('data-product-id', productId);

                            // Show the modal
                            quickViewModal.show();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            });

            // Quick view add to cart button
            document.getElementById('quickViewAddToCart').addEventListener('click', function () {
                const productId = this.getAttribute('data-product-id');
                const quantity = document.getElementById('quantityInput').value;
                const size = document.querySelector('.size-btn.active')?.getAttribute('data-size') || 'M';

                // Add to cart AJAX call
                fetch('index.php?controller=cart&action=add&id=' + productId + '&qty=' + quantity + '&size=' + size)
                    .then(response => response.text())
                    .then(data => {
                        alert('Đã thêm sản phẩm vào giỏ hàng!');
                        quickViewModal.hide();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });

            // Size buttons in quick view
            const sizeButtons = document.querySelectorAll('.size-btn');
            sizeButtons.forEach(button => {
                button.addEventListener('click', function () {
                    sizeButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Quantity controls in quick view
            document.getElementById('decreaseQuantity').addEventListener('click', function () {
                const input = document.getElementById('quantityInput');
                let value = parseInt(input.value);
                if (value > 1) {
                    input.value = value - 1;
                }
            });

            document.getElementById('increaseQuantity').addEventListener('click', function () {
                const input = document.getElementById('quantityInput');
                let value = parseInt(input.value);
                input.value = value + 1;
            });

            // Filter functionality
            const filterCheckboxes = document.querySelectorAll('.filter-checkbox');
            filterCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    applyFilters();
                });
            });

            // Sort functionality
            document.getElementById('sort-by').addEventListener('change', function () {
                applyFilters();
            });

            function applyFilters() {
                // Build query string based on filters
                let query = 'index.php?controller=category&action=show';

                // Add category ID if available
                if (<?= isset($category['id_Category']) ? 'true' : 'false' ?>) {
                    query += '&id=<?= $category['id_Category'] ?? '' ?>';
                }

                // Add sort parameter
                const sortValue = document.getElementById('sort-by').value;
                query += '&sort=' + sortValue;

                // Add price range
                const priceValue = document.getElementById('price-range').value;
                query += '&price=' + priceValue;

                // Add selected categories
                const categoryCheckboxes = document.querySelectorAll('.filter-category input[type="checkbox"]:checked');
                categoryCheckboxes.forEach(cb => {
                    if (cb.id.startsWith('cat-')) {
                        const catId = cb.id.replace('cat-', '');
                        query += '&cat[]=' + catId;
                    }
                });

                // Add selected sizes
                const sizeCheckboxes = document.querySelectorAll('.filter-option input[type="checkbox"]:checked');
                sizeCheckboxes.forEach(cb => {
                    if (cb.id.startsWith('size-')) {
                        const size = cb.id.replace('size-', '');
                        query += '&size[]=' + size;
                    }
                });

                // Redirect to filtered page
                window.location.href = query;
            }

            // View more button
            const viewMoreButton = document.querySelector('.btn-view-more');
            if (viewMoreButton) {
                viewMoreButton.addEventListener('click', function () {
                    // You would need to implement pagination logic here
                    alert('Tính năng đang được phát triển');
                });
            }
        });
    </script> -->
</body>

</html>