<?php
namespace App\Repositories;

use PDO;
use App\Config\Cnn;
use App\Models\Categoria;

class CategoriaRepository
{
    private PDO $db;

    public function __construct()
    {
        $cnn = new Cnn();
        $this->db = $cnn->getConnection();
    }

    /** @return Categoria[] */
    public function findAll(): array
    {
        $sql = "SELECT id_categoria, nombre, descripcion
                FROM categorias
                ORDER BY nombre ASC";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();

        $categorias = [];
        foreach ($rows as $row) {
            $categorias[] = new Categoria(
                (int)$row['id_categoria'],
                $row['nombre'],
                $row['descripcion'] ?? null
            );
        }
        return $categorias;
    }

    public function findById(int $id_categoria): ?Categoria
    {
        $sql = "SELECT id_categoria, nombre, descripcion
                FROM categorias
                WHERE id_categoria = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_categoria]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new Categoria(
            (int)$row['id_categoria'],
            $row['nombre'],
            $row['descripcion'] ?? null
        );
    }

    public function create(Categoria $categoria): int
    {
        $sql = "INSERT INTO categorias (nombre, descripcion)
                VALUES (:nombre, :descripcion)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nombre'      => $categoria->getNombre(),
            ':descripcion' => $categoria->getDescripcion()
        ]);

        $id = (int)$this->db->lastInsertId();
        $categoria->setIdCategoria($id);
        return $id;
    }

    public function update(Categoria $categoria): bool
    {
        if ($categoria->getIdCategoria() === null) {
            return false;
        }

        $sql = "UPDATE categorias
                SET nombre = :nombre,
                    descripcion = :descripcion
                WHERE id_categoria = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'      => $categoria->getNombre(),
            ':descripcion' => $categoria->getDescripcion(),
            ':id'          => $categoria->getIdCategoria()
        ]);
    }

    public function delete(int $id_categoria): bool
    {
        $sql = "DELETE FROM categorias WHERE id_categoria = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_categoria]);

        return $stmt->rowCount() > 0;
    }
}
