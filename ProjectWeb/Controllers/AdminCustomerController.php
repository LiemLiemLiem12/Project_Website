<?php
require_once './Models/AdminCustomerModel.php';

class AdminCustomerController {
    protected $customerModel;

    public function __construct() {
        $this->customerModel = new AdminCustomerModel();
    }

    // Trang danh sách khách hàng (lọc, sắp xếp, phân trang)
    public function index() {
        $status = $_GET['status'] ?? '';
        $sort = $_GET['sort'] ?? '';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $customers = $this->customerModel->getCustomersFiltered($status, $sort, $limit, $offset);
        $total = $this->customerModel->countCustomersFiltered($status);

        require './Views/frontend/admin/AdminCustomer/index.php';
    }

    // Tìm kiếm AJAX (không phân trang)
    public function search() {
        $keyword = $_GET['keyword'] ?? '';
        $customers = $this->customerModel->search($keyword);
        header('Content-Type: application/json');
        echo json_encode($customers);
    }
    public function toggleHide() {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? 0;
        $hide = $input['hide'] ?? 0;
        if ($id) {
            $result = $this->customerModel->updateCustomer($id, ['hide' => $hide]);
            echo json_encode(['success' => $result]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }
    public function getCustomer() {
        $id = $_GET['id'] ?? 0;
        $customer = $this->customerModel->getById($id);
        header('Content-Type: application/json');
        if ($customer) {
            echo json_encode($customer);
        } else {
            echo json_encode(['error' => 'Customer not found']);
        }
        exit;
    }
    //Thêm khách hàng
    public function addCustomer() {
        $input = json_decode(file_get_contents('php://input'), true);
        $result = $this->customerModel->addCustomer($input);
        if ($result) {
            $name = $input['name'];
            $meta = json_encode(['customer_id' => $this->customerModel->conn->insert_id]);
            $this->customerModel->addNotification([
                'type' => 'customer',
                'title' => 'Thêm khách hàng',
                'content' => "Đã thêm khách hàng $name.",
                'meta' => $meta,
                'link' => '/admin/customer/' . $this->customerModel->conn->insert_id
            ]);
        }
        echo json_encode(['success' => $result]);
        exit;
    }

    // Sửa khách hàng
    public function updateCustomer() {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? 0;
        unset($input['id']);
        $result = $this->customerModel->updateCustomer($id, $input);
        if ($result) {
            $name = $input['name'];
            $meta = json_encode(['customer_id' => $id]);
            $this->customerModel->addNotification([
                'type' => 'customer',
                'title' => 'Sửa khách hàng',
                'content' => "Đã sửa thông tin khách hàng $name.",
                'meta' => $meta,
                'link' => '/admin/customer/' . $id
            ]);
        }
        echo json_encode(['success' => $result]);
        exit;
    }

    // Xóa khách hàng
    public function deleteCustomer() {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? 0;
        $customer = $this->customerModel->getById($id);
        $result = $this->customerModel->deleteCustomer($id);
        $errorMsg = $this->customerModel->getLastError();
        $message = '';
        if ($result && $customer) {
            $meta = json_encode(['customer_id' => $id]);
            $this->customerModel->addNotification([
                'type' => 'customer',
                'title' => 'Xóa khách hàng',
                'content' => "Đã xóa khách hàng {$customer['name']}.",
                'meta' => $meta,
                'link' => '/admin/customer/' . $id
            ]);
        }
        if (!$result && strpos($errorMsg, 'a foreign key constraint fails') !== false) {
            $message = 'Khách hàng này không thể xóa do đang có đơn hàng!';
        } elseif (!$result) {
            $message = $errorMsg;
        }
        echo json_encode(['success' => $result, 'message' => $message]);
        exit;
    }

    public function deleteSelected() {
        $input = json_decode(file_get_contents('php://input'), true);
        $ids = $input['ids'] ?? [];
        $errorMsg = '';
        if (!empty($ids)) {
            $success = true;
            foreach ($ids as $id) {
                $result = $this->customerModel->deleteCustomer($id);
                if (!$result) {
                    $success = false;
                    $err = $this->customerModel->getLastError();
                    if (strpos($err, 'a foreign key constraint fails') !== false) {
                        $errorMsg = 'Có khách hàng không thể xóa do đang có đơn hàng!';
                    } else {
                        $errorMsg = $err;
                    }
                }
            }
            echo json_encode(['success' => $success, 'message' => $success ? '' : ($errorMsg ?: 'Không thể xóa khách hàng do ràng buộc dữ liệu!')]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không có khách hàng nào được chọn!']);
        }
        exit;
    }
    public function getRecentCustomerNotifications() {
        $notifications = $this->customerModel->getRecentCustomerNotifications(3);
        header('Content-Type: application/json');
        echo json_encode($notifications);
        exit;
    }


}