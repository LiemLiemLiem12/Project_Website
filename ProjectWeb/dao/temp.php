<?php
require_once 'pdo.php';
function getMenu()
{
    $sql = "SELECT * FROM menu where hide=1 ORDER BY `order` asc";
    return pdo_query($sql);
}
function showMenu($list)
{
    $html = '<ul>';
    foreach ($list as $v) {
        extract($v);        
        $html .= '<li><a href="#">' . $name . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}
?>