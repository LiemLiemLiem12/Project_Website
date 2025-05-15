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

    // Xử lý thêm danh mục mới
    public function addCategory() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || empty($input['name'])) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Tên danh mục không được để trống.']);
            return; // Add this return statement
        }

        $dataToInsert = [
            'name' => $input['name'],
            'hide' => isset($input['hide']) ? intval($input['hide']) : 0
        ];
        
        $insertedId = $this->categoryModel->addCategory($dataToInsert); 

        if ($insertedId !== false && $insertedId > 0) {
            $name = htmlspecialchars($input['name']); 
            if ($this->categoryModel->conn) { // Chỉ ghi log nếu kết nối DB ok
                $meta = json_encode(['category_id' => $insertedId]);
                $this->categoryModel->addNotification([
                    'type' => 'category', 'title' => 'Thêm danh mục',
                    'content' => "Đã thêm: $name.", 'meta' => $meta, 'link' => ''
                ]);
            }
            $this->sendJsonResponse(['success' => true, 'id' => $insertedId, 'message' => 'Đã thêm danh mục thành công!']);
        } else {
            $error = $this->categoryModel->conn ? $this->categoryModel->getLastError() : "Lỗi Model CSDL.";
            $this->sendJsonResponse(['success' => false, 'message' => 'Không thể thêm. ' . $error]);
        }
    }

    // Xử lý cập nhật danh mục
    public function updateCategory() {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? 0;
        
        if (!$input || !$id || empty($input['name'])) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
            return; // Add this return statement
        }
        
        $updateData = [
            'name' => $input['name'],
            'hide' => isset($input['hide']) ? intval($input['hide']) : 0
        ];

        $result = $this->categoryModel->updateCategory($id, $updateData);

        if ($result) {
            $name = htmlspecialchars($input['name']);
            if ($this->categoryModel->conn) {
                $meta = json_encode(['category_id' => $id]);
                $this->categoryModel->addNotification([
                    'type' => 'category', 'title' => 'Cập nhật danh mục',
                    'content' => "Đã cập nhật: $name.", 'meta' => $meta, 'link' => ''
                ]);
            }
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
        try {
            // Lấy tất cả danh mục có hide = 1
            $sql = "SELECT id_Category AS ID, name, hide FROM category WHERE hide = 1 ORDER BY name ASC";
            $result = $this->categoryModel->conn->query($sql);
            
            $data = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            
            $this->sendJsonResponse($data);
        } catch (Exception $e) {
            $this->sendJsonResponse(['error' => 'Lỗi khi lấy dữ liệu thùng rác: ' . $e->getMessage()]);
        }
    }
    
    // Khôi phục danh mục từ thùng rác (set hide = 0)
    public function restoreCategories() {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $ids = $input['ids'] ?? [];
            
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
                
                $finalMsg = '';
                if ($restoredCount > 0) $finalMsg .= "Đã khôi phục $restoredCount danh mục. ";
                if ($failedCount > 0) $finalMsg .= "Lỗi $failedCount: " . implode("; ", $messages);
                if (empty($finalMsg)) $finalMsg = empty($ids) ? "Không có ID." : "Không có gì được xử lý.";
                
                $this->sendJsonResponse([
                    'success' => $failedCount === 0,
                    'message' => trim($finalMsg)
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
}
?>