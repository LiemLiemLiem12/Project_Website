<?php
class SearchModel extends BaseModel {
    private $conn;
    private $table = 'settings';
    public function __construct() {
        // Kết nối database
        $this->conn = mysqli_connect('localhost', 'root', '', 'fashion_database');
        mysqli_set_charset($this->conn, "utf8");
        
        if (mysqli_connect_errno()) {
            die("Kết nối database thất bại: " . mysqli_connect_error());
        }
    }
    
    /**
     * Tìm kiếm sản phẩm theo từ khóa
     * @param string $keyword Từ khóa tìm kiếm
     * @return array Danh sách sản phẩm phù hợp
     */
    public function searchProducts($keyword) {
        // Làm sạch từ khóa để tránh SQL injection
        $keyword = mysqli_real_escape_string($this->conn, $keyword);
        
        // Tạo câu truy vấn tìm kiếm với LIKE để tìm các sản phẩm có tên chứa từ khóa
        $query = "SELECT p.*, c.name as category_name 
                 FROM product p
                 LEFT JOIN category c ON p.id_Category = c.id_Category
                 WHERE p.hide = 0 AND p.name LIKE '%$keyword%'
                 ORDER BY p.created_at DESC";
                 
        $result = mysqli_query($this->conn, $query);
        
        $products = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $products[] = $row;
            }
            mysqli_free_result($result);
        }
        
        return $products;
    }
    
    /**
     * Tìm kiếm nhanh sản phẩm cho gợi ý tức thời
     * @param string $keyword Từ khóa tìm kiếm
     * @param int $limit Số lượng kết quả tối đa
     * @return array Danh sách sản phẩm phù hợp
     */
    public function quickSearchProducts($keyword, $limit = 5) {
        $keyword = mysqli_real_escape_string($this->conn, $keyword);
        
        $query = "SELECT p.id_product, p.name, p.current_price, p.original_price, p.main_image 
                 FROM product p
                 WHERE p.hide = 0 AND p.name LIKE '%$keyword%'
                 ORDER BY p.click_count DESC
                 LIMIT $limit";
                 
        $result = mysqli_query($this->conn, $query);
        
        $products = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $products[] = $row;
            }
            mysqli_free_result($result);
        }
        
        return $products;
    }
}
?>