<?php 
// Include controller để có thể khởi tạo ngay trong view nếu cần
require_once __DIR__ . '/../../../../Controllers/FooterController.php';

// Khởi tạo controller để lấy dữ liệu nếu chưa được khởi tạo trước đó
if (!isset($storeSettings) || !isset($policies) || !isset($socialMedia) || !isset($paymentMethods)) {
    $footerController = new FooterController();
    if (!isset($storeSettings)) {
        $storeSettings = $footerController->getStoreSettings();
    }
    if (!isset($policies)) {
        $policies = $footerController->getPoliciesData();
    }
    if (!isset($socialMedia)) {
        $socialMedia = $footerController->getSocialMedia();
    }
    if (!isset($paymentMethods)) {
        $paymentMethods = $footerController->getPaymentMethods();
    }
}
?>

<!-- FOOTER SECTION -->
<footer class="footer bg-black text-white py-4">
    <style>
        /* CS cho phương thức thanh toán */
.payment-methods-container {
    display: flex;
    flex-wrap: wrap;
    gap: 0.625rem; /* 10px → 0.625rem */
}

.payment-method-item {
    width: 3.75rem; /* 60px → 3.75rem */
    height: 2.5rem; /* 40px → 2.5rem */
    background-color: rgba(255, 255, 255, 0.06);
    border-radius: 0.25rem; /* 4px → 0.25rem */
    padding: 0;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.payment-method-image {
    width: 100%;
    height: 100%;
    object-fit: cover; /* thay vì contain để lấp đầy khung */
    padding: 0; /* bỏ padding hoàn toàn */
}
    </style>
    <div class="container">
        <!-- MAIN FOOTER SECTIONS -->
        <div class="row">
            <!-- GIỚI THIỆU -->
            <div class="col-md-4">
                <h5 class="text-uppercase mb-3">GIỚI THIỆU</h5>
                <p class="mb-2"><?= htmlspecialchars($storeSettings['site_name']) ?> - <?= htmlspecialchars($storeSettings['site_description']) ?></p>
                <ul class="list-unstyled">
                    <li class="mb-2 d-flex align-items-center">
                        <i class="bi bi-telephone me-2"></i>
                        <span><?= htmlspecialchars($storeSettings['contact_phone']) ?></span>
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                        <i class="bi bi-envelope me-2"></i>
                        <span><?= htmlspecialchars($storeSettings['admin_email']) ?></span>
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                        <i class="bi bi-geo-alt me-2"></i>
                        <span><?= htmlspecialchars($storeSettings['contact_address']) ?></span>
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                        <i class="bi bi-clock me-2"></i>
                        <span>Giờ mở cửa : 08:30 - 22:00</span>
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                        <i class="bi bi-headset me-2"></i>
                        <span>Nhân viên tư vấn phản hồi tin nhắn đến 24:00 (Mỗi ngày)</span>
                    </li>
                </ul>
                <div class="mt-3">
                    <a href="http://online.gov.vn/Home/WebDetails/121880" target="_blank" rel="noopener">
                        <img src="/Project_Website/ProjectWeb/upload/img/Footer/FooterBCT.webp" alt="Đã thông báo Bộ Công Thương" class="img-fluid mb-2"
                        style="max-width: 180px; background-color: rgba(255, 255, 255, 0); border-radius: 5px;">
                    </a>
                    <a href="https://www.dmca.com/Protection/Status.aspx?ID=9049de26-d97b-48dc-ab97-1e5fcb221fba&refurl=https://RSStore.com/" target="_blank" rel="noopener">
                        <div>
                            <img src="/Project_Website/ProjectWeb/upload/img/Footer/FooterProtected.png" alt="DMCA Protected" class="img-fluid"
                                style="max-width: 180px; background-color: rgba(255, 255, 255, 0); border-radius: 5px;">
                        </div>
                    </a>   
                </div>
            </div>

           <!-- CHÍNH SÁCH --> 
<div class="col-md-4">
    <h5 class="text-uppercase mb-3">CHÍNH SÁCH</h5>
    <ul class="list-unstyled">
        <li class="mb-2">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <i class="bi bi-circle-fill me-2" style="font-size: 0.5rem;"></i>
                    <a href="javascript:void(0);" class="text-white">Chính sách</a>
                </div>
                <button class="policy-toggle-btn" id="policyToggleBtn">
                    <span id="chinhSachIcon">▼</span>
                </button>
            </div>
        </li>
    </ul>

    <!-- Dropdown content -->
    <div class="ms-3 footer-policy-content" id="policyContent" style="display:block;">
        <ul class="list-unstyled">
            <?php if (!empty($policies)): ?>
                <?php foreach ($policies as $policy): ?>
                <li class="mb-2">
                    <i class="bi bi-circle-fill me-2" style="font-size: 0.5rem;"></i>
                    <a href="<?= htmlspecialchars($policy['link']) ?>" class="text-white">
                        <?= htmlspecialchars($policy['title']) ?>
                    </a>
                </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="mb-2">
                    <span class="text-muted">Không có chính sách nào</span>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>

            <!-- PHƯƠNG THỨC THANH TOÁN -->
            <div class="col-md-4">
                <h5 class="text-uppercase mb-3">PHƯƠNG THỨC THANH TOÁN</h5>
                <div class="mb-4 payment-methods-container">
                    <?php if (!empty($paymentMethods)): ?>
                        <?php foreach ($paymentMethods as $method): ?>
                            <div class="payment-method-item">
                                <img src="<?= htmlspecialchars($method['image']) ?>" 
                                     alt="<?= htmlspecialchars($method['title']) ?>" 
                                     title="<?= htmlspecialchars($method['title']) ?>"
                                     class="payment-method-image">
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Fallback nếu không có phương thức thanh toán -->
                        <div class="payment-method-item">
                            <img src="/Project_Website/ProjectWeb/upload/img/Footer/ThanhToanCOD.webp" 
                                 alt="COD" 
                                 class="payment-method-image">
                        </div>
                    <?php endif; ?>
                </div>
                
                <h5 class="text-uppercase mb-3 mt-4">KẾT NỐI VỚI CHÚNG TÔI</h5>
                <div class="d-flex">
                    <?php if (!empty($socialMedia)): ?>
                        <?php foreach ($socialMedia as $social): ?>
                            <a href="<?= htmlspecialchars($social['link']) ?>" target="_blank" rel="noopener"
                                class="text-white d-inline-flex justify-content-center align-items-center rounded me-2"
                                style="background-color: <?= getSocialMediaColor($social['icon']) ?>; width: 40px; height: 40px;">
                                <i class="<?= htmlspecialchars($social['icon']) ?>"></i>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Fallback social icon nếu không có dữ liệu từ db -->
                        <a href="#" target="_blank" rel="noopener"
                            class="text-white d-inline-flex justify-content-center align-items-center rounded me-2"
                            style="background-color: #4267B2; width: 40px; height: 40px;">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- COPYRIGHT SECTION -->
    <div class="py-3 mt-4">
        <div class="container text-center">
            <p class="mb-0">BẢN QUYỀN THUỘC VỀ <?= strtoupper(htmlspecialchars($storeSettings['site_name'])) ?></p>
            <p class="mb-0">Được phát triển tại Trường Đại Học Tôn Đức Thắng
                <br>
                Phạm Nhựt Huy - 52300030
                <br>
                Trần Thanh Liêm - 52300041
                <br>
                Nguyễn Trần Minh Quân - 52300054
                <br>      
            </p>
        </div>
    </div>
</footer>

<style>
/* CSS cho footer */
.footer a {
    text-decoration: none !important;
}

.footer a:hover {
    text-decoration: none !important;
    color: #cccccc !important; 
}

.policy-toggle-btn {
    background-color: transparent;
    border: none;
    color: white;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    cursor: pointer;
    font-size: 12px;
}

.policy-toggle-btn:hover {
    background-color: rgba(255,255,255,0.2);
}

/* Tạo animation cho dropdown */
.footer-policy-content {
    max-height: 500px;
    overflow: hidden;
    transition: max-height 0.3s ease-in-out;
}

.footer-policy-content.collapsed {
    max-height: 0;
}
</style>

<script>
// Script độc lập
document.addEventListener('DOMContentLoaded', function() {
    // Chỉ chạy khi chưa được khởi tạo
    if (window.footerPolicyInitialized) return;
    window.footerPolicyInitialized = true;
    
    const toggleBtn = document.getElementById('policyToggleBtn');
    const policyContent = document.getElementById('policyContent');
    const icon = document.getElementById('chinhSachIcon');
    
    // Mặc định ban đầu đã mở sẵn
    policyContent.style.display = 'block';
    icon.innerHTML = '▲';
    
    toggleBtn.addEventListener('click', function(e) {
        if (policyContent.style.display === 'block') {
            policyContent.style.display = 'none';
            icon.innerHTML = '▼';
        } else {
            policyContent.style.display = 'block';
            icon.innerHTML = '▲';
        }
    });
});
</script>

<?php
function getSocialMediaColor($icon) {
    $colors = [
        'fa-facebook' => '#4267B2',
        'fa-instagram' => '#C13584',
        'fa-youtube' => '#FF0000',
        'fa-twitter' => '#1DA1F2',
        'fa-tiktok' => '#000000',
        'fa-linkedin' => '#0077B5',
        'fa-pinterest' => '#E60023'
    ];
    
    // Convert to lowercase for case-insensitive comparison
    $icon = strtolower($icon);
    
    // Check if any social media icon pattern matches
    foreach ($colors as $iconPattern => $color) {
        if (strpos($icon, $iconPattern) !== false) {
            return $color;
        }
    }
    
    // Default color if no matching social media found
    return '#666666';
}
?>