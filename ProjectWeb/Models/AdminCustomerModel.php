<?php
class AdminCustomerModel {
    public $conn;
    public function __construct() {
        $this->conn = new mysqli('localhost', 'root', '', 'fashion_database');
        if ($this->conn->connect_error) {
            die('Connection failed: ' . $this->conn->connect_error);
        }
    }

    public function getCustomersFiltered($status, $sort, $limit, $offset, $role = null) {
        $whereClauses = ["role = 'admin'"];
        
        if ($status === 'active') $whereClauses[] = "hide = 0";
        if ($status === 'inactive') $whereClauses[] = "hide = 1";
        
        $where = '';
        if (!empty($whereClauses)) {
            $where = "WHERE " . implode(" AND ", $whereClauses);
        }
        
        $order = '';
        if ($sort === 'name-asc') $order = "ORDER BY name ASC";
        if ($sort === 'name-desc') $order = "ORDER BY name DESC";
        if ($sort === 'newest') $order = "ORDER BY created_at DESC, id_User DESC";
        if ($sort === 'oldest') $order = "ORDER BY created_at ASC, id_User ASC";
        if (!$order) $order = "ORDER BY id_User DESC"; // Mặc định sắp xếp theo ID giảm dần
        
        $sql = "SELECT * FROM user $where $order LIMIT $limit OFFSET $offset";
        $result = $this->conn->query($sql);
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function countCustomersFiltered($status, $role = null) {
        $whereClauses = ["role = 'admin'"];
        
        if ($status === 'active') $whereClauses[] = "hide = 0";
        if ($status === 'inactive') $whereClauses[] = "hide = 1";
        
        $where = '';
        if (!empty($whereClauses)) {
            $where = "WHERE " . implode(" AND ", $whereClauses);
        }
        
        $sql = "SELECT COUNT(*) as total FROM user $where";
        $result = $this->conn->query($sql);
        $row = $result ? $result->fetch_assoc() : ['total' => 0];
        return $row['total'] ?? 0;
    }

    public function search($keyword, $role = null) {
        $keyword = $this->conn->real_escape_string($keyword);
        
        // Mặc định tìm kiếm trong admin users đang hoạt động (hide = 0)
        $whereClauses = ["role = 'admin'", "hide = 0", "(name LIKE '%$keyword%' OR email LIKE '%$keyword%' OR phone LIKE '%$keyword%')"];
        
        $where = "WHERE " . implode(" AND ", $whereClauses);
        
        $sql = "SELECT * FROM user $where";
        $result = $this->conn->query($sql);
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getById($id) {
        $id = intval($id);
        $sql = "SELECT * FROM user WHERE id_User = $id AND role = 'admin'";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }

    public function addCustomer($data) {
        $name = $this->conn->real_escape_string($data['name'] ?? '');
        $email = $this->conn->real_escape_string($data['email'] ?? '');
        $password = $this->conn->real_escape_string($data['password'] ?? '');
        $phone = $this->conn->real_escape_string($data['phone'] ?? '');
        $address = $this->conn->real_escape_string($data['address'] ?? '');
        $role = 'admin';
        $hide = intval($data['hide'] ?? 0);
        $sql = "INSERT INTO user (name, email, password, phone, address, role, hide) VALUES ('$name', '$email', '$password', '$phone', '$address', '$role', $hide)";
        return $this->conn->query($sql);
    }

    public function updateCustomer($id, $data) {
        $id = intval($id);
        
        $checkSql = "SELECT id_User FROM user WHERE id_User = $id AND role = 'admin'";
        $checkResult = $this->conn->query($checkSql);
        if (!$checkResult || $checkResult->num_rows === 0) {
            return false;
        }
        
        $fields = [];
        if (isset($data['name'])) $fields[] = "name='" . $this->conn->real_escape_string($data['name']) . "'";
        if (isset($data['email'])) $fields[] = "email='" . $this->conn->real_escape_string($data['email']) . "'";
        if (isset($data['password']) && $data['password'] !== '') $fields[] = "password='" . $this->conn->real_escape_string($data['password']) . "'";
        if (isset($data['phone'])) $fields[] = "phone='" . $this->conn->real_escape_string($data['phone']) . "'";
        if (isset($data['address'])) $fields[] = "address='" . $this->conn->real_escape_string($data['address']) . "'";
        if (isset($data['hide'])) $fields[] = "hide=" . intval($data['hide']);
        
        $fields[] = "role='admin'";
        
        if (empty($fields)) return false;
        $sql = "UPDATE user SET " . implode(", ", $fields) . " WHERE id_User = $id";
        return $this->conn->query($sql);
    }

    public function deleteCustomer($id) {
        $id = intval($id);
        $sql = "DELETE FROM user WHERE id_User = $id AND role = 'admin'";
        return $this->conn->query($sql);
    }

    public function getLastError() {
        return $this->conn->error;
    }

    public function getRecentCustomerNotifications($limit = 3) {
        // Kiểm tra xem bảng notifications có tồn tại không
        $tableExistsQuery = "SHOW TABLES LIKE 'notifications'";
        $tableExists = $this->conn->query($tableExistsQuery);
        
        if ($tableExists && $tableExists->num_rows > 0) {
            $sql = "SELECT * FROM notifications WHERE type = 'customer' AND hide = 0 ORDER BY created_at DESC LIMIT ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('i', $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            // Trả về mảng rỗng nếu bảng không tồn tại
            return [];
        }
    }

    public function addNotification($data) {
        // Kiểm tra xem bảng notifications có tồn tại không
        $tableExistsQuery = "SHOW TABLES LIKE 'notifications'";
        $tableExists = $this->conn->query($tableExistsQuery);
        
        if ($tableExists && $tableExists->num_rows > 0) {
            $stmt = $this->conn->prepare('INSERT INTO notifications (type, title, content, meta, link) VALUES (?, ?, ?, ?, ?)');
            $stmt->bind_param('sssss', $data['type'], $data['title'], $data['content'], $data['meta'], $data['link']);
            return $stmt->execute();
        } else {
            // Bảng không tồn tại, không làm gì cả
            return false;
        }
    }
}