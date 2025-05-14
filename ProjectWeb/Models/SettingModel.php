<?php
class SettingModel {
    private $conn;
    
    public function __construct() {
        // Kết nối database trực tiếp, không kế thừa
        $this->conn = mysqli_connect('localhost', 'root', '', 'fashion_database');
        mysqli_set_charset($this->conn, "utf8");
        
        if (mysqli_connect_errno()) {
            die("Kết nối database thất bại: " . mysqli_connect_error());
        }
    }
    
    // Lấy tất cả cài đặt
    public function getAllSettings() {
        $sql = "SELECT * FROM settings ORDER BY setting_group, id";
        $query = mysqli_query($this->conn, $sql);
        
        $settings = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $settings[] = $row;
        }
        
        return $settings;
    }
    
    // Lấy cài đặt theo nhóm
    public function getSettingsByGroup($group) {
        $group = mysqli_real_escape_string($this->conn, $group);
        $sql = "SELECT * FROM settings WHERE setting_group = '{$group}' ORDER BY id";
        $query = mysqli_query($this->conn, $sql);
        
        $settings = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $settings[] = $row;
        }
        
        return $settings;
    }
    
    // Lấy cài đặt theo key
    public function getSettingByKey($key) {
        $key = mysqli_real_escape_string($this->conn, $key);
        $sql = "SELECT * FROM settings WHERE setting_key = '{$key}' LIMIT 1";
        $query = mysqli_query($this->conn, $sql);
        
        return mysqli_fetch_assoc($query);
    }
    
    // Cập nhật giá trị cài đặt
    public function updateSetting($key, $value) {
        $key = mysqli_real_escape_string($this->conn, $key);
        $value = mysqli_real_escape_string($this->conn, $value);
        
        $sql = "UPDATE settings SET setting_value = '{$value}', updated_at = NOW() WHERE setting_key = '{$key}'";
        return mysqli_query($this->conn, $sql);
    }
    
    // Thêm cài đặt mới
    public function addSetting($data) {
        $key = mysqli_real_escape_string($this->conn, $data['setting_key']);
        $value = mysqli_real_escape_string($this->conn, $data['setting_value']);
        $group = mysqli_real_escape_string($this->conn, $data['setting_group']);
        $type = mysqli_real_escape_string($this->conn, $data['setting_type']);
        $label = mysqli_real_escape_string($this->conn, $data['setting_label']);
        $description = mysqli_real_escape_string($this->conn, $data['setting_description'] ?? '');
        
        $sql = "INSERT INTO settings (setting_key, setting_value, setting_group, setting_type, setting_label, setting_description) 
                VALUES ('{$key}', '{$value}', '{$group}', '{$type}', '{$label}', '{$description}')
                ON DUPLICATE KEY UPDATE 
                setting_value = '{$value}', 
                setting_group = '{$group}', 
                setting_type = '{$type}', 
                setting_label = '{$label}', 
                setting_description = '{$description}'";
                
        return mysqli_query($this->conn, $sql);
    }
    
    // Xóa cài đặt
    public function deleteSetting($key) {
        $key = mysqli_real_escape_string($this->conn, $key);
        $sql = "DELETE FROM settings WHERE setting_key = '{$key}'";
        return mysqli_query($this->conn, $sql);
    }
}
?>