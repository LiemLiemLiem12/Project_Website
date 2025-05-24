<?php
require_once 'Models/SearchModel.php';

class SearchController {
    private $searchModel;
    
    public function __construct() {
        $this->searchModel = new SearchModel();
    }
    
    /**
     * Hiển thị kết quả tìm kiếm dạng trang danh mục
     */
    public function index() {
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        if (empty($keyword)) {
            header('Location: index.php');
            exit;
        }
        
        // Lấy danh sách sản phẩm từ model
        $products = $this->searchModel->searchProducts($keyword);
        
        // Tạo "fake category" để dùng template categories/show hiện có
        $category = [
            'name' => 'Kết quả tìm kiếm: "' . htmlspecialchars($keyword) . '"',
            'id_Category' => 0,
            'is_search_results' => true
        ];
        
        // Tạo filters giả để template hiện tại vẫn hoạt động
        $filters = [
            'price_max' => 2000000,
            'sort' => 'newest',
            'sizes' => []
        ];
        
        // Hiển thị view
        require_once('Views/frontend/categories/show.php');
    }
    
    /**
     * API trả về kết quả tìm kiếm nhanh dạng JSON
     */
    public function quick() {
        header('Content-Type: application/json');
        
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        if (empty($keyword)) {
            echo json_encode(['success' => false, 'products' => []]);
            exit;
        }
        
        $products = $this->searchModel->quickSearchProducts($keyword);
        
        // Định dạng lại dữ liệu sản phẩm cho dễ hiển thị
        $formattedProducts = [];
        foreach ($products as $product) {
            $formattedProducts[] = [
                'id' => $product['id_product'],
                'name' => $product['name'],
                'price' => number_format($product['current_price'], 0, ',', '.') . '₫',
                'originalPrice' => $product['original_price'] > $product['current_price'] ? 
                    number_format($product['original_price'], 0, ',', '.') . '₫' : '',
                'image' => '/Project_Website/ProjectWeb/upload/img/All-Product/' . $product['main_image'],
                'url' => 'index.php?controller=product&action=show&id=' . $product['id_product']
            ];
        }
        
        echo json_encode([
            'success' => true,
            'products' => $formattedProducts,
            'allResultsUrl' => 'index.php?controller=search&q=' . urlencode($keyword)
        ]);
    }
        /**
     * Xử lý tìm kiếm bằng AJAX
     */
    public function ajaxSearch() {
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        if (empty($keyword)) {
            echo json_encode(['success' => false, 'message' => 'Từ khóa không hợp lệ']);
            exit;
        }
        
        $products = $this->searchModel->searchProducts($keyword);
        
        // Chỉ trả về HTML của phần kết quả sản phẩm, không bao gồm header/footer
        require('Views/frontend/categories/product_grid.php');
    }
}
?>