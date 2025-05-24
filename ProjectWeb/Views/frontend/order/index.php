<?php
// Thêm vào đầu file để lấy thông tin cài đặt
require_once 'Controllers/FooterController.php';
$footerController = new FooterController();
$storeSettings = $footerController->getStoreSettings();
$siteName = $storeSettings['site_name'] ?? 'RSStore';
$faviconPath = !empty($storeSettings['favicon_path']) ? $storeSettings['favicon_path'] : '/Project_Website/ProjectWeb/upload/img/Header/favicon.ico';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($siteName) ?> - <?= htmlspecialchars($siteDescription) ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Animation library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Order.css">
    <!-- Favicon -->
    <link rel="icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">
</head>
<body>
    <!-- Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Header Title -->
    <div class="container mb-4">
        <div class="header-title">
            <h2>Đặt hàng</h2>
            <div class="checkout-steps">
                <div class="checkout-step">
                    <div class="step-number">1</div>
                    <span>Giỏ hàng</span>
                </div>
                <div class="checkout-step active">
                    <div class="step-number">2</div>
                    <span>Đặt hàng</span>
                </div>
                <div class="checkout-step">
                    <div class="step-number">3</div>
                    <span>Hoàn tất</span>
                </div>
            </div>
        </div>
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="index.php?controller=cart&action=index">Giỏ hàng</a></li>
                <li class="breadcrumb-item active" aria-current="page">Đặt hàng</li>
            </ol>
        </nav>
    </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="container">
            <div class="alert alert-<?= $_SESSION['flash_message']['type'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['flash_message']['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="container">
        <div class="row g-4 checkout-container">
            <div class="col-md-7 order-md-1 order-2">
                <div class="order-summary">
                    <h4 class="section-title">
                        <i class="bi bi-geo-alt-fill me-2"></i> Thông tin giao hàng
                    </h4>
                    
                    <form id="checkout-form" action="index.php?controller=order&action=process" method="POST" novalidate>
                        <?php if (!empty($addresses)): ?>
                        <div class="address-selection mb-4">
                            <h5 class="mb-3">Chọn địa chỉ giao hàng</h5>
                            
                            <div class="row g-3 mb-3">
                                <?php foreach ($addresses as $address): ?>
                                    <div class="col-md-6">
                                        <div class="card address-card <?= $address['is_default'] ? 'border-primary' : '' ?>" data-address-id="<?= $address['id'] ?>">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="mb-0"><?= htmlspecialchars($address['address_name'] ?? 'Địa chỉ') ?></h6>
                                                    <?php if ($address['is_default']): ?>
                                                        <span class="badge bg-primary">Mặc định</span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <p class="mb-1"><strong><?= htmlspecialchars($address['receiver_name']) ?></strong></p>
                                                <p class="mb-1"><?= htmlspecialchars($address['phone']) ?></p>
                                                <p class="mb-1"><?= htmlspecialchars($address['street_address']) ?></p>
                                                <p class="mb-0 text-muted small"><?= htmlspecialchars($address['ward'] . ', ' . $address['district'] . ', ' . $address['province']) ?></p>
                                                
                                                <div class="form-check mt-3">
                                                    <input class="form-check-input address-selector" type="radio" 
                                                        name="selected_address" id="address_<?= $address['id'] ?>" 
                                                        value="<?= $address['id'] ?>" 
                                                        <?= $address['is_default'] ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="address_<?= $address['id'] ?>">
                                                        Giao đến địa chỉ này
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                
                                <!-- "New Address" option -->
                                <div class="col-md-6">
                                    <div class="card address-card h-100" data-address-id="new">
                                        <!-- <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                                            <i class="fas fa-plus-circle fa-3x mb-3 text-muted"></i>
                                            <h6>Sử dụng địa chỉ mới</h6>
                                            <p class="text-muted small mb-3">Nhập thông tin giao hàng mới</p>
                                            
                                            <div class="form-check mt-auto">
                                                <input class="form-check-input address-selector" type="radio" 
                                                    name="selected_address" id="address_new" value="new">
                                                <label class="form-check-label" for="address_new">
                                                    Nhập địa chỉ mới
                                                </label>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            
                          
                        </div>
                        <?php endif; ?>

                        <div class="shipping-fields">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="fullname" class="form-label">Họ và tên người nhận</label>
                                    <input type="text" class="form-control" id="fullname" name="fullname" required 
                                        placeholder="Nhập họ và tên người nhận hàng"
                                        value="<?= isset($userData['name']) ? htmlspecialchars($userData['name']) : '' ?>">
                                    <div class="invalid-feedback">Vui lòng nhập họ và tên người nhận.</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required 
                                        placeholder="Nhập số điện thoại liên hệ"
                                        value="<?= isset($userData['phone']) ? htmlspecialchars($userData['phone']) : '' ?>">
                                    <div class="invalid-feedback">Vui lòng nhập số điện thoại.</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                        placeholder="Nhập email để nhận thông tin đơn hàng"
                                        value="<?= isset($userData['email']) ? htmlspecialchars($userData['email']) : '' ?>">
                                </div>
                                
                              
                                
                                <div class="col-12">
                                    <label for="note" class="form-label">Ghi chú (tùy chọn)</label>
                                    <textarea class="form-control" id="note" name="note" rows="2" 
                                        placeholder="Thông tin bổ sung về đơn hàng hoặc giao hàng"></textarea>
                                </div>
                                
                                <!-- "Save this address" Checkbox - Hiển thị khi người dùng nhập địa chỉ mới -->
                                <div class="col-12 save-address-option" style="display: none;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="save_address" name="save_address" value="1">
                                        <label class="form-check-label" for="save_address">
                                            Lưu địa chỉ này vào sổ địa chỉ
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h4 class="section-title mt-4">
                            <i class="bi bi-truck me-2"></i> Phương thức vận chuyển
                        </h4>
                        <div class="shipping-methods mb-4">
                            <?php foreach ($shippingOptions as $option): ?>
                                <div class="shipping-method <?= ($option['id'] == 'ghn') ? 'selected' : '' ?>" 
                                    data-shipping-id="<?= $option['id'] ?>" 
                                    data-fee="<?= $option['fee'] ?>">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <input type="radio" class="form-check-input me-3" 
                                                name="shipping_method" 
                                                id="shipping-<?= $option['id'] ?>" 
                                                value="<?= $option['id'] ?>"
                                                <?= ($option['id'] == 'ghn') ? 'checked' : '' ?>>
                                            <div>
                                                <div class="shipping-method-title"><?= $option['name'] ?></div>
                                                <div class="text-muted"><?= $option['description'] ?></div>
                                            </div>
                                        </div>
                                        <div class="shipping-fee"><?= number_format($option['fee'], 0, ',', '.') ?>₫</div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <h4 class="section-title">
                            <i class="bi bi-credit-card me-2"></i> Phương thức thanh toán
                        </h4>
                        <div class="payment-methods mb-4">
                            <?php foreach ($paymentMethods as $method): ?>
                                <div class="payment-method <?= ($method['id'] == 'cod') ? 'selected' : '' ?>" 
                                    data-payment-id="<?= $method['id'] ?>">
                                    <div class="d-flex align-items-center">
                                        <input type="radio" class="form-check-input me-3" 
                                            name="payment_method" 
                                            id="payment-<?= $method['id'] ?>" 
                                            value="<?= $method['id'] ?>"
                                            <?= ($method['id'] == 'cod') ? 'checked' : '' ?>>
                                        <img src="<?= $method['icon'] ?>" alt="<?= $method['name'] ?>" class="payment-icon">
                                        <div>
                                            <div class="payment-method-title"><?= $method['name'] ?></div>
                                            <div class="text-muted"><?= $method['description'] ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Discount Code Section - Đã loại bỏ -->
                        
                        <!-- Hidden fields for form processing -->
                        <input type="hidden" name="selected_address_id" id="selected_address_id" value="<?= isset($addresses[0]) && $addresses[0]['is_default'] ? $addresses[0]['id'] : 'new' ?>">
                    </form>
                </div>
            </div>

            <div class="col-md-5 order-md-2 order-1">
                <div class="order-details">
                    <h4 class="section-title mb-4">
                        <i class="bi bi-bag-check-fill me-2"></i> Chi tiết đơn hàng
                    </h4>
                    <div class="product-list mb-4">
                        <?php foreach ($cartItems as $index => $item): ?>
                        <div class="product-item animate__animated animate__fadeIn" data-product-id="<?= $item['product']['id_product'] ?>" data-product-size="<?= $item['size'] ?>" data-max-stock="<?= $item['product'][$item['size']] ?>">
                                <div class="d-flex">
                                    <img src="/Project_Website/ProjectWeb/upload/img/Home/<?= $item['product']['main_image'] ?>" 
                                        alt="<?= $item['product']['name'] ?>" class="product-image">
                                    <div class="product-info">
                                        <div class="product-name"><?= $item['product']['name'] ?></div>
                                        <div class="product-variant">Size: <?= $item['size'] ?></div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="quantity-control">
                                                <button class="qty-btn minus" type="button">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="number" value="<?= $item['quantity'] ?>" min="1" class="qty-input" 
                                                    readonly data-price="<?= $item['subtotal'] ?>" 
                                                    data-original-price="<?= $item['product']['current_price'] ?>">
                                                <button class="qty-btn plus" type="button">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                            <div class="product-price text-end" style="min-width: 110px;">
                                                <?= number_format($item['subtotal'], 0, ',', '.') ?>₫
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="order-summary-pricing">
                        <div class="summary-item">
                            <div class="summary-label">Tạm tính</div>
                            <div class="summary-value" id="subtotal"><?= number_format($total, 0, ',', '.') ?>₫</div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-label">Phí vận chuyển</div>
                            <div class="summary-value" id="shipping-fee">35,000₫</div>
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-item">
                            <div class="summary-label fw-bold">Tổng tiền</div>
                            <div class="summary-total" id="total"><?= number_format($total + 35000, 0, ',', '.') ?>₫</div>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">Đã bao gồm VAT nếu có</small>
                        </div>
                    </div>

                    <button type="button" class="btn btn-complete-order w-100 mt-4" id="complete-order">
                        <i class="bi bi-check2-circle me-2"></i> Hoàn tất đơn hàng
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Success Modal -->
    <div class="modal fade" id="orderSuccessModal" tabindex="-1" aria-labelledby="orderSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <div class="order-success-check">
                        <div class="check-icon">
                            <span class="icon-line line-tip"></span>
                            <span class="icon-line line-long"></span>
                            <div class="icon-circle"></div>
                            <div class="icon-fix"></div>
                        </div>
                    </div>
                    <h4 class="mb-3">Đặt hàng thành công!</h4>
                    <p>Đơn hàng của bạn đã được xác nhận.</p>
                    <p>Mã đơn hàng: <strong><?= $lastOrder['order_number'] ?? 'N/A' ?></strong></p>
                    <p>Chúng tôi sẽ giao hàng cho bạn trong thời gian sớm nhất.</p>
                    <p class="mb-4">Cảm ơn bạn đã mua sắm tại 160STORE!</p>
                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                        <a href="index.php" class="btn btn-outline-primary me-md-2">
                            <i class="bi bi-house-door-fill me-1"></i> Trang chủ
                        </a>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="/Project_Website/ProjectWeb/layout/js/Order.js"></script>
    <script>
        // Xử lý location selects
        document.addEventListener('DOMContentLoaded', function() {
            // Location data for districts and wards
            const locationData = {
                'TP. Hồ Chí Minh': {
                    'Quận 1': ['Phường Bến Nghé', 'Phường Bến Thành', 'Phường Cầu Kho', 'Phường Cô Giang'],
                    'Quận 2': ['Phường An Phú', 'Phường Thảo Điền', 'Phường Bình Trưng Đông', 'Phường Bình Trưng Tây'],
                    'Quận 3': ['Phường 1', 'Phường 2', 'Phường 3', 'Phường 4']
                },
                'Hà Nội': {
                    'Quận Ba Đình': ['Phường Phúc Xá', 'Phường Trúc Bạch', 'Phường Vĩnh Phúc', 'Phường Cống Vị'],
                    'Quận Hoàn Kiếm': ['Phường Hàng Bạc', 'Phường Hàng Bồ', 'Phường Hàng Buồm', 'Phường Hàng Đào'],
                    'Quận Tây Hồ': ['Phường Bưởi', 'Phường Nhật Tân', 'Phường Quảng An', 'Phường Xuân La']
                },
                'Đà Nẵng': {
                    'Quận Hải Châu': ['Phường Hải Châu 1', 'Phường Hải Châu 2', 'Phường Nam Dương', 'Phường Phước Ninh'],
                    'Quận Thanh Khê': ['Phường An Khê', 'Phường Chính Gián', 'Phường Hòa Khê', 'Phường Tân Chính'],
                    'Quận Sơn Trà': ['Phường An Hải Bắc', 'Phường An Hải Đông', 'Phường An Hải Tây', 'Phường Mân Thái']
                },
                'Lâm Đồng': {
                    'Thành phố Đà Lạt': ['Phường 1', 'Phường 2', 'Phường 3', 'Phường 4', 'Phường 5'],
                    'Huyện Đức Trọng': ['Thị trấn Liên Nghĩa', 'Xã Hiệp An', 'Xã Hiệp Thạnh', 'Xã Liên Hiệp'],
                    'Huyện Lâm Hà': ['Thị trấn Đinh Văn', 'Thị trấn Nam Ban', 'Xã Đan Phượng', 'Xã Đông Thanh']
                }
            };
            
            // Handle location selects
            const provinceSelect = document.getElementById('province');
            const districtSelect = document.getElementById('district');
            const wardSelect = document.getElementById('ward');
            
            if (provinceSelect && districtSelect && wardSelect) {
                // Province change
                provinceSelect.addEventListener('change', function() {
                    const selectedProvince = this.value;
                    
                    // Clear district and ward selects
                    districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                    
                    // Disable ward select
                    wardSelect.disabled = true;
                    
                    if (selectedProvince && locationData[selectedProvince]) {
                        // Enable district select
                        districtSelect.disabled = false;
                        
                        // Add district options
                        for (const district in locationData[selectedProvince]) {
                            const option = document.createElement('option');
                            option.value = district;
                            option.textContent = district;
                            districtSelect.appendChild(option);
                        }
                    } else {
                        // Disable district select if no province selected
                        districtSelect.disabled = true;
                    }
                });
                
                // District change
                districtSelect.addEventListener('change', function() {
                    const selectedProvince = provinceSelect.value;
                    const selectedDistrict = this.value;
                    
                    // Clear ward select
                    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                    
                    if (selectedProvince && selectedDistrict && 
                        locationData[selectedProvince] && 
                        locationData[selectedProvince][selectedDistrict]) {
                        // Enable ward select
                        wardSelect.disabled = false;
                        
                        // Add ward options
                        locationData[selectedProvince][selectedDistrict].forEach(ward => {
                            const option = document.createElement('option');
                            option.value = ward;
                            option.textContent = ward;
                            wardSelect.appendChild(option);
                        });
                    } else {
                        // Disable ward select if no district selected
                        wardSelect.disabled = true;
                    }
                });
            }
        });
    </script>
          
</body>
</html>