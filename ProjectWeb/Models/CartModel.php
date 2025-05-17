<?php
class CartModel extends BaseModel
{
    const TABLE = 'cart';
    
    /**
     * Get cart item by user ID, product ID and size
     */
    public function getCartItem($userId, $productId, $size)
    {
        $userId = intval($userId);
        $productId = intval($productId);
        $size = mysqli_real_escape_string($this->connect, $size);
        
        $sql = "SELECT * FROM " . self::TABLE . " 
                WHERE id_User = $userId 
                AND id_Product = $productId 
                AND size = '$size'
                LIMIT 1";
        
        $result = $this->getByQuery($sql);
        return $result ? $result[0] : null;
    }
    
    /**
     * Get all cart items for a user
     */
    public function getUserCart($userId)
    {
        $userId = intval($userId);
        
        $sql = "SELECT * FROM " . self::TABLE . " 
                WHERE id_User = $userId";
        
        return $this->getByQuery($sql);
    }
    
    /**
     * Add a new item to cart
     */
    public function addCartItem($userId, $productId, $quantity, $size = 'M')
    {
        $data = [
            'id_User' => $userId,
            'id_Product' => $productId,
            'quantity' => $quantity,
            'size' => $size,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->create(self::TABLE, $data);
    }
    
    /**
     * Update cart item quantity
     */
    public function updateCartItem($userId, $productId, $size, $quantity)
    {
        $userId = intval($userId);
        $productId = intval($productId);
        $quantity = intval($quantity);
        $size = mysqli_real_escape_string($this->connect, $size);
        
        $sql = "UPDATE " . self::TABLE . " 
                SET quantity = $quantity 
                WHERE id_User = $userId 
                AND id_Product = $productId 
                AND size = '$size'";
        
        return $this->_query($sql);
    }
    
    /**
     * Remove item from cart
     */
    public function removeCartItem($userId, $productId, $size)
    {
        $userId = intval($userId);
        $productId = intval($productId);
        $size = mysqli_real_escape_string($this->connect, $size);
        
        $sql = "DELETE FROM " . self::TABLE . " 
                WHERE id_User = $userId 
                AND id_Product = $productId 
                AND size = '$size'";
        
        return $this->_query($sql);
    }
    
    /**
     * Clear user's cart
     */
    public function clearCart($userId)
    {
        $userId = intval($userId);
        
        $sql = "DELETE FROM " . self::TABLE . " 
                WHERE id_User = $userId";
        
        return $this->_query($sql);
    }
}
?>