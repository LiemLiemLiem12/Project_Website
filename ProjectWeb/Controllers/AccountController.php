<?php
class AccountController extends BaseController
{
    private $userModel;
    private $orderModel;
    private $addressModel;

    public function __construct()
    {
        parent::__construct();
        
        // Load các model cần thiết
        $this->loadModel('UserModel');
        $this->userModel = new UserModel();
        
        $this->loadModel('OrderModel');
        $this->orderModel = new OrderModel();
        
        $this->loadModel('AddressModel');
        $this->addressModel = new AddressModel();
        
        // Kiểm tra nếu người dùng chưa đăng nhập, chuyển hướng đến trang đăng nhập
        if (!isset($_SESSION['user'])) {
            $this->redirectWithMessage('index.php?controller=login', 
                'Vui lòng đăng nhập để quản lý tài khoản', 'warning');
            exit;
        }
    }
    
    /**
     * Hiển thị trang tổng quan tài khoản
     */
    public function index()
    {
        // Xác định tab hiện tại từ tham số URL, mặc định là "profile"
        $currentTab = isset($_GET['tab']) ? $_GET['tab'] : 'profile';
        
        // Lấy thông tin người dùng hiện tại
        $userId = $_SESSION['user']['id_User'];
        $user = $this->userModel->findById($userId);
        
        // Lấy danh sách đơn hàng của người dùng
        $orders = $this->orderModel->getUserOrders($userId);
        
        // Lấy danh sách địa chỉ của người dùng
        $addresses = $this->addressModel->getUserAddresses($userId);
        
        // Hiển thị view tài khoản
        $this->view('frontend.account.index', [
            'user' => $user,
            'orders' => $orders,
            'addresses' => $addresses,
            'currentTab' => $currentTab
        ]);
    }
    
    /**
     * Cập nhật thông tin cá nhân
     */
    /**
 * Cập nhật thông tin cá nhân
 */
public function updateProfile()
{
    // Kiểm tra phương thức request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->jsonResponse([
            'success' => false,
            'message' => 'Phương thức không hợp lệ'
        ]);
        return;
    }
    
    // Lấy thông tin từ form
    $userId = $_SESSION['user']['id_User'];
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    
    // Validate dữ liệu đầu vào
    if (empty($name) || empty($phone)) {
        $this->jsonResponse([
            'success' => false,
            'message' => 'Vui lòng điền đầy đủ thông tin cần thiết'
        ]);
        return;
    }
    
    // Cập nhật thông tin người dùng - chỉ cập nhật name và phone
    $userData = [
        'name' => $name,
        'phone' => $phone,
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    if ($this->userModel->updateAccount($userId, $userData)) {
        // Cập nhật thông tin trong session
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['phone'] = $phone;
        
        $this->redirectWithMessage('index.php?controller=account&tab=profile', 
            'Thông tin cá nhân đã được cập nhật thành công', 'success');
    } else {
        $this->redirectWithMessage('index.php?controller=account&tab=profile', 
            'Có lỗi xảy ra khi cập nhật thông tin. Vui lòng thử lại sau.', 'error');
    }
}
    
    /**
     * Thay đổi mật khẩu người dùng
     */
    public function changePassword()
    {
        // Kiểm tra phương thức request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithMessage('index.php?controller=account&tab=password', 
                'Phương thức không hợp lệ', 'error');
            return;
        }
        
        // Lấy thông tin từ form
        $userId = $_SESSION['user']['id_User'];
        $currentPassword = $_POST['currentPassword'] ?? '';
        $newPassword = $_POST['newPassword'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';
        
        // Validate dữ liệu đầu vào
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $this->redirectWithMessage('index.php?controller=account&tab=password', 
                'Vui lòng điền đầy đủ thông tin', 'error');
            return;
        }
        
        // Kiểm tra mật khẩu mới và xác nhận mật khẩu
        if ($newPassword !== $confirmPassword) {
            $this->redirectWithMessage('index.php?controller=account&tab=password', 
                'Mật khẩu mới và xác nhận mật khẩu không khớp', 'error');
            return;
        }
        
        // Kiểm tra độ mạnh của mật khẩu
        if (strlen($newPassword) < 8) {
            $this->redirectWithMessage('index.php?controller=account&tab=password', 
                'Mật khẩu mới phải có ít nhất 8 ký tự', 'error');
            return;
        }
        
        // Lấy thông tin người dùng
        $user = $this->userModel->findById($userId);
        
        // Kiểm tra mật khẩu hiện tại
        if (!password_verify($currentPassword, $user['password'])) {
            // Cho phép kiểm tra trực tiếp (không dùng password_verify) trong môi trường phát triển
            if ($user['password'] !== $currentPassword) {
                $this->redirectWithMessage('index.php?controller=account&tab=password', 
                    'Mật khẩu hiện tại không chính xác', 'error');
                return;
            }
        }
        
        // Mã hóa mật khẩu mới
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Cập nhật mật khẩu mới
        if ($this->userModel->updatePassword($userId, $hashedPassword)) {
            $this->redirectWithMessage('index.php?controller=account&tab=password', 
                'Mật khẩu đã được cập nhật thành công', 'success');
        } else {
            $this->redirectWithMessage('index.php?controller=account&tab=password', 
                'Có lỗi xảy ra khi cập nhật mật khẩu. Vui lòng thử lại sau.', 'error');
        }
    }
    
    /**
     * Thêm địa chỉ mới
     */
    public function addAddress()
    {
        // Kiểm tra phương thức request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithMessage('index.php?controller=account&tab=address', 
                'Phương thức không hợp lệ', 'error');
            return;
        }
        
        // Lấy thông tin từ form
        $userId = $_SESSION['user']['id_User'];
        $addressName = $_POST['address_name'] ?? 'Địa chỉ của tôi';
        $receiverName = $_POST['receiver_name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $streetAddress = $_POST['street_address'] ?? '';
        $province = $_POST['province'] ?? '';
        $district = $_POST['district'] ?? '';
        $ward = $_POST['ward'] ?? '';
        $isDefault = isset($_POST['is_default']) && $_POST['is_default'] == '1';
        
        // Validate dữ liệu đầu vào
        if (empty($receiverName) || empty($phone) || empty($streetAddress) || 
            empty($province) || empty($district) || empty($ward)) {
            $this->redirectWithMessage('index.php?controller=account&tab=address', 
                'Vui lòng điền đầy đủ thông tin địa chỉ', 'error');
            return;
        }
        
        // Tạo dữ liệu địa chỉ mới
        $addressData = [
            'id_User' => $userId,
            'address_name' => $addressName,
            'receiver_name' => $receiverName,
            'phone' => $phone,
            'street_address' => $streetAddress,
            'province' => $province,
            'district' => $district,
            'ward' => $ward,
            'is_default' => $isDefault ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Nếu địa chỉ mới được đánh dấu là mặc định, cập nhật các địa chỉ khác không còn là mặc định
        if ($isDefault) {
            $this->addressModel->clearDefaultAddress($userId);
        }
        
        // Thêm địa chỉ mới
        if ($addressId = $this->addressModel->addAddress($addressData)) {
            $this->redirectWithMessage('index.php?controller=account&tab=address', 
                'Đã thêm địa chỉ mới thành công', 'success');
        } else {
            $this->redirectWithMessage('index.php?controller=account&tab=address', 
                'Có lỗi xảy ra khi thêm địa chỉ. Vui lòng thử lại sau.', 'error');
        }
    }
    
    /**
     * Cập nhật địa chỉ
     */
    public function updateAddress()
    {
        // Kiểm tra phương thức request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithMessage('index.php?controller=account&tab=address', 
                'Phương thức không hợp lệ', 'error');
            return;
        }
        
        // Lấy thông tin từ form
        $userId = $_SESSION['user']['id_User'];
        $addressId = $_POST['address_id'] ?? 0;
        $addressName = $_POST['address_name'] ?? 'Địa chỉ của tôi';
        $receiverName = $_POST['receiver_name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $streetAddress = $_POST['street_address'] ?? '';
        $province = $_POST['province'] ?? '';
        $district = $_POST['district'] ?? '';
        $ward = $_POST['ward'] ?? '';
        $isDefault = isset($_POST['is_default']) && $_POST['is_default'] == '1';
        
        // Validate dữ liệu đầu vào
        if (!$addressId || empty($receiverName) || empty($phone) || empty($streetAddress) || 
            empty($province) || empty($district) || empty($ward)) {
            $this->redirectWithMessage('index.php?controller=account&tab=address', 
                'Vui lòng điền đầy đủ thông tin địa chỉ', 'error');
            return;
        }
        
        // Kiểm tra xem địa chỉ có tồn tại và thuộc về người dùng không
        $existingAddress = $this->addressModel->getAddress($addressId);
        if (!$existingAddress || $existingAddress['id_User'] != $userId) {
            $this->redirectWithMessage('index.php?controller=account&tab=address', 
                'Địa chỉ không tồn tại hoặc không thuộc về bạn', 'error');
            return;
        }
        
        // Cập nhật dữ liệu địa chỉ
        $addressData = [
            'address_name' => $addressName,
            'receiver_name' => $receiverName,
            'phone' => $phone,
            'street_address' => $streetAddress,
            'province' => $province,
            'district' => $district,
            'ward' => $ward,
            'is_default' => $isDefault ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Nếu địa chỉ được đánh dấu là mặc định, cập nhật các địa chỉ khác không còn là mặc định
        if ($isDefault) {
            $this->addressModel->clearDefaultAddress($userId);
        }
        
        // Cập nhật địa chỉ
        if ($this->addressModel->updateAddress($addressId, $addressData)) {
            $this->redirectWithMessage('index.php?controller=account&tab=address', 
                'Đã cập nhật địa chỉ thành công', 'success');
        } else {
            $this->redirectWithMessage('index.php?controller=account&tab=address', 
                'Có lỗi xảy ra khi cập nhật địa chỉ. Vui lòng thử lại sau.', 'error');
        }
    }
    
    /**
     * Xóa địa chỉ
     */
    public function deleteAddress()
    {
        // Kiểm tra phương thức request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithMessage('index.php?controller=account&tab=address', 
                'Phương thức không hợp lệ', 'error');
            return;
        }
        
        // Lấy thông tin từ form
        $userId = $_SESSION['user']['id_User'];
        $addressId = $_POST['address_id'] ?? 0;
        
        // Validate dữ liệu đầu vào
        if (!$addressId) {
            $this->redirectWithMessage('index.php?controller=account&tab=address', 
                'Địa chỉ không hợp lệ', 'error');
            return;
        }
        
        // Kiểm tra xem địa chỉ có tồn tại và thuộc về người dùng không
        $existingAddress = $this->addressModel->getAddress($addressId);
        if (!$existingAddress || $existingAddress['id_User'] != $userId) {
            $this->redirectWithMessage('index.php?controller=account&tab=address', 
                'Địa chỉ không tồn tại hoặc không thuộc về bạn', 'error');
            return;
        }
        
        // Kiểm tra xem địa chỉ có phải là mặc định không
        if ($existingAddress['is_default']) {
            $this->redirectWithMessage('index.php?controller=account&tab=address', 
                'Không thể xóa địa chỉ mặc định. Vui lòng đặt địa chỉ khác làm mặc định trước khi xóa.', 'error');
            return;
        }
        
        // Xóa địa chỉ
        if ($this->addressModel->deleteAddress($addressId)) {
            $this->redirectWithMessage('index.php?controller=account&tab=address', 
                'Đã xóa địa chỉ thành công', 'success');
        } else {
            $this->redirectWithMessage('index.php?controller=account&tab=address', 
                'Có lỗi xảy ra khi xóa địa chỉ. Vui lòng thử lại sau.', 'error');
        }
    }
    
    /**
     * Đặt địa chỉ làm mặc định
     */
    public function setDefaultAddress()
    {
        // Kiểm tra phương thức request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithMessage('index.php?controller=account&tab=address', 
                'Phương thức không hợp lệ', 'error');
            return;
        }
        
        // Lấy thông tin từ form
        $userId = $_SESSION['user']['id_User'];
        $addressId = $_POST['address_id'] ?? 0;
        
        // Validate dữ liệu đầu vào
        if (!$addressId) {
            $this->redirectWithMessage('index.php?controller=account&tab=address', 
                'Địa chỉ không hợp lệ', 'error');
            return;
        }
        
        // Kiểm tra xem địa chỉ có tồn tại và thuộc về người dùng không
        $existingAddress = $this->addressModel->getAddress($addressId);
        if (!$existingAddress || $existingAddress['id_User'] != $userId) {
            $this->redirectWithMessage('index.php?controller=account&tab=address', 
                'Địa chỉ không tồn tại hoặc không thuộc về bạn', 'error');
            return;
        }
        
        // Đặt các địa chỉ khác không còn là mặc định
        $this->addressModel->clearDefaultAddress($userId);
        
        // Đặt địa chỉ hiện tại là mặc định
        if ($this->addressModel->setDefaultAddress($addressId)) {
            $this->redirectWithMessage('index.php?controller=account&tab=address', 
                'Đã đặt địa chỉ làm mặc định thành công', 'success');
        } else {
            $this->redirectWithMessage('index.php?controller=account&tab=address', 
                'Có lỗi xảy ra khi đặt địa chỉ làm mặc định. Vui lòng thử lại sau.', 'error');
        }
    }
    
    /**
     * Lấy chi tiết đơn hàng qua AJAX
     */
    public function getOrderDetails()
    {
        // Đặt header JSON
        header('Content-Type: application/json');
        
        // Kiểm tra phương thức request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Phương thức không hợp lệ'
            ]);
            exit;
        }
        
        // Lấy ID đơn hàng từ POST
        $orderId = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
        $userId = $_SESSION['user']['id_User'];
        
        // Validate dữ liệu đầu vào
        if (!$orderId) {
            echo json_encode([
                'success' => false,
                'message' => 'Mã đơn hàng không hợp lệ'
            ]);
            exit;
        }
        
        // Lấy thông tin chi tiết đơn hàng
        $order = $this->orderModel->getOrderWithDetails($orderId);
        
        // Kiểm tra xem đơn hàng có tồn tại và thuộc về người dùng không
        if (!$order || $order['id_User'] != $userId) {
            echo json_encode([
                'success' => false,
                'message' => 'Đơn hàng không tồn tại hoặc không thuộc về bạn'
            ]);
            exit;
        }
        
        // Xử lý thông tin chi tiết đơn hàng
        $orderDetails = [];
        foreach ($order['details'] as $detail) {
            $orderDetails[] = [
                'product' => [
                    'id' => $detail['id_Product'],
                    'name' => $detail['name'],
                    'main_image' => $detail['main_image'],
                    'current_price' => $detail['current_price']
                ],
                'quantity' => $detail['quantity'],
                'size' => $detail['size'],
                'price' => (float)($detail['sub_total'] / $detail['quantity']),
                'sub_total' => (float)$detail['sub_total']
            ];
        }
        
        // Trả về dữ liệu đơn hàng dưới dạng JSON
        echo json_encode([
            'success' => true,
            'order' => $order,
            'orderDetails' => $orderDetails
        ]);
        exit;
    }
    
    /**
     * Trợ giúp chọn địa chỉ qua AJAX
     */
    public function selectAddress()
    {
        // Đặt header JSON
        header('Content-Type: application/json');
        
        // Kiểm tra phương thức request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Phương thức không hợp lệ'
            ]);
            exit;
        }
        
        // Lấy ID địa chỉ từ POST
        $addressId = isset($_POST['address_id']) ? (int)$_POST['address_id'] : 0;
        $userId = $_SESSION['user']['id_User'];
        
        if (!$addressId) {
            echo json_encode([
                'success' => false,
                'message' => 'ID địa chỉ không hợp lệ'
            ]);
            exit;
        }
        
        // Lấy thông tin địa chỉ
        $address = $this->addressModel->getAddress($addressId);
        
        // Kiểm tra địa chỉ tồn tại và thuộc về người dùng hiện tại
        if (!$address || $address['id_User'] != $userId) {
            echo json_encode([
                'success' => false,
                'message' => 'Địa chỉ không tồn tại hoặc không thuộc về bạn'
            ]);
            exit;
        }
        
        echo json_encode([
            'success' => true,
            'address' => $address
        ]);
        exit;
    }
    
    /**
     * Upload ảnh đại diện
     */
    public function uploadAvatar()
    {
        // Kiểm tra phương thức request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithMessage('index.php?controller=account&tab=profile', 
                'Phương thức không hợp lệ', 'error');
            return;
        }
        
        // Kiểm tra xem có file được upload không
        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $this->redirectWithMessage('index.php?controller=account&tab=profile', 
                'Không có file được upload hoặc có lỗi trong quá trình upload','error');
            return;
        }
        
        $userId = $_SESSION['user']['id_User'];
        $file = $_FILES['avatar'];
        
        // Kiểm tra loại file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            $this->redirectWithMessage('index.php?controller=account&tab=profile', 
                'Chỉ chấp nhận file hình ảnh (JPG, PNG, GIF)', 'error');
            return;
        }
        
        // Kiểm tra kích thước file (giới hạn 2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            $this->redirectWithMessage('index.php?controller=account&tab=profile', 
                'Kích thước file không được vượt quá 2MB', 'error');
            return;
        }
        
        // Tạo tên file mới để tránh trùng lặp
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = 'avatar_' . $userId . '_' . time() . '.' . $extension;
        
        // Đường dẫn thư mục upload
        $uploadDir = 'upload/img/avatars/';
        
        // Tạo thư mục nếu chưa tồn tại
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $uploadPath = $uploadDir . $newFileName;
        
        // Di chuyển file upload vào thư mục đích
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Cập nhật đường dẫn avatar trong database
            if ($this->userModel->updateAvatar($userId, $uploadPath)) {
                // Cập nhật SESSION
                $_SESSION['user']['avatar'] = $uploadPath;
                
                $this->redirectWithMessage('index.php?controller=account&tab=profile', 
                    'Đã cập nhật ảnh đại diện thành công', 'success');
            } else {
                $this->redirectWithMessage('index.php?controller=account&tab=profile', 
                    'Có lỗi xảy ra khi cập nhật thông tin ảnh đại diện trong database', 'error');
            }
        } else {
            $this->redirectWithMessage('index.php?controller=account&tab=profile', 
                'Có lỗi xảy ra khi lưu file. Vui lòng thử lại sau.', 'error');
        }
    }
    
    /**
     * Trợ giúp JSON Response
     */
    private function jsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Trợ giúp chuyển hướng với thông báo
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
        $this->redirectWithMessage(
            'index.php',
            'Đã đăng xuất thành công',
            'success'
        );
    }
}