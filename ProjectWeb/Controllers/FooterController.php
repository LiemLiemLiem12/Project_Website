<?php
// Sử dụng đường dẫn tuyệt đối để tìm model
require_once 'Models/FooterPolicyModel.php';

class FooterController {
    private $policyModel;
    
    public function __construct() {
        $this->policyModel = new FooterPolicyModel();
    }
    
    /**
     * Lấy dữ liệu cho footer và render view
     */
    public function index() {
        // Lấy danh sách chính sách
        $policies = $this->policyModel->getFooterPolicies();
        
        // Lấy thông tin cài đặt cửa hàng
        $storeSettings = $this->policyModel->getStoreSettings();
        
        // Lấy danh sách mạng xã hội
        $socialMedia = $this->policyModel->getSocialMedia();
        
        // Lấy danh sách phương thức thanh toán
        $paymentMethods = $this->policyModel->getPaymentMethods();
        
        // Render footer với dữ liệu động
        require_once __DIR__ . '/../Views/frontend/partitions/frontend/footer.php';
    }
    
    /**
     * Chỉ lấy dữ liệu chính sách và trả về mảng (cho các trường hợp Ajax hoặc API)
     * @return array Dữ liệu chính sách
     */
    public function getPoliciesData() {
        return $this->policyModel->getFooterPolicies();
    }
    
    /**
     * Lấy thông tin cài đặt cửa hàng
     * @return array Thông tin cài đặt cửa hàng
     */
    public function getStoreSettings() {
        return $this->policyModel->getStoreSettings();
    }
    
    /**
     * Lấy danh sách mạng xã hội
     * @return array Danh sách mạng xã hội
     */
    public function getSocialMedia() {
        return $this->policyModel->getSocialMedia();
    }
    
    /**
     * Lấy danh sách phương thức thanh toán
     * @return array Danh sách phương thức thanh toán
     */
    public function getPaymentMethods() {
        return $this->policyModel->getPaymentMethods();
    }
}