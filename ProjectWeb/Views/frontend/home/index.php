<?php    
    view('frontend.partitions.frontend.header');
?>
    <!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>160STORE - Chuỗi Phân Phối Thời Trang Nam Chuẩn Hiệu</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/WEB_BAN_THOI_TRANG/layout/css/Home.css">
</head>
<body>

     <!-- Hero Carousel -->
     <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="/Web_Ban_Thoi_Trang/upload/img/Home/Banner1.webp" class="d-block w-100" alt="ProCOOL™ - Mát lạnh giảm 10%">
            </div>
            <div class="carousel-item">
                <img src="/Web_Ban_Thoi_Trang/upload/img/Home/Banner2.webp" class="d-block w-100" alt="ICON Denim - New Collection">
            </div>
            <div class="carousel-item">
                <img src="/Web_Ban_Thoi_Trang/upload/img/Home/Banner3.webp" class="d-block w-100" alt="Smart Jeans™ - Siêu co giãn">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    
    <!-- Featured Products -->
    <div class="container">
        <h2 class="section-title">Sản phẩm nổi bật</h2>
        <div class="row">
            <!-- Product Card 1 -->
            <?php foreach ($mostViewProducts as $product): ?>
                <div class="col-lg-3 col-md-4 col-6">
                    <a href="index.php?controller=product&action=show&id=<?= $product['id'] ?>" class="product-card">
                        <div class="product-image">
                            <img src="<?= $product['image']; ?>" alt="<?= $product['name']; ?>">
                        </div>
                        <div class="product-info">
                            <h3 class="product-title"><?= $product['name']; ?></h3>
                            <p class="product-price"><?= number_format($product['price'], 0); ?>₫</p>
                            <button class="btn-add-cart">Thêm vào giỏ</button>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="container">
        <h2 class="section-title">Danh mục sản phẩm</h2>
        <div class="row">
            <!-- Category 1 -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="category-card">
                    <img src="/Web_Ban_Thoi_Trang/upload/img/Home/item5.webp" alt="Áo Thun" class="category-image">
                    <div class="category-title">Áo Thun</div>
                </div>
            </div>
            
            <!-- Category 2 -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="category-card">
                    <img src="/Web_Ban_Thoi_Trang/upload/img/Home/item6.webp" alt="Áo Polo" class="category-image">
                    <div class="category-title">Áo Polo</div>
                </div>
            </div>
            
            <!-- Category 3 -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="category-card">
                    <img src="/Web_Ban_Thoi_Trang/upload/img/Home/item7.webp" alt="Quần Jean" class="category-image">
                    <div class="category-title">Quần Jean</div>
                </div>
            </div>

              <!-- Category 2 -->
              <div class="col-lg-2 col-md-4 col-6">
                <div class="category-card">
                    <img src="/Web_Ban_Thoi_Trang/upload/img/Home/item6.webp" alt="Áo Polo" class="category-image">
                    <div class="category-title">Áo Polo</div>
                </div>
            </div>
            
            <!-- Category 3 -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="category-card">
                    <img src="/Web_Ban_Thoi_Trang/upload/img/Home/item7.webp" alt="Quần Jean" class="category-image">
                    <div class="category-title">Quần Jean</div>
                </div>
            </div>
             <!-- Category 3 -->
             <div class="col-lg-2 col-md-4 col-6">
                <div class="category-card">
                    <img src="/Web_Ban_Thoi_Trang/upload/img/Home/item7.webp" alt="Quần Jean" class="category-image">
                    <div class="category-title">Quần Jean</div>
                </div>
            </div>
            <!-- Additional categories would go here -->
        </div>
    </div>

    <div class="container">
        <h2 class="section-title">Sản phẩm bán chạy</h2>
        <div class="row">
             <!-- Product Card 1 -->
             <?php foreach ($mostSaleProducts as $product): ?>
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?= $product['image']; ?>" alt="<?= $product['name']; ?>">
                        </div>
                        <div class="product-info">
                            <h3 class="product-title"><?= $product['name']; ?></h3>
                            <p class="product-price"><?= number_format($product['price'], 0); ?>₫</p>
                            <button class="btn-add-cart">Thêm vào giỏ</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
        </div>
    </div>

     <!-- Collections Banner -->
     <div class="container">
        <div class="row g-4 mt-4">
            <div class="col-md-6">
                <div class="collection-card">
                    <img src="/Web_Ban_Thoi_Trang/upload/img/Home/Banner4.webp" class="card-img" alt="ProCOOL™ Collection">
                    <div class="collection-overlay">
                        <h3 class="collection-title">ProCOOL™ Collection</h3>
                        <p class="collection-description">Công nghệ vải mát lạnh, thoáng khí cao</p>
                        <a href="#" class="btn btn-view-collection">Xem thêm</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="collection-card">
                    <img src="/Web_Ban_Thoi_Trang/upload/img/Home/Banner2.webp" class="card-img" alt="Smart Jeans™ Collection">
                    <div class="collection-overlay">
                        <h3 class="collection-title">Smart Jeans™ Collection</h3>
                        <p class="collection-description">Co giãn 360° thoải mái suốt ngày dài</p>
                        <a href="#" class="btn btn-view-collection">Xem thêm</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <h2 class="section-title">Sản phẩm mới</h2>
        <div class="row">
            <!-- Product Card 1 -->
            <?php foreach ($newProducts as $product): ?>
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?= $product['image']; ?>" alt="<?= $product['name']; ?>">
                        </div>
                        <div class="product-info">
                            <h3 class="product-title"><?= $product['name']; ?></h3>
                            <p class="product-price"><?= number_format($product['price'], 0); ?>₫</p>
                            <button class="btn-add-cart">Thêm vào giỏ</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="./js/Home.js"></script>
 
</body>
</html>
   <?php    
    view('frontend.partitions.frontend.footer');
    
?>