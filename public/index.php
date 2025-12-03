<?php
require_once __DIR__ . '/../app/autoload.php';
require_once __DIR__ . '/../app/Config/config.php';

use App\Controllers\HomeController;
use App\Controllers\LibroController;
use App\Controllers\CategoriaController;
use App\Controllers\AutorController;
// más use según vayas creando controladores

$controllerName = $_GET['c'] ?? 'home';
$action         = $_GET['a'] ?? 'index';

$controllerClassMap = [
    'home'  => HomeController::class,
    'libro' => LibroController::class,
    'categoria' => CategoriaController::class,
    'autor'     => AutorController::class,
];

if (!isset($controllerClassMap[$controllerName])) {
    $controllerClass = HomeController::class;
} else {
    $controllerClass = $controllerClassMap[$controllerName];
}

$controller = new $controllerClass();

if (!method_exists($controller, $action)) {
    $action = 'index';
}

$controller->$action();
