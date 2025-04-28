
<?php
class CategoryModel extends BaseModel{
    const TABLE='categories';
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
      public function store(){
  
  
      }
      // // Cập nhật dữ liệu bảng
      // public function update(){
  
      // }
      // // Xóa dữ liệu trong bảng
      // public function delete(){
  
      // }
}
?>