<?php
namespace App\Controllers;

use App\Repositories\UsuarioRepository;
use App\Models\Usuario;

class UsuarioController
{
    private UsuarioRepository $usuarioRepo;

    public function __construct()
    {
        $this->usuarioRepo = new UsuarioRepository();
    }

    // LISTADO DE USUARIOS
    public function index(): void
    {
        $usuarios = $this->usuarioRepo->findAll();

        // Vista
        $viewFile = __DIR__ . '/../../view/usuario/listado.php';
        include __DIR__ . '/../../view/layout/main_layout.php';
    }

    // FORMULARIO CREAR USUARIO
    public function create(): void
    {
        $usuario = new Usuario(
            null,
            "",
            "",
            "",
            "",
            "",
            "ESTUDIANTE", // tipo por defecto
            1               // estado activo
        );

        $viewFile = __DIR__ . '/../../view/usuario/formulario.php';
        include __DIR__ . '/../../view/layout/main_layout.php';
    }

    // GUARDAR NUEVO USUARIO
    public function store(): void
    {
        $nombre     = trim($_POST['nombre'] ?? '');
        $apellido   = trim($_POST['apellido'] ?? '');
        $email      = trim($_POST['email'] ?? '');
        $password   = $_POST['password'] ?? '';
        $telefono   = trim($_POST['telefono'] ?? '');
        $tipo       = $_POST['tipo_usuario'] ?? 'ESTUDIANTE';
        $estado     = (int)($_POST['estado'] ?? 1);

        if ($nombre === '' || $apellido === '' || $email === '' || $password === '') {
            header("Location: index.php?c=usuario&a=create");
            exit;
        }

        // Encriptar contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $usuario = new Usuario(
            null,
            $nombre,
            $apellido,
            $email,
            $passwordHash,
            $telefono,
            $tipo,
            $estado
        );

        $this->usuarioRepo->create($usuario);

        header("Location: index.php?c=usuario&a=index");
        exit;
    }

    // FORMULARIO EDITAR
    public function edit(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            header("Location: index.php?c=usuario&a=index");
            exit;
        }

        $usuario = $this->usuarioRepo->findById($id);

        if (!$usuario) {
            header("Location: index.php?c=usuario&a=index");
            exit;
        }

        $viewFile = __DIR__ . '/../../view/usuario/formulario.php';
        include __DIR__ . '/../../view/layout/main_layout.php';
    }

    // ACTUALIZAR USUARIO
    public function update(): void
    {
        $id         = (int)($_POST['id_usuario'] ?? 0);
        $nombre     = trim($_POST['nombre'] ?? '');
        $apellido   = trim($_POST['apellido'] ?? '');
        $email      = trim($_POST['email'] ?? '');
        $telefono   = trim($_POST['telefono'] ?? '');
        $password   = $_POST['password'] ?? '';
        $tipo       = $_POST['tipo_usuario'] ?? 'ESTUDIANTE';
        $estado     = (int)($_POST['estado'] ?? 1);

        if ($id <= 0 || $nombre === '' || $apellido === '' || $email === '') {
            header("Location: index.php?c=usuario&a=index");
            exit;
        }

        // Cargar objeto actual
        $usuarioActual = $this->usuarioRepo->findById($id);

        if (!$usuarioActual) {
            header("Location: index.php?c=usuario&a=index");
            exit;
        }

        // Si no cambia la contraseña, dejamos la que estaba
        if ($password === '') {
            $passwordHash = $usuarioActual->getPasswordHash();
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        }

        $usuario = new Usuario(
            $id,
            $nombre,
            $apellido,
            $email,
            $passwordHash,
            $telefono,
            $tipo,
            $estado
        );

        $this->usuarioRepo->update($usuario);

        header("Location: index.php?c=usuario&a=index");
        exit;
    }

    // ELIMINAR
    public function delete(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id > 0) {
            $this->usuarioRepo->delete($id);
        }

        header("Location: index.php?c=usuario&a=index");
        exit;
    }
}
