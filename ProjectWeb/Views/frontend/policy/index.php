<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?= $policy['title'] ?? 'Chính Sách' ?> - RSStore</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Footer.css">
</head>
<body>
<div class="policy-container">
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/Project_Website/ProjectWeb/index.php">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $policy['title'] ?? 'Chính sách' ?></li>
            </ol>
        </nav>
        
        <!-- Title -->
        <div class="policy-title text-center mb-5">
            <h1><?= mb_strtoupper($policy['title'] ?? 'CHÍNH SÁCH', 'UTF-8') ?></h1>
            <div class="title-line mx-auto"></div>
        </div>
        
        <!-- Main Content -->
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="policy-sidebar">
                    <h3 class="sidebar-title">CHÍNH SÁCH</h3>
                    <div class="sidebar-line mb-3"></div>
                    <ul class="policy-nav">
                        <?php if (!empty($policies)): ?>
                            <?php foreach ($policies as $item): ?>
                                <li class="<?= ($policy['id'] == $item['id']) ? 'active' : '' ?>">
                                    <a href="/Project_Website/ProjectWeb/index.php?controller=policy&action=show&id=<?= $item['id'] ?>">
                                        <?php if (!empty($item['image'])): ?>
                                            <img src="/Project_Website/ProjectWeb/upload/img/Footer/<?= $item['image'] ?>" alt="<?= $item['title'] ?>">
                                        <?php else: ?>
                                            <i class="bi bi-file-text"></i>
                                        <?php endif; ?>
                                        <span><?= $item['title'] ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>
                                <span class="text-muted">Không có chính sách nào</span>
                            </li>
                        <?php endif; ?>
                    </ul>
                    
                    <div class="sidebar-contact mt-5">
                        <h3 class="sidebar-title">LIÊN HỆ</h3>
                        <div class="sidebar-line mb-3"></div>
                        <div class="contact-info">
                            <p><i class="bi bi-telephone"></i> Hotline: <a href="tel:<?= htmlspecialchars($settings['contact_phone']['setting_value'] ?? '1900 633 349') ?>"><?= htmlspecialchars($settings['contact_phone']['setting_value'] ?? '1900 633 349') ?></a></p>
                            <p><i class="bi bi-envelope"></i> Email: <a href="mailto:<?= htmlspecialchars($settings['admin_email']['setting_value'] ?? 'chamsockhachhang@rsstore.com') ?>"><?= htmlspecialchars($settings['admin_email']['setting_value'] ?? 'chamsockhachhang@rsstore.com') ?></a></p>
                        </div>
                        <div class="contact-social">
                            <?php if (!empty($socialMedia)): ?>
                                <?php foreach ($socialMedia as $social): ?>
                                    <a href="<?= htmlspecialchars($social['link'] ?? '#') ?>" class="social-icon" target="_blank" rel="noopener">
                                        <i class="<?= htmlspecialchars($social['icon'] ?? 'fab fa-link') ?>"></i>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <!-- Fallback social icons if no data is available -->
                                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-tiktok"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="policy-content">
                    <?php if (!empty($policy)): ?>
                        <!-- Hiển thị ảnh chính sách -->
                        <?php if (!empty($policy['image'])): ?>
                            <div class="policy-image mb-4">
                                <img src="/Project_Website/ProjectWeb/upload/img/Footer/<?= $policy['image'] ?>" 
                                     alt="<?= $policy['title'] ?>" class="img-fluid">
                            </div>
                        <?php endif; ?>
                        
                        <!-- Hiển thị ảnh phụ 1 (nếu có) -->
                        <?php if (!empty($policy['image2'])): ?>
                            <div class="policy-image mb-4">
                                <img src="/Project_Website/ProjectWeb/upload/img/Footer/<?= $policy['image2'] ?>" 
                                     alt="<?= $policy['title'] ?>" class="img-fluid">
                            </div>
                        <?php endif; ?>
                        
                        <!-- Hiển thị ảnh phụ 2 (nếu có) -->
                        <?php if (!empty($policy['image3'])): ?>
                            <div class="policy-image mb-4">
                                <img src="/Project_Website/ProjectWeb/upload/img/Footer/<?= $policy['image3'] ?>" 
                                     alt="<?= $policy['title'] ?>" class="img-fluid">
                            </div>
                        <?php endif; ?>
                        
                        <!-- Hiển thị nội dung phụ (nếu cần) -->
                        <div class="policy-section">
                            <h2><?= $policy['title'] ?></h2>
                            <p><?= $policy['meta'] ?? 'Thông tin đang được cập nhật.' ?></p>
                        </div>
                    <?php else: ?>
                        <div class="policy-section">
                            <h2>Không tìm thấy chính sách</h2>
                            <p>Nội dung đang được cập nhật...</p>
                        </div>
                    <?php endif; ?>
                    
                    
                </div>
            </div>
        </div>
    </div>
</div> 

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script src="/Project_Website/ProjectWeb/layout/js/Header.js"></script>
    <script src="/Project_Website/ProjectWeb/layout/js/Footer.js"></script>
</body>
</html>
