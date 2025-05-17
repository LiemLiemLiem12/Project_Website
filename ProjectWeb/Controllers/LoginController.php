<?php
class LoginController extends BaseController
{
    private $userModel;
    private $cartModel;
    
    public function __construct()
    {
        parent::__construct();
        
        // Load các model cần thiết
        $this->loadModel('UserModel');
        $this->userModel = new UserModel();
        
        // Load CartModel
        $this->loadModel('CartModel');
        $this->cartModel = new CartModel();
    }
    
    /**
     * Hiển thị trang đăng nhập
     */
    public function index()
    {
        // If user is already logged in, redirect to homepage
        if (isset($_SESSION['user'])) {
            header('Location: index.php');
            exit;
        }
        
        // Render the login page
        $this->view('frontend.login.index', []);
    }
    
    /**
     * Xử lý form đăng nhập
     */
    public function login()
    {
        // Validate request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithMessage('index.php?controller=login', 
                'Phương thức không hợp lệ', 'error');
            return;
        }
        
        // Get form data
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Validate input
        if (empty($email) || empty($password)) {
            $this->redirectWithMessage('index.php?controller=login', 
                'Vui lòng nhập đầy đủ thông tin đăng nhập', 'error');
            return;
        }
        
        // Check if email or phone was provided
        $isPhone = preg_match('/^[0-9]{10,11}$/', $email);
        
        // Authenticate user
        $user = $isPhone 
            ? $this->userModel->findByPhone($email) 
            : $this->userModel->findByEmail($email);
        
        // Check if user exists and password is correct
        if (!$user || !password_verify($password, $user['password'])) {
            // For development - simple password check (không sử dụng hashing)
            if (!$user || $password !== $user['password']) {
                $this->redirectWithMessage('index.php?controller=login', 
                    'Email/SĐT hoặc mật khẩu không chính xác', 'error');
                return;
            }
        }
        
        // Create user session without password
        unset($user['password']);
    $_SESSION['user'] = $user;
    
    // Hợp nhất giỏ hàng
    $this->mergeCart($user['id_User']);
    
    // Chuyển hướng về trang chủ
    $this->redirectWithMessage('index.php', 'Đăng nhập thành công', 'success');
          
    // Cập nhật giỏ hàng từ database

    }
    
    /**
     * Merge temporary cart (session) with user's cart in database
     */
  /**
 * Merge temporary cart (session) with user's cart in database
 * Sau khi đăng nhập, load giỏ hàng của người dùng từ database
 */
private function mergeCart($userId)
{
    // Kiểm tra xem người dùng có giỏ hàng session không
    $sessionCart = $_SESSION['cart'] ?? [];
    
    // 1. Nếu session có sản phẩm, hợp nhất với giỏ hàng trong database
    if (!empty($sessionCart)) {
        foreach ($sessionCart as $itemKey => $item) {
            $productId = $item['product_id'];
            $quantity = $item['quantity'];
            $size = $item['size'] ?? 'M';
            
            $existingItem = $this->cartModel->getCartItem($userId, $productId, $size);
            
            if ($existingItem) {
                // Cộng số lượng nếu sản phẩm đã tồn tại trong giỏ hàng người dùng
                $newQuantity = $existingItem['quantity'] + $quantity;
                $this->cartModel->updateCartItem($userId, $productId, $size, $newQuantity);
            } else {
                // Thêm mới nếu sản phẩm chưa có trong giỏ hàng người dùng
                $this->cartModel->addCartItem($userId, $productId, $quantity, $size);
            }
        }
    }
    
    // 2. Tải lại giỏ hàng của người dùng từ database (bất kể có hợp nhất hay không)
    $userCart = $this->cartModel->getUserCart($userId);
    
    // 3. Thay thế giỏ hàng session bằng giỏ hàng từ database
    $_SESSION['cart'] = [];
    
    foreach ($userCart as $item) {
        $itemKey = $item['id_Product'] . '_' . $item['size'];
        $_SESSION['cart'][$itemKey] = [
            'product_id' => $item['id_Product'],
            'quantity' => $item['quantity'],
            'size' => $item['size']
        ];
    }
    
    // 4. Đánh dấu giỏ hàng đã được cập nhật để header biết
    $_SESSION['cart_updated'] = true;
}
    
    /**
     * Process registration form submission
     */
    public function register()
    {
        // Validate request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithMessage('index.php?controller=login', 
                'Phương thức không hợp lệ', 'error');
            return;
        }
        
        // Get form data
        $fullName = $_POST['fullName'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';
        $agreeTerms = isset($_POST['agreeTerms']);
        
        // Validate input
        if (empty($fullName) || empty($email) || empty($phone) || 
            empty($password) || empty($confirmPassword)) {
            $this->redirectWithMessage('index.php?controller=login&tab=register', 
                'Vui lòng nhập đầy đủ thông tin', 'error');
            return;
        }
        
        // Validate password match
        if ($password !== $confirmPassword) {
            $this->redirectWithMessage('index.php?controller=login&tab=register', 
                'Mật khẩu không khớp', 'error');
            return;
        }
        
        // Validate password strength
        if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $this->redirectWithMessage('index.php?controller=login&tab=register', 
                'Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ và số', 'error');
            return;
        }
        
        // Validate terms agreement
        if (!$agreeTerms) {
            $this->redirectWithMessage('index.php?controller=login&tab=register', 
                'Vui lòng đồng ý với điều khoản sử dụng', 'error');
            return;
        }
        
        // Check if email already exists
        if ($this->userModel->findByEmail($email)) {
            $this->redirectWithMessage('index.php?controller=login&tab=register', 
                'Email đã được sử dụng', 'error');
            return;
        }
        
        // Check if phone already exists
        if ($this->userModel->findByPhone($phone)) {
            $this->redirectWithMessage('index.php?controller=login&tab=register', 
                'Số điện thoại đã được sử dụng', 'error');
            return;
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Prepare user data - Đặt verified mặc định là 1 (đã xác thực)
        $userData = [
            'name' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'password' => $hashedPassword,
            'verification_code' => null,
            'verified' => 1, // Tự động xác thực tài khoản
            'role' => 'user',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Register user
        $userId = $this->userModel->createUser($userData);
        
        if (!$userId) {
            $this->redirectWithMessage('index.php?controller=login&tab=register', 
                'Đăng ký không thành công. Vui lòng thử lại sau.', 'error');
            return;
        }
        
        // Redirect to login page with success message
        $this->redirectWithMessage('index.php?controller=login', 
            'Đăng ký thành công! Vui lòng đăng nhập để tiếp tục.', 'success');
    }
    
    /**
     * Handle forgot password request
     */
    public function forgotPassword()
    {
        // Validate request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Phương thức không hợp lệ'
            ]);
            exit;
        }
        
        // Get contact information
        $contact = $_POST['contact'] ?? '';
        
        // Validate input
        if (empty($contact)) {
            echo json_encode([
                'success' => false,
                'message' => 'Vui lòng nhập email hoặc số điện thoại'
            ]);
            exit;
        }
        
        // Check if email or phone was provided
        $isPhone = preg_match('/^[0-9]{10,11}$/', $contact);
        
        // Find user
        $user = $isPhone 
            ? $this->userModel->findByPhone($contact) 
            : $this->userModel->findByEmail($contact);
        
        if (!$user) {
            echo json_encode([
                'success' => false,
                'message' => 'Không tìm thấy tài khoản với thông tin này'
            ]);
            exit;
        }
        
        // Generate reset token
        $resetToken = bin2hex(random_bytes(32));
        $tokenExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Save reset token
        $this->userModel->saveResetToken($user['id_User'], $resetToken, $tokenExpiry);
        
        // In a real application, send reset link via email or SMS
        // For this implementation, we'll just return success
        
        echo json_encode([
            'success' => true,
            'message' => 'Hướng dẫn khôi phục mật khẩu đã được gửi'
        ]);
        exit;
    }
    
    /**
     * Display reset password page
     */
    public function resetPassword()
    {
        // Get token
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            $this->redirectWithMessage('index.php?controller=login', 
                'Token không hợp lệ', 'error');
            return;
        }
        
        // Validate token
        $user = $this->userModel->findByResetToken($token);
        
        if (!$user || strtotime($user['reset_token_expiry']) < time()) {
            $this->redirectWithMessage('index.php?controller=login', 
                'Token không hợp lệ hoặc đã hết hạn', 'error');
            return;
        }
        
        // Display reset password page
        $this->view('frontend.login.reset_password', [
            'token' => $token
        ]);
    }
    
    /**
     * Process reset password form submission
     */
    public function processResetPassword()
    {
        // Validate request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithMessage('index.php?controller=login', 
                'Phương thức không hợp lệ', 'error');
            return;
        }
        
        // Get form data
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';
        
        // Validate input
        if (empty($token) || empty($password) || empty($confirmPassword)) {
            $this->redirectWithMessage('index.php?controller=login&action=resetPassword&token=' . $token, 
                'Vui lòng nhập đầy đủ thông tin', 'error');
            return;
        }
        
        // Validate token
        $user = $this->userModel->findByResetToken($token);
        
        if (!$user || strtotime($user['reset_token_expiry']) < time()) {
            $this->redirectWithMessage('index.php?controller=login', 
                'Token không hợp lệ hoặc đã hết hạn', 'error');
            return;
        }
        
        // Validate password match
        if ($password !== $confirmPassword) {
            $this->redirectWithMessage('index.php?controller=login&action=resetPassword&token=' . $token, 
                'Mật khẩu không khớp', 'error');
            return;
        }
        
        // Validate password strength
        if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $this->redirectWithMessage('index.php?controller=login&action=resetPassword&token=' . $token, 
                'Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ và số', 'error');
            return;
        }
        
        // Hash new password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Update password and clear reset token
        $this->userModel->updatePassword($user['id_User'], $hashedPassword);
        
        // Redirect to login page
        $this->redirectWithMessage('index.php?controller=login', 
            'Mật khẩu đã được cập nhật! Vui lòng đăng nhập để tiếp tục.', 'success');
    }
    
    /**
     * Log out user
     */
    public function logout()
    {
        // Clear user session
        unset($_SESSION['user']);
        
        // Redirect to homepage
        $this->redirectWithMessage('index.php', 
            'Đã đăng xuất thành công', 'success');
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
?>