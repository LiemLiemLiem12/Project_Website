<?php
require_once 'pdo.php';
function getCategory()
{
    $sql = "SELECT * FROM category where hide=1 ORDER BY `order` asc";
    return pdo_query($sql);
}

?>