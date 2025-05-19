<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán Đơn Hàng - 160STORE</title>
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
</head>
<body>
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
            <div class="col-md-7 order-summary">
                <h4 class="section-title">
                    <i class="bi bi-geo-alt-fill me-2"></i> Thông tin giao hàng
                </h4>
                <form id="checkout-form" action="index.php?controller=order&action=process" method="POST" novalidate>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="fullname" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" required 
                                   placeholder="Nhập họ và tên người nhận hàng"
                                   value="<?= $userData['fullname'] ?? '' ?>">
                            <div class="invalid-feedback">Vui lòng nhập họ và tên.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required 
                                   placeholder="Nhập số điện thoại"
                                   value="<?= $userData['phone'] ?? '' ?>">
                            <div class="invalid-feedback">Vui lòng nhập số điện thoại.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="Nhập email để nhận thông tin đơn hàng"
                                   value="<?= $userData['email'] ?? '' ?>">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control" id="address" name="address" required 
                                   placeholder="Nhập địa chỉ cụ thể (số nhà, tên đường)"
                                   value="<?= $userData['address'] ?? '' ?>">
                            <div class="invalid-feedback">Vui lòng nhập địa chỉ.</div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="province" class="form-label">Tỉnh/Thành phố</label>
                                <select id="province" name="province" class="form-select" required>
                                    <option value="">Chọn tỉnh/thành</option>
                                    <option value="79">TP. Hồ Chí Minh</option>
                                    <option value="01">Hà Nội</option>
                                    <option value="48">Đà Nẵng</option>
                                    <option value="92">Cần Thơ</option>
                                    <option value="74">Bình Dương</option>
                                    <option value="75">Đồng Nai</option>
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn tỉnh/thành phố.</div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="district" class="form-label">Quận/Huyện</label>
                                <select id="district" name="district" class="form-select" required disabled>
                                    <option value="">Chọn quận/huyện</option>
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn quận/huyện.</div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ward" class="form-label">Phường/Xã</label>
                                <select id="ward" name="ward" class="form-select" required disabled>
                                    <option value="">Chọn phường/xã</option>
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn phường/xã.</div>
                            </div>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="note" class="form-label">Ghi chú (tùy chọn)</label>
                            <textarea class="form-control" id="note" name="note" rows="2" 
                                      placeholder="Thông tin bổ sung về đơn hàng hoặc giao hàng"></textarea>
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
                    
                    <!-- Hidden fields for discount -->
                    <input type="hidden" name="discount_code" id="discount_code_input" value="">
                </form>
            </div>

            <div class="col-md-5 order-details">
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

                <div class="coupon-section mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Mã giảm giá" id="coupon-code">
                        <button class="btn btn-outline-primary" type="button" id="apply-coupon">Áp dụng</button>
                    </div>
                    <div class="mt-2 text-success d-none" id="coupon-success">
                        <i class="bi bi-check-circle-fill"></i> Mã giảm giá đã được áp dụng!
                    </div>
                    <div class="mt-2 text-danger d-none" id="coupon-error">
                        <i class="bi bi-exclamation-circle-fill"></i> Mã giảm giá không hợp lệ!
                    </div>
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
                    <div class="summary-item discount-row d-none">
                        <div class="summary-label">Giảm giá</div>
                        <div class="summary-value text-success" id="discount">-0₫</div>
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

    <!-- Order Success Modal -->
   <!-- Order Success Modal - Cập nhật lại nội dung modal để có thể hiển thị thông tin đơn hàng nếu cần -->
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
                    <a href="index.php?controller=product&action=index" class="btn btn-primary">
                        <i class="bi bi-shop me-1"></i> Tiếp tục mua sắm
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
</body>
</html>