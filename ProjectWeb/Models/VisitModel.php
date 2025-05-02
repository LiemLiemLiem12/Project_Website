<?php
class VisitModel extends BaseModel
{

    const TABLE = "visits";
    public function createSession($ip)
    {
        return $this->create(self::TABLE, ["ip_address" => $ip]);
    }

    public function getNumByMonth(int $month)
    {
        return $this->getScalar("
            Select count(*) as 'Tong'
            From " . self::TABLE . "
            WHERE MONTH(visited_at) = " . $month . " AND YEAR(visited_at) = YEAR(CURDATE())
        ");
    }
}
?>