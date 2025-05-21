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
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Footer.css">

<body>
<!-- FOOTER SECTION -->
<footer class="footer bg-black text-white py-4">
    <div class="container">
         <!-- Newsletter -->
      

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
                                <a href="#" class="text-white text-decoration-none" data-bs-toggle="collapse"
                                    data-bs-target="#chinhSachList" aria-expanded="true"
                                    aria-controls="chinhSachList">Chính sách</a>
                            </div>
                            <button class="btn btn-link text-white p-0 border-0" type="button"
                                data-bs-toggle="collapse" data-bs-target="#chinhSachList" aria-expanded="true"
                                aria-controls="chinhSachList">
                                <i class="bi bi-chevron-up" id="chinhSachIcon"></i>
                            </button>
                        </div>
                    </li>
                </ul>

                <!-- Dropdown content - Hiển thị danh sách chính sách từ database -->
                <div class="collapse show ms-3" id="chinhSachList">
                    <ul class="list-unstyled">
                        <?php 
                        // Lấy dữ liệu từ controller nếu chưa có
                        if (!isset($policies)) {
                            $footerController = new FooterController();
                            $policies = $footerController->getPoliciesData();
                        }
                        
                        if (!empty($policies)): 
                        ?>
                            <?php foreach ($policies as $policy): ?>
                            <li class="mb-2">
                                <i class="bi bi-circle-fill me-2" style="font-size: 0.5rem;"></i>
                                <a href="<?= htmlspecialchars($policy['link']) ?>" class="text-white text-decoration-none" target="_blank" rel="noopener">
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
        </div>
    </div>
</footer>

<style>
/* CSS cho phương thức thanh toán */
.payment-methods-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.payment-method-item {
    width: 60px;
    height: 40px;
    background-color: rgba(255, 255, 255, 0.9);
    border-radius: 4px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.payment-method-image {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    padding: 5px;
}
</style>

<!-- Inline JavaScript để xử lý toggle chính sách -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Tìm các phần tử cần thiết cho việc toggle
    const chinhSachBtns = document.querySelectorAll('[data-bs-target="#chinhSachList"]');
    const chinhSachIcon = document.getElementById('chinhSachIcon');
    const chinhSachList = document.getElementById('chinhSachList');
    
    if (chinhSachBtns.length > 0 && chinhSachIcon && chinhSachList) {
        // Thiết lập trạng thái icon ban đầu dựa vào trạng thái của chinhSachList
        if (chinhSachList.classList.contains('show')) {
            chinhSachIcon.classList.add('bi-chevron-up');
            chinhSachIcon.classList.remove('bi-chevron-down');
        } else {
            chinhSachIcon.classList.remove('bi-chevron-up');
            chinhSachIcon.classList.add('bi-chevron-down');
        }
        
        // Thêm sự kiện click cho các nút toggle
        chinhSachBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                // Toggle icon khi người dùng click
                chinhSachIcon.classList.toggle('bi-chevron-up');
                chinhSachIcon.classList.toggle('bi-chevron-down');
                
                // Nếu không dùng Bootstrap JS, thì cũng toggle lớp show cho phần tử collapse
                if (!window.bootstrap) {
                    e.preventDefault();
                    chinhSachList.classList.toggle('show');
                }
            });
        });
        
        // Sử dụng sự kiện Bootstrap collapse nếu có sẵn
        if (typeof bootstrap !== 'undefined') {
            chinhSachList.addEventListener('show.bs.collapse', function () {
                chinhSachIcon.classList.add('bi-chevron-up');
                chinhSachIcon.classList.remove('bi-chevron-down');
            });
            
            chinhSachList.addEventListener('hide.bs.collapse', function () {
                chinhSachIcon.classList.remove('bi-chevron-up');
                chinhSachIcon.classList.add('bi-chevron-down');
            });
        }
    }
});
</script>

<!-- Helper function to get social media brand color -->
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

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="/Project_Website/ProjectWeb/layout/js/Footer.js"></script>

</body>
</html>