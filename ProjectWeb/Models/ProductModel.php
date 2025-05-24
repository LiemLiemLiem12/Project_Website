<?php
class ProductModel extends BaseModel
{
  const TABLE = 'product';
  // Lấy tất cả dữ liệu từ bản
  public function getAll($select = ['*'], $limit = 9999, $orderBys = [])
  {
    return $this->all(self::TABLE, $select, $limit, $orderBys);
  }
  // Lấy dựa vào ID
  public function findById($id)
  {
    return $this->find(self::TABLE, $id);
  }
  // Thêm dữ liệu vào bảng
  public function store($data)
  {
    return $this->create(self::TABLE, $data);
  }
  // Cập nhật dữ liệu bảng
  public function updateData($id, $data)
  {
    return $this->update(self::TABLE, $id, $data);
  }
  // Xóa dữ liệu trong bảng
  public function deleteData($id)
  {
    return $this->delete(self::TABLE, $id);
  }
  // public function getByCategoryId($categoryId)
  // {
  //   $sql = "SELECT * FROM " . self::TABLE . " WHERE id_category={$categoryId}";
  //   return $this->getByQuery($sql);
  // }
  // ProductModel.php
public function incrementClickCount($id)
{
    $sql = "UPDATE " . self::TABLE . " SET click_count = click_count + 1 WHERE id_product = {$id}";
    return $this->_query($sql);
}

  /**
   * Tìm kiếm sản phẩm theo tên và tag
   */
  public function searchProducts($query, $limit = null)
  {
    $query = mysqli_real_escape_string($this->connect, $query);
    
    $sql = "SELECT p.*, c.name as category_name 
            FROM " . self::TABLE . " p 
            LEFT JOIN category c ON p.id_Category = c.id_Category 
            WHERE (p.name LIKE '%{$query}%' 
                   OR p.tag LIKE '%{$query}%' 
                   OR p.description LIKE '%{$query}%')
            AND p.hide = 0
            ORDER BY 
                CASE 
                    WHEN p.name LIKE '{$query}%' THEN 1
                    WHEN p.name LIKE '%{$query}%' THEN 2
                    WHEN p.tag LIKE '%{$query}%' THEN 3
                    ELSE 4
                END,
                p.click_count DESC, 
                p.created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    
    return $this->getByQuery($sql);
  }

  /**
   * Lấy gợi ý tìm kiếm
   */
  // public function getSearchSuggestions($query, $limit = 5)
  // {
  //   $query = mysqli_real_escape_string($this->connect, $query);
    
  //   $sql = "SELECT DISTINCT name, id_product, main_image, current_price
  //           FROM " . self::TABLE . " 
  //           WHERE (name LIKE '%{$query}%' OR tag LIKE '%{$query}%')
  //           AND hide = 0
  //           ORDER BY click_count DESC, name ASC 
  //           LIMIT " . (int)$limit;
    
  //   return $this->getByQuery($sql);
  // }

  /**
   * Lấy sản phẩm liên quan theo category
   */
  public function getRelatedProducts($categoryId, $currentProductId, $limit = 8)
  {
    $sql = "SELECT * FROM " . self::TABLE . " 
            WHERE id_Category = {$categoryId} 
            AND id_product != {$currentProductId}
            AND hide = 0
            ORDER BY click_count DESC, created_at DESC 
            LIMIT " . (int)$limit;
    
    return $this->getByQuery($sql);
  }

  /**
   * Lấy sản phẩm theo tag
   */
  public function getProductsByTag($tag, $limit = null)
  {
    $tag = mysqli_real_escape_string($this->connect, $tag);
    
    $sql = "SELECT * FROM " . self::TABLE . " 
            WHERE tag LIKE '%{$tag}%' 
            AND hide = 0
            ORDER BY click_count DESC, created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    
    return $this->getByQuery($sql);
  }

  /**
   * Lấy sản phẩm theo tag với filters
   */
  // public function getFilteredProductsByTag($tag, $filters = [])
  // {
  //   $tag = mysqli_real_escape_string($this->connect, $tag);
    
  //   // Extract filter parameters
  //   $priceMin = $filters['price_min'] ?? 0;
  //   $priceMax = $filters['price_max'] ?? 10000000;
  //   $sizes = $filters['sizes'] ?? [];
  //   $sort = $filters['sort'] ?? 'newest';

  //   // Build SQL query
  //   $sql = "SELECT * FROM " . self::TABLE . " WHERE tag LIKE '%{$tag}%' AND hide = 0";

  //   // Add price range filter
  //   $sql .= " AND current_price >= {$priceMin} AND current_price <= {$priceMax}";

  //   // Add size filter if selected
  //   if (!empty($sizes)) {
  //     $sizeConditions = [];
  //     foreach ($sizes as $size) {
  //       if (in_array($size, ['M', 'L', 'XL'])) {
  //         $sizeConditions[] = "`{$size}` > 0";
  //       }
  //     }
  //     if (!empty($sizeConditions)) {
  //       $sql .= " AND (" . implode(" OR ", $sizeConditions) . ")";
  //     }
  //   }

  //   // Add sorting
  //   switch ($sort) {
  //     case 'price-asc':
  //       $sql .= " ORDER BY current_price ASC";
  //       break;
  //     case 'price-desc':
  //       $sql .= " ORDER BY current_price DESC";
  //       break;
  //     case 'bestseller':
  //       $sql .= " ORDER BY click_count DESC";
  //       break;
  //     case 'newest':
  //     default:
  //       $sql .= " ORDER BY created_at DESC";
  //       break;
  //   }

  //   return $this->getByQuery($sql);
  // }

  /**
   * Lấy tất cả các tag có sẵn
   */
  public function getAllTags()
  {
    $sql = "SELECT DISTINCT tag FROM " . self::TABLE . " 
            WHERE tag IS NOT NULL AND tag != '' AND hide = 0";
    
    $results = $this->getByQuery($sql);
    $allTags = [];
    
    foreach ($results as $result) {
      $tags = explode(',', $result['tag']);
      foreach ($tags as $tag) {
        $tag = trim($tag);
        if (!empty($tag)) {
          $allTags[] = $tag;
        }
      }
    }
    
    return array_unique($allTags);
  }

  /**
   * Lấy tags phổ biến
   */
  public function getPopularTags($limit = 10)
  {
    $sql = "SELECT tag, COUNT(*) as count 
            FROM " . self::TABLE . " 
            WHERE tag IS NOT NULL AND tag != '' AND hide = 0
            GROUP BY tag 
            ORDER BY count DESC, tag ASC 
            LIMIT " . (int)$limit;
    
    return $this->getByQuery($sql);
  }

  public function getTopSaleProduct()
  {
    $sql = "
          Select p.main_image, p.name, c.name as category_name, CONCAT(REPLACE(FORMAT(p.current_price, 0), ',', '.'), 'đ') as current_price, sum(quantity) as sumQuantity, CONCAT(REPLACE(FORMAT(sum(quantity * p.current_price), 0), ',', '.'), 'đ') as sumRevenue
          From product p join category c
            on p.id_Category = c.id_Category
              join order_detail od on od.id_Product = p.id_product
              join `order` o on od.id_Order = o.id_Order
          Where MONTH(o.created_at) >  MONTH(CURRENT_DATE) - 2
          Group By p.name
          Order By sum(quantity) desc

        ";
    return $this->getByQuery($sql);
  }

  public function getProductList_AdminProduct()
  {
      $sql = "
      Select id_product, main_image, p.name as product_name, 
          c.id_Category, c.name as category_name, 
          CONCAT(REPLACE(FORMAT(p.current_price, 0), ',', '.'), 'đ') as current_price, 
          CONCAT(REPLACE(FORMAT(p.original_price, 0), ',', '.'), 'đ') as original_price,
          CONCAT(REPLACE(FORMAT(p.import_price, 0), ',', '.'), 'đ') as import_price,
          store, click_count, created_at, updated_at, p.link, p.meta, 
          p.`order`, tag, CSDoiTra, CSGiaoHang, description, 
          discount_percent, p.hide, img2, img3, M, L, XL
      from product p join category c on p.id_Category = c.id_Category
      where p.hide = 0
  ";
  return $this->getByQuery($sql);
  }

  public function getProductList_AdminProduct_newest()
  {
    $sql = "
        Select id_product, main_image, p.name as product_name, 
            c.id_Category, c.name as category_name, 
            CONCAT(REPLACE(FORMAT(p.current_price, 0), ',', '.'), 'đ') as current_price, 
            CONCAT(REPLACE(FORMAT(p.original_price, 0), ',', '.'), 'đ') as original_price,
            CONCAT(REPLACE(FORMAT(p.import_price, 0), ',', '.'), 'đ') as import_price,
            store, click_count, created_at, updated_at, p.link, p.meta, 
            p.`order`, tag, CSDoiTra, CSGiaoHang, description, 
            discount_percent, p.hide, img2, img3, M, L, XL
        from product p join category c on p.id_Category = c.id_Category
        where p.hide = 0
        order by p.created_at desc
    ";
    return $this->getByQuery($sql);
  }

  public function getProductList_AdminProduct_oldest()
  {
    $sql = "
        Select id_product, main_image, p.name as product_name, 
            c.id_Category, c.name as category_name, 
            CONCAT(REPLACE(FORMAT(p.current_price, 0), ',', '.'), 'đ') as current_price, 
            CONCAT(REPLACE(FORMAT(p.original_price, 0), ',', '.'), 'đ') as original_price,
            CONCAT(REPLACE(FORMAT(p.import_price, 0), ',', '.'), 'đ') as import_price,
            store, click_count, created_at, updated_at, p.link, p.meta, 
            p.`order`, tag, CSDoiTra, CSGiaoHang, description, 
            discount_percent, p.hide, img2, img3, M, L, XL
        from product p join category c on p.id_Category = c.id_Category
        where p.hide = 0
        order by p.created_at asc
    ";
    return $this->getByQuery($sql);
  }

  public function getProductList_AdminProduct_priceASC()
  {
    $sql = "
        Select id_product, main_image, p.name as product_name, 
            c.id_Category, c.name as category_name, 
            CONCAT(REPLACE(FORMAT(p.current_price, 0), ',', '.'), 'đ') as current_price, 
            CONCAT(REPLACE(FORMAT(p.original_price, 0), ',', '.'), 'đ') as original_price,
            CONCAT(REPLACE(FORMAT(p.import_price, 0), ',', '.'), 'đ') as import_price,
            store, click_count, created_at, updated_at, p.link, p.meta, 
            p.`order`, tag, CSDoiTra, CSGiaoHang, description, 
            discount_percent, p.hide, img2, img3, M, L, XL
        from product p join category c on p.id_Category = c.id_Category
        where p.hide = 0
        order by p.current_price asc
    ";
    return $this->getByQuery($sql);
  }

  public function getProductList_AdminProduct_priceDESC()
  {
    $sql = "
        Select id_product, main_image, p.name as product_name, 
            c.id_Category, c.name as category_name, 
            CONCAT(REPLACE(FORMAT(p.current_price, 0), ',', '.'), 'đ') as current_price, 
            CONCAT(REPLACE(FORMAT(p.original_price, 0), ',', '.'), 'đ') as original_price,
            CONCAT(REPLACE(FORMAT(p.import_price, 0), ',', '.'), 'đ') as import_price,
            store, click_count, created_at, updated_at, p.link, p.meta, 
            p.`order`, tag, CSDoiTra, CSGiaoHang, description, 
            discount_percent, p.hide, img2, img3, M, L, XL
        from product p join category c on p.id_Category = c.id_Category
        where p.hide = 0
        order by p.current_price desc
    ";
    return $this->getByQuery($sql);
  }

  public function updateDataForProduct($id, $data)
  {
    return $this->updateForProduct(self::TABLE, $id, $data);
  }

  /**
   * Get filtered products by category ID with sorting
   * 
   * @param int $categoryId Category ID
   * @param array $filters Filter and sort options
   * @return array Filtered products
   */
  // public function getFilteredProducts($categoryId, $filters = [])
  // {
  //   // Extract filter parameters
  //   $priceMin = $filters['price_min'] ?? 0;
  //   $priceMax = $filters['price_max'] ?? 10000000;
  //   $sizes = $filters['sizes'] ?? [];
  //   $sort = $filters['sort'] ?? 'newest';

  //   // Build SQL query
  //   $sql = "SELECT * FROM " . self::TABLE . " WHERE id_Category = {$categoryId}";

  //   // Add price range filter
  //   $sql .= " AND current_price >= {$priceMin} AND current_price <= {$priceMax}";

  //   // Add size filter if selected
  //   if (!empty($sizes)) {
  //     $sizeConditions = [];
  //     foreach ($sizes as $size) {
  //       if (in_array($size, ['M', 'L', 'XL'])) {
  //         $sizeConditions[] = "`{$size}` > 0";
  //       }
  //     }
  //     if (!empty($sizeConditions)) {
  //       $sql .= " AND (" . implode(" OR ", $sizeConditions) . ")";
  //     }
  //   }

  //   // Add sorting
  //   switch ($sort) {
  //     case 'price-asc':
  //       $sql .= " ORDER BY current_price ASC";
  //       break;
  //     case 'price-desc':
  //       $sql .= " ORDER BY current_price DESC";
  //       break;
  //     case 'bestseller':
  //       // Đối với bestseller, lý tưởng nhất là có cột hoặc tính toán riêng
  //       // Ở đây tạm thời dùng số lượt click làm tiêu chí "bán chạy"
  //       $sql .= " ORDER BY click_count DESC";
  //       break;
  //     case 'newest':
  //     default:
  //       $sql .= " ORDER BY created_at DESC";
  //       break;
  //   }

  //   // Get products with the constructed query
  //   return $this->getByQuery($sql);
  // }

  // public function getFilteredProductsForIndex($filters = [])
  // {
  //   // Extract filter parameters
  //   $priceMin = $filters['price_min'] ?? 0;
  //   $priceMax = $filters['price_max'] ?? 10000000;
  //   $sizes = $filters['sizes'] ?? [];
  //   $sort = $filters['sort'] ?? 'newest';

  //   // Build SQL query
  //   $sql = "SELECT * FROM " . self::TABLE;

  //   // Add price range filter
  //   $sql .= " WHERE current_price >= {$priceMin} AND current_price <= {$priceMax}";

  //   // Add size filter if selected
  //   if (!empty($sizes)) {
  //     $sizeConditions = [];
  //     foreach ($sizes as $size) {
  //       if (in_array($size, ['M', 'L', 'XL'])) {
  //         $sizeConditions[] = "`{$size}` > 0";
  //       }
  //     }
  //     if (!empty($sizeConditions)) {
  //       $sql .= " AND (" . implode(" OR ", $sizeConditions) . ")";
  //     }
  //   }

  //   // Add sorting
  //   switch ($sort) {
  //     case 'price-asc':
  //       $sql .= " ORDER BY current_price ASC";
  //       break;
  //     case 'price-desc':
  //       $sql .= " ORDER BY current_price DESC";
  //       break;
  //     case 'bestseller':
  //       // Đối với bestseller, lý tưởng nhất là có cột hoặc tính toán riêng
  //       // Ở đây tạm thời dùng số lượt click làm tiêu chí "bán chạy"
  //       $sql .= " ORDER BY click_count DESC";
  //       break;
  //     case 'newest':
  //     default:
  //       $sql .= " ORDER BY created_at DESC";
  //       break;
  //   }
  //   // Get products with the constructed query
  //   return $this->getByQuery($sql);
  // }
  public function searchProductsWithFilters($searchQuery, $filters = [])
{
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.id_category = c.id_Category 
            WHERE (p.name LIKE :searchQuery OR p.tag LIKE :searchTag OR p.description LIKE :searchDesc)
            AND p.hide = 0";
    
    $params = [
        ':searchQuery' => "%{$searchQuery}%",
        ':searchTag' => "%{$searchQuery}%",
        ':searchDesc' => "%{$searchQuery}%"
    ];
    
    // Apply price filter
    if (isset($filters['price_max']) && $filters['price_max'] > 0) {
        $sql .= " AND p.current_price <= :price_max";
        $params[':price_max'] = $filters['price_max'];
    }
    
    if (isset($filters['price_min']) && $filters['price_min'] > 0) {
        $sql .= " AND p.current_price >= :price_min";
        $params[':price_min'] = $filters['price_min'];
    }
    
    // Apply category filter
    if (isset($filters['categories']) && !empty($filters['categories'])) {
        $categoryPlaceholders = [];
        foreach ($filters['categories'] as $index => $categoryId) {
            $placeholder = ":category_{$index}";
            $categoryPlaceholders[] = $placeholder;
            $params[$placeholder] = $categoryId;
        }
        $sql .= " AND p.id_category IN (" . implode(',', $categoryPlaceholders) . ")";
    }
    
    // Apply tag filter
    if (isset($filters['tags']) && !empty($filters['tags'])) {
        $tagConditions = [];
        foreach ($filters['tags'] as $index => $tag) {
            $placeholder = ":tag_{$index}";
            $tagConditions[] = "p.tag LIKE {$placeholder}";
            $params[$placeholder] = "%{$tag}%";
        }
        $sql .= " AND (" . implode(' OR ', $tagConditions) . ")";
    }
    
    // Apply size filter (assuming sizes are stored in a separate table or as JSON)
    if (isset($filters['sizes']) && !empty($filters['sizes'])) {
        $sizeConditions = [];
        foreach ($filters['sizes'] as $index => $size) {
            $placeholder = ":size_{$index}";
            $sizeConditions[] = "p.sizes LIKE {$placeholder}";
            $params[$placeholder] = "%{$size}%";
        }
        $sql .= " AND (" . implode(' OR ', $sizeConditions) . ")";
    }
    
    // Apply sorting
    $sort = $filters['sort'] ?? 'relevance';
    switch ($sort) {
        case 'price-asc':
            $sql .= " ORDER BY p.current_price ASC";
            break;
        case 'price-desc':
            $sql .= " ORDER BY p.current_price DESC";
            break;
        case 'newest':
            $sql .= " ORDER BY p.id_product DESC";
            break;
        case 'popular':
            $sql .= " ORDER BY p.click_count DESC";
            break;
        case 'relevance':
        default:
            // Sort by relevance (name match first, then tag match)
            $sql .= " ORDER BY 
                      CASE 
                        WHEN p.name LIKE :searchQuery THEN 1
                        WHEN p.tag LIKE :searchTag THEN 2
                        ELSE 3
                      END,
                      p.click_count DESC";
            break;
    }
    
    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate discount percentage and format prices
    foreach ($products as &$product) {
        $product['discount_percent'] = 0;
        if ($product['original_price'] > $product['current_price']) {
            $product['discount_percent'] = round((($product['original_price'] - $product['current_price']) / $product['original_price']) * 100);
        }
    }
    
    return $products;
}

/**
 * Get filtered products by tag with additional filters
 */
public function getFilteredProductsByTag($tag, $filters = [])
{
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.id_category = c.id_Category 
            WHERE p.tag LIKE :tag AND p.hide = 0";
    
    $params = [':tag' => "%{$tag}%"];
    
    // Apply price filter
    if (isset($filters['price_max']) && $filters['price_max'] > 0) {
        $sql .= " AND p.current_price <= :price_max";
        $params[':price_max'] = $filters['price_max'];
    }
    
    if (isset($filters['price_min']) && $filters['price_min'] > 0) {
        $sql .= " AND p.current_price >= :price_min";
        $params[':price_min'] = $filters['price_min'];
    }
    
    // Apply size filter
    if (isset($filters['sizes']) && !empty($filters['sizes'])) {
        $sizeConditions = [];
        foreach ($filters['sizes'] as $index => $size) {
            $placeholder = ":size_{$index}";
            $sizeConditions[] = "p.sizes LIKE {$placeholder}";
            $params[$placeholder] = "%{$size}%";
        }
        $sql .= " AND (" . implode(' OR ', $sizeConditions) . ")";
    }
    
    // Apply sorting
    $sort = $filters['sort'] ?? 'newest';
    switch ($sort) {
        case 'price-asc':
            $sql .= " ORDER BY p.current_price ASC";
            break;
        case 'price-desc':
            $sql .= " ORDER BY p.current_price DESC";
            break;
        case 'popular':
            $sql .= " ORDER BY p.click_count DESC";
            break;
        case 'newest':
        default:
            $sql .= " ORDER BY p.id_product DESC";
            break;
    }
    
    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate discount percentage
    foreach ($products as &$product) {
        $product['discount_percent'] = 0;
        if ($product['original_price'] > $product['current_price']) {
            $product['discount_percent'] = round((($product['original_price'] - $product['current_price']) / $product['original_price']) * 100);
        }
    }
    
    return $products;
}

/**
 * Advanced search with multiple parameters
 */
public function advancedSearch($searchParams)
{
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.id_category = c.id_Category 
            WHERE p.hide = 0";
    
    $params = [];
    $conditions = [];
    
    // Name search
    if (!empty($searchParams['name'])) {
        $conditions[] = "p.name LIKE :name";
        $params[':name'] = "%{$searchParams['name']}%";
    }
    
    // Category filter
    if (!empty($searchParams['category'])) {
        $conditions[] = "p.id_category = :category";
        $params[':category'] = $searchParams['category'];
    }
    
    // Tag filter
    if (!empty($searchParams['tag'])) {
        $conditions[] = "p.tag LIKE :tag";
        $params[':tag'] = "%{$searchParams['tag']}%";
    }
    
    // Price range
    if (isset($searchParams['price_min']) && $searchParams['price_min'] > 0) {
        $conditions[] = "p.current_price >= :price_min";
        $params[':price_min'] = $searchParams['price_min'];
    }
    
    if (isset($searchParams['price_max']) && $searchParams['price_max'] < 999999999) {
        $conditions[] = "p.current_price <= :price_max";
        $params[':price_max'] = $searchParams['price_max'];
    }
    
    // Size filter
    if (!empty($searchParams['sizes'])) {
        $sizeConditions = [];
        foreach ($searchParams['sizes'] as $index => $size) {
            $placeholder = ":size_{$index}";
            $sizeConditions[] = "p.sizes LIKE {$placeholder}";
            $params[$placeholder] = "%{$size}%";
        }
        $conditions[] = "(" . implode(' OR ', $sizeConditions) . ")";
    }
    
    // Add conditions to SQL
    if (!empty($conditions)) {
        $sql .= " AND " . implode(' AND ', $conditions);
    }
    
    $sql .= " ORDER BY p.id_product DESC";
    
    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Search within specific category
 */
public function searchInCategory($searchQuery, $categoryId, $filters = [])
{
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.id_category = c.id_Category 
            WHERE (p.name LIKE :searchQuery OR p.tag LIKE :searchTag) 
            AND p.id_category = :category_id 
            AND p.hide = 0";
    
    $params = [
        ':searchQuery' => "%{$searchQuery}%",
        ':searchTag' => "%{$searchQuery}%",
        ':category_id' => $categoryId
    ];
    
    // Apply additional filters
    if (isset($filters['price_max']) && $filters['price_max'] > 0) {
        $sql .= " AND p.current_price <= :price_max";
        $params[':price_max'] = $filters['price_max'];
    }
    
    if (isset($filters['sizes']) && !empty($filters['sizes'])) {
        $sizeConditions = [];
        foreach ($filters['sizes'] as $index => $size) {
            $placeholder = ":size_{$index}";
            $sizeConditions[] = "p.sizes LIKE {$placeholder}";
            $params[$placeholder] = "%{$size}%";
        }
        $sql .= " AND (" . implode(' OR ', $sizeConditions) . ")";
    }
    
    // Apply sorting
    $sort = $filters['sort'] ?? 'relevance';
    switch ($sort) {
        case 'price-asc':
            $sql .= " ORDER BY p.current_price ASC";
            break;
        case 'price-desc':
            $sql .= " ORDER BY p.current_price DESC";
            break;
        case 'newest':
            $sql .= " ORDER BY p.id_product DESC";
            break;
        case 'relevance':
        default:
            $sql .= " ORDER BY 
                      CASE 
                        WHEN p.name LIKE :searchQuery THEN 1
                        WHEN p.tag LIKE :searchTag THEN 2
                        ELSE 3
                      END";
            break;
    }
    
    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get search suggestions for autocomplete
 */
public function getSearchSuggestions($query, $limit = 8)
{
    $sql = "SELECT p.id_product, p.name, p.main_image, p.current_price 
            FROM products p 
            WHERE (p.name LIKE :query OR p.tag LIKE :query) 
            AND p.hide = 0 
            ORDER BY p.click_count DESC 
            LIMIT :limit";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':query', "%{$query}%", PDO::PARAM_STR);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get product for quick view (AJAX)
 */
public function getProductForQuickView($productId)
{
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.id_category = c.id_Category 
            WHERE p.id_product = :id AND p.hide = 0";
    
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':id' => $productId]);
    
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($product) {
        // Calculate discount percentage
        $product['discount_percent'] = 0;
        if ($product['original_price'] > $product['current_price']) {
            $product['discount_percent'] = round((($product['original_price'] - $product['current_price']) / $product['original_price']) * 100);
        }
        
        // Increment click count
        $this->incrementClickCount($productId);
    }
    
    return $product;
}

/**
 * Increment product click count
 */
// public function incrementClickCount($productId)
// {
//     $sql = "UPDATE products SET click_count = click_count + 1 WHERE id_product = :id";
//     $stmt = $this->db->prepare($sql);
//     $stmt->execute([':id' => $productId]);
// }

/**
 * Get popular search terms (you might want to track this in a separate table)
 */
public function getPopularSearchTerms($limit = 10)
{
    // This is a simple implementation - in production, you'd track search queries
    $sql = "SELECT DISTINCT 
                TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(p.tag, ',', numbers.n), ',', -1)) as search_term,
                COUNT(*) as frequency
            FROM products p
            CROSS JOIN (
                SELECT 1 n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5
            ) numbers
            WHERE CHAR_LENGTH(p.tag) - CHAR_LENGTH(REPLACE(p.tag, ',', '')) >= numbers.n - 1
            AND p.hide = 0
            GROUP BY search_term
            HAVING search_term != ''
            ORDER BY frequency DESC
            LIMIT :limit";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
 public function getFilteredProducts($categoryId, $filters, $page = 1, $perPage = 9)
    {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM " . self::TABLE . " WHERE id_Category = " . intval($categoryId) . " AND hide = 0";
        
        // Apply filters
        if (!empty($filters['price_min'])) {
            $sql .= " AND current_price >= " . intval($filters['price_min']);
        }
        if (!empty($filters['price_max'])) {
            $sql .= " AND current_price <= " . intval($filters['price_max']);
        }
        if (!empty($filters['sizes'])) {
            $sizes = array_map('strtoupper', $filters['sizes']);
            $sizeConditions = array_map(function($size) {
                return "`$size` > 0";
            }, $sizes);
            $sql .= " AND (" . implode(' OR ', $sizeConditions) . ")";
        }

        // Apply sorting
        switch ($filters['sort']) {
            case 'price-asc':
                $sql .= " ORDER BY current_price ASC";
                break;
            case 'price-desc':
                $sql .= " ORDER BY current_price DESC";
                break;
            case 'bestseller':
                $sql .= " ORDER BY click_count DESC";
                break;
            default:
                $sql .= " ORDER BY created_at DESC";
                break;
        }

        // Apply pagination
        $sql .= " LIMIT " . intval($perPage) . " OFFSET " . intval($offset);

        return $this->getByQuery($sql);
    }

    /**
     * Count filtered products for a specific category
     */
    public function countFilteredProducts($categoryId, $filters)
    {
        $sql = "SELECT COUNT(*) as total FROM " . self::TABLE . " WHERE id_Category = " . intval($categoryId) . " AND hide = 0";
        
        if (!empty($filters['price_min'])) {
            $sql .= " AND current_price >= " . intval($filters['price_min']);
        }
        if (!empty($filters['price_max'])) {
            $sql .= " AND current_price <= " . intval($filters['price_max']);
        }
        if (!empty($filters['sizes'])) {
            $sizes = array_map('strtoupper', $filters['sizes']);
            $sizeConditions = array_map(function($size) {
                return "`$size` > 0";
            }, $sizes);
            $sql .= " AND (" . implode(' OR ', $sizeConditions) . ")";
        }

        $result = $this->getByQuery($sql);
        return $result[0]['total'];
    }

    /**
     * Get filtered products for index page with pagination
     */
    public function getFilteredProductsForIndex($filters, $page = 1, $perPage = 9)
    {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM " . self::TABLE . " WHERE hide = 0";
        
        if (!empty($filters['price_min'])) {
            $sql .= " AND current_price >= " . intval($filters['price_min']);
        }
        if (!empty($filters['price_max'])) {
            $sql .= " AND current_price <= " . intval($filters['price_max']);
        }
        if (!empty($filters['sizes'])) {
            $sizes = array_map('strtoupper', $filters['sizes']);
            $sizeConditions = array_map(function($size) {
                return "`$size` > 0";
            }, $sizes);
            $sql .= " AND (" . implode(' OR ', $sizeConditions) . ")";
        }

        switch ($filters['sort']) {
            case 'price-asc':
                $sql .= " ORDER BY current_price ASC";
                break;
            case 'price-desc':
                $sql .= " ORDER BY current_price DESC";
                break;
            case 'bestseller':
                $sql .= " ORDER BY click_count DESC";
                break;
            default:
                $sql .= " ORDER BY created_at DESC";
                break;
        }

        $sql .= " LIMIT " . intval($perPage) . " OFFSET " . intval($offset);

        return $this->getByQuery($sql);
    }

    /**
     * Count filtered products for index page
     */
    public function countFilteredProductsForIndex($filters)
    {
        $sql = "SELECT COUNT(*) as total FROM " . self::TABLE . " WHERE hide = 0";
        
        if (!empty($filters['price_min'])) {
            $sql .= " AND current_price >= " . intval($filters['price_min']);
        }
        if (!empty($filters['price_max'])) {
            $sql .= " AND current_price <= " . intval($filters['price_max']);
        }
        if (!empty($filters['sizes'])) {
            $sizes = array_map('strtoupper', $filters['sizes']);
            $sizeConditions = array_map(function($size) {
                return "`$size` > 0";
            }, $sizes);
            $sql .= " AND (" . implode(' OR ', $sizeConditions) . ")";
        }

        $result = $this->getByQuery($sql);
        return $result[0]['total'];
    }

    /**
     * Get products by category ID
     */
    public function getByCategoryId($categoryId)
    {
        $sql = "SELECT * FROM " . self::TABLE . " WHERE id_Category = " . intval($categoryId) . " AND hide = 0";
        return $this->getByQuery($sql);
    }
}
?>