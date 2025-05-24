<!-- <?php
session_start(); // KHÔNG được quên dòng này!

if (!isset($_SESSION['visited'])) {
    // Đây là lượt truy cập mới trong phiên này
    $_SESSION['visited'] = true;

    // Ghi nhận vào cơ sở dữ liệu hoặc tăng biến đếm
    $ip = $_SERVER['REMOTE_ADDR'];
    $visited_at = date(format: 'Y-m-d H:i:s');
    // ví dụ SQL: INSERT INTO visit s (ip_address, visited_at) VALUES (...)
    $vs = new VisitModel();
    $vs->createSession($ip);
}
// Lấy đường dẫn favicon từ cài đặt hoặc mặc định
require_once 'Controllers/FooterController.php';
$footerController = new FooterController();
$storeSettings = $footerController->getStoreSettings();
$faviconPath = !empty($storeSettings['favicon_path']) ? $storeSettings['favicon_path'] : '/Project_Website/ProjectWeb/upload/img/Header/favicon.ico';
?>
?> -->



<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSStore - Thời trang nam chính hãng</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <!-- Favicon -->
    <link rel="icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Home.css">
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Footer.css">
</head>

<body>
    <?php
    view('frontend.partitions.frontend.header');
    ?>
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Header.css">


    <!-- Hero Carousel -->
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"
                aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="/Project_Website/ProjectWeb/upload/img/Home/Banner1.webp" class="d-block w-100" alt="ProCOOL™ - Mát lạnh giảm 10%">
            </div>
            <div class="carousel-item">
                <img src="/Project_Website/ProjectWeb/upload/img/Home/Banner2.webp" class="d-block w-100" alt="ICON Denim - New Collection">
            </div>
            <div class="carousel-item">
                <img src="/Project_Website/ProjectWeb/upload/img/Home/Banner3.webp" class="d-block w-100" alt="Smart Jeans™ - Siêu co giãn">
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
        <?php foreach ($mostViewProducts as $product): ?>
            <div class="col-lg-3 col-md-4 col-6">
                <!-- Loại bỏ thẻ <a>, thêm data-product-id -->
                <div class="product-card" data-product-id="<?= $product['id_product'] ?>">
                    <div class="product-image">
                        <img src="/Project_Website/ProjectWeb/upload/img/Home/<?= $product['main_image']; ?>" alt="<?= $product['name']; ?>">
                    </div>
                    <div class="product-info">
                        <h3 class="product-title"><?= $product['name']; ?></h3>
                      <span class="fw-bold current-price text-danger fs-4"><?= number_format($product['current_price'], 0, ',', '.') ?>₫</span>
                                              <?php if($product['original_price'] > $product['current_price']): ?>
                                                <span class="fw-bold original-price original-price text-decoration-line-through"><?= number_format($product['original_price'], 0, ',', '.') ?>₫</span>
                                            <?php endif; ?>
                        
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

    <!-- Categories -->
    <div class="container">
        <h2 class="section-title">Danh mục sản phẩm</h2>
        <div class="row">
            <!-- Category 1 -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="category-card">
                    <img src="/Project_Website/ProjectWeb/upload/img/Home/item5.webp" alt="Áo Thun" class="category-image">
                    <div class="category-title">Áo Thun</div>
                </div>
            </div>

            <!-- Category 2 -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="category-card">
                    <img src="/Project_Website/ProjectWeb/upload/img/Home/item6.webp" alt="Áo Polo" class="category-image">
                    <div class="category-title">Áo Polo</div>
                </div>
            </div>

            <!-- Category 3 -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="category-card">
                    <img src="/Project_Website/ProjectWeb/upload/img/Home/item7.webp" alt="Quần Jean" class="category-image">
                    <div class="category-title">Quần Jean</div>
                </div>
            </div>

            <!-- Category 2 -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="category-card">
                    <img src="/Project_Website/ProjectWeb/upload/img/Home/item6.webp" alt="Áo Polo" class="category-image">
                    <div class="category-title">Áo Polo</div>
                </div>
            </div>

            <!-- Category 3 -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="category-card">
                    <img src="/Project_Website/ProjectWeb/upload/img/Home/item7.webp" alt="Quần Jean" class="category-image">
                    <div class="category-title">Quần Jean</div>
                </div>
            </div>
            <!-- Category 3 -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="category-card">
                    <img src="/Project_Website/ProjectWeb/upload/img/Home/item7.webp" alt="Quần Jean" class="category-image">
                    <div class="category-title">Quần Jean</div>
                </div>
            </div>
            <!-- Additional categories would go here -->
        </div>
    </div>

    <!-- Bestsellers -->
    <div class="container">
        <h2 class="section-title">Sản phẩm bán chạy</h2>
        <div class="row">
            <?php foreach ($mostSaleProducts as $product): ?>
            <div class="col-lg-3 col-md-4 col-6">
                <!-- Loại bỏ thẻ <a>, thêm data-product-id -->
                <div class="product-card" data-product-id="<?= $product['id_product'] ?>">
                    <div class="product-image">
                        <img src="/Project_Website/ProjectWeb/upload/img/Home/<?= $product['main_image']; ?>" alt="<?= $product['name']; ?>">
                    </div>
                    <div class="product-info">
                        <h3 class="product-title"><?= $product['name']; ?></h3>
                      <span class="fw-bold current-price text-danger fs-4"><?= number_format($product['current_price'], 0, ',', '.') ?>₫</span>
                                              <?php if($product['original_price'] > $product['current_price']): ?>
                                                <span class="fw-bold original-price original-price text-decoration-line-through"><?= number_format($product['original_price'], 0, ',', '.') ?>₫</span>
                                            <?php endif; ?>
                     
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
                </div>
            </div>

        
        </div>
    </div>

    <!-- Collections Banner -->
    <!-- <div class="container">
        <div class="row g-4 mt-4">
            <div class="col-md-6">
                <div class="collection-card">
                    <img src="/Project_Website/ProjectWeb/upload/img/Home/Banner4.webp" class="card-img" alt="ProCOOL™ Collection">
                    <div class="collection-overlay">
                        <h3 class="collection-title">ProCOOL™ Collection</h3>
                        <p class="collection-description">Công nghệ vải mát lạnh, thoáng khí cao</p>
                        <a href="#" class="btn btn-view-collection">Xem thêm</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="collection-card">
                    <img src="/Project_Website/ProjectWeb/upload/img/Home/Banner2.webp" class="card-img" alt="Smart Jeans™ Collection">
                    <div class="collection-overlay">
                        <h3 class="collection-title">Smart Jeans™ Collection</h3>
                        <p class="collection-description">Co giãn 360° thoải mái suốt ngày dài</p>
                        <a href="#" class="btn btn-view-collection">Xem thêm</a>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- New Arrivals -->
    <div class="container">
        <h2 class="section-title">Sản phẩm mới</h2>
        <div class="row">
             <?php foreach ($newProducts as $product): ?>
            <div class="col-lg-3 col-md-4 col-6">
                <!-- Loại bỏ thẻ <a>, thêm data-product-id -->
                <div class="product-card" data-product-id="<?= $product['id_product'] ?>">
                    <div class="product-image">
                        <img src="/Project_Website/ProjectWeb/upload/img/Home/<?= $product['main_image']; ?>" alt="<?= $product['name']; ?>">
                    </div>
                    <div class="product-info">
                        <h3 class="product-title"><?= $product['name']; ?></h3>
                      <span class="fw-bold current-price text-danger fs-4"><?= number_format($product['current_price'], 0, ',', '.') ?>₫</span>
                                              <?php if($product['original_price'] > $product['current_price']): ?>
                                                <span class="fw-bold original-price original-price text-decoration-line-through"><?= number_format($product['original_price'], 0, ',', '.') ?>₫</span>
                                            <?php endif; ?>
                 
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>

    <!-- Features -->
    <section class="features-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h3 class="feature-title">Giao hàng toàn quốc</h3>
                        <p class="feature-description">Giao hàng nhanh chóng trên toàn quốc</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-undo"></i>
                        </div>
                        <h3 class="feature-title">Đổi trả miễn phí</h3>
                        <p class="feature-description">Đổi trả sản phẩm trong 30 ngày</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h3 class="feature-title">Thanh toán an toàn</h3>
                        <p class="feature-description">Nhiều phương thức thanh toán an toàn</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3 class="feature-title">Hỗ trợ trực tuyến</h3>
                        <p class="feature-description">Hỗ trợ 24/7 cho mọi thắc mắc</p>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <?php
    view('frontend.partitions.frontend.footer');
    ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script src="/Project_Website/ProjectWeb/layout/js/Header.js"></script>
    <script src="/Project_Website/ProjectWeb/layout/js/Home.js"></script>
    <script src="/Project_Website/ProjectWeb/layout/js/Footer.js"></script>
</body>

</html>