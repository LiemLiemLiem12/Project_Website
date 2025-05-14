<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cài Đặt - SR Store</title>
    <!-- Bootstrap CSS -->
    <link href="/Project_Website/ProjectWeb/layout/cssBootstrap/bootstrap.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Admin.css">
    <style>
        .settings-container {
            padding: 20px;
        }
        
        .settings-card {
            margin-bottom: 30px;
        }
        
        .settings-card .card-header {
            background-color: #f8f9fa;
            font-weight: 500;
        }
        
        .setting-item {
            margin-bottom: 20px;
        }
        
        .setting-item label {
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .setting-item .form-text {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .preview-image {
            max-width: 150px;
            max-height: 100px;
            margin-top: 10px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        
        @media (max-width: 768px) {
            .settings-tabs {
                flex-direction: column;
            }
            
            .settings-tabs .nav-link {
                margin-bottom: 5px;
                border-radius: 4px !important;
            }
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
                <div class="header-right" style="display: flex; align-items: center; gap: 1rem; margin-left: auto; position: relative;">
                    <div class="notification" id="notificationBell" style="position: relative; cursor: pointer;">
                    </div>
                    <div class="profile">
                        <img src="/Project_Website/ProjectWeb/upload/img/avatar.jpg" alt="Admin Avatar" class="profile-image">
                    </div>
                </div>
            </header>

            <!-- Settings Content -->
            <div class="settings-container">
                <div class="page-header">
                    <h1>Cài Đặt Hệ Thống</h1>
                </div>
                
                <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Cài đặt đã được cập nhật thành công!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <ul class="nav nav-tabs mb-4 settings-tabs" id="settingsTabs" role="tablist">
                    <?php 
                    $firstTab = true;
                    foreach ($settingGroups as $groupName => $groupSettings): 
                        $groupLabel = ucfirst($groupName);
                        switch($groupName) {
                            case 'general': $groupLabel = 'Thông tin chung'; $icon = 'fa-gear'; break;
                            case 'contact': $groupLabel = 'Liên hệ'; $icon = 'fa-envelope'; break;
                            case 'appearance': $groupLabel = 'Giao diện'; $icon = 'fa-palette'; break;
                            case 'shop': $groupLabel = 'Cửa hàng'; $icon = 'fa-store'; break;
                            case 'system': $groupLabel = 'Hệ thống'; $icon = 'fa-server'; break;
                            default: $icon = 'fa-list';
                        }
                    ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo $firstTab ? 'active' : ''; ?>" 
                                id="<?php echo $groupName; ?>-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#<?php echo $groupName; ?>-pane" 
                                type="button" 
                                role="tab" 
                                aria-controls="<?php echo $groupName; ?>-pane" 
                                aria-selected="<?php echo $firstTab ? 'true' : 'false'; ?>">
                            <i class="fas <?php echo $icon; ?> me-2"></i><?php echo $groupLabel; ?>
                        </button>
                    </li>
                    <?php $firstTab = false; endforeach; ?>
                </ul>
                
                <form action="index.php?controller=adminsetting&action=update" method="post" enctype="multipart/form-data">
                    <div class="tab-content" id="settingsTabContent">
                        <?php 
                        $firstTab = true;
                        foreach ($settingGroups as $groupName => $groupSettings): 
                        ?>
                        <div class="tab-pane fade <?php echo $firstTab ? 'show active' : ''; ?>" 
                             id="<?php echo $groupName; ?>-pane" 
                             role="tabpanel" 
                             aria-labelledby="<?php echo $groupName; ?>-tab" 
                             tabindex="0">
                            
                            <div class="card settings-card">
                                <div class="card-body">
                                    <?php foreach ($groupSettings as $setting): ?>
                                    <div class="setting-item">
                                        <label for="setting_<?php echo $setting['setting_key']; ?>" class="form-label">
                                            <?php echo $setting['setting_label']; ?>
                                        </label>
                                        
                                        <?php if ($setting['setting_type'] === 'text' || $setting['setting_type'] === 'email' || $setting['setting_type'] === 'number'): ?>
                                        <input type="<?php echo $setting['setting_type']; ?>" 
                                               class="form-control" 
                                               id="setting_<?php echo $setting['setting_key']; ?>" 
                                               name="setting_<?php echo $setting['setting_key']; ?>"
                                               value="<?php echo htmlspecialchars($setting['setting_value']); ?>">
                                        
                                        <?php elseif ($setting['setting_type'] === 'textarea'): ?>
                                        <textarea class="form-control" 
                                                  id="setting_<?php echo $setting['setting_key']; ?>" 
                                                  name="setting_<?php echo $setting['setting_key']; ?>" 
                                                  rows="3"><?php echo htmlspecialchars($setting['setting_value']); ?></textarea>
                                        
                                        <?php elseif ($setting['setting_type'] === 'boolean'): ?>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   role="switch" 
                                                   id="setting_<?php echo $setting['setting_key']; ?>" 
                                                   name="setting_<?php echo $setting['setting_key']; ?>" 
                                                   value="1" 
                                                   <?php echo $setting['setting_value'] == '1' ? 'checked' : ''; ?>>
                                        </div>
                                        
                                        <?php elseif ($setting['setting_type'] === 'select'): ?>
                                        <select class="form-select" 
                                                id="setting_<?php echo $setting['setting_key']; ?>" 
                                                name="setting_<?php echo $setting['setting_key']; ?>">
                                            <?php 
                                            if ($setting['setting_key'] === 'currency') {
                                                $options = ['VND' => 'VNĐ', 'USD' => 'USD', 'EUR' => 'EUR'];
                                                foreach ($options as $value => $label) {
                                                    $selected = $setting['setting_value'] === $value ? 'selected' : '';
                                                    echo "<option value=\"{$value}\" {$selected}>{$label}</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                        
                                        <?php elseif ($setting['setting_type'] === 'file'): ?>
                                        <input type="file" 
                                               class="form-control mb-2" 
                                               id="setting_<?php echo $setting['setting_key']; ?>" 
                                               name="setting_<?php echo $setting['setting_key']; ?>">
                                        
                                        <?php if (!empty($setting['setting_value'])): ?>
                                        <div>
                                            <p class="mb-1">File hiện tại:</p>
                                            <?php if (strpos($setting['setting_key'], 'logo') !== false || strpos($setting['setting_key'], 'favicon') !== false): ?>
                                            <img src="<?php echo $setting['setting_value']; ?>" alt="Preview" class="preview-image">
                                            <?php else: ?>
                                            <a href="<?php echo $setting['setting_value']; ?>" target="_blank"><?php echo basename($setting['setting_value']); ?></a>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($setting['setting_description'])): ?>
                                        <div class="form-text"><?php echo $setting['setting_description']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php $firstTab = false; endforeach; ?>
                    </div>
                    
                    <div class="d-flex justify-content-end mb-5">
                        <button type="submit" name="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Lưu cài đặt
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS with Popper -->
    <script src="/Project_Website/ProjectWeb/layout/jsBootstrap/bootstrap.js"></script>
    <!-- Custom JavaScript -->
    <script src="/Project_Website/ProjectWeb/layout/js/Admin.js"></script>
    <script>
        // Script xử lý preview file khi upload
        document.addEventListener('DOMContentLoaded', function() {
            const fileInputs = document.querySelectorAll('input[type="file"]');
            
            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const previewContainer = this.nextElementSibling;
                    
                    if (previewContainer && this.files && this.files[0]) {
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            // Kiểm tra nếu previewContainer chứa thẻ img
                            const img = previewContainer.querySelector('img');
                            if (img) {
                                img.src = e.target.result;
                            }
                        };
                        
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            });
        });
    </script>
</body>

</html>