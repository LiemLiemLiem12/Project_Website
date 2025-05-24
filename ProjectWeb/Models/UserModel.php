<?php
class UserModel extends BaseModel
{
    const TABLE = "user";

    
    public function getNumByMonth($month)
    {
        return $this->getScalar("
            SELECT count(*) as 'Tong'
            FROM " . self::TABLE . "
            WHERE MONTH(CREATED_AT) = " . $month . " AND YEAR(CREATED_AT) = YEAR(CURDATE()) AND ROLE = 'user'
        ");
    }
    public function getSettingValueByKey($key)
{
    $key = $this->escapeValue($key);
    $sql = "SELECT setting_value FROM settings WHERE setting_key = '{$key}' LIMIT 1";
    $result = $this->_query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['setting_value'];
    }

    return null;
}

    /**
     * Find user by email
     */
    public function findByEmail($email)
    {
        $email = mysqli_real_escape_string($this->connect, $email);
        $sql = "SELECT * FROM " . self::TABLE . " WHERE email = '$email' LIMIT 1";
        $result = $this->getByQuery($sql);
        return $result ? $result[0] : null;
    }
    
    /**
     * Find user by phone number
     */
    public function findByPhone($phone)
    {
        $phone = mysqli_real_escape_string($this->connect, $phone);
        $sql = "SELECT * FROM " . self::TABLE . " WHERE phone = '$phone' LIMIT 1";
        $result = $this->getByQuery($sql);
        return $result ? $result[0] : null;
    }
    
    /**
     * Find user by ID
     */
    public function findById($id)
    {
        $id = (int)$id;
        $sql = "SELECT * FROM " . self::TABLE . " WHERE id_User = $id LIMIT 1";
        $result = $this->getByQuery($sql);
        return $result ? $result[0] : null;
    }
    
    /**
     * Find user by reset token
     */
    public function findByResetToken($token)
    {
        $token = mysqli_real_escape_string($this->connect, $token);
        $sql = "SELECT * FROM " . self::TABLE . " 
               WHERE reset_token = '$token' 
               LIMIT 1";
        
        error_log("SQL query để tìm token: $sql");
        
        $result = $this->getByQuery($sql);
        return $result ? $result[0] : null;
    }
    
    /**
     * Create a new user
     */
    public function createUser($userData)
    {
        // Ensure required fields are set
        if (!isset($userData['email']) || !isset($userData['password'])) {
            return false;
        }
        
        // Insert user data
        $this->create(self::TABLE, $userData);
        
        // Get the last inserted ID
        return $this->connect->insert_id;
    }
    
    /**
     * Update user information
     */
    public function updateUser($userId, $userData)
    {
        $userId = (int)$userId;
        
        // Chuẩn bị dữ liệu để cập nhật
        $updateSets = [];
        foreach ($userData as $key => $value) {
            if ($value === null) {
                $updateSets[] = "{$key} = NULL";
            } else {
                $value = mysqli_real_escape_string($this->connect, $value);
                $updateSets[] = "{$key} = '{$value}'";
            }
        }
        
        $updateString = implode(', ', $updateSets);
        
        // Tạo và thực thi câu truy vấn UPDATE
        $sql = "UPDATE " . self::TABLE . " SET {$updateString} WHERE id_User = {$userId}";
        
        return $this->_query($sql);
    }
    
    /**
     * Update user account (alias for updateUser for compatibility)
     */
    public function updateAccount($userId, $userData)
    {
        return $this->updateUser($userId, $userData);
    }
    
    /**
     * Update user avatar
     */
    public function updateAvatar($userId, $avatarPath)
    {
        $userId = (int)$userId;
        $avatarPath = mysqli_real_escape_string($this->connect, $avatarPath);
        
        $sql = "UPDATE " . self::TABLE . " SET avatar = '{$avatarPath}' WHERE id_User = {$userId}";
        return $this->_query($sql);
    }
    
    /**
     * Mark user as verified
     */
    public function markAsVerified($userId)
    {
        $userId = (int)$userId;
        
        $sql = "UPDATE " . self::TABLE . " 
                SET verified = 1, 
                    verification_code = NULL 
                WHERE id_User = $userId";
        
        return $this->_query($sql);
    }
    
    /**
     * Save password reset token
     */
    public function saveResetToken($userId, $token, $expiry)
    {
        $userId = (int)$userId;
        $token = mysqli_real_escape_string($this->connect, $token);
        $expiry = mysqli_real_escape_string($this->connect, $expiry);
        
        $sql = "UPDATE " . self::TABLE . " 
                SET reset_token = '$token', 
                    reset_token_expiry = '$expiry' 
                WHERE id_User = $userId";
        
        return $this->_query($sql);
    }
    
    /**
     * Update user password
     */
    public function updatePassword($userId, $hashedPassword)
    {
        $userId = (int)$userId;
        $hashedPassword = mysqli_real_escape_string($this->connect, $hashedPassword);
        
        $sql = "UPDATE " . self::TABLE . " 
                SET password = '$hashedPassword', 
                    reset_token = NULL, 
                    reset_token_expiry = NULL 
                WHERE id_User = $userId";
        
        return $this->_query($sql);
    }
    
    /**
     * Update user verification code
     */
    public function updateVerificationCode($userId, $code)
    {
        $userId = (int)$userId;
        $code = mysqli_real_escape_string($this->connect, $code);
        
        $sql = "UPDATE " . self::TABLE . " 
                SET verification_code = '$code' 
                WHERE id_User = $userId";
        
        return $this->_query($sql);
    }

    /**
     * Find user by verification code
     */
    public function findByVerificationCode($code)
    {
        $code = mysqli_real_escape_string($this->connect, $code);
        $sql = "SELECT * FROM " . self::TABLE . " 
                WHERE verification_code = '$code' 
                LIMIT 1";
        $result = $this->getByQuery($sql);
        return $result ? $result[0] : null;
    }
}
?>