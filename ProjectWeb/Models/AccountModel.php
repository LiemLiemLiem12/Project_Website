<?php
class AccountModel extends BaseModel
{
    const TABLE = 'user'; // Bảng user vì chúng ta lưu thông tin tài khoản trong bảng user
    
    /**
     * Lấy thông tin tài khoản người dùng
     */
    public function getAccount($userId)
    {
        $userId = (int)$userId;
        $sql = "SELECT * FROM " . self::TABLE . " WHERE id_User = {$userId} LIMIT 1";
        $result = $this->getByQuery($sql);
        return !empty($result) ? $result[0] : null;
    }
    
    /**
     * Cập nhật thông tin tài khoản
     */
    public function updateAccount($userId, $data)
    {
        $userId = (int)$userId;
        
        // Chuẩn bị các trường dữ liệu để cập nhật
        $updateSets = [];
        foreach ($data as $key => $value) {
            $value = $this->connect->real_escape_string($value);
            $updateSets[] = "{$key} = '{$value}'";
        }
        
        $updateString = implode(', ', $updateSets);
        
        // Tạo và thực thi câu truy vấn UPDATE
        $sql = "UPDATE " . self::TABLE . " SET {$updateString} WHERE id_User = {$userId}";
        return $this->_query($sql);
    }
    
    /**
     * Cập nhật avatar của người dùng
     */
    public function updateAvatar($userId, $avatarPath)
    {
        $userId = (int)$userId;
        $avatarPath = $this->connect->real_escape_string($avatarPath);
        
        $sql = "UPDATE " . self::TABLE . " SET avatar = '{$avatarPath}' WHERE id_User = {$userId}";
        return $this->_query($sql);
    }
}