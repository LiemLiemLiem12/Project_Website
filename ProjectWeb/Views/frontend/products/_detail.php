<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'] ?? null;
    $quantity = $_POST['quantity'] ?? 1;

    // Thêm vào giỏ (ví dụ đơn giản với $_SESSION)
    $_SESSION['cart'][$productId] = $quantity;

    echo json_encode(['success' => true]);
    exit;   
}

// Load the ProductDetailController to get dynamic content
require_once('Controllers/ProductDetailController.php');
$productDetailCtrl = new ProductDetailController();

// Get the product ID from the existing product detail
$productId = $productDetail['id_product'];

// Get product details including description and policies
$dynamicDetails = $productDetailCtrl->getProductDetails($productId);

// Get product images
$productImages = $productDetailCtrl->getProductImages($productId);

// Get related products based on category
require_once('Models/ProductModel.php');
$productModel = new ProductModel();
$relatedProducts = $productModel->getRelatedProducts($productDetail['id_Category'], $productId, 8);
                
// Set default images if any are missing
$mainImage = !empty($productImages['main_image']) ? $productImages['main_image'] : 'default.jpg';
$image2 = !empty($productImages['img2']) ? $productImages['img2'] : '';
$image3 = !empty($productImages['img3']) ? $productImages['img3'] : '';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $productDetail['name'];?> - 160STORE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/DetailProduct.css">
</head>
<body>
    <div class="container mt-4">
        <!-- Breadcrumb -->
        <div class="breadcrumb mb-3">
            <a href="#" onclick="goBack()">Trang chủ</a> / 
          
            <span><?= $productDetail['name'];?></span>
        </div>

        <div class="row gx-4">
            <!-- Product Images - Left side -->
            <div class="col-md-6">
                <div class="product-main-image-container mb-3">
                    <img id="mainProductImage" src="/Project_Website/ProjectWeb/upload/img/All-Product/<?= $mainImage; ?>" class="product-main-image" alt="<?= $productDetail['name'];?>">
                </div>
                <div class="product-thumbnails-wrapper">
                    <div class="product-thumbnails-container">
                        <img src="/Project_Website/ProjectWeb/upload/img/All-Product/<?= $mainImage; ?>" class="product-thumbnail img-thumbnail active" alt="Thumbnail 1" onclick="changeMainImage(this, '/Project_Website/ProjectWeb/upload/img/All-Product/<?= $mainImage; ?>')">
                        <?php if (!empty($image2)): ?>
                        <img src="/Project_Website/ProjectWeb/upload/img/All-Product/<?= $image2; ?>" class="product-thumbnail img-thumbnail" alt="Thumbnail 2" onclick="changeMainImage(this, '/Project_Website/ProjectWeb/upload/img/All-Product/<?= $image2; ?>')">
                        <?php endif; ?>
                        <?php if (!empty($image3)): ?>
                        <img src="/Project_Website/ProjectWeb/upload/img/All-Product/<?= $image3; ?>" class="product-thumbnail img-thumbnail" alt="Thumbnail 3" onclick="changeMainImage(this, '/Project_Website/ProjectWeb/upload/img/All-Product/<?= $image3; ?>')">
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Product Info Highlights for Mobile - Added for better mobile layout -->
                <div class="d-block d-md-none mobile-product-highlights mt-3">
                    <div class="guarantee-item">
                        <i class="fas fa-undo-alt"></i>
                        <span>Đổi trả trong 15 ngày</span>
                    </div>
                    <div class="guarantee-item">
                        <i class="fas fa-shipping-fast"></i>
                        <span>Freeship đơn từ 250K</span>
                    </div>
                </div>
            </div>

            <!-- Product Details - Right side -->
            <div class="col-md-6">
                <div class="product-details-container">
                    <!-- Product Title -->
                    <h1 class="product-main-title"><?= $productDetail['name'];?></h1>
                    
                    <!-- Product Code -->
                  
                    <!-- Price -->
                    <div class="d-flex align-items-center mb-4">
                    <?php
                        $originalPrice = (float)$productDetail['original_price']; 
                        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
                        $totalPrice =(float)$productDetail['current_price'] * $quantity;
                  
                    ?>
                    <span class="current-price me-2" id="totalPrice">
                          <?= number_format($totalPrice, 0, ',', '.'); ?>₫
                    </span>
                    <?php if ((float)$productDetail['discount_percent'] > 0): ?>
                        <span class="original-price text-decoration-line-through"><?= number_format($originalPrice* $quantity, 0, ',', '.'); ?>₫</span>
                    <?php endif; ?>
                </div>


                 
                 <?php
                    // Lấy thông tin số lượng cho mỗi size
                    // Giả sử bạn đã có $productDetail chứa thông tin sản phẩm
                    $sizeInfo = [
                        'M' => [
                            'quantity' => (int)$productDetail['M'],
                            'label' => 'M'
                        ],
                        'L' => [
                            'quantity' => (int)$productDetail['L'],
                            'label' => 'L'
                        ],
                        'XL' => [
                            'quantity' => (int)$productDetail['XL'],
                            'label' => 'XL'
                        ]
                    ];

                    // Lọc ra chỉ những size có hàng (quantity > 0)
                    $availableSizes = array_filter($sizeInfo, function($size) {
                        return $size['quantity'] > 0;
                    });
                ?>
                 
                 <!-- Size Selection -->
           <!-- Size Selection -->
                    <div class="mb-4">
                        <div class="fw-bold mb-3">Kích thước:</div>
                        <div class="d-flex">
                            <?php
                            // Kiểm tra nếu có size nào có sẵn
                            if (!empty($availableSizes)):
                                foreach ($availableSizes as $sizeCode => $sizeData):
                            ?>
                                <div class="size-option me-3" data-size="<?= $sizeCode ?>" data-quantity="<?= $sizeData['quantity'] ?>">
                                    <div class="size-box text-center mb-1">
                                        <span class="d-block"><?= $sizeData['label'] ?></span>
                                    </div>
                                    <div class="size-stock small text-center">
                                        <span class="text-muted"><?= $sizeData['quantity'] ?> sản phẩm</span>
                                    </div>
                                </div>
                            <?php
                                endforeach;
                            else:
                                // Hiển thị khi không có size nào có sẵn
                            ?>
                                <p class="text-dark">Sản phẩm hiện tại không có size nào.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Quantity Selection -->
                    <div class="mb-4">
                        <div class="fw-bold mb-3">Số lượng:</div>
                        <div class="d-flex align-items-center">
                            <button class="btn btn-outline-dark quantity-btn" type="button" id="decreaseQuantity">-</button>
                            <input type="text" class="form-control mx-2 quantity-input" id="quantityInput" value="1" readonly>
                            <button class="btn btn-outline-dark quantity-btn" type="button" id="increaseQuantity">+</button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 mb-4">
                        <div class="row g-2">
                                                        
                             <button id="buyNowBtn" class="btn btn-outline-dark w-100 buy-now " type="button" data-id="<?= $productDetail['id_product'] ?>">MUA NGAY</button>
                           
                            <!-- THÊM VÀO GIỎ - Gửi bằng JS -->
                            <button id="addToCartBtn" class="btn btn-outline-dark w-100" type="button" data-id="<?= $productDetail['id_product'] ?>">THÊM VÀO GIỎ</button>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Tabs -->
        <div class="container mt-5">
            <div class="tab-container mt-5">
                <div class="tab active" onclick="openTab('mo-ta')">Mô tả</div>
                <div class="tab" onclick="openTab('giao-hang')">Chính sách giao hàng</div>
                <div class="tab" onclick="openTab('doi-hang')">Chính sách đổi hàng</div>
            </div>
            
            <!-- Tab contents -->
            <div id="mo-ta" class="tab-content active">
                <div class="row">
                    <div class="col-lg-8">
                        <h2>Mô Tả</h2>
                        <div class="product-description-content">
                            <p class="fw-bold mb-3"><?= $productDetail['name'];?></p>
                            
                            <?php if (!empty($dynamicDetails['description'])): ?>
                                <?=  $dynamicDetails['description']; ?>
                            <?php else: ?>
                                <div class="mb-4">
                                    <h3>✔ Sản phẩm chưa có mô tả chi tiết</h3>
                                    <p>Vui lòng liên hệ bộ phận chăm sóc khách hàng để biết thêm thông tin.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <?php if (!empty($image2)): ?>
                            <img src="/Project_Website/ProjectWeb/upload/img/All-Product/<?= $image2; ?>" alt="Product Detail" class="img-fluid rounded">
                        <?php else: ?>
                            <img src="/Project_Website/ProjectWeb/upload/img/DetailProduct/detailItem2.webp" alt="Product Detail" class="img-fluid rounded">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div id="giao-hang" class="tab-content">
                <h2>Chính Sách Giao Hàng</h2>
                <div class="text-center">
                    <?php if (!empty($dynamicDetails['CSGiaoHang'])): ?>
                        <img src="/Project_Website/ProjectWeb/upload/img/DetailProduct/<?= $dynamicDetails['CSGiaoHang']; ?>" alt="Chính sách giao hàng" class="img-fluid">
                    <?php else: ?>
                        <img src="/Project_Website/ProjectWeb/upload/img/DetailProduct/cs_giaohanh.webp" alt="Chính sách giao hàng mặc định" class="img-fluid">
                    <?php endif; ?>
                </div>
            </div>
            <div id="doi-hang" class="tab-content">
                <h2>Chính Sách Đổi Hàng</h2>
                <div class="text-center">
                    <?php if (!empty($dynamicDetails['CSDoiTra'])): ?>
                        <img src="/Project_Website/ProjectWeb/upload/img/DetailProduct/<?= $dynamicDetails['CSDoiTra']; ?>" alt="Chính sách đổi hàng" class="img-fluid">
                    <?php else: ?>
                        <img src="/Project_Website/ProjectWeb/upload/img/DetailProduct/doitra_1.webp" alt="Chính sách đổi hàng mặc định" class="img-fluid">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Products Section -->
    <div class="container my-5">
        <section class="related-products-section">
            <h3 class="section-title">SẢN PHẨM LIÊN QUAN</h3>
            
            <?php if (!empty($relatedProducts)): ?>
                <div class="related-products-carousel">
                    <button class="carousel-control carousel-control-prev" id="prevBtn">&lt;</button>
                    
                    <div class="related-products-wrapper" id="productsWrapper">
                        <div class="related-products-slider" id="productsSlider">
                            <?php foreach ($relatedProducts as $relatedProduct): ?>
                                <div class="related-product-card">
                                    <?php if (!empty($relatedProduct['discount_percent']) && $relatedProduct['discount_percent'] > 0): ?>
                                        <span class="badge-discount">-<?= $relatedProduct['discount_percent'] ?>%</span>
                                    <?php else: ?>
                                        <span class="badge-new">Hàng Mới</span>
                                    <?php endif; ?>
                                    
                                    <div class="product-image-container">
                                        <a href="index.php?controller=product&action=show&id=<?= $relatedProduct['id_product'] ?>">
                                            <img src="/Project_Website/ProjectWeb/upload/img/All-Product/<?= $relatedProduct['main_image'] ?>" 
                                                 alt="<?= htmlspecialchars($relatedProduct['name']) ?>"
                                                 onerror="this.src='/Project_Website/ProjectWeb/upload/img/All-Product/default.jpg'">
                                        </a>
                                    </div>
                                    
                                    <div class="product-info-container">
                                        <h6>
                                            <a href="index.php?controller=product&action=show&id=<?= $relatedProduct['id_product'] ?>" 
                                               class="text-decoration-none text-dark">
                                                <?= htmlspecialchars($relatedProduct['name']) ?>
                                            </a>
                                        </h6>
                                        
                                        <div class="product-price-container">
                                            <p class="product-price">₫<?= number_format($relatedProduct['current_price'], 0, ',', '.') ?></p>
                                            <?php if (!empty($relatedProduct['original_price']) && $relatedProduct['original_price'] > $relatedProduct['current_price']): ?>
                                                <p class="product-original-price">₫<?= number_format($relatedProduct['original_price'], 0, ',', '.') ?></p>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if (!empty($relatedProduct['tag'])): ?>
                                            <div class="product-tags">
                                                <?php 
                                                $tags = explode(',', $relatedProduct['tag']);
                                                foreach (array_slice($tags, 0, 2) as $tag): 
                                                    $tag = trim($tag);
                                                    if (!empty($tag)):
                                                ?>
                                                    <span class="product-tag"><?= htmlspecialchars($tag) ?></span>
                                                <?php 
                                                    endif;
                                                endforeach; 
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="product-stats">
                                            <small class="text-muted">
                                                <i class="fas fa-eye"></i> <?= $relatedProduct['click_count'] ?? 0 ?> lượt xem
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <button class="carousel-control carousel-control-next" id="nextBtn">&gt;</button>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <p class="text-muted">Không có sản phẩm liên quan nào để hiển thị.</p>
                    <a href="index.php" class="btn btn-outline-primary">
                        <i class="fas fa-home"></i> Về trang chủ
                    </a>
                </div>
            <?php endif; ?>
        </section>
    </div>
    
    <!-- Toast Notification -->
    <div id="cartToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Thông Báo</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Thêm sản phẩm vào giỏ hàng thành công!
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
        
        function openTab(tabId) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.remove('active');
            });
            
            // Remove active class from all tabs
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab content and mark tab as active
            document.getElementById(tabId).classList.add('active');
            event.currentTarget.classList.add('active');
        }
        
        function changeMainImage(thumbnailElement, imageSrc) {
            // Update main image
            document.getElementById('mainProductImage').src = imageSrc;
            
            // Update active thumbnail
            const thumbnails = document.querySelectorAll('.product-thumbnail');
            thumbnails.forEach(thumb => {
                thumb.classList.remove('active');
            });
            thumbnailElement.classList.add('active');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Project_Website/ProjectWeb/layout/js/DetailProduct.js"></script>
    
    <script>
        // Initialize related products carousel when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeRelatedProductsCarousel();
            
            // Add click tracking for related products
            document.querySelectorAll('.related-product-card a').forEach(link => {
                link.addEventListener('click', function() {
                    // Track related product clicks for analytics
                    const productId = this.href.match(/id=(\d+)/);
                    if (productId) {
                        trackRelatedProductClick(productId[1]);
                    }
                });
            });
        });
        
        function trackRelatedProductClick(productId) {
            // Send analytics data (optional)
            fetch('index.php?controller=analytics&action=trackRelatedClick', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    current_product: <?= $productDetail['id_product'] ?>,
                    clicked_product: productId
                })
            }).catch(err => console.log('Analytics tracking failed:', err));
        }
        
        function initializeRelatedProductsCarousel() {
            const carousel = document.querySelector('.related-products-carousel');
            const slider = document.querySelector('.related-products-slider');
            const cards = document.querySelectorAll('.related-product-card');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            
            if (!carousel || !slider || cards.length === 0) return;
            
            let currentIndex = 0;
            const cardWidth = 280 + 24; // card width + gap
            const visibleCards = Math.floor(carousel.offsetWidth / cardWidth) || 1;
            const maxIndex = Math.max(0, cards.length - visibleCards);
            
            // Update carousel position
            function updateCarousel() {
                const translateX = -currentIndex * cardWidth;
                slider.style.transform = `translateX(${translateX}px)`;
                
                // Update button states
                prevBtn.style.opacity = currentIndex <= 0 ? '0.5' : '1';
                prevBtn.style.pointerEvents = currentIndex <= 0 ? 'none' : 'auto';
                
                nextBtn.style.opacity = currentIndex >= maxIndex ? '0.5' : '1';
                nextBtn.style.pointerEvents = currentIndex >= maxIndex ? 'none' : 'auto';
            }
            
            // Previous button
            prevBtn?.addEventListener('click', () => {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCarousel();
                }
            });
            
            // Next button
            nextBtn?.addEventListener('click', () => {
                if (currentIndex < maxIndex) {
                    currentIndex++;
                    updateCarousel();
                }
            });
            
            // Touch/swipe support for mobile
            let startX = 0;
            let currentX = 0;
            let isDragging = false;
            
            slider.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                isDragging = true;
                slider.style.transition = 'none';
            });
            
            slider.addEventListener('touchmove', (e) => {
                if (!isDragging) return;
                
                currentX = e.touches[0].clientX;
                const diff = startX - currentX;
                const currentTranslate = -currentIndex * cardWidth;
                slider.style.transform = `translateX(${currentTranslate - diff}px)`;
            });
            
            slider.addEventListener('touchend', (e) => {
                if (!isDragging) return;
                
                const diff = startX - currentX;
                const threshold = cardWidth / 3;
                
                slider.style.transition = 'transform 0.3s ease';
                
                if (Math.abs(diff) > threshold) {
                    if (diff > 0 && currentIndex < maxIndex) {
                        currentIndex++;
                    } else if (diff < 0 && currentIndex > 0) {
                        currentIndex--;
                    }
                }
                
                updateCarousel();
                isDragging = false;
            });
            
            // Initialize
            updateCarousel();
            
            // Update on window resize
            window.addEventListener('resize', debounce(() => {
                const newVisibleCards = Math.floor(carousel.offsetWidth / cardWidth) || 1;
                const newMaxIndex = Math.max(0, cards.length - newVisibleCards);
                
                if (currentIndex > newMaxIndex) {
                    currentIndex = newMaxIndex;
                }
                
                updateCarousel();
            }, 250));
            
            // Add animation to cards
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('animate-in');
            });
        }
        
        // Debounce utility function
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    </script>
    
    <style>
        /* Additional styles for related products */
        .badge-discount {
            background-color: #e74c3c;
            color: white;
            padding: 0.3rem 0.6rem;
            border-radius: 15px;
            font-size: 0.8rem;
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
        }
        
        .product-price-container {
            margin: 0.5rem 0;
        }
        
        .product-original-price {
            font-size: 0.9rem;
            color: #999;
            text-decoration: line-through;
            margin: 0;
        }
        
        .product-tags {
            margin: 0.5rem 0;
        }
        
        .product-tag {
            background: #e3f2fd;
            color: #1976d2;
            padding: 0.2rem 0.4rem;
            border-radius: 10px;
            font-size: 0.7rem;
            margin-right: 0.3rem;
            display: inline-block;
        }
        
        .product-stats {
            margin-top: 0.5rem;
        }
        
        .related-product-card {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .related-product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        .product-image-container img {
            transition: transform 0.3s ease;
        }
        
        .related-product-card:hover .product-image-container img {
            transform: scale(1.05);
        }
    </style>
</body>
</html>