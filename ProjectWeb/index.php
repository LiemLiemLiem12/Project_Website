<?php
ini_set('session.cookie_lifetime', 0); // Cookie phiên chỉ tồn tại đến khi đóng trình duyệt
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.gc_maxlifetime', 0);

session_start();

require './core/Database.php';
require 'Models/BaseModel.php';
require './Controllers/BaseController.php';

// Get controller and action from URL parameters
$controllerName = ucfirst(strtolower($_REQUEST['controller'] ?? 'Home')) . 'Controller';
$actionName = $_REQUEST['action'] ?? 'index';

// Path to the controller file
$controllerPath = "./Controllers/{$controllerName}.php";

// Record visit for analytics (if VisitModel is available)
if (!isset($_SESSION['visited'])) {
    // This is a new visit in this session
    $_SESSION['visited'] = true;

    // Try to record the visit if the class exists
    if (file_exists('Models/VisitModel.php')) {
        require_once 'Models/VisitModel.php';
        $vs = new VisitModel();

        // Record the visit
        $ip = $_SERVER['REMOTE_ADDR'];
        $vs->createSession($ip);
    }
}

// Check if controller file exists
if (file_exists($controllerPath)) {
    // Include controller file
    require_once $controllerPath;

    // Check if the controller class exists
    if (class_exists($controllerName)) {
        // Create controller instance
        $controllerObject = new $controllerName;

        // Check if action exists
        if (method_exists($controllerObject, $actionName)) {
            // Call the action
            $controllerObject->$actionName();
        } else {
            // Action not found, show 404 error
            header("HTTP/1.0 404 Not Found");
            echo "Error: Action '{$actionName}' not found in controller '{$controllerName}'";
            // Uncomment the line below if you have this view file:
            // require_once 'Views/frontend/errors/404.php';
        }
    } else {
        // Controller class doesn't exist
        header("HTTP/1.0 404 Not Found");
        echo "Error: Controller class '{$controllerName}' not found";
        // Uncomment the line below if you have this view file:
        // require_once 'Views/frontend/errors/404.php';
    }
} else {
    // Controller file not found
    header("HTTP/1.0 404 Not Found");
    echo "Error: Controller file '{$controllerPath}' not found";
    // Uncomment the line below if you have this view file:
    // require_once 'Views/frontend/errors/404.php';
}
?>