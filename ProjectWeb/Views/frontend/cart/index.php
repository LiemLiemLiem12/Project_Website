
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Cart.css">
</head>

<body>
    <div class="cart-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Giỏ hàng</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="cart-container">
                    <div class="cart-title">
                        <span>Giỏ hàng:</span>
                        <span class="badge bg-dark" id="item-count">22 Sản phẩm</span>
                    </div>

                    <div class="cart-notification">
                        <i class="fas fa-tags me-2"></i> Đơn hàng của bạn đã đủ điều kiện giảm 50K nhờ nhập mã
                        <strong>SALE50K</strong>
                    </div>

                    <!-- Empty cart message - hidden by default -->
                    <div class="empty-cart-message d-none" id="empty-cart">
                        <i class="fas fa-shopping-cart"></i>
                        <h4>Giỏ hàng của bạn đang trống</h4>
                        <p>Hãy thêm sản phẩm vào giỏ hàng để mua sắm!</p>
                        <a href="index.php" class="btn btn-dark mt-3 ">Tiếp tục mua sắm</a>
                    </div>
                  <!-- <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td>
                            <img src="<?= $item['product']['main_image'] ?>" alt="" width="60">
                            <?= $item['product']['name'] ?>
                        </td>
                        <td><?= number_format($item['product']['current_price'], 0, ',', '.') ?>₫</td>
                        <td><?= $item['size'] ?></td>
                        <td>
                            <input type="number" value="<?= $item['quantity'] ?>" min="1" 
                                   data-item-key="<?= array_search($item, $cartItems) ?>"
                                   class="quantity-input">
                        </td>
                        <td><?= number_format($item['subtotal'], 0, ',', '.') ?>₫</td>
                        <td>
                            <a href="index.php?controller=cart&action=remove&item=<?= array_search($item, $cartItems) ?>" 
                               class="btn btn-danger btn-sm">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?> -->
                    <div class="cart-items" id="cart-items">
                        <!-- Cart Item 1 -->
                        <?php  foreach ($cartItems as $item): ?>
                      <div class="cart-item" data-id="<?= $item['product']['id_product'] ?>" data-unit-price="<?= $item['product']['current_price'] ?>" data-max-stock="<?= $item['product'][$item['size']] ?>">
                            <img src="/Project_Website/ProjectWeb/upload/img/All-Product/<?= $item['product']['main_image'] ?>" alt="Áo Thun Nam" class="cart-item-image">
                            <div class="cart-item-info">
                                <div class="cart-item-title"><?= $item['product']['name'] ?></div>
                                <div class="cart-item-variant"><?= $item['size'] ?></div>
                                <div class="cart-item-actions">
                                    <div class="quantity-control">
                                        <button id="addToOrderBtn"class="quantity-btn decrease-btn" productId="<?= $item['product']['id_product'] ?>"  productSize="<?= $item['size'] ?>">−</button>
                                        <input type="text" class="quantity-input" value="<?= $item['quantity'] ?>" min="1" max="100000000000000">
                                        <button id="addToOrderBtn" class="quantity-btn increase-btn" productId="<?= $item['product']['id_product'] ?>"  productSize="<?= $item['size'] ?>">+</button>
                                    </div>
                                </div>
                            </div>
                            <div class="cart-item-price"><?= number_format($item['product']['current_price'], 0, ',', '.') ?>đ</div>
                            <a href="index.php?controller=cart&action=delete& id=<?= $item['product']['id_product']?>">
                       <a href="index.php?controller=cart&action=delete&product_id=<?= $item['product']['id_product'] ?>&size=<?= $item['size'] ?>" class="btn btn-dark btn-sm ms-3">Xóa</a>

                        </div>
                          <?php endforeach; ?>

                        
                     
                    </div>
                </div>

                <div class="cart-container note-area">
                    <div class="mb-3">
                        
                     
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="order-summary">
                    <div class="summary-title">Tóm tắt đơn hàng</div>

                 
                    <div class="mt-4">
                        <div class="summary-row">
                            <span>Tạm tính:</span>
                            <span id="cart-subtotal">224,000₫</span>
                        </div>
                        <!-- <div class="summary-row">
                            <span>Phí vận chuyển:</span>
                            <span id="cart-shipping">30,000₫</span>
                        </div> -->
                        <div class="summary-row discount-row" style="display: none;">
                            <span>Giảm giá:</span>
                            <span id="cart-discount" class="text-success">-50,000₫</span>
                        </div>
                        <div class="summary-row mt-3">
                            <span>Tổng cộng:</span>
                            <span id="cart-total" class="summary-total">0₫</span>
                        </div>
                    </div>

                    <button  type="button" class=" checkout-btn" >THANH TOÁN</button>


                    <div class="mt-3">
                        <!-- <div class="text-center text-muted small mb-2">Miễn phí vận chuyển cho đơn hàng từ 200K</div> -->
                        <!-- <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="text-end text-muted small mt-1">Đủ điều kiện ✓</div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Project_Website/ProjectWeb/layout/js/Cart.js"></script>
</body>

