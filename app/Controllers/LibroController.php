<?php
namespace App\Controllers;

use App\Repositories\LibroRepository;
use App\Repositories\CategoriaRepository;
use App\Models\Libro;

class LibroController
{
    private LibroRepository $libroRepo;
    private CategoriaRepository $categoriaRepo;

    public function __construct()
    {
        $this->libroRepo     = new LibroRepository();
        $this->categoriaRepo = new CategoriaRepository();
    }

    // LISTADO DE LIBROS
    public function index(): void
    {
        $libros = $this->libroRepo->findAll();

        // Ruta física de la vista
        $viewFile = __DIR__ . '/../../view/libros/listado.php';
        include __DIR__ . '/../../view/layout/main_layout.php';
    }

    // FORMULARIO NUEVO LIBRO
    public function create(): void
    {
        $categorias = $this->categoriaRepo->findAll();
        $libro = new Libro(); // objeto vacío para reutilizar el formulario

        $viewFile = __DIR__ . '/../../view/libros/formulario.php';
        include __DIR__ . '/../../view/layout/main_layout.php';
    }

    // GUARDAR NUEVO LIBRO (POST)
    public function store(): void
    {
        $titulo   = trim($_POST['titulo'] ?? '');
        $isbn     = trim($_POST['isbn'] ?? '');
        $idCat    = !empty($_POST['id_categoria']) ? (int)$_POST['id_categoria'] : null;
        $stock    = (int)($_POST['stock_total'] ?? 1);
        $anio     = !empty($_POST['anio_publicacion']) ? (int)$_POST['anio_publicacion'] : null;
        $estado   = $_POST['estado'] ?? 'DISPONIBLE';
        $desc     = trim($_POST['descripcion'] ?? '');

        if ($stock <= 0) {
            $stock = 1;
        }

        $libro = new Libro(
            null,
            $titulo,
            $isbn !== '' ? $isbn : null,
            $idCat,
            null,          // Categoria (objeto) lo puedes cargar después si quieres
            $stock,
            $stock,        // stock_disponible = stock_total al inicio
            $estado,
            $desc !== '' ? $desc : null,
            $anio,
            null           // fecha_registro la pone la BD
        );

        $this->libroRepo->create($libro);

        header('Location: index.php?c=libro&a=index');
        exit;
    }

    // FORMULARIO EDITAR LIBRO
    public function edit(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $libro = $this->libroRepo->findById($id);

        if (!$libro) {
            // Podrías mandar a una página de error o al listado
            header('Location: index.php?c=libro&a=index');
            exit;
        }

        $categorias = $this->categoriaRepo->findAll();

        $viewFile = __DIR__ . '/../../view/libros/formulario.php';
        include __DIR__ . '/../../view/layout/main_layout.php';
    }

    // ACTUALIZAR LIBRO (POST)
    public function update(): void
    {
        $id       = (int)($_POST['id_libro'] ?? 0);
        $titulo   = trim($_POST['titulo'] ?? '');
        $isbn     = trim($_POST['isbn'] ?? '');
        $idCat    = !empty($_POST['id_categoria']) ? (int)$_POST['id_categoria'] : null;
        $stock    = (int)($_POST['stock_total'] ?? 1);
        $stockDisp= (int)($_POST['stock_disponible'] ?? $stock);
        $anio     = !empty($_POST['anio_publicacion']) ? (int)$_POST['anio_publicacion'] : null;
        $estado   = $_POST['estado'] ?? 'DISPONIBLE';
        $desc     = trim($_POST['descripcion'] ?? '');

        if ($id <= 0) {
            header('Location: index.php?c=libro&a=index');
            exit;
        }

        $libro = new Libro(
            $id,
            $titulo,
            $isbn !== '' ? $isbn : null,
            $idCat,
            null,
            $stock,
            $stockDisp,
            $estado,
            $desc !== '' ? $desc : null,
            $anio,
            null // no tocamos fecha_registro desde aquí
        );

        $this->libroRepo->update($libro);

        header('Location: index.php?c=libro&a=index');
        exit;
    }

    // ELIMINAR LIBRO
    public function delete(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $this->libroRepo->delete($id);
        }

        header('Location: index.php?c=libro&a=index');
        exit;
    }
}
