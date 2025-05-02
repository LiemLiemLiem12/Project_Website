<?php
class UserModel extends BaseModel
{
    const TABLE = "user";

    public function getNumByMonth($month)
    {
        return $this->getScalar("
                Select count(*) as 'Tong'
                From " . self::TABLE . "
                WHERE MONTH(CREATED_AT) = " . $month . " AND YEAR(CREATED_AT) = YEAR(CURDATE()) AND ROLE = 'user'
            ");
    }
}
?>