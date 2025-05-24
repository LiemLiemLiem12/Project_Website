<?php

class SearchController extends BaseController
{
    private $productModel;

    public function __construct()
    {
        parent::__construct();
        $this->loadModel('ProductModel');
        $this->productModel = new ProductModel();
    }
        public function showByTag()
    {
        $tag = $_GET['tag'] ?? '';
        
        if (empty($tag)) {
            header('Location: index.php');
            exit;
        }

        // Xử lý các tham số lọc và sắp xếp
        $filters = [
            'price_min' => isset($_GET['price_min']) ? (int) $_GET['price_min'] : 100000,
            'price_max' => isset($_GET['price_max']) ? (int) $_GET['price_max'] : 2000000,
            'sizes' => isset($_GET['size']) && is_array($_GET['size']) ? $_GET['size'] : [],
            'sort' => $_GET['sort'] ?? 'newest'
        ];

        // Lấy giá từ thanh trượt (slider)
        if (isset($_GET['price']) && !empty($_GET['price'])) {
            $filters['price_max'] = (int) $_GET['price'];
        }

        // Get products by tag with filters
        $products = $this->productModel->getFilteredProductsByTag($tag, $filters);

        // Tạo category ảo cho tag
        $category = [
            'id_Category' => 'tag',
            'name' => 'Tag: ' . ucfirst($tag)
        ];

        $this->view('frontend.categories._detail', [
            'category' => $category,
            'products' => $products,
            'filters' => $filters,
            'searchTag' => $tag,
            'isTagSearch' => true
        ]);
    }

    /**
     * AJAX filter cho tag search  
     */
    public function filterByTag()
    {
        // Kiểm tra có phải là yêu cầu AJAX không
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
            header('HTTP/1.0 403 Forbidden');
            exit;
        }

        $tag = $_GET['tag'] ?? '';
        if (empty($tag)) {
            echo json_encode(['error' => 'Tag không hợp lệ']);
            exit;
        }

        // Xử lý các tham số lọc và sắp xếp  
        $filters = [
            'price_min' => isset($_GET['price_min']) ? (int) $_GET['price_min'] : 100000,
            'price_max' => isset($_GET['price_max']) ? (int) $_GET['price_max'] : 2000000,
            'sizes' => isset($_GET['size']) && is_array($_GET['size']) ? $_GET['size'] : [],
            'sort' => $_GET['sort'] ?? 'newest'
        ];

        // Lấy giá từ thanh trượt (slider)
        if (isset($_GET['price']) && !empty($_GET['price'])) {
            $filters['price_max'] = (int) $_GET['price'];
        }

        // Lấy sản phẩm đã lọc theo tag
        $products = $this->productModel->getFilteredProductsByTag($tag, $filters);

        // Tạo response với HTML của danh sách sản phẩm
        ob_start();
        require('Views/frontend/categories/product_grid.php');
        $productGridHtml = ob_get_clean();
        
        echo json_encode([
            'success' => true,
            'count' => count($products),
            'html' => $productGridHtml
        ]);
        exit;
    }

    public function index()
    {
        $searchQuery = $_GET['q'] ?? '';
        $page = $_GET['page'] ?? 1;
        $itemsPerPage = 12;
        
        if (empty($searchQuery)) {
            // Nếu không có từ khóa, chuyển về trang chủ
            header('Location: index.php');
            exit;
        }

        // Tìm kiếm sản phẩm theo tên và tag
        $searchResult = $this->productModel->searchProducts($searchQuery);
        
        // Phân trang
        $totalProducts = count($searchResult);
        $totalPages = ceil($totalProducts / $itemsPerPage);
        $currentPage = max(1, min($totalPages, (int)$page));
        $offset = ($currentPage - 1) * $itemsPerPage;
        $products = array_slice($searchResult, $offset, $itemsPerPage);

        $this->view('frontend.search.index', [
            'products' => $products,
            'searchQuery' => $searchQuery,
            'totalProducts' => $totalProducts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'itemsPerPage' => $itemsPerPage
        ]);
    }

    public function suggestions()
    {
        $query = $_GET['q'] ?? '';
        
        if (strlen($query) < 2) {
            echo json_encode([]);
            return;
        }

        $suggestions = $this->productModel->getSearchSuggestions($query);
        echo json_encode($suggestions);
    }
}