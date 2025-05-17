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
                AND reset_token_expiry > NOW() 
                LIMIT 1";
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
}
?>