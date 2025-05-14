<?php
require_once 'Models/SettingModel.php';
class AdminSettingController {
    private $settingModel;
    
    public function __construct() {
        $this->settingModel = new SettingModel();
    }
    
    public function index() {
        // Lấy danh sách tất cả các cài đặt
        $settings = $this->settingModel->getAllSettings();
        
        // Nhóm cài đặt theo nhóm
        $settingGroups = [];
        foreach ($settings as $setting) {
            $group = $setting['setting_group'];
            if (!isset($settingGroups[$group])) {
                $settingGroups[$group] = [];
            }
            $settingGroups[$group][] = $setting;
        }
        
        // Hiển thị view với dữ liệu
        require_once './Views/frontend/admin/AdminSetting/index.php';
    }
    
    public function update() {
        // Xử lý form submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lặp qua tất cả các trường đã gửi
            foreach ($_POST as $key => $value) {
                // Bỏ qua trường submit và các trường không phải setting
                if ($key === 'submit' || strpos($key, 'setting_') !== 0) continue;
                
                // Lấy setting_key từ tên trường (setting_site_name -> site_name)
                $settingKey = substr($key, 8);
                
                // Cập nhật giá trị cài đặt
                $this->settingModel->updateSetting($settingKey, $value);
            }
            
            // Xử lý upload file nếu có
            // Xử lý upload file nếu có
if (!empty($_FILES)) {
    foreach ($_FILES as $key => $file) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            // Kiểm tra nếu là trường setting file
            if (strpos($key, 'setting_') === 0) {
                $settingKey = substr($key, 8);
                $setting = $this->settingModel->getSettingByKey($settingKey);
                
                if ($setting && $setting['setting_type'] === 'file') {
                    // Xác định thư mục lưu trữ dựa trên setting_key
                    $uploadDir = './upload/img/settings/';
                    $fileName = basename($file['name']);
                    
                    // Nếu là logo hoặc favicon, lưu vào thư mục Header
                    if ($settingKey === 'logo' || $settingKey === 'site_logo') {
                        $uploadDir = './upload/img/Header/';
                        // Không đổi tên file, vẫn giữ tên gốc
                    }
                    
                    $uploadPath = $uploadDir . $fileName;
                    
                    // Tạo thư mục nếu chưa tồn tại
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    // Di chuyển file tạm sang thư mục đích
                    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                        // Cập nhật đường dẫn file trong database
                        // Nếu là logo, sử dụng đường dẫn với định dạng /Project_Website/ProjectWeb/upload/img/Header/
                        if ($settingKey === 'logo' || $settingKey === 'site_logo') {
                            $this->settingModel->updateSetting($settingKey, '/Project_Website/ProjectWeb/upload/img/Header/' . $fileName);
                        } else {
                            $this->settingModel->updateSetting($settingKey, '/Project_Website/ProjectWeb/upload/img/settings/' . $fileName);
                        }
                    }
                }
            }
        }
    }
}
            
            // Chuyển hướng với thông báo thành công
            header('Location: index.php?controller=adminsetting&success=1');
            exit;
        }
        
        // Nếu không phải POST request, chuyển hướng về trang settings
        header('Location: index.php?controller=adminsetting');
        exit;
    }
    
    // Phương thức để lấy giá trị cài đặt theo key
    public function getSetting($key, $default = '') {
        $setting = $this->settingModel->getSettingByKey($key);
        return $setting ? $setting['setting_value'] : $default;
    }
}
?>