<?php

namespace App\Controllers;

use App\Repositories\LibroRepository;
use App\Repositories\CategoriaRepository;
use App\Repositories\AutorRepository;
use App\Repositories\LibroAutorRepository;
use App\Models\Libro;

class LibroController
{
    private LibroRepository $libroRepo;
    private CategoriaRepository $categoriaRepo;
    private AutorRepository $autorRepo;
    private LibroAutorRepository $libroAutorRepo;

    public function __construct()
    {
        $this->libroRepo      = new LibroRepository();
        $this->categoriaRepo  = new CategoriaRepository();
        $this->autorRepo      = new AutorRepository();
        $this->libroAutorRepo = new LibroAutorRepository();
    }

    public function index(): void
    {
        $libros = $this->libroRepo->findAll();

        // opcional: traer autores por libro para mostrar en la tabla
        $autoresPorLibro = [];
        foreach ($libros as $libro) {
            $autoresPorLibro[$libro->getIdLibro()] = $this->libroAutorRepo->findAutoresByLibro($libro->getIdLibro());
        }

        $viewFile = __DIR__ . '/../../view/libros/listado.php';
        include __DIR__ . '/../../view/layout/main_layout.php';
    }

    public function create(): void
    {
        $libro       = new Libro();
        $categorias  = $this->categoriaRepo->findAll();
        $autores     = $this->autorRepo->findAll();
        $autoresSel  = [];

        $viewFile = __DIR__ . '/../../view/libros/formulario.php';
        include __DIR__ . '/../../view/layout/main_layout.php';
    }

    public function store(): void
    {
        $titulo          = trim($_POST['titulo'] ?? '');
        $isbn            = trim($_POST['isbn'] ?? '');
        $anioPublicacion = (int)($_POST['anio_publicacion'] ?? 0);
        $idCategoria     = (int)($_POST['id_categoria'] ?? 0);
        $descripcion     = trim($_POST['descripcion'] ?? '');
        $stockTotal      = (int)($_POST['stock_total'] ?? 0);
        $stockDisp       = (int)($_POST['stock_disponible'] ?? $stockTotal);
        $estado          = trim($_POST['estado'] ?? 'DISPONIBLE');

        if ($titulo === '' || $idCategoria <= 0) {
            header('Location: index.php?c=libro&a=create');
            exit;
        }

        $libro = new Libro(
            null,
            $titulo,
            $isbn,
            $anioPublicacion,
            $idCategoria,
            $descripcion !== '' ? $descripcion : null,
            $stockTotal,
            $stockDisp,
            $estado !== '' ? $estado : 'DISPONIBLE',
            null
        );

        $idLibro = $this->libroRepo->create($libro);

        $idsAutores = $_POST['autores'] ?? [];
        $this->libroAutorRepo->setAutoresForLibro($idLibro, $idsAutores);

        header('Location: index.php?c=libro&a=index');
        exit;
    }

    public function edit(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            header('Location: index.php?c=libro&a=index');
            exit;
        }

        $libro = $this->libroRepo->findById($id);
        if (!$libro) {
            header('Location: index.php?c=libro&a=index');
            exit;
        }

        $categorias = $this->categoriaRepo->findAll();
        $autores    = $this->autorRepo->findAll();
        $autoresSel = $this->libroAutorRepo->findAutoresIdsByLibro($id);

        $viewFile = __DIR__ . '/../../view/libros/formulario.php';
        include __DIR__ . '/../../view/layout/main_layout.php';
    }

    public function update(): void
    {
        $idLibro         = (int)($_POST['id_libro'] ?? 0);
        $titulo          = trim($_POST['titulo'] ?? '');
        $isbn            = trim($_POST['isbn'] ?? '');
        $anioPublicacion = (int)($_POST['anio_publicacion'] ?? 0);
        $idCategoria     = (int)($_POST['id_categoria'] ?? 0);
        $descripcion     = trim($_POST['descripcion'] ?? '');
        $stockTotal      = (int)($_POST['stock_total'] ?? 0);
        $stockDisp       = (int)($_POST['stock_disponible'] ?? $stockTotal);
        $estado          = trim($_POST['estado'] ?? 'DISPONIBLE');

        if ($idLibro <= 0 || $titulo === '' || $idCategoria <= 0) {
            header('Location: index.php?c=libro&a=index');
            exit;
        }

        $libro = new Libro(
            $idLibro,
            $titulo,
            $isbn,
            $anioPublicacion,
            $idCategoria,
            $descripcion !== '' ? $descripcion : null,
            $stockTotal,
            $stockDisp,
            $estado !== '' ? $estado : 'DISPONIBLE',
            null
        );

        $this->libroRepo->update($libro);

        $idsAutores = $_POST['autores'] ?? [];
        $this->libroAutorRepo->setAutoresForLibro($idLibro, $idsAutores);

        header('Location: index.php?c=libro&a=index');
        exit;
    }

    public function delete(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id > 0) {
            $this->libroAutorRepo->deleteByLibro($id);
            $this->libroRepo->delete($id);
        }

        header('Location: index.php?c=libro&a=index');
        exit;
    }
}
