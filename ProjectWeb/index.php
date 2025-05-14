<?php 
session_start();
require './core/Database.php';
require 'Models/BaseModel.php';
require './Controllers/BaseController.php';
$controllerName = ucfirst(strtolower($_REQUEST['controller'] ?? 'Home')) . 'Controller';
$actionName = $_REQUEST['action'] ?? 'index';
require "./Controllers/{$controllerName}.php";

$controllerObject = new $controllerName;
$controllerObject->$actionName();
?>