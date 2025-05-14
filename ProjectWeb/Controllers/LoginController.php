<?php
class LoginController extends BaseController
{
    private $userModel;
    
    public function __construct()
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Load necessary models
        $this->loadModel('UserModel');
        $this->userModel = new UserModel();
    }
    
    /**
     * Display login/register page
     */
    public function index()
    {
        // If user is already logged in, redirect to homepage
        if (isset($_SESSION['user'])) {
            header('Location: index.php');
            exit;
        }
        
        // Check if there's a redirect parameter
        $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';
        
        // Render the login page
        $this->view('frontend.login.index', [
            'redirect' => $redirect
        ]);
    }
    
    /**
     * Process login form submission
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
        $redirect = $_POST['redirect'] ?? '';
        
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
            $this->redirectWithMessage('index.php?controller=login', 
                'Email/SĐT hoặc mật khẩu không chính xác', 'error');
            return;
        }
        
        // Check if account is verified
        if ($user['verified'] != 1) {
            // Generate new verification code
            $verificationCode = $this->generateVerificationCode();
            $this->userModel->updateVerificationCode($user['id_User'], $verificationCode);
            
            // Store user data in session for verification
            $_SESSION['pending_user'] = [
                'id' => $user['id_User'],
                'email' => $user['email'],
                'phone' => $user['phone']
            ];
            
            $this->redirectWithMessage('index.php?controller=login&action=verify', 
                'Tài khoản chưa được xác thực. Vui lòng xác thực để tiếp tục.', 'warning');
            return;
        }
        
        // Create user session without password
        unset($user['password']);
        $_SESSION['user'] = $user;
        
        // Redirect to requested page or homepage
        $redirectUrl = !empty($redirect) ? $redirect : 'index.php';
        $this->redirectWithMessage($redirectUrl, 
            'Đăng nhập thành công', 'success');
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
            $this->redirectWithMessage('index.php?controller=login#register', 
                'Vui lòng nhập đầy đủ thông tin', 'error');
            return;
        }
        
        // Validate password match
        if ($password !== $confirmPassword) {
            $this->redirectWithMessage('index.php?controller=login#register', 
                'Mật khẩu không khớp', 'error');
            return;
        }
        
        // Validate password strength (minimum 8 characters, includes letters and numbers)
        if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $this->redirectWithMessage('index.php?controller=login#register', 
                'Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ và số', 'error');
            return;
        }
        
        // Validate terms agreement
        if (!$agreeTerms) {
            $this->redirectWithMessage('index.php?controller=login#register', 
                'Vui lòng đồng ý với điều khoản sử dụng', 'error');
            return;
        }
        
        // Check if email already exists
        if ($this->userModel->findByEmail($email)) {
            $this->redirectWithMessage('index.php?controller=login#register', 
                'Email đã được sử dụng', 'error');
            return;
        }
        
        // Check if phone already exists
        if ($this->userModel->findByPhone($phone)) {
            $this->redirectWithMessage('index.php?controller=login#register', 
                'Số điện thoại đã được sử dụng', 'error');
            return;
        }
        
        // Generate verification code
        $verificationCode = $this->generateVerificationCode();
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Prepare user data
        $userData = [
            'fullname' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'password' => $hashedPassword,
            'verification_code' => $verificationCode,
            'verified' => 0,
            'role' => 'customer',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Register user
        $userId = $this->userModel->createUser($userData);
        
        if (!$userId) {
            $this->redirectWithMessage('index.php?controller=login#register', 
                'Đăng ký không thành công. Vui lòng thử lại sau.', 'error');
            return;
        }
        
        // Store user data in session for verification
        $_SESSION['pending_user'] = [
            'id' => $userId,
            'email' => $email,
            'phone' => $phone
        ];
        
        // Redirect to verification page
        $this->redirectWithMessage('index.php?controller=login&action=verify', 
            'Đăng ký thành công! Vui lòng xác thực tài khoản.', 'success');
    }
    
    /**
     * Display verification page and handle verification
     */
    public function verify()
    {
        // Check if there's a pending user
        if (!isset($_SESSION['pending_user'])) {
            $this->redirectWithMessage('index.php?controller=login', 
                'Không có tài khoản chờ xác thực', 'error');
            return;
        }
        
        // Check if the request is to verify a code
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCode();
            return;
        }
        
        // Get contact information
        $email = $_SESSION['pending_user']['email'];
        $phone = $_SESSION['pending_user']['phone'];
        
        // Display verification page
        $this->view('frontend.login.verify', [
            'email' => $email,
            'phone' => $phone
        ]);
    }
    
    /**
     * Verify code submitted by user
     */
    private function verifyCode()
    {
        // Get verification code
        $code = $_POST['code'] ?? '';
        
        // Validate input
        if (empty($code) || strlen($code) !== 6) {
            $this->redirectWithMessage('index.php?controller=login&action=verify', 
                'Mã xác thực không hợp lệ', 'error');
            return;
        }
        
        // Get pending user
        $userId = $_SESSION['pending_user']['id'];
        
        // Verify code
        $user = $this->userModel->findById($userId);
        
        if (!$user || $user['verification_code'] !== $code) {
            $this->redirectWithMessage('index.php?controller=login&action=verify', 
                'Mã xác thực không chính xác', 'error');
            return;
        }
        
        // Mark user as verified
        $this->userModel->markAsVerified($userId);
        
        // Clear pending user
        unset($_SESSION['pending_user']);
        
        // Redirect to login page
        $this->redirectWithMessage('index.php?controller=login', 
            'Xác thực thành công! Vui lòng đăng nhập để tiếp tục.', 'success');
    }
    
    /**
     * Resend verification code
     */
    public function resendCode()
    {
        // Check if there's a pending user
        if (!isset($_SESSION['pending_user'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Không có tài khoản chờ xác thực'
            ]);
            exit;
        }
        
        // Generate new verification code
        $verificationCode = $this->generateVerificationCode();
        
        // Update user's verification code
        $userId = $_SESSION['pending_user']['id'];
        $this->userModel->updateVerificationCode($userId, $verificationCode);
        
        // In a real application, send the code via email or SMS
        // For this implementation, we'll just return success
        
        echo json_encode([
            'success' => true,
            'message' => 'Mã xác thực mới đã được gửi'
        ]);
        exit;
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
        
        // Redirect to login page
        $this->redirectWithMessage('index.php', 
            'Đã đăng xuất thành công', 'success');
    }
    
    /**
     * Generate random verification code
     */
    private function generateVerificationCode()
    {
        return sprintf('%06d', mt_rand(0, 999999));
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