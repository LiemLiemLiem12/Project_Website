<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products | RentEase</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/WEB_BAN_THOI_TRANG/layout/css/AllProduct.css">
</head>

<body>
    <!-- Main Container -->
    <div class="container mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="Home.html">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tất cả sản phẩm</li>
            </ol>
        </nav>
        
        <!-- Hero Banner -->
        <div class="hero-banner">
            <img src="/WEB_BAN_THOI_TRANG/upload/img/All-Product/Banner1.webp" alt="Collection Banner" class="img-fluid">
        </div>

        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3">
                <div class="side-filter">
                    <h4>Bộ lọc sản phẩm</h4>
                    
                    <div class="filter-category">
                        <div class="category-title">Loại sản phẩm</div>
                        <div class="filter-option">
                            <input type="checkbox" id="cat-shirt" class="filter-checkbox">
                            <label for="cat-shirt">Áo thun</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="cat-jeans" class="filter-checkbox">
                            <label for="cat-jeans">Quần jeans</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="cat-jacket" class="filter-checkbox">
                            <label for="cat-jacket">Áo khoác</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="cat-dress" class="filter-checkbox">
                            <label for="cat-dress">Đầm</label>
                        </div>
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
                            <input type="checkbox" id="size-s" class="filter-checkbox">
                            <label for="size-s">S</label>
                        </div>
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
                <div class="filter-section d-flex justify-content-between align-items-center">
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
                        <span>Hiển thị 1-12 của 36 sản phẩm</span>
                    </div>
                </div>
                
                <!-- Product Grid -->
                <div class="row product-grid">
                    <!-- Product 1 -->
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4 col-6">
                            <a href="index.php?controller=product&action=show&id=<?= $product['id'] ?>"  class="product-card">
                                <div class="product-img-wrap">
                                    <span class="product-badge">Mới</span>
                                    <img src="<?= $product['image']; ?>" class="card-img-top" alt="<?= $product['name']; ?>">
                                    <button class="btn-quickview">Xem nhanh</button>
                                </div>
                                <div class="product-info">
                                    <h5 class="product-title"><?= $product['name']; ?></h5>
                                    <div class="product-price"><?= number_format($product['price'], 0); ?>₫</div>
                                    <button class="btn-add-cart">Thêm vào giỏ hàng</button>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>

                    
                 
                
                <!-- Pagination -->
                <nav aria-label="Product pagination">
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
                <button type="button" class="btn-view-more">Xem thêm sản phẩm</button>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="/WEB_BAN_THOI_TRANG/layout/js/AllProduct.js"></script>
</body>

</html>