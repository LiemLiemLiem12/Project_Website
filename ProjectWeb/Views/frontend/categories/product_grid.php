<?php
// Views/frontend/partials/product_grid.php
?>
<div id="products-container">
    <!-- Product Grid -->
    <div class="row product-grid">
        <?php if (empty($products)): ?>
            <div class="col-12 text-center py-5">
                <p>Không có sản phẩm nào phù hợp với tiêu chí lọc.</p>
                <button type="button" class="btn btn-dark reset-filters">Đặt lại bộ lọc</button>
            </div>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 col-6 mb-4">
                    <div class="product-card">
                        <div class="product-img-wrap">
                            <?php if ($product['discount_percent'] > 0): ?>
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
                                <?php if ($product['original_price'] > $product['current_price']): ?>
                                    <span class="original-price text-decoration-line-through"><?= number_format($product['original_price'], 0, ',', '.') ?>₫</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <nav aria-label="Product pagination" class="mt-4">
        <ul class="pagination justify-content-center">
           
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                    <a class="page-link <?= $i == $currentPage ? 'current-page' : '' ?>" href="#" data-page="<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
          
        </ul>
    </nav>
    <?php endif; ?>
</div>