<?php
require_once 'Models/AdminLoginModel.php';

class AdminLoginController {
    private $model;
    
    public function __construct() {
        $this->model = new AdminLoginModel();
    }
    
    /**
     * Hiển thị trang đăng nhập hoặc xử lý đăng nhập
     */
    public function index() {
        // Nếu đã đăng nhập, chuyển hướng đến trang dashboard
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
            header('Location: index.php?controller=admindashboard');
            exit;
        }
        
        // Xử lý form đăng nhập
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
        } else {
            // Hiển thị form đăng nhập
            require_once __DIR__ . '/../Views/frontend/admin/AdminLogin/index.php';
        }
    }
    
    /**
     * Xử lý đăng nhập
     */
    private function handleLogin() {
        try {
            // Validate dữ liệu đầu vào
            if (empty($_POST['email']) || empty($_POST['password'])) {
                throw new Exception("Vui lòng nhập đầy đủ email và mật khẩu");
            }
            
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email không hợp lệ");
            }
            
            // Kiểm tra đăng nhập
            $admin = $this->model->checkAdminLogin($email, $password);
            
            if ($admin) {
                // Đăng nhập thành công
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id_User'];
                $_SESSION['admin_name'] = $admin['name'];
                $_SESSION['admin_email'] = $admin['email'];
                
                // Cập nhật thời gian đăng nhập cuối
                $this->model->updateLastLogin($admin['id_User']);
                
                // Chuyển hướng đến trang dashboard
                header('Location: index.php?controller=admindashboard');
                exit;
            } else {
                throw new Exception("Email hoặc mật khẩu không đúng");
            }
            
        } catch (Exception $e) {
            // Hiển thị form đăng nhập với thông báo lỗi
            $error = $e->getMessage();
            require_once __DIR__ . '/../Views/frontend/admin/AdminLogin/index.php';
        }
    }
    
    /**
     * Đăng xuất
     */
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Xóa tất cả session
        session_unset();
        session_destroy();
        
        // Chuyển hướng về trang đăng nhập
        header('Location: index.php?controller=adminlogin');
        exit;
    }
}
