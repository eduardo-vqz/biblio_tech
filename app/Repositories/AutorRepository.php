<?php
namespace App\Repositories;

use PDO;
use App\Config\Cnn;
use App\Models\Autor;

class AutorRepository
{
    private PDO $db;

    public function __construct()
    {
        $cnn = new Cnn();
        $this->db = $cnn->getConnection();
    }

    /** @return Autor[] */
    public function findAll(): array
    {
        $sql = "SELECT id_autor, nombre, apellido, nacionalidad
                FROM autores
                ORDER BY apellido ASC, nombre ASC";
        $stmt = $this->db->query($sql);
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

    public function findById(int $id_autor): ?Autor
    {
        $sql = "SELECT id_autor, nombre, apellido, nacionalidad
                FROM autores
                WHERE id_autor = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_autor]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new Autor(
            (int)$row['id_autor'],
            $row['nombre'],
            $row['apellido'],
            $row['nacionalidad'] ?? null
        );
    }

    public function create(Autor $autor): int
    {
        $sql = "INSERT INTO autores (nombre, apellido, nacionalidad)
                VALUES (:nombre, :apellido, :nacionalidad)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nombre'       => $autor->getNombre(),
            ':apellido'     => $autor->getApellido(),
            ':nacionalidad' => $autor->getNacionalidad()
        ]);

        $id = (int)$this->db->lastInsertId();
        $autor->setIdAutor($id);
        return $id;
    }

    public function update(Autor $autor): bool
    {
        if ($autor->getIdAutor() === null) {
            return false;
        }

        $sql = "UPDATE autores
                SET nombre = :nombre,
                    apellido = :apellido,
                    nacionalidad = :nacionalidad
                WHERE id_autor = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'       => $autor->getNombre(),
            ':apellido'     => $autor->getApellido(),
            ':nacionalidad' => $autor->getNacionalidad(),
            ':id'           => $autor->getIdAutor()
        ]);
    }

    public function delete(int $id_autor): bool
    {
        $sql = "DELETE FROM autores WHERE id_autor = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_autor]);

        return $stmt->rowCount() > 0;
    }
}
