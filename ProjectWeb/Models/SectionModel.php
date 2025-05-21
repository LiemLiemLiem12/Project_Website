<?php
class SectionModel extends BaseModel
{
    const TABLE = 'home_sections';

    public function getAll()
    {
        return $this->getByQuery('
            SELECT * FROM ' . self::TABLE . '
            Order By `order` asc
        ');
    }

    public function getDetailProductById($id)
    {
        return $this->getByQuery('
            SELECT * FROM home_section_items h join product p on h.item_id = p.id_product
            WHERE section_id = ' . $id . '
            ORDER BY h.`order` asc
        ');
    }
}
?>