<?php
require_once 'pdo.php';

// /**
//  * Thêm loại mới
//  * @param String $ten_danhmuc là tên loại
//  * @throws PDOException lỗi thêm mới
//  */
// function danhmuc_insert($ten_danhmuc){
//     $sql = "INSERT INTO danhmuc(ten_danhmuc) VALUES(?)";
//     pdo_execute($sql, $ten_danhmuc);
// }
// /**
//  * Cập nhật tên loại
//  * @param int $ma_danhmuc là mã loại cần cập nhật
//  * @param String $ten_danhmuc là tên loại mới
//  * @throws PDOException lỗi cập nhật
//  */
// function danhmuc_update($ma_danhmuc, $ten_danhmuc){
//     $sql = "UPDATE danhmuc SET ten_danhmuc=? WHERE ma_danhmuc=?";
//     pdo_execute($sql, $ten_danhmuc, $ma_danhmuc);
// }
// /**
//  * Xóa một hoặc nhiều loại
//  * @param mix $ma_danhmuc là mã loại hoặc mảng mã loại
//  * @throws PDOException lỗi xóa
//  */
// function danhmuc_delete($ma_danhmuc){
//     $sql = "DELETE FROM danhmuc WHERE ma_danhmuc=?";
//     if(is_array($ma_danhmuc)){
//         foreach ($ma_danhmuc as $ma) {
//             pdo_execute($sql, $ma);
//         }
//     }
//     else{
//         pdo_execute($sql, $ma_danhmuc);
//     }
// }
/**
 * Truy vấn tất cả các loại
 * @return array mảng loại truy vấn được
 * @throws PDOException lỗi truy vấn
 */
function getlistall_product()
{
    $sql = "SELECT * FROM product ORDER BY `order` DESC";
    return pdo_query($sql);
}

function getlist_product($idcategory, $limit)
{
    $sql = 'SELECT * FROM product where categoryid=' . $idcategory . ' ORDER BY id DESC limit ' . $limit;
    return pdo_query($sql);
}
function getlistall_product_viewmax($limit)
{
    $sql = 'SELECT * FROM product ORDER BY view DESC limit ' . $limit;
    return pdo_query($sql);
}
function showproduct_viewmax($list)
{
    $html_prouct_viewmax = '<ul id="flexiselDemo1">';
    foreach ($list as $l) {
        extract($l);
        $html_prouct_viewmax .= '<li><a href="#"><img class="img-responsive women" src="upload/img/' . $img . '" alt=""></a>
                    <div class="hide-in"><p>' . $name . '</p>
                        <span>$179.00 | <a href="#">Buy Now </a></span>
                    </div>
                </li>';
    }
    $html_prouct_viewmax .= '</ul>';
    return $html_prouct_viewmax;
}


// /**
//  * Truy vấn một loại theo mã
//  * @param int $ma_danhmuc là mã loại cần truy vấn
//  * @return array mảng chứa thông tin của một loại
//  * @throws PDOException lỗi truy vấn
//  */
// function danhmuc_select_by_id($ma_danhmuc){
//     $sql = "SELECT * FROM danhmuc WHERE ma_danhmuc=?";
//     return pdo_query_one($sql, $ma_danhmuc);
// }
// /**
//  * Kiểm tra sự tồn tại của một loại
//  * @param int $ma_danhmuc là mã loại cần kiểm tra
//  * @return boolean có tồn tại hay không
//  * @throws PDOException lỗi truy vấn
//  */
// function danhmuc_exist($ma_danhmuc){
//     $sql = "SELECT count(*) FROM danhmuc WHERE ma_danhmuc=?";
//     return pdo_query_value($sql, $ma_danhmuc) > 0;
// }