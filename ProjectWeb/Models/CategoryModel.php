<?php
class CategoryModel extends BaseModel
{
    const TABLE = 'category';
    
    /**
     * Get all categories with optional filtering and sorting
     */
    public function getAll($select = ['*'], $limit = 100, $orderBys = [])
    {
        // By default, only show non-hidden categories
        $sql = "SELECT " . implode(',', $select) . " FROM " . self::TABLE;
        
        // Add WHERE condition to filter by hide = 0
        $sql .= " WHERE hide = 0 OR hide IS NULL";
        
        // Add ORDER BY if specified
        if (!empty($orderBys)) {
            $sql .= " ORDER BY " . $orderBys['column'] . " " . $orderBys['order'];
        }
        
        // Add LIMIT
        $sql .= " LIMIT " . $limit;
        
        return $this->getByQuery($sql);
    }
    
    /**
     * Find a category by its ID
     */
    public function findById($id)
    {
        $sql = "SELECT * FROM " . self::TABLE . " WHERE id_Category = " . intval($id) . " LIMIT 1";
        $result = $this->getByQuery($sql);
        return $result ? $result[0] : null;
    }
    
    /**
     * Store a new category
     */
    public function store($data)
    {
        return $this->create(self::TABLE, $data);
    }
    
    /**
     * Update a category
     */
    public function updateData($id, $data)
    {
        return $this->update(self::TABLE, $id, $data);
    }
    
    /**
     * Delete a category
     */
    public function deleteData($id)
    {
        return $this->delete(self::TABLE, $id);
    }
    
    /**
     * Get categories with their product counts
     */
    public function getCategoriesWithProductCount()
    {
        $sql = "SELECT c.*, COUNT(p.id_product) as product_count 
                FROM " . self::TABLE . " c
                LEFT JOIN product p ON c.id_Category = p.id_Category
                WHERE c.hide = 0 OR c.hide IS NULL
                GROUP BY c.id_Category
                ORDER BY c.`order` ASC";
        
        return $this->getByQuery($sql);
    }
    
    /**
     * Get featured categories for home page (based on lowest order values)
     */
    public function getFeaturedCategories($limit = 5)
    {
        $sql = "SELECT * FROM " . self::TABLE . " 
                WHERE hide = 0 OR hide IS NULL
                ORDER BY `order` ASC 
                LIMIT " . intval($limit);
        
        return $this->getByQuery($sql);
    }
    
    /**
     * Get categories for menu display
     */
    public function getCategoriesForMenu()
    {
        $sql = "SELECT id_Category, name, link FROM " . self::TABLE . " 
                WHERE hide = 0 OR hide IS NULL
                ORDER BY `order` ASC";
        
        return $this->getByQuery($sql);
    }
    
}
?>