<?php
namespace App\Repositories;

use PDO;
use RuntimeException;
use App\Config\Cnn;
use App\Models\Libro;
use App\Models\Categoria;

class LibroRepository
{
    private PDO $db;

    public function __construct()
    {
        $cnn = new Cnn();
        $this->db = $cnn->getConnection();
    }

    // ==================================================
    //   MAPEADOR FILA → MODELOS (Libro + Categoria)
    // ==================================================
    private function mapRowToLibro(array $row): Libro
    {
        // Construimos la categoría solo si hay datos
        $categoria = null;
        if (isset($row['id_categoria']) && $row['id_categoria'] !== null) {
            $categoria = new Categoria(
                (int)$row['id_categoria'],
                $row['categoria_nombre'] ?? '',
                $row['categoria_descripcion'] ?? null
            );
        }

        return new Libro(
            isset($row['id_libro']) ? (int)$row['id_libro'] : null,
            $row['titulo'],
            $row['isbn'] ?? null,
            isset($row['id_categoria']) ? (int)$row['id_categoria'] : null,
            $categoria,
            (int)$row['stock_total'],
            (int)$row['stock_disponible'],
            $row['estado'],
            $row['descripcion'] ?? null,
            isset($row['anio_publicacion']) && $row['anio_publicacion'] !== null
                ? (int)$row['anio_publicacion']
                : null,
            $row['fecha_registro'] ?? null
        );
    }

    // ==================================================
    //   OBTENER TODOS LOS LIBROS (JOIN CATEGORÍAS)
    // ==================================================
    /**
     * @return Libro[]
     */
    public function findAll(): array
    {
        $sql = "SELECT
                    l.id_libro,
                    l.titulo,
                    l.isbn,
                    l.anio_publicacion,
                    l.id_categoria,
                    c.nombre      AS categoria_nombre,
                    c.descripcion AS categoria_descripcion,
                    l.descripcion,
                    l.stock_total,
                    l.stock_disponible,
                    l.estado,
                    l.fecha_registro
                FROM libros l
                LEFT JOIN categorias c ON c.id_categoria = l.id_categoria
                ORDER BY l.titulo ASC";

        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();

        $libros = [];
        foreach ($rows as $row) {
            $libros[] = $this->mapRowToLibro($row);
        }
        return $libros;
    }

    // ==================================================
    //   BUSCAR LIBRO POR ID (JOIN CATEGORÍAS)
    // ==================================================
    public function findById(int $id_libro): ?Libro
    {
        $sql = "SELECT
                    l.id_libro,
                    l.titulo,
                    l.isbn,
                    l.anio_publicacion,
                    l.id_categoria,
                    c.nombre      AS categoria_nombre,
                    c.descripcion AS categoria_descripcion,
                    l.descripcion,
                    l.stock_total,
                    l.stock_disponible,
                    l.estado,
                    l.fecha_registro
                FROM libros l
                LEFT JOIN categorias c ON c.id_categoria = l.id_categoria
                WHERE l.id_libro = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_libro]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return $this->mapRowToLibro($row);
    }

    // ==================================================
    //   BÚSQUEDA POR TÍTULO Y/O CATEGORÍA
    // ==================================================
    /**
     * @return Libro[]
     */
    public function search(?string $titulo = null, ?int $id_categoria = null): array
    {
        $where  = [];
        $params = [];

        if ($titulo !== null && $titulo !== '') {
            $where[] = "l.titulo LIKE :titulo";
            $params[':titulo'] = '%' . $titulo . '%';
        }

        if ($id_categoria !== null) {
            $where[] = "l.id_categoria = :id_categoria";
            $params[':id_categoria'] = $id_categoria;
        }

        $sql = "SELECT
                    l.id_libro,
                    l.titulo,
                    l.isbn,
                    l.anio_publicacion,
                    l.id_categoria,
                    c.nombre      AS categoria_nombre,
                    c.descripcion AS categoria_descripcion,
                    l.descripcion,
                    l.stock_total,
                    l.stock_disponible,
                    l.estado,
                    l.fecha_registro
                FROM libros l
                LEFT JOIN categorias c ON c.id_categoria = l.id_categoria";

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY l.titulo ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        $libros = [];
        foreach ($rows as $row) {
            $libros[] = $this->mapRowToLibro($row);
        }
        return $libros;
    }

    // ==================================================
    //   CREAR LIBRO (INSERT)
    // ==================================================
    public function create(Libro $libro): int
    {
        $sql = "INSERT INTO libros
                    (titulo, isbn, anio_publicacion, id_categoria, descripcion,
                     stock_total, stock_disponible, estado)
                VALUES
                    (:titulo, :isbn, :anio_publicacion, :id_categoria, :descripcion,
                     :stock_total, :stock_disponible, :estado)";

        $stmt = $this->db->prepare($sql);

        $anio = $libro->getAnioPublicacion();
        $categoria = $libro->getCategoria();

        $stmt->execute([
            ':titulo'           => $libro->getTitulo(),
            ':isbn'             => $libro->getIsbn(),
            ':anio_publicacion' => $anio !== null ? $anio : null,
            ':id_categoria'     => $categoria?->getIdCategoria() ?? $libro->getIdCategoria(),
            ':descripcion'      => $libro->getDescripcion(),
            ':stock_total'      => $libro->getStockTotal(),
            ':stock_disponible' => $libro->getStockDisponible(),
            ':estado'           => $libro->getEstado(),
        ]);

        $id = (int)$this->db->lastInsertId();
        $libro->setIdLibro($id);
        return $id;
    }

    // ==================================================
    //   ACTUALIZAR LIBRO (UPDATE)
    // ==================================================
    public function update(Libro $libro): bool
    {
        if ($libro->getIdLibro() === null) {
            throw new RuntimeException("No se puede actualizar un libro sin ID.");
        }

        $sql = "UPDATE libros SET
                    titulo           = :titulo,
                    isbn             = :isbn,
                    anio_publicacion = :anio_publicacion,
                    id_categoria     = :id_categoria,
                    descripcion      = :descripcion,
                    stock_total      = :stock_total,
                    stock_disponible = :stock_disponible,
                    estado           = :estado
                WHERE id_libro = :id_libro";

        $stmt = $this->db->prepare($sql);

        $anio = $libro->getAnioPublicacion();
        $categoria = $libro->getCategoria();

        return $stmt->execute([
            ':titulo'           => $libro->getTitulo(),
            ':isbn'             => $libro->getIsbn(),
            ':anio_publicacion' => $anio !== null ? $anio : null,
            ':id_categoria'     => $categoria?->getIdCategoria() ?? $libro->getIdCategoria(),
            ':descripcion'      => $libro->getDescripcion(),
            ':stock_total'      => $libro->getStockTotal(),
            ':stock_disponible' => $libro->getStockDisponible(),
            ':estado'           => $libro->getEstado(),
            ':id_libro'         => $libro->getIdLibro(),
        ]);
    }

    // ==================================================
    //   ELIMINAR LIBRO (DELETE)
    // ==================================================
    public function delete(int $id_libro): bool
    {
        $sql = "DELETE FROM libros WHERE id_libro = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_libro]);

        return $stmt->rowCount() > 0;
    }

    // ==================================================
    //   ACTUALIZAR SOLO STOCK DISPONIBLE
    // ==================================================
    public function actualizarStockDisponible(int $id_libro, int $nuevoStock): bool
    {
        $sql = "UPDATE libros 
                SET stock_disponible = :stock_disponible
                WHERE id_libro = :id_libro";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':stock_disponible' => $nuevoStock,
            ':id_libro'         => $id_libro
        ]);
    }
}
