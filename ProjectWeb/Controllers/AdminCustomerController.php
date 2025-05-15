<?php
require_once './Models/AdminCustomerModel.php';

class AdminCustomerController {
    protected $customerModel;

    public function __construct() {
        $this->customerModel = new AdminCustomerModel();
    }

    // Trang danh sách khách hàng (lọc, sắp xếp, phân trang)
    public function index() {
        // Mặc định chỉ hiển thị tài khoản đang hoạt động (hide = 0)
        $status = $_GET['status'] ?? 'active';
        $sort = $_GET['sort'] ?? '';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 5;
        $offset = ($page - 1) * $limit;

        // Sử dụng phương thức từ model để đảm bảo chỉ lấy admin users
        $customers = $this->customerModel->getCustomersFiltered($status, $sort, $limit, $offset);
        
        // Đếm tổng số admin bằng phương thức từ model
        $total = $this->customerModel->countCustomersFiltered($status);

        require './Views/frontend/admin/AdminCustomer/index.php';
    }

    // Tìm kiếm AJAX (không phân trang)
    public function search() {
        $keyword = $_GET['keyword'] ?? '';
        
        // Sử dụng phương thức search từ model (đã giới hạn chỉ lấy admin)
        $data = $this->customerModel->search($keyword);
        
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    public function toggleHide() {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? 0;
        $hide = $input['hide'] ?? 0;
        if ($id) {
            $result = $this->customerModel->updateCustomer($id, ['hide' => $hide]);
            header('Content-Type: application/json');
            echo json_encode(['success' => $result]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false]);
        }
        exit;
    }
    public function getCustomer() {
        $id = $_GET['id'] ?? 0;
        $customer = $this->customerModel->getById($id);
        header('Content-Type: application/json');
        
        // Verify this is an admin user
        if ($customer && $customer['role'] === 'admin') {
            echo json_encode($customer);
        } else {
            echo json_encode(['error' => 'Admin not found']);
        }
        exit;
    }
    //Thêm khách hàng
    public function addCustomer() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Không cần đặt role vì model đã đảm bảo chỉ có thể thêm admin
        
        $result = $this->customerModel->addCustomer($input);
        if ($result) {
            $name = $input['name'];
            $meta = json_encode(['customer_id' => $this->customerModel->conn->insert_id]);
            $this->customerModel->addNotification([
                'type' => 'customer',
                'title' => 'Thêm tài khoản admin',
                'content' => "Đã thêm tài khoản admin: $name.",
                'meta' => $meta,
                'link' => '/admin/customer/' . $this->customerModel->conn->insert_id
            ]);
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => $result]);
        exit;
    }

    // Sửa khách hàng
    public function updateCustomer() {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? 0;
        unset($input['id']);
        
        // Không cần đặt role vì model đã đảm bảo chỉ có thể cập nhật admin
        
        $result = $this->customerModel->updateCustomer($id, $input);
        if ($result) {
            $name = $input['name'];
            $meta = json_encode(['customer_id' => $id]);
            $this->customerModel->addNotification([
                'type' => 'customer',
                'title' => 'Sửa tài khoản admin',
                'content' => "Đã sửa thông tin tài khoản admin: $name.",
                'meta' => $meta,
                'link' => '/admin/customer/' . $id
            ]);
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => $result]);
        exit;
    }

    // Xóa khách hàng
    public function deleteCustomer() {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? 0;
        $customer = $this->customerModel->getById($id);
        
        // Thay vì xóa, chỉ cập nhật hide = 1
        $result = $this->customerModel->updateCustomer($id, ['hide' => 1]);
        
        $errorMsg = $this->customerModel->getLastError();
        $message = '';
        
        if ($result && $customer) {
            $meta = json_encode(['customer_id' => $id]);
            $this->customerModel->addNotification([
                'type' => 'customer',
                'title' => 'Ẩn tài khoản admin',
                'content' => "Đã ẩn tài khoản admin: {$customer['name']}.",
                'meta' => $meta,
                'link' => '/admin/customer/' . $id
            ]);
        }
        if (!$result) {
            $message = $errorMsg;
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => $result, 'message' => $message]);
        exit;
    }

    public function deleteSelected() {
        $input = json_decode(file_get_contents('php://input'), true);
        $ids = $input['ids'] ?? [];
        $errorMsg = '';
        $hiddenCount = 0;
        $failedCount = 0;
        
        if (!empty($ids)) {
            $success = true;
            foreach ($ids as $id) {
                // Thay vì xóa, chỉ cập nhật hide = 1
                $customer = $this->customerModel->getById($id);
                $result = $this->customerModel->updateCustomer($id, ['hide' => 1]);
                
                if ($result) {
                    $hiddenCount++;
                    if ($customer) {
                        $meta = json_encode(['customer_id' => $id]);
                        $this->customerModel->addNotification([
                            'type' => 'customer',
                            'title' => 'Ẩn tài khoản admin',
                            'content' => "Đã ẩn tài khoản admin: {$customer['name']}.",
                            'meta' => $meta,
                            'link' => '/admin/customer/' . $id
                        ]);
                    }
                } else {
                    $failedCount++;
                    $success = false;
                    $err = $this->customerModel->getLastError();
                    $errorMsg = $err;
                }
            }
            
            $finalMsg = '';
            if ($hiddenCount > 0) $finalMsg .= "Đã ẩn $hiddenCount tài khoản admin. ";
            if ($failedCount > 0) $finalMsg .= "Lỗi $failedCount tài khoản. ";
            
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'message' => $finalMsg ?: 'Đã ẩn tài khoản admin thành công!']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Không có tài khoản nào được chọn!']);
        }
        exit;
    }
    public function getRecentCustomerNotifications() {
        $notifications = $this->customerModel->getRecentCustomerNotifications(3);
        header('Content-Type: application/json');
        echo json_encode($notifications);
        exit;
    }

    // Lấy danh sách khách hàng trong thùng rác (hide = 1)
    public function getTrashCustomers() {
        try {
            // Sử dụng prepared statement để lấy admin users đã ẩn
            $sql = "SELECT * FROM user WHERE hide = 1 AND role = 'admin' ORDER BY name ASC";
            $stmt = $this->customerModel->conn->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Lỗi chuẩn bị truy vấn: " . $this->customerModel->conn->error);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            
            header('Content-Type: application/json');
            echo json_encode($data);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Lỗi khi lấy dữ liệu thùng rác: ' . $e->getMessage()]);
        }
        exit;
    }
    
    // Khôi phục khách hàng từ thùng rác (set hide = 0)
    public function restoreCustomers() {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $ids = $input['ids'] ?? [];
            
            $restoredCount = 0;
            $failedCount = 0;
            $messages = [];
            
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $id = intval($id);
                    if ($id <= 0) continue;
                    
                    $customerInfo = $this->customerModel->getById($id);
                    $result = $this->customerModel->updateCustomer($id, ['hide' => 0]);
                    
                    if ($result) {
                        $restoredCount++;
                        if ($customerInfo) {
                            $meta = json_encode(['customer_id' => $id, 'name' => $customerInfo['name']]);
                            $this->customerModel->addNotification([
                                'type' => 'customer', 
                                'title' => 'Khôi phục tài khoản admin',
                                'content' => "Đã khôi phục tài khoản admin: " . htmlspecialchars($customerInfo['name']), 
                                'meta' => $meta, 
                                'link' => ''
                            ]);
                        }
                    } else {
                        $failedCount++;
                        $customerName = $customerInfo ? htmlspecialchars($customerInfo['name']) : "ID $id";
                        $messages[] = "'$customerName': lỗi khôi phục.";
                    }
                }
                
                $finalMsg = '';
                if ($restoredCount > 0) $finalMsg .= "Đã khôi phục $restoredCount tài khoản admin. ";
                if ($failedCount > 0) $finalMsg .= "Lỗi $failedCount: " . implode("; ", $messages);
                if (empty($finalMsg)) $finalMsg = empty($ids) ? "Không có ID." : "Không có gì được xử lý.";
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => $failedCount === 0,
                    'message' => trim($finalMsg)
                ]);
                exit;
            }
            
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Không có tài khoản nào được chọn.']);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        }
        exit;
    }

    // API để lấy dữ liệu AJAX cho bảng khách hàng
    public function getCustomersData() {
        // Mặc định chỉ hiển thị tài khoản đang hoạt động (hide = 0)
        $status = $_GET['status'] ?? 'active';
        $sort = $_GET['sort'] ?? '';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : 5;
        $offset = ($page - 1) * $limit;

        // Sử dụng phương thức của model với role=admin
        $data = $this->customerModel->getCustomersFiltered($status, $sort, $limit, $offset);
        
        // Lấy tổng số admin customers để phân trang
        $total = $this->customerModel->countCustomersFiltered($status);
        
        header('Content-Type: application/json');
        echo json_encode([
            'data' => $data,
            'status' => $status,
            'sort' => $sort,
            'total' => $total,
            'page' => $page,
            'limit' => $limit
        ]);
        exit;
    }

    // API để lấy danh sách khách hàng thông thường (role = user)
    public function getUsers() {
        try {
            // Sử dụng prepared statement để lấy users
            $sql = "SELECT * FROM user WHERE role = 'user' ORDER BY name ASC";
            $stmt = $this->customerModel->conn->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Lỗi chuẩn bị truy vấn: " . $this->customerModel->conn->error);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            
            header('Content-Type: application/json');
            echo json_encode($data);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Lỗi khi lấy dữ liệu khách hàng: ' . $e->getMessage()]);
        }
        exit;
    }
}