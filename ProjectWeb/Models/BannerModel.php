<?php
class BannerModel extends BaseModel
{
    const TABLE = 'banners';

    public function getBanner()
    {
        return $this->getByQuery('
            Select * From banners
        ');
    }
}
?>