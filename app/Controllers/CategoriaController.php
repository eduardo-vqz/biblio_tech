<?php
namespace App\Controllers;

use App\Repositories\CategoriaRepository;
use App\Models\Categoria;

class CategoriaController
{
    private CategoriaRepository $categoriaRepo;

    public function __construct()
    {
        $this->categoriaRepo = new CategoriaRepository();
    }

    // LISTADO DE CATEGORÍAS
    public function index(): void
    {
        $categorias = $this->categoriaRepo->findAll();

        $viewFile = __DIR__ . '/../../view/categorias/listado.php';
        include __DIR__ . '/../../view/layout/main_layout.php';
    }

    // FORMULARIO NUEVA CATEGORÍA
    public function create(): void
    {
        $categoria = new Categoria(); // objeto vacío

        $viewFile = __DIR__ . '/../../view/categorias/formulario.php';
        include __DIR__ . '/../../view/layout/main_layout.php';
    }

    // GUARDAR NUEVA CATEGORÍA (POST)
    public function store(): void
    {
        $nombre      = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if ($nombre === '') {
            // Podrías guardar un mensaje en sesión; por ahora, regresamos al listado
            header('Location: index.php?c=categoria&a=index');
            exit;
        }

        $categoria = new Categoria(
            null,
            $nombre,
            $descripcion !== '' ? $descripcion : null
        );

        $this->categoriaRepo->create($categoria);

        header('Location: index.php?c=categoria&a=index');
        exit;
    }

    // FORMULARIO EDITAR CATEGORÍA
    public function edit(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            header('Location: index.php?c=categoria&a=index');
            exit;
        }

        $categoria = $this->categoriaRepo->findById($id);

        if (!$categoria) {
            header('Location: index.php?c=categoria&a=index');
            exit;
        }

        $viewFile = __DIR__ . '/../../view/categorias/formulario.php';
        include __DIR__ . '/../../view/layout/main_layout.php';
    }

    // ACTUALIZAR CATEGORÍA (POST)
    public function update(): void
    {
        $id          = (int)($_POST['id_categoria'] ?? 0);
        $nombre      = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if ($id <= 0 || $nombre === '') {
            header('Location: index.php?c=categoria&a=index');
            exit;
        }

        $categoria = new Categoria(
            $id,
            $nombre,
            $descripcion !== '' ? $descripcion : null
        );

        $this->categoriaRepo->update($categoria);

        header('Location: index.php?c=categoria&a=index');
        exit;
    }

    // ELIMINAR CATEGORÍA
    public function delete(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id > 0) {
            $this->categoriaRepo->delete($id);
        }

        header('Location: index.php?c=categoria&a=index');
        exit;
    }
}
 