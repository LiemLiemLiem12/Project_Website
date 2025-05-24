<?php
// Thêm vào đầu file để lấy thông tin cài đặt
require_once 'Controllers/FooterController.php';
$footerController = new FooterController();
$storeSettings = $footerController->getStoreSettings();
$siteName = $storeSettings['site_name'] ?? 'RSStore';
$faviconPath = !empty($storeSettings['favicon_path']) ? $storeSettings['favicon_path'] : '/Project_Website/ProjectWeb/upload/img/Header/favicon.ico';
$isTagSearch = isset($isTagSearch) && $isTagSearch;
$searchTag = $searchTag ?? '';
$categoryName = $isTagSearch ? 'Tag: ' . ucfirst($searchTag) : ($category['name'] ?? 'Tất cả sản phẩm');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $categoryName ?> | Fashion Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Favicon -->
    <link rel="icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/AllProduct.css">
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Footer.css">
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Header.css">
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/category-filter.css">
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Footer.css">
    <style>
        .pagination .page-link {
            color: #000000; /* Chữ đen */
            background-color: #ffffff; /* Nền trắng */
            border: 1px solid #dee2e6;
        }
        .pagination .page-link:hover {
            background-color: #e9ecef; /* Nền xám nhạt khi hover */
            color: #000000;
        }
        .pagination .page-item.active .page-link.current-page {
            color: #ffffff; /* Chữ trắng */
            background-color: #000000; /* Nền đen */
            border-color: #000000;
        }
        .pagination .page-item.disabled .page-link {
            color: #6c757d; /* Màu xám cho nút bị vô hiệu hóa */
            background-color: #ffffff;
            border-color: #dee2e6;
            pointer-events: none;
        }
    </style>
</head>
<body>
      <?php
       // Chỉ hiển thị header nếu không được gọi từ file khác đã có header
       if (!isset($skipHeader) || $skipHeader !== true) {
           view('frontend.partitions.frontend.header');
       ?>

       <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Header.css">
       <?php
       }
       ?>
    <!-- Main Container -->
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <?php if ($isTagSearch): ?>
                    <li class="breadcrumb-item"><a href="index.php?controller=search">Tìm kiếm</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tag: <?= htmlspecialchars($searchTag) ?></li>
                <?php else: ?>
                    <li class="breadcrumb-item"><a href="index.php?controller=category&action=index">Danh mục</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $categoryName ?></li>
                <?php endif; ?>
            </ol>
        </nav>
        <div class="hero-banner mb-4">
            <?php if ($isTagSearch): ?>
                <div class="tag-search-banner">
                    <div class="banner-content text-center py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px;">
                        <i class="fas fa-tags fa-3x mb-3"></i>
                        <h1>Tìm kiếm theo tag: "<?= htmlspecialchars($searchTag) ?>"</h1>
                        <p class="lead">Khám phá các sản phẩm được gắn tag <?= htmlspecialchars($searchTag) ?></p>
                    </div>
                </div>
            <?php else: ?>
                <img src="/Project_Website/ProjectWeb/upload/img/home/Banner3.webp" alt="<?= $categoryName ?>" class="img-fluid">
                <div class="banner-content">
                    <h1><?= $categoryName ?></h1>
                </div>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="side-filter">
                    <h4>Bộ lọc sản phẩm</h4>
                    <?php if ($isTagSearch): ?>
                        <div class="filter-category mb-3">
                            <div class="category-title">Đang tìm kiếm</div>
                            <div class="current-tag-search p-3" style="background: #f8f9fa; border-radius: 10px;">
                                <span class="badge bg-primary me-2">
                                    <i class="fas fa-tag"></i> <?= htmlspecialchars($searchTag) ?>
                                </span>
                                <small class="text-muted d-block mt-2">
                                    Hiển thị tất cả sản phẩm có tag này
                                </small>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="filter-category">
                        <div class="category-title">Khoảng giá</div>
                        <div class="price-slider">
                            <input type="range" class="form-range" min="100000" max="2000000" step="50000" value="<?= $filters['price_max'] ?? 1000000 ?>" id="price-range">
                            <div class="price-range mt-2">
                                <span>100,000₫</span>
                                <span id="price-value"><?= number_format($filters['price_max'] ?? 1000000, 0, ',', '.') ?>₫</span>
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
                    <?php if ($isTagSearch): ?>
                        <div class="filter-category">
                            <div class="category-title">Tag liên quan</div>
                            <div class="related-tags">
                                <?php
                                $relatedTags = ['áo polo', 'nam giới', 'thể thao', 'casual', 'công sở'];
                                foreach ($relatedTags as $relatedTag):
                                    if ($relatedTag !== $searchTag):
                                ?>
                                    <a href="index.php?controller=search&action=showByTag&tag=<?= urlencode($relatedTag) ?>" class="badge bg-light text-dark me-1 mb-1 text-decoration-none">
                                        #<?= htmlspecialchars($relatedTag) ?>
                                    </a>
                                <?php endif; endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="filter-section d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="filter-label">Sắp xếp theo:</span>
                        <select class="filter-dropdown" id="sort-by">
                            <option value="newest" <?= ($filters['sort'] ?? 'newest') == 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                            <option value="price-asc" <?= ($filters['sort'] ?? '') == 'price-asc' ? 'selected' : '' ?>>Giá: Thấp đến cao</option>
                            <option value="price-desc" <?= ($filters['sort'] ?? '') == 'price-desc' ? 'selected' : '' ?>>Giá: Cao đến thấp</option>
                            <option value="bestseller" <?= ($filters['sort'] ?? '') == 'bestseller' ? 'selected' : '' ?>>Bán chạy nhất</option>
                        </select>
                    </div>
                    <div id="product-count-display">
                        <span>Hiển thị <?= count($products) ?> sản phẩm (Tổng: <?= $totalProducts ?>)</span>
                        <?php if ($isTagSearch): ?>
                            <small class="text-muted d-block">cho tag "<?= htmlspecialchars($searchTag) ?>"</small>
                        <?php endif; ?>
                    </div>
                    <div id="loading-indicator" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Đang tải...</span>
                        </div>
                        <p class="mt-2">Đang tải sản phẩm...</p>
                    </div>
                </div>
                <div class="notification-container"></div>
                <div id="products-container">
                    <?php
                    $currentPage = $currentPage ?? 1;
                    $totalPages = $totalPages ?? 1;
                    $totalProducts = $totalProducts ?? count($products);
                    require('Views/frontend/categories/product_grid.php');
                    ?>
                </div>
            </div>
        </div>
    </div>
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
    <script>
        const isTagSearch = <?= $isTagSearch ? 'true' : 'false' ?>;
        const searchTag = '<?= $searchTag ?>';
        const categoryId = '<?= $category['id_Category'] ?? 'null' ?>';
    </script>
    <script src="/Project_Website/ProjectWeb/layout/js/category-filter-ajax.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Project_Website/ProjectWeb/layout/js/Header.js"></script>
    <script src="/Project_Website/ProjectWeb/layout/js/Footer.js"></script>
    
    <?php
    view('frontend.partitions.frontend.footer');
    ?>
</body>
</html>