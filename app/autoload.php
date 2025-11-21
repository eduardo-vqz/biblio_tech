<?php
// Autoloader sencillo para clases bajo el namespace App\*

spl_autoload_register(function (string $class) {
    $prefix   = 'App\\';
    $baseDir  = __DIR__ . '/';  // carpeta /app

    // Identificamos si la clase utiliza el prefijo
    $len = strlen($prefix);
    if (strncmp($class, $prefix, $len) !== 0) {
        return; // No es de App\, ignoramos
    }

    // Obtener la parte relativa del nombre de la clase (sin el prefijo App\)
    $relativeClass = substr($class, $len);

    // Reemplazar "\" por "/" y formar ruta física
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});
