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


    
}