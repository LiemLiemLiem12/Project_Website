<?php
class MaintenanceMiddleware {
    private $settingModel;
    private $excludedControllers = [
        'adminlogin',
        'admindashboard',
        'admincustomer',
        'adminproduct',
        'admincategory',
        'adminorder',
        'adminsetting',
        'adminstatistic',
        'adminreport',
        'adminhome',
        'adminblog'
    ];

    public function __construct() {
        require_once 'Models/SettingModel.php';
        $this->settingModel = new SettingModel();
    }

    public function handle() {
        // Kiểm tra maintenance mode
        $maintenanceSetting = $this->settingModel->getSettingByKey('maintenance_mode');
        
        if ($maintenanceSetting && $maintenanceSetting['setting_value'] == '1') {
            // Lấy controller từ query string
            $controller = strtolower($_REQUEST['controller'] ?? 'home');
            
            // Kiểm tra nếu là admin controller
            if (in_array($controller, $this->excludedControllers)) {
                return true; // Cho phép truy cập
            }
            
            // Cho phép truy cập các file static
            $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $staticPaths = ['/css/', '/js/', '/images/', '/assets/', '/upload/'];
            foreach ($staticPaths as $path) {
                if (strpos($currentPath, $path) !== false) {
                    return true;
                }
            }

            // Nếu không phải admin controller, hiển thị trang bảo trì
            require 'Views/frontend/admin/AdminSetting/maintenace.php';
            exit();
        }

        return true;
    }
} 