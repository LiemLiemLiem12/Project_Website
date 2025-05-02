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
            WHERE MONTH(CREATED_AT) = " . $month . " AND YEAR(CREATED_AT) = YEAR(CURDATE()) AND STATUS = 'delivered'
        ");
    }
}
?>