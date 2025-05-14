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
                <li class="breadcrumb-item active" aria-current="page"><?= $category['name'] ?? 'Tất cả sản phẩm' ?></li>
            </ol>
        </nav>
        
        <!-- Hero Banner -->
        <div class="hero-banner mb-4">
            <img src="/upload/img/banner/category-banner.webp" alt="<?= $category['name'] ?? 'Danh mục sản phẩm' ?>" class="img-fluid">
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
                        <div class="category-title">Loại sản phẩm</div>
                        <?php 
                        // Get all categories to use as filter
                        $allCategories = $this->categoryModel->getAll(['id_Category', 'name'], 100, ['column' => 'name', 'order' => 'asc']);
                        foreach($allCategories as $cat): 
                        ?>
                        <div class="filter-option">
                            <input type="checkbox" id="cat-<?= $cat['id_Category'] ?>" class="filter-checkbox" 
                                   <?= (isset($category['id_Category']) && $category['id_Category'] == $cat['id_Category']) ? 'checked' : '' ?>>
                            <label for="cat-<?= $cat['id_Category'] ?>"><?= $cat['name'] ?></label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="filter-category">
                        <div class="category-title">Khoảng giá</div>
                        <div class="price-slider">
                            <input type="range" class="form-range" min="100000" max="2000000" step="50000" value="1000000" id="price-range">
                            <div class="price-range mt-2">
                                <span>100,000₫</span>
                                <span id="price-value">1,000,000₫</span>
                                <span>2,000,000₫</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="filter-category">
                        <div class="category-title">Màu sắc</div>
                        <div class="color-options">
                            <div class="color-option" style="background-color: black;"></div>
                            <div class="color-option" style="background-color: white;"></div>
                            <div class="color-option" style="background-color: blue;"></div>
                            <div class="color-option" style="background-color: red;"></div>
                            <div class="color-option" style="background-color: green;"></div>
                            <div class="color-option" style="background-color: yellow;"></div>
                        </div>
                    </div>
                    
                    <div class="filter-category">
                        <div class="category-title">Kích cỡ</div>
                        <div class="filter-option">
                            <input type="checkbox" id="size-m" class="filter-checkbox">
                            <label for="size-m">M</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="size-l" class="filter-checkbox">
                            <label for="size-l">L</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="size-xl" class="filter-checkbox">
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
                            <option value="newest">Mới nhất</option>
                            <option value="price-asc">Giá: Thấp đến cao</option>
                            <option value="price-desc">Giá: Cao đến thấp</option>
                            <option value="bestseller">Bán chạy nhất</option>
                        </select>
                    </div>
                    <div>
                        <span>Hiển thị <?= count($products) ?> sản phẩm</span>
                    </div>
                </div>
                
                <!-- Product Grid -->
                <div class="row product-grid">
                    <?php if(empty($products)): ?>
                        <div class="col-12 text-center py-5">
                            <p>Không có sản phẩm nào trong danh mục này.</p>
                            <a href="index.php" class="btn btn-primary">Quay lại trang chủ</a>
                        </div>
                    <?php else: ?>
                        <?php foreach($products as $product): ?>
                            <div class="col-md-4 col-6 mb-4">
                                <div class="product-card">
                                    <div class="product-img-wrap">
                                        <?php if($product['discount_percent'] > 0): ?>
                                            <span class="product-badge">-<?= $product['discount_percent'] ?>%</span>
                                        <?php else: ?>
                                            <span class="product-badge">Mới</span>
                                        <?php endif; ?>
                                        <a href="index.php?controller=product&action=show&id=<?= $product['id_product'] ?>">
                                            <img src="<?= $product['main_image'] ?>" class="card-img-top" alt="<?= $product['name'] ?>">
                                        </a>
                                        <button class="btn-quickview" data-product-id="<?= $product['id_product'] ?>">Xem nhanh</button>
                                    </div>
                                    <div class="product-info">
                                        <h5 class="product-title">
                                            <a href="index.php?controller=product&action=show&id=<?= $product['id_product'] ?>"><?= $product['name'] ?></a>
                                        </h5>
                                        <div class="product-price">
                                            <?php if($product['original_price'] > $product['current_price']): ?>
                                                <span class="original-price"><?= number_format($product['original_price'], 0, ',', '.') ?>₫</span>
                                            <?php endif; ?>
                                            <span class="current-price"><?= number_format($product['current_price'], 0, ',', '.') ?>₫</span>
                                        </div>
                                        <button class="btn-add-cart" data-product-id="<?= $product['id_product'] ?>">Thêm vào giỏ hàng</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Pagination -->
                <?php if(count($products) > 0): ?>
                <nav aria-label="Product pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
                
                <!-- View More Button -->
                <div class="text-center mt-4 mb-5">
                    <button type="button" class="btn btn-outline-primary btn-view-more">Xem thêm sản phẩm</button>
                </div>
                <?php endif; ?>
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
                                    <button type="button" class="btn btn-outline-secondary size-btn" data-size="M">M</button>
                                    <button type="button" class="btn btn-outline-secondary size-btn" data-size="L">L</button>
                                    <button type="button" class="btn btn-outline-secondary size-btn" data-size="XL">XL</button>
                                </div>
                            </div>
                            
                            <div class="quantity-selector mb-3">
                                <label class="me-2">Số lượng:</label>
                                <div class="input-group" style="width: 130px;">
                                    <button class="btn btn-outline-secondary" id="decreaseQuantity" type="button">-</button>
                                    <input type="text" class="form-control text-center" id="quantityInput" value="1">
                                    <button class="btn btn-outline-secondary" id="increaseQuantity" type="button">+</button>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" id="quickViewAddToCart">Thêm vào giỏ hàng</button>
                                <a href="#" class="btn btn-outline-primary" id="quickViewViewDetail">Xem chi tiết sản phẩm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Price range slider
        const priceRange = document.getElementById('price-range');
        const priceValue = document.getElementById('price-value');
        
        if(priceRange && priceValue) {
            priceRange.addEventListener('input', function() {
                priceValue.textContent = Number(this.value).toLocaleString('vi-VN') + '₫';
            });
        }
        
        // Color options
        const colorOptions = document.querySelectorAll('.color-option');
        colorOptions.forEach(option => {
            option.addEventListener('click', function() {
                colorOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // Add to cart functionality
        const addToCartButtons = document.querySelectorAll('.btn-add-cart');
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function(e) {
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
            button.addEventListener('click', function(e) {
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
                        
                        if(data.original_price > data.current_price) {
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
        document.getElementById('quickViewAddToCart').addEventListener('click', function() {
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
            button.addEventListener('click', function() {
                sizeButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // Quantity controls in quick view
        document.getElementById('decreaseQuantity').addEventListener('click', function() {
            const input = document.getElementById('quantityInput');
            let value = parseInt(input.value);
            if(value > 1) {
                input.value = value - 1;
            }
        });
        
        document.getElementById('increaseQuantity').addEventListener('click', function() {
            const input = document.getElementById('quantityInput');
            let value = parseInt(input.value);
            input.value = value + 1;
        });
        
        // Filter functionality
        const filterCheckboxes = document.querySelectorAll('.filter-checkbox');
        filterCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                applyFilters();
            });
        });
        
        // Sort functionality
        document.getElementById('sort-by').addEventListener('change', function() {
            applyFilters();
        });
        
        function applyFilters() {
            // Build query string based on filters
            let query = 'index.php?controller=category&action=show';
            
            // Add category ID if available
            if(<?= isset($category['id_Category']) ? 'true' : 'false' ?>) {
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
                if(cb.id.startsWith('cat-')) {
                    const catId = cb.id.replace('cat-', '');
                    query += '&cat[]=' + catId;
                }
            });
            
            // Add selected sizes
            const sizeCheckboxes = document.querySelectorAll('.filter-option input[type="checkbox"]:checked');
            sizeCheckboxes.forEach(cb => {
                if(cb.id.startsWith('size-')) {
                    const size = cb.id.replace('size-', '');
                    query += '&size[]=' + size;
                }
            });
            
            // Redirect to filtered page
            window.location.href = query;
        }
        
        // View more button
        const viewMoreButton = document.querySelector('.btn-view-more');
        if(viewMoreButton) {
            viewMoreButton.addEventListener('click', function() {
                // You would need to implement pagination logic here
                alert('Tính năng đang được phát triển');
            });
        }
    });
    </script>
</body>

</html>