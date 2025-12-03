<?php
namespace App\Security;

class AccessControl
{
    /**
     * Verifica si un tipo de usuario puede acceder a cierto controlador/acción.
     */
    public static function isAllowed(string $tipoUsuario, string $controller, string $action): bool
    {
        $tipoUsuario = strtoupper($tipoUsuario);
        $controller  = strtolower($controller);
        $action      = strtolower($action);

        // ADMIN puede hacer de todo
        if ($tipoUsuario === 'ADMIN') {
            return true;
        }

        // BIBLIOTECARIO
        if ($tipoUsuario === 'BIBLIOTECARIO') {
            // Puede gestionar libros, categorías, autores y préstamos
            $controllersLibros = ['libro', 'categoria', 'autor', 'prestamo'];

            if (in_array($controller, $controllersLibros, true)) {
                // Permitimos todas las acciones CRUD típicas
                $accionesPermitidas = [
                    'index', 'create', 'store', 'edit', 'update', 'delete',
                    'marcardevuelto'
                ];
                return in_array($action, $accionesPermitidas, true);
            }

            // No puede gestionar usuarios ni configuración ni auth
            if (in_array($controller, ['usuario'], true)) {
                return false;
            }

            // Home siempre ok
            if ($controller === 'home') {
                return true;
            }

            return false;
        }

        // ESTUDIANTE
        if ($tipoUsuario === 'ESTUDIANTE') {
            // Puede ver libros
            if ($controller === 'libro') {
                // Solo lectura
                return in_array($action, ['index'], true);
            }

            // Puede gestionar sus préstamos (crear y ver los suyos)
            if ($controller === 'prestamo') {
                $accionesPermitidas = [
                    'index',   // listado (pero filtra por usuario en el controlador)
                    'create',  // formulario nuevo préstamo
                    'store',   // guardar préstamo
                ];
                return in_array($action, $accionesPermitidas, true);
            }

            // Home siempre ok
            if ($controller === 'home') {
                return true;
            }

            // Nada más
            return false;
        }

        // Si el tipo de usuario no es reconocido, negamos
        return false;
    }
}
