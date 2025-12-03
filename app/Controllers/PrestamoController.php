namespace App\Controllers;

use App\Repositories\PrestamoRepository;
use App\Repositories\UsuarioRepository;
use App\Repositories\LibroRepository;
use App\Models\Prestamo;

class PrestamoController
{
    // ...

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

    public function create(): void
    {
        $usuarioSesion = $_SESSION['usuario'] ?? null;
        $tipo          = $usuarioSesion['tipo'] ?? '';

        // Si es ESTUDIANTE, solo se le permite seleccionar ÉL mismo como usuario
        if ($tipo === 'ESTUDIANTE') {
            $usuarios = []; // no se muestra lista de usuarios
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

    public function store(): void
    {
        $usuarioSesion = $_SESSION['usuario'] ?? null;
        $tipo          = $usuarioSesion['tipo'] ?? '';

        if ($tipo === 'ESTUDIANTE') {
            // El id_usuario siempre será el del estudiante logueado
            $idUsuario = (int)$usuarioSesion['id'];
        } else {
            // ADMIN / BIBLIOTECARIO pueden escoger usuario
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
}
