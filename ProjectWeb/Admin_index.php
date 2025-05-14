<?php
// Thiết lập đường dẫn cơ sở là admin
define('ADMIN_PATH', true);

// Yêu cầu các file cần thiết
require './core/Database.php';
require 'Models/BaseModel.php';
require './Controllers/BaseController.php';

// Mặc định controller là admindashboard nếu không có controller nào được chỉ định
$controllerName = ucfirst(strtolower($_REQUEST['controller'] ?? 'admindashboard')) . 'Controller';
$actionName = $_REQUEST['action'] ?? 'index';

// Đường dẫn file controller
$controllerFile = "./Controllers/{$controllerName}.php";

// Kiểm tra file tồn tại trước khi require
if (file_exists($controllerFile)) {
    require $controllerFile;
    
    // Tạo đối tượng controller và gọi hành động
    if (class_exists($controllerName)) {
        $controllerObject = new $controllerName;
        
        if (method_exists($controllerObject, $actionName)) {
            $controllerObject->$actionName();
        } else {
            echo "Action not found";
        }
    } else {
        echo "Controller class not found";
    }
} else {
    echo "Controller file not found";
}
?>