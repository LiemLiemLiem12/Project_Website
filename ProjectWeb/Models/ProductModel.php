<?php
class ProductModel extends BaseModel
{
  const TABLE = 'products';
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
}
?>