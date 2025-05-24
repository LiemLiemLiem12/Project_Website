
<?php
class OrderModel extends BaseModel
{
       const TABLE = "order"; // Thêm dấu backticks
 

     
    
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
        // Chuẩn bị dữ liệu để insert vào database
        $columns = implode(',', array_keys($orderData));
        $values = implode(',', array_map(function($value) {
            return is_null($value) ? "NULL" : "'" . mysqli_real_escape_string($this->connect, $value) . "'";
        }, array_values($orderData)));
        
        // Tạo câu lệnh SQL insert vào bảng
        $sql = "INSERT INTO `" . self::TABLE . "` ({$columns}) VALUES ({$values})";
        
        // Thực thi câu lệnh SQL
        $this->_query($sql);
        
        // Lấy ID vừa insert
        $lastId = mysqli_insert_id($this->connect);
        
        return $lastId;
    }
    
    
    /**
     * Create order detail
     */
public function createOrderDetail($detailData)
{
    // Kiểm tra nếu có trường size, thêm nó vào điều kiện khóa chính
    $primaryKeyCheck = "id_Order = " . (int)$detailData['id_Order'] . " AND id_Product = " . (int)$detailData['id_Product'];
    
    // Nếu có size, thêm nó vào điều kiện
    if (isset($detailData['size'])) {
        $size = mysqli_real_escape_string($this->connect, $detailData['size']);
        $primaryKeyCheck .= " AND size = '$size'";
    }
    
    // Kiểm tra xem bản ghi đã tồn tại chưa
    $checkSql = "SELECT COUNT(*) FROM order_detail WHERE $primaryKeyCheck";
    $result = $this->_query($checkSql);
    $row = mysqli_fetch_row($result);
    $exists = (int)$row[0] > 0;
    
    if ($exists) {
        // Nếu đã tồn tại, cập nhật số lượng và sub_total
        $updateSets = [];
        
        if (isset($detailData['quantity'])) {
            $updateSets[] = "quantity = quantity + " . (int)$detailData['quantity'];
        }
        
        if (isset($detailData['sub_total'])) {
            $updateSets[] = "sub_total = sub_total + " . (float)$detailData['sub_total'];
        }
        
        if (!empty($updateSets)) {
            $updateSql = "UPDATE order_detail SET " . implode(", ", $updateSets) . " WHERE $primaryKeyCheck";
            return $this->_query($updateSql);
        }
        
        return true;
    } else {
        // Nếu chưa tồn tại, thêm mới
        $columns = implode(',', array_keys($detailData));
        $values = implode(',', array_map(function($value) {
            return is_null($value) ? "NULL" : "'" . mysqli_real_escape_string($this->connect, $value) . "'";
        }, array_values($detailData)));
        
        $sql = "INSERT INTO order_detail ({$columns}) VALUES ({$values})";
        return $this->_query($sql);
    }
}
    
 
    /**
     * Get order with details
     */
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



     public function getOrderWithDetails($orderId)
    {
        $orderId = (int)$orderId;
        
        // Get order information
        $sql = "SELECT o.*, u.name as customer_name, u.email, u.phone 
                FROM `" . self::TABLE . "` o 
                LEFT JOIN user u ON o.id_User = u.id_User 
                WHERE o.id_Order = {$orderId} LIMIT 1";
        $query = $this->_query($sql);
        $order = mysqli_fetch_assoc($query);
        
        if (!$order) {
            return null;
        }
        
        // Get order details
        $sql = "SELECT od.*, p.name, p.main_image, p.current_price 
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
        $sql = "SELECT * FROM `order` WHERE id_User = {$userId} ORDER BY created_at DESC";
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


    public function getAll_AdminOrder()
    {
        return $this->getByQuery("
            SELECT id_Order, u.name, o.created_at, CONCAT(REPLACE(FORMAT(total_amount, 0), ',', '.'), 'đ') as total_amount, payment_by, status, o.hide, note
            FROM `order` o join user u on o.id_User = u.id_User
            WHERE o.hide = 0
        ");
    }

    public function getAll_AdminOrder_newest()
    {
        return $this->getByQuery("
            SELECT id_Order, u.name, o.created_at, CONCAT(REPLACE(FORMAT(total_amount, 0), ',', '.'), 'đ') as total_amount, payment_by, status, o.hide, note
            FROM `order` o join user u on o.id_User = u.id_User
            WHERE o.hide = 0
            ORDER BY o.created_at DESC
        ");
    }

    public function getAll_AdminOrder_oldest()
    {
        return $this->getByQuery("
            SELECT id_Order, u.name, o.created_at, CONCAT(REPLACE(FORMAT(total_amount, 0), ',', '.'), 'đ') as total_amount, payment_by, status, o.hide, note
            FROM `order` o join user u on o.id_User = u.id_User
            WHERE o.hide = 0
            ORDER BY o.created_at ASC
        ");
    }

    public function getAll_AdminOrder_total_high()
    {
        return $this->getByQuery("
            SELECT id_Order, u.name, o.created_at, CONCAT(REPLACE(FORMAT(total_amount, 0), ',', '.'), 'đ') as total_amount, payment_by, status, o.hide, note
            FROM `order` o join user u on o.id_User = u.id_User
            WHERE o.hide = 0
            ORDER BY o.total_amount DESC
        ");
    }

    public function getAll_AdminOrder_total_low()
    {
        return $this->getByQuery("
            SELECT id_Order, u.name, o.created_at, CONCAT(REPLACE(FORMAT(total_amount, 0), ',', '.'), 'đ') as total_amount, payment_by, status, o.hide, note
            FROM `order` o join user u on o.id_User = u.id_User
            WHERE o.hide = 0
            ORDER BY o.total_amount ASC
        ");
    }

    public function updateOrder($id, $data)
    {
        return $this->updateForOrder(self::TABLE, $id, $data);
    }
    /**
 * Get categories for menu display
 */
public function getCategoriesForMenu()
{
    $sql = "SELECT id_Category, name, link, meta FROM " . self::TABLE . " 
            WHERE hide = 0 OR hide IS NULL
            ORDER BY `order` ASC";
    
    return $this->getByQuery($sql);
}

}
?>
