<?php
require_once 'Models/AdminHomeModel.php';

class AdminHomeController {
    private $model;
    private $bannerUploadPath = 'upload/img/Home/';
    private $footerUploadPath = 'upload/img/Footer/';
    private $bannerMaxWidth = 1200;
    private $bannerMaxHeight = 400;
    
    public function __construct() {
        $this->model = new AdminHomeModel();
        
        // Đảm bảo thư mục upload tồn tại
        if (!file_exists($this->bannerUploadPath)) {
            mkdir($this->bannerUploadPath, 0777, true);
        }
    }
    
    public function index() {
        // Hiển thị trang quản lý trang chủ
        $sections = $this->model->getAllSections();
        $banners = $this->model->getAllBanners();
        $policies = $this->model->getAllPolicies();
        $socialMedias = $this->model->getAllSocialMedia();
        $paymentMethods = $this->model->getAllPaymentMethods();
        
        require_once __DIR__ . '/../Views/frontend/admin/AdminHome/index.php';
    }
    
    // QUẢN LÝ SECTIONS
    
    public function addSection() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $sectionData = [
                    'title' => $_POST['title'] ?? '',
                    'section_type' => $_POST['section_type'] ?? 'product',
                    'display_style' => $_POST['display_style'] ?? 'grid',
                    'product_count' => $_POST['product_count'] ?? 4,
                    'hide' => isset($_POST['status']) ? 0 : 1, // Bật status = hide=0, ẩn status = hide=1
                    'link' => $_POST['link'] ?? '',
                    'meta' => $_POST['meta'] ?? ''
                ];
                
                // Validate data
                if (empty($sectionData['title'])) {
                    throw new Exception("Tiêu đề vùng hiển thị không được để trống");
                }
                
                $sectionId = $this->model->createSection($sectionData);
                
                if ($sectionId) {
                    $this->sendJsonResponse(['success' => true, 'id' => $sectionId, 'message' => 'Thêm vùng hiển thị thành công']);
                } else {
                    throw new Exception("Không thể thêm vùng hiển thị");
                }
            } catch (Exception $e) {
                $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            // Redirect to index if not POST request
            header('Location: index.php?controller=adminhome');
        }
    }
    
    public function editSection() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id'] ?? 0;
                
                if (!$id) {
                    throw new Exception("ID không hợp lệ");
                }
                
                $sectionData = [
                    'title' => $_POST['title'] ?? '',
                    'section_type' => $_POST['section_type'] ?? 'product',
                    'display_style' => $_POST['display_style'] ?? 'grid',
                    'product_count' => $_POST['product_count'] ?? 4,
                    'hide' => isset($_POST['status']) ? 0 : 1, // Hiển thị = 0, Ẩn = 1
                    'link' => $_POST['link'] ?? '',
                    'meta' => $_POST['meta'] ?? ''
                ];
                
                // Validate data
                if (empty($sectionData['title'])) {
                    throw new Exception("Tiêu đề vùng hiển thị không được để trống");
                }
                
                $success = $this->model->updateSection($id, $sectionData);
                
                if ($success) {
                    $this->sendJsonResponse(['success' => true, 'message' => 'Cập nhật vùng hiển thị thành công']);
                } else {
                    throw new Exception("Không thể cập nhật vùng hiển thị");
                }
            } catch (Exception $e) {
                $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            // Lấy thông tin section để hiển thị trong form edit
            $id = $_GET['id'] ?? 0;
            
            if (!$id) {
                header('Location: index.php?controller=adminhome');
                return;
            }
            
            $section = $this->model->getSectionById($id);
            
            if (!$section) {
                header('Location: index.php?controller=adminhome');
                return;
            }
            
            $this->sendJsonResponse($section);
        }
    }
    
    public function deleteSection() {
        try {
            $id = $_POST['id'] ?? 0;
            
            if (!$id) {
                throw new Exception("ID không hợp lệ");
            }
            
            $success = $this->model->deleteSection($id);
            
            if ($success) {
                $this->sendJsonResponse(['success' => true, 'message' => 'Xóa vùng hiển thị thành công']);
            } else {
                throw new Exception("Không thể xóa vùng hiển thị");
            }
        } catch (Exception $e) {
            $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function updateSectionsOrder() {
        try {
            $positions = $_POST['positions'] ?? [];
            
            if (empty($positions)) {
                throw new Exception("Dữ liệu vị trí không hợp lệ");
            }
            
            $success = $this->model->updateSectionOrder($positions);
            
            if ($success) {
                $this->sendJsonResponse(['success' => true, 'message' => 'Cập nhật thứ tự vùng hiển thị thành công']);
            } else {
                throw new Exception("Không thể cập nhật thứ tự vùng hiển thị");
            }
        } catch (Exception $e) {
            $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // QUẢN LÝ SECTION ITEMS
    
    public function getSectionItems() {
        try {
            $sectionId = $_GET['section_id'] ?? 0;
            
            if (!$sectionId) {
                throw new Exception("ID section không hợp lệ");
            }
            
            $section = $this->model->getSectionById($sectionId);
            
            if (!$section) {
                throw new Exception("Không tìm thấy section");
            }
            
            $items = $this->model->getSectionItems($sectionId);
            $itemDetails = $this->model->getItemDetails($items);
            
            // Nếu là section loại sản phẩm, lấy tất cả sản phẩm
            if ($section['section_type'] == 'product') {
                $allProducts = $this->model->getAllProducts();
            } else {
                $allCategories = $this->model->getAllCategories();
            }
            
            $this->sendJsonResponse([
                'success' => true,
                'section' => $section,
                'items' => $items,
                'itemDetails' => $itemDetails,
                'allProducts' => $allProducts ?? [],
                'allCategories' => $allCategories ?? []
            ]);
        } catch (Exception $e) {
            $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function addSectionItem() {
        try {
            $sectionId = $_POST['section_id'] ?? 0;
            $itemId = $_POST['item_id'] ?? 0;
            $itemType = $_POST['item_type'] ?? '';
            
            if (!$sectionId || !$itemId || !$itemType) {
                throw new Exception("Dữ liệu không hợp lệ");
            }
            
            $success = $this->model->addSectionItem($sectionId, $itemId, $itemType);
            
            if ($success) {
                // Lấy lại danh sách items sau khi thêm
                $items = $this->model->getSectionItems($sectionId);
                $itemDetails = $this->model->getItemDetails($items);
                
                $this->sendJsonResponse([
                    'success' => true, 
                    'message' => 'Thêm item thành công',
                    'items' => $items,
                    'itemDetails' => $itemDetails
                ]);
            } else {
                throw new Exception("Không thể thêm item");
            }
        } catch (Exception $e) {
            $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function deleteSectionItem() {
        try {
            $id = $_POST['id'] ?? 0;
            $sectionId = $_POST['section_id'] ?? 0;
            
            if (!$id || !$sectionId) {
                throw new Exception("Dữ liệu không hợp lệ");
            }
            
            $success = $this->model->deleteSectionItem($id);
            
            if ($success) {
                // Lấy lại danh sách items sau khi xóa
                $items = $this->model->getSectionItems($sectionId);
                $itemDetails = $this->model->getItemDetails($items);
                
                $this->sendJsonResponse([
                    'success' => true, 
                    'message' => 'Xóa item thành công',
                    'items' => $items,
                    'itemDetails' => $itemDetails
                ]);
            } else {
                throw new Exception("Không thể xóa item");
            }
        } catch (Exception $e) {
            $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function updateSectionItemsOrder() {
        try {
            $positions = $_POST['positions'] ?? [];
            $sectionId = $_POST['section_id'] ?? 0;
            
            if (empty($positions) || !$sectionId) {
                throw new Exception("Dữ liệu không hợp lệ");
            }
            
            $success = $this->model->updateSectionItemOrder($positions);
            
            if ($success) {
                // Lấy lại danh sách items sau khi cập nhật
                $items = $this->model->getSectionItems($sectionId);
                $itemDetails = $this->model->getItemDetails($items);
                
                $this->sendJsonResponse([
                    'success' => true, 
                    'message' => 'Cập nhật thứ tự thành công',
                    'items' => $items,
                    'itemDetails' => $itemDetails
                ]);
            } else {
                throw new Exception("Không thể cập nhật thứ tự");
            }
        } catch (Exception $e) {
            $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // QUẢN LÝ BANNERS
    
    public function addBanner() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Kiểm tra có upload hình ảnh hoặc có ảnh đã cắt không
                if (empty($_FILES['image']['name']) && (empty($_POST['image']) || strpos($_POST['image'], 'data:image/') !== 0)) {
                    throw new Exception("Vui lòng chọn hình ảnh banner");
                }
                
                // Xử lý upload hình ảnh
                $targetDir = $this->bannerUploadPath;
                
                // Kiểm tra nếu có dữ liệu ảnh đã cắt từ cropper
                if (!empty($_POST['image']) && strpos($_POST['image'], 'data:image/') === 0) {
                    // Xử lý ảnh đã cắt từ cropper
                    $imageData = $_POST['image'];
                    $imageInfo = $this->getImageInfoFromBase64($imageData);
                    $extension = $imageInfo['extension'];
                    
                    // Tạo tên file mới
                    $fileName = time() . '_cropped.' . $extension;
                    $targetFile = $targetDir . $fileName;
                    
                    // Lưu file ảnh từ base64
                    $decoded = $this->decodeBase64Image($imageData);
                    if (!file_put_contents($targetFile, $decoded)) {
                        throw new Exception("Không thể lưu ảnh đã cắt");
                    }
                    
                    $resizedImagePath = $targetFile; // Vẫn lưu đường dẫn đầy đủ cho file system
                } else {
                    // Xử lý upload file thông thường
                    $fileName = time() . '_' . basename($_FILES['image']['name']);
                    $targetFile = $targetDir . $fileName;
                    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                    
                    // Kiểm tra định dạng file
                    $allowedFormats = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    if (!in_array($imageFileType, $allowedFormats)) {
                        throw new Exception("Chỉ chấp nhận file hình ảnh (JPG, JPEG, PNG, GIF, WEBP)");
                    }
                    
                    // Kiểm tra kích thước file (max 2MB)
                    if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                        throw new Exception("Kích thước file không được vượt quá 2MB");
                    }
                    
                    // Upload file tạm thời
                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                        throw new Exception("Không thể upload hình ảnh");
                    }

                    // Điều chỉnh kích thước ảnh thành 1200x400 pixel
                    $resizedImagePath = $this->resizeImage($targetFile, $this->bannerMaxWidth, $this->bannerMaxHeight);
                    if (!$resizedImagePath) {
                        throw new Exception("Không thể xử lý hình ảnh");
                    }
                    // resizedImagePath vẫn chứa đường dẫn đầy đủ để lưu file vào hệ thống
                }
                
                // Dữ liệu banner
                $bannerData = [
                    'title' => $_POST['title'] ?? '',
                    'link' => $_POST['link_url'] ?? '#', // Chuyển đổi link_url sang link
                    'start_date' => $_POST['start_date'] ?? date('Y-m-d'),
                    'end_date' => $_POST['end_date'] ?? date('Y-m-d', strtotime('+30 days')),
                    'hide' => isset($_POST['status']) ? 0 : 1, // Hiển thị = 0, Ẩn = 1
                    'meta' => $_POST['meta'] ?? ''
                ];
                
                // Validate dữ liệu
                if (empty($bannerData['title'])) {
                    throw new Exception("Tiêu đề banner không được để trống");
                }
                
                // Thêm banner vào database
                // Lấy chỉ tên file, không lấy đường dẫn
                $fileName = basename($resizedImagePath);
                
                $bannerId = $this->model->createBanner($bannerData, $fileName);
                
                if ($bannerId) {
                    $this->sendJsonResponse(['success' => true, 'id' => $bannerId, 'message' => 'Thêm banner thành công']);
                } else {
                    // Xóa file hình ảnh đã upload nếu thêm vào database thất bại
                    if (file_exists($resizedImagePath)) {
                        unlink($resizedImagePath);
                    }
                    throw new Exception("Không thể thêm banner");
                }
                
            } catch (Exception $e) {
                $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            // Redirect to index if not POST request
            header('Location: index.php?controller=adminhome');
        }
    }
    
    public function editBanner() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id'] ?? 0;
                
                if (!$id) {
                    throw new Exception("ID không hợp lệ");
                }
                
                // Lấy thông tin banner hiện tại
                $banner = $this->model->getBannerById($id);
                
                if (!$banner) {
                    throw new Exception("Không tìm thấy banner");
                }
                
                // Dữ liệu banner
                $bannerData = [
                    'title' => $_POST['title'] ?? '',
                    'link' => $_POST['link_url'] ?? '#', // Chuyển đổi link_url sang link
                    'start_date' => $_POST['start_date'] ?? date('Y-m-d'),
                    'end_date' => $_POST['end_date'] ?? date('Y-m-d', strtotime('+30 days')),
                    'hide' => isset($_POST['status']) ? 0 : 1, // Hiển thị = 0, Ẩn = 1
                    'meta' => $_POST['meta'] ?? ''
                ];
                
                // Validate dữ liệu
                if (empty($bannerData['title'])) {
                    throw new Exception("Tiêu đề banner không được để trống");
                }
                
                $imagePath = null;
                
                // Kiểm tra nếu có dữ liệu ảnh đã cắt từ cropper
                if (!empty($_POST['image']) && strpos($_POST['image'], 'data:image/') === 0) {
                    // Xử lý ảnh đã cắt từ cropper
                    $imageData = $_POST['image'];
                    $imageInfo = $this->getImageInfoFromBase64($imageData);
                    $extension = $imageInfo['extension'];
                    
                    // Tạo tên file mới
                    $fileName = time() . '_cropped.' . $extension;
                    $targetDir = $this->bannerUploadPath;
                    $targetFile = $targetDir . $fileName;
                    
                    // Lưu file ảnh từ base64
                    $decoded = $this->decodeBase64Image($imageData);
                    if (!file_put_contents($targetFile, $decoded)) {
                        throw new Exception("Không thể lưu ảnh đã cắt");
                    }
                    
                    $imagePath = $targetFile; // Vẫn lưu đường dẫn đầy đủ cho file system
                    
                    // Nếu có ảnh mới, xóa ảnh cũ
                    if (!empty($banner['image_path']) && file_exists($banner['image_path'])) {
                        @unlink($banner['image_path']);
                    }
                } 
                // Kiểm tra có upload hình ảnh mới không
                else if (!empty($_FILES['image']['name'])) {
                    // Xử lý upload hình ảnh
                    $targetDir = $this->bannerUploadPath;
                    $fileName = time() . '_' . basename($_FILES['image']['name']);
                    $targetFile = $targetDir . $fileName;
                    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                    
                    // Kiểm tra định dạng file
                    $allowedFormats = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    if (!in_array($imageFileType, $allowedFormats)) {
                        throw new Exception("Chỉ chấp nhận file hình ảnh (JPG, JPEG, PNG, GIF, WEBP)");
                    }
                    
                    // Kiểm tra kích thước file (max 2MB)
                    if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                        throw new Exception("Kích thước file không được vượt quá 2MB");
                    }
                    
                    // Upload file tạm thời
                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                        throw new Exception("Không thể upload hình ảnh");
                    }
                    
                    // Điều chỉnh kích thước ảnh thành 1200x400 pixel
                    $imagePath = $this->resizeImage($targetFile, $this->bannerMaxWidth, $this->bannerMaxHeight);
                    if (!$imagePath) {
                        throw new Exception("Không thể xử lý hình ảnh");
                    }
                    
                    // Nếu có ảnh mới, xóa ảnh cũ
                    if (!empty($banner['image_path']) && file_exists($banner['image_path'])) {
                        @unlink($banner['image_path']);
                    }
                }
                
                // Cập nhật banner trong database - lấy tên file thay vì đường dẫn đầy đủ
                $fileNameOnly = isset($imagePath) ? basename($imagePath) : null;
                $success = $this->model->updateBanner($id, $bannerData, $fileNameOnly);
                
                if ($success) {
                    $this->sendJsonResponse(['success' => true, 'message' => 'Cập nhật banner thành công']);
                } else {
                    // Xóa file hình ảnh đã upload nếu cập nhật database thất bại
                    if (isset($imagePath) && file_exists($imagePath)) {
                        @unlink($imagePath);
                    }
                    throw new Exception("Không thể cập nhật banner");
                }
                
            } catch (Exception $e) {
                $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            // Lấy thông tin banner để hiển thị trong form edit
            $id = $_GET['id'] ?? 0;
            
            if (!$id) {
                header('Location: index.php?controller=adminhome');
                return;
            }
            
            $banner = $this->model->getBannerById($id);
            
            if (!$banner) {
                header('Location: index.php?controller=adminhome');
                return;
            }
            
            $this->sendJsonResponse($banner);
        }
    }
    
    public function deleteBanner() {
        try {
            $id = $_POST['id'] ?? 0;
            
            if (!$id) {
                throw new Exception("ID không hợp lệ");
            }
            
            // Xóa banner và lấy đường dẫn ảnh để xóa file
            $imagePath = $this->model->deleteBanner($id);
            
            if ($imagePath) {
                // Xóa file hình ảnh từ server
                if (file_exists($imagePath)) {
                    @unlink($imagePath);
                }
                
                $this->sendJsonResponse(['success' => true, 'message' => 'Xóa banner thành công']);
            } else {
                throw new Exception("Không thể xóa banner");
            }
        } catch (Exception $e) {
            $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function updateBannersOrder() {
        try {
            $positions = $_POST['positions'] ?? [];
            
            if (empty($positions)) {
                throw new Exception("Dữ liệu vị trí không hợp lệ");
            }
            
            $success = $this->model->updateBannerOrder($positions);
            
            if ($success) {
                $this->sendJsonResponse(['success' => true, 'message' => 'Cập nhật thứ tự banner thành công']);
            } else {
                throw new Exception("Không thể cập nhật thứ tự banner");
            }
        } catch (Exception $e) {
            $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // PHƯƠNG THỨC QUẢN LÝ POLICIES
    
    public function getPolicies() {
        try {
            $policies = $this->model->getAllPolicies();
            $this->sendJsonResponse(['success' => true, 'policies' => $policies]);
        } catch (Exception $e) {
            $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function addPolicy() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Lấy dữ liệu từ form
                $policyData = [
                    'title' => $_POST['title'] ?? '',
                    'status' => isset($_POST['status']), // Status là checkbox
                    'meta' => $_POST['meta'] ?? ''
                ];
                
                // Validate dữ liệu
                if (empty($policyData['title'])) {
                    throw new Exception("Tiêu đề chính sách không được để trống");
                }
                
                $imagePath = null;
                
                // Xử lý upload hình ảnh nếu có
                if (!empty($_FILES['image_upload']['name'])) {
                    $targetDir = $this->footerUploadPath;
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    $fileName = time() . '_' . basename($_FILES['image_upload']['name']);
                    $targetFile = $targetDir . $fileName;
                    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                    $allowedFormats = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    if (!in_array($imageFileType, $allowedFormats)) {
                        throw new Exception("Chỉ chấp nhận file hình ảnh (JPG, JPEG, PNG, GIF, WEBP)");
                    }
                    if ($_FILES['image_upload']['size'] > 1 * 1024 * 1024) {
                        throw new Exception("Kích thước file không được vượt quá 1MB");
                    }
                    if (!move_uploaded_file($_FILES['image_upload']['tmp_name'], $targetFile)) {
                        throw new Exception("Không thể upload hình ảnh");
                    }
                    $imagePath = $fileName;
                } else if (!empty($_POST['image']) && strpos($_POST['image'], 'data:image/') === 0) {
                    $imageData = $_POST['image'];
                    $imageInfo = $this->getImageInfoFromBase64($imageData);
                    $extension = $imageInfo['extension'];
                    $targetDir = $this->footerUploadPath;
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    $fileName = time() . '_cropped.' . $extension;
                    $targetFile = $targetDir . $fileName;
                    $decoded = $this->decodeBase64Image($imageData);
                    if (!file_put_contents($targetFile, $decoded)) {
                        throw new Exception("Không thể lưu ảnh đã cắt");
                    }
                    $imagePath = $fileName;
                }
                
                // Thêm vào cơ sở dữ liệu
                $policyId = $this->model->createPolicy($policyData, $imagePath);
                
                if ($policyId) {
                    $this->sendJsonResponse([
                        'success' => true, 
                        'message' => 'Thêm chính sách thành công', 
                        'policy_id' => $policyId
                    ]);
                } else {
                    // Xóa file hình ảnh đã upload nếu thêm vào database thất bại
                    if ($imagePath && file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                    throw new Exception("Không thể thêm chính sách");
                }
                
            } catch (Exception $e) {
                $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            // Nếu không phải POST request, chuyển hướng về trang chính
            header('Location: index.php?controller=adminhome');
        }
    }
    
    public function editPolicy() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id'] ?? 0;
                
                if (!$id) {
                    throw new Exception("ID không hợp lệ");
                }
                
                // Lấy thông tin policy hiện tại
                $policy = $this->model->getPolicyById($id);
                
                if (!$policy) {
                    throw new Exception("Không tìm thấy chính sách");
                }
                
                // Dữ liệu policy
                $policyData = [
                    'title' => $_POST['title'] ?? '',
                    'status' => isset($_POST['status']), // Status là checkbox
                    'meta' => $_POST['meta'] ?? ''
                ];
                
                // Validate dữ liệu
                if (empty($policyData['title'])) {
                    throw new Exception("Tiêu đề chính sách không được để trống");
                }
                
                $imagePath = null;
                
                // Xử lý upload hình ảnh mới nếu có
                if (!empty($_FILES['image_upload']['name'])) {
                    $targetDir = $this->footerUploadPath;
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    $fileName = time() . '_' . basename($_FILES['image_upload']['name']);
                    $targetFile = $targetDir . $fileName;
                    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                    $allowedFormats = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    if (!in_array($imageFileType, $allowedFormats)) {
                        throw new Exception("Chỉ chấp nhận file hình ảnh (JPG, JPEG, PNG, GIF, WEBP)");
                    }
                    if ($_FILES['image_upload']['size'] > 1 * 1024 * 1024) {
                        throw new Exception("Kích thước file không được vượt quá 1MB");
                    }
                    if (!move_uploaded_file($_FILES['image_upload']['tmp_name'], $targetFile)) {
                        throw new Exception("Không thể upload hình ảnh");
                    }
                    $imagePath = $fileName;
                    if (!empty($policy['image'])) {
                        $oldFile = $targetDir . $policy['image'];
                        if (file_exists($oldFile)) {
                            @unlink($oldFile);
                        }
                    }
                } else if (!empty($_POST['image']) && strpos($_POST['image'], 'data:image/') === 0) {
                    $imageData = $_POST['image'];
                    $imageInfo = $this->getImageInfoFromBase64($imageData);
                    $extension = $imageInfo['extension'];
                    $targetDir = $this->footerUploadPath;
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    $fileName = time() . '_cropped.' . $extension;
                    $targetFile = $targetDir . $fileName;
                    $decoded = $this->decodeBase64Image($imageData);
                    if (!file_put_contents($targetFile, $decoded)) {
                        throw new Exception("Không thể lưu ảnh đã cắt");
                    }
                    $imagePath = $fileName;
                    if (!empty($policy['image'])) {
                        $oldFile = $targetDir . $policy['image'];
                        if (file_exists($oldFile)) {
                            @unlink($oldFile);
                        }
                    }
                }
                
                // Cập nhật policy trong database
                $success = $this->model->updatePolicy($id, $policyData, $imagePath);
                
                if ($success) {
                    $this->sendJsonResponse(['success' => true, 'message' => 'Cập nhật chính sách thành công']);
                } else {
                    // Xóa file hình ảnh đã upload nếu cập nhật database thất bại
                    if ($imagePath && file_exists($imagePath)) {
                        @unlink($imagePath);
                    }
                    throw new Exception("Không thể cập nhật chính sách");
                }
                
            } catch (Exception $e) {
                $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            // Lấy thông tin policy để hiển thị trong form edit
            $id = $_GET['id'] ?? 0;
            
            if (!$id) {
                $this->sendJsonResponse(['success' => false, 'message' => 'ID không hợp lệ']);
                return;
            }
            
            $policy = $this->model->getPolicyById($id);
            
            if (!$policy) {
                $this->sendJsonResponse(['success' => false, 'message' => 'Không tìm thấy chính sách']);
                return;
            }
            
            $this->sendJsonResponse(['success' => true, 'policy' => $policy]);
        }
    }
    
    public function deletePolicy() {
        try {
            $id = $_POST['id'] ?? 0;
            
            if (!$id) {
                throw new Exception("ID không hợp lệ");
            }
            
            // Xóa policy và lấy đường dẫn ảnh để xóa file
            $imagePath = $this->model->deletePolicy($id);
            
            if ($imagePath && file_exists($imagePath)) {
                // Xóa file hình ảnh từ server
                @unlink($imagePath);
            }
            
            $this->sendJsonResponse(['success' => true, 'message' => 'Xóa chính sách thành công']);
            
        } catch (Exception $e) {
            $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function updatePoliciesOrder() {
        try {
            $positions = $_POST['positions'] ?? [];
            
            if (empty($positions)) {
                throw new Exception("Dữ liệu vị trí không hợp lệ");
            }
            
            $success = $this->model->updatePoliciesOrder($positions);
            
            if ($success) {
                $this->sendJsonResponse(['success' => true, 'message' => 'Cập nhật thứ tự chính sách thành công']);
            } else {
                throw new Exception("Không thể cập nhật thứ tự chính sách");
            }
        } catch (Exception $e) {
            $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // QUẢN LÝ SOCIAL MEDIA
    
    public function getSocialMedia() {
        try {
            $socialMedias = $this->model->getAllSocialMedia();
            $this->sendJsonResponse([
                'success' => true,
                'data' => $socialMedias
            ]);
        } catch (Exception $e) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function addSocialMedia() {
        try {
            // Validate dữ liệu
            if (empty($_POST['title'])) {
                throw new Exception("Tên mạng xã hội không được để trống");
            }
            
            if (empty($_POST['icon'])) {
                throw new Exception("Icon không được để trống");
            }
            
            // Dữ liệu social media
            $socialMediaData = [
                'title' => $_POST['title'],
                'icon' => $_POST['icon'],
                'link' => $_POST['link'] ?? '#',
                'status' => isset($_POST['status']), // Status là checkbox
                'meta' => $_POST['meta'] ?? ''
            ];
            
            // Thêm mới social media
            $result = $this->model->createSocialMedia($socialMediaData);
            
            if (!$result) {
                throw new Exception("Không thể thêm mới mạng xã hội");
            }
            
            $this->sendJsonResponse([
                'success' => true,
                'message' => 'Thêm mới mạng xã hội thành công',
                'data' => ['id' => $result]
            ]);
            
        } catch (Exception $e) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function editSocialMedia() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id'] ?? 0;
                if (!$id) {
                    throw new Exception("ID không hợp lệ");
                }
                // Kiểm tra social media tồn tại
                $socialMedia = $this->model->getSocialMediaById($id);
                if (!$socialMedia) {
                    throw new Exception("Không tìm thấy mạng xã hội");
                }
                // Lấy dữ liệu cập nhật
                $data = [
                    'title' => $_POST['title'] ?? '',
                    'icon' => $_POST['icon'] ?? '',
                    'link' => $_POST['link'] ?? '#',
                    'status' => isset($_POST['status']),
                    'meta' => $_POST['meta'] ?? ''
                ];
                if (empty($data['title']) || empty($data['icon'])) {
                    throw new Exception("Tên mạng xã hội và icon không được để trống");
                }
                $result = $this->model->updateSocialMedia($id, $data);
                if (!$result) {
                    throw new Exception("Không thể cập nhật mạng xã hội");
                }
                $this->sendJsonResponse([
                    'success' => true,
                    'message' => 'Cập nhật mạng xã hội thành công'
                ]);
            } catch (Exception $e) {
                $this->sendJsonResponse([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        } else {
            $id = $_GET['id'] ?? 0;
            if (!$id) {
                $this->sendJsonResponse(['success' => false, 'message' => 'ID không hợp lệ']);
                return;
            }
            $socialMedia = $this->model->getSocialMediaById($id);
            if (!$socialMedia) {
                $this->sendJsonResponse(['success' => false, 'message' => 'Không tìm thấy mạng xã hội']);
                return;
            }
            $this->sendJsonResponse(['success' => true, 'data' => $socialMedia]);
        }
    }
    
    public function deleteSocialMedia() {
        try {
            if (empty($_POST['id'])) {
                throw new Exception("ID không hợp lệ");
            }
            
            // Kiểm tra social media tồn tại
            $socialMedia = $this->model->getSocialMediaById($_POST['id']);
            if (!$socialMedia) {
                throw new Exception("Không tìm thấy mạng xã hội");
            }
            
            // Xóa social media
            $result = $this->model->deleteSocialMedia($_POST['id']);
            
            if (!$result) {
                throw new Exception("Không thể xóa mạng xã hội");
            }
            
            $this->sendJsonResponse([
                'success' => true,
                'message' => 'Xóa mạng xã hội thành công'
            ]);
            
        } catch (Exception $e) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function updateSocialMediaOrder() {
        try {
            if (empty($_POST['positions'])) {
                throw new Exception("Dữ liệu không hợp lệ");
            }
            
            $positions = json_decode($_POST['positions'], true);
            if (!is_array($positions)) {
                throw new Exception("Dữ liệu không hợp lệ");
            }
            
            $result = $this->model->updateSocialMediaOrder($positions);
            
            if (!$result) {
                throw new Exception("Không thể cập nhật thứ tự");
            }
            
            $this->sendJsonResponse([
                'success' => true,
                'message' => 'Cập nhật thứ tự thành công'
            ]);
            
        } catch (Exception $e) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    // QUẢN LÝ PHƯƠNG THỨC THANH TOÁN
    
    public function getPaymentMethods() {
        try {
            $paymentMethods = $this->model->getAllPaymentMethods();
            $this->sendJsonResponse([
                'success' => true,
                'data' => $paymentMethods
            ]);
        } catch (Exception $e) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function addPaymentMethod() {
        try {
            // Validate dữ liệu
            if (empty($_POST['title'])) {
                throw new Exception("Tên phương thức thanh toán không được để trống");
            }
            
            if (empty($_FILES['image_upload']['name'])) {
                throw new Exception("Vui lòng chọn ảnh cho phương thức thanh toán");
            }
            
            // Dữ liệu payment method
            $paymentMethodData = [
                'title' => $_POST['title'],
                'link' => $_POST['link'] ?? '#',
                'status' => isset($_POST['status']), // Status là checkbox
                'meta' => $_POST['meta'] ?? ''
            ];
            
            // Xử lý upload hình ảnh
            $targetDir = $this->footerUploadPath;
            
            // Đảm bảo thư mục tồn tại
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $fileName = time() . '_' . basename($_FILES['image_upload']['name']);
            $targetFile = $targetDir . $fileName;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            
            // Kiểm tra định dạng file
            $allowedFormats = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($imageFileType, $allowedFormats)) {
                throw new Exception("Chỉ chấp nhận file hình ảnh (JPG, JPEG, PNG, GIF, WEBP)");
            }
            
            // Kiểm tra kích thước file (max 1MB)
            if ($_FILES['image_upload']['size'] > 1 * 1024 * 1024) {
                throw new Exception("Kích thước file không được vượt quá 1MB");
            }
            
            // Upload file
            if (!move_uploaded_file($_FILES['image_upload']['tmp_name'], $targetFile)) {
                throw new Exception("Không thể upload file");
            }
            
            // Thêm mới payment method
            $result = $this->model->createPaymentMethod($paymentMethodData, $fileName);
            
            if (!$result) {
                // Xóa file đã upload nếu thêm mới thất bại
                unlink($targetFile);
                throw new Exception("Không thể thêm mới phương thức thanh toán");
            }
            
            $this->sendJsonResponse([
                'success' => true,
                'message' => 'Thêm mới phương thức thanh toán thành công',
                'data' => ['id' => $result]
            ]);
            
        } catch (Exception $e) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function editPaymentMethod() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id'] ?? 0;
                
                if (!$id) {
                    throw new Exception("ID không hợp lệ");
                }
                
                // Kiểm tra payment method tồn tại
                $paymentMethod = $this->model->getPaymentMethodById($id);
                
                if (!$paymentMethod) {
                    throw new Exception("Không tìm thấy phương thức thanh toán");
                }
                
                // Dữ liệu payment method
                $paymentMethodData = [
                    'title' => $_POST['title'] ?? '',
                    'link' => $_POST['link'] ?? '#',
                    'status' => isset($_POST['status']), // Status là checkbox
                    'meta' => $_POST['meta'] ?? ''
                ];
                
                $imagePath = null;
                
                // Xử lý upload hình ảnh mới nếu có
                if (!empty($_FILES['image_upload']['name'])) {
                    $targetDir = $this->footerUploadPath;
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    $fileName = time() . '_' . basename($_FILES['image_upload']['name']);
                    $targetFile = $targetDir . $fileName;
                    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                    $allowedFormats = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    if (!in_array($imageFileType, $allowedFormats)) {
                        throw new Exception("Chỉ chấp nhận file hình ảnh (JPG, JPEG, PNG, GIF, WEBP)");
                    }
                    if ($_FILES['image_upload']['size'] > 1 * 1024 * 1024) {
                        throw new Exception("Kích thước file không được vượt quá 1MB");
                    }
                    if (!move_uploaded_file($_FILES['image_upload']['tmp_name'], $targetFile)) {
                        throw new Exception("Không thể upload hình ảnh");
                    }
                    $imagePath = $fileName;
                    if (!empty($paymentMethod['image'])) {
                        $oldFile = $targetDir . $paymentMethod['image'];
                        if (file_exists($oldFile)) {
                            @unlink($oldFile);
                        }
                    }
                } else if (!empty($_POST['image']) && strpos($_POST['image'], 'data:image/') === 0) {
                    $imageData = $_POST['image'];
                    $imageInfo = $this->getImageInfoFromBase64($imageData);
                    $extension = $imageInfo['extension'];
                    $targetDir = $this->footerUploadPath;
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    $fileName = time() . '_cropped.' . $extension;
                    $targetFile = $targetDir . $fileName;
                    $decoded = $this->decodeBase64Image($imageData);
                    if (!file_put_contents($targetFile, $decoded)) {
                        throw new Exception("Không thể lưu ảnh đã cắt");
                    }
                    $imagePath = $fileName;
                    if (!empty($paymentMethod['image'])) {
                        $oldFile = $targetDir . $paymentMethod['image'];
                        if (file_exists($oldFile)) {
                            @unlink($oldFile);
                        }
                    }
                }
                
                // Cập nhật payment method
                $result = $this->model->updatePaymentMethod($id, $paymentMethodData, $imagePath);
                
                if ($result) {
                    $this->sendJsonResponse(['success' => true, 'message' => 'Cập nhật phương thức thanh toán thành công']);
                } else {
                    // Xóa file mới nếu cập nhật thất bại
                    if ($imagePath) {
                        unlink($targetDir . $imagePath);
                    }
                    throw new Exception("Không thể cập nhật phương thức thanh toán");
                }
                
            } catch (Exception $e) {
                $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            // Lấy thông tin payment method để hiển thị trong form edit
            $id = $_GET['id'] ?? 0;
            
            if (!$id) {
                $this->sendJsonResponse(['success' => false, 'message' => 'ID không hợp lệ']);
                return;
            }
            
            $paymentMethod = $this->model->getPaymentMethodById($id);
            
            if (!$paymentMethod) {
                $this->sendJsonResponse(['success' => false, 'message' => 'Không tìm thấy phương thức thanh toán']);
                return;
            }
            
            $this->sendJsonResponse(['success' => true, 'data' => $paymentMethod]);
        }
    }
    
    public function deletePaymentMethod() {
        try {
            if (empty($_POST['id'])) {
                throw new Exception("ID không hợp lệ");
            }
            
            // Kiểm tra payment method tồn tại
            $paymentMethod = $this->model->getPaymentMethodById($_POST['id']);
            if (!$paymentMethod) {
                throw new Exception("Không tìm thấy phương thức thanh toán");
            }
            
            // Xóa payment method
            $result = $this->model->deletePaymentMethod($_POST['id']);
            
            if (!$result) {
                throw new Exception("Không thể xóa phương thức thanh toán");
            }
            
            // Xóa file ảnh
            $targetDir = $this->footerUploadPath;
            $oldFile = $targetDir . $paymentMethod['image'];
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
            
            $this->sendJsonResponse([
                'success' => true,
                'message' => 'Xóa phương thức thanh toán thành công'
            ]);
            
        } catch (Exception $e) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function updatePaymentMethodsOrder() {
        try {
            if (empty($_POST['positions'])) {
                throw new Exception("Dữ liệu không hợp lệ");
            }
            
            $positions = json_decode($_POST['positions'], true);
            if (!is_array($positions)) {
                throw new Exception("Dữ liệu không hợp lệ");
            }
            
            $result = $this->model->updatePaymentMethodsOrder($positions);
            
            if (!$result) {
                throw new Exception("Không thể cập nhật thứ tự");
            }
            
            $this->sendJsonResponse([
                'success' => true,
                'message' => 'Cập nhật thứ tự thành công'
            ]);
            
        } catch (Exception $e) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    // PHƯƠNG THỨC HỖ TRỢ
    
    private function resizeImage($sourcePath, $targetWidth = 1200, $targetHeight = 400) {
        // Kiểm tra GD library
        if (!extension_loaded('gd') || !function_exists('gd_info')) {
            error_log('GD Library không khả dụng');
            return $sourcePath; // Trả về đường dẫn gốc nếu không có GD
        }
        
        // Lấy thông tin về ảnh gốc
        list($sourceWidth, $sourceHeight, $sourceType) = getimagesize($sourcePath);
        
        // Tạo image resource từ file gốc
        switch ($sourceType) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF:
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            case IMAGETYPE_WEBP:
                $sourceImage = imagecreatefromwebp($sourcePath);
                break;
            default:
                return $sourcePath; // Không hỗ trợ định dạng
        }
        
        if (!$sourceImage) {
            return $sourcePath;
        }
        
        // Tạo image mới với kích thước mục tiêu
        $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);
        
        // Xử lý transparent cho PNG và GIF
        if ($sourceType == IMAGETYPE_PNG || $sourceType == IMAGETYPE_GIF) {
            // Bật alpha blending và lưu thông tin alpha
            imagealphablending($targetImage, false);
            imagesavealpha($targetImage, true);
            
            // Tạo màu transparent
            $transparent = imagecolorallocatealpha($targetImage, 255, 255, 255, 127);
            imagefilledrectangle($targetImage, 0, 0, $targetWidth, $targetHeight, $transparent);
        }
        
        // Resize image
        imagecopyresampled(
            $targetImage, 
            $sourceImage, 
            0, 0, 0, 0, 
            $targetWidth, $targetHeight, 
            $sourceWidth, $sourceHeight
        );
        
        // Xác định đường dẫn lưu ảnh đã resize
        $pathInfo = pathinfo($sourcePath);
        $targetPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_resized.' . $pathInfo['extension'];
        
        // Lưu ảnh dựa trên định dạng
        switch ($sourceType) {
            case IMAGETYPE_JPEG:
                imagejpeg($targetImage, $targetPath, 90);
                break;
            case IMAGETYPE_PNG:
                imagepng($targetImage, $targetPath, 9);
                break;
            case IMAGETYPE_GIF:
                imagegif($targetImage, $targetPath);
                break;
            case IMAGETYPE_WEBP:
                imagewebp($targetImage, $targetPath, 90);
                break;
        }
        
        // Giải phóng tài nguyên
        imagedestroy($sourceImage);
        imagedestroy($targetImage);
        
        // Xóa file gốc
        if (file_exists($sourcePath) && $sourcePath != $targetPath) {
            @unlink($sourcePath);
        }
        
        return $targetPath;
    }
    
    private function sendJsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // API để lấy tất cả sections (dùng cho AJAX)
    public function getSections() {
        try {
            $sections = $this->model->getAllSections();
            $this->sendJsonResponse(['success' => true, 'sections' => $sections]);
        } catch (Exception $e) {
            $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // Phương thức lấy thông tin hình ảnh từ base64
    private function getImageInfoFromBase64($base64Data) {
        // Lấy thông tin từ string base64
        $matches = [];
        preg_match('/^data:image\/([a-zA-Z]+);base64,/', $base64Data, $matches);
        
        if (isset($matches[1])) {
            $extension = strtolower($matches[1]);
            
            // Xử lý trường hợp đặc biệt
            if ($extension === 'jpeg') {
                $extension = 'jpg';
            }
            
            return [
                'mime' => 'image/' . $matches[1],
                'extension' => $extension
            ];
        }
        
        // Mặc định trả về jpg nếu không nhận dạng được
        return [
            'mime' => 'image/jpeg',
            'extension' => 'jpg'
        ];
    }
    
    // Phương thức giải mã dữ liệu base64 thành nhị phân
    private function decodeBase64Image($base64Data) {
        // Loại bỏ phần đầu 'data:image/jpeg;base64,'
        $parts = explode(',', $base64Data);
        $base64 = isset($parts[1]) ? $parts[1] : $base64Data;
        
        // Giải mã base64 thành binary
        return base64_decode($base64);
    }

    
  
}