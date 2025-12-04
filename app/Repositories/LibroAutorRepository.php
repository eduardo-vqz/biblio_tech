<?php

namespace App\Repositories;

use App\Config\Cnn;
use App\Models\LibroAutor;
use App\Models\Autor;

class LibroAutorRepository
{
    private Cnn $cnn;

    public function __construct()
    {
        $this->cnn = new Cnn();
    }

    public function create(LibroAutor $la): void
    {
        $sql = "INSERT INTO libro_autor (id_libro, id_autor)
                VALUES (:id_libro, :id_autor)";
        $this->cnn->executeQuery($sql, [
            ':id_libro' => $la->getIdLibro(),
            ':id_autor' => $la->getIdAutor(),
        ]);
    }

    public function deleteByLibro(int $idLibro): void
    {
        $sql = "DELETE FROM libro_autor WHERE id_libro = :id_libro";
        $this->cnn->executeQuery($sql, [':id_libro' => $idLibro]);
    }

    public function findAutoresIdsByLibro(int $idLibro): array
    {
        $sql = "SELECT id_autor FROM libro_autor WHERE id_libro = :id_libro";
        $rows = $this->cnn->fetchQuery($sql, [':id_libro' => $idLibro]);

        return array_map(static fn($r) => (int)$r['id_autor'], $rows);
    }

    /**
     * Para mostrar autores en el listado de libros (opcional)
     */
    public function findAutoresByLibro(int $idLibro): array
    {
        // OJO: aquí ya NO usamos a.biografia ni a.fecha_registro
        $sql = "SELECT a.id_autor, a.nombre, a.apellido
            FROM autores a
            INNER JOIN libro_autor la ON la.id_autor = a.id_autor
            WHERE la.id_libro = :id_libro
            ORDER BY a.nombre, a.apellido";

        $rows = $this->cnn->fetchQuery($sql, [':id_libro' => $idLibro]);

        $result = [];
        foreach ($rows as $row) {
            // Ajusta esto a la firma real de tu modelo Autor
            $result[] = new \App\Models\Autor(
                isset($row['id_autor']) ? (int)$row['id_autor'] : null,
                $row['nombre']   ?? '',
                $row['apellido'] ?? ''
            );
        }
        return $result;
    }


    public function setAutoresForLibro(int $idLibro, array $idsAutores): void
    {
        $this->deleteByLibro($idLibro);

        foreach ($idsAutores as $idAutor) {
            $idAutor = (int)$idAutor;
            if ($idAutor <= 0) continue;

            $la = new LibroAutor($idLibro, $idAutor);
            $this->create($la);
        }
    }
}
