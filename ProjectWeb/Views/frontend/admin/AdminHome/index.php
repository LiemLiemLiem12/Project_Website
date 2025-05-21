<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: ?controller=Adminlogin');
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Trang chủ - SR Store</title>
    <!-- Bootstrap CSS -->
    <link href="/Project_Website/ProjectWeb/layout/cssBootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <!-- Cropper.js CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Admin.css">
    <style>
        .section-card,
        .banner-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .section-header,
        .banner-header {
            padding: 15px;
            border-bottom: 1px solid #eee;
            background-color: #f8f9fa;
            border-radius: 10px 10px 0 0;
        }

        .section-body,
        .banner-body {
            padding: 15px;
        }

        .section-footer,
        .banner-footer {
            padding: 15px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }

        .banner-preview {
            max-width: 100%;
            max-height: 200px;
            object-fit: contain;
            display: block;
            margin: 0 auto 10px auto;
        }

        .section-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .sortable-placeholder {
            border: 1px dashed #ccc;
            background-color: #f9f9f9;
            height: 50px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .item-handle {
            cursor: move;
            padding: 5px;
            margin-right: 10px;
            color: #999;
        }

        .item-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 10px;
        }

        .status-badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-active {
            background-color: #d4f5e9;
            color: #2e7d32;
        }

        .status-inactive {
            background-color: #ffe0e0;
            color: #c62828;
        }

        .date-badge {
            background-color: #e3f2fd;
            color: #0d47a1;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 500;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s, opacity 0.3s;
        }

        .loading-overlay.show {
            visibility: visible;
            opacity: 1;
        }

        #itemsList {
            max-height: 400px;
            overflow-y: auto;
        }

        .section-type-badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 500;
            background-color: #f0f0f0;
            margin-left: 10px;
        }

        .section-type-product {
            background-color: #fff8e1;
            color: #ff8f00;
        }

        .section-type-category {
            background-color: #e8eaf6;
            color: #3f51b5;
        }

        .tab-pane {
            padding: 20px 0;
        }

        /* Thêm vào phần style của file */
        .section-card:hover,
        .banner-card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .item-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 10px;
            border-radius: 4px;
            border: 1px solid #eee;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f8f9fa;
            font-size: 24px;
            color: #6c757d;
        }

        .section-type-product {
            background-color: #fff8e1;
            color: #ff8f00;
        }

        .section-type-category {
            background-color: #e8eaf6;
            color: #3f51b5;
        }

        #itemsContainer {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 5px;
        }

        #itemsContainer::-webkit-scrollbar {
            width: 6px;
        }

        #itemsContainer::-webkit-scrollbar-thumb {
            background-color: #ced4da;
            border-radius: 3px;
        }

        #itemsContainer::-webkit-scrollbar-track {
            background-color: #f8f9fa;
        }

        .btn-delete-item {
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s, opacity 0.2s linear;
        }

        .section-item:hover .btn-delete-item {
            visibility: visible;
            opacity: 1;
        }

        /* Alert animations */
        .alert {
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive fixes */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .page-header .btn {
                margin-top: 10px;
            }

            .section-footer,
            .banner-footer {
                flex-direction: column;
                gap: 10px;
            }

            .section-footer .btn-group,
            .banner-footer .btn-group {
                width: 100%;
            }
        }

        /* Highlight newly added items */
        .highlight-new {
            animation: highlightNew 2s;
        }

        @keyframes highlightNew {
            0% {
                background-color: #e8f5e9;
            }

            100% {
                background-color: transparent;
            }
        }

        /* Cropper styles */
        .img-container {
            margin-bottom: 1rem;
            width: 100%;
            height: 400px;
            max-height: 60vh;
            overflow: hidden;
        }

        .img-container img {
            display: block;
            max-width: 100%;
        }

        .cropper-container {
            max-width: 100%;
        }

        .cropper-bg {
            background-image: none !important;
        }

        .preview-container {
            overflow: hidden;
            height: 150px;
            width: 100%;
            max-width: 400px;
            margin: 0 auto 1rem;
            border: 1px solid #ddd;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .preview-container.wide {
            width: 100%;
            max-width: 400px;
            height: 133px;
        }

        .crop-btn {
            margin-bottom: 15px;
        }

        /* Icon picker styling */
        .icon-picker-container {
            max-height: 300px;
            overflow-y: auto;
        }

        .icon-item {
            cursor: pointer;
            border-radius: 5px;
            width: 80px;
        }

        .icon-item:hover {
            background-color: #f8f9fa;
        }

        .icon-picker-modal {
            z-index: 10000;
        }
    </style>
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
                <div class="header-right d-flex align-items-center gap-3 ms-auto">
                    <div class="profile">
                        <img src="/Project_Website/ProjectWeb/upload/img/avatar.jpg" alt="Admin Avatar"
                            class="profile-image">
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="content" id="content-container">
                <div class="page-header d-flex justify-content-between align-items-center mb-4">
                    <h1 class="mb-0">Quản lý Trang chủ</h1>
                </div>

                <!-- Main Tabs -->
                <ul class="nav nav-tabs" id="homeManagementTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="sections-tab" data-bs-toggle="tab"
                            data-bs-target="#sections-content" type="button" role="tab" aria-controls="sections-content"
                            aria-selected="true">
                            <i class="fas fa-th-large me-2"></i>Vùng hiển thị
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="banners-tab" data-bs-toggle="tab" data-bs-target="#banners-content"
                            type="button" role="tab" aria-controls="banners-content" aria-selected="false">
                            <i class="fas fa-images me-2"></i>Banners
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="policies-tab" data-bs-toggle="tab"
                            data-bs-target="#policies-content" type="button" role="tab" aria-controls="policies-content"
                            aria-selected="false">
                            <i class="fas fa-file-alt me-2"></i>Chính sách
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="social-media-tab" data-bs-toggle="tab"
                            data-bs-target="#social-media-content" type="button" role="tab"
                            aria-controls="social-media-content" aria-selected="false">
                            <i class="fas fa-hashtag me-2"></i>Truyền thông
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="payment-methods-tab" data-bs-toggle="tab"
                            data-bs-target="#payment-methods-content" type="button" role="tab"
                            aria-controls="payment-methods-content" aria-selected="false">
                            <i class="fas fa-credit-card me-2"></i>Thanh toán
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="homeManagementTabsContent">
                    <!-- Sections Tab -->
                    <div class="tab-pane fade show active" id="sections-content" role="tabpanel"
                        aria-labelledby="sections-tab">
                        <div class="d-flex justify-content-between mb-3">
                            <h4 class="mb-0">Danh sách vùng hiển thị</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                                <i class="fas fa-plus me-2"></i>Thêm vùng hiển thị
                            </button>
                        </div>

                        <div id="sectionsContainer">
                            <!-- Sections will be loaded here -->
                            <?php if (empty($sections)): ?>
                                <div class="alert alert-info">
                                    Chưa có vùng hiển thị nào. Hãy thêm vùng hiển thị mới.
                                </div>
                            <?php else: ?>
                                <?php foreach ($sections as $section): ?>
                                    <div class="section-card" data-id="<?= $section['id'] ?>">
                                        <div class="section-header d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-0">
                                                    <?= htmlspecialchars($section['title']) ?>
                                                    <span
                                                        class="section-type-badge section-type-<?= $section['section_type'] ?>"><?= $section['section_type'] == 'product' ? 'Sản phẩm' : 'Danh mục' ?></span>
                                                </h5>
                                            </div>
                                            <div>
                                                <span
                                                    class="status-badge <?= $section['hide'] == 0 ? 'status-active' : 'status-inactive' ?>">
                                                    <?= $section['hide'] == 0 ? 'Đang hiển thị' : 'Đã ẩn' ?>
                                                </span>
                                                <span class="ms-2 text-muted small">Hiển thị: <?= $section['product_count'] ?>
                                                    mục</span>
                                                <span class="ms-2 text-muted small">Style:
                                                    <?= $section['display_style'] ?></span>
                                            </div>
                                        </div>
                                        <div class="section-footer">
                                            <div class="item-handle">
                                                <i class="fas fa-grip-vertical"></i>
                                            </div>
                                            <div>
                                                <button class="btn btn-sm btn-outline-primary btn-manage-items"
                                                    data-id="<?= $section['id'] ?>">
                                                    <i class="fas fa-list me-1"></i>Quản lý nội dung
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary btn-edit-section"
                                                    data-id="<?= $section['id'] ?>">
                                                    <i class="fas fa-edit me-1"></i>Sửa
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger btn-delete-section"
                                                    data-id="<?= $section['id'] ?>">
                                                    <i class="fas fa-trash me-1"></i>Xóa
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Banners Tab -->
                    <div class="tab-pane fade" id="banners-content" role="tabpanel" aria-labelledby="banners-tab">
                        <div class="d-flex justify-content-between mb-3">
                            <h4 class="mb-0">Danh sách banner</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBannerModal">
                                <i class="fas fa-plus me-2"></i>Thêm banner
                            </button>
                        </div>

                        <div id="bannersContainer">
                            <?php if (empty($banners)): ?>
                                <div class="alert alert-info">Chưa có banner nào. Hãy thêm banner mới.</div>
                            <?php else: ?>
                                <?php foreach ($banners as $banner): ?>
                                    <div class="banner-card" data-id="<?= $banner['id'] ?>">
                                        <div class="banner-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0"><?= htmlspecialchars($banner['title']) ?></h5>
                                            <div>
                                                <span
                                                    class="status-badge <?= $banner['hide'] == 0 ? 'status-active' : 'status-inactive' ?>">
                                                    <?= $banner['hide'] == 0 ? 'Đang hiển thị' : 'Đã ẩn' ?>
                                                </span>
                                                <span class="date-badge">
                                                    <?= date('d/m/Y', strtotime($banner['start_date'])) ?> -
                                                    <?= date('d/m/Y', strtotime($banner['end_date'])) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="banner-body">
                                            <img src="<?= $banner['image_path'] ?>"
                                                alt="<?= htmlspecialchars($banner['title']) ?>" class="banner-preview">
                                            <p class="text-center mb-0"><a href="<?= $banner['link'] ?>" target="_blank"
                                                    class="text-decoration-none"><?= $banner['link'] ?></a></p>
                                        </div>
                                        <div class="banner-footer">
                                            <div class="item-handle">
                                                <i class="fas fa-grip-vertical"></i>
                                            </div>
                                            <div>
                                                <button class="btn btn-sm btn-outline-secondary btn-edit-banner"
                                                    data-id="<?= $banner['id'] ?>">
                                                    <i class="fas fa-edit me-1"></i>Sửa
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger btn-delete-banner"
                                                    data-id="<?= $banner['id'] ?>">
                                                    <i class="fas fa-trash me-1"></i>Xóa
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Policies Tab -->
                    <div class="tab-pane fade" id="policies-content" role="tabpanel" aria-labelledby="policies-tab">
                        <div class="d-flex justify-content-between mb-3">
                            <h4 class="mb-0">Danh sách chính sách</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPolicyModal">
                                <i class="fas fa-plus me-2"></i>Thêm chính sách
                            </button>
                        </div>

                        <div id="policiesContainer">
                            <!-- Policies will be loaded here -->
                            <?php if (empty($policies)): ?>
                                <div class="alert alert-info">
                                    Chưa có chính sách nào. Hãy thêm chính sách mới.
                                </div>
                            <?php else: ?>
                                <?php foreach ($policies as $policy): ?>
                                    <div class="section-card" data-id="<?= $policy['id'] ?>">
                                        <div class="section-header d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($policy['image'])): ?>
                                                    <img src="<?= $policy['image'] ?>"
                                                        alt="<?= htmlspecialchars($policy['title']) ?>" class="me-2"
                                                        style="width: 30px; height: 30px; object-fit: cover;">
                                                <?php else: ?>
                                                    <i class="fas fa-file-alt me-2"></i>
                                                <?php endif; ?>
                                                <h5 class="mb-0"><?= htmlspecialchars($policy['title']) ?></h5>
                                            </div>
                                            <div>
                                                <span
                                                    class="status-badge <?= $policy['hide'] == 0 ? 'status-active' : 'status-inactive' ?>">
                                                    <?= $policy['hide'] == 0 ? 'Đang hiển thị' : 'Đã ẩn' ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="section-body">
                                            <p><a href="<?= $policy['link'] ?>" target="_blank"
                                                    class="text-decoration-none"><?= $policy['link'] ?></a></p>
                                            <?php if (!empty($policy['meta'])): ?>
                                                <p class="text-muted small"><?= htmlspecialchars($policy['meta']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="section-footer">
                                            <div class="item-handle">
                                                <i class="fas fa-grip-vertical"></i>
                                            </div>
                                            <div>
                                                <button class="btn btn-sm btn-outline-secondary btn-edit-policy"
                                                    data-id="<?= $policy['id'] ?>">
                                                    <i class="fas fa-edit me-1"></i>Sửa
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger btn-delete-policy"
                                                    data-id="<?= $policy['id'] ?>">
                                                    <i class="fas fa-trash me-1"></i>Xóa
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Social Media Tab -->
                    <div class="tab-pane fade" id="social-media-content" role="tabpanel"
                        aria-labelledby="social-media-tab">
                        <div class="d-flex justify-content-between mb-3">
                            <h4 class="mb-0">Danh sách mạng xã hội</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addSocialMediaModal">
                                <i class="fas fa-plus me-2"></i>Thêm mạng xã hội
                            </button>
                        </div>

                        <div id="socialMediaContainer">
                            <?php if (empty($socialMedias)): ?>
                                <div class="alert alert-info">Chưa có mạng xã hội nào. Hãy thêm mạng xã hội mới.</div>
                            <?php else: ?>
                                <?php foreach ($socialMedias as $socialMedia): ?>
                                    <div class="section-card" data-id="<?= $socialMedia['id'] ?>">
                                        <div class="section-card-header">
                                            <div class="d-flex align-items-center">
                                                <span class="item-handle"><i class="fas fa-grip-vertical"></i></span>
                                                <h6 class="section-title mb-0 ms-2"><?= $socialMedia['title'] ?></h6>
                                            </div>
                                            <div>
                                                <?php if ($socialMedia['hide'] == 1): ?>
                                                    <span class="badge bg-warning text-dark"><i
                                                            class="fas fa-eye-slash me-1"></i>Ẩn</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Hiển
                                                        thị</span>
                                                <?php endif; ?>
                                                <button class="btn btn-sm btn-outline-primary ms-1 btn-edit-social-media"
                                                    data-id="<?= $socialMedia['id'] ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger ms-1 btn-delete-social-media"
                                                    data-id="<?= $socialMedia['id'] ?>">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="section-card-body">
                                            <div class="row">
                                                <div class="col-md-1">
                                                    <div class="social-icon-preview text-center">
                                                        <i class="<?= $socialMedia['icon'] ?> fa-3x"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-11">
                                                    <div class="mb-2">
                                                        <strong>Icon:</strong>
                                                        <code><?= htmlspecialchars($socialMedia['icon']) ?></code>
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong>Link:</strong> <a href="<?= $socialMedia['link'] ?>"
                                                            target="_blank"><?= $socialMedia['link'] ?></a>
                                                    </div>
                                                    <div>
                                                        <strong>Mô tả:</strong>
                                                        <?= nl2br($socialMedia['meta'] ?? 'Không có mô tả') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Payment Methods Tab -->
                    <div class="tab-pane fade" id="payment-methods-content" role="tabpanel"
                        aria-labelledby="payment-methods-tab">
                        <div class="d-flex justify-content-between mb-3">
                            <h4 class="mb-0">Danh sách phương thức thanh toán</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addPaymentMethodModal">
                                <i class="fas fa-plus me-2"></i>Thêm phương thức thanh toán
                            </button>
                        </div>

                        <div id="paymentMethodsContainer">
                            <?php if (empty($paymentMethods)): ?>
                                <div class="alert alert-info">Chưa có phương thức thanh toán nào. Hãy thêm phương thức thanh
                                    toán mới.</div>
                            <?php else: ?>
                                <?php foreach ($paymentMethods as $paymentMethod): ?>
                                    <div class="section-card" data-id="<?= $paymentMethod['id'] ?>">
                                        <div class="section-card-header">
                                            <div class="d-flex align-items-center">
                                                <span class="item-handle"><i class="fas fa-grip-vertical"></i></span>
                                                <h6 class="section-title mb-0 ms-2"><?= $paymentMethod['title'] ?></h6>
                                            </div>
                                            <div>
                                                <?php if ($paymentMethod['hide'] == 1): ?>
                                                    <span class="badge bg-warning text-dark"><i
                                                            class="fas fa-eye-slash me-1"></i>Ẩn</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Hiển
                                                        thị</span>
                                                <?php endif; ?>
                                                <button class="btn btn-sm btn-outline-primary ms-1 btn-edit-payment-method"
                                                    data-id="<?= $paymentMethod['id'] ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger ms-1 btn-delete-payment-method"
                                                    data-id="<?= $paymentMethod['id'] ?>">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="section-card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <img src="<?= $paymentMethod['image'] ?>" class="img-fluid rounded"
                                                        alt="<?= $paymentMethod['title'] ?>">
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="mb-2">
                                                        <strong>Link:</strong> <a href="<?= $paymentMethod['link'] ?>"
                                                            target="_blank"><?= $paymentMethod['link'] ?></a>
                                                    </div>
                                                    <div>
                                                        <strong>Mô tả:</strong>
                                                        <?= nl2br($paymentMethod['meta'] ?? 'Không có mô tả') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Add Section Modal -->
    <div class="modal fade" id="addSectionModal" tabindex="-1" aria-labelledby="addSectionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSectionModalLabel">Thêm mới vùng hiển thị</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addSectionForm">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="section_type" class="form-label">Loại vùng</label>
                            <select class="form-select" id="section_type" name="section_type">
                                <option value="product">Sản phẩm</option>
                                <option value="category">Danh mục</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="display_style" class="form-label">Kiểu hiển thị</label>
                            <select class="form-select" id="display_style" name="display_style">
                                <option value="grid">Lưới</option>
                                <option value="carousel">Trượt ngang</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="product_count" class="form-label">Số lượng hiển thị</label>
                            <input type="number" class="form-control" id="product_count" name="product_count" value="4"
                                min="1" max="20">
                        </div>
                        <div class="mb-3">
                            <label for="link" class="form-label">Đường dẫn (Link)</label>
                            <input type="text" class="form-control" id="link" name="link" value="#">
                        </div>
                        <div class="mb-3">
                            <label for="meta" class="form-label">Metadata</label>
                            <textarea class="form-control" id="meta" name="meta" rows="2"></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="status" name="status" checked>
                            <label class="form-check-label" for="status">
                                Hiển thị
                            </label>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Thêm mới</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Section Modal -->
    <div class="modal fade" id="editSectionModal" tabindex="-1" aria-labelledby="editSectionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSectionModalLabel">Chỉnh sửa vùng hiển thị</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editSectionForm">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_title" class="form-label">Tiêu đề <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_section_type" class="form-label">Loại vùng</label>
                            <select class="form-select" id="edit_section_type" name="section_type">
                                <option value="product">Sản phẩm</option>
                                <option value="category">Danh mục</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_display_style" class="form-label">Kiểu hiển thị</label>
                            <select class="form-select" id="edit_display_style" name="display_style">
                                <option value="grid">Lưới</option>
                                <option value="carousel">Trượt ngang</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_product_count" class="form-label">Số lượng hiển thị</label>
                            <input type="number" class="form-control" id="edit_product_count" name="product_count"
                                min="1" max="20">
                        </div>
                        <div class="mb-3">
                            <label for="edit_link" class="form-label">Đường dẫn (Link)</label>
                            <input type="text" class="form-control" id="edit_link" name="link">
                        </div>
                        <div class="mb-3">
                            <label for="edit_meta" class="form-label">Metadata</label>
                            <textarea class="form-control" id="edit_meta" name="meta" rows="2"></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="edit_hide" name="hide">
                            <label class="form-check-label" for="edit_hide">
                                Ẩn khỏi trang chủ (đặt vào thùng rác)
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="edit_status" name="status">
                            <label class="form-check-label" for="edit_status">
                                Hiển thị
                            </label>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Items Modal -->
    <div class="modal fade" id="sectionItemsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Quản lý nội dung vùng hiển thị</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="currentSectionId">
                    <input type="hidden" id="currentSectionType">

                    <div class="row mb-3">
                        <div class="col">
                            <h6 id="sectionItemsTitle"></h6>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-sm btn-outline-primary" id="addItemBtn">
                                <i class="fas fa-plus me-1"></i>Thêm mục
                            </button>
                        </div>
                    </div>

                    <div class="mb-3" id="addItemFormContainer" style="display: none;">
                        <div class="card">
                            <div class="card-body">
                                <form id="addItemForm" class="row g-3">
                                    <div class="col-md-12">
                                        <select class="form-select" id="itemSelector" required>
                                            <option value="">-- Chọn mục --</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 text-end">
                                        <button type="button" class="btn btn-sm btn-secondary"
                                            id="cancelAddItem">Hủy</button>
                                        <button type="button" class="btn btn-sm btn-primary"
                                            id="confirmAddItem">Thêm</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="itemsList">
                        <!-- Items will be displayed here -->
                        <div class="text-center py-3" id="itemsLoading">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Đang tải...</span>
                            </div>
                        </div>
                        <div id="itemsContainer"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Banner Modal -->
    <div class="modal fade" id="addBannerModal" tabindex="-1" aria-labelledby="addBannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBannerModalLabel">Thêm mới banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addBannerForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="banner_title" class="form-label">Tiêu đề <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="banner_title" name="title" required>
                        </div>
                        <div class="mb-3" id="image_upload_container">
                            <label for="bannerImage" class="form-label">Ảnh banner <span
                                    class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="bannerImage" name="image_upload"
                                accept="image/*" required>
                            <div class="form-text">Kích thước đề xuất: 1200x400 pixel, tối đa 2MB</div>
                        </div>

                        <div id="cropperContainer" style="display: none;">
                            <div class="img-container mb-3">
                                <img id="imageToCrop" src="">
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary" data-method="zoom"
                                        data-option="0.1">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-method="zoom"
                                        data-option="-0.1">
                                        <i class="fas fa-search-minus"></i>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary" data-method="rotate"
                                        data-option="-90">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-method="rotate"
                                        data-option="90">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary" data-method="scaleX"
                                        data-option="-1">
                                        <i class="fas fa-arrows-alt-h"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-method="scaleY"
                                        data-option="-1">
                                        <i class="fas fa-arrows-alt-v"></i>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary" data-method="reset">
                                        <i class="fas fa-sync-alt"></i> Reset
                                    </button>
                                </div>
                            </div>

                            <div class="text-center mb-3">
                                <h6>Xem trước (1200x400)</h6>
                                <div class="preview-container wide mx-auto">
                                    <div class="preview"></div>
                                </div>
                            </div>

                            <div class="text-center mb-3">
                                <button type="button" class="btn btn-primary crop-btn" id="cropImageBtn">
                                    <i class="fas fa-crop-alt"></i> Cắt và sử dụng
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="cancelCropBtn">
                                    <i class="fas fa-times"></i> Hủy
                                </button>
                            </div>

                            <!-- Hidden input to store cropped image data -->
                            <input type="hidden" name="image" id="croppedImageData">
                        </div>

                        <div class="mb-3">
                            <label for="banner_link_url" class="form-label">Đường dẫn khi click</label>
                            <input type="text" class="form-control" id="banner_link_url" name="link_url" value="#">
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="banner_start_date" class="form-label">Ngày bắt đầu</label>
                                <input type="date" class="form-control" id="banner_start_date" name="start_date"
                                    value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col">
                                <label for="banner_end_date" class="form-label">Ngày kết thúc</label>
                                <input type="date" class="form-control" id="banner_end_date" name="end_date"
                                    value="<?php echo date('Y-m-d', strtotime('+30 days')); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="banner_meta" class="form-label">Metadata</label>
                            <textarea class="form-control" id="banner_meta" name="meta" rows="2"></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="banner_status" name="status" checked>
                            <label class="form-check-label" for="banner_status">
                                Hiển thị
                            </label>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Thêm mới</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Banner Modal -->
    <div class="modal fade" id="editBannerModal" tabindex="-1" aria-labelledby="editBannerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBannerModalLabel">Chỉnh sửa banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editBannerForm" enctype="multipart/form-data">
                        <input type="hidden" id="edit_banner_id" name="id">
                        <div class="mb-3">
                            <label for="edit_banner_title" class="form-label">Tiêu đề <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_banner_title" name="title" required>
                        </div>
                        <div class="mb-3" id="edit_image_upload_container">
                            <label for="edit_banner_image" class="form-label">Ảnh banner</label>
                            <input type="file" class="form-control" id="edit_banner_image" name="image_upload"
                                accept="image/*">
                            <div class="form-text">Kích thước đề xuất: 1200x400 pixel, tối đa 2MB. Để trống nếu không
                                thay đổi ảnh.</div>
                            <div class="mt-2">
                                <img id="editBannerPreview" src="" alt="Preview"
                                    style="max-width: 100%; max-height: 200px; display: none;">
                            </div>
                        </div>

                        <div id="edit_cropperContainer" style="display: none;">
                            <div class="img-container mb-3">
                                <img id="editImageToCrop" src="">
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary" data-method="zoom"
                                        data-option="0.1">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-method="zoom"
                                        data-option="-0.1">
                                        <i class="fas fa-search-minus"></i>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary" data-method="rotate"
                                        data-option="-90">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-method="rotate"
                                        data-option="90">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary" data-method="scaleX"
                                        data-option="-1">
                                        <i class="fas fa-arrows-alt-h"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-method="scaleY"
                                        data-option="-1">
                                        <i class="fas fa-arrows-alt-v"></i>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary" data-method="reset">
                                        <i class="fas fa-sync-alt"></i> Reset
                                    </button>
                                </div>
                            </div>

                            <div class="text-center mb-3">
                                <h6>Xem trước (1200x400)</h6>
                                <div class="preview-container wide mx-auto">
                                    <div class="edit-preview"></div>
                                </div>
                            </div>

                            <div class="text-center mb-3">
                                <button type="button" class="btn btn-primary crop-btn" id="editCropImageBtn">
                                    <i class="fas fa-crop-alt"></i> Cắt và sử dụng
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="editCancelCropBtn">
                                    <i class="fas fa-times"></i> Hủy
                                </button>
                            </div>

                            <!-- Hidden input to store cropped image data -->
                            <input type="hidden" name="image" id="editCroppedImageData">
                        </div>

                        <div class="mb-3">
                            <label for="edit_banner_link_url" class="form-label">Đường dẫn khi click</label>
                            <input type="text" class="form-control" id="edit_banner_link_url" name="link_url">
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="edit_banner_start_date" class="form-label">Ngày bắt đầu</label>
                                <input type="date" class="form-control" id="edit_banner_start_date" name="start_date">
                            </div>
                            <div class="col">
                                <label for="edit_banner_end_date" class="form-label">Ngày kết thúc</label>
                                <input type="date" class="form-control" id="edit_banner_end_date" name="end_date">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_banner_meta" class="form-label">Metadata</label>
                            <textarea class="form-control" id="edit_banner_meta" name="meta" rows="2"></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="edit_banner_hide" name="hide">
                            <label class="form-check-label" for="edit_banner_hide">
                                Ẩn khỏi trang chủ (đặt vào thùng rác)
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="edit_banner_status" name="status">
                            <label class="form-check-label" for="edit_banner_status">
                                Hiển thị
                            </label>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Policy Modal -->
    <div class="modal fade" id="addPolicyModal" tabindex="-1" aria-labelledby="addPolicyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPolicyModalLabel">Thêm mới chính sách</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPolicyForm" enctype="multipart/form-data" method="post" action="javascript:void(0);">
                        <div class="mb-3">
                            <label for="policy_title" class="form-label">Tiêu đề <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="policy_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="policy_image" class="form-label">Ảnh (tùy chọn)</label>
                            <input type="file" class="form-control" id="policy_image" name="image_upload"
                                accept="image/*">
                            <div class="form-text">Hình ảnh đại diện cho chính sách (nếu có), tối đa 1MB</div>
                        </div>

                        <div id="policy_cropperContainer" style="display: none;">
                            <div class="img-container mb-3">
                                <img id="policyImageToCrop" src="">
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary" data-method="zoom"
                                        data-option="0.1">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-method="zoom"
                                        data-option="-0.1">
                                        <i class="fas fa-search-minus"></i>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary" data-method="rotate"
                                        data-option="-90">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-method="rotate"
                                        data-option="90">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary" data-method="reset">
                                        <i class="fas fa-sync-alt"></i> Reset
                                    </button>
                                </div>
                            </div>

                            <div class="text-center mb-3">
                                <div class="preview-container mx-auto">
                                    <div class="policy-preview"></div>
                                </div>
                            </div>

                            <div class="text-center mb-3">
                                <button type="button" class="btn btn-primary crop-btn" id="cropPolicyBtn">
                                    <i class="fas fa-crop-alt"></i> Cắt và sử dụng
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="cancelPolicyCropBtn">
                                    <i class="fas fa-times"></i> Hủy
                                </button>
                            </div>

                            <!-- Hidden input to store cropped image data -->
                            <input type="hidden" name="image" id="policyCroppedImageData">
                        </div>

                        <div class="mb-3">

                            <div class="mb-3">
                                <label for="policy_meta" class="form-label">Mô tả</label>
                                <textarea class="form-control" id="policy_meta" name="meta" rows="2"></textarea>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="policy_status" name="status"
                                    checked>
                                <label class="form-check-label" for="policy_status">
                                    Hiển thị
                                </label>
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-primary">Thêm mới</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Add Social Media Modal -->
    <div class="modal fade" id="addSocialMediaModal" tabindex="-1" aria-labelledby="addSocialMediaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSocialMediaModalLabel">Thêm mới mạng xã hội</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addSocialMediaForm" method="post" action="javascript:void(0);">
                        <div class="mb-3">
                            <label for="social_media_title" class="form-label">Tên mạng xã hội <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="social_media_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="social_media_icon" class="form-label">Icon <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="social_media_icon" name="icon" required
                                    placeholder="fab fa-facebook">
                                <button type="button" class="btn btn-outline-secondary icon-picker-btn"
                                    data-target="social_media_icon">
                                    <i class="fas fa-icons"></i> Chọn icon
                                </button>
                            </div>
                            <div class="form-text">Ví dụ: fab fa-facebook, fab fa-instagram,...</div>
                            <div class="mt-2">
                                <i id="iconPreview" class="fab fa-facebook fa-2x"></i>
                                <span class="ms-2 text-muted">Xem trước icon</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="social_media_link" class="form-label">Đường dẫn (Link)</label>
                            <input type="url" class="form-control" id="social_media_link" name="link" value="#">
                        </div>
                        <div class="mb-3">
                            <label for="social_media_meta" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="social_media_meta" name="meta" rows="2"></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="social_media_status" name="status"
                                checked>
                            <label class="form-check-label" for="social_media_status">
                                Hiển thị
                            </label>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Thêm mới</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Social Media Modal -->
    <div class="modal fade" id="editSocialMediaModal" tabindex="-1" aria-labelledby="editSocialMediaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSocialMediaModalLabel">Chỉnh sửa mạng xã hội</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editSocialMediaForm">
                        <input type="hidden" id="edit_social_media_id" name="id">
                        <div class="mb-3">
                            <label for="edit_social_media_title" class="form-label">Tên mạng xã hội <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_social_media_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_social_media_icon" class="form-label">Icon <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="edit_social_media_icon" name="icon"
                                    required>
                                <button type="button" class="btn btn-outline-secondary icon-picker-btn"
                                    data-target="edit_social_media_icon">
                                    <i class="fas fa-icons"></i> Chọn icon
                                </button>
                            </div>
                            <div class="form-text">Ví dụ: fab fa-facebook, fab fa-instagram,...</div>
                            <div class="mt-2">
                                <i id="editIconPreview" class="fab fa-facebook fa-2x"></i>
                                <span class="ms-2 text-muted">Xem trước icon</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_social_media_link" class="form-label">Đường dẫn (Link)</label>
                            <input type="url" class="form-control" id="edit_social_media_link" name="link">
                        </div>
                        <div class="mb-3">
                            <label for="edit_social_media_meta" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="edit_social_media_meta" name="meta" rows="2"></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="edit_social_media_status" name="status">
                            <label class="form-check-label" for="edit_social_media_status">
                                Hiển thị
                            </label>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Payment Method Modal -->
    <div class="modal fade" id="addPaymentMethodModal" tabindex="-1" aria-labelledby="addPaymentMethodModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentMethodModalLabel">Thêm mới phương thức thanh toán</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPaymentMethodForm" enctype="multipart/form-data" method="post"
                        action="javascript:void(0);">
                        <div class="mb-3">
                            <label for="payment_method_title" class="form-label">Tên phương thức <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="payment_method_title" name="title" required>
                        </div>
                        <div class="mb-3" id="payment_method_image_container">
                            <label for="payment_method_image" class="form-label">Ảnh <span
                                    class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="payment_method_image" name="image_upload"
                                accept="image/*" required>
                            <div class="form-text">Hình ảnh biểu tượng cho phương thức thanh toán, tối đa 1MB</div>
                        </div>

                        <div id="payment_method_cropperContainer" style="display: none;">
                            <div class="img-container mb-3">
                                <img id="paymentMethodImageToCrop" src="">
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary" data-method="zoom"
                                        data-option="0.1">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-method="zoom"
                                        data-option="-0.1">
                                        <i class="fas fa-search-minus"></i>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary" data-method="rotate"
                                        data-option="-90">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-method="rotate"
                                        data-option="90">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary" data-method="reset">
                                        <i class="fas fa-sync-alt"></i> Reset
                                    </button>
                                </div>
                            </div>

                            <div class="text-center mb-3">
                                <div class="preview-container mx-auto">
                                    <div class="payment-method-preview"></div>
                                </div>
                            </div>

                            <div class="text-center mb-3">
                                <button type="button" class="btn btn-primary crop-btn" id="cropPaymentMethodBtn">
                                    <i class="fas fa-crop-alt"></i> Cắt và sử dụng
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="cancelPaymentMethodCropBtn">
                                    <i class="fas fa-times"></i> Hủy
                                </button>
                            </div>

                            <!-- Hidden input to store cropped image data -->
                            <input type="hidden" name="image" id="paymentMethodCroppedImageData">
                        </div>

                        <div class="mb-3">
                            <label for="payment_method_link" class="form-label">Đường dẫn (Link)</label>
                            <input type="url" class="form-control" id="payment_method_link" name="link" value="#">
                        </div>
                        <div class="mb-3">
                            <label for="payment_method_meta" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="payment_method_meta" name="meta" rows="2"></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="payment_method_status" name="status"
                                checked>
                            <label class="form-check-label" for="payment_method_status">
                                Hiển thị
                            </label>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Thêm mới</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Payment Method Modal -->
    <div class="modal fade" id="editPaymentMethodModal" tabindex="-1" aria-labelledby="editPaymentMethodModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPaymentMethodModalLabel">Chỉnh sửa phương thức thanh toán</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editPaymentMethodForm" enctype="multipart/form-data" method="post"
                        action="javascript:void(0);">
                        <input type="hidden" id="edit_payment_method_id" name="id">
                        <div class="mb-3">
                            <label for="edit_payment_method_title" class="form-label">Tên phương thức <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_payment_method_title" name="title"
                                required>
                        </div>
                        <div class="mb-3" id="edit_payment_method_image_container">
                            <label for="edit_payment_method_image" class="form-label">Ảnh</label>
                            <input type="file" class="form-control" id="edit_payment_method_image" name="image_upload"
                                accept="image/*">
                            <div class="form-text">Để trống nếu không muốn thay đổi ảnh hiện tại, tối đa 1MB</div>
                            <div class="mt-2">
                                <img id="editPaymentMethodPreview" src="" alt="Preview"
                                    style="max-width: 100%; max-height: 200px; display: none;">
                            </div>
                        </div>

                        <div id="edit_payment_method_cropperContainer" style="display: none;">
                            <div class="img-container mb-3">
                                <img id="editPaymentMethodImageToCrop" src="">
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary" data-method="zoom"
                                        data-option="0.1">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-method="zoom"
                                        data-option="-0.1">
                                        <i class="fas fa-search-minus"></i>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary" data-method="rotate"
                                        data-option="-90">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-method="rotate"
                                        data-option="90">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary" data-method="reset">
                                        <i class="fas fa-sync-alt"></i> Reset
                                    </button>
                                </div>
                            </div>

                            <div class="text-center mb-3">
                                <div class="preview-container mx-auto">
                                    <div class="edit-payment-method-preview"></div>
                                </div>
                            </div>

                            <div class="text-center mb-3">
                                <button type="button" class="btn btn-primary crop-btn" id="editCropPaymentMethodBtn">
                                    <i class="fas fa-crop-alt"></i> Cắt và sử dụng
                                </button>
                                <button type="button" class="btn btn-outline-secondary"
                                    id="editCancelPaymentMethodCropBtn">
                                    <i class="fas fa-times"></i> Hủy
                                </button>
                            </div>

                            <!-- Hidden input to store cropped image data -->
                            <input type="hidden" name="image" id="editPaymentMethodCroppedImageData">
                        </div>

                        <div class="mb-3">
                            <label for="edit_payment_method_link" class="form-label">Đường dẫn (Link)</label>
                            <input type="url" class="form-control" id="edit_payment_method_link" name="link">
                        </div>
                        <div class="mb-3">
                            <label for="edit_payment_method_meta" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="edit_payment_method_meta" name="meta"
                                rows="2"></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="edit_payment_method_status"
                                name="status">
                            <label class="form-check-label" for="edit_payment_method_status">
                                Hiển thị
                            </label>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Đang xử lý...</span>
        </div>
    </div>

    <!-- Thêm JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="/Project_Website/ProjectWeb/layout/jsBootstrap/bootstrap.bundle.min.js"></script>
    <!-- Cropper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            console.log("Document ready - AdminHome page initialized");

            // Biến và cài đặt
            let cropper = null;
            let editCropper = null;
            let paymentMethodCropper = null;
            let editPaymentMethodCropper = null;


            // Xử lý hiển thị icon preview khi nhập class icon trong form edit
            $('#edit_social_media_icon').on('input', function () {
                const iconClass = $(this).val();
                $('#editIconPreview').attr('class', iconClass + ' fa-2x');
            });

            // -------------------------------
            // PHẦN 1: CÁC HÀM TIỆN ÍCH
            // -------------------------------

            // Hiển thị/ẩn loading overlay
            function toggleLoading(show) {
                if (show) {
                    $("#loadingOverlay").addClass("show");
                } else {
                    $("#loadingOverlay").removeClass("show");
                }
            }

            // Hiển thị thông báo
            function showAlert(message, type = 'success') {
                console.log(`Alert: ${message} (${type})`);
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

                // Xóa thông báo cũ
                $(".alert").alert('close');

                // Thêm thông báo mới
                $("#content-container").prepend(alertHtml);

                // Tự động đóng sau 3 giây
                setTimeout(function () {
                    $(".alert").alert('close');
                }, 3000);
            }

            // Format giá tiền
            function formatCurrency(amount) {
                return new Intl.NumberFormat('vi-VN').format(amount);
            }

            // Xử lý lỗi Ajax
            function handleAjaxError(xhr, status, error, customMessage = '') {
                console.error("Ajax error:", xhr, status, error);
                let errorMsg = customMessage || 'Lỗi khi xử lý yêu cầu';

                if (xhr.responseText) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMsg += ': ' + response.message;
                        }
                    } catch (e) {
                        errorMsg += ': ' + xhr.responseText.substring(0, 100);
                    }
                } else if (error) {
                    errorMsg += ': ' + error;
                }

                showAlert(errorMsg, 'error');
            }

            // Mở modal
            function openModal(modalId) {
                const modal = new bootstrap.Modal(document.getElementById(modalId));
                modal.show();
            }

            // Toggle sidebar
            $("#sidebarToggleBtn").click(function () {
                $("#sidebar").toggleClass("active");
            });

            // -------------------------------
            // PHẦN 2: QUẢN LÝ SECTIONS
            // -------------------------------

            // Function to load sections without page refresh
            function loadSections() {
                console.log("Loading sections list");
                $.ajax({
                    url: 'index.php?controller=adminhome&action=getSections',
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function () {
                        toggleLoading(true);
                    },
                    success: function (response) {
                        if (response.sections) {
                            let sectionsHtml = '';

                            if (response.sections.length === 0) {
                                $('#sectionsContainer').html('<div class="alert alert-info">Chưa có vùng hiển thị nào. Hãy thêm vùng hiển thị mới.</div>');
                                return;
                            }

                            response.sections.forEach(function (section) {
                                sectionsHtml += `
                            <div class="section-card" data-id="${section.id}">
                                <div class="section-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0">
                                            ${section.title}
                                            <span class="section-type-badge section-type-${section.section_type}">${section.section_type == 'product' ? 'Sản phẩm' : 'Danh mục'}</span>
                                        </h5>
                                    </div>
                                    <div>
                                        <span class="status-badge ${section.hide == 0 ? 'status-active' : 'status-inactive'}">
                                            ${section.hide == 0 ? 'Đang hiển thị' : 'Đã ẩn'}
                                        </span>
                                        <span class="ms-2 text-muted small">Hiển thị: ${section.product_count} mục</span>
                                        <span class="ms-2 text-muted small">Style: ${section.display_style}</span>
                                    </div>
                                </div>
                                <div class="section-footer">
                                    <div class="item-handle">
                                        <i class="fas fa-grip-vertical"></i>
                                    </div>
                                    <div>
                                        <button class="btn btn-sm btn-outline-primary btn-manage-items" data-id="${section.id}">
                                            <i class="fas fa-list me-1"></i>Quản lý nội dung
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary btn-edit-section" data-id="${section.id}">
                                            <i class="fas fa-edit me-1"></i>Sửa
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger btn-delete-section" data-id="${section.id}">
                                            <i class="fas fa-trash me-1"></i>Xóa
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                            });

                            $('#sectionsContainer').html(sectionsHtml);

                            // Re-initialize sortable
                            $("#sectionsContainer").sortable({
                                items: ".section-card",
                                handle: ".item-handle",
                                placeholder: "sortable-placeholder",
                                update: function (event, ui) {
                                    console.log("Reordering sections");

                                    // Lấy thứ tự mới
                                    const positions = {};
                                    $(".section-card").each(function (index) {
                                        positions[$(this).data("id")] = index + 1;
                                    });

                                    // Cập nhật thứ tự trên server
                                    $.ajax({
                                        url: 'index.php?controller=adminhome&action=updateSectionsOrder',
                                        type: 'POST',
                                        data: { positions: positions },
                                        dataType: 'json',
                                        success: function (response) {
                                            if (response.success) {
                                                showAlert(response.message);
                                            } else {
                                                showAlert(response.message || "Không thể cập nhật thứ tự vùng hiển thị", "error");
                                            }
                                        },
                                        error: function (xhr, status, error) {
                                            handleAjaxError(xhr, status, error, 'Không thể cập nhật thứ tự vùng hiển thị');
                                        }
                                    });
                                }
                            }).disableSelection();
                        } else {
                            showAlert("Không thể tải danh sách vùng hiển thị", "error");
                        }
                    },
                    error: function (xhr, status, error) {
                        handleAjaxError(xhr, status, error, 'Không thể tải danh sách vùng hiển thị');
                    },
                    complete: function () {
                        toggleLoading(false);
                    }
                });
            }

            // Thêm section mới
            $("#addSectionForm").on("submit", function (e) {
                e.preventDefault();
                console.log("Add section form submitted");

                const formData = new FormData(this);

                $.ajax({
                    url: 'index.php?controller=adminhome&action=addSection',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    beforeSend: function () {
                        toggleLoading(true);
                    },
                    success: function (response) {
                        console.log("Add section response:", response);
                        if (response.success) {
                            showAlert(response.message);

                            // Đóng modal và reset form
                            $("#addSectionModal").modal('hide');
                            $("#addSectionForm")[0].reset();

                            // Tải lại danh sách sections mà không cần refresh trang
                            loadSections();

                            // Mở modal để thêm sản phẩm vào section mới
                            setTimeout(function () {
                                openSectionItemsModal(response.id);
                            }, 500);
                        } else {
                            showAlert(response.message, 'error');
                        }
                    },
                    error: function (xhr, status, error) {
                        handleAjaxError(xhr, status, error, 'Không thể thêm vùng hiển thị');
                    },
                    complete: function () {
                        toggleLoading(false);
                    }
                });
            });

            // Sửa section
            $(document).on("click", ".btn-edit-section", function (e) {
                e.preventDefault();
                console.log("Edit section button clicked");

                const sectionId = $(this).data("id");
                if (!sectionId) {
                    showAlert("Không tìm thấy ID của vùng hiển thị", "error");
                    return;
                }

                $.ajax({
                    url: `index.php?controller=adminhome&action=editSection&id=${sectionId}`,
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function () {
                        toggleLoading(true);
                    },
                    success: function (section) {
                        console.log("Section data received:", section);

                        // Điền dữ liệu vào form
                        $('#edit_id').val(section.id);
                        $('#edit_title').val(section.title);
                        $('#edit_section_type').val(section.section_type);
                        $('#edit_display_style').val(section.display_style);
                        $('#edit_product_count').val(section.product_count);
                        $('#edit_link').val(section.link || '');
                        $('#edit_meta').val(section.meta || '');

                        // Set trạng thái hiển thị
                        $('#edit_status').prop('checked', section.hide == 0);
                        $('#edit_hide').prop('checked', section.hide == 1);

                        // Mở modal
                        openModal('editSectionModal');
                    },
                    error: function (xhr, status, error) {
                        handleAjaxError(xhr, status, error, 'Không thể tải thông tin vùng hiển thị');
                    },
                    complete: function () {
                        toggleLoading(false);
                    }
                });
            });

            // Xử lý submit form edit section
            $("#editSectionForm").on("submit", function (e) {
                e.preventDefault();
                console.log("Edit section form submitted");

                const formData = new FormData(this);

                $.ajax({
                    url: 'index.php?controller=adminhome&action=editSection',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    beforeSend: function () {
                        toggleLoading(true);
                    },
                    success: function (response) {
                        console.log("Edit section response:", response);
                        if (response.success) {
                            showAlert(response.message);

                            // Đóng modal
                            $("#editSectionModal").modal('hide');

                            // Tải lại trang sau 1s
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        } else {
                            showAlert(response.message, 'error');
                        }
                    },
                    error: function (xhr, status, error) {
                        handleAjaxError(xhr, status, error, 'Không thể cập nhật vùng hiển thị');
                    },
                    complete: function () {
                        toggleLoading(false);
                    }
                });
            });

            // Xóa section
            $(document).on("click", ".btn-delete-section", function (e) {
                e.preventDefault();
                console.log("Delete section button clicked");

                const sectionId = $(this).data("id");
                if (!sectionId) {
                    showAlert("Không tìm thấy ID của vùng hiển thị", "error");
                    return;
                }

                if (confirm("Bạn có chắc chắn muốn xóa vùng hiển thị này không?")) {
                    $.ajax({
                        url: 'index.php?controller=adminhome&action=deleteSection',
                        type: 'POST',
                        data: { id: sectionId },
                        dataType: 'json',
                        beforeSend: function () {
                            toggleLoading(true);
                        },
                        success: function (response) {
                            console.log("Delete section response:", response);
                            if (response.success) {
                                showAlert(response.message);

                                // Xóa phần tử trên UI
                                $(`.section-card[data-id="${sectionId}"]`).fadeOut(300, function () {
                                    $(this).remove();

                                    // Hiển thị thông báo nếu không còn section nào
                                    if ($('#sectionsContainer .section-card').length === 0) {
                                        $('#sectionsContainer').html('<div class="alert alert-info">Chưa có vùng hiển thị nào. Hãy thêm vùng hiển thị mới.</div>');
                                    }
                                });
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function (xhr, status, error) {
                            handleAjaxError(xhr, status, error, 'Không thể xóa vùng hiển thị');
                        },
                        complete: function () {
                            toggleLoading(false);
                        }
                    });
                }
            });

            // Sắp xếp các section bằng drag & drop
            if ($("#sectionsContainer").length) {
                $("#sectionsContainer").sortable({
                    items: ".section-card",
                    handle: ".item-handle",
                    placeholder: "sortable-placeholder",
                    update: function (event, ui) {
                        console.log("Reordering sections");

                        // Lấy thứ tự mới
                        const positions = {};
                        $(".section-card").each(function (index) {
                            positions[$(this).data("id")] = index + 1;
                        });

                        // Cập nhật thứ tự trên server
                        $.ajax({
                            url: 'index.php?controller=adminhome&action=updateSectionsOrder',
                            type: 'POST',
                            data: { positions: positions },
                            dataType: 'json',
                            success: function (response) {
                                if (response.success) {
                                    showAlert(response.message);
                                } else {
                                    showAlert(response.message || "Không thể cập nhật thứ tự vùng hiển thị", "error");
                                }
                            },
                            error: function (xhr, status, error) {
                                handleAjaxError(xhr, status, error, 'Không thể cập nhật thứ tự vùng hiển thị');
                            }
                        });
                    }
                }).disableSelection();
            }

            // -------------------------------
            // PHẦN 3: QUẢN LÝ NỘI DUNG SECTION
            // -------------------------------

            // Mở modal quản lý nội dung section
            function openSectionItemsModal(sectionId) {
                console.log("Opening section items modal for section ID:", sectionId);

                if (!sectionId) {
                    showAlert("Không tìm thấy ID vùng hiển thị", "error");
                    return;
                }

                // Lưu ID section hiện tại
                $('#currentSectionId').val(sectionId);

                // Reset UI
                $('#itemsContainer').empty();
                $('#itemsLoading').show();
                $('#addItemFormContainer').hide();

                // Enable the add button by default (will disable if limit reached)
                $('#addItemBtn').removeClass('disabled').prop('disabled', false);

                // Lấy thông tin section và items
                $.ajax({
                    url: `index.php?controller=adminhome&action=getSectionItems&section_id=${sectionId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        console.log("Get section items response:", response);

                        if (response.success) {
                            $('#sectionItemsTitle').text(`Quản lý nội dung: ${response.section.title}`);
                            $('#currentSectionType').val(response.section.section_type);

                            // Hiển thị danh sách items
                            renderSectionItems(response.items, response.itemDetails);

                            // Cập nhật selector với danh sách sản phẩm/danh mục
                            updateItemSelector(response.section.section_type, response.allProducts, response.allCategories);

                            // Check if item limit is reached
                            const maxItems = parseInt(response.section.product_count) || 4;
                            if (response.items.length >= maxItems) {
                                // Disable add button if limit reached
                                $('#addItemBtn').addClass('disabled').prop('disabled', true);
                                showAlert(`Đã đạt giới hạn số lượng tối đa (${maxItems} mục). Không thể thêm mục mới.`, "warning");
                            }

                            // Mở modal
                            openModal('sectionItemsModal');
                        } else {
                            showAlert(response.message || "Không thể tải nội dung vùng hiển thị", "error");
                        }
                    },
                    error: function (xhr, status, error) {
                        handleAjaxError(xhr, status, error, 'Không thể tải nội dung vùng hiển thị');
                    },
                    complete: function () {
                        $('#itemsLoading').hide();
                    }
                });
            }

            // Mở modal quản lý nội dung khi click vào nút
            $(document).on("click", ".btn-manage-items", function (e) {
                e.preventDefault();
                const sectionId = $(this).data("id");
                openSectionItemsModal(sectionId);
            });

            // Hiển thị danh sách items
            function renderSectionItems(items, itemDetails) {
                console.log("Rendering section items:", items);
                const container = $('#itemsContainer');
                container.empty();

                if (!items || items.length === 0) {
                    container.html('<div class="alert alert-info">Chưa có mục nào trong vùng hiển thị này. Hãy thêm mục mới.</div>');
                    return;
                }

                // Tạo danh sách có thể kéo thả để sắp xếp
                const itemsList = $('<div class="section-items-list"></div>');

                // Hiển thị từng item
                items.forEach(function (item) {
                    const itemKey = `${item.item_type}_${item.item_id}`;
                    const details = itemDetails[itemKey] || {};

                    const itemHtml = `
                                <div class="section-item" data-id="${item.id}">
                    <div class="item-handle">
                                        <i class="fas fa-grip-vertical"></i>
                                    </div>
                    <div class="item-img">
                        ${item.item_type === 'product' && details.main_image
                            ? `<img src="${details.main_image}" alt="${details.name || 'Sản phẩm'}" class="w-100 h-100 object-fit-cover">`
                            : `<i class="${item.item_type === 'product' ? 'fas fa-box' : 'fas fa-folder'}"></i>`
                        }
                    </div>
                                    <div class="flex-grow-1">
                        <strong>${details.name || 'Chưa có tên'}</strong>
                        ${item.item_type === 'product' && details.current_price
                            ? `<br><span class="text-danger">${formatCurrency(details.current_price)}đ</span>`
                            : ''
                        }
                                        </div>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete-item" data-id="${item.id}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            `;

                    itemsList.append(itemHtml);
                });

                container.append(itemsList);

                // Kích hoạt sortable cho danh sách items
                itemsList.sortable({
                    items: ".section-item",
                    handle: ".item-handle",
                    placeholder: "sortable-placeholder",
                    update: function (event, ui) {
                        updateSectionItemsOrder();
                    }
                }).disableSelection();
            }

            // Cập nhật dropdown chọn item
            function updateItemSelector(sectionType, products, categories) {
                console.log("Updating item selector for type:", sectionType);

                const selector = $('#itemSelector');
                selector.empty();
                selector.append('<option value="">-- Chọn mục --</option>');

                if (sectionType === 'product' && products && products.length > 0) {
                    // Thêm các sản phẩm vào selector
                    products.forEach(function (product) {
                        selector.append(`<option value="product_${product.id}">${product.name} - ${formatCurrency(product.current_price)}đ</option>`);
                    });
                } else if (sectionType === 'category' && categories && categories.length > 0) {
                    // Thêm các danh mục vào selector
                    categories.forEach(function (category) {
                        selector.append(`<option value="category_${category.id}">${category.name}</option>`);
                    });
                }
            }

            // Hiển thị form thêm item
            $('#addItemBtn').click(function (e) {
                e.preventDefault();
                $('#addItemFormContainer').slideDown();
            });

            // Ẩn form thêm item
            $('#cancelAddItem').click(function (e) {
                e.preventDefault();
                $('#addItemFormContainer').slideUp();
                $('#itemSelector').val('');
            });

            // Thêm item vào section
            $('#confirmAddItem').click(function (e) {
                e.preventDefault();
                console.log("Confirm add item clicked");

                const sectionId = $('#currentSectionId').val();
                const itemValue = $('#itemSelector').val();

                if (!itemValue) {
                    showAlert("Vui lòng chọn một mục để thêm", "error");
                    return;
                }

                // Kiểm tra giới hạn sản phẩm
                $.ajax({
                    url: `index.php?controller=adminhome&action=getSectionItems&section_id=${sectionId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            const section = response.section;
                            const currentItems = response.items;
                            const maxItems = parseInt(section.product_count) || 4; // Default to 4 if not set

                            console.log(`Checking item limit: Current: ${currentItems.length}, Max: ${maxItems}`);

                            // Strict check: Ensure current items count is less than configured limit
                            if (currentItems.length >= maxItems) {
                                showAlert(`Đã đạt giới hạn số lượng tối đa (${maxItems} mục) cho vùng hiển thị này. Không thể thêm mục mới.`, "error");
                                return;
                            }

                            // Nếu chưa đạt giới hạn, tiếp tục thêm item
                            // Tách loại và ID từ giá trị được chọn (format: type_id)
                            const [itemType, itemId] = itemValue.split('_');

                            // Gửi request thêm item
                            $.ajax({
                                url: 'index.php?controller=adminhome&action=addSectionItem',
                                type: 'POST',
                                data: {
                                    section_id: sectionId,
                                    item_id: itemId,
                                    item_type: itemType
                                },
                                dataType: 'json',
                                beforeSend: function () {
                                    toggleLoading(true);
                                },
                                success: function (response) {
                                    console.log("Add section item response:", response);

                                    if (response.success) {
                                        showAlert(response.message);

                                        // Render lại danh sách items
                                        renderSectionItems(response.items, response.itemDetails);

                                        // Reset form
                                        $('#addItemFormContainer').slideUp();
                                        $('#itemSelector').val('');

                                        // Check if we've reached the limit after adding
                                        if (response.items.length >= maxItems) {
                                            // Disable the add button when limit is reached
                                            $('#addItemBtn').addClass('disabled').prop('disabled', true);
                                            showAlert(`Đã đạt giới hạn số lượng tối đa (${maxItems} mục). Không thể thêm mục mới.`, "warning");
                                        }
                                    } else {
                                        showAlert(response.message || "Không thể thêm mục", "error");
                                    }
                                },
                                error: function (xhr, status, error) {
                                    handleAjaxError(xhr, status, error, 'Không thể thêm mục');
                                },
                                complete: function () {
                                    toggleLoading(false);
                                }
                            });
                        } else {
                            showAlert("Không thể kiểm tra giới hạn mục cho vùng hiển thị", "error");
                        }
                    },
                    error: function (xhr, status, error) {
                        handleAjaxError(xhr, status, error, 'Không thể kiểm tra thông tin vùng hiển thị');
                    }
                });
            });

            // Xóa item
            $(document).on("click", ".btn-delete-item", function (e) {
                e.preventDefault();
                console.log("Delete item button clicked");

                const itemId = $(this).data("id");
                const sectionId = $('#currentSectionId').val();

                if (!itemId) {
                    showAlert("Không tìm thấy ID của mục", "error");
                    return;
                }

                if (confirm("Bạn có chắc chắn muốn xóa mục này không?")) {
                    $.ajax({
                        url: 'index.php?controller=adminhome&action=deleteSectionItem',
                        type: 'POST',
                        data: {
                            id: itemId,
                            section_id: sectionId
                        },
                        dataType: 'json',
                        beforeSend: function () {
                            toggleLoading(true);
                        },
                        success: function (response) {
                            console.log("Delete section item response:", response);

                            if (response.success) {
                                showAlert(response.message);

                                // Render lại danh sách items
                                renderSectionItems(response.items, response.itemDetails);

                                // Get the section's product_count limit
                                $.ajax({
                                    url: `index.php?controller=adminhome&action=getSectionItems&section_id=${sectionId}`,
                                    type: 'GET',
                                    dataType: 'json',
                                    success: function (sectionResponse) {
                                        if (sectionResponse.success) {
                                            const maxItems = parseInt(sectionResponse.section.product_count) || 4;
                                            const currentItemCount = response.items.length;

                                            // Re-enable Add button if we're now below the limit
                                            if (currentItemCount < maxItems) {
                                                $('#addItemBtn').removeClass('disabled').prop('disabled', false);
                                            }
                                        }
                                    }
                                });
                            } else {
                                showAlert(response.message || "Không thể xóa mục", "error");
                            }
                        },
                        error: function (xhr, status, error) {
                            handleAjaxError(xhr, status, error, 'Không thể xóa mục');
                        },
                        complete: function () {
                            toggleLoading(false);
                        }
                    });
                }
            });

            // Cập nhật thứ tự các items
            function updateSectionItemsOrder() {
                console.log("Updating section items order");

                const sectionId = $('#currentSectionId').val();

                // Lấy thứ tự mới
                const positions = {};
                $('.section-item').each(function (index) {
                    positions[$(this).data('id')] = index + 1;
                });

                // Gửi request cập nhật thứ tự
                $.ajax({
                    url: 'index.php?controller=adminhome&action=updateSectionItemsOrder',
                    type: 'POST',
                    data: {
                        section_id: sectionId,
                        positions: positions
                    },
                    dataType: 'json',
                    success: function (response) {
                        console.log("Update section items order response:", response);

                        if (response.success) {
                            showAlert(response.message);
                        } else {
                            showAlert(response.message || "Không thể cập nhật thứ tự", "error");

                            // Render lại danh sách nếu có lỗi
                            if (response.items && response.itemDetails) {
                                renderSectionItems(response.items, response.itemDetails);
                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        handleAjaxError(xhr, status, error, 'Không thể cập nhật thứ tự');
                    }
                });
            }

            // -------------------------------
            // PHẦN 4: QUẢN LÝ BANNERS
            // -------------------------------

            // Xử lý chọn file ảnh cho banner thêm mới
            $('#bannerImage').change(function (e) {
                const file = e.target.files[0];

                if (!file) return;

                // Kiểm tra kích thước file
                if (file.size > 2 * 1024 * 1024) { // 2MB
                    showAlert("Kích thước file quá lớn (tối đa 2MB)", "error");
                    this.value = '';
                    return;
                }

                // Kiểm tra định dạng file
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    showAlert("Định dạng file không hợp lệ (Chỉ chấp nhận JPG, PNG, GIF, WEBP)", "error");
                    this.value = '';
                    return;
                }

                // Hiển thị cropper
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#imageToCrop').attr('src', e.target.result);
                    $('#image_upload_container').hide();
                    $('#cropperContainer').show();

                    // Hủy cropper cũ nếu có
                    if (cropper) {
                        cropper.destroy();
                    }

                    // Khởi tạo cropper mới
                    cropper = new Cropper(document.getElementById('imageToCrop'), {
                        aspectRatio: 1200 / 400,
                        viewMode: 1,
                        preview: '.preview-container .preview',
                        minContainerWidth: 300,
                        minContainerHeight: 200,
                        ready: function () {
                            console.log("Cropper ready");
                        }
                    });
                };
                reader.readAsDataURL(file);
            });

            // Xử lý nút cắt ảnh
            $('#cropImageBtn').click(function () {
                if (!cropper) return;

                const canvas = cropper.getCroppedCanvas({
                    width: 1200,
                    height: 400
                });

                if (!canvas) return;

                // Lấy định dạng từ file gốc
                const originalFile = $('#bannerImage')[0].files[0];
                let mimeType = 'image/jpeg'; // Mặc định

                if (originalFile) {
                    // Lấy định dạng từ file gốc
                    mimeType = originalFile.type;
                }

                // Chuyển ảnh sang base64 và giữ nguyên định dạng
                const imageData = canvas.toDataURL(mimeType, 0.9);

                // Lưu dữ liệu ảnh và thông tin định dạng
                $('#croppedImageData').val(imageData);

                // Hiển thị thông báo thành công
                $('#cropperContainer').hide();
                $('#image_upload_container').show();

                // Hiển thị ảnh đã cắt trong khung xem trước
                $('.preview-container.wide .preview').html('').append(
                    $('<img>').attr('src', imageData).css({
                        'width': '100%',
                        'height': '100%',
                        'object-fit': 'contain'
                    })
                );

                $('#bannerImage').nextAll('.form-text').after(
                    '<div class="mt-2 text-success"><i class="fas fa-check-circle"></i> Ảnh đã được cắt thành công</div>'
                );
            });

            // Hủy cắt ảnh
            $('#cancelCropBtn').click(function () {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                $('#imageToCrop').attr('src', '');
                $('#cropperContainer').hide();
                $('#image_upload_container').show();
                $('#bannerImage').val('');
            });

            // Xử lý chọn file ảnh cho banner chỉnh sửa
            $('#edit_banner_image').change(function (e) {
                const file = e.target.files[0];

                if (!file) return;

                // Kiểm tra kích thước file
                if (file.size > 2 * 1024 * 1024) { // 2MB
                    showAlert("Kích thước file quá lớn (tối đa 2MB)", "error");
                    this.value = '';
                    return;
                }

                // Kiểm tra định dạng file
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    showAlert("Định dạng file không hợp lệ (Chỉ chấp nhận JPG, PNG, GIF, WEBP)", "error");
                    this.value = '';
                    return;
                }

                // Hiển thị cropper
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#editImageToCrop').attr('src', e.target.result);
                    $('#edit_image_upload_container').hide();
                    $('#edit_cropperContainer').show();

                    // Hủy cropper cũ nếu có
                    if (editCropper) {
                        editCropper.destroy();
                    }

                    // Khởi tạo cropper mới
                    editCropper = new Cropper(document.getElementById('editImageToCrop'), {
                        aspectRatio: 1200 / 400,
                        viewMode: 1,
                        preview: '.preview-container .edit-preview',
                        minContainerWidth: 300,
                        minContainerHeight: 200,
                        ready: function () {
                            console.log("Edit cropper ready");
                        }
                    });
                };
                reader.readAsDataURL(file);
            });

            // Xử lý nút cắt ảnh trong edit modal
            $('#editCropImageBtn').click(function () {
                if (!editCropper) return;

                const canvas = editCropper.getCroppedCanvas({
                    width: 1200,
                    height: 400
                });

                if (!canvas) return;

                // Lấy định dạng từ file gốc
                const originalFile = $('#edit_banner_image')[0].files[0];
                let mimeType = 'image/jpeg'; // Mặc định

                if (originalFile) {
                    // Lấy định dạng từ file gốc
                    mimeType = originalFile.type;
                }

                // Chuyển ảnh sang base64 và giữ nguyên định dạng
                const imageData = canvas.toDataURL(mimeType, 0.9);
                $('#editCroppedImageData').val(imageData);

                // Hiển thị thông báo thành công
                $('#edit_cropperContainer').hide();
                $('#edit_image_upload_container').show();

                // Hiển thị ảnh đã cắt trong khung xem trước
                $('#editBannerPreview').attr('src', imageData).show();

                $('#edit_banner_image').nextAll('.form-text').after(
                    '<div class="mt-2 text-success"><i class="fas fa-check-circle"></i> Ảnh đã được cắt thành công</div>'
                );
            });

            // Hủy cắt ảnh trong edit modal
            $('#editCancelCropBtn').click(function () {
                if (editCropper) {
                    editCropper.destroy();
                    editCropper = null;
                }
                $('#editImageToCrop').attr('src', '');
                $('#edit_cropperContainer').hide();
                $('#edit_image_upload_container').show();
                $('#editBannerPreview').hide();
                $('#editCroppedImageData').val('');
                $('#edit_banner_image').val('');
                $('#edit_banner_image').nextAll('.text-success').remove();
            });

            // Xử lý các nút của cropper
            $(document).on('click', '[data-method]', function () {
                const $this = $(this);
                const data = $this.data();
                const activeCropper = $('#cropperContainer').is(':visible') ? cropper : editCropper;

                if (!activeCropper || !data.method) return;

                if (/^rotate$/.test(data.method)) {
                    activeCropper.rotate(data.option);
                } else if (/^scale/.test(data.method)) {
                    activeCropper[data.method](data.option);
                } else if (/^zoom$/.test(data.method)) {
                    activeCropper.zoom(data.option);
                } else if (data.method === 'reset') {
                    activeCropper.reset();
                }
            });

            // Thêm banner mới
            $('#addBannerForm').submit(function (e) {
                e.preventDefault();
                console.log("Add banner form submitted");

                // Kiểm tra ngày
                const startDate = new Date($('#banner_start_date').val());
                const endDate = new Date($('#banner_end_date').val());

                if (startDate > endDate) {
                    showAlert("Ngày bắt đầu phải trước ngày kết thúc", "error");
                    return;
                }

                // Kiểm tra ảnh
                const hasFileSelected = $('#bannerImage').val() !== '';
                const hasCroppedImage = $('#croppedImageData').val() !== '';

                if (!hasFileSelected && !hasCroppedImage) {
                    showAlert("Vui lòng chọn và cắt ảnh banner", "error");
                    return;
                }

                const formData = new FormData(this);

                $.ajax({
                    url: 'index.php?controller=adminhome&action=addBanner',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    beforeSend: function () {
                        toggleLoading(true);
                    },
                    success: function (response) {
                        console.log("Add banner response:", response);

                        if (response.success) {
                            showAlert(response.message);

                            // Đóng modal và reset form
                            $('#addBannerModal').modal('hide');
                            $('#addBannerForm')[0].reset();

                            // Tải lại trang sau 1s
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        } else {
                            showAlert(response.message || "Không thể thêm banner", "error");
                        }
                    },
                    error: function (xhr, status, error) {
                        handleAjaxError(xhr, status, error, 'Không thể thêm banner');
                    },
                    complete: function () {
                        toggleLoading(false);
                    }
                });
            });

            // Lấy thông tin banner để chỉnh sửa
            $(document).on('click', '.btn-edit-banner', function (e) {
                e.preventDefault();
                console.log("Edit banner button clicked");

                const bannerId = $(this).data('id');
                if (!bannerId) {
                    showAlert("Không tìm thấy ID của banner", "error");
                    return;
                }

                $.ajax({
                    url: `index.php?controller=adminhome&action=editBanner&id=${bannerId}`,
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function () {
                        toggleLoading(true);
                    },
                    success: function (banner) {
                        console.log("Banner data received:", banner);

                        // Điền dữ liệu vào form
                        $('#edit_banner_id').val(banner.id);
                        $('#edit_banner_title').val(banner.title);
                        $('#edit_banner_link_url').val(banner.link);
                        $('#edit_banner_start_date').val(banner.start_date);
                        $('#edit_banner_end_date').val(banner.end_date);
                        $('#edit_banner_meta').val(banner.meta || '');

                        // Set trạng thái hiển thị
                        $('#edit_banner_status').prop('checked', banner.hide == 0);
                        $('#edit_banner_hide').prop('checked', banner.hide == 1);

                        // Hiển thị ảnh hiện tại
                        if (banner.image_path) {
                            $('#editBannerPreview').attr('src', banner.image_path).show();
                        }

                        // Mở modal
                        openModal('editBannerModal');
                    },
                    error: function (xhr, status, error) {
                        handleAjaxError(xhr, status, error, 'Không thể tải thông tin banner');
                    },
                    complete: function () {
                        toggleLoading(false);
                    }
                });
            });

            // Cập nhật banner
            $('#editBannerForm').submit(function (e) {
                e.preventDefault();
                console.log("Edit banner form submitted");

                // Kiểm tra ngày
                const startDate = new Date($('#edit_banner_start_date').val());
                const endDate = new Date($('#edit_banner_end_date').val());

                if (startDate > endDate) {
                    showAlert("Ngày bắt đầu phải trước ngày kết thúc", "error");
                    return;
                }

                const formData = new FormData(this);

                $.ajax({
                    url: 'index.php?controller=adminhome&action=editBanner',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    beforeSend: function () {
                        toggleLoading(true);
                    },
                    success: function (response) {
                        console.log("Edit banner response:", response);

                        if (response.success) {
                            showAlert(response.message);

                            // Đóng modal
                            $('#editBannerModal').modal('hide');

                            // Tải lại trang sau 1s
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        } else {
                            showAlert(response.message || "Không thể cập nhật banner", "error");
                        }
                    },
                    error: function (xhr, status, error) {
                        handleAjaxError(xhr, status, error, 'Không thể cập nhật banner');
                    },
                    complete: function () {
                        toggleLoading(false);
                    }
                });
            });

            // Xóa banner
            $(document).on('click', '.btn-delete-banner', function (e) {
                e.preventDefault();
                console.log("Delete banner button clicked");

                const bannerId = $(this).data('id');
                if (!bannerId) {
                    showAlert("Không tìm thấy ID của banner", "error");
                    return;
                }

                if (confirm("Bạn có chắc chắn muốn xóa banner này không?")) {
                    $.ajax({
                        url: 'index.php?controller=adminhome&action=deleteBanner',
                        type: 'POST',
                        data: { id: bannerId },
                        dataType: 'json',
                        beforeSend: function () {
                            toggleLoading(true);
                        },
                        success: function (response) {
                            console.log("Delete banner response:", response);

                            if (response.success) {
                                showAlert(response.message);

                                // Xóa phần tử trên UI
                                $(`.banner-card[data-id="${bannerId}"]`).fadeOut(300, function () {
                                    $(this).remove();

                                    // Hiển thị thông báo nếu không còn banner nào
                                    if ($('#bannersContainer .banner-card').length === 0) {
                                        $('#bannersContainer').html('<div class="alert alert-info">Chưa có banner nào. Hãy thêm banner mới.</div>');
                                    }
                                });
                            } else {
                                showAlert(response.message || "Không thể xóa banner", "error");
                            }
                        },
                        error: function (xhr, status, error) {
                            handleAjaxError(xhr, status, error, 'Không thể xóa banner');
                        },
                        complete: function () {
                            toggleLoading(false);
                        }
                    });
                }
            });

            // Sắp xếp banners bằng drag & drop
            if ($("#bannersContainer").length) {
                $("#bannersContainer").sortable({
                    items: ".banner-card",
                    handle: ".item-handle",
                    placeholder: "sortable-placeholder",
                    update: function (event, ui) {
                        console.log("Reordering banners");

                        // Lấy thứ tự mới
                        const positions = {};
                        $(".banner-card").each(function (index) {
                            positions[$(this).data("id")] = index + 1;
                        });

                        // Cập nhật thứ tự trên server
                        $.ajax({
                            url: 'index.php?controller=adminhome&action=updateBannersOrder',
                            type: 'POST',
                            data: { positions: positions },
                            dataType: 'json',
                            success: function (response) {
                                if (response.success) {
                                    showAlert(response.message);
                                } else {
                                    showAlert(response.message || "Không thể cập nhật thứ tự banner", "error");
                                }
                            },
                            error: function (xhr, status, error) {
                                handleAjaxError(xhr, status, error, 'Không thể cập nhật thứ tự banner');
                            }
                        });
                    }
                }).disableSelection();
            }

            // Reset modals khi đóng
            $('#addBannerModal').on('hidden.bs.modal', function () {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                $('#imageToCrop').attr('src', '');
                $('#cropperContainer').hide();
                $('#image_upload_container').show();
                $('#bannerImage').val('');
                $('#croppedImageData').val('');
                $('#addBannerForm')[0].reset();
                $('#bannerImage').nextAll('.text-success').remove();
            });

            $('#editBannerModal').on('hidden.bs.modal', function () {
                if (editCropper) {
                    editCropper.destroy();
                    editCropper = null;
                }
                $('#editImageToCrop').attr('src', '');
                $('#edit_cropperContainer').hide();
                $('#edit_image_upload_container').show();
                $('#editBannerPreview').hide();
                $('#editCroppedImageData').val('');
                $('#edit_banner_image').val('');
                $('#edit_banner_image').nextAll('.text-success').remove();
            });

            // Add event listener for modal close to reload sections
            $('#sectionItemsModal').on('hidden.bs.modal', function () {
                // Reload sections list when the modal is closed
                loadSections();
            });

            // Add event listener for the close button in the sections items modal
            $(document).on('click', '#sectionItemsModal .btn-secondary, #sectionItemsModal .btn-close, #sectionItemsModal .close', function () {
                // Will trigger the hidden.bs.modal event above
                $('#sectionItemsModal').modal('hide');
            });



            // Phần chính sách

            /**
             * Namespace quản lý chính sách (PolicyManager)
             * Chỉ thao tác trong tab/chức năng chính sách, không ảnh hưởng vùng khác
             */
            const PolicyManager = (function () {
                let isInitialized = false;
                return {
                    init: function () {
                        if (isInitialized) return;
                        isInitialized = true;
                        this.loadPolicies();
                        this.setupEvents();
                        this.setupSortable();
                    },
                    loadPolicies: function () {
                        $.ajax({
                            url: 'index.php?controller=adminhome&action=getPolicies',
                            type: 'GET',
                            dataType: 'json',
                            success: function (response) {
                                if (response.success) {
                                    PolicyManager.renderPolicies(response.policies);
                                } else {
                                    showAlert(response.message || 'Không thể tải danh sách chính sách', 'error');
                                }
                            },
                            error: function () {
                                showAlert('Không thể kết nối đến máy chủ', 'error');
                            }
                        });
                    },
                    renderPolicies: function (policies) {
                        const container = $('#policiesContainer');
                        container.empty();
                        if (!policies || policies.length === 0) {
                            container.html('<div class="alert alert-info">Chưa có chính sách nào. Hãy thêm chính sách mới.</div>');
                            return;
                        }
                        policies.forEach(function (policy) {
                            const statusBadge = policy.hide == 0
                                ? '<span class="status-badge status-active">Đang hiển thị</span>'
                                : '<span class="status-badge status-inactive">Đã ẩn</span>';
                            const card = `
                        <div class="section-card" data-id="${policy.id}">
                            <div class="section-header d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    ${policy.image ? `<img src="${policy.image}" alt="${policy.title}" class="me-2" style="width: 30px; height: 30px; object-fit: cover;">` : '<i class="fas fa-file-alt me-2"></i>'}
                                    <h5 class="mb-0">${policy.title}</h5>
                                </div>
                                <div>
                                    ${statusBadge}
                                    <button class="btn btn-sm btn-outline-secondary btn-edit-policy" data-id="${policy.id}"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-outline-danger btn-delete-policy" data-id="${policy.id}"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            </div>
                            <div class="section-body">
                                <p><a href="${policy.link}" target="_blank" class="text-decoration-none">${policy.link}</a></p>
                                ${policy.meta ? `<p class="text-muted small">${policy.meta}</p>` : ''}
                            </div>
                        </div>`;
                            container.append(card);
                        });
                    },
                    setupEvents: function () {
                        // Thêm chính sách
                        $('#addPolicyForm').off('submit').on('submit', function (e) {
                            e.preventDefault();
                            const formData = new FormData(this);
                            $.ajax({
                                url: 'index.php?controller=adminhome&action=addPolicy',
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                dataType: 'json',
                                success: function (response) {
                                    if (response.success) {
                                        showAlert('Thêm chính sách thành công');
                                        $('#addPolicyModal').modal('hide');
                                        PolicyManager.loadPolicies();
                                    } else {
                                        showAlert(response.message || 'Không thể thêm chính sách', 'error');
                                    }
                                },
                                error: function () {
                                    showAlert('Không thể kết nối đến máy chủ', 'error');
                                }
                            });
                        });
                        // Sửa chính sách
                        $(document).off('click', '.btn-edit-policy').on('click', '.btn-edit-policy', function () {
                            const id = $(this).data('id');
                            $.ajax({
                                url: 'index.php?controller=adminhome&action=editPolicy',
                                type: 'GET',
                                data: { id: id },
                                dataType: 'json',
                                success: function (response) {
                                    if (response.success && response.policy) {
                                        const policy = response.policy;
                                        $('#edit_policy_id').val(policy.id);
                                        $('#edit_policy_title').val(policy.title);

                                        $('#edit_policy_meta').val(policy.meta);
                                        $('#edit_policy_status').prop('checked', policy.hide == 0);
                                        if (policy.image) {
                                            $('#currentPolicyImage').attr('src', policy.image).parent().show();
                                        } else {
                                            $('#currentPolicyImage').parent().hide();
                                        }
                                        $('#editPolicyModal').modal('show');
                                    } else {
                                        showAlert(response.message || 'Không thể lấy thông tin chính sách', 'error');
                                    }
                                },
                                error: function () {
                                    showAlert('Không thể kết nối đến máy chủ', 'error');
                                }
                            });
                        });
                        // Cập nhật chính sách
                        $('#editPolicyForm').off('submit').on('submit', function (e) {
                            e.preventDefault();
                            const formData = new FormData(this);
                            $.ajax({
                                url: 'index.php?controller=adminhome&action=editPolicy',
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                dataType: 'json',
                                success: function (response) {
                                    if (response.success) {
                                        showAlert('Cập nhật chính sách thành công');
                                        $('#editPolicyModal').modal('hide');
                                        PolicyManager.loadPolicies();
                                    } else {
                                        showAlert(response.message || 'Không thể cập nhật chính sách', 'error');
                                    }
                                },
                                error: function () {
                                    showAlert('Không thể kết nối đến máy chủ', 'error');
                                }
                            });
                        });
                        // Xóa chính sách
                        $(document).off('click', '.btn-delete-policy').on('click', '.btn-delete-policy', function () {
                            const id = $(this).data('id');
                            if (confirm('Bạn có chắc chắn muốn xóa chính sách này?')) {
                                $.ajax({
                                    url: 'index.php?controller=adminhome&action=deletePolicy',
                                    type: 'POST',
                                    data: { id: id },
                                    dataType: 'json',
                                    success: function (response) {
                                        if (response.success) {
                                            showAlert('Xóa chính sách thành công');
                                            PolicyManager.loadPolicies();
                                        } else {
                                            showAlert(response.message || 'Không thể xóa chính sách', 'error');
                                        }
                                    },
                                    error: function () {
                                        showAlert('Không thể kết nối đến máy chủ', 'error');
                                    }
                                });
                            }
                        });
                    },
                    setupSortable: function () {
                        $('#policiesContainer').sortable({
                            items: '.section-card',
                            handle: '.item-handle',
                            placeholder: 'sortable-placeholder',
                            update: function () {
                                const positions = {};
                                $('.section-card').each(function (index) {
                                    positions[$(this).data('id')] = index + 1;
                                });
                                $.ajax({
                                    url: 'index.php?controller=adminhome&action=updatePoliciesOrder',
                                    type: 'POST',
                                    data: { positions: positions },
                                    dataType: 'json',
                                    success: function (response) {
                                        if (response.success) {
                                            showAlert('Cập nhật thứ tự thành công');
                                        } else {
                                            showAlert(response.message || 'Không thể cập nhật thứ tự', 'error');
                                            PolicyManager.loadPolicies();
                                        }
                                    },
                                    error: function () {
                                        showAlert('Không thể kết nối đến máy chủ', 'error');
                                        PolicyManager.loadPolicies();
                                    }
                                });
                            }
                        });
                    }
                };
            })();

            // Khởi tạo khi tab policies được active
            $('#policies-tab').on('shown.bs.tab', function () {
                PolicyManager.init();
            });

            // Phần phương thức thanh toán

            /**
             * Namespace quản lý phương thức thanh toán (PaymentMethodManager)
             * Chỉ thao tác trong tab/chức năng phương thức thanh toán, không ảnh hưởng vùng khác
             */
            const PaymentMethodManager = (function () {
                let isInitialized = false;
                return {
                    init: function () {
                        if (isInitialized) return;
                        isInitialized = true;
                        this.loadPaymentMethods();
                        this.setupEvents();
                        this.setupSortable();
                    },
                    loadPaymentMethods: function () {
                        $.ajax({
                            url: 'index.php?controller=adminhome&action=getPaymentMethods',
                            type: 'GET',
                            dataType: 'json',
                            success: function (response) {
                                if (response.success) {
                                    PaymentMethodManager.renderPaymentMethods(response.data);
                                } else {
                                    showAlert(response.message || 'Không thể tải danh sách phương thức thanh toán', 'error');
                                }
                            },
                            error: function () {
                                showAlert('Không thể kết nối đến máy chủ', 'error');
                            }
                        });
                    },
                    renderPaymentMethods: function (paymentMethods) {
                        const container = $('#paymentMethodsContainer');
                        container.empty();
                        if (!paymentMethods || paymentMethods.length === 0) {
                            container.html('<div class="alert alert-info">Chưa có phương thức thanh toán nào. Hãy thêm phương thức mới.</div>');
                            return;
                        }
                        paymentMethods.forEach(function (method) {
                            const statusBadge = method.hide == 0
                                ? '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Hiển thị</span>'
                                : '<span class="badge bg-warning text-dark"><i class="fas fa-eye-slash me-1"></i>Ẩn</span>';
                            const card = `
                        <div class="section-card" data-id="${method.id}">
                            <div class="section-card-header">
                                <div class="d-flex align-items-center">
                                    <span class="item-handle"><i class="fas fa-grip-vertical"></i></span>
                                    <h6 class="section-title mb-0 ms-2">${method.title}</h6>
                                </div>
                                <div>
                                    ${statusBadge}
                                    <button class="btn btn-sm btn-outline-primary ms-1 btn-edit-payment-method" data-id="${method.id}"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-outline-danger ms-1 btn-delete-payment-method" data-id="${method.id}"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            </div>
                            <div class="section-card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <img src="${method.image}" class="img-fluid rounded" alt="${method.title}">
                                    </div>
                                    <div class="col-md-9">
                                        <div class="mb-2"><strong>Link:</strong> <a href="${method.link}" target="_blank">${method.link}</a></div>
                                        <div><strong>Mô tả:</strong> ${method.meta ? method.meta : 'Không có mô tả'}</div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                            container.append(card);
                        });
                    },
                    setupEvents: function () {
                        // Thêm phương thức thanh toán
                        $('#addPaymentMethodForm').off('submit').on('submit', function (e) {
                            e.preventDefault();
                            const formData = new FormData(this);
                            $.ajax({
                                url: 'index.php?controller=adminhome&action=addPaymentMethod',
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                dataType: 'json',
                                success: function (response) {
                                    if (response.success) {
                                        showAlert('Thêm phương thức thanh toán thành công');
                                        $('#addPaymentMethodModal').modal('hide');
                                        PaymentMethodManager.loadPaymentMethods();
                                    } else {
                                        showAlert(response.message || 'Không thể thêm phương thức thanh toán', 'error');
                                    }
                                },
                                error: function () {
                                    showAlert('Không thể kết nối đến máy chủ', 'error');
                                }
                            });
                        });
                        // Sửa phương thức thanh toán
                        $(document).off('click', '.btn-edit-payment-method').on('click', '.btn-edit-payment-method', function () {
                            const id = $(this).data('id');
                            $.ajax({
                                url: 'index.php?controller=adminhome&action=editPaymentMethod',
                                type: 'GET',
                                data: { id: id },
                                dataType: 'json',
                                success: function (response) {
                                    if (response.success && response.data) {
                                        const method = response.data;
                                        $('#edit_payment_method_id').val(method.id);
                                        $('#edit_payment_method_title').val(method.title);
                                        $('#edit_payment_method_link').val(method.link);
                                        $('#edit_payment_method_meta').val(method.meta);
                                        $('#edit_payment_method_status').prop('checked', method.hide == 0);
                                        if (method.image) {
                                            $('#editPaymentMethodPreview').attr('src', method.image).show();
                                        } else {
                                            $('#editPaymentMethodPreview').hide();
                                        }
                                        $('#editPaymentMethodModal').modal('show');
                                    } else {
                                        showAlert(response.message || 'Không thể lấy thông tin phương thức', 'error');
                                    }
                                },
                                error: function () {
                                    showAlert('Không thể kết nối đến máy chủ', 'error');
                                }
                            });
                        });
                        // Cập nhật phương thức thanh toán
                        $('#editPaymentMethodForm').off('submit').on('submit', function (e) {
                            e.preventDefault();
                            const formData = new FormData(this);
                            $.ajax({
                                url: 'index.php?controller=adminhome&action=editPaymentMethod',
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                dataType: 'json',
                                success: function (response) {
                                    if (response.success) {
                                        showAlert('Cập nhật phương thức thành công');
                                        $('#editPaymentMethodModal').modal('hide');
                                        PaymentMethodManager.loadPaymentMethods();
                                    } else {
                                        showAlert(response.message || 'Không thể cập nhật phương thức', 'error');
                                    }
                                },
                                error: function () {
                                    showAlert('Không thể kết nối đến máy chủ', 'error');
                                }
                            });
                        });
                        // Xóa phương thức thanh toán
                        $(document).off('click', '.btn-delete-payment-method').on('click', '.btn-delete-payment-method', function () {
                            const id = $(this).data('id');
                            if (confirm('Bạn có chắc chắn muốn xóa phương thức này?')) {
                                $.ajax({
                                    url: 'index.php?controller=adminhome&action=deletePaymentMethod',
                                    type: 'POST',
                                    data: { id: id },
                                    dataType: 'json',
                                    success: function (response) {
                                        if (response.success) {
                                            showAlert('Xóa phương thức thành công');
                                            PaymentMethodManager.loadPaymentMethods();
                                        } else {
                                            showAlert(response.message || 'Không thể xóa phương thức', 'error');
                                        }
                                    },
                                    error: function () {
                                        showAlert('Không thể kết nối đến máy chủ', 'error');
                                    }
                                });
                            }
                        });
                    },
                    setupSortable: function () {
                        $('#paymentMethodsContainer').sortable({
                            items: '.section-card',
                            handle: '.item-handle',
                            placeholder: 'sortable-placeholder',
                            update: function () {
                                const positions = {};
                                $('.section-card').each(function (index) {
                                    positions[$(this).data('id')] = index + 1;
                                });
                                $.ajax({
                                    url: 'index.php?controller=adminhome&action=updatePaymentMethodsOrder',
                                    type: 'POST',
                                    data: { positions: JSON.stringify(positions) },
                                    dataType: 'json',
                                    success: function (response) {
                                        if (response.success) {
                                            showAlert('Cập nhật thứ tự thành công');
                                        } else {
                                            showAlert(response.message || 'Không thể cập nhật thứ tự', 'error');
                                            PaymentMethodManager.loadPaymentMethods();
                                        }
                                    },
                                    error: function () {
                                        showAlert('Không thể kết nối đến máy chủ', 'error');
                                        PaymentMethodManager.loadPaymentMethods();
                                    }
                                });
                            }
                        });
                    }
                };
            })();

            $('#payment-methods-tab').on('shown.bs.tab', function () {
                PaymentMethodManager.init();
            });

            // Phần mạng xã hội

            /**
             * Namespace quản lý mạng xã hội (SocialMediaManager)
             * Chỉ thao tác trong tab/chức năng mạng xã hội, không ảnh hưởng vùng khác
             */
            const SocialMediaManager = (function () {
                let isInitialized = false;
                return {
                    init: function () {
                        if (isInitialized) return;
                        isInitialized = true;
                        this.loadSocialMedia();
                        this.setupEvents();
                        this.setupSortable();
                    },
                    loadSocialMedia: function () {
                        $.ajax({
                            url: 'index.php?controller=adminhome&action=getSocialMedia',
                            type: 'GET',
                            dataType: 'json',
                            success: function (response) {
                                if (response.success) {
                                    SocialMediaManager.renderSocialMedia(response.data);
                                } else {
                                    showAlert(response.message || 'Không thể tải danh sách mạng xã hội', 'error');
                                }
                            },
                            error: function () {
                                showAlert('Không thể kết nối đến máy chủ', 'error');
                            }
                        });
                    },
                    renderSocialMedia: function (socialMedias) {
                        const container = $('#socialMediaContainer');
                        container.empty();
                        if (!socialMedias || socialMedias.length === 0) {
                            container.html('<div class="alert alert-info">Chưa có mạng xã hội nào. Hãy thêm mạng xã hội mới.</div>');
                            return;
                        }
                        socialMedias.forEach(function (sm) {
                            const statusBadge = sm.hide == 0
                                ? '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Hiển thị</span>'
                                : '<span class="badge bg-warning text-dark"><i class="fas fa-eye-slash me-1"></i>Ẩn</span>';
                            const card = `
                        <div class="section-card" data-id="${sm.id}">
                            <div class="section-card-header">
                                <div class="d-flex align-items-center">
                                    <span class="item-handle"><i class="fas fa-grip-vertical"></i></span>
                                    <h6 class="section-title mb-0 ms-2">${sm.title}</h6>
                                </div>
                                <div>
                                    ${statusBadge}
                                    <button class="btn btn-sm btn-outline-primary ms-1 btn-edit-social-media" data-id="${sm.id}"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-outline-danger ms-1 btn-delete-social-media" data-id="${sm.id}"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            </div>
                            <div class="section-card-body">
                                <div class="row">
                                    <div class="col-md-1"><div class="social-icon-preview text-center"><i class="${sm.icon} fa-3x"></i></div></div>
                                    <div class="col-md-11">
                                        <div class="mb-2"><strong>Icon:</strong> <code>${sm.icon}</code></div>
                                        <div class="mb-2"><strong>Link:</strong> <a href="${sm.link}" target="_blank">${sm.link}</a></div>
                                        <div><strong>Mô tả:</strong> ${sm.meta ? sm.meta : 'Không có mô tả'}</div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                            container.append(card);
                        });
                    },
                    setupEvents: function () {
                        // Thêm mạng xã hội
                        $('#addSocialMediaForm').off('submit').on('submit', function (e) {
                            e.preventDefault();
                            const formData = $(this).serialize();
                            $.ajax({
                                url: 'index.php?controller=adminhome&action=addSocialMedia',
                                type: 'POST',
                                data: formData,
                                dataType: 'json',
                                success: function (response) {
                                    if (response.success) {
                                        showAlert('Thêm mạng xã hội thành công');
                                        $('#addSocialMediaModal').modal('hide');
                                        SocialMediaManager.loadSocialMedia();
                                    } else {
                                        showAlert(response.message || 'Không thể thêm mạng xã hội', 'error');
                                    }
                                },
                                error: function () {
                                    showAlert('Không thể kết nối đến máy chủ', 'error');
                                }
                            });
                        });
                        // Sửa mạng xã hội
                        $(document).off('click', '.btn-edit-social-media').on('click', '.btn-edit-social-media', function () {
                            const id = $(this).data('id');
                            $.ajax({
                                url: 'index.php?controller=adminhome&action=editSocialMedia',
                                type: 'GET',
                                data: { id: id },
                                dataType: 'json',
                                success: function (response) {
                                    if (response.success && response.data) {
                                        const sm = response.data;
                                        $('#edit_social_media_id').val(sm.id);
                                        $('#edit_social_media_title').val(sm.title);
                                        $('#edit_social_media_icon').val(sm.icon);
                                        $('#edit_social_media_link').val(sm.link);
                                        $('#edit_social_media_meta').val(sm.meta);
                                        $('#edit_social_media_status').prop('checked', sm.hide == 0);
                                        $('#editSocialMediaModal').modal('show');
                                    } else {
                                        showAlert(response.message || 'Không thể lấy thông tin mạng xã hội', 'error');
                                    }
                                },
                                error: function () {
                                    showAlert('Không thể kết nối đến máy chủ', 'error');
                                }
                            });
                        });
                        // Cập nhật mạng xã hội
                        $('#editSocialMediaForm').off('submit').on('submit', function (e) {
                            e.preventDefault();
                            const formData = $(this).serialize();
                            $.ajax({
                                url: 'index.php?controller=adminhome&action=editSocialMedia',
                                type: 'POST',
                                data: formData,
                                dataType: 'json',
                                success: function (response) {
                                    if (response.success) {
                                        showAlert('Cập nhật mạng xã hội thành công');
                                        $('#editSocialMediaModal').modal('hide');
                                        SocialMediaManager.loadSocialMedia();
                                    } else {
                                        showAlert(response.message || 'Không thể cập nhật mạng xã hội', 'error');
                                    }
                                },
                                error: function () {
                                    showAlert('Không thể kết nối đến máy chủ', 'error');
                                }
                            });
                        });
                        // Xóa mạng xã hội
                        $(document).off('click', '.btn-delete-social-media').on('click', '.btn-delete-social-media', function () {
                            const id = $(this).data('id');
                            if (confirm('Bạn có chắc chắn muốn xóa mạng xã hội này?')) {
                                $.ajax({
                                    url: 'index.php?controller=adminhome&action=deleteSocialMedia',
                                    type: 'POST',
                                    data: { id: id },
                                    dataType: 'json',
                                    success: function (response) {
                                        if (response.success) {
                                            showAlert('Xóa mạng xã hội thành công');
                                            SocialMediaManager.loadSocialMedia();
                                        } else {
                                            showAlert(response.message || 'Không thể xóa mạng xã hội', 'error');
                                        }
                                    },
                                    error: function () {
                                        showAlert('Không thể kết nối đến máy chủ', 'error');
                                    }
                                });
                            }
                        });
                    },
                    setupSortable: function () {
                        $('#socialMediaContainer').sortable({
                            items: '.section-card',
                            handle: '.item-handle',
                            placeholder: 'sortable-placeholder',
                            update: function () {
                                const positions = [];
                                $('.section-card').each(function (index) {
                                    positions.push({ id: $(this).data('id'), order: index + 1 });
                                });
                                $.ajax({
                                    url: 'index.php?controller=adminhome&action=updateSocialMediaOrder',
                                    type: 'POST',
                                    data: { positions: JSON.stringify(positions) },
                                    dataType: 'json',
                                    success: function (response) {
                                        if (response.success) {
                                            showAlert('Cập nhật thứ tự thành công');
                                        } else {
                                            showAlert(response.message || 'Không thể cập nhật thứ tự', 'error');
                                            SocialMediaManager.loadSocialMedia();
                                        }
                                    },
                                    error: function () {
                                        showAlert('Không thể kết nối đến máy chủ', 'error');
                                        SocialMediaManager.loadSocialMedia();
                                    }
                                });
                            }
                        });
                    }
                };
            })();

            $('#social-media-tab').on('shown.bs.tab', function () {
                SocialMediaManager.init();
            });

            // Xử lý nút chọn icon cho cả form thêm và sửa
            $('.icon-picker-btn').on('click', function () {
                // Lưu lại input target để biết đang thao tác với form nào
                $('#iconPickerModal').data('target', $(this).data('target'));
                $('#iconPickerModal').modal('show');
            });
            // Khi chọn icon trong modal
            $(document).on('click', '.icon-item', function () {
                var iconClass = $(this).data('icon');
                var targetInput = $('#iconPickerModal').data('target');
                $('#' + targetInput).val(iconClass).trigger('input');
                $('#iconPickerModal').modal('hide');
            });
            // Đảm bảo phần xem trước icon luôn cập nhật khi input thay đổi
            $('#social_media_icon').on('input', function () {
                $('#iconPreview').attr('class', $(this).val() + ' fa-2x');
            });
            $('#edit_social_media_icon').on('input', function () {
                $('#editIconPreview').attr('class', $(this).val() + ' fa-2x');
            });
        });
    </script>

    <!-- Modal chọn icon mạng xã hội -->
    <div class="modal fade" id="iconPickerModal" tabindex="-1" aria-labelledby="iconPickerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="iconPickerModalLabel">Chọn icon mạng xã hội</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-wrap gap-3 icon-picker-container">
                        <div class="icon-item" data-icon="fab fa-facebook"><i class="fab fa-facebook fa-2x"></i></div>
                        <div class="icon-item" data-icon="fab fa-instagram"><i class="fab fa-instagram fa-2x"></i></div>
                        <div class="icon-item" data-icon="fab fa-youtube"><i class="fab fa-youtube fa-2x"></i></div>
                        <div class="icon-item" data-icon="fab fa-tiktok"><i class="fab fa-tiktok fa-2x"></i></div>
                        <div class="icon-item" data-icon="fab fa-twitter"><i class="fab fa-twitter fa-2x"></i></div>
                        <div class="icon-item" data-icon="fab fa-linkedin"><i class="fab fa-linkedin fa-2x"></i></div>
                        <div class="icon-item" data-icon="fab fa-telegram"><i class="fab fa-telegram fa-2x"></i></div>
                        <div class="icon-item" data-icon="fab fa-pinterest"><i class="fab fa-pinterest fa-2x"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Policy Modal -->
    <div class="modal fade" id="editPolicyModal" tabindex="-1" aria-labelledby="editPolicyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPolicyModalLabel">Chỉnh sửa chính sách</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editPolicyForm" enctype="multipart/form-data" method="post" action="javascript:void(0);">
                        <input type="hidden" id="edit_policy_id" name="id">
                        <div class="mb-3">
                            <label for="edit_policy_title" class="form-label">Tiêu đề <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_policy_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_policy_image" class="form-label">Ảnh (tùy chọn)</label>
                            <input type="file" class="form-control" id="edit_policy_image" name="image_upload"
                                accept="image/*">
                            <div class="form-text">Hình ảnh đại diện cho chính sách (nếu có), tối đa 1MB</div>
                            <div class="mt-2">
                                <img id="currentPolicyImage" src="" alt="Preview"
                                    style="max-width: 100%; max-height: 200px; display: none;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_policy_meta" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="edit_policy_meta" name="meta" rows="2"></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="edit_policy_status" name="status">
                            <label class="form-check-label" for="edit_policy_status">
                                Hiển thị
                            </label>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>