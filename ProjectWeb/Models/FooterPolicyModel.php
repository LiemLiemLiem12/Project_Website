<?php
class FooterPolicyModel {
    private $conn;

    public function __construct() {
        try {
            // Kết nối đến CSDL
            $this->conn = new mysqli('localhost', 'root', '', 'fashion_database');
            
            if ($this->conn->connect_error) {
                throw new Exception("Kết nối CSDL thất bại: " . $this->conn->connect_error);
            }
            
            $this->conn->set_charset("utf8mb4");
            
        } catch (Exception $e) {
            error_log("Database Error: " . $e->getMessage());
            exit("Lỗi kết nối đến cơ sở dữ liệu, vui lòng thử lại sau.");
        }
    }

    /**
     * Lấy danh sách các chính sách hiển thị trong footer
     * @return array Danh sách các chính sách
     */
    public function getFooterPolicies() {
        $policies = [];
        
        try {
            // Truy vấn lấy các chính sách được hiển thị (hide = 0), sắp xếp theo order
            $sql = "SELECT id, title, image, link, meta FROM footer_policies 
                    WHERE hide = 0 
                    ORDER BY `order` ASC";
            
            $result = $this->conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Nếu có ảnh, thêm đường dẫn đầy đủ
                    if (!empty($row['image'])) {
                        $row['image'] = 'upload/img/Footer/' . $row['image'];
                    }
                    
                    $policies[] = $row;
                }
            }
            
            return $policies;
            
        } catch (Exception $e) {
            error_log("Error fetching footer policies: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy thông tin cài đặt của cửa hàng từ bảng settings
     * @return array Mảng các cài đặt với key là setting_key
     */
    public function getStoreSettings() {
        $settings = [];
        
        try {
            // Truy vấn lấy các cài đặt cần thiết
            $sql = "SELECT setting_key, setting_value FROM settings 
                   WHERE setting_key IN ('site_name', 'site_description', 'admin_email', 
                   'contact_phone', 'contact_address', 'logo_path')";
            
            $result = $this->conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $settings[$row['setting_key']] = $row['setting_value'];
                }
            }
            
            // Thiết lập giá trị mặc định nếu không tìm thấy trong database
            $defaults = [
                'site_name' => 'RSStore',
                'site_description' => 'Chuỗi Phân Phối Thời Trang Nam Chuẩn Hiệu',
                'admin_email' => 'cs@RSStore.com',
                'contact_phone' => '02871006789',
                'contact_address' => 'TP. Hồ Chí Minh, Việt Nam',
                'logo_path' => '/Project_Website/ProjectWeb/upload/img/Header/logo.png'
            ];
            
            // Áp dụng giá trị mặc định cho bất kỳ cài đặt nào không được tìm thấy
            foreach ($defaults as $key => $value) {
                if (!isset($settings[$key])) {
                    $settings[$key] = $value;
                }
            }
            
            return $settings;
            
        } catch (Exception $e) {
            error_log("Error fetching store settings: " . $e->getMessage());
            return [
                'site_name' => 'RSStore',
                'site_description' => 'Chuỗi Phân Phối Thời Trang Nam Chuẩn Hiệu',
                'admin_email' => 'cs@RSStore.com',
                'contact_phone' => '02871006789',
                'contact_address' => 'TP. Hồ Chí Minh, Việt Nam',
                'logo_path' => '/Project_Website/ProjectWeb/upload/img/Header/logo.png'
            ];
        }
    }
    
    /**
     * Lấy danh sách các mạng xã hội từ bảng footer_social_media
     * @return array Danh sách các mạng xã hội
     */
    public function getSocialMedia() {
        $socialMedia = [];
        
        try {
            // Truy vấn lấy các mạng xã hội được hiển thị (hide = 0), sắp xếp theo order
            $sql = "SELECT id, title, icon, link, meta FROM footer_social_media 
                    WHERE hide = 0 
                    ORDER BY `order` ASC";
            
            $result = $this->conn->query($sql);
            
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
    
    /**
     * Lấy danh sách các phương thức thanh toán từ bảng footer_payment_methods
     * @return array Danh sách các phương thức thanh toán
     */
    public function getPaymentMethods() {
        $paymentMethods = [];
        
        try {
            // Truy vấn lấy các phương thức thanh toán được hiển thị (hide = 0), sắp xếp theo order
            $sql = "SELECT id, title, image, meta FROM footer_payment_methods 
                    WHERE hide = 0 
                    ORDER BY `order` ASC";
            
            $result = $this->conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Thêm đường dẫn đầy đủ cho ảnh
                    if (!empty($row['image'])) {
                        $row['image'] = '/Project_Website/ProjectWeb/upload/img/Footer/' . $row['image'];
                    } else {
                        // Ảnh mặc định nếu không có
                        $row['image'] = '/Project_Website/ProjectWeb/upload/img/Footer/ThanhToanCOD.webp';
                    }
                    
                    $paymentMethods[] = $row;
                }
            }
            
            return $paymentMethods;
            
        } catch (Exception $e) {
            error_log("Error fetching payment methods: " . $e->getMessage());
            return [];
        }
    }
}