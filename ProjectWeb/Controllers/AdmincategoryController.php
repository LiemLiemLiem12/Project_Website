<?php
// Đảm bảo đường dẫn đến Model là chính xác
require_once './Models/AdminCategoryModel.php'; 

class AdminCategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new AdminCategoryModel();
    }

    // Hiển thị trang danh sách danh mục
    public function index() {
        $status = $_GET['status'] ?? '';
        $sort = $_GET['sort'] ?? '';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 5; 
        $offset = ($page - 1) * $limit;

        $categories = $this->categoryModel->getCategoriesFiltered($status, $sort, $limit, $offset);
        $total = $this->categoryModel->countCategoriesFiltered($status);
        
        require './Views/frontend/admin/AdminCategory/index.php';
    }

    private function sendJsonResponse($data) {
        // Xóa bất kỳ output buffer nào đã có trước đó để đảm bảo chỉ có JSON được gửi đi
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit; // Quan trọng: dừng script ngay sau khi gửi JSON
    }

    // Xử lý tìm kiếm AJAX
    public function search() {
        $keyword = $_GET['keyword'] ?? '';
        $status = $_GET['status'] ?? '';
        $sort = $_GET['sort'] ?? '';
        $categories = $this->categoryModel->search($keyword, $status, $sort);
        $this->sendJsonResponse($categories);
    }

    // Xử lý ẩn/hiện danh mục
    public function toggleHide() {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? 0;
        $hide = isset($input['hide']) ? intval($input['hide']) : 0;
        $result = false;

        if ($id > 0) {
            $result = $this->categoryModel->updateCategory($id, ['hide' => $hide]);
        }
        $this->sendJsonResponse(['success' => (bool)$result]);
    }

    // Lấy thông tin một danh mục
    public function getCategory() {
        $id = $_GET['id'] ?? 0;
        $category = null;
        if ($id > 0) {
            $category = $this->categoryModel->getById($id);
        }
        
        if ($category) {
            $this->sendJsonResponse($category);
        } else {
            $this->sendJsonResponse(['error' => 'Không tìm thấy danh mục.', 'id_received' => $id]);
        }
    }

    // Helper function to process base64 image data and save as a file
    private function saveBase64Image($base64Data, $targetDir) {
        // Check if directory exists, create if not
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        // Extract the base64 string
        $parts = explode(';base64,', $base64Data);
        if (count($parts) < 2) {
            error_log('Invalid base64 format - missing ;base64, delimiter');
            return false; // Invalid format
        }
        
        // Get the image data
        $imageData = base64_decode($parts[1]);
        if (!$imageData) {
            error_log('Failed to decode base64 data');
            return false; // Failed to decode
        }
        
        // Generate a filename
        $filename = 'banner_' . time() . '.jpg';
        $targetFile = $targetDir . $filename;
        
        // Save the file
        if (file_put_contents($targetFile, $imageData)) {
            error_log('Successfully saved banner image: ' . $filename);
            return $filename;
        }
        
        error_log('Failed to save file to: ' . $targetFile);
        return false; // Failed to save
    }

    // Xử lý thêm danh mục mới
    public function addCategory() {
        // Sử dụng FormData nên lấy từ $_POST và $_FILES
        $name = $_POST['name'] ?? '';
        $hide = isset($_POST['hide']) ? intval($_POST['hide']) : 0;
        $imageName = '';
        $bannerName = '';
        
        error_log('Adding new category: ' . $name);
        
        // Xử lý upload ảnh thường
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $targetDir = 'upload/img/category/';
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $imageName = time() . '_' . basename($_FILES['image']['name']);
            $targetFile = $targetDir . $imageName;
            move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
            error_log('Uploaded image: ' . $imageName);
        }
        
        // Xử lý banner từ dữ liệu base64 (ảnh đã cắt)
        if (isset($_POST['is_cropped_banner']) && $_POST['is_cropped_banner'] === '1' && isset($_POST['banner_data'])) {
            $targetDir = 'upload/img/category/';
            $bannerName = $this->saveBase64Image($_POST['banner_data'], $targetDir);
            if (!$bannerName) {
                error_log('Failed to save cropped banner');
            } else {
                error_log('Saved cropped banner: ' . $bannerName);
            }
        }
        // Hoặc xử lý upload banner thường nếu không có ảnh đã cắt
        else if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
            $targetDir = 'upload/img/category/';
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $bannerName = 'banner_' . time() . '_' . basename($_FILES['banner']['name']);
            $targetFile = $targetDir . $bannerName;
            move_uploaded_file($_FILES['banner']['tmp_name'], $targetFile);
            error_log('Uploaded banner file: ' . $bannerName);
        }
        
        $dataToInsert = [
            'name' => $name,
            'hide' => $hide,
            'image' => $imageName,
            'banner' => $bannerName
        ];
        
        error_log('Data to insert: ' . print_r($dataToInsert, true));
        
        $insertedId = $this->categoryModel->addCategory($dataToInsert);
        if ($insertedId !== false && $insertedId > 0) {
            $this->sendJsonResponse(['success' => true, 'id' => $insertedId, 'message' => 'Đã thêm danh mục thành công!']);
        } else {
            $error = $this->categoryModel->conn ? $this->categoryModel->getLastError() : "Lỗi Model CSDL.";
            $this->sendJsonResponse(['success' => false, 'message' => 'Không thể thêm. ' . $error]);
        }
    }

    // Xử lý cập nhật danh mục
    public function updateCategory() {
        $id = $_POST['id'] ?? 0;
        $name = $_POST['name'] ?? '';
        $hide = isset($_POST['hide']) ? intval($_POST['hide']) : 0;
        $imageName = '';
        $bannerName = '';
        
        error_log('Updating category ID: ' . $id);
        
        // Kiểm tra xem có yêu cầu giữ lại ảnh cũ không
        $keepOldImage = isset($_POST['keep_old_image']) && $_POST['keep_old_image'] === '1';
        $oldImageName = $_POST['old_image_name'] ?? '';
        
        // Kiểm tra xem có yêu cầu giữ lại banner cũ không
        $keepOldBanner = isset($_POST['keep_old_banner']) && $_POST['keep_old_banner'] === '1';
        $oldBannerName = $_POST['old_banner_name'] ?? '';
        
        error_log('Keep old image: ' . ($keepOldImage ? 'Yes' : 'No') . ', Old image name: ' . $oldImageName);
        error_log('Keep old banner: ' . ($keepOldBanner ? 'Yes' : 'No') . ', Old banner name: ' . $oldBannerName);
        
        // Lấy thông tin danh mục cũ
        $old = $this->categoryModel->getById($id);
        if ($old) {
            error_log('Current category data: ' . print_r($old, true));
        }
        
        // Xử lý ảnh
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Có upload ảnh mới
            $targetDir = 'upload/img/category/';
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $imageName = time() . '_' . basename($_FILES['image']['name']);
            $targetFile = $targetDir . $imageName;
            move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
            error_log('Uploaded new image: ' . $imageName);
            
            // Xóa ảnh cũ nếu có
            if ($old && !empty($old['image'])) {
                $oldImagePath = parse_url($old['image'], PHP_URL_PATH);
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $oldImagePath)) {
                    @unlink($_SERVER['DOCUMENT_ROOT'] . $oldImagePath);
                    error_log('Deleted old image: ' . $oldImagePath);
                }
            }
        } else if ($keepOldImage && !empty($oldImageName)) {
            // Giữ lại tên ảnh cũ từ request
            $imageName = $oldImageName;
            error_log('Keeping old image: ' . $imageName);
        } else if ($old && !empty($old['image'])) {
            // Backup: Lấy tên ảnh từ database nếu không có tên từ request
            $imagePath = parse_url($old['image'], PHP_URL_PATH);
            $pathParts = explode('/', $imagePath);
            $imageName = end($pathParts);
            error_log('Using existing image from DB: ' . $imageName);
        }
        
        // Xử lý banner từ dữ liệu base64 (ảnh đã cắt)
        if (isset($_POST['is_cropped_banner']) && $_POST['is_cropped_banner'] === '1' && isset($_POST['banner_data'])) {
            $targetDir = 'upload/img/category/';
            $bannerName = $this->saveBase64Image($_POST['banner_data'], $targetDir);
            if (!$bannerName) {
                error_log('Failed to save cropped banner during update');
            } else {
                error_log('Saved cropped banner for update: ' . $bannerName);
                
                // Xóa banner cũ nếu có
                if ($old && !empty($old['banner'])) {
                    $oldBannerPath = 'upload/img/category/' . $old['banner'];
                    if (file_exists($oldBannerPath)) {
                        @unlink($oldBannerPath);
                        error_log('Deleted old banner: ' . $oldBannerPath);
                    }
                }
            }
        }
        // Hoặc xử lý upload banner thường
        else if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
            $targetDir = 'upload/img/category/';
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $bannerName = 'banner_' . time() . '_' . basename($_FILES['banner']['name']);
            $targetFile = $targetDir . $bannerName;
            move_uploaded_file($_FILES['banner']['tmp_name'], $targetFile);
            error_log('Uploaded banner file for update: ' . $bannerName);
            
            // Xóa banner cũ nếu có
            if ($old && !empty($old['banner'])) {
                $oldBannerPath = 'upload/img/category/' . $old['banner'];
                if (file_exists($oldBannerPath)) {
                    @unlink($oldBannerPath);
                    error_log('Deleted old banner: ' . $oldBannerPath);
                }
            }
        } else if ($keepOldBanner && !empty($oldBannerName)) {
            // Giữ lại banner cũ
            $bannerName = basename($oldBannerName);
            error_log('Keeping old banner: ' . $bannerName);
        } else if ($old && !empty($old['banner'])) {
            // Lấy banner từ database
            $bannerName = $old['banner'];
            error_log('Using existing banner from DB: ' . $bannerName);
        }
        
        $updateData = [
            'name' => $name,
            'hide' => $hide
        ];
        
        // Chỉ cập nhật ảnh nếu có tên ảnh
        if (!empty($imageName)) {
            $updateData['image'] = $imageName;
        }
        
        // Chỉ cập nhật banner nếu có tên banner
        if (!empty($bannerName)) {
            $updateData['banner'] = $bannerName;
        }
        
        error_log('Data to update: ' . print_r($updateData, true));
        
        $result = $this->categoryModel->updateCategory($id, $updateData);
        if ($result) {
            $this->sendJsonResponse(['success' => true, 'message' => 'Đã cập nhật thành công!']);
        } else {
            $error = $this->categoryModel->conn ? $this->categoryModel->getLastError() : "Lỗi Model CSDL.";
            $this->sendJsonResponse(['success' => false, 'message' => 'Không thể cập nhật. ' . $error]);
        }
    }
    
    // Xử lý xóa các danh mục đã chọn
    public function deleteSelected() {
        try {
            // Tắt hiển thị lỗi
            error_reporting(0);
            
            $input = json_decode(file_get_contents('php://input'), true);
            $ids = $input['ids'] ?? [];
            
            // Lấy thông tin trang hiện tại
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $status = $_GET['status'] ?? '';
            $sort = $_GET['sort'] ?? '';
            $limit = 5; // Phải giống với giá trị limit trong phương thức index()
            
            $overallSuccess = true; $messages = []; $hiddenCount = 0; $failedCount = 0;
    
            if (!empty($ids) && is_array($ids)) {
                foreach ($ids as $id) {
                    $id = intval($id);
                    if ($id <= 0) continue; 
                    $catInfo = $this->categoryModel->getById($id);
                    
                    // Thay vì xóa, chỉ cập nhật hide = 1
                    $result = $this->categoryModel->updateCategory($id, ['hide' => 1]);
                    
                    if ($result) {
                        $hiddenCount++;
                        if ($catInfo && $this->categoryModel->conn) {
                             $meta = json_encode(['category_id' => $id, 'name' => $catInfo['name']]);
                             $this->categoryModel->addNotification([
                                'type' => 'category', 'title' => 'Ẩn danh mục',
                                'content' => "Đã ẩn: " . htmlspecialchars($catInfo['name']), 
                                'meta' => $meta, 'link' => ''
                            ]);
                        }
                    } else {
                        $failedCount++; $overallSuccess = false;
                        $errorMsg = $this->categoryModel->conn ? $this->categoryModel->getLastError() : "Lỗi Model CSDL.";
                        $catName = $catInfo ? htmlspecialchars($catInfo['name']) : "ID $id";
                        $messages[] = "'$catName': lỗi ẩn danh mục.";
                    }
                }
                
                // Đếm lại số lượng sau khi ẩn
                $remainingCount = $this->categoryModel->countCategoriesFiltered($status);
                $maxPage = ceil($remainingCount / $limit);
                
                $finalMsg = '';
                if ($hiddenCount > 0) $finalMsg .= "Đã ẩn $hiddenCount danh mục. ";
                if ($failedCount > 0) $finalMsg .= "Lỗi $failedCount: " . implode("; ", $messages);
                if (empty($finalMsg)) $finalMsg = empty($ids) ? "Không có ID." : "Không có gì được xử lý.";
                
                // Trả về thêm thông tin phân trang
                $this->sendJsonResponse([
                    'success' => $overallSuccess, 
                    'message' => trim($finalMsg),
                    'pagination' => [
                        'currentPage' => $page,
                        'maxPage' => $maxPage,
                        'remainingCount' => $remainingCount,
                        'itemsPerPage' => $limit,
                        'status' => $status,
                        'sort' => $sort
                    ]
                ]);
                return;
            }
            
            $this->sendJsonResponse(['success' => false, 'message' => 'Không có ID được chọn.']);
        } catch (Exception $e) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        }
    }
    
    public function getRecentCategoryNotifications() {
        $notifications = $this->categoryModel->getRecentCategoryNotifications(5); 
        $this->sendJsonResponse($notifications);
    }

    // Lấy danh sách danh mục trong thùng rác (hide = 1)
    public function getTrashCategories() {
        $data = $this->categoryModel->getTrashCategories();
        $this->sendJsonResponse($data);
    }
    
    // Khôi phục danh mục từ thùng rác (set hide = 0)
    public function restoreCategories() {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $ids = $input['ids'] ?? [];
            
            // Lấy thông tin trang hiện tại
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $status = $_GET['status'] ?? '';
            $sort = $_GET['sort'] ?? '';
            $limit = 5; // Phải giống với giá trị limit trong phương thức index()
            
            $restoredCount = 0;
            $failedCount = 0;
            $messages = [];
            
            if (!empty($ids) && is_array($ids)) {
                foreach ($ids as $id) {
                    $id = intval($id);
                    if ($id <= 0) continue;
                    
                    $catInfo = $this->categoryModel->getById($id);
                    $result = $this->categoryModel->updateCategory($id, ['hide' => 0]);
                    
                    if ($result) {
                        $restoredCount++;
                        if ($catInfo && $this->categoryModel->conn) {
                            $meta = json_encode(['category_id' => $id, 'name' => $catInfo['name']]);
                            $this->categoryModel->addNotification([
                                'type' => 'category', 'title' => 'Khôi phục danh mục',
                                'content' => "Đã khôi phục: " . htmlspecialchars($catInfo['name']), 
                                'meta' => $meta, 'link' => ''
                            ]);
                        }
                    } else {
                        $failedCount++;
                        $catName = $catInfo ? htmlspecialchars($catInfo['name']) : "ID $id";
                        $messages[] = "'$catName': lỗi khôi phục.";
                    }
                }
                
                // Đếm lại số lượng sau khi khôi phục
                $activeCount = $this->categoryModel->countCategoriesFiltered('active');
                $maxPage = ceil($activeCount / $limit);
                
                $finalMsg = '';
                if ($restoredCount > 0) $finalMsg .= "Đã khôi phục $restoredCount danh mục. ";
                if ($failedCount > 0) $finalMsg .= "Lỗi $failedCount: " . implode("; ", $messages);
                if (empty($finalMsg)) $finalMsg = empty($ids) ? "Không có ID." : "Không có gì được xử lý.";
                
                $this->sendJsonResponse([
                    'success' => $failedCount === 0,
                    'message' => trim($finalMsg),
                    'pagination' => [
                        'currentPage' => $page,
                        'maxPage' => $maxPage,
                        'totalItems' => $activeCount,
                        'itemsPerPage' => $limit,
                        'status' => $status,
                        'sort' => $sort
                    ]
                ]);
                return;
            }
            
            $this->sendJsonResponse(['success' => false, 'message' => 'Không có ID được chọn.']);
        } catch (Exception $e) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        }
    }
    
    // Tìm kiếm danh mục với AJAX (không tải lại trang)
    public function ajaxSearch() {
        $keyword = $_GET['keyword'] ?? '';
        $status = $_GET['status'] ?? '';
        $sort = $_GET['sort'] ?? '';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 5;
        $offset = ($page - 1) * $limit;
        
        // Sử dụng model để lấy dữ liệu theo điều kiện
        $categories = [];
        $total = 0;
        
        if (!empty($keyword)) {
            // Nếu có từ khóa tìm kiếm, sử dụng phương thức search
            $categories = $this->categoryModel->search($keyword, $status, $sort);
            $total = count($categories);
            
            // Phân trang thủ công cho kết quả tìm kiếm
            $categories = array_slice($categories, $offset, $limit);
        } else {
            // Nếu không có từ khóa, sử dụng phương thức getCategoriesFiltered
            $categories = $this->categoryModel->getCategoriesFiltered($status, $sort, $limit, $offset);
            $total = $this->categoryModel->countCategoriesFiltered($status);
        }
        
        // Tính toán thông tin phân trang
        $totalPages = ceil($total / $limit);
        
        // Trả về kết quả dưới dạng JSON
        $this->sendJsonResponse([
            'categories' => $categories,
            'pagination' => [
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'total' => $total
            ]
        ]);
    }

    public function getCategoriesCount() {
        $count = $this->categoryModel->countCategoriesFiltered('active');
        $this->sendJsonResponse(['count' => $count]);
    }
}
?>