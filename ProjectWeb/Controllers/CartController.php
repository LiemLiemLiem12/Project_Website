<?php
class CartController extends BaseController
{
    private $productModel;
    private $cartModel;

    public function __construct()
    {
        parent::__construct();
        
        // Khởi tạo các model cần thiết
        $this->loadModel('ProductModel');
        $this->productModel = new ProductModel();
        
        // Khởi tạo CartModel nếu file tồn tại
        if (file_exists('./Models/CartModel.php')) {
            $this->loadModel('CartModel');
            $this->cartModel = new CartModel();
        }
        //     if (!$this->isLoggedIn()) {
        // // Chưa đăng nhập, chuyển hướng đến trang đăng nhập
        // $this->redirectWithMessage('index.php?controller=login', 
        //     'Vui lòng đăng nhập để tiếp tục', 'warning');
    // }
     if (!isset($_SESSION['user'])) {
        $this->redirectWithMessage('index.php?controller=login', 
            'Vui lòng đăng nhập để sử dụng giỏ hàng', 'warning');
        exit;
    }
    }

    // Hiển thị trang giỏ hàng
    public function index()
    {
        $cart = $_SESSION['cart'] ?? [];
        $cartItems = [];
        $total = 0;
        $number = 0;
        
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
                $number += $item['quantity'];
            }
        }

        $this->view('frontend.cart.index', [
            'cartItems' => $cartItems,
            'total' => $total,
            'itemCount' => count($cartItems),
            'number' => $number,
        ]);
    }

    // Thêm sản phẩm vào giỏ hàng
   /**
 * Thêm sản phẩm vào giỏ hàng
 */
public function add()
{
    // Kiểm tra nếu đây là request AJAX
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    
    $productId = isset($_POST['product_id']) ? $_POST['product_id'] : (isset($_GET['id']) ? $_GET['id'] : null);
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : (isset($_GET['qty']) ? (int)$_GET['qty'] : 1);
    $size = isset($_POST['size']) ? $_POST['size'] : (isset($_GET['size']) ? $_GET['size'] : 'M');

    if (!$productId || $quantity <= 0) {
        if ($isAjax) {
            echo json_encode([
                'success' => false,
                'message' => 'Thông tin sản phẩm không hợp lệ!'
            ]);
            exit;
        }
        $this->redirectWithMessage('index.php?controller=product&action=show&id=' . $productId, 'Thông tin sản phẩm không hợp lệ!', 'error');
        return;
    }

    $product = $this->productModel->findById($productId);
    if (!$product) {
        if ($isAjax) {
            echo json_encode([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm!'
            ]);
            exit;
        }
        $this->redirectWithMessage('index.php', 'Không tìm thấy sản phẩm!', 'error');
        return;
    }

    // Kiểm tra số lượng tồn kho
    if (!isset($product[$size]) || $product[$size] <= 0) {
        if ($isAjax) {
            echo json_encode([
                'success' => false,
                'message' => 'Kích thước ' . $size . ' đã hết hàng!'
            ]);
            exit;
        }
        $this->redirectWithMessage('index.php?controller=product&action=show&id=' . $productId, 'Kích thước ' . $size . ' đã hết hàng!', 'error');
        return;
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Sử dụng khóa duy nhất "productId_size"
    $itemKey = $productId . '_' . $size;

    // Nếu sản phẩm đã có trong giỏ với cùng size, kiểm tra số lượng tồn kho
    $currentQuantity = isset($_SESSION['cart'][$itemKey]) ? $_SESSION['cart'][$itemKey]['quantity'] : 0;
    $newTotalQuantity = $currentQuantity + $quantity;

    // Kiểm tra nếu tổng số lượng vượt quá tồn kho
    if ($newTotalQuantity > $product[$size]) {
        $availableQty = $product[$size] - $currentQuantity;
        $message = 'Không thể thêm ' . $quantity . ' sản phẩm vào giỏ hàng. Chỉ còn ' . 
                   max(0, $availableQty) . ' sản phẩm kích thước ' . $size . ' trong kho!';
        
        if ($isAjax) {
            echo json_encode([
                'success' => false,
                'message' => $message
            ]);
            exit;
        }
        $this->redirectWithMessage(
            'index.php?controller=product&action=show&id=' . $productId, 
            $message,
            'error'
        );
        return;
    }

    // Nếu sản phẩm đã có trong giỏ với cùng size, tăng số lượng
    if (isset($_SESSION['cart'][$itemKey])) {
        $_SESSION['cart'][$itemKey]['quantity'] = $newTotalQuantity;
    } else {
        // Nếu chưa có, thêm sản phẩm mới với size riêng
        $_SESSION['cart'][$itemKey] = [
            'product_id' => $productId,
            'quantity' => $quantity,
            'size' => $size
        ];
    }

    // Nếu người dùng đã đăng nhập và CartModel đã được tải, cập nhật giỏ hàng trong DB
    if (isset($_SESSION['user'], $_SESSION['user']['id_User']) && isset($this->cartModel)) {
        $userId = $_SESSION['user']['id_User'];
        
        // Kiểm tra xem sản phẩm đã có trong giỏ hàng của người dùng chưa
        $existingItem = $this->cartModel->getCartItem($userId, $productId, $size);
        
        if ($existingItem) {
            // Nếu đã có, cập nhật số lượng
            $this->cartModel->updateCartItem($userId, $productId, $size, $newTotalQuantity);
        } else {
            // Nếu chưa có, thêm mới
            $this->cartModel->addCartItem($userId, $productId, $quantity, $size);
        }
    }

    if ($isAjax) {
        echo json_encode([
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
            'count' => $this->getCartItemCount()
        ]);
        exit;
    }

    $this->redirectWithMessage('index.php?controller=cart&action=index', 'Đã thêm sản phẩm vào giỏ hàng!', 'success');
}
private function getCartItemCount()
{
    $cart = $_SESSION['cart'] ?? [];
    $totalItems = 0;
    foreach ($cart as $item) {
        $totalItems += $item['quantity'];
    }
    return $totalItems;
}

    /**
     * Mua ngay - thêm sản phẩm vào giỏ và chuyển đến trang thanh toán
     */
   /**
 * Mua ngay - thêm sản phẩm vào giỏ và chuyển đến trang thanh toán
 */
public function buyNow()
{
    // Kiểm tra phương thức yêu cầu
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->jsonResponse([
            'success' => false,
            'message' => 'Phương thức không hợp lệ.'
        ]);
        return;
    }
    
    // Lấy thông tin sản phẩm
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $size = isset($_POST['size']) ? $_POST['size'] : 'M';
    
    // Xác thực ID sản phẩm
    if (!$productId) {
        $this->jsonResponse([
            'success' => false,
            'message' => 'Sản phẩm không hợp lệ.'
        ]);
        return;
    }
    
    // Xác thực size
    if (!in_array($size, ['M', 'L', 'XL'])) {
        $this->jsonResponse([
            'success' => false,
            'message' => 'Kích thước không hợp lệ.'
        ]);
        return;
    }
    
    // Lấy thông tin sản phẩm
    $product = $this->productModel->findById($productId);
    
    if (!$product) {
        $this->jsonResponse([
            'success' => false,
            'message' => 'Sản phẩm không tồn tại.'
        ]);
        return;
    }
    
    // Xác thực số lượng
    if ($quantity <= 0) {
        $quantity = 1; // Đặt số lượng mặc định nếu không hợp lệ
    }
    
    // Kiểm tra tồn kho
    if ($product[$size] < $quantity) {
        $this->jsonResponse([
            'success' => false,
            'message' => 'Sản phẩm không đủ hàng. Chỉ còn ' . $product[$size] . ' sản phẩm kích thước ' . $size . ' trong kho.'
        ]);
        return;
    }
    
    // Khởi tạo session nếu chưa có
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Tạo khóa duy nhất cho sản phẩm này + kích thước
    $cartKey = $productId . '_' . $size;
    
    // Xóa giỏ hàng và chỉ thêm mục này
    $_SESSION['cart'] = [];
    
    // Thêm sản phẩm vào giỏ hàng
    $_SESSION['cart'][$cartKey] = [
        'product_id' => $productId,
        'quantity' => $quantity,
        'size' => $size,
        'name' => $product['name'],
        'price' => $product['current_price'],
        'image' => $product['main_image']
    ];
    
    // Đặt cờ để chỉ ra rằng đây là đơn đặt hàng "Mua ngay"
    $_SESSION['buy_now'] = true;
    
    // Nếu người dùng đã đăng nhập, cập nhật giỏ hàng trong database
    if (isset($_SESSION['user'], $_SESSION['user']['id_User'])) {
        $userId = $_SESSION['user']['id_User'];
        
        // Xóa giỏ hàng hiện tại của người dùng trong database
        $this->cartModel->clearCart($userId);
        
        // Thêm sản phẩm mới vào giỏ hàng của người dùng trong database
        $this->cartModel->addCartItem($userId, $productId, $quantity, $size);
    }
    
    // Trả về phản hồi thành công với thông tin chuyển hướng
    $this->jsonResponse([
        'success' => true,
        'message' => 'Đang chuyển đến trang đặt hàng...',
        'redirect' => 'index.php?controller=order',
        'cart_count' => 1 // Luôn là 1 cho "Mua ngay"
    ]);
}
    /**
     * Hàm hỗ trợ để gửi phản hồi JSON
     */
    private function jsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Cập nhật sản phẩm trong giỏ hàng
     */
  public function updateItem()
{
    // Đảm bảo đây là yêu cầu AJAX
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
        header('HTTP/1.0 403 Forbidden');
        exit;
    }
    
    $productId = $_POST['product_id'] ?? null;
    $size = $_POST['size'] ?? 'M';
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
    
    if (!$productId || !$size) {
        echo json_encode(['success' => false, 'message' => 'Thông tin sản phẩm không hợp lệ!']);
        exit;
    }
    
    // Kiểm tra tồn kho trước khi cập nhật
    $product = $this->productModel->findById($productId);
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm!']);
        exit;
    }
    
    // Kiểm tra số lượng tồn kho
    if (isset($product[$size]) && $quantity > $product[$size]) {
        echo json_encode([
            'success' => false, 
            'message' => 'Chỉ còn ' . $product[$size] . ' sản phẩm kích thước ' . $size . ' trong kho!'
        ]);
        exit;
    }
    
    $itemKey = $productId . '_' . $size;
    
    if ($quantity <= 0) {
        // Nếu số lượng <= 0, xóa sản phẩm khỏi giỏ hàng
        if (isset($_SESSION['cart'][$itemKey])) {
            unset($_SESSION['cart'][$itemKey]);
        }
        
        // Nếu người dùng đã đăng nhập và CartModel đã được tải, xóa sản phẩm khỏi DB
        if (isset($_SESSION['user'], $_SESSION['user']['id_User']) && isset($this->cartModel)) {
            $userId = $_SESSION['user']['id_User'];
            $this->cartModel->removeCartItem($userId, $productId, $size);
        }
    } else {
        // Cập nhật số lượng trong session
        if (isset($_SESSION['cart'][$itemKey])) {
            $_SESSION['cart'][$itemKey]['quantity'] = $quantity;
        }
        
        // Nếu người dùng đã đăng nhập và CartModel đã được tải, cập nhật số lượng trong DB
        if (isset($_SESSION['user'], $_SESSION['user']['id_User']) && isset($this->cartModel)) {
            $userId = $_SESSION['user']['id_User'];
            
            // Kiểm tra xem sản phẩm đã có trong giỏ hàng của người dùng chưa
            $existingItem = $this->cartModel->getCartItem($userId, $productId, $size);
            
            if ($existingItem) {
                // Nếu đã có, cập nhật số lượng
                $this->cartModel->updateCartItem($userId, $productId, $size, $quantity);
            } else {
                // Nếu chưa có, thêm mới
                $this->cartModel->addCartItem($userId, $productId, $quantity, $size);
            }
        }
    }
    
    // Tính tổng số sản phẩm
    $totalItems = 0;
    foreach ($_SESSION['cart'] as $item) {
        $totalItems += $item['quantity'];
    }
    
    echo json_encode([
        'success' => true, 
        'message' => 'Giỏ hàng đã được cập nhật!',
        'count' => $totalItems
    ]);
    exit;
}
    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function delete()
    {
        $productId = $_GET['product_id'] ?? null;
        $size = $_GET['size'] ?? 'M';

        if (!$productId || !$size) {
            $this->redirectWithMessage('index.php?controller=cart&action=index', 'Thông tin sản phẩm không hợp lệ!', 'error');
            return;
        }

        $itemKey = $productId . '_' . $size;
        
        // Xóa sản phẩm khỏi session
        if (isset($_SESSION['cart'][$itemKey])) {
            unset($_SESSION['cart'][$itemKey]);
        }
        
        // Nếu người dùng đã đăng nhập và CartModel đã được tải, xóa sản phẩm khỏi DB
        if (isset($_SESSION['user'], $_SESSION['user']['id_User']) && isset($this->cartModel)) {
            $userId = $_SESSION['user']['id_User'];
            $this->cartModel->removeCartItem($userId, $productId, $size);
        }

        $this->redirectWithMessage('index.php?controller=cart&action=index', 'Đã xóa sản phẩm khỏi giỏ hàng!', 'success');
    }

    /**
     * Chuyển hướng với thông báo
     */
    private function redirectWithMessage($url, $message, $type = 'success')
    {
        $_SESSION['flash_message'] = [
            'message' => $message,
            'type' => $type
        ];
        header("Location: $url");
        exit();
    }

    /**
     * Xóa giỏ hàng
     */
    public function clear()
    {
        // Xóa giỏ hàng trong session
        $_SESSION['cart'] = [];
        
        // Nếu người dùng đã đăng nhập và CartModel đã được tải, xóa giỏ hàng trong DB
        if (isset($_SESSION['user'], $_SESSION['user']['id_User']) && isset($this->cartModel)) {
            $userId = $_SESSION['user']['id_User'];
            $this->cartModel->clearCart($userId);
        }
        
        $this->redirectWithMessage('index.php?controller=cart&action=index', 'Giỏ hàng đã được xóa!', 'success');
    }

    /**
     * Lấy số lượng sản phẩm trong giỏ hàng (sử dụng cho AJAX)
     */
    /**
 * Lấy số lượng sản phẩm trong giỏ hàng (sử dụng cho AJAX)
 */
public function getCount()
{
    // Đảm bảo đây là yêu cầu AJAX
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
        header('HTTP/1.0 403 Forbidden');
        exit;
    }
    
    // Lấy giỏ hàng từ session
    $cart = $_SESSION['cart'] ?? [];
    
    // Nếu người dùng đã đăng nhập, đảm bảo giỏ hàng từ database đã được tải
    if (isset($_SESSION['user'], $_SESSION['user']['id_User']) && empty($cart)) {
        $userId = $_SESSION['user']['id_User'];
        $userCart = $this->cartModel->getUserCart($userId);
        
        foreach ($userCart as $item) {
            $itemKey = $item['id_Product'] . '_' . $item['size'];
            $cart[$itemKey] = [
                'product_id' => $item['id_Product'],
                'quantity' => $item['quantity'],
                'size' => $item['size']
            ];
        }
        
        // Cập nhật session
        $_SESSION['cart'] = $cart;
    }
    
    // Tính tổng số sản phẩm
    $totalItems = 0;
    foreach ($cart as $item) {
        $totalItems += $item['quantity'];
    }
    
    // Trả về phản hồi JSON
    header('Content-Type: application/json');
    echo json_encode(['count' => $totalItems]);
    exit;
}

    /**
     * Cập nhật số lượng sản phẩm qua AJAX
     */
   
    
}