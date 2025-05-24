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
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Enhanced validation
        $validationResult = $this->validateLoginInput($email, $password);
        if (!$validationResult['valid']) {
            $this->redirectWithMessage('index.php?controller=login', 
                $validationResult['message'], 'error');
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
    }
    
    /**
     * Enhanced validation for login input
     */
    private function validateLoginInput($email, $password)
    {
        // Check empty fields
        if (empty($email) || empty($password)) {
            return [
                'valid' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin đăng nhập'
            ];
        }
        
        // Validate email format or phone format
        $isEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
        $isPhone = preg_match('/^(0[3|5|7|8|9])+([0-9]{8})$/', $email); // Vietnamese phone format
        
        if (!$isEmail && !$isPhone) {
            return [
                'valid' => false,
                'message' => 'Email hoặc số điện thoại không đúng định dạng'
            ];
        }
        
        return ['valid' => true];
    }
    
    /**
     * Merge temporary cart (session) with user's cart in database
     */
    private function mergeCart($userId)
    {
        // 1. Kiểm tra nếu có giỏ hàng session tạm thời và nó không trống
        $sessionCart = $_SESSION['cart'] ?? [];
        
        // 2. Lấy giỏ hàng của người dùng từ database
        $userCart = $this->cartModel->getUserCart($userId);
        $userCartMap = []; // Map để kiểm tra nhanh các sản phẩm trong giỏ hàng người dùng
        
        // 3. Tạo giỏ hàng mới từ database
        $_SESSION['cart'] = [];
        
        // Tạo map cho các sản phẩm trong giỏ hàng database
        foreach ($userCart as $item) {
            $itemKey = $item['id_Product'] . '_' . $item['size'];
            $_SESSION['cart'][$itemKey] = [
                'product_id' => $item['id_Product'],
                'quantity' => $item['quantity'],
                'size' => $item['size']
            ];
            $userCartMap[$itemKey] = true;
        }
        
        // 4. Thêm các sản phẩm từ session vào database nếu chưa có
        if (!empty($sessionCart)) {
            foreach ($sessionCart as $itemKey => $item) {
                $productId = $item['product_id'];
                $quantity = $item['quantity'];
                $size = $item['size'] ?? 'M';
                
                // Nếu sản phẩm chưa có trong giỏ hàng database
                if (!isset($userCartMap[$itemKey])) {
                    // Thêm vào database
                    $this->cartModel->addCartItem($userId, $productId, $quantity, $size);
                    
                    // Thêm vào session
                    $_SESSION['cart'][$itemKey] = [
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'size' => $size
                    ];
                }
                // Không cập nhật số lượng nếu sản phẩm đã tồn tại (ưu tiên database)
            }
        }
        
        // 5. Đánh dấu giỏ hàng đã được tải
        $_SESSION['cart_loaded'] = true;
    }
    
    /**
     * Process registration form submission with enhanced validation
     */
    public function register()
    {
        // Validate request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithMessage('index.php?controller=login', 
                'Phương thức không hợp lệ', 'error');
            return;
        }
        
        // Get form data and trim whitespace
        $fullName = trim($_POST['fullName'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';
        $agreeTerms = isset($_POST['agreeTerms']);
        
        // Enhanced validation
        $validationResult = $this->validateRegistrationInput(
            $fullName, $email, $phone, $password, $confirmPassword, $agreeTerms
        );
        
        if (!$validationResult['valid']) {
            $this->redirectWithMessage('index.php?controller=login&tab=register', 
                $validationResult['message'], 'error');
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
     * Enhanced validation for registration input
     */
    private function validateRegistrationInput($fullName, $email, $phone, $password, $confirmPassword, $agreeTerms)
    {
        // Check empty fields
        if (empty($fullName) || empty($email) || empty($phone) || 
            empty($password) || empty($confirmPassword)) {
            return [
                'valid' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin'
            ];
        }
        
        // Validate full name
        if (!$this->validateFullName($fullName)) {
            return [
                'valid' => false,
                'message' => 'Họ tên không hợp lệ. Tên không được toàn số và phải từ 2-50 ký tự'
            ];
        }
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'valid' => false,
                'message' => 'Email không đúng định dạng'
            ];
        }
        
        // Validate phone
        if (!$this->validatePhoneNumber($phone)) {
            return [
                'valid' => false,
                'message' => 'Số điện thoại không hợp lệ (phải là số Việt Nam 10-11 chữ số)'
            ];
        }
        
        // Validate password
        $passwordValidation = $this->validatePassword($password);
        if (!$passwordValidation['valid']) {
            return $passwordValidation;
        }
        
        // Validate password match
        if ($password !== $confirmPassword) {
            return [
                'valid' => false,
                'message' => 'Mật khẩu xác nhận không khớp'
            ];
        }
        
        // Validate terms agreement
        if (!$agreeTerms) {
            return [
                'valid' => false,
                'message' => 'Vui lòng đồng ý với điều khoản sử dụng'
            ];
        }
        
        return ['valid' => true];
    }
    
    /**
     * Validate full name
     */
    private function validateFullName($name)
    {
        // Remove extra spaces and check length
        $name = preg_replace('/\s+/', ' ', $name);
        
        // Check length (2-50 characters)
        if (strlen($name) < 2 || strlen($name) > 50) {
            return false;
        }
        
        // Check if name is all numbers
        if (is_numeric(str_replace(' ', '', $name))) {
            return false;
        }
        
        // Check if name contains only valid characters (letters, spaces, Vietnamese characters)
        if (!preg_match('/^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỂưăạảấầẩẫậắằẳẵặẹẻẽềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵýỷỹ\s]+$/u', $name)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate Vietnamese phone number
     */
    private function validatePhoneNumber($phone)
    {
        // Remove spaces and special characters
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);
        
        // Check Vietnamese phone number format
        // Mobile: 03x, 05x, 07x, 08x, 09x (10 digits)
        // Landline: area code + number (10-11 digits)
        return preg_match('/^(0[3|5|7|8|9])+([0-9]{8})$/', $phone) || 
               preg_match('/^(0[2|4|6])+([0-9]{8,9})$/', $phone);
    }
    
    /**
     * Enhanced password validation
     */
    private function validatePassword($password)
    {
        // Check minimum length
        if (strlen($password) < 8) {
            return [
                'valid' => false,
                'message' => 'Mật khẩu phải có ít nhất 8 ký tự'
            ];
        }
        
        // Check maximum length
        if (strlen($password) > 128) {
            return [
                'valid' => false,
                'message' => 'Mật khẩu không được vượt quá 128 ký tự'
            ];
        }
        
        // Check for at least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            return [
                'valid' => false,
                'message' => 'Mật khẩu phải chứa ít nhất một chữ cái thường'
            ];
        }
        
        // Check for at least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            return [
                'valid' => false,
                'message' => 'Mật khẩu phải chứa ít nhất một chữ cái hoa'
            ];
        }
        
        // Check for at least one number
        if (!preg_match('/[0-9]/', $password)) {
            return [
                'valid' => false,
                'message' => 'Mật khẩu phải chứa ít nhất một chữ số'
            ];
        }
        
        // Check for at least one special character
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            return [
                'valid' => false,
                'message' => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt (!@#$%^&*(),.?":{}|<>)'
            ];
        }
        
        // Check for common weak passwords
        $weakPasswords = [
            'password', '12345678', 'qwerty123', 'admin123', 
            'Password123!', '123456789', 'abcd1234'
        ];
        
        if (in_array(strtolower($password), array_map('strtolower', $weakPasswords))) {
            return [
                'valid' => false,
                'message' => 'Mật khẩu này quá phổ biến, vui lòng chọn mật khẩu khác'
            ];
        }
        
        return ['valid' => true];
    }
    
    /**
     * Handle forgot password request
     */
    public function forgotPassword() {
        try {
            // Đặt header cho JSON
            header('Content-Type: application/json');
            
            // Validate request method
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode([
                    'success' => false,
                    'message' => 'Phương thức không hợp lệ'
                ]);
                exit;
            }
            
            // Get contact information
            $contact = trim($_POST['contact'] ?? '');
            
            // Enhanced validation for contact
            $contactValidation = $this->validateContactInfo($contact);
            if (!$contactValidation['valid']) {
                echo json_encode([
                    'success' => false,
                    'message' => $contactValidation['message']
                ]);
                exit;
            }
            
            // Check if email or phone was provided
            $isPhone = preg_match('/^(0[3|5|7|8|9])+([0-9]{8})$/', $contact);
            
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
            
            // Tạo mã xác thực ngẫu nhiên (5 số)
            $verificationCode = sprintf("%05d", rand(0, 99999));
            
            // Tạo reset token
            $resetToken = bin2hex(random_bytes(32));
            $tokenExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Lưu thông tin vào session
            $_SESSION['reset_contact'] = $contact;
            $_SESSION['reset_token'] = $resetToken;
            
            // Lưu mã xác thực và token vào database
            $this->userModel->updateVerificationCode($user['id_User'], $verificationCode);
            $this->userModel->saveResetToken($user['id_User'], $resetToken, $tokenExpiry);
            
            // Gửi email với mã xác thực nếu là email
            $emailSent = false;
            if (!$isPhone) {
                $emailSent = $this->sendVerificationEmail($contact, $verificationCode);
            } else {
                // Xử lý gửi SMS nếu cần (chưa triển khai)
                $emailSent = true; // Giả định thành công để đơn giản hóa
            }
            
            if (!$emailSent) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Không thể gửi thông tin xác thực. Vui lòng thử lại sau.'
                ]);
            } else {
                echo json_encode([
                    'success' => true,
                    'message' => 'Hướng dẫn khôi phục mật khẩu đã được gửi'
                ]);
            }
        } catch (Exception $e) {
            error_log("Lỗi trong forgotPassword: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi hệ thống, vui lòng thử lại sau.'
            ]);
        }
        exit;
    }
    
    /**
     * Validate contact information (email or phone)
     */
    private function validateContactInfo($contact)
    {
        if (empty($contact)) {
            return [
                'valid' => false,
                'message' => 'Vui lòng nhập email hoặc số điện thoại'
            ];
        }
        
        // Check if it's email format
        $isEmail = filter_var($contact, FILTER_VALIDATE_EMAIL);
        
        // Check if it's phone format
        $isPhone = $this->validatePhoneNumber($contact);
        
        if (!$isEmail && !$isPhone) {
            return [
                'valid' => false,
                'message' => 'Email hoặc số điện thoại không đúng định dạng'
            ];
        }
        
        return ['valid' => true];
    }
    
    /**
     * Log out user
     */
    public function logout()
    {
        // Lưu ID người dùng trước khi xóa để làm sạch giỏ hàng session
        $hadUser = isset($_SESSION['user'], $_SESSION['user']['id_User']);
        
        // Clear user session
        unset($_SESSION['user']);
        
        // Clear cart related session variables completely
        unset($_SESSION['cart']);
        unset($_SESSION['cart_loaded']);
        
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
    
    // ... (các phương thức khác giữ nguyên như sendVerificationEmail, verifyResetCode, etc.)
    
    /**
     * Gửi email chứa mã xác thực
     */
    private function sendVerificationEmail($email, $code)
    {
        try {
            $autoloadPaths = [
                // Thử nhiều đường dẫn khác nhau dựa trên cấu trúc thư mục thực tế
                __DIR__ . '/../../vendor/autoload.php',  // Đường dẫn đúng theo cấu trúc thư mục của bạn
                __DIR__ . '/../vendor/autoload.php',
                'vendor/autoload.php',
                $_SERVER['DOCUMENT_ROOT'] . '/PROJECT_WEBSITE/vendor/autoload.php'
            ];
            
            $autoloadLoaded = false;
            foreach ($autoloadPaths as $path) {
                if (file_exists($path)) {
                    require_once $path;
                    $autoloadLoaded = true;
                    error_log("Đã nạp autoload.php thành công từ: " . $path);
                    break;
                }
            }
            
            if (!$autoloadLoaded) {
                error_log("Không thể tìm thấy autoload.php. Đã kiểm tra các đường dẫn: " . implode(', ', $autoloadPaths));
                
                // Thử nạp trực tiếp từ thư mục PHPMailer trong vendor
                $phpmailerPath = __DIR__ . '/../../vendor/phpmailer/phpmailer/src/';
                if (file_exists($phpmailerPath . 'Exception.php')) {
                    require_once $phpmailerPath . 'Exception.php';
                    require_once $phpmailerPath . 'PHPMailer.php';
                    require_once $phpmailerPath . 'SMTP.php';
                    error_log("Đã nạp PHPMailer trực tiếp từ: " . $phpmailerPath);
                    $autoloadLoaded = true;
                } else {
                    error_log("Không thể tìm thấy các file PHPMailer tại: " . $phpmailerPath);
                    return false;
                }
            }
            
            // Kiểm tra lớp PHPMailer có tồn tại không
            if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
                error_log("Lỗi: Không tìm thấy lớp PHPMailer");
                return false;
            }
            
            // Tạo một instance mới của PHPMailer
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            
            // Bật chế độ debug
            $mail->SMTPDebug = 2; // Mức độ debug: 0 = tắt, 1 = thông báo, 2 = thông báo + dữ liệu
            $mail->Debugoutput = function($str, $level) {
                error_log("PHPMailer Debug: $str");
            };
            
            // Cấu hình Server
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'nguyentranminhquan.dl2018@gmail.com';
            $mail->Password   = 'mpzw hybb jpiv ubaj'; // Mật khẩu ứng dụng Gmail
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';
            
            // Người nhận
            $mail->setFrom('nguyentranminhquan.dl2018@gmail.com', '160STORE');
            $mail->addAddress($email);
            // $storeName = $this->userModel->getSettingValueByKey('site_name');

            // Nội dung
            $mail->isHTML(true);
          $mail->Subject = 'SR Store-Mã xác thực đặt lại mật khẩu';

            
            // Tạo nội dung email đẹp mắt
            $mailContent = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 5px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h2 style="color: #333;">Xác thực mật khẩu</h2>
                </div>
                <div style="padding: 20px; background-color: #f9f9f9; border-radius: 5px;">
                    <p>Xin chào,</p>
                    <p>Bạn vừa yêu cầu đặt lại mật khẩu cho tài khoản của mình. Đây là mã xác thực của bạn:</p>
                    <div style="text-align: center; margin: 30px 0;">
                        <div style="display: inline-block; padding: 15px 30px; background-color: #f5f5f5; border-radius: 5px; letter-spacing: 5px; font-size: 24px; font-weight: bold;">
                            '.$code.'
                        </div>
                    </div>
                    <p>Mã xác thực này sẽ hết hạn sau 30 phút.</p>
                    <p>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email.</p>
                </div>
                <div style="margin-top: 20px; text-align: center; color: #777; font-size: 12px;">
                    <p>© 2025 SR StoreStore All rights reserved.</p>
                </div>
            </div>';
            
            $mail->Body = $mailContent;
            $mail->AltBody = "Mã xác thực của bạn là: $code\n\nMã này sẽ hết hạn sau 30 phút.";
            
            // Gửi email
            $result = $mail->send();
            error_log("Kết quả gửi email: " . ($result ? "Thành công" : "Thất bại"));
            return $result;
            
        } catch (Exception $e) {
            error_log("Lỗi chi tiết khi gửi email: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Xác thực mã để đặt lại mật khẩu
     */
    public function verifyResetCode()
    {
        // Kiểm tra phương thức
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Phương thức không hợp lệ'
            ]);
            exit;
        }
        
        // Lấy mã xác thực từ POST
        $code = $_POST['code'] ?? '';
        $contact = $_SESSION['reset_contact'] ?? '';
        
        if (empty($code) || empty($contact)) {
            echo json_encode([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ'
            ]);
            exit;
        }
        
        // Kiểm tra xem đầu vào là email hay số điện thoại
        $isPhone = preg_match('/^[0-9]{10,11}$/', $contact);
        
        // Tìm người dùng
        $user = $isPhone 
            ? $this->userModel->findByPhone($contact) 
            : $this->userModel->findByEmail($contact);
        
        // Kiểm tra user và verification_code
        if (!$user) {
            echo json_encode([
                'success' => false,
                'message' => 'Không tìm thấy tài khoản'
            ]);
            exit;
        }
        
        // Kiểm tra mã xác thực
        if ($user['verification_code'] !== $code) {
            echo json_encode([
                'success' => false,
                'message' => 'Mã xác thực không chính xác'
            ]);
            exit;
        }
        
        // Mã xác thực chính xác, cho phép đặt lại mật khẩu
        echo json_encode([
            'success' => true,
            'message' => 'Xác thực thành công',
            'resetToken' => $_SESSION['reset_token']
        ]);
        exit;
    }
    
    /**
     * Hiển thị trang đặt lại mật khẩu sau khi xác thực
     */
    public function resetPassword()
    {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            $this->redirectWithMessage('index.php?controller=login', 
                'Token không hợp lệ', 'error');
            return;
        }
        
        // Xác thực token
        $user = $this->userModel->findByResetToken($token);
        
        if (!$user || strtotime($user['reset_token_expiry']) < time()) {
            $this->redirectWithMessage('index.php?controller=login', 
                'Token không hợp lệ hoặc đã hết hạn', 'error');
            return;
        }
        
        // Hiển thị form đặt lại mật khẩu
        $this->view('frontend.login.reset_password', [
            'token' => $token
        ]);
    }
    
    /**
     * Process reset password form submission via AJAX with enhanced validation
     */
    public function processResetPassword()
    {
        header('Content-Type: application/json');
        
        // Validate request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Phương thức không hợp lệ'
            ]);
            exit;
        }
        
        // Get form data
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';
        
        // Log để debug
        error_log("Token nhận được: " . $token);
        
        // Validate input
        if (empty($token) || empty($password) || empty($confirmPassword)) {
            echo json_encode([
                'success' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin',
                'debug' => ['token_empty' => empty($token)]
            ]);
            exit;
        }
        
        // Validate token
        $user = $this->userModel->findByResetToken($token);
        
        // Log để debug
        error_log("Kết quả tìm kiếm user từ token: " . ($user ? "Tìm thấy user ID: {$user['id_User']}" : "Không tìm thấy user"));
        
        if (!$user) {
            echo json_encode([
                'success' => false,
                'message' => 'Token không hợp lệ',
                'debug' => ['token' => $token, 'user_found' => false]
            ]);
            exit;
        }
        
        if (strtotime($user['reset_token_expiry']) < time()) {
            echo json_encode([
                'success' => false,
                'message' => 'Token đã hết hạn',
                'debug' => [
                    'token_expiry' => $user['reset_token_expiry'],
                    'current_time' => date('Y-m-d H:i:s'),
                    'expired' => true
                ]
            ]);
            exit;
        }
        
        // Validate password match
        if ($password !== $confirmPassword) {
            echo json_encode([
                'success' => false,
                'message' => 'Mật khẩu xác nhận không khớp'
            ]);
            exit;
        }
        
        // Enhanced password validation
        $passwordValidation = $this->validatePassword($password);
        if (!$passwordValidation['valid']) {
            echo json_encode([
                'success' => false,
                'message' => $passwordValidation['message']
            ]);
            exit;
        }
        
        // Hash new password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Update password and clear reset token
        if ($this->userModel->updatePassword($user['id_User'], $hashedPassword)) {
            echo json_encode([
                'success' => true,
                'message' => 'Mật khẩu đã được cập nhật! Vui lòng đăng nhập bằng mật khẩu mới.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật mật khẩu. Vui lòng thử lại sau.'
            ]);
        }
        exit;
    }
}
?>