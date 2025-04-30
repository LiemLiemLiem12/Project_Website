<?php
require './core/Database.php';
require 'Models/BaseModel.php';
require './Controllers/BaseController.php';
$controllerName = ucfirst(strtolower($_REQUEST['controller'] ?? 'Home')) . 'Controller';
$actionName = $_REQUEST['action'] ?? 'index';
require "./Controllers/{$controllerName}.php";

$controllerObject = new $controllerName;
$controllerObject->$actionName();
// Router
// $pageRaw = $_GET['page'] ?? '';
// $parsedUrl = parse_url($pageRaw);
// $pageParam = $parsedUrl['path'] ?? '';
// Điều hướng đến các trang cụ thể
// switch ($pageParam) {
//     case 'myprofile':
//         include 'views/pages/customer/myprofile.php';
//         break;
//     case 'home':
//         include 'views/pages/customer/home.php';
//         break;
//     case 'product-detail':
//         include 'views/pages/customer/product-details.php';
//         break;
//     case 'checkout':
//         include 'views/pages/customer/checkout.php';
//         break;
//     case 'service':
//         include 'views/pages/customer/services.php';
//         break;
//     case 'cart':
//         include 'views/pages/customer/cart.php';
//         break;
//     case 'about-us':
//         include 'views/pages/customer/about-us.php';
//         break;
//     case 'contact':
//         include 'views/pages/customer/contact.php';
//         break;
//     case 'cart':
//         include 'views/pages/customer/cart.php';
//         break;
//     case 'login':
//         include 'views/pages/customer/login.php';
//         break;
//     case 'sign-in':
//         include 'views/pages/customer/register.php';
//         break;
//     case 'product-detail':
//         include 'views/pages/customer/product-details.php';
//         break;
//     default:
//         include 'Views/frontend/home/index.php';
//         break;
// }
?>