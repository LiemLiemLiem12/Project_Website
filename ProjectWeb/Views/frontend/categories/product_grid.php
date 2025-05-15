<?php
// Views/frontend/partials/product_grid.php
// File này chỉ chứa phần grid sản phẩm, không bao gồm tiêu đề hoặc bố cục trang
?>
<!-- Product Grid -->
<div class="row product-grid">
    <?php if(empty($products)): ?>
        <div class="col-12 text-center py-5">
            <p>Không có sản phẩm nào phù hợp với tiêu chí lọc.</p>
            <button type="button" class="btn btn-dark reset-filters">Đặt lại bộ lọc</button>
        </div>
    <?php else: ?>
        <?php foreach($products as $product): ?>
            <div class="col-md-4 col-6 mb-4">
                <div class="product-card">
                    <div class="product-img-wrap">
                        <?php if($product['discount_percent'] > 0): ?>
                            <span class="product-badge">-<?= $product['discount_percent'] ?>%</span>
                        <?php else: ?>
                            <span class="product-badge">Mới</span>
                        <?php endif; ?>
                        <a href="index.php?controller=product&action=show&id=<?= $product['id_product'] ?>">
                            <img src="/Project_Website/ProjectWeb/upload/img/All-Product/<?= $product['main_image'] ?>" class="card-img-top" alt="<?= $product['name'] ?>">
                        </a>
                        <button class="btn-quickview" data-product-id="<?= $product['id_product'] ?>">Xem nhanh</button>
                    </div>
                    <div class="product-info">
                        <h5 class="product-title">
                            <a class="text-dark text-decoration-none" href="index.php?controller=product&action=show&id=<?= $product['id_product'] ?>"><?= $product['name'] ?></a>
                        </h5>
                        <div class="product-price">
                            <span class="current-price text-danger fs-4"><?= number_format($product['current_price'], 0, ',', '.') ?>₫</span>
                            <?php if($product['original_price'] > $product['current_price']): ?>
                                <span class="original-price text-decoration-line-through"><?= number_format($product['original_price'], 0, ',', '.') ?>₫</span>
                            <?php endif; ?>
                        </div>
                        <button class="btn-add-cart" data-product-id="<?= $product['id_product'] ?>">Thêm vào giỏ hàng</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<!-- Hiển thị số lượng sản phẩm và phân trang nếu cần -->
<div class="product-count-info mt-3">
    <span>Hiển thị <?= count($products) ?> sản phẩm</span>
</div>

<?php if(count($products) > 12): ?>
<!-- Pagination -->
<nav aria-label="Product pagination" class="mt-4">
    <ul class="pagination justify-content-center">
        <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                <i class="fas fa-chevron-left"></i>
            </a>
        </li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item">
            <a class="page-link" href="#">
                <i class="fas fa-chevron-right"></i>
            </a>
        </li>
    </ul>
</nav>
<?php endif; ?>