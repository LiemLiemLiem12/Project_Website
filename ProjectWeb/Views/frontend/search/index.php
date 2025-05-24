<?php
// views/frontend/search/index.php
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm: "<?= htmlspecialchars($searchQuery) ?>" | Fashion Store</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/AllProduct.css">
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Footer.css">
</head>

<body>
    <?php
    view('frontend.partitions.frontend.header');
    ?>
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Header.css">
    
    <!-- Main Container -->
    <div class="container mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kết quả tìm kiếm</li>
            </ol>
        </nav>

        <!-- Hero Banner - Search Results -->
        <div class="hero-banner mb-4">
            <div class="search-results-banner">
                <div class="banner-content text-center py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px;">
                    <i class="fas fa-search fa-3x mb-3"></i>
                    <h1>Kết quả tìm kiếm: "<?= htmlspecialchars($searchQuery) ?>"</h1>
                    <p class="lead">Tìm thấy <?= $totalProducts ?> sản phẩm phù hợp</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3">
                <div class="side-filter">
                    <h4>Bộ lọc sản phẩm</h4>
                    
                    <!-- Search Query Info -->
                    <div class="filter-category mb-3">
                        <div class="category-title">Đang tìm kiếm</div>
                        <div class="current-search p-3" style="background: #f8f9fa; border-radius: 10px;">
                            <span class="badge bg-primary me-2">
                                <i class="fas fa-search"></i> <?= htmlspecialchars($searchQuery) ?>
                            </span>
                            <small class="text-muted d-block mt-2">
                                <?= $totalProducts ?> sản phẩm được tìm thấy
                            </small>
                        </div>
                    </div>
                    
                    <!-- Price Filter -->
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
                    
                    <!-- Size Filter -->
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

                    <!-- Related Keywords -->
                    <!-- <div class="filter-category">
                        <div class="category-title">Từ khóa liên quan</div>
                        <div class="related-keywords">
                            <?php
                            // Tạo các từ khóa liên quan dựa trên từ khóa tìm kiếm
                            $relatedKeywords = [];
                            $searchWords = explode(' ', strtolower($searchQuery));
                            
                            // Các từ khóa gợi ý chung
                            $suggestions = ['áo', 'quần', 'đầm', 'giày', 'túi', 'phụ kiện', 'nam', 'nữ', 'trẻ em'];
                            
                            foreach ($suggestions as $suggestion):
                                if (!in_array($suggestion, $searchWords)):
                                    $combinedSearch = $searchQuery . ' ' . $suggestion;
                            ?>
                                <a href="index.php?controller=search&q=<?= urlencode($combinedSearch) ?>" 
                                   class="badge bg-light text-dark me-1 mb-1 text-decoration-none">
                                    <?= htmlspecialchars($combinedSearch) ?>
                                </a>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    </div> -->

                    <!-- Clear Filters -->
                    <div class="filter-category">
                        <button type="button" class="btn btn-outline-secondary w-100 reset-filters">
                            <i class="fas fa-undo"></i> Đặt lại bộ lọc
                        </button>
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
                            <option value="relevance" <?= ($filters['sort'] ?? 'relevance') == 'relevance' ? 'selected' : '' ?>>Liên quan nhất</option>
                            <option value="newest" <?= ($filters['sort'] ?? '') == 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                            <option value="price-asc" <?= ($filters['sort'] ?? '') == 'price-asc' ? 'selected' : '' ?>>Giá: Thấp đến cao</option>
                            <option value="price-desc" <?= ($filters['sort'] ?? '') == 'price-desc' ? 'selected' : '' ?>>Giá: Cao đến thấp</option>
                            <option value="popular" <?= ($filters['sort'] ?? '') == 'popular' ? 'selected' : '' ?>>Phổ biến nhất</option>
                        </select>
                    </div>
                    
                    <div id="product-count-display">
                        <span>Hiển thị <?= count($products) ?> / <?= $totalProducts ?> sản phẩm</span>
                        <small class="text-muted d-block">cho từ khóa "<?= htmlspecialchars($searchQuery) ?>"</small>
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
                <?php if (!empty($products)): ?>
                    <div class="row product-grid">
                        <?php foreach($products as $product): ?>
                            <div class="col-md-4 col-6 mb-4">
                                <div class="product-card">
                                    <div class="product-img-wrap">
                                        <?php if(!empty($product['discount_percent']) && $product['discount_percent'] > 0): ?>
                                            <span class="product-badge">-<?= $product['discount_percent'] ?>%</span>
                                        <?php else: ?>
                                            <span class="product-badge">Mới</span>
                                        <?php endif; ?>
                                        <a href="index.php?controller=product&action=show&id=<?= $product['id_product'] ?>">
                                            <img src="/Project_Website/ProjectWeb/upload/img/All-Product/<?= $product['main_image'] ?>" 
                                                 class="card-img-top" 
                                                 alt="<?= htmlspecialchars($product['name']) ?>"
                                                 onerror="this.src='/Project_Website/ProjectWeb/upload/img/All-Product/default.jpg'">
                                        </a>
                                        <button class="btn-quickview" data-product-id="<?= $product['id_product'] ?>">Xem nhanh</button>
                                    </div>
                                    <div class="product-info">
                                        <h5 class="product-title">
                                            <a class="text-dark text-decoration-none" 
                                               href="index.php?controller=product&action=show&id=<?= $product['id_product'] ?>">
                                                <?= htmlspecialchars($product['name']) ?>
                                            </a>
                                        </h5>
                                        
                                        <?php if (!empty($product['tag'])): ?>
                                            <div class="product-tags mb-2">
                                                <?php 
                                                $tags = explode(',', $product['tag']);
                                                foreach (array_slice($tags, 0, 2) as $tag): 
                                                    $tag = trim($tag);
                                                    if (!empty($tag)):
                                                ?>
                                                    <a href="index.php?controller=search&action=showByTag&tag=<?= urlencode($tag) ?>" 
                                                       class="badge bg-light text-primary text-decoration-none me-1">
                                                        #<?= htmlspecialchars($tag) ?>
                                                    </a>
                                                <?php 
                                                    endif;
                                                endforeach; 
                                                ?>
                                            </div>
                                        <?php endif; ?>

                                        <div class="product-price">
                                            <span class="current-price text-danger fs-4"><?= number_format($product['current_price'], 0, ',', '.') ?>₫</span>
                                            <?php if(!empty($product['original_price']) && $product['original_price'] > $product['current_price']): ?>
                                                <span class="original-price text-decoration-line-through"><?= number_format($product['original_price'], 0, ',', '.') ?>₫</span>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (!empty($product['category_name'])): ?>
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-tag"></i> <?= htmlspecialchars($product['category_name']) ?>
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <nav aria-label="Search results pagination" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php if ($currentPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?controller=search&q=<?= urlencode($searchQuery) ?>&page=<?= $currentPage - 1 ?>">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php
                                $startPage = max(1, $currentPage - 2);
                                $endPage = min($totalPages, $currentPage + 2);
                                
                                if ($startPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?controller=search&q=<?= urlencode($searchQuery) ?>&page=1">1</a>
                                    </li>
                                    <?php if ($startPage > 2): ?>
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                        <a class="page-link" href="?controller=search&q=<?= urlencode($searchQuery) ?>&page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($endPage < $totalPages): ?>
                                    <?php if ($endPage < $totalPages - 1): ?>
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    <?php endif; ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?controller=search&q=<?= urlencode($searchQuery) ?>&page=<?= $totalPages ?>"><?= $totalPages ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($currentPage < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?controller=search&q=<?= urlencode($searchQuery) ?>&page=<?= $currentPage + 1 ?>">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>

                <?php else: ?>
                    <!-- No Results -->
                    <div class="col-12 text-center py-5">
                        <img src="/Project_Website/ProjectWeb/upload/img/no-results.png" alt="Không tìm thấy kết quả" class="img-fluid mb-4" style="max-width: 200px; opacity: 0.7;">
                        <h3>Không tìm thấy sản phẩm nào</h3>
                        <p class="text-muted">Không có sản phẩm nào phù hợp với từ khóa "<strong><?= htmlspecialchars($searchQuery) ?></strong>"</p>
                        
                        <div class="mt-4">
                            <h5>Gợi ý:</h5>
                            <ul class="list-unstyled">
                                <li>• Kiểm tra lại chính tả từ khóa</li>
                                <li>• Thử sử dụng từ khóa khác</li>
                                <li>• Sử dụng từ khóa ngắn gọn hơn</li>
                                <li>• Tìm kiếm theo danh mục sản phẩm</li>
                            </ul>
                        </div>

                        <div class="mt-4">
                            <a href="index.php" class="btn btn-primary me-2">
                                <i class="fas fa-home"></i> Về trang chủ
                            </a>
                            <a href="index.php?controller=product" class="btn btn-outline-primary">
                                <i class="fas fa-th-large"></i> Xem tất cả sản phẩm
                            </a>
                            <button type="button" class="btn btn-dark reset-filters">
                                <i class="fas fa-undo"></i> Đặt lại bộ lọc
                            </button>
                        </div>
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
                                <a href="#" class="btn btn-outline-primary" id="quickViewViewDetail">Xem chi tiết sản phẩm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript để xử lý search với filters -->
    <script>
        // Biến để xác định loại trang hiện tại
        const isSearch = true;
        const searchQuery = '<?= htmlspecialchars($searchQuery) ?>';
        const currentPage = <?= $currentPage ?>;
        const totalPages = <?= $totalPages ?>;
    </script>
    
    <script src="/Project_Website/ProjectWeb/layout/js/search-filter-ajax.js"></script>
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/category-filter.css">
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Project_Website/ProjectWeb/layout/js/Header.js"></script>
    <script src="/Project_Website/ProjectWeb/layout/js/Footer.js"></script>
</body>

</html>