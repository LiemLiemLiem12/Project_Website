<?php

class ProductDetailModel
{
    private $db;
    
    public function __construct()
    {
        // Direct database connection without inheritance
        $host = 'localhost';
        $username = 'root'; 
        $password = '';
        $database = 'fashion_database'; // Make sure this matches your actual database name
        
        try {
            $this->db = new PDO("mysql:host=$host;dbname=$database", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit;
        }
    }
    
    public function getProductDetailsById($productId)
    {
        $query = "SELECT id_product, name, description, CSDoiTra, CSGiaoHang, 
                         main_image, img2, img3
                  FROM product 
                  WHERE id_product = :productId";
                  
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getProductDescription($productId)
    {
        $query = "SELECT description 
                  FROM product 
                  WHERE id_product = :productId";
                  
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['description'] ?? '';
    }
    
    public function getProductPolicies($productId)
    {
        $query = "SELECT CSDoiTra, CSGiaoHang 
                  FROM product 
                  WHERE id_product = :productId";
                  
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getProductImages($productId)
    {
        $query = "SELECT main_image, img2, img3 
                  FROM product 
                  WHERE id_product = :productId";
                  
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
} 