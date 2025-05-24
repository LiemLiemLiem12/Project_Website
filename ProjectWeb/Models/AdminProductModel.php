<?php
class AdminProductModel {
    public $conn;
    
    public function __construct() {
        // Kết nối database
        $this->conn = mysqli_connect('localhost', 'root', '', 'fashion_database');
        mysqli_set_charset($this->conn, "utf8");
        
        if (mysqli_connect_errno()) {
            die("Kết nối database thất bại: " . mysqli_connect_error());
        }
    }
    

    public function getTrashItems() {
        $sql = "SELECT p.*, c.name as category_name 
                FROM product p 
                LEFT JOIN category c ON p.id_Category = c.id_Category 
                WHERE p.hide = 1";
        $result = $this->conn->query($sql);
        
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    public function restoreProduct($id) {
        $id = $this->conn->real_escape_string($id);
        $sql = "UPDATE product SET hide = 0 WHERE id_product = '$id'";
        return $this->conn->query($sql);
    }

    // Thêm hàm mới để lọc theo danh mục
    public function filterProductsByCategory($categoryId) {
        $query = "SELECT p.*, c.name as category_name 
                FROM product p 
                LEFT JOIN category c ON p.id_Category = c.id_Category 
                WHERE p.hide = 0";
                
        // Thêm điều kiện lọc theo danh mục nếu có
        if (!empty($categoryId)) {
            $categoryId = mysqli_real_escape_string($this->conn, $categoryId);
            $query .= " AND p.id_Category = '$categoryId'";
        }
        
        $query .= " ORDER BY p.created_at DESC";
        
        $result = $this->conn->query($query);
        $productList = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Đảm bảo có trường product_name để tương thích với code hiện tại
                if (!isset($row['product_name']) && isset($row['name'])) {
                    $row['product_name'] = $row['name'];
                }
                $productList[] = $row;
            }
        }
        
        return $productList;
    }
    
}