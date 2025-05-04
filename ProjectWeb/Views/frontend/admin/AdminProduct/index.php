<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sản phẩm - SR Store</title>
    <!-- Bootstrap CSS -->
    <link href="/Project_Website/ProjectWeb/layout/cssBootstrap/bootstrap.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Admin.css">
</head>

<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="logo">
                <h2>SR STORE</h2>
            </div>
            <ul class="nav-links">
                <li>
                    <a href="index.php?controller=admindashboard"><i class="fas fa-th-large"></i> Dashboard</a>
                </li>
                <li class="active">
                    <a href="index.php?controller=adminproduct"><i class="fas fa-tshirt"></i> Sản Phẩm</a>
                </li>
                <li>
                    <a href="AdminOrder.html"><i class="fas fa-shopping-cart"></i> Đơn Hàng</a>
                </li>
                <li>
                    <a href="AdminCustomer.html"><i class="fas fa-users"></i> Khách Hàng</a>
                </li>
                <li>
                    <a href="AdminCategory.html"><i class="fas fa-tags"></i> Danh Mục</a>
                </li>
                <li>
                    <a href="AdminReport.html"><i class="fas fa-chart-bar"></i> Báo Cáo</a>
                </li>
                <li>
                    <a href="AdminSetting.html"><i class="fas fa-cog"></i> Cài Đặt</a>
                </li>
            </ul>
            <div class="admin-info">
                <img src="../upload/img/avatar.jpg" alt="Admin Avatar" class="profile-image">
                <div>
                    <p class="admin-name">Admin</p>
                    <a href="#" class="logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="product-management">
                <!-- Page Header -->
                <div class="page-header d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">Quản lý Sản phẩm</h1>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="fas fa-plus"></i> Thêm sản phẩm
                        </button>
                        <button class="btn btn-danger" id="deleteSelectedBtn" disabled>
                            <i class="fas fa-trash"></i> Xóa nhiều
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
                                <input type="text" placeholder="Tìm kiếm sản phẩm...">
                            </div>
                        </div>
                        <div class="filter-item">
                            <select class="filter-dropdown">
                                <option value="">Danh mục</option>
                                <option value="ao">Áo</option>
                                <option value="quan">Quần</option>
                                <option value="vay">Váy</option>
                                <option value="phu-kien">Phụ kiện</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <select class="filter-dropdown">
                                <option value="">Trạng thái</option>
                                <option value="con-hang">Còn hàng</option>
                                <option value="het-hang">Hết hàng</option>
                                <option value="ngung-ban">Ngừng bán</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <select class="filter-dropdown">
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
                    <table class="table">
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
                                    <tr class="product-row" data-id_product="' . $data['id_product'] . '" data-name="' . $data['product_name'] . '"
                                        data-description="' . $data['description'] . '"
                                        data-original_price="' . $data['original_price'] . '" data-discount_percent="' . $data['discount_percent'] . '" data-current_price="' . $data['current_price'] . '"
                                        data-created_at="' . $data['created_at'] . '" data-updated_at="' . $data['updated_at'] . '" data-id_category="' . $data['category_name'] . '"
                                        data-main_image="/Project_Website/ProjectWeb/upload/img/All-Product/' . $data['main_image'] . '"
                                        data-img="/Project_Website/ProjectWeb/upload/img/All-Product/' . $data['img2'] . ',/Project_Website/ProjectWeb/upload/img/All-Product/' . $data['img3'] . '"
                                        data-link="' . $data['link'] . '" data-meta="' . $data['meta'] . '" data-hide="' . $data['hide'] . '" data-order="' . $data['order'] . '"
                                        data-click_count="' . $data['click_count'] . '" data-tags="' . $data['tag'] . '"
                                        data-policy_return="/Project_Website/ProjectWeb/upload/img/DetailProduct/' . $data['CSDoiTra'] . '"
                                        data-policy_warranty="/Project_Website/ProjectWeb/upload/img/DetailProduct/' . $data['CSGiaoHang'] . '" data-stock="' . $data['store'] . '">
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
                                                <button class="btn btn-sm btn-info btn-view-order" title="Ẩn/Hiện" data-hide="0">
                                                    <i class="fas fa-eye"></i>
                                                </button>
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







    <!-- Modal Thêm Sản Phẩm -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="addProductForm">
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
                        </div>
                        <div class="mb-3">
                            <label for="productImage" class="form-label">Hình Ảnh</label>
                            <input type="file" class="form-control" id="productImage" name="productImage[]"
                                accept="image/*" multiple>
                            <div id="imagePreview" class="d-flex flex-wrap gap-2 mt-2"></div>
                        </div>
                        <div class="mb-3">
                            <label for="productName" class="form-label">Tên Sản Phẩm</label>
                            <input type="text" class="form-control" id="productName" name="productName" required>
                        </div>
                        <div class="mb-3">
                            <label for="productDesc" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="productDesc" name="productDesc" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="productCategory" class="form-label">Danh Mục</label>
                            <select class="form-select" id="productCategory" name="productCategory" required>
                                <option value="">Chọn danh mục</option>
                                <option value="1">Áo</option>
                                <option value="2">Quần</option>
                                <option value="3">Giày</option>
                                <option value="4">Phụ kiện</option>
                                <option value="5">Khuyến mãi</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Giá</label>
                            <input type="number" class="form-control" id="productPrice" name="productPrice" min="0"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="productStatus" class="form-label">Trạng Thái</label>
                            <select class="form-select" id="productStatus" name="productStatus" required>
                                <option value="0">Còn hàng</option>
                                <option value="1">Hết hàng</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="policyReturn" class="form-label">Chính sách đổi trả (ảnh)</label>
                            <input type="file" class="form-control" id="policyReturn" name="policyReturn"
                                accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="policyWarranty" class="form-label">Chính sách bảo hành (ảnh)</label>
                            <input type="file" class="form-control" id="policyWarranty" name="policyWarranty"
                                accept="image/*" required>
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
                            <p>
                                <strong>Giá:</strong>
                                <span id="detailOriginalPrice" style="text-decoration:line-through;color:gray"></span>
                                <span id="detailCurrentPrice" style="color:red;font-weight:bold"></span>
                                <span id="detailDiscount" class="badge bg-success"></span>
                            </p>
                            <p><strong>Tồn kho:</strong> <span id="detailStock"></span></p>
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
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('productDesc'); // 'productDesc' là id của textarea
    </script>
</body>

</html>