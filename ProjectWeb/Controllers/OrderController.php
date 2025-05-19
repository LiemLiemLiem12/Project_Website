<?php
class OrderController extends BaseController
{
    private $productModel;
    private $orderModel;
    private $userModel;
   
    public function __construct()
    {
        // Luôn gọi constructor của lớp cha trước tiên
        parent::__construct();
        
        // Sau đó mới load các model cụ thể cho controller này
        $this->loadModel('OrderModel');
        $this->loadModel('ProductModel');
        $this->loadModel('UserModel');
        
        // Khởi tạo các đối tượng model
        $this->orderModel = new OrderModel();
        $this->productModel = new ProductModel();
        $this->userModel = new UserModel();
    }
    

    /**
     * Display the Order/Checkout page
     */
   /**
 * Display the Order/Checkout page
 */
public function index()
{
    // Nếu có order_success=true trong URL, hiển thị modal thành công
    $orderSuccess = isset($_GET['order_success']) && $_GET['order_success'] === 'true';
    
    // Kiểm tra xem có đơn hàng mới tạo không
    $lastOrderId = $_SESSION['last_order_id'] ?? null;
    $lastOrder = null;
    
    if ($lastOrderId && $orderSuccess) {
        // Lấy thông tin đơn hàng mới tạo để hiển thị chi tiết nếu cần
        $lastOrder = $this->orderModel->getOrderWithDetails($lastOrderId);
        // Xóa session sau khi đã lấy thông tin
        unset($_SESSION['last_order_id']);
    }

    // Get cart items from session
    $cart = $_SESSION['cart'] ?? [];
    $cartItems = [];
    $total = 0;

    // Process cart items
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

    // Nếu giỏ hàng trống và không có order_success, chuyển hướng đến giỏ hàng
    if (empty($cartItems) && !$orderSuccess) {
        $this->redirectWithMessage('index.php?controller=cart&action=index', 
            'Giỏ hàng của bạn đang trống! Vui lòng thêm sản phẩm trước khi thanh toán.', 'error');
        return;
    }

    // Get user data if logged in
    $userData = $_SESSION['user'] ?? [];

    // Prepare shipping options
    $shippingOptions = [
        'ghn' => [
            'id' => 'ghn',
            'name' => 'Giao hàng nhanh (GHN)',
            'description' => 'Nhận hàng trong 1-3 ngày',
            'fee' => 35000
        ],
        'ghtk' => [
            'id' => 'ghtk',
            'name' => 'Giao hàng tiết kiệm (GHTK)',
            'description' => 'Nhận hàng trong 3-5 ngày',
            'fee' => 30000
        ]
    ];

    // Prepare payment methods
    $paymentMethods = [
        'cod' => [
            'id' => 'cod',
            'name' => 'Thanh toán khi nhận hàng (COD)',
            'description' => 'Thanh toán bằng tiền mặt khi nhận hàng',
            'icon' => '/Project_Website/ProjectWeb/upload/img/Order/COD.svg'
        ],
        'vnpay' => [
            'id' => 'vnpay',
            'name' => 'Thanh toán VNPay',
            'description' => 'Thanh toán qua cổng VNPay',
            'icon' => '/Project_Website/ProjectWeb/upload/img/Order/VNPAY.svg'
        ],
        'momo' => [
            'id' => 'momo',
            'name' => 'Thanh toán MoMo',
            'description' => 'Thanh toán qua ví MoMo',
            'icon' => '/Project_Website/ProjectWeb/upload/img/Order/MOMO.svg'
        ]
    ];

    // Render the order/checkout page
    $this->view('frontend.order.index', [
        'cartItems' => $cartItems,
        'total' => $total,
        'userData' => $userData,
        'shippingOptions' => $shippingOptions,
        'paymentMethods' => $paymentMethods,
        'orderSuccess' => $orderSuccess,
        'lastOrder' => $lastOrder
    ]);
}

    /**
     * Process the order
     */
  /**
 * Process the order
 */
/**
 * Process the order
 */
/**
 * Process the order
 */
public function process()
{
    // Validate request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirectWithMessage('index.php?controller=order&action=index', 
            'Phương thức không hợp lệ!', 'error');
        return;
    }

    // Get form data
    $fullname = $_POST['fullname'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $province = $_POST['province'] ?? '';
    $district = $_POST['district'] ?? '';
    $ward = $_POST['ward'] ?? '';
    $note = $_POST['note'] ?? '';
    $shippingMethod = $_POST['shipping_method'] ?? 'ghn';
    $paymentMethod = $_POST['payment_method'] ?? 'cod';
    $discountCode = $_POST['discount_code'] ?? '';

    // Validate required fields
    if (empty($fullname) || empty($phone) || empty($address) || 
        empty($province) || empty($district) || empty($ward)) {
        $this->redirectWithMessage('index.php?controller=order&action=index', 
            'Vui lòng điền đầy đủ thông tin giao hàng!', 'error');
        return;
    }

    // Get cart items
    $cart = $_SESSION['cart'] ?? [];
    if (empty($cart)) {
        $this->redirectWithMessage('index.php?controller=cart&action=index', 
            'Giỏ hàng của bạn đang trống!', 'error');
        return;
    }

    // Calculate order total
    $cartItems = [];
    $subtotal = 0;
    $outOfStockItems = [];

    foreach ($cart as $itemKey => $item) {
        $product = $this->productModel->findById($item['product_id']);
        
        if ($product) {
            // Kiểm tra tồn kho
            $size = $item['size'];
            if (!isset($product[$size]) || $product[$size] < $item['quantity']) {
                // Thêm vào danh sách sản phẩm hết hàng
                $outOfStockItems[] = [
                    'name' => $product['name'],
                    'size' => $size,
                    'requested' => $item['quantity'],
                    'available' => $product[$size] ?? 0
                ];
                continue;
            }
            
            $itemSubtotal = $product['current_price'] * $item['quantity'];
            $cartItems[] = [
                'product_id' => $item['product_id'],
                'product' => $product,
                'quantity' => $item['quantity'],
                'size' => $size,
                'price' => $product['current_price'],
                'subtotal' => $itemSubtotal
            ];
            $subtotal += $itemSubtotal;
        }
    }
    
    // Kiểm tra nếu có sản phẩm hết hàng
    if (!empty($outOfStockItems)) {
        $errorMessage = 'Một số sản phẩm không đủ hàng:<br>';
        foreach ($outOfStockItems as $item) {
            $errorMessage .= "- {$item['name']} (Size {$item['size']}): Yêu cầu {$item['requested']} sản phẩm, chỉ còn {$item['available']} sản phẩm.<br>";
        }
        $errorMessage .= 'Vui lòng cập nhật giỏ hàng của bạn!';
        
        $this->redirectWithMessage('index.php?controller=cart&action=index', $errorMessage, 'error');
        return;
    }

    // Determine shipping fee
    $shippingFee = ($shippingMethod === 'ghn') ? 35000 : 30000;

    // Apply discount if applicable
    $discount = 0;
    if ($discountCode === 'SALE100K') {
        $discount = 100000;
    } elseif ($discountCode === 'SALE50K') {
        $discount = 50000;
    } elseif ($discountCode === 'FREESHIP') {
        $discount = $shippingFee;
    }

    // Generate order number
    $orderNumber = 'SR' . date('Ymd') . rand(1000, 9999);

    // Calculate total amount
    $totalAmount = $subtotal + $shippingFee - $discount;

    // Create full address string
    $fullAddress = "$address, $ward, $district, $province";

    // Create order
    $orderData = [
        'order_number' => $orderNumber,
        'id_User' => isset($_SESSION['user']) ? $_SESSION['user']['id_User'] : null,
        'total_amount' => $totalAmount,
        'payment_by' => $paymentMethod,
        'shipping_method' => $shippingMethod,
        'status' => 'pending',
        'created_at' => date('Y-m-d H:i:s'),
        'note' => "$fullname - $phone - $email - $fullAddress" . ($note ? " | $note" : ''),
        'shipping_fee' => $shippingFee,
        'hide' => 0
    ];

    // Insert order and get order ID
    $orderId = $this->orderModel->createOrder($orderData);

    if (!$orderId) {
        $this->redirectWithMessage('index.php?controller=order&action=index', 
            'Có lỗi xảy ra khi tạo đơn hàng. Vui lòng thử lại!', 'error');
        return;
    }

    // Insert order details
    foreach ($cartItems as $item) {
        $orderDetailData = [
            'id_Order' => $orderId,
            'id_Product' => $item['product_id'],
            'quantity' => $item['quantity'],
            'size' => $item['size'],
            'sub_total' => $item['subtotal']
        ];
        
        $this->orderModel->createOrderDetail($orderDetailData);
        
        // Update product inventory (reduce stock)
        $sizeColumn = $item['size']; // 'M', 'L' hoặc 'XL'
        $currentStock = $item['product'][$sizeColumn];
        $newStock = max(0, $currentStock - $item['quantity']); // Đảm bảo không âm
        
        $this->productModel->updateDataForProduct($item['product_id'], [
            $sizeColumn => $newStock
        ]);
    }

    // Clear cart after successful order
    $_SESSION['cart'] = [];
    unset($_SESSION['cart_loaded']);
    
    // Xóa giỏ hàng trong database nếu người dùng đã đăng nhập
    if (isset($_SESSION['user'], $_SESSION['user']['id_User'])) {
        $userId = $_SESSION['user']['id_User'];
        if (!isset($this->cartModel)) {
            $this->loadModel('CartModel');
            $this->cartModel = new CartModel();
        }
        $this->cartModel->clearCart($userId);
    }
    
    // Thêm query parameter 'order_success' để JS có thể hiển thị modal
    // Lưu orderId vào session để hiển thị thông tin đơn hàng nếu cần
    $_SESSION['last_order_id'] = $orderId;
    
    // Redirect với query param order_success=true để hiển thị modal
    header("Location: index.php?controller=order&action=index&order_success=true");
    exit;
}
    /**
     * Display order confirmation page
     */
   /**
 * Display order confirmation page
 */
public function confirmation()
{
    $orderId = $_SESSION['last_order_id'] ?? null;
    
    if (!$orderId) {
        $this->redirectWithMessage('index.php', 'Không tìm thấy thông tin đơn hàng!', 'error');
        return;
    }
    
    // Get order details
    $order = $this->orderModel->getOrderWithDetails($orderId);
    
    if (!$order) {
        $this->redirectWithMessage('index.php', 'Không tìm thấy thông tin đơn hàng!', 'error');
        return;
    }
    
    // Display confirmation page
    $this->view('frontend.order.confirmation', [
        'order' => $order
    ]);
    
    // Clear the order ID from session
    unset($_SESSION['last_order_id']);
}

    /**
     * Apply discount code (AJAX)
     */
    public function applyDiscount()
    {
        // Enable AJAX response
        header('Content-Type: application/json');
        
        // Get the coupon code
        $code = $_POST['code'] ?? '';
        
        // Validate coupon code (simplified example)
        $validCodes = [
            'SALE50K' => 50000,
            'SALE100K' => 100000,
            'FREESHIP' => 35000
        ];
        
        if (isset($validCodes[$code])) {
            echo json_encode([
                'success' => true,
                'discount' => $validCodes[$code],
                'message' => 'Mã giảm giá đã được áp dụng!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn!'
            ]);
        }
        exit;
    }

    /**
     * Helper method to redirect with a flash message
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
}