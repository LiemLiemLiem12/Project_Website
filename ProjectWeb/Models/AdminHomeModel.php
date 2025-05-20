<?php
class AdminHomeModel {
    private $conn;

    public function __construct() {
        try {
            // Kết nối đến CSDL
            $this->conn = new mysqli('localhost', 'root', '', 'fashion_database');
            
            if ($this->conn->connect_error) {
                throw new Exception("Kết nối CSDL thất bại: " . $this->conn->connect_error);
            }
            
            $this->conn->set_charset("utf8mb4");
            
        } catch (Exception $e) {
            error_log("Database Error: " . $e->getMessage());
            exit("Lỗi kết nối đến cơ sở dữ liệu, vui lòng thử lại sau.");
        }
    }
    
    // PHƯƠNG THỨC QUẢN LÝ SECTIONS
    
    public function getAllSections() {
        $sql = "SELECT * FROM home_sections ORDER BY `order` ASC";
        $result = $this->conn->query($sql);
        $sections = [];
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sections[] = $row;
            }
        }
        
        return $sections;
    }
    
    public function getSectionById($id) {
        $id = $this->conn->real_escape_string($id);
        $sql = "SELECT * FROM home_sections WHERE id = '$id'";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public function createSection($data) {
        $title = $this->conn->real_escape_string($data['title']);
        $sectionType = $this->conn->real_escape_string($data['section_type']);
        $displayStyle = $this->conn->real_escape_string($data['display_style']);
        $productCount = (int)$data['product_count'];
        $hide = isset($data['hide']) ? (int)$data['hide'] : 0;
        $link = isset($data['link']) ? $this->conn->real_escape_string($data['link']) : '';
        $meta = isset($data['meta']) ? $this->conn->real_escape_string($data['meta']) : '';
        
        // Lấy vị trí cao nhất hiện tại + 1
        $orderQuery = "SELECT MAX(`order`) as max_order FROM home_sections";
        $orderResult = $this->conn->query($orderQuery);
        $orderRow = $orderResult->fetch_assoc();
        $orderPosition = (int)($orderRow['max_order'] ?? 0) + 1;
        
        $sql = "INSERT INTO home_sections (title, section_type, display_style, product_count, `order`, hide, link, meta) 
                VALUES ('$title', '$sectionType', '$displayStyle', $productCount, $orderPosition, $hide, '$link', '$meta')";
        
        // Ghi log lỗi SQL
        if (!$this->conn->query($sql)) {
            error_log("SQL Error: " . $this->conn->error . " with query: " . $sql);
            return false;
        }
        
        return $this->conn->insert_id;
    }
    
    public function updateSection($id, $data) {
        $id = $this->conn->real_escape_string($id);
        $title = $this->conn->real_escape_string($data['title']);
        $sectionType = $this->conn->real_escape_string($data['section_type']);
        $displayStyle = $this->conn->real_escape_string($data['display_style']);
        $productCount = (int)$data['product_count'];
        $hide = isset($data['hide']) ? (int)$data['hide'] : 0;
        $link = isset($data['link']) ? $this->conn->real_escape_string($data['link']) : '';
        $meta = isset($data['meta']) ? $this->conn->real_escape_string($data['meta']) : '';
        
        $sql = "UPDATE home_sections SET 
                title = '$title', 
                section_type = '$sectionType', 
                display_style = '$displayStyle', 
                product_count = $productCount, 
                hide = $hide,
                link = '$link',
                meta = '$meta'
                WHERE id = '$id'";
        
        if (!$this->conn->query($sql)) {
            error_log("SQL Error: " . $this->conn->error . " with query: " . $sql);
            return false;
        }
        
        return true;
    }
    
    public function deleteSection($id) {
        $id = $this->conn->real_escape_string($id);
        
        // Xóa các items liên quan trước
        $this->conn->query("DELETE FROM home_section_items WHERE section_id = '$id'");
        
        // Sau đó xóa section
        $sql = "DELETE FROM home_sections WHERE id = '$id'";
        return $this->conn->query($sql);
    }
    
    public function updateSectionOrder($positions) {
        $success = true;
        
        foreach ($positions as $id => $position) {
            $id = $this->conn->real_escape_string($id);
            $position = (int)$position;
            
            $sql = "UPDATE home_sections SET `order` = $position WHERE id = '$id'";
            if (!$this->conn->query($sql)) {
                $success = false;
                error_log("SQL Error: " . $this->conn->error . " with query: " . $sql);
            }
        }
        
        return $success;
    }
    
    // PHƯƠNG THỨC QUẢN LÝ SECTION ITEMS
    
    public function getSectionItems($sectionId) {
        $sectionId = $this->conn->real_escape_string($sectionId);
        $sql = "SELECT * FROM home_section_items WHERE section_id = '$sectionId' ORDER BY `order` ASC";
        $result = $this->conn->query($sql);
        $items = [];
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
        }
        
        return $items;
    }
    
    public function getItemDetails($sectionItems) {
        $details = [];
        foreach ($sectionItems as $item) {
            if ($item['item_type'] === 'product') {
                $product = $this->getProductById($item['item_id']);
                if ($product) {
                    if (!empty($product['main_image'])) {
                        $product['main_image'] = 'upload/img/All-Product/' . $product['main_image'];
                    }
                    $details['product_' . $item['item_id']] = $product;
                }
            } else if ($item['item_type'] === 'category') {
                $category = $this->getCategoryById($item['item_id']);
                if ($category) {
                    $details['category_' . $item['item_id']] = $category;
                }
            }
        }
        return $details;
    }
    
    public function addSectionItem($sectionId, $itemId, $itemType) {
        $sectionId = $this->conn->real_escape_string($sectionId);
        $itemId = $this->conn->real_escape_string($itemId);
        $itemType = $this->conn->real_escape_string($itemType);
        
        // Kiểm tra xem item đã tồn tại trong section chưa
        $checkSql = "SELECT id FROM home_section_items WHERE section_id = '$sectionId' AND item_id = '$itemId' AND item_type = '$itemType'";
        $checkResult = $this->conn->query($checkSql);
        
        if ($checkResult->num_rows > 0) {
            return true; // Item đã tồn tại, không cần thêm lại
        }
        
        // Lấy vị trí cao nhất hiện tại + 1
        $orderQuery = "SELECT MAX(`order`) as max_order FROM home_section_items WHERE section_id = '$sectionId'";
        $orderResult = $this->conn->query($orderQuery);
        $orderRow = $orderResult->fetch_assoc();
        $orderPosition = (int)($orderRow['max_order'] ?? 0) + 1;
        
        $sql = "INSERT INTO home_section_items (section_id, item_id, item_type, `order`, hide, link, meta) 
                VALUES ('$sectionId', '$itemId', '$itemType', $orderPosition, 0, '', '')";
        
        if (!$this->conn->query($sql)) {
            error_log("SQL Error: " . $this->conn->error . " with query: " . $sql);
            return false;
        }
        
        return true;
    }
    
    public function deleteSectionItem($itemId) {
        $itemId = $this->conn->real_escape_string($itemId);
        $sql = "DELETE FROM home_section_items WHERE id = '$itemId'";
        return $this->conn->query($sql);
    }
    
    public function updateSectionItemOrder($positions) {
        $success = true;
        
        foreach ($positions as $id => $position) {
            $id = $this->conn->real_escape_string($id);
            $position = (int)$position;
            
            $sql = "UPDATE home_section_items SET `order` = $position WHERE id = '$id'";
            if (!$this->conn->query($sql)) {
                $success = false;
                error_log("SQL Error: " . $this->conn->error . " with query: " . $sql);
            }
        }
        
        return $success;
    }
    
    // PHƯƠNG THỨC QUẢN LÝ BANNERS
    
    public function getAllBanners() {
        $sql = "SELECT * FROM banners ORDER BY `order` ASC";
        $result = $this->conn->query($sql);
        $banners = [];
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Add the path prefix to the image filename for display
                if (!empty($row['image_path'])) {
                    $row['image_path'] = 'upload/img/Home/' . $row['image_path'];
                }
                $banners[] = $row;
            }
        }
        
        return $banners;
    }
    
    public function getBannerById($id) {
        $id = $this->conn->real_escape_string($id);
        $sql = "SELECT * FROM banners WHERE id = '$id'";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows > 0) {
            $banner = $result->fetch_assoc();
            // Add the path prefix to the image filename for display
            if (!empty($banner['image_path'])) {
                $banner['image_path'] = 'upload/img/Home/' . $banner['image_path'];
            }
            return $banner;
        }
        
        return null;
    }
    
    public function createBanner($data, $imagePath) {
        $title = $this->conn->real_escape_string($data['title']);
        $link = $this->conn->real_escape_string($data['link'] ?? '#');
        $startDate = $this->conn->real_escape_string($data['start_date']);
        $endDate = $this->conn->real_escape_string($data['end_date']);
        $hide = isset($data['hide']) ? (int)$data['hide'] : 0;
        $meta = isset($data['meta']) ? $this->conn->real_escape_string($data['meta']) : '';
        $imagePath = $this->conn->real_escape_string($imagePath);
        
        // Lấy vị trí cao nhất hiện tại + 1
        $orderQuery = "SELECT MAX(`order`) as max_order FROM banners";
        $orderResult = $this->conn->query($orderQuery);
        $orderRow = $orderResult->fetch_assoc();
        $orderPosition = (int)($orderRow['max_order'] ?? 0) + 1;
        
        $sql = "INSERT INTO banners 
                (title, image_path, link, start_date, end_date, `order`, hide, meta) 
                VALUES 
                ('$title', '$imagePath', '$link', '$startDate', '$endDate', $orderPosition, $hide, '$meta')";
        
        if (!$this->conn->query($sql)) {
            error_log("SQL Error: " . $this->conn->error . " with query: " . $sql);
            return false;
        }
        
        return $this->conn->insert_id;
    }
    
    public function updateBanner($id, $data, $imagePath = null) {
        $id = $this->conn->real_escape_string($id);
        $title = $this->conn->real_escape_string($data['title']);
        $link = $this->conn->real_escape_string($data['link'] ?? '#');
        $startDate = $this->conn->real_escape_string($data['start_date']);
        $endDate = $this->conn->real_escape_string($data['end_date']);
        $hide = isset($data['hide']) ? (int)$data['hide'] : 0;
        $meta = isset($data['meta']) ? $this->conn->real_escape_string($data['meta']) : '';
        
        $imageSql = '';
        if ($imagePath) {
            $imagePath = $this->conn->real_escape_string($imagePath);
            $imageSql = ", image_path = '$imagePath'";
        }
        
        $sql = "UPDATE banners SET 
                title = '$title', 
                link = '$link', 
                start_date = '$startDate', 
                end_date = '$endDate', 
                hide = $hide,
                meta = '$meta'
                $imageSql
                WHERE id = '$id'";
        
        if (!$this->conn->query($sql)) {
            error_log("SQL Error: " . $this->conn->error . " with query: " . $sql);
            return false;
        }
        
        return true;
    }
    
    public function deleteBanner($id) {
        $id = $this->conn->real_escape_string($id);
        
        // Lấy đường dẫn ảnh để xóa file
        $sql = "SELECT image_path FROM banners WHERE id = '$id'";
        $result = $this->conn->query($sql);
        $banner = $result->fetch_assoc();
        $imagePath = null;
        
        if ($banner && !empty($banner['image_path'])) {
            $imagePath = 'upload/img/Home/' . $banner['image_path'];
        }
        
        // Xóa banner từ CSDL
        $deleteSql = "DELETE FROM banners WHERE id = '$id'";
        $success = $this->conn->query($deleteSql);
        
        // Trả về đường dẫn ảnh để controller xóa file
        if ($success && $imagePath) {
            return $imagePath;
        }
        
        return false;
    }
    
    public function updateBannerOrder($positions) {
        $success = true;
        
        foreach ($positions as $id => $position) {
            $id = $this->conn->real_escape_string($id);
            $position = (int)$position;
            
            $sql = "UPDATE banners SET `order` = $position WHERE id = '$id'";
            if (!$this->conn->query($sql)) {
                $success = false;
                error_log("SQL Error: " . $this->conn->error . " with query: " . $sql);
            }
        }
        
        return $success;
    }
    
    // PHƯƠNG THỨC HỖ TRỢ
    
    public function getAllCategories() {
        // Điều chỉnh theo cấu trúc bảng category hiện có
        $sql = "SELECT id_Category as id, name, image FROM category WHERE hide = 0 OR hide IS NULL ORDER BY `order` ASC";
        $result = $this->conn->query($sql);
        $categories = [];
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        
        return $categories;
    }
    
    public function getAllProducts() {
        // Điều chỉnh theo cấu trúc bảng product hiện có
        $sql = "SELECT id_product as id, name, current_price, main_image, discount_percent 
                FROM product WHERE hide = 0 OR hide IS NULL ORDER BY name ASC";
        $result = $this->conn->query($sql);
        $products = [];
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        
        return $products;
    }
    
    public function getActiveSectionsForHomepage() {
        $sql = "SELECT * FROM home_sections WHERE hide = 0 ORDER BY `order` ASC";
        $result = $this->conn->query($sql);
        $sections = [];
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sections[] = $row;
            }
        }
        
        return $sections;
    }
    
    public function getActiveBannersForHomepage() {
        $today = date('Y-m-d');
        $sql = "SELECT * FROM banners 
                WHERE hide = 0 
                AND start_date <= '$today' 
                AND end_date >= '$today' 
                ORDER BY `order` ASC";
        $result = $this->conn->query($sql);
        $banners = [];
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $banners[] = $row;
            }
        }
        
        return $banners;
    }
    
    // PHƯƠNG THỨC QUẢN LÝ CHÍNH SÁCH (POLICIES)
    
    /**
     * Lấy tất cả chính sách
     * @return array Danh sách chính sách
     */
    public function getAllPolicies() {
        $sql = "SELECT * FROM footer_policies ORDER BY `order` ASC";
        $result = $this->conn->query($sql);
        $policies = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (!empty($row['image'])) {
                    $row['image'] = 'upload/img/Footer/' . $row['image'];
                }
                $policies[] = $row;
            }
        }
        return $policies;
    }
    
    /**
     * Lấy thông tin chi tiết chính sách theo ID
     * @param int $id ID của chính sách
     * @return array|null Thông tin chính sách hoặc null nếu không tìm thấy
     */
    public function getPolicyById($id) {
        $id = $this->conn->real_escape_string($id);
        $sql = "SELECT * FROM footer_policies WHERE id = '$id'";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (!empty($row['image'])) {
                $row['image'] = 'upload/img/Footer/' . $row['image'];
            }
            return $row;
        }
        return null;
    }
    
    /**
     * Tạo mới chính sách
     * @param array $data Dữ liệu chính sách
     * @param string|null $imagePath Đường dẫn ảnh (nếu có)
     * @return int|false ID của chính sách vừa tạo hoặc false nếu thất bại
     */
    public function createPolicy($data, $imagePath = null) {
        $title = $this->conn->real_escape_string($data['title']);
        $hide = $data['status'] ? 0 : 1;
        $meta = isset($data['meta']) ? $this->conn->real_escape_string($data['meta']) : '';
        
        // Xử lý đường dẫn ảnh
        $imagePathSql = 'NULL';
        if ($imagePath) {
            $imagePath = $this->conn->real_escape_string($imagePath);
            $imagePathSql = "'$imagePath'";
        }
        
        // Lấy vị trí cao nhất hiện tại + 1
        $orderQuery = "SELECT MAX(`order`) as max_order FROM footer_policies";
        $orderResult = $this->conn->query($orderQuery);
        $orderRow = $orderResult->fetch_assoc();
        $orderPosition = (int)($orderRow['max_order'] ?? 0) + 1;
        
        // Link sẽ được cập nhật sau khi có ID
        $sql = "INSERT INTO footer_policies 
                (title, image, link, meta, `order`, hide) 
                VALUES 
                ('$title', $imagePathSql, '#', '$meta', $orderPosition, $hide)";
        
        if (!$this->conn->query($sql)) {
            error_log("SQL Error: " . $this->conn->error . " with query: " . $sql);
            return false;
        }
        
        // Lấy ID vừa tạo
        $newId = $this->conn->insert_id;
        
        // Cập nhật link tự động
        $autoLink = "/Project_Website/ProjectWeb/index.php?controller=policy&action=show&id=" . $newId;
        $updateSql = "UPDATE footer_policies SET link = '$autoLink' WHERE id = $newId";
        $this->conn->query($updateSql);
        
        return $newId;
    }
    
    /**
     * Cập nhật thông tin chính sách
     * @param int $id ID của chính sách
     * @param array $data Dữ liệu chính sách
     * @param string|null $imagePath Đường dẫn ảnh mới (nếu có)
     * @return bool Kết quả cập nhật
     */
    public function updatePolicy($id, $data, $imagePath = null) {
        $id = $this->conn->real_escape_string($id);
        $title = $this->conn->real_escape_string($data['title']);
        $hide = $data['status'] ? 0 : 1;
        $meta = isset($data['meta']) ? $this->conn->real_escape_string($data['meta']) : '';
        
        // Tạo link tự động
        $link = "/Project_Website/ProjectWeb/index.php?controller=policy&action=show&id=" . $id;
        
        $imageSql = '';
        if ($imagePath !== null) {
            $imagePath = $this->conn->real_escape_string($imagePath);
            $imageSql = ", image = '$imagePath'";
        }
        
        $sql = "UPDATE footer_policies SET 
                title = '$title', 
                link = '$link', 
                hide = $hide,
                meta = '$meta'
                $imageSql
                WHERE id = '$id'";
        
        if (!$this->conn->query($sql)) {
            error_log("SQL Error: " . $this->conn->error . " with query: " . $sql);
            return false;
        }
        
        return true;
    }
    
    /**
     * Xóa chính sách
     * @param int $id ID của chính sách
     * @return string|false Đường dẫn ảnh của chính sách (để xóa file) hoặc false nếu không thành công
     */
    public function deletePolicy($id) {
        $id = $this->conn->real_escape_string($id);
        
        // Lấy đường dẫn ảnh để xóa file
        $sql = "SELECT image FROM footer_policies WHERE id = '$id'";
        $result = $this->conn->query($sql);
        $policy = $result->fetch_assoc();
        
        // Xóa policy từ CSDL
        $deleteSql = "DELETE FROM footer_policies WHERE id = '$id'";
        $success = $this->conn->query($deleteSql);
        
        // Trả về đường dẫn ảnh để controller xóa file
        if ($success && $policy && $policy['image']) {
            return $policy['image'];
        }
        
        return $success ? true : false;
    }
    
    /**
     * Cập nhật thứ tự hiển thị của các chính sách
     * @param array $positions Mảng vị trí mới (key: ID chính sách, value: thứ tự mới)
     * @return bool Kết quả cập nhật
     */
    public function updatePoliciesOrder($positions) {
        $success = true;
        
        foreach ($positions as $id => $position) {
            $id = $this->conn->real_escape_string($id);
            $position = (int)$position;
            
            $sql = "UPDATE footer_policies SET `order` = $position WHERE id = '$id'";
            if (!$this->conn->query($sql)) {
                $success = false;
                error_log("SQL Error: " . $this->conn->error . " with query: " . $sql);
            }
        }
        
        return $success;
    }

    // QUẢN LÝ SOCIAL MEDIA
    
    public function getAllSocialMedia() {
        $sql = "SELECT * FROM footer_social_media ORDER BY `order` ASC";
        $result = $this->conn->query($sql);
        
        $socialMedias = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $socialMedias[] = $row;
            }
        }
        
        return $socialMedias;
    }
    
    public function getSocialMediaById($id) {
        $id = $this->conn->real_escape_string($id);
        $sql = "SELECT * FROM footer_social_media WHERE id = '$id'";
        $result = $this->conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public function createSocialMedia($data) {
        $title = $this->conn->real_escape_string($data['title']);
        $icon = $this->conn->real_escape_string($data['icon']);
        $link = $this->conn->real_escape_string($data['link'] ?? '#');
        $hide = $data['status'] ? 0 : 1;
        $meta = isset($data['meta']) ? $this->conn->real_escape_string($data['meta']) : '';
        
        // Lấy vị trí cao nhất hiện tại + 1
        $orderQuery = "SELECT MAX(`order`) as max_order FROM footer_social_media";
        $orderResult = $this->conn->query($orderQuery);
        $orderRow = $orderResult->fetch_assoc();
        $orderPosition = (int)($orderRow['max_order'] ?? 0) + 1;
        
        $sql = "INSERT INTO footer_social_media 
                (title, icon, link, meta, `order`, hide) 
                VALUES 
                ('$title', '$icon', '$link', '$meta', $orderPosition, $hide)";
        
        if (!$this->conn->query($sql)) {
            error_log("SQL Error: " . $this->conn->error . " with query: " . $sql);
            return false;
        }
        
        return $this->conn->insert_id;
    }
    
    public function updateSocialMedia($id, $data) {
        $id = $this->conn->real_escape_string($id);
        $title = $this->conn->real_escape_string($data['title']);
        $icon = $this->conn->real_escape_string($data['icon']);
        $link = $this->conn->real_escape_string($data['link'] ?? '#');
        $hide = $data['status'] ? 0 : 1;
        $meta = isset($data['meta']) ? $this->conn->real_escape_string($data['meta']) : '';
        
        $sql = "UPDATE footer_social_media 
                SET title = '$title',
                    icon = '$icon',
                    link = '$link',
                    meta = '$meta',
                    hide = $hide
                WHERE id = '$id'";
        
        if (!$this->conn->query($sql)) {
            error_log("SQL Error: " . $this->conn->error . " with query: " . $sql);
            return false;
        }
        
        return true;
    }
    
    public function deleteSocialMedia($id) {
        $id = $this->conn->real_escape_string($id);
        
        $sql = "DELETE FROM footer_social_media WHERE id = '$id'";
        
        if (!$this->conn->query($sql)) {
            error_log("SQL Error: " . $this->conn->error . " with query: " . $sql);
            return false;
        }
        
        return true;
    }
    
    public function updateSocialMediaOrder($positions) {
        $this->conn->begin_transaction();
        
        try {
            foreach ($positions as $position) {
                $id = $this->conn->real_escape_string($position['id']);
                $order = (int)$position['order'];
                
                $sql = "UPDATE footer_social_media SET `order` = $order WHERE id = '$id'";
                if (!$this->conn->query($sql)) {
                    throw new Exception("SQL Error: " . $this->conn->error);
                }
            }
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Error updating social media order: " . $e->getMessage());
            return false;
        }
    }

    // QUẢN LÝ PHƯƠNG THỨC THANH TOÁN
    
    public function getAllPaymentMethods() {
        $sql = "SELECT * FROM footer_payment_methods ORDER BY `order` ASC";
        $result = $this->conn->query($sql);
        $paymentMethods = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (!empty($row['image'])) {
                    $row['image'] = 'upload/img/Footer/' . $row['image'];
                }
                $paymentMethods[] = $row;
            }
        }
        return $paymentMethods;
    }
    
    public function getPaymentMethodById($id) {
        $id = $this->conn->real_escape_string($id);
        $sql = "SELECT * FROM footer_payment_methods WHERE id = '$id'";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (!empty($row['image'])) {
                $row['image'] = 'upload/img/Footer/' . $row['image'];
            }
            return $row;
        }
        return null;
    }
    
    public function createPaymentMethod($data, $imagePath) {
        $title = $this->conn->real_escape_string($data['title']);
        $link = $this->conn->real_escape_string($data['link'] ?? '#');
        $hide = $data['status'] ? 0 : 1;
        $meta = isset($data['meta']) ? $this->conn->real_escape_string($data['meta']) : '';
        $image = $this->conn->real_escape_string($imagePath);
        
        // Lấy vị trí cao nhất hiện tại + 1
        $orderQuery = "SELECT MAX(`order`) as max_order FROM footer_payment_methods";
        $orderResult = $this->conn->query($orderQuery);
        $orderRow = $orderResult->fetch_assoc();
        $orderPosition = (int)($orderRow['max_order'] ?? 0) + 1;
        
        $sql = "INSERT INTO footer_payment_methods 
                (title, image, link, meta, `order`, hide) 
                VALUES 
                ('$title', '$image', '$link', '$meta', $orderPosition, $hide)";
        
        if (!$this->conn->query($sql)) {
            error_log("SQL Error: " . $this->conn->error . " with query: " . $sql);
            return false;
        }
        
        return $this->conn->insert_id;
    }
    
    public function updatePaymentMethod($id, $data, $imagePath = null) {
        $id = $this->conn->real_escape_string($id);
        $title = $this->conn->real_escape_string($data['title']);
        $link = $this->conn->real_escape_string($data['link'] ?? '#');
        $hide = $data['status'] ? 0 : 1;
        $meta = isset($data['meta']) ? $this->conn->real_escape_string($data['meta']) : '';
        
        $sql = "UPDATE footer_payment_methods 
                SET title = '$title',
                    link = '$link',
                    meta = '$meta',
                    hide = $hide";
        
        if ($imagePath) {
            $image = $this->conn->real_escape_string($imagePath);
            $sql .= ", image = '$image'";
        }
        
        $sql .= " WHERE id = '$id'";
        
        if (!$this->conn->query($sql)) {
            error_log("SQL Error: " . $this->conn->error . " with query: " . $sql);
            return false;
        }
        
        return true;
    }
    
    public function deletePaymentMethod($id) {
        $id = $this->conn->real_escape_string($id);
        
        $sql = "DELETE FROM footer_payment_methods WHERE id = '$id'";
        
        if (!$this->conn->query($sql)) {
            error_log("SQL Error: " . $this->conn->error . " with query: " . $sql);
            return false;
        }
        
        return true;
    }
    
    public function updatePaymentMethodsOrder($positions) {
        $this->conn->begin_transaction();
        
        try {
            foreach ($positions as $position) {
                $id = $this->conn->real_escape_string($position['id']);
                $order = (int)$position['order'];
                
                $sql = "UPDATE footer_payment_methods SET `order` = $order WHERE id = '$id'";
                if (!$this->conn->query($sql)) {
                    throw new Exception("SQL Error: " . $this->conn->error);
                }
            }
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Error updating payment methods order: " . $e->getMessage());
            return false;
        }
    }

    public function getProductById($id) {
        $id = $this->conn->real_escape_string($id);
        $sql = "SELECT id_product, name, main_image, current_price, original_price, discount_percent FROM product WHERE id_product = '$id' LIMIT 1";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
}