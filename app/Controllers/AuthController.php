<?php
namespace App\Controllers;

use App\Repositories\UsuarioRepository;

class AuthController
{
    private UsuarioRepository $usuarioRepo;

    public function __construct()
    {
        $this->usuarioRepo = new UsuarioRepository();
    }

    // Mostrar formulario de login
    public function login(): void
{
    if (isset($_SESSION['usuario'])) {
        header('Location: index.php?c=home&a=index');
        exit;
    }

    $error = $_SESSION['login_error'] ?? null;
    unset($_SESSION['login_error']);

    $viewFile = __DIR__ . '/../../view/auth/login.php';
    include __DIR__ . '/../../view/layout/main_layout.php';
}


    // Procesar login (POST)
    public function authenticate(): void
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $_SESSION['login_error'] = 'Debe ingresar correo y contraseña.';
            header('Location: index.php?c=auth&a=login');
            exit;
        }

        $usuario = $this->usuarioRepo->findByEmail($email);

        // Validar usuario, estado y contraseña
        if (!$usuario || $usuario->getEstado() !== 1) {
            $_SESSION['login_error'] = 'Usuario no encontrado o inactivo.';
            header('Location: index.php?c=auth&a=login');
            exit;
        }

        if (!password_verify($password, $usuario->getPasswordHash())) {
            $_SESSION['login_error'] = 'Credenciales incorrectas.';
            header('Location: index.php?c=auth&a=login');
            exit;
        }

        // Login exitoso: guardamos datos mínimos en sesión
        $_SESSION['usuario'] = [
            'id'     => $usuario->getIdUsuario(),
            'nombre' => $usuario->getNombreCompleto(),
            'email'  => $usuario->getEmail(),
            'tipo'   => $usuario->getTipoUsuario(),
        ];

        header('Location: index.php?c=home&a=index');
        exit;
    }

    // Cerrar sesión
    public function logout(): void
    {
        unset($_SESSION['usuario']);
        session_regenerate_id(true);

        header('Location: index.php?c=auth&a=login');
        exit;
    }

    // Por si el router llama index por defecto
    public function index(): void
    {
        $this->login();
    }
}
