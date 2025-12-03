<?php
namespace App\Repositories;

use App\Config\Cnn;
use App\Models\LibroAutor;

class LibroAutorRepository
{
    private Cnn $cnn;

    public function __construct()
    {
        $this->cnn = new Cnn();
    }

    // Guarda la relación libro–autor
    public function create(LibroAutor $la): void
    {
        $sql = "INSERT INTO libro_autor (id_libro, id_autor) VALUES (:id_libro, :id_autor)";
        $this->cnn->executeQuery($sql, [
            ':id_libro' => $la->getIdLibro(),
            ':id_autor' => $la->getIdAutor(),
        ]);
    }

    // Elimina TODAS las relaciones de un libro
    public function deleteByLibro(int $idLibro): void
    {
        $sql = "DELETE FROM libro_autor WHERE id_libro = :id_libro";
        $this->cnn->executeQuery($sql, [':id_libro' => $idLibro]);
    }

    // Devuelve los IDs de autores de un libro
    public function findAutoresIdsByLibro(int $idLibro): array
    {
        $sql = "SELECT id_autor FROM libro_autor WHERE id_libro = :id_libro";
        $rows = $this->cnn->fetchQuery($sql, [':id_libro' => $idLibro]);

        return array_map(static fn($r) => (int)$r['id_autor'], $rows);
    }

    // “Setea” autores de un libro (borra y vuelve a insertar)
    public function setAutoresForLibro(int $idLibro, array $idsAutores): void
    {
        $this->deleteByLibro($idLibro);

        foreach ($idsAutores as $idAutor) {
            $idAutor = (int)$idAutor;
            if ($idAutor <= 0) continue;

            $la = new LibroAutor(null, $idLibro, $idAutor);
            $this->create($la);
        }
    }
}
