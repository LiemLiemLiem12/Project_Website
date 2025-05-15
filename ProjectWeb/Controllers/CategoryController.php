<?php
class CategoryController extends BaseController
{    protected $categoryModel;
    protected $productModel;

    public function __construct()
    {
        parent::__construct();
    
    // Load ProductModel (nhưng không load lại CategoryModel)
    $this->loadModel('ProductModel');
    $this->productModel = new ProductModel();
    }

    /**
     * Display a listing of all categories
     */
    public function index()
    {
        // Get all visible categories ordered by their defined order
        $categories = $this->categoryModel->getAll(['id_Category', 'name', 'link', 'meta'], 100, [
            'column' => '`order`', 
            'order' => 'asc'
        ]);
        
        return $this->view('frontend.categories._detail', [
            'categories' => $categories
        ]);
    }

    /**
     * Display a specific category and its products
     */
   public function show()
{
    $categoryId = $_GET['id'] ?? null;
    
    if (!$categoryId) {
        // Redirect to categories list if no ID provided
        header('Location: index.php?controller=category&action=index');
        exit();
    }
    
    // Get the category details
    $category = $this->categoryModel->findById($categoryId);
    
    if (!$category || $category['hide'] == 1) {
        // Handle category not found or hidden
        return $this->view('frontend.errors.404', [
            'message' => 'Danh mục không tồn tại hoặc đã bị ẩn'
        ]);
    }
    
    // Xử lý các tham số lọc và sắp xếp
    $filters = [
        'price_min' => isset($_GET['price_min']) ? (int)$_GET['price_min'] : 100000,
        'price_max' => isset($_GET['price_max']) ? (int)$_GET['price_max'] : 2000000,
        'sizes' => isset($_GET['size']) && is_array($_GET['size']) ? $_GET['size'] : [],
        'sort' => $_GET['sort'] ?? 'newest'
    ];

    // Lấy giá từ thanh trượt (slider)
    if (isset($_GET['price']) && !empty($_GET['price'])) {
        $filters['price_max'] = (int)$_GET['price'];
    }
    
    // Get products in this category with filters
    $products = $this->productModel->getFilteredProducts($categoryId, $filters);
    
    return $this->view('frontend.categories._detail', [
        'category' => $category,
        'products' => $products,
        'filters' => $filters
    ]);
}

    /**
     * Admin functionality to create a new category
     */
    public function create()
    {
        // Check if user is admin
        // This would use your authentication system
        
        return $this->view('frontend.admin.categories.create');
    }

    /**
     * Admin functionality to store a new category
     */
    public function store()
    {
        // Check if user is admin
        // This would use your authentication system
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'link' => $_POST['link'] ?? '',
                'meta' => $_POST['meta'] ?? '',
                'hide' => isset($_POST['hide']) ? 1 : 0,
                'order' => $_POST['order'] ?? 0
            ];
            
            // Validation
            if (empty($data['name'])) {
                $_SESSION['error'] = 'Tên danh mục không được để trống';
                return $this->view('frontend.admin.categories.create', ['data' => $data]);
            }
            
            // Generate link and meta if not provided
            if (empty($data['link'])) {
                $data['link'] = '/' . $this->toSlug($data['name']);
            }
            
            if (empty($data['meta'])) {
                $data['meta'] = $this->toSlug($data['name']);
            }
            
            // Store the category
            $result = $this->categoryModel->store($data);
            
            if ($result) {
                $_SESSION['success'] = 'Đã tạo danh mục thành công';
                header('Location: index.php?controller=category&action=index');
                exit();
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                return $this->view('frontend.admin.categories.create', ['data' => $data]);
            }
        }
    }

    /**
     * Admin functionality to edit a category
     */
    public function edit()
    {
        // Check if user is admin
        // This would use your authentication system
        
        $categoryId = $_GET['id'] ?? null;
        
        if (!$categoryId) {
            $_SESSION['error'] = 'ID danh mục không hợp lệ';
            header('Location: index.php?controller=category&action=index');
            exit();
        }
        
        $category = $this->categoryModel->findById($categoryId);
        
        if (!$category) {
            $_SESSION['error'] = 'Danh mục không tồn tại';
            header('Location: index.php?controller=category&action=index');
            exit();
        }
        
        return $this->view('frontend.admin.categories.edit', [
            'category' => $category
        ]);
    }

    /**
     * Admin functionality to update a category
     */
    public function update()
    {
        // Check if user is admin
        // This would use your authentication system
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryId = $_POST['id'] ?? null;
            
            if (!$categoryId) {
                $_SESSION['error'] = 'ID danh mục không hợp lệ';
                header('Location: index.php?controller=category&action=index');
                exit();
            }
            
            $data = [
                'name' => $_POST['name'] ?? '',
                'link' => $_POST['link'] ?? '',
                'meta' => $_POST['meta'] ?? '',
                'hide' => isset($_POST['hide']) ? 1 : 0,
                'order' => $_POST['order'] ?? 0
            ];
            
            // Validation
            if (empty($data['name'])) {
                $_SESSION['error'] = 'Tên danh mục không được để trống';
                return $this->redirect("index.php?controller=category&action=edit&id={$categoryId}");
            }
            
            // Generate link and meta if not provided
            if (empty($data['link'])) {
                $data['link'] = '/' . $this->toSlug($data['name']);
            }
            
            if (empty($data['meta'])) {
                $data['meta'] = $this->toSlug($data['name']);
            }
            
            // Update the category
            $result = $this->categoryModel->updateData($categoryId, $data);
            
            if ($result) {
                $_SESSION['success'] = 'Cập nhật danh mục thành công';
                header('Location: index.php?controller=category&action=index');
                exit();
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                return $this->redirect("index.php?controller=category&action=edit&id={$categoryId}");
            }
        }
    }

    /**
     * Admin functionality to delete a category
     */
    public function delete()
    {
        // Check if user is admin
        // This would use your authentication system
        
        $categoryId = $_GET['id'] ?? null;
        
        if (!$categoryId) {
            $_SESSION['error'] = 'ID danh mục không hợp lệ';
            header('Location: index.php?controller=category&action=index');
            exit();
        }
        
        // Check if category has products
        $products = $this->productModel->getByCategoryId($categoryId);
        
        if (count($products) > 0) {
            $_SESSION['error'] = 'Không thể xóa danh mục này vì có sản phẩm liên quan';
            header('Location: index.php?controller=category&action=index');
            exit();
        }
        
        // Delete the category
        $result = $this->categoryModel->deleteData($categoryId);
        
        if ($result) {
            $_SESSION['success'] = 'Xóa danh mục thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
        }
        
        header('Location: index.php?controller=category&action=index');
        exit();
    }

    /**
     * Convert a string to a slug (URL-friendly string)
     */
    private function toSlug($string)
    {
        $string = preg_replace('~[^\pL\d]+~u', '-', $string);
        $string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);
        $string = preg_replace('~[^-\w]+~', '', $string);
        $string = trim($string, '-');
        $string = preg_replace('~-+~', '-', $string);
        $string = strtolower($string);
        return $string;
    }

    /**
     * Helper method to redirect with error message
     */
    private function redirect($url)
    {
        header("Location: {$url}");
        exit();
    }
    public function getHeaderCategories()
{
    return $this->categoryModel->getCategoriesForMenu();
}
/**
 * Phương thức xử lý yêu cầu AJAX để lọc và sắp xếp sản phẩm
 */
public function filterProducts()
{
    // Kiểm tra có phải là yêu cầu AJAX không
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
        header('HTTP/1.0 403 Forbidden');
        exit;
    }
    
    $categoryId = $_GET['id'] ?? null;
    
    if (!$categoryId) {
        echo json_encode(['error' => 'ID danh mục không hợp lệ']);
        exit;
    }
    
    // Lấy thông tin danh mục
    $category = $this->categoryModel->findById($categoryId);
    
    if (!$category || $category['hide'] == 1) {
        echo json_encode(['error' => 'Danh mục không tồn tại hoặc đã bị ẩn']);
        exit;
    }
    
    // Xử lý các tham số lọc và sắp xếp
    $filters = [
        'price_min' => isset($_GET['price_min']) ? (int)$_GET['price_min'] : 100000,
        'price_max' => isset($_GET['price_max']) ? (int)$_GET['price_max'] : 2000000,
        'sizes' => isset($_GET['size']) && is_array($_GET['size']) ? $_GET['size'] : [],
        'sort' => $_GET['sort'] ?? 'newest'
    ];

    // Lấy giá từ thanh trượt (slider)
    if (isset($_GET['price']) && !empty($_GET['price'])) {
        $filters['price_max'] = (int)$_GET['price'];
    }
    
    // Lấy sản phẩm đã lọc
    $products = $this->productModel->getFilteredProducts($categoryId, $filters);
    
    // Tạo response với HTML của danh sách sản phẩm
    ob_start();
    // Sử dụng một partial view để hiển thị chỉ danh sách sản phẩm
    require('Views/frontend/partials/product_grid.php');
    $productGridHtml = ob_get_clean();
    
    echo json_encode([
        'success' => true,
        'count' => count($products),
        'html' => $productGridHtml
    ]);
    exit;
}
}

?>