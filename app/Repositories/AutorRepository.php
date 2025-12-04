<?php

namespace App\Repositories;

use App\Config\Cnn;
use App\Models\Autor;

class AutorRepository
{
    private Cnn $cnn;

    public function __construct()
    {
        $this->cnn = new Cnn();
    }

    private function mapRowToAutor(array $row): Autor
    {
        // Ajusta a la firma real de tu modelo Autor
        return new Autor(
            isset($row['id_autor']) ? (int)$row['id_autor'] : null,
            $row['nombre']   ?? '',
            $row['apellido'] ?? ''
        );
    }

    public function findAll(): array
    {
        $sql  = "SELECT id_autor, nombre, apellido FROM autores ORDER BY nombre, apellido";
        $rows = $this->cnn->fetchQuery($sql);

        $result = [];
        foreach ($rows as $row) {
            $result[] = $this->mapRowToAutor($row);
        }
        return $result;
    }

    public function findById(int $id): ?Autor
    {
        $sql  = "SELECT id_autor, nombre, apellido FROM autores WHERE id_autor = :id LIMIT 1";
        $rows = $this->cnn->fetchQuery($sql, [':id' => $id]);

        if (empty($rows)) {
            return null;
        }
        return $this->mapRowToAutor($rows[0]);
    }
}
