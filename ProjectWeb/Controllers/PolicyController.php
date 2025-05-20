<?php
require_once 'Models/PolicyModel.php';

class PolicyController {
    private $policyModel;
    
    public function __construct() {
        $this->policyModel = new PolicyModel();
    }
    
    /**
     * Phương thức mặc định, hiển thị chính sách đầu tiên
     */
    public function index() {
        // Lấy chính sách đầu tiên (mặc định)
        $currentPolicy = $this->policyModel->getDefaultPolicy();
        
        // Lấy danh sách tất cả chính sách cho sidebar
        $allPolicies = $this->policyModel->getAllPolicies();
        
        // Hiển thị view với dữ liệu
        $this->renderView($currentPolicy, $allPolicies);
    }
    
    /**
     * Hiển thị một chính sách cụ thể theo ID
     */
    public function show() {
        // Lấy ID từ URL
        $policyId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        // Lấy thông tin chính sách theo ID
        $currentPolicy = $policyId > 0 ? $this->policyModel->getPolicyById($policyId) : null;
        
        // Nếu không tìm thấy, lấy chính sách mặc định
        if (!$currentPolicy) {
            $currentPolicy = $this->policyModel->getDefaultPolicy();
        }
        
        // Lấy danh sách tất cả chính sách cho sidebar
        $allPolicies = $this->policyModel->getAllPolicies();
        
        // Hiển thị view với dữ liệu
        $this->renderView($currentPolicy, $allPolicies);
    }
    
    /**
     * Hiển thị giao diện chính sách
     */
    private function renderView($currentPolicy, $allPolicies) {
        // Thêm CSS vào header - Chỉ các CSS đặc trưng cho trang chính sách
        // CSS của Bootstrap đã được include trong header
        echo '<link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Policy.css">';
        
        // Lấy thông tin từ bảng settings
        $settingKeys = ['contact_phone', 'admin_email', 'site_name'];
        $settings = $this->policyModel->getSettings($settingKeys);
        
        // Lấy thông tin mạng xã hội
        $socialMedia = $this->policyModel->getSocialMedia();
        
        // Tạo mảng dữ liệu để truyền vào view
        $data = [
            'policy' => $currentPolicy,
            'policies' => $allPolicies,
            'settings' => $settings,
            'socialMedia' => $socialMedia
        ];
        
        // Hiển thị header
        view('frontend.partitions.frontend.header');
        
        // Hiển thị nội dung chính sách
        view('frontend.policy.index', $data);
        
        // Thêm JavaScript TRƯỚC khi hiển thị footer
        echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
        echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';
        echo '<script src="/Project_Website/ProjectWeb/layout/js/Policy.js"></script>';
        
        // Hiển thị footer
        view('frontend.partitions.frontend.footer');
    }
} 