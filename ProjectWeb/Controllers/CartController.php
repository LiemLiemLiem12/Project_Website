<?php
class CartController extends BaseController
{
    private $productModel;

    public function __construct()
    {
         parent::__construct();
        
        // Sau đó khởi tạo các model riêng
        $this->loadModel('ProductModel');
        $this->productModel = new ProductModel();
    }

    // Display Cart Page
    public function index()
    {
        $cart = $_SESSION['cart'] ?? [];
        $cartItems = [];
        $total = 0;
        $number=0;
        foreach ($cart as $item) {
            $product = $this->productModel->findById($item['product_id']);
            if ($product) {
                $subtotal = $product['current_price'] * $item['quantity'];
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'size' => $item['size'],
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
                $number+=$item['quantity'];
            }
        }

        $this->view('frontend.cart.index', [
            'cartItems' => $cartItems,
            'total' => $total,
            'itemCount' => count($cartItems),
            'number'=> $number,
        ]);
    }

    // Add Product to Cart
    public function add()
    {
        $productId = $_GET['id'] ?? null;
        $quantity = isset($_GET['qty']) ? (int)$_GET['qty'] : 1;
        $size = $_GET['size'] ?? 'M';

        if (!$productId || $quantity <= 0) {
            $this->redirectWithMessage('index.php?controller=product&action=show&id=' . $productId, 'Invalid product information!', 'error');
            return;
        }

        $product = $this->productModel->findById($productId);
        if (!$product) {
            $this->redirectWithMessage('index.php', 'Product not found!', 'error');
            return;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Sử dụng khóa duy nhất "productId_size"
        $itemKey = $productId . '_' . $size;

        // Nếu sản phẩm đã có trong giỏ với cùng size, tăng số lượng
        if (isset($_SESSION['cart'][$itemKey])) {
            $_SESSION['cart'][$itemKey]['quantity'] += $quantity;
        } else {
            // Nếu chưa có, thêm sản phẩm mới với size riêng
            $_SESSION['cart'][$itemKey] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'size' => $size
            ];
        }

        $this->redirectWithMessage('index.php?controller=cart&action=index', 'Product added to cart!', 'success');
    }

     /**
 * Handle "Buy Now" functionality - add product directly to cart and redirect to checkout
 */
    public function buyNow()
    {
        // Check request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Phương thức không hợp lệ.'
            ]);
            return;
        }
        
        // Get product info - support both POST and GET for flexibility
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 
                        (isset($_POST['id']) ? (int)$_POST['id'] : 0);
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] :
                    (isset($_POST['qty']) ? (int)$_POST['qty'] : 1);
            $size = isset($_POST['size']) ? $_POST['size'] : 'M';
        } else {
            $productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            $quantity = isset($_GET['qty']) ? (int)$_GET['qty'] : 1;
            $size = isset($_GET['size']) ? $_GET['size'] : 'M';
        }
        
        // Validate product ID
        if (!$productId) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Sản phẩm không hợp lệ.'
            ]);
            return;
        }
        
        // Load product model if not already loaded
        if (!isset($this->productModel)) {
            $this->loadModel('ProductModel');
            $this->productModel = new ProductModel();
        }
        
        // Get product information
        $product = $this->productModel->findById($productId);
        
        if (!$product) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại.'
            ]);
            return;
        }
        
        // Validate quantity
        if ($quantity <= 0) {
            $quantity = 1; // Set default quantity if invalid
        }
        
        // Check stock availability
        if ($product[$size] < $quantity) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Sản phẩm không đủ hàng. Chỉ còn ' . $product[$size] . ' sản phẩm kích thước ' . $size . ' trong kho.'
            ]);
            return;
        }
        
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Generate a unique key for this product + size combination
        $cartKey = $productId . '-' . $size;
        
        // Clear the cart and add only this item
        $_SESSION['cart'] = [];
        
        // Add the item to cart
        $_SESSION['cart'][$cartKey] = [
            'product_id' => $productId,
            'quantity' => $quantity,
            'size' => $size,
            'name' => $product['name'],
            'price' => $product['current_price'],
            'image' => $product['main_image']
        ];
        
        // Set a flag to indicate this is a "Buy Now" order
        $_SESSION['buy_now'] = true;
        
        // Return success response with redirect information
        $this->jsonResponse([
            'success' => true,
            'message' => 'Đang chuyển đến trang đặt hàng...',
            'redirect' => 'index.php?controller=order',
            'cart_count' => 1 // Always 1 for "Buy Now"
        ]);
    }

/**
 * Helper function to send JSON response
 */
    private function jsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    // Update Product in Cart
    public function update()
    {
        $productId = $_POST['product_id'] ?? null;
        $size = $_POST['size'] ?? 'M';
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

        if (!$productId || !$size) {
            $this->redirectWithMessage('index.php?controller=cart&action=index', 'Invalid product information!', 'error');
            return;
        }

        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $productId && $item['size'] == $size) {
                if ($quantity <= 0) {
                    unset($item);
                } else {
                    $item['quantity'] = $quantity;
                }
                break;
            }
        }

        $this->redirectWithMessage('index.php?controller=cart&action=index', 'Cart updated!', 'success');
    }

    // Remove Product from Cart
    public function delete()
    {
        $productId = $_GET['product_id'] ?? null;
        $size = $_GET['size'] ?? 'M';

        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['product_id'] == $productId && $item['size'] == $size) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }

        $this->redirectWithMessage('index.php?controller=cart&action=index', 'Product removed from cart!', 'success');
    }
private function redirectWithMessage($url, $message, $type = 'success')
{
    $_SESSION['flash_message'] = [
        'message' => $message,
        'type' => $type
    ];
    header("Location: $url");
    exit();
}

    // Clear Cart
    public function clear()
    {
        $_SESSION['cart'] = [];
        $this->redirectWithMessage('index.php?controller=cart&action=index', 'Cart cleared!', 'success');
    }
    public function getCount()
{
    // Ensure this is an AJAX request
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
        header('HTTP/1.0 403 Forbidden');
        exit;
    }
    
    // Get cart from session
    $cart = $_SESSION['cart'] ?? [];
    
    // Calculate total items
    $totalItems = 0;
    foreach ($cart as $item) {
        $totalItems += $item['quantity'];
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(['count' => $totalItems]);
    exit;
}

/**
 * Method to update cart items via AJAX
 */
public function updateItem()
{
    // Ensure this is an AJAX request
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
        header('HTTP/1.0 403 Forbidden');
        exit;
    }
    
    $productId = $_POST['product_id'] ?? null;
    $size = $_POST['size'] ?? 'M';
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
    
    if (!$productId || !$size) {
        echo json_encode(['success' => false, 'message' => 'Invalid product information!']);
        exit;
    }
    
    $itemKey = $productId . '_' . $size;
    
    if ($quantity <= 0) {
        unset($_SESSION['cart'][$itemKey]);
    } else {
        if (isset($_SESSION['cart'][$itemKey])) {
            $_SESSION['cart'][$itemKey]['quantity'] = $quantity;
        }
    }
    
    // Calculate total items
    $totalItems = 0;
    foreach ($_SESSION['cart'] as $item) {
        $totalItems += $item['quantity'];
    }
    
    echo json_encode([
        'success' => true, 
        'message' => 'Cart updated!',
        'count' => $totalItems
    ]);
    exit;
}
}
