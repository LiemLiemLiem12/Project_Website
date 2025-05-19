<?php
class AdminLoginModel {
    private $conn;

    public function __construct() {
        try {
            // Kết nối đến CSDL
            $this->conn = new mysqli('localhost', 'root', '', 'fashion_database');
            
            if ($this->conn->connect_error) {
                throw new Exception("Kết nối CSDL thất bại: " . $this->conn->connect_error);
            }
            
            $this->conn->set_charset("utf8mb4");
            
        } catch (Exception $e) {
            error_log("Database Error: " . $e->getMessage());
            exit("Lỗi kết nối đến cơ sở dữ liệu, vui lòng thử lại sau.");
        }
    }

    /**
     * Kiểm tra đăng nhập admin
     * @param string $email Email đăng nhập
     * @param string $password Mật khẩu (chưa hash)
     * @return array|false Thông tin admin nếu đăng nhập thành công, false nếu thất bại
     */
    public function checkAdminLogin($email, $password) {
        try {
            // Escape các giá trị đầu vào để tránh SQL injection
            $email = $this->conn->real_escape_string($email);
            
            // Tìm user với email và role là admin
            $sql = "SELECT id_User, name, email, password, role, hide 
                    FROM user 
                    WHERE email = '$email' 
                    AND role = 'admin' 
                    AND (hide = 0 OR hide IS NULL) 
                    LIMIT 1";
            
            $result = $this->conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();
                
                // So sánh password thuần (không md5)
                if ($password === $user['password']) {
                    // Không trả về password trong kết quả
                    unset($user['password']);
                    return $user;
                }
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("Login Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra email đã tồn tại chưa
     * @param string $email Email cần kiểm tra
     * @return bool True nếu email tồn tại, false nếu chưa
     */
    public function checkEmailExists($email) {
        try {
            $email = $this->conn->real_escape_string($email);
            
            $sql = "SELECT id_User FROM user WHERE email = '$email' LIMIT 1";
            $result = $this->conn->query($sql);
            
            return $result && $result->num_rows > 0;
            
        } catch (Exception $e) {
            error_log("Email Check Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật thời gian đăng nhập cuối
     * @param int $userId ID của user
     * @return bool True nếu cập nhật thành công, false nếu thất bại
     */
    public function updateLastLogin($userId) {
        try {
            $userId = (int)$userId;
            $currentTime = date('Y-m-d H:i:s');
            
            $sql = "UPDATE user SET updated_at = '$currentTime' WHERE id_User = $userId";
            return $this->conn->query($sql);
            
        } catch (Exception $e) {
            error_log("Update Last Login Error: " . $e->getMessage());
            return false;
        }
    }
}
