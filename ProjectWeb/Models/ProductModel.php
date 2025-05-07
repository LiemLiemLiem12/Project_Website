<?php
class ProductModel extends BaseModel
{
  const TABLE = 'product';
  // Lấy tất cả dữ liệu từ bản
  public function getAll($select = ['*'], $limit, $orderBys = [])
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
  public function getByCategoryId($categoryId)
  {
    $sql = "SELECT * FROM " . self::TABLE . " WHERE category_id={$categoryId}";
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
        Select id_product, main_image, p.name as product_name, c.id_Category, c.name as category_name, CONCAT(REPLACE(FORMAT(p.current_price, 0), ',', '.'), 'đ') as current_price, CONCAT(REPLACE(FORMAT(p.original_price, 0), ',', '.'), 'đ') as original_price, store, click_count, created_at, updated_at, p.link, p.meta, p.`order`, tag, CSDoiTra, CSGiaoHang, description, discount_percent, p.hide, img2, img3
        from product p join category c on p.id_Category = c.id_Category
    ";
    return $this->getByQuery($sql);
  }

  public function getProductList_AdminProduct_newest()
  {
    $sql = "
        Select id_product, main_image, p.name as product_name, c.id_Category, c.name as category_name, CONCAT(REPLACE(FORMAT(p.current_price, 0), ',', '.'), 'đ') as current_price, CONCAT(REPLACE(FORMAT(p.original_price, 0), ',', '.'), 'đ') as original_price, store, click_count, created_at, updated_at, p.link, p.meta, p.`order`, tag, CSDoiTra, CSGiaoHang, description, discount_percent, p.hide, img2, img3
        from product p join category c on p.id_Category = c.id_Category
        order by p.created_at desc
    ";
    return $this->getByQuery($sql);
  }

  public function getProductList_AdminProduct_oldest()
  {
    $sql = "
        Select id_product, main_image, p.name as product_name, c.id_Category, c.name as category_name, CONCAT(REPLACE(FORMAT(p.current_price, 0), ',', '.'), 'đ') as current_price, CONCAT(REPLACE(FORMAT(p.original_price, 0), ',', '.'), 'đ') as original_price, store, click_count, created_at, updated_at, p.link, p.meta, p.`order`, tag, CSDoiTra, CSGiaoHang, description, discount_percent, p.hide, img2, img3
        from product p join category c on p.id_Category = c.id_Category
        order by p.created_at asc
    ";
    return $this->getByQuery($sql);
  }

  public function getProductList_AdminProduct_priceASC()
  {
    $sql = "
        Select id_product, main_image, p.name as product_name, c.id_Category, c.name as category_name, CONCAT(REPLACE(FORMAT(p.current_price, 0), ',', '.'), 'đ') as current_price, CONCAT(REPLACE(FORMAT(p.original_price, 0), ',', '.'), 'đ') as original_price, store, click_count, created_at, updated_at, p.link, p.meta, p.`order`, tag, CSDoiTra, CSGiaoHang, description, discount_percent, p.hide, img2, img3
        from product p join category c on p.id_Category = c.id_Category
        order by p.current_price asc
    ";
    return $this->getByQuery($sql);
  }

  public function getProductList_AdminProduct_priceDESC()
  {
    $sql = "
        Select id_product, main_image, p.name as product_name, c.id_Category, c.name as category_name, CONCAT(REPLACE(FORMAT(p.current_price, 0), ',', '.'), 'đ') as current_price, CONCAT(REPLACE(FORMAT(p.original_price, 0), ',', '.'), 'đ') as original_price, store, click_count, created_at, updated_at, p.link, p.meta, p.`order`, tag, CSDoiTra, CSGiaoHang, description, discount_percent, p.hide, img2, img3
        from product p join category c on p.id_Category = c.id_Category
        order by p.current_price desc
    ";
    return $this->getByQuery($sql);
  }

  public function updateDataForProduct($id, $data)
  {
    return $this->updateForProduct(self::TABLE, $id, $data);
  }


}
?>