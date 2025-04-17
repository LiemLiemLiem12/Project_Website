<?php


// nhúng kết nối csdl
include "dao/pdo.php";
include "dao/temp.php";
include "dao/banner.php";


include "view/header.php";




if (!isset($_GET['pg'])) {
    include "view/home.php";
} else {
    switch ($_GET['pg']) {
        case 'product':
            include "view/product.php";
            break;
        default:
            include "view/home.php";
            break;
    }
}


include "view/footer.php";