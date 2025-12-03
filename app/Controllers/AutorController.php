<?php
namespace App\Controllers;

use App\Repositories\AutorRepository;
use App\Models\Autor;

class AutorController
{
    private AutorRepository $autorRepo;

    public function __construct()
    {
        $this->autorRepo = new AutorRepository();
    }

    // LISTADO DE AUTORES
    public function index(): void
    {
        $autores = $this->autorRepo->findAll();

        $viewFile = __DIR__ . '/../../view/autores/listado.php';
        include __DIR__ . '/../../view/layout/main_layout.php';
    }

    // FORMULARIO NUEVO AUTOR
    public function create(): void
    {
        $autor = new Autor(); // objeto vacío

        $viewFile = __DIR__ . '/../../view/autores/formulario.php';
        include __DIR__ . '/../../view/layout/main_layout.php';
    }

    // GUARDAR NUEVO AUTOR (POST)
    public function store(): void
    {
        $nombre       = trim($_POST['nombre'] ?? '');
        $apellido     = trim($_POST['apellido'] ?? '');
        $nacionalidad = trim($_POST['nacionalidad'] ?? '');

        if ($nombre === '' || $apellido === '') {
            // Podrías manejar mensajes de error con sesión; por ahora redirigimos
            header('Location: index.php?c=autor&a=index');
            exit;
        }

        $autor = new Autor(
            null,
            $nombre,
            $apellido,
            $nacionalidad !== '' ? $nacionalidad : null
        );

        $this->autorRepo->create($autor);

        header('Location: index.php?c=autor&a=index');
        exit;
    }

    // FORMULARIO EDITAR AUTOR
    public function edit(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            header('Location: index.php?c=autor&a=index');
            exit;
        }

        $autor = $this->autorRepo->findById($id);

        if (!$autor) {
            header('Location: index.php?c=autor&a=index');
            exit;
        }

        $viewFile = __DIR__ . '/../../view/autores/formulario.php';
        include __DIR__ . '/../../view/layout/main_layout.php';
    }

    // ACTUALIZAR AUTOR (POST)
    public function update(): void
    {
        $id           = (int)($_POST['id_autor'] ?? 0);
        $nombre       = trim($_POST['nombre'] ?? '');
        $apellido     = trim($_POST['apellido'] ?? '');
        $nacionalidad = trim($_POST['nacionalidad'] ?? '');

        if ($id <= 0 || $nombre === '' || $apellido === '') {
            header('Location: index.php?c=autor&a=index');
            exit;
        }

        $autor = new Autor(
            $id,
            $nombre,
            $apellido,
            $nacionalidad !== '' ? $nacionalidad : null
        );

        $this->autorRepo->update($autor);

        header('Location: index.php?c=autor&a=index');
        exit;
    }

    // ELIMINAR AUTOR
    public function delete(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id > 0) {
            $this->autorRepo->delete($id);
        }

        header('Location: index.php?c=autor&a=index');
        exit;
    }
}
