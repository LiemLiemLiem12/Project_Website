<?php
class AddressModel extends BaseModel
{
    const TABLE = 'addresses';
    
    /**
     * Lấy tất cả địa chỉ của người dùng
     *
     * @param int $userId ID của người dùng
     * @return array Danh sách địa chỉ
     */
    public function getUserAddresses($userId)
    {
        $userId = (int)$userId;
        $sql = "SELECT * FROM " . self::TABLE . " WHERE id_User = {$userId} ORDER BY is_default DESC, created_at DESC";
        return $this->getByQuery($sql);
    }
    
    /**
     * Lấy thông tin chi tiết của một địa chỉ
     *
     * @param int $addressId ID của địa chỉ
     * @return array|null Thông tin địa chỉ hoặc null nếu không tìm thấy
     */
    public function getAddress($addressId)
    {
        $addressId = (int)$addressId;
        $sql = "SELECT * FROM " . self::TABLE . " WHERE id = {$addressId} LIMIT 1";
        $result = $this->getByQuery($sql);
        return !empty($result) ? $result[0] : null;
    }
    
    /**
     * Lấy địa chỉ mặc định của người dùng
     *
     * @param int $userId ID của người dùng
     * @return array|null Thông tin địa chỉ mặc định hoặc null nếu không tìm thấy
     */
    public function getDefaultAddress($userId)
    {
        $userId = (int)$userId;
        $sql = "SELECT * FROM " . self::TABLE . " WHERE id_User = {$userId} AND is_default = 1 LIMIT 1";
        $result = $this->getByQuery($sql);
        return !empty($result) ? $result[0] : null;
    }
    
    /**
     * Thêm địa chỉ mới
     *
     * @param array $data Thông tin địa chỉ
     * @return int|bool ID của địa chỉ đã thêm hoặc false nếu thất bại
     */
    public function addAddress($data)
    {
        // Sử dụng phương thức create từ BaseModel
        $this->create(self::TABLE, $data);
        
        // Trả về ID đã insert
        return $this->connect->insert_id;
    }
    
    /**
     * Cập nhật thông tin địa chỉ
     *
     * @param int $addressId ID của địa chỉ
     * @param array $data Thông tin địa chỉ mới
     * @return bool Kết quả cập nhật
     */
    public function updateAddress($addressId, $data)
    {
        $addressId = (int)$addressId;
        
        // Chuẩn bị các trường dữ liệu để cập nhật
        $updateSets = [];
        foreach ($data as $key => $value) {
            $value = $this->connect->real_escape_string($value);
            $updateSets[] = "{$key} = '{$value}'";
        }
        
        $updateString = implode(', ', $updateSets);
        
        // Tạo và thực thi câu truy vấn UPDATE
        $sql = "UPDATE " . self::TABLE . " SET {$updateString} WHERE id = {$addressId}";
        $result = $this->_query($sql);
        
        return $result;
    }
    
    /**
     * Xóa địa chỉ
     *
     * @param int $addressId ID của địa chỉ
     * @return bool Kết quả xóa
     */
    public function deleteAddress($addressId)
    {
        $addressId = (int)$addressId;
        $sql = "DELETE FROM " . self::TABLE . " WHERE id = {$addressId}";
        return $this->_query($sql);
    }
    
    /**
     * Xóa trạng thái mặc định của tất cả địa chỉ của một người dùng
     *
     * @param int $userId ID của người dùng
     * @return bool Kết quả cập nhật
     */
    public function clearDefaultAddress($userId)
    {
        $userId = (int)$userId;
        $sql = "UPDATE " . self::TABLE . " SET is_default = 0 WHERE id_User = {$userId}";
        return $this->_query($sql);
    }
    
    /**
     * Đặt một địa chỉ làm địa chỉ mặc định
     *
     * @param int $addressId ID của địa chỉ
     * @return bool Kết quả cập nhật
     */
    public function setDefaultAddress($addressId)
    {
        $addressId = (int)$addressId;
        $sql = "UPDATE " . self::TABLE . " SET is_default = 1 WHERE id = {$addressId}";
        return $this->_query($sql);
    }
    
    /**
     * Đếm số lượng địa chỉ của người dùng
     *
     * @param int $userId ID của người dùng
     * @return int Số lượng địa chỉ
     */
    public function countUserAddresses($userId)
    {
        $userId = (int)$userId;
        $sql = "SELECT COUNT(*) as count FROM " . self::TABLE . " WHERE id_User = {$userId}";
        $result = $this->getByQuery($sql);
        return isset($result[0]['count']) ? (int)$result[0]['count'] : 0;
    }
}