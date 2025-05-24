<style>
/* Thêm style cho các field bị disabled */
.form-control:disabled,
.form-select:disabled {
    background-color: #f8f9fa !important;
    opacity: 0.65;
    cursor: not-allowed;
}

.form-control:disabled:focus,
.form-select:disabled:focus {
    background-color: #f8f9fa !important;
    border-color: #ced4da;
    box-shadow: none;
}

/* Style cho address cards */
.address-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.address-card:hover {
    border-color: #ddd;
}

.address-card.border-primary {
    border-color: #0d6efd;
    background-color: rgba(13, 110, 253, 0.03);
}

.shipping-divider {
    position: relative;
    text-align: center;
}

.shipping-divider hr {
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    margin: 0;
    z-index: 1;
}

.shipping-divider p {
    display: inline-block;
    position: relative;
    padding: 0 15px;
    background-color: #f0f0f0;
    z-index: 2;
    margin: 0;
}

/* Notification styles */
.notification-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

.notification {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    margin-bottom: 10px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateX(100%);
    transition: transform 0.3s ease;
    min-width: 300px;
}

.notification.show {
    transform: translateX(0);
}

.notification-success {
    background-color: #d4edda;
    border-left: 4px solid #28a745;
    color: #155724;
}

.notification-error {
    background-color: #f8d7da;
    border-left: 4px solid #dc3545;
    color: #721c24;
}

.notification-icon {
    margin-right: 10px;
    font-size: 18px;
}

.notification-content {
    flex: 1;
}
</style><!DOCTYPE html>
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
                        <?php if ($hasAddresses): ?>
                        <!-- Hiển thị sổ địa chỉ khi có địa chỉ -->
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
                                        <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <!-- Hiển thị thông báo khi chưa có địa chỉ -->
                        <div class="no-address-notice mb-4">
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>
                                <div>
                                    <strong>Chưa có địa chỉ giao hàng</strong><br>
                                    Vui lòng nhập thông tin địa chỉ giao hàng bên dưới. Bạn có thể chọn lưu địa chỉ này để sử dụng cho các đơn hàng sau.
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="shipping-fields">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="fullname" class="form-label">Họ và tên người nhận <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="fullname" name="fullname" required 
                                        placeholder="Nhập họ và tên người nhận hàng"
                                        value="<?= isset($userData['name']) ? htmlspecialchars($userData['name']) : '' ?>">
                                    <div class="invalid-feedback">Vui lòng nhập họ và tên người nhận.</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
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
                                    <label for="address" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="address" name="address" required 
                                        placeholder="Số nhà, tên đường">
                                    <div class="invalid-feedback">Vui lòng nhập địa chỉ.</div>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="province" class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                    <select class="form-select" id="province" name="province" required>
                                        <option value="">Chọn tỉnh/thành phố</option>
                                        <option value="TP. Hồ Chí Minh">TP. Hồ Chí Minh</option>
                                        <option value="Hà Nội">Hà Nội</option>
                                        <option value="Đà Nẵng">Đà Nẵng</option>
                                        <option value="Hải Phòng">Hải Phòng</option>
                                        <option value="Cần Thơ">Cần Thơ</option>
                                        <option value="An Giang">An Giang</option>
                                        <option value="Bà Rịa - Vũng Tàu">Bà Rịa - Vũng Tàu</option>
                                        <option value="Bắc Giang">Bắc Giang</option>
                                        <option value="Bắc Kạn">Bắc Kạn</option>
                                        <option value="Bạc Liêu">Bạc Liêu</option>
                                        <option value="Bắc Ninh">Bắc Ninh</option>
                                        <option value="Bến Tre">Bến Tre</option>
                                        <option value="Bình Định">Bình Định</option>
                                        <option value="Bình Dương">Bình Dương</option>
                                        <option value="Bình Phước">Bình Phước</option>
                                        <option value="Bình Thuận">Bình Thuận</option>
                                        <option value="Cà Mau">Cà Mau</option>
                                        <option value="Cao Bằng">Cao Bằng</option>
                                        <option value="Đắk Lắk">Đắk Lắk</option>
                                        <option value="Đắk Nông">Đắk Nông</option>
                                        <option value="Điện Biên">Điện Biên</option>
                                        <option value="Đồng Nai">Đồng Nai</option>
                                        <option value="Đồng Tháp">Đồng Tháp</option>
                                        <option value="Gia Lai">Gia Lai</option>
                                        <option value="Hà Giang">Hà Giang</option>
                                        <option value="Hà Nam">Hà Nam</option>
                                        <option value="Hà Tĩnh">Hà Tĩnh</option>
                                        <option value="Hải Dương">Hải Dương</option>
                                        <option value="Hậu Giang">Hậu Giang</option>
                                        <option value="Hòa Bình">Hòa Bình</option>
                                        <option value="Hưng Yên">Hưng Yên</option>
                                        <option value="Khánh Hòa">Khánh Hòa</option>
                                        <option value="Kiên Giang">Kiên Giang</option>
                                        <option value="Kon Tum">Kon Tum</option>
                                        <option value="Lai Châu">Lai Châu</option>
                                        <option value="Lâm Đồng">Lâm Đồng</option>
                                        <option value="Lạng Sơn">Lạng Sơn</option>
                                        <option value="Lào Cai">Lào Cai</option>
                                        <option value="Long An">Long An</option>
                                        <option value="Nam Định">Nam Định</option>
                                        <option value="Nghệ An">Nghệ An</option>
                                        <option value="Ninh Bình">Ninh Bình</option>
                                        <option value="Ninh Thuận">Ninh Thuận</option>
                                        <option value="Phú Thọ">Phú Thọ</option>
                                        <option value="Phú Yên">Phú Yên</option>
                                        <option value="Quảng Bình">Quảng Bình</option>
                                        <option value="Quảng Nam">Quảng Nam</option>
                                        <option value="Quảng Ngãi">Quảng Ngãi</option>
                                        <option value="Quảng Ninh">Quảng Ninh</option>
                                        <option value="Quảng Trị">Quảng Trị</option>
                                        <option value="Sóc Trăng">Sóc Trăng</option>
                                        <option value="Sơn La">Sơn La</option>
                                        <option value="Tây Ninh">Tây Ninh</option>
                                        <option value="Thái Bình">Thái Bình</option>
                                        <option value="Thái Nguyên">Thái Nguyên</option>
                                        <option value="Thanh Hóa">Thanh Hóa</option>
                                        <option value="Thừa Thiên Huế">Thừa Thiên Huế</option>
                                        <option value="Tiền Giang">Tiền Giang</option>
                                        <option value="Trà Vinh">Trà Vinh</option>
                                        <option value="Tuyên Quang">Tuyên Quang</option>
                                        <option value="Vĩnh Long">Vĩnh Long</option>
                                        <option value="Vĩnh Phúc">Vĩnh Phúc</option>
                                        <option value="Yên Bái">Yên Bái</option>
                                    </select>
                                    <div class="invalid-feedback">Vui lòng chọn tỉnh/thành phố.</div>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="district" class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                    <select class="form-select" id="district" name="district" required disabled>
                                        <option value="">Chọn quận/huyện</option>
                                    </select>
                                    <div class="invalid-feedback">Vui lòng chọn quận/huyện.</div>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="ward" class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                                    <select class="form-select" id="ward" name="ward" required disabled>
                                        <option value="">Chọn phường/xã</option>
                                    </select>
                                    <div class="invalid-feedback">Vui lòng chọn phường/xã.</div>
                                </div>
                                
                                <div class="col-12">
                                    <label for="note" class="form-label">Ghi chú (tùy chọn)</label>
                                    <textarea class="form-control" id="note" name="note" rows="2" 
                                        placeholder="Thông tin bổ sung về đơn hàng hoặc giao hàng"></textarea>
                                </div>
                                
                                <!-- "Save this address" Checkbox - Hiển thị khi người dùng đăng nhập và nhập địa chỉ mới -->
                                <?php if (isset($_SESSION['user'])): ?>
                                <div class="col-12 save-address-option" <?= $hasAddresses ? 'style="display: none;"' : '' ?>>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="save_address" name="save_address" value="1" <?= !$hasAddresses ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="save_address">
                                            <i class="fas fa-bookmark me-1"></i>
                                            Lưu địa chỉ này vào sổ địa chỉ để sử dụng cho các đơn hàng sau
                                        </label>
                                    </div>
                                </div>
                                <?php endif; ?>
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
                        
                        <!-- Hidden fields for form processing -->
                        <input type="hidden" name="selected_address_id" id="selected_address_id" value="<?= isset($addresses) && !empty($addresses) && $addresses[0]['is_default'] ? $addresses[0]['id'] : 'new' ?>">
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

            // Xử lý hiển thị/ẩn checkbox lưu địa chỉ
            const addressSelectors = document.querySelectorAll('.address-selector');
            const saveAddressOption = document.querySelector('.save-address-option');
            
            if (addressSelectors.length > 0 && saveAddressOption) {
                addressSelectors.forEach(selector => {
                    selector.addEventListener('change', function() {
                        if (this.value === 'new') {
                            // Hiển thị tùy chọn lưu địa chỉ khi chọn địa chỉ mới
                            saveAddressOption.style.display = 'block';
                        } else {
                            // Ẩn tùy chọn lưu địa chỉ khi chọn địa chỉ có sẵn
                            saveAddressOption.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>