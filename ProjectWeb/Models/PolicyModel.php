<?php
class PolicyModel {
    private $db;
    
    public function __construct() {
        try {
            // Kết nối đến CSDL
            $this->db = new mysqli('localhost', 'root', '', 'fashion_database');
            
            if ($this->db->connect_error) {
                throw new Exception("Kết nối CSDL thất bại: " . $this->db->connect_error);
            }
            
            $this->db->set_charset("utf8mb4");
            
        } catch (Exception $e) {
            error_log("Database Error: " . $e->getMessage());
            exit("Lỗi kết nối đến cơ sở dữ liệu, vui lòng thử lại sau.");
        }
    }
    
    /**
     * Lấy tất cả chính sách cho sidebar
     */
    public function getAllPolicies() {
        $policies = [];
        
        try {
            $sql = "SELECT id, title, image, link FROM footer_policies 
                    WHERE hide = 0 
                    ORDER BY `order` ASC";
            
            $result = $this->db->query($sql);
            
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $policies[] = $row;
                }
            }
            
            return $policies;
            
        } catch (Exception $e) {
            error_log("Error fetching policies: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy thông tin chi tiết một chính sách theo ID
     */
    public function getPolicyById($id) {
        try {
            $sql = "SELECT * FROM footer_policies WHERE id = ? AND hide = 0";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows > 0) {
                return $result->fetch_assoc();
            }
            
            return null;
            
        } catch (Exception $e) {
            error_log("Error fetching policy by ID: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Lấy chính sách đầu tiên (mặc định)
     */
    public function getDefaultPolicy() {
        try {
            $sql = "SELECT * FROM footer_policies 
                    WHERE hide = 0 
                    ORDER BY `order` ASC 
                    LIMIT 1";
            
            $result = $this->db->query($sql);
            
            if ($result && $result->num_rows > 0) {
                return $result->fetch_assoc();
            }
            
            return null;
            
        } catch (Exception $e) {
            error_log("Error fetching default policy: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Lấy thông tin cài đặt website
     * @param string|array $keys Khóa cài đặt cần lấy
     * @param string $group Nhóm cài đặt (tuỳ chọn)
     * @return array Mảng dữ liệu cài đặt
     */
    public function getSettings($keys = null, $group = null) {
        try {
            $sql = "SELECT * FROM settings WHERE 1=1";
            $params = [];
            $types = "";
            
            // Lọc theo khóa cài đặt
            if (!empty($keys)) {
                if (is_array($keys)) {
                    $placeholders = implode(',', array_fill(0, count($keys), '?'));
                    $sql .= " AND setting_key IN ($placeholders)";
                    foreach ($keys as $key) {
                        $types .= "s";
                        $params[] = $key;
                    }
                } else {
                    $sql .= " AND setting_key = ?";
                    $types .= "s";
                    $params[] = $keys;
                }
            }
            
            // Lọc theo nhóm cài đặt
            if (!empty($group)) {
                $sql .= " AND setting_group = ?";
                $types .= "s";
                $params[] = $group;
            }
            
            $stmt = $this->db->prepare($sql);
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            $settings = [];
            
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Trả về dưới dạng key-value
                    $settings[$row['setting_key']] = $row;
                }
            }
            
            return $settings;
            
        } catch (Exception $e) {
            error_log("Error fetching settings: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy thông tin các mạng xã hội
     * @return array Mảng dữ liệu mạng xã hội
     */
    public function getSocialMedia() {
        try {
            $sql = "SELECT * FROM footer_social_media WHERE hide = 0 ORDER BY `order` ASC";
            $result = $this->db->query($sql);
            
            $socialMedia = [];
            
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $socialMedia[] = $row;
                }
            }
            
            return $socialMedia;
            
        } catch (Exception $e) {
            error_log("Error fetching social media: " . $e->getMessage());
            return [];
        }
    }
} 