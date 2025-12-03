<?php
namespace App\Controllers;

use App\Repositories\PrestamoRepository;
use App\Repositories\UsuarioRepository;
use App\Repositories\LibroRepository;
use App\Models\Prestamo;

class PrestamoController
{
    private PrestamoRepository $prestamoRepo;
    private UsuarioRepository $usuarioRepo;
    private LibroRepository $libroRepo;

    public function __construct()
    {
        $this->prestamoRepo = new PrestamoRepository();
        $this->usuarioRepo  = new UsuarioRepository();
        $this->libroRepo    = new LibroRepository();
    }

    // LISTADO DE PRÉSTAMOS
    public function index(): void
    {
        $usuarioSesion = $_SESSION['usuario'] ?? null;
        $tipo          = $usuarioSesion['tipo'] ?? '';

        if ($tipo === 'ESTUDIANTE') {
            // El estudiante solo ve SUS préstamos
            $prestamos = $this->prestamoRepo->findByUsuario((int)$usuarioSesion['id']);
        } else {
            // ADMIN y BIBLIOTECARIO ven todos
            $prestamos = $this->prestamoRepo->findAll();
        }

        $usuarios  = $this->usuarioRepo->findAll();
        $libros    = $this->libroRepo->findAll();

        $usuariosMap = [];
        foreach ($usuarios as $u) {
            $usuariosMap[$u->getIdUsuario()] = $u;
        }

        $librosMap = [];
        foreach ($libros as $l) {
            $librosMap[$l->getIdLibro()] = $l;
        }

        $viewFile = __DIR__ . '/../../view/prestamos/listado.php';
        include __DIR__ . '/../../view/layout/main_layout.php';
    }

    // FORMULARIO NUEVO PRÉSTAMO
    public function create(): void
    {
        $usuarioSesion = $_SESSION['usuario'] ?? null;
        $tipo          = $usuarioSesion['tipo'] ?? '';

        if ($tipo === 'ESTUDIANTE') {
            $usuarios = []; // no se muestra lista, siempre será él mismo
        } else {
            $usuarios = $this->usuarioRepo->findAll();
        }

        $libros = $this->libroRepo->findAll();

        $prestamo = new Prestamo(
            null,
            $usuarioSesion ? (int)$usuarioSesion['id'] : 0,
            0,
            date('Y-m-d'),
            date('Y-m-d', strtotime('+7 days')),
            null,
            'PRESTADO',
            null
        );

        $viewFile = __DIR__ . '/../../view/prestamos/formulario.php';
        include __DIR__ . '/../../view/layout/main_layout.php';
    }

    // GUARDAR NUEVO PRÉSTAMO
    public function store(): void
    {
        $usuarioSesion = $_SESSION['usuario'] ?? null;
        $tipo          = $usuarioSesion['tipo'] ?? '';

        if ($tipo === 'ESTUDIANTE') {
            $idUsuario = (int)$usuarioSesion['id'];
        } else {
            $idUsuario = (int)($_POST['id_usuario'] ?? 0);
        }

        $idLibro         = (int)($_POST['id_libro'] ?? 0);
        $fechaPrestamo   = $_POST['fecha_prestamo'] ?? date('Y-m-d');
        $fechaDevolucion = $_POST['fecha_devolucion'] ?? date('Y-m-d', strtotime('+7 days'));
        $observaciones   = trim($_POST['observaciones'] ?? '');

        if ($idUsuario <= 0 || $idLibro <= 0) {
            header('Location: index.php?c=prestamo&a=index');
            exit;
        }

        $prestamo = new Prestamo(
            null,
            $idUsuario,
            $idLibro,
            $fechaPrestamo,
            $fechaDevolucion,
            null,
            'PRESTADO',
            $observaciones !== '' ? $observaciones : null
        );

        $this->prestamoRepo->create($prestamo);

        header('Location: index.php?c=prestamo&a=index');
        exit;
    }

    // FORMULARIO EDITAR PRÉSTAMO
    public function edit(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            header('Location: index.php?c=prestamo&a=index');
            exit;
        }

        $prestamo = $this->prestamoRepo->findById($id);
        if (!$prestamo) {
            header('Location: index.php?c=prestamo&a=index');
            exit;
        }

        $usuarios = $this->usuarioRepo->findAll();
        $libros   = $this->libroRepo->findAll();

        $viewFile = __DIR__ . '/../../view/prestamos/formulario.php';
        include __DIR__ . '/../../view/layout/main_layout.php';
    }

    // ACTUALIZAR PRÉSTAMO
    public function update(): void
    {
        $id              = (int)($_POST['id_prestamo'] ?? 0);
        $idUsuario       = (int)($_POST['id_usuario'] ?? 0);
        $idLibro         = (int)($_POST['id_libro'] ?? 0);
        $fechaPrestamo   = $_POST['fecha_prestamo'] ?? date('Y-m-d');
        $fechaDevolucion = $_POST['fecha_devolucion'] ?? date('Y-m-d', strtotime('+7 days'));
        $fechaDevuelto   = $_POST['fecha_devuelto'] ?: null;
        $estado          = $_POST['estado'] ?? 'PRESTADO';
        $observaciones   = trim($_POST['observaciones'] ?? '');

        if ($id <= 0 || $idUsuario <= 0 || $idLibro <= 0) {
            header('Location: index.php?c=prestamo&a=index');
            exit;
        }

        $prestamo = new Prestamo(
            $id,
            $idUsuario,
            $idLibro,
            $fechaPrestamo,
            $fechaDevolucion,
            $fechaDevuelto,
            $estado,
            $observaciones !== '' ? $observaciones : null
        );

        $this->prestamoRepo->update($prestamo);

        header('Location: index.php?c=prestamo&a=index');
        exit;
    }

    // MARCAR COMO DEVUELTO
    public function marcarDevuelto(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id > 0) {
            $this->prestamoRepo->marcarDevuelto($id, date('Y-m-d'), 'DEVUELTO');
        }

        header('Location: index.php?c=prestamo&a=index');
        exit;
    }

    // ELIMINAR PRÉSTAMO
    public function delete(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id > 0) {
            $this->prestamoRepo->delete($id);
        }

        header('Location: index.php?c=prestamo&a=index');
        exit;
    }
}
