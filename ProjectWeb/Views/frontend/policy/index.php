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
            <h1><?= strtoupper($policy['title'] ?? 'CHÍNH SÁCH') ?></h1>
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
                            <p><i class="bi bi-telephone"></i> Hotline: <a href="tel:1900633349">1900 633 349</a></p>
                            <p><i class="bi bi-envelope"></i> Email: <a href="mailto:chamsockhachhang@rsstore.com">chamsockhachhang@rsstore.com</a></p>
                        </div>
                        <div class="contact-social">
                            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-tiktok"></i></a>
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