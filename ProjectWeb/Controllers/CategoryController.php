<?php
class CartController extends BaseController
{
    private $productModel;
    private $cartModel;

    public function __construct()
    {
        // Khởi động session để lưu trữ giỏ hàng
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Load các model cần thiết
        $this->loadModel('ProductModel');
        $this->productModel = new ProductModel;
        
        // Có thể thêm CartModel nếu cần lưu trữ vào database
        // $this->loadModel('CartModel');
        // $this->cartModel = new CartModel;
    }

    /**
     * Hiển thị trang giỏ hàng
     * Tương tự như việc mở tủ để xem những gì bạn đã chuẩn bị để mua
     */
    public function index()
    {
        // Lấy dữ liệu giỏ hàng từ session
        $cart = $_SESSION['cart'] ?? [];
        $cartItems = [];
        $total = 0;

        // Nếu giỏ hàng không rỗng, lấy thông tin chi tiết của từng sản phẩm
        if (!empty($cart)) {
            foreach ($cart as $productId => $item) {
                // Lấy thông tin sản phẩm từ database
                $product = $this->productModel->findById($productId);
                
                if ($product) {
                    // Kết hợp thông tin sản phẩm với thông tin trong giỏ hàng
                    $cartItem = [
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'size' => $item['size'] ?? 'M',
                        'subtotal' => $product['current_price'] * $item['quantity']
                    ];
                    
                    $cartItems[] = $cartItem;
                    $total += $cartItem['subtotal'];
                }
            }
        }

        // Truyền dữ liệu đến view
        $this->view('frontend.cart.index', [
            'cartItems' => $cartItems,
            'total' => $total,
            'itemCount' => count($cartItems)
        ]);
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     * Giống như việc đặt một món hàng vào giỏ mua sắm
     */
    public function add()
    {
        // Lấy thông tin từ request
        $productId = $_GET['id'] ?? null;
        $quantity = isset($_GET['qty']) ? (int)$_GET['qty'] : 1;
        $size = $_GET['size'] ?? 'M';

        // Kiểm tra tính hợp lệ của dữ liệu
        if (!$productId || $quantity <= 0) {
            $this->redirectWithMessage('index.php?controller=product&action=show&id=' . $productId, 
                'Thông tin sản phẩm không hợp lệ!', 'error');
            return;
        }

        // Lấy thông tin sản phẩm từ database
        $product = $this->productModel->findById($productId);
        
        if (!$product) {
            $this->redirectWithMessage('index.php', 
                'Sản phẩm không tồn tại!', 'error');
            return;
        }

        // Kiểm tra số lượng sản phẩm có sẵn
        $availableQuantity = $product[$size] ?? 0;
        
        if ($quantity > $availableQuantity) {
            $this->redirectWithMessage('index.php?controller=product&action=show&id=' . $productId, 
                'Số lượng yêu cầu vượt quá số lượng có sẵn!', 'error');
            return;
        }

        // Khởi tạo giỏ hàng nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Tạo key duy nhất cho từng sản phẩm với size khác nhau
        $itemKey = $productId . '_' . $size;

        // Nếu sản phẩm đã có trong giỏ, tăng số lượng
        if (isset($_SESSION['cart'][$itemKey])) {
            $currentQuantity = $_SESSION['cart'][$itemKey]['quantity'];
            $newQuantity = $currentQuantity + $quantity;
            
            // Kiểm tra lại số lượng sau khi cộng thêm
            if ($newQuantity > $availableQuantity) {
                $this->redirectWithMessage('index.php?controller=cart&action=index', 
                    'Tổng số lượng vượt quá số hàng có sẵn!', 'error');
                return;
            }
            
            $_SESSION['cart'][$itemKey]['quantity'] = $newQuantity;
        } else {
            // Nếu chưa có, thêm mới vào giỏ
            $_SESSION['cart'][$itemKey] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'size' => $size,
                'added_at' => time()
            ];
        }

        // Chuyển hướng với thông báo thành công
        $this->redirectWithMessage('index.php?controller=cart&action=index', 
            'Đã thêm sản phẩm vào giỏ hàng!', 'success');
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     * Giống như việc thay đổi số lượng món hàng trong giỏ
     */
    public function update()
    {
        $itemKey = $_POST['item_key'] ?? null;
        $newQuantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

        if (!$itemKey || !isset($_SESSION['cart'][$itemKey])) {
            $this->redirectWithMessage('index.php?controller=cart&action=index', 
                'Sản phẩm không tồn tại trong giỏ hàng!', 'error');
            return;
        }

        // Nếu số lượng là 0, xóa sản phẩm khỏi giỏ
        if ($newQuantity <= 0) {
            unset($_SESSION['cart'][$itemKey]);
            $this->redirectWithMessage('index.php?controller=cart&action=index', 
                'Đã xóa sản phẩm khỏi giỏ hàng!', 'success');
            return;
        }

        // Kiểm tra số lượng có sẵn
        $cartItem = $_SESSION['cart'][$itemKey];
        $product = $this->productModel->findById($cartItem['product_id']);
        $availableQuantity = $product[$cartItem['size']] ?? 0;

        if ($newQuantity > $availableQuantity) {
            $this->redirectWithMessage('index.php?controller=cart&action=index', 
                'Số lượng yêu cầu vượt quá số hàng có sẵn!', 'error');
            return;
        }

        // Cập nhật số lượng
        $_SESSION['cart'][$itemKey]['quantity'] = $newQuantity;
        
        $this->redirectWithMessage('index.php?controller=cart&action=index', 
            'Đã cập nhật số lượng sản phẩm!', 'success');
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     * Giống như việc lấy một món hàng ra khỏi giỏ
     */
    public function remove()
    {
        $itemKey = $_GET['item'] ?? null;

        if (!$itemKey || !isset($_SESSION['cart'][$itemKey])) {
            $this->redirectWithMessage('index.php?controller=cart&action=index', 
                'Sản phẩm không tồn tại trong giỏ hàng!', 'error');
            return;
        }

        unset($_SESSION['cart'][$itemKey]);
        
        $this->redirectWithMessage('index.php?controller=cart&action=index', 
            'Đã xóa sản phẩm khỏi giỏ hàng!', 'success');
    }

    /**
     * Xóa toàn bộ giỏ hàng
     * Giống như việc đổ sạch giỏ hàng
     */
    public function clear()
    {
        $_SESSION['cart'] = [];
        
        $this->redirectWithMessage('index.php?controller=cart&action=index', 
            'Đã xóa toàn bộ giỏ hàng!', 'success');
    }

    /**
     * Xử lý "Mua ngay"
     * Thêm sản phẩm vào giỏ và chuyển ngay đến trang thanh toán
     */
    public function buynow()
    {
        // Thực hiện như hàm add
        $productId = $_GET['id'] ?? null;
        $quantity = isset($_GET['qty']) ? (int)$_GET['qty'] : 1;
        $size = $_GET['size'] ?? 'M';

        // Xóa giỏ hàng hiện tại (vì mua ngay)
        $_SESSION['cart'] = [];

        // Thêm sản phẩm vào giỏ hàng mới
        if ($productId) {
            $itemKey = $productId . '_' . $size;
            $_SESSION['cart'][$itemKey] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'size' => $size,
                'added_at' => time()
            ];
        }

        // Chuyển thẳng đến checkout
        header('Location: index.php?controller=checkout&action=index');
        exit;
    }

    /**
     * Chuyển đến trang thanh toán
     * Giống như việc đến quầy thu ngân
     */
    public function checkout()
    {
        // Kiểm tra giỏ hàng có sản phẩm không
        if (empty($_SESSION['cart'])) {
            $this->redirectWithMessage('index.php?controller=cart&action=index', 
                'Giỏ hàng của bạn đang trống!', 'error');
            return;
        }

        // Tính tổng tiền
        $total = $this->calculateTotal();

        // Chuyển đến trang checkout
        header('Location: index.php?controller=checkout&action=index');
        exit;
    }

    /**
     * Hàm helper để tính tổng tiền giỏ hàng
     */
    private function calculateTotal()
    {
        $total = 0;
        $cart = $_SESSION['cart'] ?? [];

        foreach ($cart as $item) {
            $product = $this->productModel->findById($item['product_id']);
            if ($product) {
                $total += $product['current_price'] * $item['quantity'];
            }
        }

        return $total;
    }

    /**
     * Hàm helper để chuyển hướng với thông báo
     */
    private function redirectWithMessage($url, $message, $type = 'info')
    {
        $_SESSION['flash_message'] = [
            'type' => $type,
            'message' => $message
        ];
        
        header("Location: " . $url);
        exit;
    }

    /**
     * Lấy số lượng items trong giỏ hàng (cho hiển thị ở header)
     */
    public function getCartCount()
    {
        $count = 0;
        $cart = $_SESSION['cart'] ?? [];
        
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        
        return $count;
    }

    /**
     * Lấy dữ liệu giỏ hàng (AJAX)
     */
    public function getCartData()
    {
        header('Content-Type: application/json');
        
        $cart = $_SESSION['cart'] ?? [];
        $cartItems = [];
        $total = 0;

        foreach ($cart as $itemKey => $item) {
            $product = $this->productModel->findById($item['product_id']);
            
            if ($product) {
                $subtotal = $product['current_price'] * $item['quantity'];
                $cartItems[] = [
                    'key' => $itemKey,
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'size' => $item['size'],
                    'subtotal' => $subtotal
                ];
                $total += $subtotal;
            }
        }

        echo json_encode([
            'items' => $cartItems,
            'total' => $total,
            'count' => array_sum(array_column($cartItems, 'quantity'))
        ]);
        exit;
    }
}