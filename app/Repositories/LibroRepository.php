<?php
namespace App\Repositories;

use App\Config\Cnn;
use App\Models\Libro;
use PDO;

class LibroRepository
{
    private Cnn $cnn;

    public function __construct()
    {
        $this->cnn = new Cnn();
    }

    /**
     * Convierte un row de la BD en un objeto Libro
     */
    private function mapRowToLibro(array $row): Libro
    {
        $libro = new Libro(
            isset($row['id_libro']) ? (int)$row['id_libro'] : null,
            $row['titulo']           ?? '',
            $row['isbn']             ?? '',
            isset($row['anio_publicacion']) ? (int)$row['anio_publicacion'] : 0,
            isset($row['id_categoria']) ? (int)$row['id_categoria'] : 0,
            $row['descripcion']      ?? null,
            isset($row['stock_total']) ? (int)$row['stock_total'] : 0,
            isset($row['stock_disponible']) ? (int)$row['stock_disponible'] : 0,
            $row['estado']           ?? 'DISPONIBLE',
            $row['fecha_registro']   ?? null
        );

        // Si viene el nombre de la categoría desde un JOIN
        if (isset($row['nombre_categoria'])) {
            $libro->setNombreCategoria($row['nombre_categoria']);
        } elseif (isset($row['nombre'])) {
            // Por si el alias quedó como "nombre"
            $libro->setNombreCategoria($row['nombre']);
        }

        return $libro;
    }

    /**
     * Obtener todos los libros (con nombre de categoría si se hace JOIN)
     */
    public function findAll(): array
    {
        $sql = "SELECT 
                    l.id_libro,
                    l.titulo,
                    l.isbn,
                    l.anio_publicacion,
                    l.id_categoria,
                    l.descripcion,
                    l.stock_total,
                    l.stock_disponible,
                    l.estado,
                    l.fecha_registro,
                    c.nombre AS nombre_categoria
                FROM libros l
                INNER JOIN categorias c ON c.id_categoria = l.id_categoria
                ORDER BY l.id_libro DESC";

        $rows = $this->cnn->fetchQuery($sql);

        $result = [];
        foreach ($rows as $row) {
            $result[] = $this->mapRowToLibro($row);
        }
        return $result;
    }

    /**
     * Buscar un libro por ID
     */
    public function findById(int $id): ?Libro
    {
        $sql = "SELECT 
                    l.id_libro,
                    l.titulo,
                    l.isbn,
                    l.anio_publicacion,
                    l.id_categoria,
                    l.descripcion,
                    l.stock_total,
                    l.stock_disponible,
                    l.estado,
                    l.fecha_registro,
                    c.nombre AS nombre_categoria
                FROM libros l
                INNER JOIN categorias c ON c.id_categoria = l.id_categoria
                WHERE l.id_libro = :id_libro
                LIMIT 1";

        $rows = $this->cnn->fetchQuery($sql, [':id_libro' => $id]);

        if (empty($rows)) {
            return null;
        }

        return $this->mapRowToLibro($rows[0]);
    }

    /**
     * Crear un nuevo libro y devolver el ID insertado
     */
    public function create(Libro $libro): int
    {
        $sql = "INSERT INTO libros
                    (titulo, isbn, anio_publicacion, id_categoria, descripcion,
                     stock_total, stock_disponible, estado, fecha_registro)
                VALUES
                    (:titulo, :isbn, :anio_publicacion, :id_categoria, :descripcion,
                     :stock_total, :stock_disponible, :estado, NOW())";

        /** @var PDO $pdo */
        $pdo = $this->cnn->getConnection();
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':titulo'           => $libro->getTitulo(),
            ':isbn'             => $libro->getIsbn(),
            ':anio_publicacion' => $libro->getAnioPublicacion(),
            ':id_categoria'     => $libro->getIdCategoria(),          // ← ya NO getCategoria()
            ':descripcion'      => $libro->getDescripcion(),
            ':stock_total'      => $libro->getStockTotal(),
            ':stock_disponible' => $libro->getStockDisponible(),
            ':estado'           => $libro->getEstado(),
        ]);

        return (int)$pdo->lastInsertId();
    }

    /**
     * Actualizar un libro existente
     */
    public function update(Libro $libro): void
    {
        if ($libro->getIdLibro() === null) {
            throw new \RuntimeException("No se puede actualizar un libro sin ID.");
        }

        $sql = "UPDATE libros
                SET
                    titulo           = :titulo,
                    isbn             = :isbn,
                    anio_publicacion = :anio_publicacion,
                    id_categoria     = :id_categoria,
                    descripcion      = :descripcion,
                    stock_total      = :stock_total,
                    stock_disponible = :stock_disponible,
                    estado           = :estado
                WHERE id_libro = :id_libro";

        $this->cnn->executeQuery($sql, [
            ':titulo'           => $libro->getTitulo(),
            ':isbn'             => $libro->getIsbn(),
            ':anio_publicacion' => $libro->getAnioPublicacion(),
            ':id_categoria'     => $libro->getIdCategoria(),          // ← aquí también
            ':descripcion'      => $libro->getDescripcion(),
            ':stock_total'      => $libro->getStockTotal(),
            ':stock_disponible' => $libro->getStockDisponible(),
            ':estado'           => $libro->getEstado(),
            ':id_libro'         => $libro->getIdLibro(),
        ]);
    }

    /**
     * Eliminar un libro por ID
     */
    public function delete(int $id): void
    {
        $sql = "DELETE FROM libros WHERE id_libro = :id_libro";
        $this->cnn->executeQuery($sql, [':id_libro' => $id]);
    }
}
