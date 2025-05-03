<?php
class OrderModel extends BaseModel
{

    const TABLE = "order";
    public function getByMonth($month)
    {
        return $this->getScalar("SELECT COUNT(*) 
                                        FROM `ORDER` 
                                        WHERE MONTH(CREATED_AT) = " . $month . "
                                        AND YEAR(CREATED_AT) = YEAR(CURDATE())");
    }

    public function getRevenue($month)
    {
        return $this->getScalar("
            Select sum(total_amount) as 'Tong'
            From `order`
            WHERE MONTH(CREATED_AT) = " . $month . " AND YEAR(CREATED_AT) = YEAR(CURDATE()) AND STATUS = 'completed'
        ");
    }

    public function getAll()
    {
        return $this->getByQuery("
            SELECT id_Order, name, CONCAT(REPLACE(FORMAT(total_amount, 0), ',', '.'), 'đ') as total_amount, status, `order`.created_at 
            FROM `order` join `user` on `order`.id_User = user.id_User
            ORDER BY created_at desc LIMIT 5
        ");
    }
}
?>