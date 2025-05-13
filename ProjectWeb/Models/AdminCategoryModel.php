<?php
class AdminCategoryModel {
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

    public function getCategoriesFiltered($status, $sort, $limit, $offset) {
        try {
            $whereClauses = [];
            if ($status === 'active') $whereClauses[] = "hide = 0";
            if ($status === 'inactive') $whereClauses[] = "hide = 1";
            
            $where = "";
            if (!empty($whereClauses)) {
                $where = "WHERE " . implode(" AND ", $whereClauses);
            }
            
            $order = '';
            if ($sort === 'name-asc') $order = "ORDER BY name ASC";
            if ($sort === 'name-desc') $order = "ORDER BY name DESC";
            
            // Query để lấy danh sách danh mục
            $sql = "SELECT id_Category AS ID, name, hide FROM category $where $order LIMIT $limit OFFSET $offset";
            $result = $this->conn->query($sql);
            
            $data = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            return $data;
        } catch (Exception $e) {
            error_log('Error in getCategoriesFiltered: ' . $e->getMessage());
            return [];
        }
    }

    public function countCategoriesFiltered($status) {
        try {
            $whereClauses = [];
            if ($status === 'active') $whereClauses[] = "hide = 0";
            if ($status === 'inactive') $whereClauses[] = "hide = 1";

            $where = "";
            if (!empty($whereClauses)) {
                $where = "WHERE " . implode(" AND ", $whereClauses);
            }
            
            // Query để đếm tổng số danh mục
            $sql = "SELECT COUNT(*) as total FROM category $where";
            $result = $this->conn->query($sql);
            $row = $result ? $result->fetch_assoc() : ['total' => 0];
            return $row['total'] ?? 0;
        } catch (Exception $e) {
            error_log('Error in countCategoriesFiltered: ' . $e->getMessage());
            return 0;
        }
    }

    public function search($keyword) {
        try {
            $escapedKeyword = $this->conn->real_escape_string($keyword);
            // Query tìm kiếm danh mục
            $sql = "SELECT id_Category AS ID, name, hide FROM category WHERE name LIKE '%$escapedKeyword%'";
            $result = $this->conn->query($sql);
            
            $data = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            return $data;
        } catch (Exception $e) {
            error_log('Error in search: ' . $e->getMessage());
            return [];
        }
    }

    public function getById($id) {
        try {
            $id = intval($id);
            // Query lấy thông tin danh mục theo ID
            $sql = "SELECT id_Category AS ID, name, hide FROM category WHERE id_Category = $id";
            $result = $this->conn->query($sql);
            return $result ? $result->fetch_assoc() : null;
        } catch (Exception $e) {
            error_log('Error in getById: ' . $e->getMessage());
            return null;
        }
    }

    public function addCategory($data) {
        try {
            $name = $this->conn->real_escape_string($data['name'] ?? '');
            $hide = intval($data['hide'] ?? 0);
            
            // Query thêm danh mục mới
            $sql = "INSERT INTO category (name, hide) VALUES ('$name', $hide)";
            if ($this->conn->query($sql)) {
                return $this->conn->insert_id; // Trả về ID mới
            }
            return false;
        } catch (Exception $e) {
            error_log('Error in addCategory: ' . $e->getMessage());
            return false;
        }
    }

    public function updateCategory($id, $data) {
        try {
            $id = intval($id);
            $fields = [];
            
            if (isset($data['name'])) {
                $fields[] = "name='" . $this->conn->real_escape_string($data['name']) . "'";
            }
            if (isset($data['hide'])) {
                $fields[] = "hide=" . intval($data['hide']);
            }
            
            if (empty($fields)) return false;
            
            // Query cập nhật danh mục
            $sql = "UPDATE category SET " . implode(", ", $fields) . " WHERE id_Category = $id";
            return $this->conn->query($sql);
        } catch (Exception $e) {
            error_log('Error in updateCategory: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteCategory($id) {
        try {
            $id = intval($id);
            // Query xóa danh mục
            $sql = "DELETE FROM category WHERE id_Category = $id";
            return $this->conn->query($sql);
        } catch (Exception $e) {
            error_log('Error in deleteCategory: ' . $e->getMessage());
            return false;
        }
    }

    public function getLastError() {
        return $this->conn->error;
    }

    // Phương thức lấy thông báo gần đây
    public function getRecentCategoryNotifications($limit = 3) {
        try {
            // Query lấy thông báo gần đây
            $sql = "SELECT * FROM notifications WHERE type = 'category' AND hide = 0 ORDER BY created_at DESC LIMIT ?";
            $stmt = $this->conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param('i', $limit);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                $stmt->close();
                return $data;
            }
            return []; // Trả về mảng rỗng nếu có lỗi
        } catch (Exception $e) {
            error_log('Error in getRecentCategoryNotifications: ' . $e->getMessage());
            return [];
        }
    }

    public function addNotification($data) {
        try {
            // Xử lý dữ liệu thông báo
            $type = isset($data['type']) ? $this->conn->real_escape_string($data['type']) : 'category';
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