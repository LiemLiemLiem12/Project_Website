<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: ?controller=Adminlogin');
    exit;
}
require_once 'Controllers/FooterController.php';
$footerController = new FooterController();
$storeSettings = $footerController->getStoreSettings();
$faviconPath = !empty($storeSettings['favicon_path']) ? $storeSettings['favicon_path'] : '/Project_Website/ProjectWeb/upload/img/Header/favicon.ico';

?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sản phẩm </title>
    <!-- Favicon -->
    <link rel="icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="/Project_Website/ProjectWeb/layout/cssBootstrap/bootstrap.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/Views/frontend/admin/AdminProduct/style.css">
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Admin.css">
</head>

<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/Project_Website/ProjectWeb/Views/frontend/partitions/frontend/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Header -->
            <header class="header">
                <button class="sidebar-toggle" id="sidebarToggleBtn" aria-label="Mở menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <div class="header-right"
                    style="display: flex; align-items: center; gap: 1rem; margin-left: auto; position: relative;">
                    <div class="notification" id="notificationBell" style="position: relative; cursor: pointer;">
                    </div>
                    <div class="profile">
                        <img src="/Project_Website/ProjectWeb/upload/img/avatar.jpg" alt="Admin Avatar"
                            class="profile-image">
                    </div>
                </div>
            </header>

            <div class="product-management">
                <!-- Page Header -->
                <div class="page-header d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">Quản lý Sản phẩm</h1>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" id="btn-add-product" data-bs-toggle="modal"
                            data-bs-target="#addProductModal">
                            <i class="fas fa-plus"></i> Thêm sản phẩm
                        </button>
                        <button class="btn btn-danger" id="deleteSelectedBtn" disabled>
                            <i class="fas fa-trash"></i> Xóa nhiều
                        </button>
                        <!-- Add this button next to your "Add Product" button -->
                        <button class="btn btn-secondary" id="btn-trash" data-bs-toggle="modal"
                            data-bs-target="#trashModal">
                            <i class="fas fa-trash"></i> Thùng rác
                        </button>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="product-filters">
                    <div class="filter-row">
                        <div class="filter-item">
                            <div class="search-box">
                                <button type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                                <input id="searchInputProduct" type="text" placeholder="Tìm kiếm sản phẩm...">
                            </div>
                        </div>
                        <div class="filter-item">
                            <select class="filter-dropdown" id="filter-dropdown-product">
                                <option value="">Danh mục</option>
                                <?php
                                foreach ($categoryList as $category) {
                                    echo '<option value="' . $category['id_Category'] . '">' . $category['name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="filter-item">
                            <select class="filter-dropdown" id="filter-dropdown-status-product">
                                <option value="">Trạng thái</option>
                                <option value="con-hang">Còn hàng</option>
                                <option value="het-hang">Hết hàng</option>
                                <option value="ngung-ban">Ngừng bán</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <select class="filter-dropdown" id="filter-dropdown-sort-product">
                                <option value="">Sắp xếp theo</option>
                                <option value="moi-nhat">Mới nhất</option>
                                <option value="cu-nhat">Cũ nhất</option>
                                <option value="gia-tang">Giá tăng dần</option>
                                <option value="gia-giam">Giá giảm dần</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="product-table">
                    <div class="table-responsive-product">
                        <table id="table-product" class="table">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th>Mã SP</th>
                                    <th>Hình ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Danh mục</th>
                                    <th>Giá</th>
                                    <th>Tồn kho</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($productList as $data) {
                                    echo '
                                        <tr class="product-row" data-id_product="' . $data['id_product'] . '" 
                                            data-name="' . $data['product_name'] . '"
                                            data-description="' . base64_encode($data['description']) . '"
                                            data-original_price="' . $data['original_price'] . '"
                                            data-import_price="' . $data['import_price'] . '"
                                            data-discount_percent="' . $data['discount_percent'] . '"
                                            data-current_price="' . $data['current_price'] . '"
                                            data-created_at="' . $data['created_at'] . '"
                                            data-updated_at="' . $data['updated_at'] . '"
                                            data-id_category="' . $data['category_name'] . '"
                                            id_category="' . $data['id_Category'] . '"
                                            data-main_image="/Project_Website/ProjectWeb/upload/img/All-Product/' . $data['main_image'] . '"
                                            data-img="/Project_Website/ProjectWeb/upload/img/All-Product/' . $data['img2'] . ',/Project_Website/ProjectWeb/upload/img/All-Product/' . $data['img3'] . '"
                                            data-link="' . $data['link'] . '"
                                            data-meta="' . $data['meta'] . '"
                                            data-hide="' . $data['hide'] . '"
                                            data-order="' . $data['order'] . '"
                                            data-click_count="' . $data['click_count'] . '"
                                            data-tags="' . $data['tag'] . '"
                                            data-M-quantity="' . $data['M'] . '"
                                            data-L-quantity="' . $data['L'] . '"
                                            data-XL-quantity="' . $data['XL'] . '"
                                            data-policy_return="/Project_Website/ProjectWeb/upload/img/DetailProduct/' . $data['CSDoiTra'] . '"
                                            data-policy_warranty="/Project_Website/ProjectWeb/upload/img/DetailProduct/' . $data['CSGiaoHang'] . '"
                                            data-stock="' . $data['store'] . '">
                                            <td><input type="checkbox" class="product-checkbox"></td>
                                            <td>' . $data['id_product'] . '</td>
                                            <td><img src="/Project_Website/ProjectWeb/upload/img/All-Product/' . $data['main_image'] . '" alt="Product" width="50"></td>
                                            <td>' . $data['product_name'] . '</td>
                                            <td>' . $data['category_name'] . '</td>
                                            <td>' . $data['current_price'] . '</td>
                                            <td>' . $data['store'] . '</td>';

                                    if ((int) $data['store'] == 0) {
                                        echo '
                                                    <td><span class="status cancelled">Hết hàng</span></td>
                                                ';
                                    } else {
                                        echo '
                                                    <td><span class="status completed">Còn hàng</span></td>
                                                ';
                                    }


                                    echo '
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-sm btn-warning btn-edit" title="Sửa"><i
                                                            class="fas fa-edit"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                ';
                                }
                                ?>
                                <!-- Thêm các sản phẩm khác tương tự -->
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>
    </div>




    <!-- Modal Thùng Rác -->
    <div class="modal fade" id="trashModal" tabindex="-1" aria-labelledby="trashModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="trashModalLabel">Thùng Rác</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="trash-table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all-trash" class="form-check-input"></th>
                                    <th>Mã SP</th>
                                    <th>Hình ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Danh mục</th>
                                    <th>Giá</th>
                                    <th>Tồn kho</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="trash-table-body">
                                <!-- Trash items will be loaded here via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    <div id="trash-empty-message" class="text-center py-4 d-none">
                        <i class="fas fa-trash fa-3x mb-3 text-muted"></i>
                        <p>Thùng rác trống</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-success" id="restoreSelectedBtn" disabled>
                        <i class="fas fa-trash-restore"></i> Khôi phục đã chọn
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Thêm Sản Phẩm -->
    <div class="modal fade" id="addProductModal" aria-labelledby="addProductModalLabel" aria-hidden="true"
        data-bs-focus="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form class="need-validation" id="addProductForm" novalidate>
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Thêm Sản Phẩm Mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="productTags" class="form-label">Tags</label>
                            <div id="tagInputContainer" class="form-control d-flex flex-wrap align-items-center"
                                style="min-height: 48px;">
                                <input type="text" id="tagInput" class="border-0 flex-grow-1"
                                    placeholder="Nhập tag và nhấn Enter..." style="outline: none; min-width: 120px;">

                            </div>
                            <input type="hidden" name="productTags" id="productTags">
                            <div class="invalid-feedback">Vui lòng nhập tag cho sản phẩm</div>
                        </div>
                        <div class="mb-3">
                            <label for="productImage" class="form-label">Hình Ảnh</label>
                            <input type="file" class="form-control" id="productImage" name="productImage[]"
                                accept="image/*" multiple required>
                            <div id="imagePreview" class="d-flex flex-wrap gap-2 mt-2"></div>
                            <div class="invalid-feedback">Vui lòng chọn đủ 3 ảnh</div>
                        </div>
                        <div class="mb-3">
                            <label for="productName" class="form-label">Tên Sản Phẩm</label>
                            <input type="text" class="form-control" id="productName" name="productName" required
                                pattern="^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểẾỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸỳỵỷỹ\s]+$"
                                maxlength="1000" minlength="4">
                            <div class="invalid-feedback">Vui lòng nhập tên sản phẩm hợp lệ (không có số).</div>
                        </div>
                        <div class="mb-3">
                            <label for="productDesc" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="productDesc" name="productDesc" rows="2"
                                aria-hidden="false"></textarea>
                            <small id="wordCount" class="form-text text-muted">0/2000 từ</small>
                            <div id="productDescError" class="text-danger small mt-1 d-none">Mô tả phải từ 10 đến 1000
                                ký tự.</div>
                        </div>
                        <div class="mb-3">
                            <label for="productCategory" class="form-label">Danh Mục</label>
                            <select class="form-select" id="productCategory" name="productCategory" required>
                                <option value="">Chọn danh mục</option>
                                <?php
                                foreach ($categoryList as $data) {
                                    echo '
                                            <option value="' . $data['id_Category'] . '">' . $data['name'] . '</option>
                                        ';
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback">Vui lòng chọn danh mục</div>
                        </div>
                        <div class="mb-3">
                            <!-- Trong Modal thêm/sửa sản phẩm -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label>Giá nhập</label>
                                        <input type="number" min="0" step="1000" 
                                            class="form-control" name="importPrice" required
                                            value="<?= isset($product) ? $product['import_price'] : '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label>Giá bán</label>
                                        <input type="number" min="0" step="1000" 
                                            class="form-control" name="productPrice" required
                                            value="<?= isset($product) ? $product['original_price'] : '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label>Giảm giá (%)</label>
                                        <input type="number" min="0" max="100" 
                                            class="form-control" name="discountPercent"
                                            value="<?= isset($product) ? $product['discount_percent'] : '0' ?>">
                                    </div>
                                </div>
                                <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                        const priceInput = document.querySelector('input[name="productPrice"]');
                                        const discountInput = document.querySelector('input[name="discountPercent"]');
                                        const finalPriceDisplay = document.getElementById('finalPrice'); // Thêm element này vào modal

                                        const calculateFinalPrice = () => {
                                            const price = parseInt(priceInput.value) || 0;
                                            const discount = parseInt(discountInput.value) || 0;
                                            const finalPrice = price * (1 - (discount / 100));
                                            finalPriceDisplay.textContent = new Intl.NumberFormat('vi-VN', {
                                                style: 'currency',
                                                currency: 'VND'
                                            }).format(finalPrice);
                                        };

                                        priceInput?.addEventListener('input', calculateFinalPrice);
                                        discountInput?.addEventListener('input', calculateFinalPrice);
                                    });
                                </script>
                            </div>
                            <!-- <label for="productPrice" class="form-label">Giá</label>
                            <input type="number" class="form-control" id="productPrice" name="productPrice" min="0"
                                max="10000000" required>
                            <div class="invalid-feedback">Vui lòng nhập giá hợp lệ (0<< 10.000.000đ)</div> -->
                        </div>
                            <div class="mb-3">
                                <label for="productStock" class="form-label">Tồn Kho</label>
                                <input type="number" class="form-control mb-3" id="productStock" name="productStock"
                                    min="0" required>
                                <div class="invalid-feedback mt-3">Vui lòng nhập số lượng tồn kho hợp lệ</div>

                                <div class="d-flex flex-column ms-0 w-25">
                                    <!-- Size M -->
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="w-50 d-flex align-items-center">
                                            <input type="checkbox" class="form-check-input me-2" id="Size_M"
                                                name="Size_M">
                                            <label for="Size_M" class="form-label mb-0">Size M</label>
                                        </div>
                                        <div class="w-50">
                                            <input type="number" class="form-control" id="Size_M_quantity"
                                                name="Size_M_quantity" min="0" max="10000000" disabled>
                                        </div>
                                    </div>

                                    <!-- Size L -->
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="w-50 d-flex align-items-center">
                                            <input type="checkbox" class="form-check-input me-2" id="Size_L"
                                                name="Size_L">
                                            <label for="Size_L" class="form-label mb-0">Size L</label>
                                        </div>
                                        <div class="w-50">
                                            <input type="number" class="form-control" id="Size_L_quantity"
                                                name="Size_L_quantity" min="0" max="10000000" disabled>
                                        </div>
                                    </div>

                                    <!-- Size XL -->
                                    <div class="d-flex align-items-center">
                                        <div class="w-50 d-flex align-items-center">
                                            <input type="checkbox" class="form-check-input me-2" id="Size_XL"
                                                name="Size_XL">
                                            <label for="Size_XL" class="form-label mb-0">Size XL</label>
                                        </div>
                                        <div class="w-50">
                                            <input type="number" class="form-control" id="Size_XL_quantity"
                                                name="Size_XL_quantity" min="0" max="10000000" disabled>
                                        </div>
                                    </div>

                                    <input type="text" id="size_total_validator" hidden>
                                    <div class="invalid-feedback"></div>
                                </div>




                            </div>

                            <div class="mb-3">
                                <label for="policyReturn" class="form-label">Chính sách đổi trả (ảnh)</label>
                                <input type="file" class="form-control" id="policyReturn" name="policyReturn"
                                    accept="image/*" required>
                                <div class="invalid-feedback">Vui lòng nhập ảnh hợp lệ</div>
                            </div>
                            <div class="mb-3">
                                <label for="policyWarranty" class="form-label">Chính sách bảo hành (ảnh)</label>
                                <input type="file" class="form-control" id="policyWarranty" name="policyWarranty"
                                    accept="image/*" required>
                                <div class="invalid-feedback">Vui lòng nhập ảnh hợp lệ</div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Sửa Sản Phẩm -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form class="need-validation" id="addProductForm" novalidate>
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Sửa Sản Phẩm Mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="productTags" class="form-label">Tags</label>
                            <div id="tagInputContainer" class="form-control d-flex flex-wrap align-items-center"
                                style="min-height: 48px;">
                                <input type="text" id="tagInput" class="border-0 flex-grow-1"
                                    placeholder="Nhập tag và nhấn Enter..." style="outline: none; min-width: 120px;">

                            </div>
                            <input type="hidden" name="productTags" id="productTags">
                            <div class="invalid-feedback">Vui lòng nhập tag cho sản phẩm</div>
                        </div>
                        <div class="mb-3">
                            <label for="productImage" class="form-label">Hình Ảnh</label>
                            <input type="file" class="form-control" id="productImage" name="productImage[]"
                                accept="image/*" multiple required>
                            <div id="imagePreview" class="d-flex flex-wrap gap-2 mt-2"></div>
                            <div class="invalid-feedback">Vui lòng nhập ảnh hợp lệ</div>
                        </div>
                        <div class="mb-3">
                            <label for="productName" class="form-label">Tên Sản Phẩm</label>
                            <input type="text" class="form-control" id="productName" name="productName" required
                                pattern="[A-Za-zÀ-ÿ\s]+" maxlength="1000" minlength="4">
                            <div class="invalid-feedback">Vui lòng nhập tên sản phẩm hợp lệ (không có số).</div>
                        </div>
                        <div class="mb-3">
                            <label for="productDesc" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="productDesc" name="productDesc" rows="2"></textarea>
                            <small id="wordCount" class="form-text text-muted">0/2000 từ</small>
                            <div id="productDescError" class="text-danger small mt-1 d-none">Mô tả phải từ 10 đến 1000
                                ký tự.</div>
                        </div>
                        <div class="mb-3">
                            <label for="productCategory" class="form-label">Danh Mục</label>
                            <select class="form-select" id="productCategory" name="productCategory" required>
                                <option value="">Chọn danh mục</option>
                                <?php
                                foreach ($categoryList as $data) {
                                    echo '
                                            <option value="' . $data['id_Category'] . '">' . $data['name'] . '</option>
                                        ';
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback">Vui lòng chọn danh mục</div>
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Giá</label>
                            <input type="number" class="form-control" id="productPrice" name="productPrice" min="0"
                                max="10000000" required>
                            <div class="invalid-feedback">Vui lòng nhập giá hợp lệ (0<< 10.000.000đ)</div>
                            </div>
                            <div class="mb-3">
                                <label for="productStock" class="form-label">Tồn Kho</label>
                                <input type="number" class="form-control" id="productStock" name="productStock" min="0"
                                    required>
                                <div class="invalid-feedback">Vui lòng nhập số lượng tồn kho hợp lệ (0<<
                                        10.000.000đ)</div>
                                </div>
                                <div class="mb-3">
                                    <label for="policyReturn" class="form-label">Chính sách đổi trả (ảnh)</label>
                                    <input type="file" class="form-control" id="policyReturn" name="policyReturn"
                                        accept="image/*" required>
                                    <div class="invalid-feedback">Vui lòng nhập ảnh hợp lệ</div>
                                </div>
                                <div class="mb-3">
                                    <label for="policyWarranty" class="form-label">Chính sách bảo hành (ảnh)</label>
                                    <input type="file" class="form-control" id="policyWarranty" name="policyWarranty"
                                        accept="image/*" required>
                                    <div class="invalid-feedback">Vui lòng nhập ảnh hợp lệ</div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
                            </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Chi Tiết Sản Phẩm -->
    <div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productDetailModalLabel">Chi tiết sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <img id="detailImage" src="" alt="Ảnh chính" class="img-fluid rounded border mb-2"
                                style="max-height:220px;">
                            <div id="detailGallery" class="d-flex gap-2"></div>
                        </div>
                        <div class="col-md-7">
                            <h4 id="detailName"></h4>
                            <p><strong>Mã SP:</strong> <span id="detailCode"></span></p>
                            <p><strong>Danh mục:</strong> <span id="detailCategory"></span></p>
                            <p><strong>Giá nhập:</strong> <span id="detailImportPrice"></span></p>
                            <p>
                                <strong>Giá bán:</strong>
                                <span id="detailOriginalPrice" style="text-decoration:line-through;color:gray"></span>
                                <span id="detailCurrentPrice" style="color:red;font-weight:bold"></span>
                                <span id="detailDiscount" class="badge bg-success"></span>
                            </p>
                            <p><strong>Tồn kho:</strong> <span id="detailStock"></span></p>
                            <p><strong>Size M:</strong> <span id="M_quantity"></span></p>
                            <p><strong>Size L:</strong> <span id="L_quantity"></span></p>
                            <p><strong>Size XL:</strong> <span id="XL_quantity"></span></p>
                            <p><strong>Trạng thái:</strong> <span id="detailStatus"></span></p>
                            <p><strong>Lượt xem:</strong> <span id="detailClick"></span></p>
                            <p><strong>Ngày tạo:</strong> <span id="detailCreated"></span></p>
                            <p><strong>Ngày cập nhật:</strong> <span id="detailUpdated"></span></p>
                            <p><strong>Link SEO:</strong> <span id="detailLink"></span></p>
                            <p><strong>Meta SEO:</strong> <span id="detailMeta"></span></p>
                            <p><strong>Thứ tự hiển thị:</strong> <span id="detailOrder"></span></p>
                            <p><strong>Tags:</strong> <span id="detailTags"></span></p>
                            <p><strong>Chính sách đổi trả:</strong><br><img id="detailPolicyReturn" src=""
                                    style="max-width:100px"></p>
                            <p><strong>Chính sách bảo hành:</strong><br><img id="detailPolicyWarranty" src=""
                                    style="max-width:100px"></p>
                            <p><strong>Mô tả:</strong></p>
                            <div id="detailDesc" class="border rounded p-2 bg-light"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="/Project_Website/ProjectWeb/layout/jsBootstrap/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom JS -->
    <script src="/Project_Website/ProjectWeb/layout/js/Admin.js"></script>
    
    <!-- Ckeditor -->
    <script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            // Lấy phần tử sidebar và nút toggle
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebarToggleBtn');

            console.log('Toggle button:', toggleBtn);
            console.log('Sidebar:', sidebar);

            // Gắn sự kiện click cho nút toggle
            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function () {
                    console.log('Toggle button clicked');
                    sidebar.classList.toggle('show');
                });
            }
        });
        // CKEDITOR.replace('productDesc'); // 'productDesc' là id của textarea
    </script>

    <script>
        // Trash bin functionality
        document.addEventListener('DOMContentLoaded', function () {
            const trashBtn = document.getElementById('btn-trash');
            const trashTableBody = document.getElementById('trash-table-body');
            const trashEmptyMessage = document.getElementById('trash-empty-message');
            const selectAllTrashCheckbox = document.getElementById('select-all-trash');
            const restoreSelectedBtn = document.getElementById('restoreSelectedBtn');

            console.log('Trash button:', trashBtn);

            // Load trash items when trash button is clicked
            if (trashBtn) {
                trashBtn.addEventListener('click', function () {
                    console.log('Trash button clicked');
                    loadTrashItems();
                });
            }

            // Function to load trash items
            function loadTrashItems() {
                console.log('Loading trash items...');
                trashTableBody.innerHTML = '<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Đang tải dữ liệu...</td></tr>';

                // Thêm tham số timestamp để tránh cache
                fetch('/Project_Website/ProjectWeb/index.php?controller=adminproduct&action=getTrashItems&_=' + new Date().getTime())
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.text(); // Lấy text trước để debug
                    })
                    .then(text => {
                        console.log('Raw response:', text.substring(0, 200)); // Hiển thị 200 ký tự đầu tiên để debug

                        try {
                            // Xử lý trường hợp text rỗng
                            if (!text || text.trim() === '') {
                                throw new Error('Server trả về dữ liệu rỗng');
                            }

                            // Loại bỏ các ký tự HTML nếu có
                            let cleanText = text;
                            if (text.includes('<br') || text.includes('<b>')) {
                                // Nếu có lỗi PHP HTML, hiển thị thông báo lỗi
                                throw new Error('Server trả về lỗi PHP thay vì JSON');
                            }

                            // Chuyển text thành JSON
                            const data = JSON.parse(cleanText);
                            console.log('Parsed JSON data:', data);

                            trashTableBody.innerHTML = '';

                            if (data.error) {
                                console.error('Server returned error:', data.error);
                                trashTableBody.innerHTML = `<tr><td colspan="8" class="text-center text-danger">${data.error}</td></tr>`;
                                return;
                            }

                            if (!data || data.length === 0) {
                                console.log('No trash items found');
                                trashEmptyMessage.classList.remove('d-none');
                                trashTableBody.innerHTML = '<tr><td colspan="8" class="text-center">Thùng rác trống</td></tr>';
                                if (restoreSelectedBtn) restoreSelectedBtn.disabled = true;
                            } else {
                                trashEmptyMessage.classList.add('d-none');

                                data.forEach(item => {
                                    console.log('Creating row for item:', item);
                                    const row = document.createElement('tr');
                                    row.innerHTML = `
                                    <td><input type="checkbox" class="form-check-input trash-checkbox" data-id="${item.id_product}"></td>
                                    <td>${item.id_product}</td>
                                    <td><img src="/Project_Website/ProjectWeb/upload/img/All-Product/${item.main_image}" alt="Product" width="50"></td>
                                    <td>${item.product_name || item.name}</td>
                                    <td>${item.category_name}</td>
                                    <td>${item.current_price}</td>
                                    <td>${item.store}</td>
                                    <td>
                                        <button class="btn btn-sm btn-success btn-restore-single" data-id="${item.id_product}">
                                            <i class="fas fa-trash-restore"></i>
                                        </button>
                                    </td>
                                `;
                                    trashTableBody.appendChild(row);
                                });

                                // Add event listeners to single restore buttons
                                document.querySelectorAll('.btn-restore-single').forEach(btn => {
                                    btn.addEventListener('click', function () {
                                        const productId = this.getAttribute('data-id');
                                        console.log('Restoring product ID:', productId);
                                        restoreProduct([productId]);
                                    });
                                });

                                // Add event listeners to checkboxes
                                attachTrashCheckboxEvents();
                            }
                        } catch (parseError) {
                            console.error('Error parsing JSON:', parseError);
                            trashTableBody.innerHTML = `
                            <tr><td colspan="8" class="text-center text-danger">
                                Lỗi phân tích dữ liệu: ${parseError.message}<br>
                                <small>Kiểm tra console để biết thêm chi tiết.</small>
                            </td></tr>
                        `;
                            if (restoreSelectedBtn) restoreSelectedBtn.disabled = true;
                        }
                    })
                    .catch(error => {
                        console.error('Error loading trash items:', error);
                        trashTableBody.innerHTML = `
                        <tr><td colspan="8" class="text-center text-danger">
                            Lỗi kết nối: ${error.message}<br>
                            <small>Kiểm tra console để biết thêm chi tiết.</small>
                        </td></tr>
                    `;
                        if (restoreSelectedBtn) restoreSelectedBtn.disabled = true;
                    });
            }

            // Function to attach checkbox events
            function attachTrashCheckboxEvents() {
                // Select all checkbox
                if (selectAllTrashCheckbox) {
                    selectAllTrashCheckbox.addEventListener('change', function () {
                        const checkboxes = document.querySelectorAll('.trash-checkbox');
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = this.checked;
                        });
                        updateRestoreButtonState();
                    });
                }

                // Individual checkboxes
                document.querySelectorAll('.trash-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        updateRestoreButtonState();

                        // Update "select all" checkbox state
                        if (selectAllTrashCheckbox) {
                            const totalCheckboxes = document.querySelectorAll('.trash-checkbox').length;
                            const checkedCheckboxes = document.querySelectorAll('.trash-checkbox:checked').length;
                            selectAllTrashCheckbox.checked = totalCheckboxes === checkedCheckboxes && totalCheckboxes > 0;
                        }
                    });
                });
            }

            // Function to update restore button state
            function updateRestoreButtonState() {
                if (restoreSelectedBtn) {
                    const selectedCheckboxes = document.querySelectorAll('.trash-checkbox:checked');
                    restoreSelectedBtn.disabled = selectedCheckboxes.length === 0;
                }
            }

            // Restore selected button click event
            if (restoreSelectedBtn) {
                restoreSelectedBtn.addEventListener('click', function () {
                    const selectedIds = Array.from(document.querySelectorAll('.trash-checkbox:checked')).map(
                        checkbox => checkbox.getAttribute('data-id')
                    );

                    if (selectedIds.length > 0) {
                        restoreProduct(selectedIds);
                    }
                });
            }

        // Function to restore product(s)
        function restoreProduct(productIds) {
            console.log('Restoring products:', productIds);
            
            // Create a chain of promises to restore products one by one
            let promiseChain = Promise.resolve();
            let successCount = 0;
            let failCount = 0;
            
            productIds.forEach(productId => {
                promiseChain = promiseChain.then(() => {
                    return fetch(`/Project_Website/ProjectWeb/index.php?controller=adminproduct&action=restoreProduct&id=${productId}&_=${new Date().getTime()}`)
                        .then(response => response.text())
                        .then(text => {
                            console.log('Raw restore response:', text.substring(0, 200));
                            
                            try {
                                // Xử lý trường hợp text rỗng
                                if (!text || text.trim() === '') {
                                    throw new Error('Server trả về dữ liệu rỗng');
                                }
                                
                                // Loại bỏ các ký tự HTML nếu có
                                let cleanText = text;
                                if (text.includes('<br') || text.includes('<b>')) {
                                    throw new Error('Server trả về lỗi PHP thay vì JSON');
                                }
                                
                                // Parse JSON
                                const data = JSON.parse(cleanText);
                                console.log('Restore response for ID ' + productId + ':', data);
                                
                                if (data.success) {
                                    // Remove the row from trash table
                                    const checkbox = document.querySelector(`.trash-checkbox[data-id="${productId}"]`);
                                    if (checkbox) {
                                        const row = checkbox.closest('tr');
                                        if (row) row.remove();
                                    }
                                    successCount++;
                                } else {
                                    failCount++;
                                    console.error('Failed to restore product ID ' + productId + ': ', data.error || 'Unknown error');
                                }
                            } catch (parseError) {
                                failCount++;
                                console.error('Error parsing restore response:', parseError);
                            }
                        })
                        .catch(error => {
                            failCount++;
                            console.error('Network error restoring product ID ' + productId + ': ', error);
                        });
                });
            });
            
            // After all products are processed
            promiseChain.then(() => {
                // Check if trash is empty
                if (trashTableBody.children.length === 0 || document.querySelectorAll('.trash-checkbox').length === 0) {
                    trashEmptyMessage.classList.remove('d-none');
                    trashTableBody.innerHTML = '<tr><td colspan="8" class="text-center">Thùng rác trống</td></tr>';
                }
                
                // Update select all checkbox
                if (selectAllTrashCheckbox) {
                    selectAllTrashCheckbox.checked = false;
                }
                
                // Disable restore button
                if (restoreSelectedBtn) {
                    restoreSelectedBtn.disabled = true;
                }
                
                // Show result message
                let message = '';
                if (successCount > 0) {
                    message += `Đã khôi phục thành công ${successCount} sản phẩm. `;
                }
                if (failCount > 0) {
                    message += `Có ${failCount} sản phẩm không thể khôi phục.`;
                }
                
                if (message) {
                    showSuccessAlert(message);
                }
                
                // Reload main product table
                location.reload();
            });
        }
    });
    </script>
    <script>
// Xử lý lọc theo danh mục
document.addEventListener('DOMContentLoaded', function() {
    // Lấy dropdown danh mục
    const categoryDropdown = document.getElementById('filter-dropdown-product');
    
    // Thêm event listener cho dropdown danh mục
    if (categoryDropdown) {
        categoryDropdown.addEventListener('change', function() {
            filterByCategory();
        });
    }
    
    // Hàm lọc theo danh mục
    function filterByCategory() {
        const categoryId = categoryDropdown.value;
        
        // Hiển thị loading
        document.querySelector('.table-responsive-product').innerHTML = '<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-3x"></i><p class="mt-3">Đang tải dữ liệu...</p></div>';
        
        // Gửi request AJAX
        const formData = new FormData();
        formData.append('category_id', categoryId);
        
        fetch('/Project_Website/ProjectWeb/index.php?controller=adminproduct&action=filterCategory', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            // Cập nhật bảng sản phẩm với kết quả
            document.querySelector('.table-responsive-product').innerHTML = html;
            
            // Khởi tạo lại các event listeners
            reinitializeTableEvents();
        })
        .catch(error => {
            console.error('Lỗi khi lọc sản phẩm:', error);
            // Hiển thị thông báo lỗi
            document.querySelector('.table-responsive-product').innerHTML = '<div class="alert alert-danger">Đã xảy ra lỗi khi lọc sản phẩm. Vui lòng thử lại sau.</div>';
        });
    }
    
    // Hàm khởi tạo lại các event listeners cho bảng
    function reinitializeTableEvents() {
        // Khởi tạo lại checkbox "Chọn tất cả"
        const selectAll = document.getElementById('select-all');
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.product-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateDeleteButtonState();
            });
        }
        
        // Khởi tạo lại các checkbox sản phẩm
        document.querySelectorAll('.product-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateDeleteButtonState();
            });
        });
        
        // Khởi tạo lại các nút sửa
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
               const row = this.closest('.product-row');
                const productData = {
                    id_product: row.getAttribute('data-id_product'),
                    name: row.getAttribute('data-name'),
                    description: atob(row.getAttribute('data-description')),
                    original_price: row.getAttribute('data-original_price'),
                    import_price: row.getAttribute('data-import_price'),
                    discount_percent: row.getAttribute('data-discount_percent'),
                    current_price: row.getAttribute('data-current_price'),
                    store: row.getAttribute('data-stock'),
                    category_id: row.getAttribute('id_category'),
                    tags: row.getAttribute('data-tags'),
                    M_quantity: row.getAttribute('data-M-quantity'),
                    L_quantity: row.getAttribute('data-L-quantity'),
                    XL_quantity: row.getAttribute('data-XL-quantity')
                };
                
         
                
                editProduct(productData);
            });
        });
    }
    
    // Hàm cập nhật trạng thái nút xóa
    function updateDeleteButtonState() {
        const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
        if (deleteSelectedBtn) {
            const selectedCheckboxes = document.querySelectorAll('.product-checkbox:checked');
            deleteSelectedBtn.disabled = selectedCheckboxes.length === 0;
        }
    }
    
    // Hàm hiển thị modal sửa sản phẩm (giữ lại logic hiện có)
    function showEditProductModal(row) {
        // Lấy dữ liệu từ hàng được chọn
        const productId = row.getAttribute('data-id_product');
        const productName = row.getAttribute('data-name');
        const productDesc = atob(row.getAttribute('data-description')); // Giải mã Base64
        const productCategory = row.getAttribute('id_category');
        const productPrice = row.getAttribute('data-original_price');
        const productImportPrice = row.getAttribute('data-import_price');
        const productStock = row.getAttribute('data-stock');
        const productImage = row.getAttribute('data-main_image');
        const productTags = row.getAttribute('data-tags');
        
        // Logic hiển thị modal sửa sản phẩm (giữ nguyên)
        // ...
    }
});
</script>
    <!-- Sweet Alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom SweetAlert Config -->
    <script src="/Project_Website/ProjectWeb/layout/js/sweetalert-config.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lấy các phần tử cần thiết
        const selectAllCheckbox = document.getElementById('select-all');
        const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');

        // Hàm cập nhật trạng thái nút xóa
        function updateDeleteButtonState() {
            if (deleteSelectedBtn) {
                const selectedCheckboxes = document.querySelectorAll('.product-checkbox:checked');
                deleteSelectedBtn.disabled = selectedCheckboxes.length === 0;
            }
        }

        // Xử lý sự kiện cho checkbox "Chọn tất cả"
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.product-checkbox');
                // Bỏ chọn tất cả các checkbox hiện tại trước
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                // Sau đó mới áp dụng trạng thái mới
                if (this.checked) {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = true;
                    });
                }
                updateDeleteButtonState();
            });
        }

        // Xử lý sự kiện cho từng checkbox sản phẩm
        document.addEventListener('change', function(event) {
            if (event.target.classList.contains('product-checkbox')) {
                // Cập nhật trạng thái của checkbox "Chọn tất cả"
                if (selectAllCheckbox) {
                    const totalCheckboxes = document.querySelectorAll('.product-checkbox').length;
                    const checkedCheckboxes = document.querySelectorAll('.product-checkbox:checked').length;
                    selectAllCheckbox.checked = totalCheckboxes === checkedCheckboxes && totalCheckboxes > 0;
                }
                updateDeleteButtonState();
            }
        });

        // Khởi tạo trạng thái ban đầu
        updateDeleteButtonState();
    });
    </script>

    <!-- Thêm script để xử lý load dữ liệu khi sửa -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lưu trữ nút submit gốc
        const originalSubmitText = document.querySelector('#addProductForm button[type="submit"]').textContent;
        
        // Xử lý khi nhấn nút sửa
        window.editProduct = function(productData) {
            const modal = new bootstrap.Modal(document.getElementById('addProductModal'));
            modal.show();
            
            // Đổi text nút submit
            document.querySelector('#addProductForm button[type="submit"]').textContent = 'Cập nhật sản phẩm';
            
            // Load các giá trị vào form
            document.querySelector('input[name="importPrice"]').value = 
                parseFloat(productData.import_price.replace(/[^\d]/g, ''));
            document.querySelector('input[name="productPrice"]').value = 
                parseFloat(productData.original_price.replace(/[^\d]/g, ''));
            document.querySelector('input[name="discountPercent"]').value = 
                productData.discount_percent || 0;
            
            // Thêm ID sản phẩm vào form
            if (!document.querySelector('input[name="productID"]')) {
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'productID';
                idInput.value = productData.id_product;
                document.getElementById('addProductForm').appendChild(idInput);
            } else {
                document.querySelector('input[name="productID"]').value = productData.id_product;
            }
            
            // Tính và hiển thị giá cuối cùng
            const finalPrice = document.getElementById('finalPrice');
            if (finalPrice) {
                const price = parseFloat(productData.original_price.replace(/[^\d]/g, ''));
                const discount = productData.discount_percent || 0;
                const finalPriceValue = price * (1 - (discount / 100));
                finalPrice.textContent = new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(finalPriceValue);
            }
        }
        
        // Reset form khi đóng modal
        document.getElementById('addProductModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('addProductForm').reset();
            document.querySelector('#addProductForm button[type="submit"]').textContent = originalSubmitText;
            const idInput = document.querySelector('input[name="productID"]');
            if (idInput) idInput.remove();
        });
    });
    </script>

    <!-- Thêm div hiển thị giá cuối cùng -->
    <div class="row mt-2">
        <div class="col-12">
            <strong>Giá sau giảm:</strong> 
            <span id="finalPrice" class="text-danger fw-bold"></span>
        </div>
    </div>
</body>

</html>