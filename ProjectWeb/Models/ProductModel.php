
<?php
class ProductModel extends BaseModel{
    const TABLE='products';
      // Lấy tất cả dữ liệu từ bản
      public function getAll($select= ['*'],$limit,$orderBys=[])
      {
        return $this->all(self::TABLE,$select,$limit,$orderBys);
      }
      // Lấy dựa vào ID
      public function findById($id){
        return $this-> find(self::TABLE,$id);
      }
      // Thêm dữ liệu vào bảng
      public function store($data)
      {
        return $this-> create(self::TABLE,$data);
      }
      // Cập nhật dữ liệu bảng
      public function updateData($id,$data)
      {
        return $this-> update(self::TABLE,$id,$data);
      }
      // Xóa dữ liệu trong bảng
      public function deleteData($id){
        return $this -> delete(self::TABLE,$id);
      }
      public function getByCategoryId($categoryId)
      {
        $sql="SELECT * FROM ". self::TABLE ." WHERE category_id={$categoryId}";
        return $this->getByQuery($sql);
      }
}
?>