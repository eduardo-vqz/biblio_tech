<?php
namespace App\Repositories;

use PDO;
use App\Config\Cnn;
use App\Models\AutorLibro;
use App\Models\Autor;
use App\Models\Libro;

class AutorLibroRepository
{
    private PDO $db;

    public function __construct()
    {
        $cnn = new Cnn();
        $this->db = $cnn->getConnection();
    }

    // ======================================================
    //   OBTENER AUTORES RELACIONADOS A UN LIBRO
    // ======================================================
    /** @return Autor[] */
    public function findAutoresByLibro(int $id_libro): array
    {
        $sql = "SELECT autores.id_autor, autores.nombre, autores.apellido, autores.nacionalidad
                FROM libro_autor
                INNER JOIN autores a ON autores.id_autor = libro_autor.id_autor
                WHERE libro_autor.id_libro = :id_libro
                ORDER BY autores.apellido ASC, autores.nombre ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_libro' => $id_libro]);
        $rows = $stmt->fetchAll();

        $autores = [];
        foreach ($rows as $row) {
            $autores[] = new Autor(
                (int)$row['id_autor'],
                $row['nombre'],
                $row['apellido'],
                $row['nacionalidad'] ?? null
            );
        }
        return $autores;
    }

    // ======================================================
    //   OBTENER LIBROS RELACIONADOS A UN AUTOR
    // ======================================================
    /** @return Libro[] */
    public function findLibrosByAutor(int $id_autor): array
    {
        $sql = "SELECT libros.id_libro, libros.titulo, libros.isbn, libros.anio_publicacion,
                       libros.id_categoria, libros.descripcion, libros.stock_total,
                       libros.stock_disponible, libros.estado, libros.fecha_registro
                FROM libro_autor
                INNER JOIN libros ON libros.id_libro = libro_autor.id_libro
                WHERE libro_autor.id_autor = :id_autor
                ORDER BY libros.titulo ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_autor' => $id_autor]);
        $rows = $stmt->fetchAll();

        $libros = [];
        foreach ($rows as $row) {
            $libros[] = new Libro(
                (int)$row['id_libro'],
                $row['titulo'],
                $row['isbn'] ?? null,
                isset($row['id_categoria']) ? (int)$row['id_categoria'] : null,
                null, // Categoria se llena desde LibroRepository
                (int)$row['stock_total'],
                (int)$row['stock_disponible'],
                $row['estado'],
                $row['descripcion'] ?? null,
                isset($row['anio_publicacion']) ? (int)$row['anio_publicacion'] : null,
                $row['fecha_registro'] ?? null
            );
        }
        return $libros;
    }

    // ======================================================
    //   AGREGAR RELACIÓN (INSERT)
    // ======================================================
    public function addRelation(int $id_libro, int $id_autor): bool
    {
        $sql = "INSERT INTO libro_autor (id_libro, id_autor)
                VALUES (:id_libro, :id_autor)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_libro' => $id_libro,
            ':id_autor' => $id_autor
        ]);
    }

    // ======================================================
    //   ELIMINAR RELACIÓN (DELETE)
    // ======================================================
    public function deleteRelation(int $id_libro, int $id_autor): bool
    {
        $sql = "DELETE FROM libro_autor
                WHERE id_libro = :id_libro AND id_autor = :id_autor";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_libro' => $id_libro,
            ':id_autor' => $id_autor
        ]);

        return $stmt->rowCount() > 0;
    }

    // ======================================================
    //   ELIMINAR TODAS LAS RELACIONES DE UN LIBRO
    // ======================================================
    public function deleteRelationsByLibro(int $id_libro): bool
    {
        $sql = "DELETE FROM libro_autor WHERE id_libro = :id_libro";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_libro' => $id_libro]);
        return $stmt->rowCount() >= 0;
    }

    // ======================================================
    //   REEMPLAZAR RELACIONES (para formularios)
    // ======================================================
    public function replaceRelationsForLibro(int $id_libro, array $autoresIds): void
    {
        $this->deleteRelationsByLibro($id_libro);

        foreach ($autoresIds as $id_autor) {
            $this->addRelation($id_libro, $id_autor);
        }
    }
}
