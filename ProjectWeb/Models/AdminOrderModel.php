<?php
class AdminOrderModel {
    public $conn;
    
    public function __construct() {
        try {
            // Đảm bảo cấu hình kết nối database đúng
            $this->conn = new mysqli('localhost', 'root', '', 'fashion_database');
            if ($this->conn->connect_error) {
                throw new Exception('Connection failed: ' . $this->conn->connect_error);
            }
            // Thiết lập charset để hỗ trợ Unicode
            $this->conn->set_charset("utf8mb4");
        } catch (Exception $e) {
            error_log('Database connection error: ' . $e->getMessage());
            exit('Lỗi kết nối database');
        }
    }
    
    public function getTrashOrders() {
        try {
            // Query lấy đơn hàng trong thùng rác
            $sql = "SELECT o.id_Order, u.name, DATE_FORMAT(o.created_at, '%d/%m/%Y') as created_at, 
                    CONCAT(REPLACE(FORMAT(o.total_amount, 0), ',', '.'), 'đ') as total_amount, 
                    o.payment_by, o.status, o.hide, o.note
                   FROM `order` o
                   LEFT JOIN user u ON o.id_User = u.id_User
                   WHERE o.hide = 1
                   ORDER BY o.created_at DESC";
            
            $result = $this->conn->query($sql);
            
            $data = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            return $data;
        } catch (Exception $e) {
            error_log('Error in getTrashOrders: ' . $e->getMessage());
            return [];
        }
    }
    
    public function restoreOrder($id) {
        try {
            $id = intval($id);
            // Query khôi phục đơn hàng từ thùng rác
            $sql = "UPDATE `order` SET hide = 0 WHERE id_Order = $id";
            return $this->conn->query($sql);
        } catch (Exception $e) {
            error_log('Error in restoreOrder: ' . $e->getMessage());
            return false;
        }
    }
    
    public function addNotification($data) {
        try {
            // Xử lý dữ liệu thông báo
            $type = isset($data['type']) ? $this->conn->real_escape_string($data['type']) : 'order';
            $title = isset($data['title']) ? $this->conn->real_escape_string($data['title']) : '';
            $content = isset($data['content']) ? $this->conn->real_escape_string($data['content']) : '';
            $meta = isset($data['meta']) ? $this->conn->real_escape_string($data['meta']) : null; 
            $link = isset($data['link']) ? $this->conn->real_escape_string($data['link']) : null;

            // Query thêm thông báo mới
            $sql = "INSERT INTO notifications (type, title, content, meta, link) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param('sssss', $type, $title, $content, $meta, $link);
                $success = $stmt->execute();
                $stmt->close();
                return $success;
            }
            return false; // Trả về false nếu có lỗi
        } catch (Exception $e) {
            error_log('Error in addNotification: ' . $e->getMessage());
            return false;
        }
    }
    
    // Giải phóng kết nối database khi object bị hủy
    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?> 