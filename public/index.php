<?php
session_start();

require_once __DIR__ . '/../app/autoload.php';
require_once __DIR__ . '/../app/Config/config.php';

use App\Controllers\HomeController;
use App\Controllers\LibroController;
use App\Controllers\CategoriaController;
use App\Controllers\AutorController;
use App\Controllers\UsuarioController;
use App\Controllers\PrestamoController;
use App\Controllers\AuthController;
use App\Security\AccessControl;

$controllerName = $_GET['c'] ?? 'home';
$action         = $_GET['a'] ?? 'index';

$controllerClassMap = [
    'home'      => HomeController::class,
    'libro'     => LibroController::class,
    'categoria' => CategoriaController::class,
    'autor'     => AutorController::class,
    'usuario'   => UsuarioController::class,
    'prestamo'  => PrestamoController::class,
    'auth'      => AuthController::class,
];

// Controladores públicos (no requieren login)
$publicControllers = ['auth'];

if (!isset($_SESSION['usuario']) && !in_array($controllerName, $publicControllers, true)) {
    // No logueado → mandar a login
    $controllerName = 'auth';
    $action         = 'login';
}

// Si el controlador no existe en el mapa, apuntamos a home
if (!isset($controllerClassMap[$controllerName])) {
    $controllerName = 'home';
}

$controllerClass = $controllerClassMap[$controllerName];

// Verificar permisos por rol (solo si está logueado y no es controlador público)
if (isset($_SESSION['usuario']) && !in_array($controllerName, $publicControllers, true)) {
    $tipoUsuario = $_SESSION['usuario']['tipo'] ?? '';

    if (!AccessControl::isAllowed($tipoUsuario, $controllerName, $action)) {
        // Si no tiene permiso, podemos:
        //  a) Mandarlo al home
        //  b) Mostrar página 403 sencilla
        $viewFile = __DIR__ . '/../view/error/403.php';
        if (!file_exists($viewFile)) {
            // fallback simple
            header('HTTP/1.1 403 Forbidden');
            echo "No tiene permisos para acceder a esta sección.";
            exit;
        }

        include_once $viewFile;
        exit;
    }
}

$controller = new $controllerClass();

if (!method_exists($controller, $action)) {
    $action = 'index';
}

$controller->$action();
