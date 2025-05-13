
<?php
class OrderModel extends BaseModel
{
       const TABLE = "order";
    public function getByMonth($month)
    {
        return $this->getScalar("SELECT COUNT(*) 
                                        FROM `ORDER` 
                                        WHERE MONTH(CREATED_AT) = " . $month . "
                                        AND YEAR(CREATED_AT) = YEAR(CURDATE())");
    }

    public function getRevenue($month)
    {
        return $this->getScalar("
            Select sum(total_amount) as 'Tong'
            From `order`
            WHERE MONTH(CREATED_AT) = " . $month . " AND YEAR(CREATED_AT) = YEAR(CURDATE()) AND STATUS = 'completed'
        ");
    }

    public function getAll()
    {
        return $this->getByQuery("
            SELECT id_Order, name, CONCAT(REPLACE(FORMAT(total_amount, 0), ',', '.'), 'đ') as total_amount, status, `order`.created_at 
            FROM `order` join `user` on `order`.id_User = user.id_User
            ORDER BY created_at desc LIMIT 5
        ");
    }
    
    
    /**
     * Create a new order
     */
    public function createOrder($orderData)
    {
        // Insert order data
        $this->create(self::TABLE, $orderData);
        
        // Get the last inserted ID
        $sql = "SELECT LAST_INSERT_ID() as id";
        $query = $this->_query($sql);
        $result = mysqli_fetch_assoc($query);
        
        return $result['id'] ?? null;
    }
    
    /**
     * Create order detail
     */
    public function createOrderDetail($detailData)
    {
        return $this->create('order_detail', $detailData);
    }
    
    /**
     * Get order with details
     */
    public function getOrderWithDetails($orderId)
    {
        // Get order information
        $sql = "SELECT * FROM " . self::TABLE . " WHERE id_Order = {$orderId} LIMIT 1";
        $query = $this->_query($sql);
        $order = mysqli_fetch_assoc($query);
        
        if (!$order) {
            return null;
        }
        
        // Get order details
        $sql = "SELECT od.*, p.name, p.main_image 
                FROM order_detail od 
                JOIN product p ON od.id_Product = p.id_product 
                WHERE od.id_Order = {$orderId}";
        $query = $this->_query($sql);
        
        $orderDetails = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $orderDetails[] = $row;
        }
        
        $order['details'] = $orderDetails;
        
        return $order;
    }
    
    /**
     * Update order status
     */
    public function updateStatus($orderId, $status)
    {
        $status = $this->escapeValue($status);
        $sql = "UPDATE " . self::TABLE . " SET status = '{$status}' WHERE id_Order = {$orderId}";
        return $this->_query($sql);
    }
    
    /**
     * Get user orders
     */
    public function getUserOrders($userId)
    {
        $sql = "SELECT * FROM " . self::TABLE . " WHERE id_User = {$userId} ORDER BY created_at DESC";
        $query = $this->_query($sql);
        
        $orders = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $orders[] = $row;
        }
        
        return $orders;
    }
    
    /**
     * Escape string value to prevent SQL injection
     */
    private function escapeValue($value)
    {
        return mysqli_real_escape_string($this->connect, $value);
    }
}