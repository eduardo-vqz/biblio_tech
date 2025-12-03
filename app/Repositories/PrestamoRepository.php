<?php
namespace App\Repositories;

use PDO;
use App\Config\Cnn;
use App\Models\Prestamo;

class PrestamoRepository
{
    private PDO $db;

    public function __construct()
    {
        $cnn = new Cnn();
        $this->db = $cnn->getConnection();
    }

    // ==========================================
    //   MAPEADOR FILA → MODELO Prestamo
    // ==========================================
    private function mapRowToPrestamo(array $row): Prestamo
    {
        return new Prestamo(
            isset($row['id_prestamo']) ? (int)$row['id_prestamo'] : null,
            (int)$row['id_usuario'],
            (int)$row['id_libro'],
            $row['fecha_prestamo'],
            $row['fecha_devolucion'],
            $row['fecha_devuelto'] ?? null,
            $row['estado'],
            $row['observaciones'] ?? null
        );
    }

    // ==========================================
    //   OBTENER TODOS LOS PRÉSTAMOS
    // ==========================================
    /**
     * @return Prestamo[]
     */
    public function findAll(): array
    {
        $sql = "SELECT id_prestamo, id_usuario, id_libro,
                       fecha_prestamo, fecha_devolucion, fecha_devuelto,
                       estado, observaciones
                FROM prestamos
                ORDER BY fecha_prestamo DESC, id_prestamo DESC";

        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();

        $prestamos = [];
        foreach ($rows as $row) {
            $prestamos[] = $this->mapRowToPrestamo($row);
        }

        return $prestamos;
    }

    // ==========================================
    //   BUSCAR POR ID
    // ==========================================
    public function findById(int $id_prestamo): ?Prestamo
    {
        $sql = "SELECT id_prestamo, id_usuario, id_libro,
                       fecha_prestamo, fecha_devolucion, fecha_devuelto,
                       estado, observaciones
                FROM prestamos
                WHERE id_prestamo = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_prestamo]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return $this->mapRowToPrestamo($row);
    }

    // ==========================================
    //   PRÉSTAMOS POR USUARIO
    // ==========================================
    /**
     * @return Prestamo[]
     */
    public function findByUsuario(int $id_usuario): array
    {
        $sql = "SELECT id_prestamo, id_usuario, id_libro,
                       fecha_prestamo, fecha_devolucion, fecha_devuelto,
                       estado, observaciones
                FROM prestamos
                WHERE id_usuario = :id_usuario
                ORDER BY fecha_prestamo DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_usuario' => $id_usuario]);
        $rows = $stmt->fetchAll();

        $prestamos = [];
        foreach ($rows as $row) {
            $prestamos[] = $this->mapRowToPrestamo($row);
        }

        return $prestamos;
    }

    // ==========================================
    //   PRÉSTAMOS POR LIBRO
    // ==========================================
    /**
     * @return Prestamo[]
     */
    public function findByLibro(int $id_libro): array
    {
        $sql = "SELECT id_prestamo, id_usuario, id_libro,
                       fecha_prestamo, fecha_devolucion, fecha_devuelto,
                       estado, observaciones
                FROM prestamos
                WHERE id_libro = :id_libro
                ORDER BY fecha_prestamo DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_libro' => $id_libro]);
        $rows = $stmt->fetchAll();

        $prestamos = [];
        foreach ($rows as $row) {
            $prestamos[] = $this->mapRowToPrestamo($row);
        }

        return $prestamos;
    }

    // ==========================================
    //   PRÉSTAMOS ACTIVOS (PRESTADO o ATRASADO)
    // ==========================================
    /**
     * @return Prestamo[]
     */
    public function findActivos(): array
    {
        $sql = "SELECT id_prestamo, id_usuario, id_libro,
                       fecha_prestamo, fecha_devolucion, fecha_devuelto,
                       estado, observaciones
                FROM prestamos
                WHERE estado IN ('PRESTADO', 'ATRASADO')
                ORDER BY fecha_prestamo DESC";

        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();

        $prestamos = [];
        foreach ($rows as $row) {
            $prestamos[] = $this->mapRowToPrestamo($row);
        }

        return $prestamos;
    }

    // ==========================================
    //   CREAR PRÉSTAMO
    // ==========================================
    public function create(Prestamo $prestamo): int
    {
        $sql = "INSERT INTO prestamos
                    (id_usuario, id_libro, fecha_prestamo, fecha_devolucion,
                     fecha_devuelto, estado, observaciones)
                VALUES
                    (:id_usuario, :id_libro, :fecha_prestamo, :fecha_devolucion,
                     :fecha_devuelto, :estado, :observaciones)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_usuario'       => $prestamo->getIdUsuario(),
            ':id_libro'         => $prestamo->getIdLibro(),
            ':fecha_prestamo'   => $prestamo->getFechaPrestamo(),
            ':fecha_devolucion' => $prestamo->getFechaDevolucion(),
            ':fecha_devuelto'   => $prestamo->getFechaDevuelto(),
            ':estado'           => $prestamo->getEstado(),
            ':observaciones'    => $prestamo->getObservaciones()
        ]);

        $id = (int)$this->db->lastInsertId();
        $prestamo->setIdPrestamo($id);
        return $id;
    }

    // ==========================================
    //   ACTUALIZAR PRÉSTAMO COMPLETO
    // ==========================================
    public function update(Prestamo $prestamo): bool
    {
        if ($prestamo->getIdPrestamo() === null) {
            // aquí podrías lanzar excepción si lo prefieres
            return false;
        }

        $sql = "UPDATE prestamos SET
                    id_usuario       = :id_usuario,
                    id_libro         = :id_libro,
                    fecha_prestamo   = :fecha_prestamo,
                    fecha_devolucion = :fecha_devolucion,
                    fecha_devuelto   = :fecha_devuelto,
                    estado           = :estado,
                    observaciones    = :observaciones
                WHERE id_prestamo = :id_prestamo";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_usuario'       => $prestamo->getIdUsuario(),
            ':id_libro'         => $prestamo->getIdLibro(),
            ':fecha_prestamo'   => $prestamo->getFechaPrestamo(),
            ':fecha_devolucion' => $prestamo->getFechaDevolucion(),
            ':fecha_devuelto'   => $prestamo->getFechaDevuelto(),
            ':estado'           => $prestamo->getEstado(),
            ':observaciones'    => $prestamo->getObservaciones(),
            ':id_prestamo'      => $prestamo->getIdPrestamo()
        ]);
    }

    // ==========================================
    //   MARCAR COMO DEVUELTO
    // ==========================================
    public function marcarDevuelto(int $id_prestamo, string $fecha_devuelto, string $estadoFinal = 'DEVUELTO'): bool
    {
        $sql = "UPDATE prestamos
                SET fecha_devuelto = :fecha_devuelto,
                    estado         = :estado
                WHERE id_prestamo = :id_prestamo";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':fecha_devuelto' => $fecha_devuelto,
            ':estado'         => $estadoFinal,
            ':id_prestamo'    => $id_prestamo
        ]);
    }

    // ==========================================
    //   ACTUALIZAR SOLO ESTADO
    // ==========================================
    public function actualizarEstado(int $id_prestamo, string $estado): bool
    {
        $sql = "UPDATE prestamos
                SET estado = :estado
                WHERE id_prestamo = :id_prestamo";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':estado'      => $estado,
            ':id_prestamo' => $id_prestamo
        ]);
    }

    // ==========================================
    //   ELIMINAR PRÉSTAMO
    // ==========================================
    public function delete(int $id_prestamo): bool
    {
        $sql = "DELETE FROM prestamos WHERE id_prestamo = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_prestamo]);

        return $stmt->rowCount() > 0;
    }
}
