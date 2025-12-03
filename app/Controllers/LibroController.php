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

    // LISTADO DE LIBROS
    public function index(): void
    {
        // El repo puede traer ya la categoría unida (si lo programaste así)
        $libros = $this->libroRepo->findAll();

        $viewFile = __DIR__ . '/../../view/libros/listado.php';
        include __DIR__ . '/../../view/layout/main_layout.php';
    }

    // FORMULARIO NUEVO LIBRO
    public function create(): void
    {
        $libro       = new Libro();               // objeto vacío
        $categorias  = $this->categoriaRepo->findAll();
        $autores     = $this->autorRepo->findAll();
        $autoresSel  = [];                        // ningún autor seleccionado

        $viewFile = __DIR__ . '/../../view/libros/formulario.php';
        include __DIR__ . '/../../view/layout/main_layout.php';
    }

    // GUARDAR NUEVO LIBRO
    public function store(): void
    {
        $titulo          = trim($_POST['titulo']          ?? '');
        $isbn            = trim($_POST['isbn']            ?? '');
        $anioPublicacion = (int)($_POST['anio_publicacion'] ?? 0);
        $idCategoria     = (int)($_POST['id_categoria']   ?? 0);
        $descripcion     = trim($_POST['descripcion']     ?? '');
        $stockTotal      = (int)($_POST['stock_total']    ?? 0);
        $stockDisp       = (int)($_POST['stock_disponible'] ?? $stockTotal);
        $estado          = trim($_POST['estado']          ?? 'DISPONIBLE');

        if ($titulo === '' || $idCategoria <= 0) {
            header('Location: index.php?c=libro&a=create');
            exit;
        }

        // Creamos el modelo de Libro (ajusta los parámetros al orden de tu constructor)
        $libro = new Libro(
            null,                 // id_libro
            $titulo,
            $isbn,
            $anioPublicacion,
            $idCategoria,
            $descripcion !== '' ? $descripcion : null,
            $stockTotal,
            $stockDisp,
            $estado !== '' ? $estado : 'DISPONIBLE',
            null                  // fecha_registro (la pone la BD)
        );

        // IMPORTANTE: LibroRepository::create debe devolver el ID insertado
        $idLibro = $this->libroRepo->create($libro);

        // Autores seleccionados (puede venir vacío)
        $idsAutores = $_POST['autores'] ?? [];
        $this->libroAutorRepo->setAutoresForLibro($idLibro, $idsAutores);

        header('Location: index.php?c=libro&a=index');
        exit;
    }

    // FORMULARIO EDITAR LIBRO
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

    // ACTUALIZAR LIBRO EXISTENTE
    public function update(): void
    {
        $idLibro         = (int)($_POST['id_libro']       ?? 0);
        $titulo          = trim($_POST['titulo']          ?? '');
        $isbn            = trim($_POST['isbn']            ?? '');
        $anioPublicacion = (int)($_POST['anio_publicacion'] ?? 0);
        $idCategoria     = (int)($_POST['id_categoria']   ?? 0);
        $descripcion     = trim($_POST['descripcion']     ?? '');
        $stockTotal      = (int)($_POST['stock_total']    ?? 0);
        $stockDisp       = (int)($_POST['stock_disponible'] ?? $stockTotal);
        $estado          = trim($_POST['estado']          ?? 'DISPONIBLE');

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
            null     // fecha_registro: puedes conservar la anterior si tu modelo lo soporta
        );

        $this->libroRepo->update($libro);

        // Actualizar autores asociados
        $idsAutores = $_POST['autores'] ?? [];
        $this->libroAutorRepo->setAutoresForLibro($idLibro, $idsAutores);

        header('Location: index.php?c=libro&a=index');
        exit;
    }

    // ELIMINAR LIBRO
    public function delete(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id > 0) {
            // Primero borramos relaciones libro_autor
            $this->libroAutorRepo->deleteByLibro($id);
            // Luego borramos el libro
            $this->libroRepo->delete($id);
        }

        header('Location: index.php?c=libro&a=index');
        exit;
    }
}
