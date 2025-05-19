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
            'addresses' => $addresses
        ]);
    }
    
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
        $gender = $_POST['gender'] ?? '';
        $birthday = $_POST['birthday'] ?? null;
        
        // Validate dữ liệu đầu vào
        if (empty($name) || empty($phone)) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Vui lòng điền đầy đủ thông tin cần thiết'
            ]);
            return;
        }
        
        // Cập nhật thông tin người dùng
        $userData = [
            'name' => $name,
            'phone' => $phone,
            'gender' => $gender,
            'birthday' => $birthday,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->userModel->updateUser($userId, $userData)) {
            // Cập nhật thông tin trong session
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['phone'] = $phone;
            
            $this->jsonResponse([
                'success' => true,
                'message' => 'Thông tin cá nhân đã được cập nhật thành công'
            ]);
        } else {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật thông tin. Vui lòng thử lại sau.'
            ]);
        }
    }
    
    /**
     * Thay đổi mật khẩu người dùng
     */
    public function changePassword()
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
        $currentPassword = $_POST['currentPassword'] ?? '';
        $newPassword = $_POST['newPassword'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';
        
        // Validate dữ liệu đầu vào
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Vui lòng điền đầy đủ thông tin'
            ]);
            return;
        }
        
        // Kiểm tra mật khẩu mới và xác nhận mật khẩu
        if ($newPassword !== $confirmPassword) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Mật khẩu mới và xác nhận mật khẩu không khớp'
            ]);
            return;
        }
        
        // Kiểm tra độ mạnh của mật khẩu
        if (strlen($newPassword) < 8) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Mật khẩu mới phải có ít nhất 8 ký tự'
            ]);
            return;
        }
        
        // Lấy thông tin người dùng
        $user = $this->userModel->findById($userId);
        
        // Kiểm tra mật khẩu hiện tại
        if (!password_verify($currentPassword, $user['password'])) {
            // Cho phép kiểm tra trực tiếp (không dùng password_verify) trong môi trường phát triển
            if ($user['password'] !== $currentPassword) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Mật khẩu hiện tại không chính xác'
                ]);
                return;
            }
        }
        
        // Mã hóa mật khẩu mới
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Cập nhật mật khẩu mới
        if ($this->userModel->updatePassword($userId, $hashedPassword)) {
            $this->jsonResponse([
                'success' => true,
                'message' => 'Mật khẩu đã được cập nhật thành công'
            ]);
        } else {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật mật khẩu. Vui lòng thử lại sau.'
            ]);
        }
    }
    
    /**
     * Thêm địa chỉ mới
     */
    public function addAddress()
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
        $address = $_POST['address'] ?? '';
        $province = $_POST['province'] ?? '';
        $district = $_POST['district'] ?? '';
        $ward = $_POST['ward'] ?? '';
        $isDefault = isset($_POST['is_default']) && $_POST['is_default'] == '1';
        $addressType = $_POST['address_type'] ?? 'home';
        
        // Validate dữ liệu đầu vào
        if (empty($name) || empty($phone) || empty($address) || 
            empty($province) || empty($district) || empty($ward)) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Vui lòng điền đầy đủ thông tin địa chỉ'
            ]);
            return;
        }
        
        // Tạo dữ liệu địa chỉ mới
        $addressData = [
            'id_User' => $userId,
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
            'province' => $province,
            'district' => $district,
            'ward' => $ward,
            'is_default' => $isDefault ? 1 : 0,
            'address_type' => $addressType,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Nếu địa chỉ mới được đánh dấu là mặc định, cập nhật các địa chỉ khác không còn là mặc định
        if ($isDefault) {
            $this->addressModel->clearDefaultAddress($userId);
        }
        
        // Thêm địa chỉ mới
        if ($addressId = $this->addressModel->addAddress($addressData)) {
            $this->jsonResponse([
                'success' => true,
                'message' => 'Đã thêm địa chỉ mới thành công',
                'address_id' => $addressId
            ]);
        } else {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm địa chỉ. Vui lòng thử lại sau.'
            ]);
        }
    }
    
    /**
     * Cập nhật địa chỉ
     */
    public function updateAddress()
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
        $addressId = $_POST['address_id'] ?? 0;
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        $province = $_POST['province'] ?? '';
        $district = $_POST['district'] ?? '';
        $ward = $_POST['ward'] ?? '';
        $isDefault = isset($_POST['is_default']) && $_POST['is_default'] == '1';
        $addressType = $_POST['address_type'] ?? 'home';
        
        // Validate dữ liệu đầu vào
        if (!$addressId || empty($name) || empty($phone) || empty($address) || 
            empty($province) || empty($district) || empty($ward)) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Vui lòng điền đầy đủ thông tin địa chỉ'
            ]);
            return;
        }
        
        // Kiểm tra xem địa chỉ có tồn tại và thuộc về người dùng không
        $existingAddress = $this->addressModel->getAddress($addressId);
        if (!$existingAddress || $existingAddress['id_User'] != $userId) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Địa chỉ không tồn tại hoặc không thuộc về bạn'
            ]);
            return;
        }
        
        // Cập nhật dữ liệu địa chỉ
        $addressData = [
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
            'province' => $province,
            'district' => $district,
            'ward' => $ward,
            'is_default' => $isDefault ? 1 : 0,
            'address_type' => $addressType,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Nếu địa chỉ được đánh dấu là mặc định, cập nhật các địa chỉ khác không còn là mặc định
        if ($isDefault) {
            $this->addressModel->clearDefaultAddress($userId);
        }
        
        // Cập nhật địa chỉ
        if ($this->addressModel->updateAddress($addressId, $addressData)) {
            $this->jsonResponse([
                'success' => true,
                'message' => 'Đã cập nhật địa chỉ thành công'
            ]);
        } else {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật địa chỉ. Vui lòng thử lại sau.'
            ]);
        }
    }
    
    /**
     * Xóa địa chỉ
     */
    public function deleteAddress()
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
        $addressId = $_POST['address_id'] ?? 0;
        
        // Validate dữ liệu đầu vào
        if (!$addressId) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Địa chỉ không hợp lệ'
            ]);
            return;
        }
        
        // Kiểm tra xem địa chỉ có tồn tại và thuộc về người dùng không
        $existingAddress = $this->addressModel->getAddress($addressId);
        if (!$existingAddress || $existingAddress['id_User'] != $userId) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Địa chỉ không tồn tại hoặc không thuộc về bạn'
            ]);
            return;
        }
        
        // Kiểm tra xem địa chỉ có phải là mặc định không
        if ($existingAddress['is_default']) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Không thể xóa địa chỉ mặc định. Vui lòng đặt địa chỉ khác làm mặc định trước khi xóa.'
            ]);
            return;
        }
        
        // Xóa địa chỉ
        if ($this->addressModel->deleteAddress($addressId)) {
            $this->jsonResponse([
                'success' => true,
                'message' => 'Đã xóa địa chỉ thành công'
            ]);
        } else {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa địa chỉ. Vui lòng thử lại sau.'
            ]);
        }
    }
    
    /**
     * Đặt địa chỉ làm mặc định
     */
    public function setDefaultAddress()
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
        $addressId = $_POST['address_id'] ?? 0;
        
        // Validate dữ liệu đầu vào
        if (!$addressId) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Địa chỉ không hợp lệ'
            ]);
            return;
        }
        
        // Kiểm tra xem địa chỉ có tồn tại và thuộc về người dùng không
        $existingAddress = $this->addressModel->getAddress($addressId);
        if (!$existingAddress || $existingAddress['id_User'] != $userId) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Địa chỉ không tồn tại hoặc không thuộc về bạn'
            ]);
            return;
        }
        
        // Đặt các địa chỉ khác không còn là mặc định
        $this->addressModel->clearDefaultAddress($userId);
        
        // Đặt địa chỉ hiện tại là mặc định
        if ($this->addressModel->setDefaultAddress($addressId)) {
            $this->jsonResponse([
                'success' => true,
                'message' => 'Đã đặt địa chỉ làm mặc định thành công'
            ]);
        } else {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đặt địa chỉ làm mặc định. Vui lòng thử lại sau.'
            ]);
        }
    }
    
    /**
     * Lấy thông tin chi tiết đơn hàng
     */
    public function getOrderDetail()
    {
        // Kiểm tra phương thức request hoặc có thể sử dụng GET cho truy vấn này
        $orderId = $_GET['order_id'] ?? 0;
        $userId = $_SESSION['user']['id_User'];
        
        // Validate dữ liệu đầu vào
        if (!$orderId) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Mã đơn hàng không hợp lệ'
            ]);
            return;
        }
        
        // Lấy thông tin chi tiết đơn hàng
        $order = $this->orderModel->getOrderWithDetails($orderId);
        
        // Kiểm tra xem đơn hàng có tồn tại và thuộc về người dùng không
        if (!$order || $order['id_User'] != $userId) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Đơn hàng không tồn tại hoặc không thuộc về bạn'
            ]);
            return;
        }
        
        // Trả về dữ liệu đơn hàng dưới dạng JSON
        $this->jsonResponse([
            'success' => true,
            'order' => $order
        ]);
    }
    
    /**
     * Upload ảnh đại diện
     */
    public function uploadAvatar()
    {
        // Kiểm tra phương thức request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Phương thức không hợp lệ'
            ]);
            return;
        }
        
        // Kiểm tra xem có file được upload không
        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Không có file được upload hoặc có lỗi trong quá trình upload'
            ]);
            return;
        }
        
        $userId = $_SESSION['user']['id_User'];
        $file = $_FILES['avatar'];
        
        // Kiểm tra loại file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Chỉ chấp nhận file hình ảnh (JPG, PNG, GIF)'
            ]);
            return;
        }
        
        // Kiểm tra kích thước file (giới hạn 2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Kích thước file không được vượt quá 2MB'
            ]);
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
                
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Đã cập nhật ảnh đại diện thành công',
                    'avatar_url' => $uploadPath
                ]);
            } else {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi cập nhật thông tin ảnh đại diện trong database'
                ]);
            }
        } else {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lưu file. Vui lòng thử lại sau.'
            ]);
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
}